<?php 

require_once dirname(__FILE__).'/common/util.php';

function shutdownDevices($theGroupName) {
	$aDevices = figureOutDevicesByClue($theGroupName);

	if(count($aDevices) > 0) {
		$aPoweredOnDevices 	= findPoweredOnDevicesByIds($aDevices);
		$aHowManyDevices 	= count($aPoweredOnDevices);

		if($aHowManyDevices > 0) {
			$aCommand = array(
				'win' 	=> 'shutdown -s -t 60 & msg * "O computador vai desligar em 1 minuto. Salve tudo aberto agora!"',
				'linux' => 'shutdown -h +1 "O computador vai desligar em 1 minuto. Salve tudo aberto agora!" &',
				'mac' 	=> '',
			);
			$aTask = array(
				'time' 		=> time(),
				'priority' 	=> 1,
				'status' 	=> Aura\Tasks::STATUS_RUNNING,
				'exec' 		=> serialize($aCommand)
			);
			Aura\Tasks::add($aTask, $aPoweredOnDevices);
			echo 'Ok, ' . ($aHowManyDevices == 1 ? 'o computador será desligado ' : 'os computadores serão desligados ') . 'em 1 minuto.';
		} else {
			echo $aHowManyDevices == 1 ? 'O computador já está desligado.' : 'Todos os computadores já estão desligados.';
		}
	} else {
		echo 'O grupo '.$theGroupName.' não tem computadores.';
	}
}

Aura\Interpreter::addSentenseHandler('shutdownDevices', '/(desligue|desligar?|apagar?|apague)(todos |todas )?( os?| as?)?( computadores| computador| aparelhos| dispositivos| pcs| máquinas| equipamentos| coisos| coisas)( da| do)? ([\w\W]*)/', array(6));

?>