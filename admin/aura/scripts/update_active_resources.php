<?php
	/**
	 * Analisa um conjunto de pings enviados pelos clientes, processando
	 * cada um deles para criar duas tabelas com os usuários e os dispositivos
	 * ligados nesse exato instante.
	 */
	require_once dirname(__FILE__).'/../globals.php';
	require_once dirname(__FILE__).'/../../../inc/globals.php';

	$aPings	= Aura\Pings::find(time() - 60*2);
	
	$aRet = array(
		'computers' => array(), 
		'users'	 	=> array()
	);
	
	echo "Updating active users/devices...\n";
	
	if(count($aPings) == 0) {
		echo "No recent data found, aborting.\n";
		return;
	}

	foreach($aPings as $aIdDevice => $aPingsDevice) {
		if(count($aPingsDevice) == 0) continue;
		
		foreach($aPingsDevice as $aInfo) {
			$aData = @unserialize($aInfo['data']);

			if($aData !== false) {
				if(!empty($aData['users'])) {
					$aUsers = unserialize($aData['users']);

					if($aUsers !== false) {
						foreach($aUsers as $aUser) {
							$aRet['users'][$aUser['name']][$aIdDevice] = $aIdDevice;
						}
					}
				}
				
				if(!isset($aRet['computers'][$aIdDevice])) {
					$aMatches = array();
					preg_match_all('/.*\/.* \((.*);(.*)\)/', $aInfo['client'], $aMatches);
					
					$aRet['computers'][$aIdDevice]['client'] 	= $aInfo['client'];
					$aRet['computers'][$aIdDevice]['os'] 		= $aMatches[1][0];
					
					unset($aData['ping_ip']);
					$aRet['computers'][$aIdDevice]['data'] 		= $aData;
				}
			}
		}
	}
	//var_dump($aRet);exit();
	echo "Adding devices:\n";
	if(count($aRet['computers']) > 0) {
		foreach($aRet['computers'] as $aIdDevice => $aInfo) {
			$aInfo['time'] 			= time();
			$aInfo['data']			= @serialize($aInfo['data']);
			$aValues				= Aura\Utils::prepareForSql($aInfo);
			$aUpdate				= Aura\Utils::generateUpdateStatement($aInfo);
			
			$aValues['fk_device'] 	= $aIdDevice;
			
			Aura\Db::execute("INSERT INTO ".Aura\Db::TABLE_ACTIVE_DEVICES." (".implode(',', array_keys($aValues)).") VALUES (".implode(',', $aValues).")
								ON DUPLICATE KEY UPDATE " .$aUpdate);
			echo "   Device ".$aIdDevice." (".$aInfo['os'].")\n";
		}
	} else {
		echo " No devices found.\n";
	}
	
	echo "Adding users:\n";
	if(count($aRet['users']) > 0) {
		foreach($aRet['users'] as $aUserName => $aDevices) {
			$aValues = "";
			if(count($aDevices) > 0) {
				foreach($aDevices as $aIdDevice) {
					$aValues .= "(".$aIdDevice.", '".$aUserName."', ".time()."),";
				}
			}
			$aValues = substr($aValues, 0, strlen($aValues) - 1);
			
			Aura\Db::execute("INSERT INTO ".Aura\Db::TABLE_ACTIVE_USERS." (fk_device, user_name, time) VALUES ".$aValues."
									ON DUPLICATE KEY UPDATE time = " .time());
			echo "   ".$aUserName." at devices: ".implode(', ', $aDevices).".\n";
		}
	} else {
		echo " No users found.\n";
	}
	
	echo "Removing old entries...";
	Aura\Db::execute("DELETE FROM ".Aura\Db::TABLE_ACTIVE_DEVICES." WHERE time <= " . (time() - 120));
	Aura\Db::execute("DELETE FROM ".Aura\Db::TABLE_ACTIVE_USERS." WHERE time <= " . (time() - 120));
	echo "[OK]\n";	
	
	echo "All done, have a nice day!\n";
?>