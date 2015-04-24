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

	require_once dirname(__FILE__).'/functions.php';

	if (php_sapi_name() != 'cli') {
		die('Rode pela linha de comando!');
	}

	error_reporting(E_ALL | E_STRICT);
	ini_set('display_errors', 1);
	ini_set('logMsg_errors', 0);
	ini_set('html_errors', 0);

	loadConfigFile();

	$aHostname 	= '';
	$aOs	   	= '';
	$aOsName   	= '';
	$aOsVersion = '';
	$aSerialHd 	= '';
	$aMacEth0 	= '';

	if(stristr(PHP_OS, 'win') !== false) {
		// Windows
		$aOs		= 'win';
		$aWin		= getMachineInfoWindows();

		$aHostname 	= $aWin['hostname'];
		$aOsName	= $aWin['os_name'];
		$aOsVersion	= $aWin['os_version'];
		$aSerialHd 	= $aWin['serial_hd'];
		$aMacEth0 	= $aWin['mac_eth0'];
	} else {
		// Linux
		$aOs		= 'linux';
		$aUnix		= getMachineInfoLinux();

		$aHostname 	= $aUnix['hostname'];
		$aOsName	= $aUnix['os_name'];
		$aOsVersion	= $aUnix['os_version'];
		$aSerialHd 	= $aUnix['serial_hd'];
		$aMacEth0 	= $aUnix['mac_eth0'];
	}

	define('AURA_HOSTNAME',		$aHostname);
	define('AURA_OS_NAME',		$aOsName);
	define('AURA_OS_VERSION',	$aOsVersion);
	define('AURA_OS',			$aOs);
	define('AURA_HASH',			md5($aMacEth0));

	logMsg('Iniciando atividades em '.AURA_HOSTNAME.' ('.AURA_HASH.'), rodando '.AURA_OS_NAME.' ('.AURA_OS_VERSION.').');

	while(1) {
		checkMachineIsOk();
		processEnquedBatchCommands();

		logMsg('Solicitando novas ordens...');
		$aResult = getUrl(AURA_BRAIN_URL . '?method=tasks&device='.AURA_HOSTNAME.'&hash='.AURA_HASH);

		if($aResult !== false) {
			$aData = @json_decode($aResult);

			if($aData !== null && isset($aData->error)) {
				logMsg('Cerebro nao mandou ordens, mandou erro: ' . $aData->msg);

			} else if($aData !== null) {
				logMsg('Ordens recebidas: ' . count($aData));

				if(count($aData) > 0) {
					foreach($aData as $aIdTask => $aInfos) {
						processBrainTask($aInfos);
					}
				}
			} else {
				logMsg('Erro na decodificacao das ordens.');
			}
		} else {
			logMsg('Erro na solicitacao de ordens!');
		}

		pingBrain();

		logMsg('Dormindo ate a proxima requisicao...');
		sleep(AURA_PING_INTERVAL);
	}
?>
