<?php 

function listGroupMembers($theGroupName) {
	$aGroup = Aura\Groups::getByClue($theGroupName);

	if($aGroup === null) {
		echo 'Não conheço o grupo ' . $theGroupName.'.';
		return;
	}

	$aUsers = Aura\Groups::findUsers($aGroup['id']);

	if(count($aUsers) > 0) {
		echo 'O grupo '.$theGroupName.' tem os membros:<br /><br />';

		foreach($aUsers as $User) {
			echo '<li><strong>'.$User.'</strong></li>';
		}
	} else {
		// O grupo pode ser de dispositivos.
		echo 'O grupo '.$theGroupName.' não tem membros.';
	}
}

Aura\Interpreter::addSentenseHandler('listGroupMembers', '/(liste|listar?|mostre|mostrar?|informe|informar?)( os| as)? (integrantes|membros|participantes) do grupo ([\w\W]*)/', array(4));
Aura\Interpreter::addSentenseHandler('listGroupMembers', '/(quais|quem) são( os| as)? (integrantes|membros|participantes) do grupo ([\w\W]*)/u', array(4));
Aura\Interpreter::addSentenseHandler('listGroupMembers', '/quem (faz parte|integra|compõe) (d?o )?grupo ([\w\W]*)/', array(3));
Aura\Interpreter::addSentenseHandler('listGroupMembers', '/quem (é |faz parte )do ([\w\W]*)/', array(2));
Aura\Interpreter::addSentenseHandler('listGroupMembers', '/quem (é |são )(os |as )? (integrantes|membros|participantes) do grupo ([\w\W]*)/', array(4));

?>