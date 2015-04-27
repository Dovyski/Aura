<?php 

function logoffUsers($theGroupName) {
	$aDevices = figureOutDevicesByClue($theGroupName);

	if(count($aDevices) > 0) {
		$aPoweredOnDevices 	= findPoweredOnDevicesByIds($aDevices);
		$aHowManyDevices 	= count($aPoweredOnDevices);

		if($aHowManyDevices > 0) {
			$aCommand = array(
				'win' 	=> 'shutdown /r /t 0',
				'linux' => 'shutdown -r now',
				'mac' 	=> '',
			);
			$aTask = array(
				'time' 		=> time(),
				'priority' 	=> 1,
				'status' 	=> Aura\Tasks::STATUS_RUNNING,
				'exec' 		=> serialize($aCommand)
			);
			Aura\Tasks::add($aTask, $aPoweredOnDevices);
			echo 'Ok, todos os usuários serão deslogados agora.';
		} else {
			echo 'Não há gente para deslogar porque todos os computadores estão desligados.';
		}
	} else {
		echo 'O grupo '.$theGroupName.' não tem computadores.';
	}
}

Aura\Interpreter::addSentenseHandler('logoffUsers', '/(deslogue|faça logoff dos?|faça logoff das?|faça logout|faça logout dos?|faça logout das?|logout|logout dos?|logout das?)( todos| todas)?( os| as)? (usuários|pessoas|alunos|coisos|coisas|viventes)? d(a|o) ([\w\W]*)/', array(6));

?>