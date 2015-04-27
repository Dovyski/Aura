<?php

	/**
	 * Funções do cliente.
	 */

	define('AURA_CLI_VERSION', '2.0.0');

	function loadConfigFile() {
		$aIniArray = parse_ini_file(dirname(__FILE__) . "/config.ini");

		define('AURA_BRAIN_URL', 			$aIniArray['brain_url']);
		define('AURA_PING_INTERVAL', 		$aIniArray['brain_pulling_interval']);
		define('AURA_LOG_REQUESTS', 		$aIniArray['log_requests'] ? true : false);
		define('AURA_LOG_EXECS', 			$aIniArray['log_execs'] ? true : false);
	}

	function getUrl($theUrl) {
		static $aCh = null;
		$aUserAgent = 'Aura Client/'.AURA_CLI_VERSION.' ('.AURA_OS_NAME.'; '.AURA_OS_VERSION.')';

		if(AURA_LOG_REQUESTS) {
			logMsg('[URL] ' . $theUrl);
		}

		if($aCh == null) {
			$aCh = curl_init();
			curl_setopt($aCh, CURLOPT_SSL_VERIFYPEER, 	false);
			curl_setopt($aCh, CURLOPT_USERAGENT, 		$aUserAgent);
			curl_setopt($aCh, CURLOPT_RETURNTRANSFER, 	1);
			curl_setopt($aCh, CURLOPT_CONNECTTIMEOUT, 	10);
			curl_setopt($aCh, CURLOPT_TIMEOUT, 			10);
			curl_setopt($aCh, CURLOPT_DNS_CACHE_TIMEOUT,3600);
			curl_setopt($aCh, CURLOPT_FAILONERROR, 		1);
		}

		curl_setopt($aCh, CURLOPT_URL, $theUrl);

		$aResult = curl_exec($aCh);
		$aRet	 = $aResult === false ? false : $aResult;

		if($aRet === false) {
			logMsg('[CURL_E] ' . curl_error($aCh));
		}

		return $aRet;
	}

	function logMsg($theMsg) {
		echo date('[h:i:s d/m/Y]') . " ".$theMsg . "\n";
	}

	/**
	 * Analisa uma ordem do cérebro (que pode possuir mais de um OS relacionado) e gera um comando
	 * que pode ser executado no OS atual. Os comandos devem vir no seguinte formato:
	 *
	 * array(
	 * 		'win' 	=> 'versão windows do comando'
	 * 		'mac' 	=> 'versão mac do comando'
	 * 		'linux' => 'versão linux do comando'
	 * 	)
	 * @param array $theCommand comando a ser executado, indexado pela plataforma, que poder 'win', 'mac' ou 'linux'.
	 * @return string comando a ser executado, ou uma string vazia, se não conseguiu decodificar o que o cérebro enviou.s
	 */
	function getCommandAccordingOS($theCommand) {
		$aCommand = '';

		if(is_string($theCommand)) {
			$aVet 		= @unserialize($theCommand);
			$aCommand 	= isset($aVet[AURA_OS]) ? $aVet[AURA_OS] : '';
		}

		if(is_object($theCommand)) {
			$aProp		= AURA_OS;
			$aCommand 	= isset($theCommand->$aProp) ? $theCommand->$aProp : '';
		}

		return $aCommand;
	}

	/**
	 * Verifica se uma tarefa possui um comando único para ser executado ou se, na verdade, existe
	 * um lote de comandos associados a ela.
	 *
	 * @param array $theTask dados da tarefa que terá seus comandos enfileirados.
	 * @return boolean <code>true</code> se a tarefa possui um lote de comandos (vários comandos), ou false se ela possui um único comando pronto para ser executado.
	 */
	function hasBatchCommands($theTask) {
		$aCommand = getCommandAccordingOS($theTask->exec);
		return is_array(@unserialize($aCommand));
	}

	/**
	 * Coloca em uma fila os comandos de um determinada tarefa, assim os comandos serão executados
	 * de forma "offline".
	 *
	 * @param array $theTask dados da tarefa que terá seus comandos enfileirados.
	 */
	function enqueBatchCommands($theTask) {
		$aCommand = @unserialize(getCommandAccordingOS($theTask->exec));

		if($aCommand !== false) {
			$aCount = count($aCommand);
			logMsg('Enfileirando '.$aCount.' comandos da tarefa '.$theTask->id.' para execucao posterior.');

			for($i = 0; $i < $aCount; $i++) {
				file_put_contents(dirname(__FILE__).'/bc-'.$theTask->id.'-'.$i, $aCommand[$i]);
			}
		} else {
			logMsg('Nao foi possivel enfileirar comandos da tarefa '.$theTask->id);
		}
	}

	/**
	 * Moe os comandos que foram salvos no disco para serem executados de forma
	 * sequencial. Esses comandos são ditos batch porque podem ser muitos e podem
	 * ser executados depois de um reboot, por exemplo.
	 *
	 * Os comandos ficam guardados como arquivo na mesma pasta do aura-cli, com o formato bc-$taskId-$num.
	 */
	function processEnquedBatchCommands() {
		$aTasks = array();

		foreach (glob(dirname(__FILE__).'/bc-*') as $aFile) {
			$aTemp		= explode('-', $aFile);
			$aIdTask 	= $aTemp[1];
			$aIdCommand = $aTemp[2];

			logMsg('Batch adicionado: '.$aFile);

			$aTasks[$aIdTask][] = array(
				'file'		=> $aFile,
				'content' 	=> file_get_contents($aFile),
				'id'		=> $aIdCommand
			);
		}

		if(count($aTasks) > 0) {
			logMsg('Um total de '.count($aTasks).' tarefas com comandos batch foram encontradas no disco.');

			foreach($aTasks as $aId => $aCommands) {
				foreach($aCommands as $aCom) {
					unlink($aCom['file']);
					$aOut = runCommand($aCom['content'], false);

					logMsg('Comando batch '.$aCom['id'].' da tarefa '.$aId.' executado: '.(AURA_LOG_EXECS ? $aOut : substr($aOut, 0, 5).'...'));
				}

				logMsg('Todos os comandos batch da tarefa '.$aId.' foram executados, avisando o cerebro...');
				getUrl(AURA_BRAIN_URL . '?method=tasklog&task='.$aId.'&device='.AURA_HOSTNAME.'&hash='.AURA_HASH.'&time_end='.time().'&result=***BATCH***');
				logMsg('Cerebro foi avisado!');
			}
		}
	}

	/**
	 * Processa e executa o(s) comando(s) enviados pelo cérebro, notificando ele quando
	 * o comando é iniciado e quando ele é finalizado.
	 *
	 * @param array $theInfos informações da tarefa enviadaa pelo cérebro.
	 */
	function processBrainTask($theTask) {
		getUrl(AURA_BRAIN_URL . '?method=tasklog&task='.$theTask->id.'&device='.AURA_HOSTNAME.'&hash='.AURA_HASH.'&time_start='.time());

		if(hasBatchCommands($theTask)) {
			// A tarefa possui um lote de comandos. Colocamos eles em disco e executamos
			// um por um.
			enqueBatchCommands($theTask);

		} else {
			// A tarefa possui apenas um comando. Moemos ele aqui mesmo.
			$aOut = runCommand($theTask->exec);
			getUrl(AURA_BRAIN_URL . '?method=tasklog&task='.$theTask->id.'&device='.AURA_HOSTNAME.'&hash='.AURA_HASH.'&time_end='.time().'&result=' . urlencode($aOut));

			logMsg('Comando '.$theTask->id.' executado, saida enviada para o cerebro. Saida: '.substr($aOut, 0, 5).'...');
		}

	}

	/**
	 * TODO: add docs
	 */
	function replaceAuraEnvVars($theString) {
		$aConstants = get_defined_constants(true);
		$aVars 	 	= array();
		$aValues 	= array();

		foreach($aConstants['user'] as $aName => $aValue) {
			if(strpos($aName, 'AURA_') !== false) {
				$aVars[] 	= '{@' . $aName .'}';
				$aValues[] 	= $aValue;
			}
		}

		return str_ireplace($aVars, $aValues, $theString);
	}

	/**
	 * Executa os comandos recebidos do cérebro. Os comandos devem vir no seguinte formato:
	 *
	 * array(
	 * 		'win' 	=> 'versão windows do comando'
	 * 		'mac' 	=> 'versão mac do comando'
	 * 		'linux' => 'versão linux do comando'
	 * 	)
	 * @param array|string $theCommand comando a ser executado, indexado pela plataforma, que poder 'win', 'mac' ou 'linux'.
	 * @param bool $theDecideUsingOS se <code>true</code> (default), a função irá tratar <code>$theCommand</code> como um array de comandos indexado pelo sistema operacional. Se for false, a função entenderá que o comando já está pronto para ser executado no sistema operacional local.
	 */
	function runCommand($theCommand, $theDecideUsingOS = true) {
		$aRet 		= '';
		$aCommand 	= $theDecideUsingOS ? getCommandAccordingOS($theCommand) : $theCommand;

		if($aCommand != '') {
			$aCommand = replaceAuraEnvVars($aCommand);
			logMsg('Exec: ' . (AURA_LOG_EXECS ? $aCommand : substr($aCommand, 0, 6).'...'));

			ob_start();
			$aOut = trim(shell_exec($aCommand));
			$aRet = empty($aOut) ? ob_get_contents() : $aOut;
			ob_end_clean();
		} else {
			logMsg('Comando nao suportado em '.AURA_OS);
		}

		return $aRet;
	}

	function pingBrain() {
		$aData = array(
			'ping_ip'				=> 0, 	// percentagem de pacotes perdidos.
			'ping_host'				=> 0,	// percentagem de pacotes perdidos.
			'storage_total'			=> -1,	// tamanho em bytes do HD principal
			'storage_available'		=> -1,	// bytes disponíveis no HD principal.
			'users'					=> ''	// usuários logados
		);

		switch(AURA_OS) {
			case 'win':
				$aData = getSystemInfosWindows();
				break;

			case 'mac':
			case 'linux':
				$aData = getSystemInfosLinux();
				break;
		}

		logMsg('Enviando ping.');
		getUrl(AURA_BRAIN_URL . '?method=ping&device='.AURA_HOSTNAME.'&hash='.AURA_HASH.'&time='.time().'&data='.urlencode(serialize($aData)));
	}

	/**
	 * Confere se a máquina está em conformidade com as informações do cérebro. Uma
	 * máquina ok é aquela cujo hostname está devidamente ligado ao serial do seu HD.
	 * Essa função irá perguntar para o cérebro se ela está ok com as infos que o cérebro
	 * tem. Se estiver, nada acontece, caso contrário o cérebro enviará os comandos
	 * necessários para que a máquina fique ok.
	 *
	 * @return boolean true se a máquina está ok e pode executar o cliente aura, ou false caso contrário.
	 */
	function checkMachineIsOk() {
		$aRet = false;

		logMsg('Checando com cerebro se a maquina esta OK...');

		// Enviamos um check para termos certeza que estamos cadastrados. Se nao
		// estamos, o cerebro vai nos cadastrar automaticamente (e nao retornara erros)
		// ou nao vai nos cadastrar e vai retornar um erro, dizendo que nao somos
		// cadastrados.
		logMsg('Enviando check...');
		$aData = @json_decode(getUrl(AURA_BRAIN_URL . '?method=check&device='.AURA_HOSTNAME.'&hash='.AURA_HASH));

		if($aData !== null && isset($aData->error)) {
			// Nao estamos cadastrados e o cerebro mandou pastarmos.
			// Nao há nada mais que possamos fazer.
			logMsg('Erro recebido do check: ' . $aData->msg);

		} else if($aData !== null){
			// Nao recebemos erro no check, o que indica que já estamos cadastrados
			// ou fomos cadastrados automaticamente. Podemos pedir para o cerebro
			// se somos uma máquina ok então.
			if(count($aData) > 0) {
				if(isset($aData->exec)) {
					logMsg('Cerebro mandou ajustes');
					$aOut = runCommand($aData->exec);
					logMsg('Ajustes feitos: ' . $aOut);
				} else {
					logMsg('Cerebro mandou ajustar, mas nao disse o que fazer...aff.');
				}
			} else {
				logMsg('Tudo certo, cerebro nao mandou comandos para ajuste.');
				$aRet = true;
			}
		} else {
			logMsg('Respostas do cerebro indefinida para o check.');
		}

		return $aRet;
	}

	function getSystemInfosWindows() {
		$aData 		= array();

		// Pings
		$aOut 				= trim(shell_exec('ping -n 5 -w 1000 8.8.8.8'));
		$aMatches 			= array();

		preg_match_all('/ \(([0-9]+)%/', $aOut, $aMatches);
		$aData['ping_ip'] 	= isset($aMatches[1][0]) ? $aMatches[1][0] : NULL;

		// Espaço no HD
		$aMatches 					= array();
		$aOut 						= trim(shell_exec('fsutil volume diskfree c:'));
		$aTemp						= explode("\n", $aOut);
		$aFreeBytes 				= explode(':', $aTemp[2]);
		$aTotalBytes 				= explode(':', $aTemp[1]);

		$aData['storage_total'] 	 = trim($aTotalBytes[1]);
		$aData['storage_available']  = trim($aFreeBytes[1]);

		// Usuários logados
		$aUsers				= array();
		$aMatches 			= array();
		$aOut 				= trim(shell_exec('qwinsta'));
		preg_match_all('/(\w+)\s+(.*)([0-9]+)(.*)/', $aOut, $aMatches);

		if(count($aMatches[2])) {
			foreach($aMatches[2] as $aIndex => $aUserName) {
				$aUsers[] = array('name' => trim($aUserName), 'status' => trim($aMatches[4][$aIndex]));
			}
		}
		$aData['users'] = serialize($aUsers);

		return $aData;
	}

	function getSystemInfosLinux() {
		$aData 		= array();

		// Pings
		$aOut 				= trim(shell_exec('ping -c 5 -q 8.8.8.8'));
		$aMatches 			= array();

		preg_match_all('/ ([0-9]+)%/', $aOut, $aMatches);
		$aData['ping_ip'] 	= isset($aMatches[1][0]) ? $aMatches[1][0] : NULL;

		// Espaço no HD
		$aMatches 					= array();
		$aOut 						= trim(shell_exec('df'));
		preg_match_all('/ ([0-9]+) +([0-9]+) +([0-9]+).*\//', $aOut, $aMatches);
		$aData['storage_total'] 	= trim(isset($aMatches[1][0]) ? $aMatches[1][0] : NULL);
		$aData['storage_available'] = trim(isset($aMatches[3][0]) ? $aMatches[3][0] : NULL);

		// Usuários logados
		$aUsers				= array();
		$aMatches 			= array();
		$aOut 				= @explode(' ', trim(shell_exec('users')));

		if(count($aOut)) {
			foreach($aOut as $aUserName) {
				$aUsers[] = array('name' => trim($aUserName), 'status' => '');
			}
		}
		$aData['users'] = serialize($aUsers);

		return $aData;
	}

	function getMachineInfoWindows() {
		$aRet = array();

		$aRet['hostname'] = trim(shell_exec('hostname'));

		$aInfos 			= explode("\n", shell_exec('systeminfo'));
		$aTemp				= explode(':', $aInfos[2], 2);
		$aRet['os_name']	= trim($aTemp[1]);

		$aTemp				= explode(':', $aInfos[3], 2);
		$aRet['os_version']	= trim($aTemp[1]);

		$aInfos 			= trim(shell_exec('wmic diskdrive get serialnumber'));
		$aTemp 				= explode("\n", $aInfos);
		$aRet['serial_hd']  = trim($aTemp[1]);

		$aInfos 			= trim(shell_exec('wmic nic get MACAddress, ProductName'));
		$aMatches 			= array();
		preg_match_all('/(([0-9A-F]{2}[:-]){5}([0-9A-F]{2})) (.*)/', $aInfos, $aMatches);

		foreach($aMatches[1] as $aKey => $aValue) {
			$aMac  = strtoupper(trim($aMatches[1][$aKey]));
			$aDesc = trim($aMatches[4][$aKey]);

			if(preg_match('/(WiFi|Wireless|Virtual|Bluetooth|802\.1)/i', $aDesc) == 0) {
				$aRet['mac_eth0'] = $aMac;
				logMsg('[OK] '.$aMac . " ".$aDesc);
			} else {
				logMsg('[IG] '.$aMac . " ".$aDesc);
			}
		}

		return $aRet;
	}

	function getMachineInfoLinux() {
		$aRet = array();

		$aRet['hostname'] = trim(shell_exec('hostname'));

		$aInfos 			= explode("\n", shell_exec('lsb_release -a'));
		$aTemp				= explode(':', $aInfos[0], 2);
		$aRet['os_name']	= trim($aTemp[1]);

		$aTemp				= explode(':', $aInfos[2], 2);
		$aRet['os_version']	= trim($aTemp[1]);

		$aTemp				= explode(':', $aInfos[3], 2);
		$aRet['os_version']	.= ' ' . trim($aTemp[1]);

		$aInfos 			= explode("\n", shell_exec('hdparm -I /dev/sda | grep Serial'));
		$aTemp				= explode(':', $aInfos[0], 2);
		$aRet['serial_hd']	= trim($aTemp[1]);

		$aInfos 			= explode("\n", shell_exec('ifconfig eth0'));
		$aTemp				= explode(' ', trim($aInfos[0]));
		$aRet['mac_eth0']	= strtoupper(trim($aTemp[count($aTemp) - 1]));

		logMsg('[OK] '.$aRet['mac_eth0']);

		return $aRet;
	}
?>
