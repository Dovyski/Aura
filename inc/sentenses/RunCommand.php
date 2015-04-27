<?php

require_once dirname(__FILE__).'/common/util.php';

function runCommand($theCommand, $theGroupName) {
	$aDevices 			= figureOutDevicesByClue($theGroupName);
	$aActiveDevices		= findPoweredOnDevicesByIds($aDevices);
	$aHowManyDevices 	= count($aDevices);

	$theCommand			= trim($theCommand);
	$theCommand			= substr($theCommand, 1, strlen($theCommand) - 2);

	if ($aHowManyDevices > 0) {
		if(count($aActiveDevices) > 0) {
			$aCommand = array(
				'win' 	=> $theCommand,
				'linux' => $theCommand,
				'mac' 	=> '',
			);

			$aTask = array(
				'time' 		=> time(),
				'priority' 	=> 1,
				'status' 	=> Aura\Tasks::STATUS_RUNNING,
				'exec' 		=> serialize($aCommand)
			);

			Aura\Tasks::add($aTask, $aActiveDevices);
			echo 'Ok, moendo. ' . $theCommand;
		} else {
			echo ($aHowManyDevices == 1) ? 'O computador '.$theGroupName.' está desligado.' : 'Os computadores do grupo '.$theGroupName.' estão todos desligados.';
		}

	} else {
		echo 'O grupo '.$theGroupName.' não tem computadores.';
	}
}

Aura\Interpreter::addSentenseHandler('runCommand', '/(rode|rodar?|execute|executar?|moa)( o comando| o programa)?( "[\w\W]*")?( em todos| em todas| em todos os| em todas as| todos| todos os| todas| todas as| os?| as?| nos?| nas?)?( computadores| computador| aparelhos?| dispositivos?| pcs?| maquinas?| equipamentos?| coisos?| coisas?)( da| do)? ([\w\W]*)/', array(3, 7));

?>
