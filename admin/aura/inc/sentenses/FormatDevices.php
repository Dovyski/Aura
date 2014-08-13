<?php 

require_once dirname(__FILE__).'/common/util.php';

function formatDevices($theGroupName) {
	$aDevices 			= figureOutDevicesByClue($theGroupName);
	$aHowManyDevices 	= count($aDevices);

	if($aHowManyDevices > 0) {
		$aCommand = array(
			'win' 	=> 'sed "s/.*set default=\"[0-9]\".*/   set default=\"3\"/" o:\boot\grub\grub.cfg > o:\boot\grub\grub.cfg.tmp & cat o:\boot\grub\grub.cfg.tmp > o:\boot\grub\grub.cfg & shutdown -s -t 60 & msg * "O computador vai ser formatado em 1 minuto. Salve tudo aberto agora!"',
			'linux' => 'sed \'s/.*set default=\"[0-9]\".*/   set default=\"3\"/\' /boot/grub/grub.cfg > /boot/grub/grub.cfg.tmp ; cat /boot/grub/grub.cfg.tmp > /boot/grub/grub.cfg ; shutdown -r +1 >/dev/null 2>/dev/null &',
			'mac' 	=> '',
		);
		$aTask = array(
			'time' 		=> time(),
			'priority' 	=> 1,
			'status' 	=> Aura\Tasks::STATUS_RUNNING,
			'exec' 		=> serialize($aCommand)
		);
		
		Aura\Tasks::add($aTask, $aDevices);
		echo 'Ok, ' . ($aHowManyDevices == 1 ? 'o computador será formatado ' : 'os computadores serão formatados ') . 'em 1 minuto.';
	} else {
		echo 'O grupo '.$theGroupName.' não tem computadores.';
	}
}

Aura\Interpreter::addSentenseHandler('formatDevices', '/(formate|formatar?)(todos |todas )?( os?| as?)?( computadores| computador| aparelhos| dispositivos| pcs| máquinas| equipamentos| coisos| coisas)( da| do)? ([\w\W]*)/', array(6));

?>