<?php 

function shutdownDevices($theGroupName) {
	$aGroup = Aura\Groups::getByClue($theGroupName);
	$aDevices = null;

	if ($aGroup === null) {
		// The target name is not a group, so it must be a specific device.
		$aDevice = Aura\Devices::getByClue($theGroupName);
		
		if ($aDevice === null) {
			// Target name is not a device either. We couldn't figure out what
			// it was, so it ends here.
			echo 'Não conheço o grupo ou dispositivo <strong>' . $theGroupName.'<strong>.';
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

	if(count($aDevices) > 0) {
		$aPings  = Aura\Pings::findByDevices($aDevices, time() - 60 * 3);
		$aReport = Aura\Utils::generateLabReport($aPings); 

		if(count($aReport['computers']) > 0) {
			$aCommand = array(
				'win' 	=> 'shutdown -s -t 60 & msg * "O computador vai desligar em 1 minuto. Salve tudo aberto agora!"',
				'linux' => 'shutdown -h +1 "O computador vai desligar em 1 minuto. Salve tudo aberto agora!"',
				'mac' 	=> '',
			);
			$aTask = array(
				'time' 		=> time(),
				'priority' 	=> 1,
				'status' 	=> Aura\Tasks::STATUS_RUNNING,
				'exec' 		=> serialize($aCommand)
			);
			Aura\Tasks::add($aTask, $aReport['computers']);
			echo 'Ok, os computadores serão desligados em 1 minuto.';
		} else {
			echo 'Todos os computadores já estão desligados.';
		}
	} else {
		echo 'O grupo '.$theGroupName.' não tem computadores.';
	}
}

Aura\Interpreter::addSentenseHandler('shutdownDevices', '/(desligue|desligar?|apagar?|apague)(todos |todas )?( os?| as?)?( computadores| computador| aparelhos| dispositivos| pcs| máquinas| equipamentos| coisos| coisas)( da| do)? ([\w\W]*)/', array(6));

?>