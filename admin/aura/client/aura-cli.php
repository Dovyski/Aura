<?php

	/**
	 * Cliente Aura
	 * 
	 * Esse programa roda em cada uma das máquinas controladas pela Aura. O objetivo
	 * desse cliente é buscar comandos da central da Aura, executar eles e retortar
	 * resultados.
	 * 
	 * O cliente por si só não tem capacidade de "pensar", ele apenas envia informações
	 * e executa ordens. A coisa mágica toda acontece dentro da Aura, analisando os
	 * resultados dos comandos e decidindo o que fazer.
	 * 
	 */

	define('BRAIN_URL', 'http://localhost/ncc.cc.uffs.edu.br/admin/aura/brain.php');

	function getUrl($theUrl) {
		$aUserAgent = 'Aura Client/1.0 ('.AURA_OS_NAME.'; '.AURA_OS_VERSION.')';
		
		$aCh = curl_init($theUrl);
		curl_setopt($aCh, CURLOPT_USERAGENT, $aUserAgent);
		curl_setopt($aCh, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($aCh,CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt($aCh, CURLOPT_FAILONERROR, 1);
		
		$aResult = curl_exec($aCh);
		$aRet	 = curl_errno($aCh) ? false : $aResult; 
		curl_close($aCh);
		
		return $aRet;
	}
	
	function logMsg($theMsg) {
		echo date('[h:i:s d/m/Y]') . " ".$theMsg . "\n";
	}

	if (php_sapi_name() != 'cli') {
		die('Rode pela linha de comando!');
	}
	
	error_reporting(E_ALL | E_STRICT);
	ini_set('display_errors', 1);
	ini_set('logMsg_errors', 0);
	ini_set('html_errors', 0);
	
	$aHostname 	= '';
	$aOs	   	= '';
	$aOsVersion = '';
	
	if(stristr(PHP_OS, 'win') !== false) {
		// Windows
		$aHostname 	= trim(shell_exec('hostname'));
		$aTemp 		= explode("\n", shell_exec('systeminfo | findstr /B /C:"OS Name" /C:"OS Version"'));
		$aParts		= explode(':', $aTemp[0]);
		$aParts2	= explode(':', $aTemp[1]);
		
		$aOs		= trim($aParts[1]);
		$aOsVersion	= trim($aParts2[1]);		
	} else {
		// Linux
	}
	
	define('AURA_HOSTNAME',		$aHostname);
	define('AURA_OS_NAME',		$aOs);
	define('AURA_OS_VERSION',	$aOsVersion);
	
	logMsg('Iniciando atividades em '.AURA_HOSTNAME.', rodando '.AURA_OS_NAME.' ('.AURA_OS_VERSION.').');

	while(1) {
		logMsg('Solicitando novos dados...');
		$aResult = getUrl(BRAIN_URL . '?method=tasks&device='.AURA_HOSTNAME);
		
		if($aResult !== false) {
			logMsg('Ordens recebidas: ');
			logMsg($aResult);
			
			$aData = @json_decode($aResult);
			
			if($aData !== null) {
				if(count($aData) > 0) {
					foreach($aData as $aIdTask => $aInfos) {
						getUrl(BRAIN_URL . '?method=tasklog&task='.$aInfos->id.'&device='.AURA_HOSTNAME.'&time_start='.time());
						
						$aOut = trim(shell_exec($aInfos->exec));
						getUrl(BRAIN_URL . '?method=tasklog&task='.$aInfos->id.'&device='.AURA_HOSTNAME.'&time_end='.time().'&result=' . urlencode($aOut));
	
						logMsg('Comando '.$aInfos->id.' executado, saida enviada para o cerebro. Saida: '.$aOut);
					}
				}
			} else {
				logMsg('Erro na decodificacao.');
			}
		} else {
			logMsg('Erro na solicitacao de dados!');
		}

		logMsg('Dormindo ate a proxima requisicao...');
		sleep(5);
	}
?>