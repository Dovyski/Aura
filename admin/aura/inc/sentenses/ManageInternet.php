<?php 

function manageInternet($theDeactivate, $theActivate, $theGroupName) {
	$aGroup = Aura\Groups::getByClue($theGroupName);

	if($aGroup === null) {
		echo 'Não conheço o grupo <strong>' . $theGroupName.'</strong>.';
		return;
	}

	$aActivate = !empty($theActivate);
	$aDevices  = Aura\Groups::findDevices($aGroup['id']);

	if(count($aDevices) > 0) {
		$aCommand = null;
		
		if($aActivate) {
			$aCommand = array(
				'win' 	=> 'netsh advfirewall firewall delete rule name="Aura: Block Internet" & msg * "A internet foi habilitada novamente."',
				'linux' => '',
				'mac' 	=> '',
			);
		} else {
			$aCommand = array(
				'win' 	=> 'netsh advfirewall firewall add rule name="Aura: Block Internet" dir=out remoteip=0.0.0.0-172.20.0.0,172.21.0.0-192.168.0.0,192.169.0.0-255.255.255.255 action=block & msg * "A internet foi desligada."',
				'linux' => '',
				'mac' 	=> '',
			);
		}

		$aTask = array(
			'time' 		=> time(),
			'priority' 	=> 1,
			'status' 	=> Aura\Tasks::STATUS_RUNNING,
			'exec' 		=> serialize($aCommand)
		);
		Aura\Tasks::add($aTask, $aDevices);
		echo 'Ok, a Internet foi ' . ($aActivate ? 'ativada' : 'desativada').'.';
		
	} else {
		echo 'O grupo '.$theGroupName.' não tem computadores conectados à Internet.';
	}
}

Aura\Interpreter::addSentenseHandler('manageInternet', '/(desligue|desligar?|apagar?|apague|corte|cortar?|cancele|cancelar?|suspender?|suspenda|mate|matar?|desabilite|desabilitar?|desconectar?|desconecte)?(ligar?|ligue|ativar?|ative|reconecte|reconectar?|habilite|habilitar?)?( os?| as?)?( internet| net| redes?| conexão| conexões | comunicação| comunicações)?( dos?)?( computadores| computador| aparelhos| dispositivos| pcs| máquinas| equipamentos| coisos| coisas)?( da| do)? ([\w\W]*)/', array(1, 2, 8));

?>