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

	if(stristr(PHP_OS, 'win') !== false) {
		// Windows
		$aOs		= 'win';
		$aWin		= getMachineInfoWindows();
		
		$aHostname 	= $aWin['hostname'];
		$aOsName	= $aWin['os_name'];
		$aOsVersion	= $aWin['os_version'];
		$aSerialHd 	= $aWin['serial_hd'];
	} else {
		// Linux
		$aOs		= 'linux';
		$aUnix		= getMachineInfoLinux();
		
		$aHostname 	= $aUnix['hostname'];
		$aOsName	= $aUnix['os_name'];
		$aOsVersion	= $aUnix['os_version'];
		$aSerialHd 	= $aUnix['serial_hd'];
	}
	
	define('AURA_HOSTNAME',		$aHostname);
	define('AURA_OS_NAME',		$aOsName);
	define('AURA_OS_VERSION',	$aOsVersion);
	define('AURA_OS',			$aOs);
	define('AURA_HASH',			md5($aSerialHd));
	
	logMsg('Iniciando atividades em '.AURA_HOSTNAME.' ('.AURA_HASH.'), rodando '.AURA_OS_NAME.' ('.AURA_OS_VERSION.').');
	$aMachineOk = checkMachineIsOk();

	while($aMachineOk) {
		logMsg('Solicitando novas ordens...');
		$aResult = getUrl(BRAIN_URL . '?method=tasks&device='.AURA_HOSTNAME.'&hash='.AURA_HASH);
		
		if($aResult !== false) {
			$aData = @json_decode($aResult);
			
			if($aData !== null) {
				logMsg('Ordens recebidas: ' . count($aData));
				
				if(count($aData) > 0) {
					foreach($aData as $aIdTask => $aInfos) {
						getUrl(BRAIN_URL . '?method=tasklog&task='.$aInfos->id.'&device='.AURA_HOSTNAME.'&hash='.AURA_HASH.'&time_start='.time());
						
						$aOut = runCommand($aInfos->exec);
						getUrl(BRAIN_URL . '?method=tasklog&task='.$aInfos->id.'&device='.AURA_HOSTNAME.'&hash='.AURA_HASH.'&time_end='.time().'&result=' . urlencode($aOut));
	
						logMsg('Comando '.$aInfos->id.' executado, saida enviada para o cerebro. Saida: '.substr($aOut, 0, 5).'...');
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
		sleep(PING_INTERVAL);
	}
?>