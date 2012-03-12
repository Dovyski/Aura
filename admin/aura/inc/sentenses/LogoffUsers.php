<?php 

function logoffUsers($theGroupName) {
	$aGroup = Aura\Groups::getByClue($theGroupName);

	if($aGroup === null) {
		echo 'Não conheço o grupo ' . $theGroupName.'.';
		return;
	}

	$aDevices = Aura\Groups::findDevices($aGroup['id']);

	if(count($aDevices) > 0) {
		$aPings  = Aura\Pings::findByDevices($aDevices, time() - 60 * 3);
		$aReport = Aura\Utils::generateLabReport($aPings); 

		if(count($aReport['computers']) > 0) {
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
			Aura\Tasks::add($aTask, $aReport['computers']);
			echo 'Ok, todos os usuários serão deslogados agora.';
		} else {
			echo 'Não há gente para deslogar porque todos os computadores estão desligados.';
		}
	} else {
		echo 'O grupo '.$theGroupName.' não tem computadores.';
	}
}

Aura\Interpreter::addSentenseHandler('logoffUsers', '/(deslogue|faça logoff|faça logout|logout)(todos |todas )?( os| as)? (usuários|pessoas|alunos|coisos|coisas|viventes)? d(a|o) ([\w\W]*)/', array(6));

?>