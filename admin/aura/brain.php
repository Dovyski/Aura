<?php
	header("Content-Type: text/plain; charset=UTF-8");
	require_once dirname(__FILE__).'/globals.php';

	$aMethod = isset($_REQUEST['method']) ? $_REQUEST['method'] : '';
	$aDevice = isset($_REQUEST['device']) ? $_REQUEST['device'] : 0;
	$aRet	 = '';
	
	unset($_REQUEST['method'], $_REQUEST['device']);
	
	switch($aMethod) {
		case 'ping':
			// TODO: log ping requests
			break;
			
		case 'commands':
			$aRet = Aura\Commands::findPendingCommandsByDevice($aDevice);
			break;
			
		case 'commandlog':
			$aCommand = isset($_REQUEST['command']) ? $_REQUEST['command'] 	: false;
			unset($_REQUEST['command']);

			if($aCommand !== false) {
				$aRet = Aura\Commands::updateLog($aDevice, $aCommand, $_REQUEST);
				$aRet = $aRet ? array('success' => true) : array('error' => true, 'msg' => 'Dados inválidos para atualização de log de comandos.');
			} else {
				$aRet = array('error' => true, 'msg' => 'Atualização de log mal formada.');
			}
			break;
			
		default:
			echo 'Método inválido.';
	}

	echo json_encode($aRet);
	exit();
?>