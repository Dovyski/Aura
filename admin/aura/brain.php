<?php
	header("Content-Type: text/plain; charset=UTF-8");
	require_once dirname(__FILE__).'/globals.php';

	$aMethod = isset($_REQUEST['method']) ? $_REQUEST['method'] : '';
	$aDevice = isset($_REQUEST['device']) ? $_REQUEST['device'] : '';
	$aHash 	 = isset($_REQUEST['hash'])   ? $_REQUEST['hash']   : '';
	$aRet	 = array();
	
	unset($_REQUEST['method'], $_REQUEST['device'], $_REQUEST['hash']);
	
	$aInfoDevice = Aura\Devices::getByClue($aHash);
	
	if($aInfoDevice !== null) {
		switch($aMethod) {
			case 'check':
				$aPossibleNames = $aInfoDevice['name'] . ' ' . $aInfoDevice['alias'];
				  
				if(strpos($aPossibleNames, $aDevice) === false) {
					$aRet['exec'] = array(
						'win' 	=> 'wmic1 computersystem where name="%COMPUTERNAME%" call rename name="W'.$aInfoDevice['name'].'" & shutdown1 -r -t 0',
						'linux' => '#echo "U'.$aInfoDevice['name'].'" > /etc/hostname & shutdown -r now',
						'mac' 	=> '',
					);					
				}
				break;
				
			case 'ping':
				if(!empty($_REQUEST['data'])) {
					$_REQUEST['data'] = @urldecode($_REQUEST['data']);
				}
				
				$_REQUEST['client'] = $_SERVER['HTTP_USER_AGENT'];
				 
				$aRet = Aura\Pings::add($aInfoDevice['id'], $_REQUEST);
				$aRet = $aRet ? array('success' => true) : array('error' => true, 'msg' => 'Dados inválidos no ping.');
				break;
					
			case 'tasks':
				$aRet = Aura\Tasks::findPendingTasksByDevice($aInfoDevice['id']);
				break;
					
			case 'tasklog':
				$aTask = isset($_REQUEST['task']) ? $_REQUEST['task'] 	: false;
				unset($_REQUEST['task']);
		
				if($aTask !== false) {
					$aRet = Aura\Tasks::updateLog($aInfoDevice['id'], $aTask, $_REQUEST);
					$aRet = $aRet ? array('success' => true) : array('error' => true, 'msg' => 'Dados inválidos para atualização de log de comandos.');
				} else {
					$aRet = array('error' => true, 'msg' => 'Atualização de log mal formada.');
				}
				break;
					
			default:
				echo 'Método inválido.';
		}
	} else {
		if(AURA_AUTO_INCLUDE_DEVICES && !empty($aHash)) {
			Aura\Devices::update(array('name' => $aHash, 'hash' => $aHash, 'desc' => 'Adicionado automaticamente vindo de '.$aDevice.', IP '.$_SERVER['REMOTE_ADDR'].'.'));
			$aRet = array('success' => true, 'msg' => 'Dispositivo '.$aDevice.' adicionado.');
		} else {
			$aRet = array('error' => true, 'msg' => 'Dispositivo '.$aDevice.' desconhecido.');
		}
	}

	echo json_encode($aRet);
	exit();
?>