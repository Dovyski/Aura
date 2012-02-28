<?php

	/**
	 * Funções do cliente. 
	 */

	// TODO: pegar isso de um arquivo ini? 
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
	
	/**
	 * Executa os comandos recebidos do cérebro. Os comandos devem vir no seguinte formato:
	 * 
	 * array(
	 * 		'win' 	=> 'versão windows do comando' 
	 * 		'mac' 	=> 'versão mac do comando' 
	 * 		'linux' => 'versão linux do comando' 
	 * 	) 
	 * @param array $theCommand comando a ser executado, indexado pela plataforma, que poder 'win', 'mac' ou 'linux'. 
	 */
	function runCommand($theCommand) {
		$aRet 		= '';
		$aCommand 	= array();
		
		if(is_string($theCommand)) {
			$aCommand = @unserialize($theCommand);
		}
		
		if(isset($aCommand[AURA_OS])) {
			$aOut = trim(shell_exec($aCommand[AURA_OS]));
		} else {
			$aRet = 'Nao suportado em '.AURA_OS.':' . print_r($theCommand, true);
		}
		
		return $aRet;
	}
?>