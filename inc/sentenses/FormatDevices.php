<?php 

require_once dirname(__FILE__).'/common/util.php';

function formatDevices($theUseCustomImage, $theImageName, $theGroupName) {
	$aDevices 			= figureOutDevicesByClue($theGroupName);
	$aHowManyDevices 	= count($aDevices);
	$aUseCustomImage	= strlen($theUseCustomImage) > 0;
	$theImageName		= trim(str_replace('"', '', $theImageName));

	if ($aHowManyDevices > 0) {
		if ($aUseCustomImage && strlen($theImageName) == 0) {
			echo 'Preciso saber qual imagem usar para formatar. Diga o nome da imagem entre áspas duplas.';
			
		} else {
			$aCustomImg = '';
			
			if ($aUseCustomImage) {
				$aCustomImg = ';s/didatica-ultima/'.$theImageName.'/';
			}
		
			$aCommand = array(
				'win' 	=> 'sed "s/.*set default=\"[0-9]\".*/   set default=\"3\"/'.$aCustomImg.'" o:\boot\grub\grub.cfg > o:\boot\grub\grub.cfg.tmp & attrib -r o:\boot\grub\grub.cfg & cat o:\boot\grub\grub.cfg.tmp > o:\boot\grub\grub.cfg & shutdown -r -t 60 & msg * "O computador vai ser formatado em 1 minuto. Salve tudo aberto agora!"',
				'linux' => 'sed \'s/.*set default=\"[0-9]\".*/   set default=\"3\"/'.$aCustomImg.'\' /boot/grub/grub.cfg > /boot/grub/grub.cfg.tmp ; cat /boot/grub/grub.cfg.tmp > /boot/grub/grub.cfg ; shutdown -r +1 >/dev/null 2>/dev/null &',
				'mac' 	=> '',
			);

			$aTask = array(
				'time' 		=> time(),
				'priority' 	=> 1,
				'status' 	=> Aura\Tasks::STATUS_RUNNING,
				'exec' 		=> serialize($aCommand)
			);
			
			Aura\Tasks::add($aTask, $aDevices);
			echo 'Ok, ' . ($aHowManyDevices == 1 ? 'o computador será formatado ' : 'os computadores serão formatados ') . 'em 1 minuto ' . ($aUseCustomImage ? ' usando a imagem ' . $theImageName : '.');
		}
	} else {
		echo 'O grupo '.$theGroupName.' não tem computadores.';
	}
}

Aura\Interpreter::addSentenseHandler('formatDevices', '/(coloque|ponha|instale|formate|formatar?)( a imagem| com a imagem| usando a imagem)?( "[\w\W]*")?( em todos| em todas| em todos os| em todas as| todos| todos os| todas| todas as| os?| as?| nos?| nas?)?( computadores| computador| aparelhos?| dispositivos?| pcs?| maquinas?| equipamentos?| coisos?| coisas?)( da| do)? ([\w\W]*)/', array(2, 3, 7));

?>