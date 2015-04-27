<?php 

/**
 * Useful functions to help sentense implementation.
 */

 
function figureOutDevicesByClue($theClue) {
	$aGroup = Aura\Groups::getByClue($theClue);
	$aDevices = null;

	if ($aGroup === null) {
		// The target name is not a group, so it must be a specific device.
		$aDevice = Aura\Devices::getByClue($theClue);
		
		if ($aDevice === null) {
			// Target name is not a device either. We couldn't figure out what
			// it was, so it ends here.
			echo 'Não conheço o grupo ou dispositivo <strong>' . $theClue.'<strong>.';
			return;
			
		} else {
			// Target name is a device! Let's add it to the list of devices the command
			// will work on.
			$aDevices = array($aDevice['id']);
		}
	} else {
		// The target name is an existing group. Let's collect the devices
		// members of that group
		$aDevices = Aura\Groups::findDevices($aGroup['id']);
	}
	
	return $aDevices;
}

function findPoweredOnDevicesByIds($theDeviceIds) {	
	$aPings  = Aura\Pings::findByDevices($theDeviceIds, time() - 60 * 3);
	$aReport = Aura\Utils::generateLabReport($aPings); 
	
	return $aReport['computers'];
}

?>