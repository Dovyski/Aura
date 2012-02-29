<?php 

function addGroupMember($thePersonName, $theGroupName) {
	$aUser 	= Aura\Users::getByClue($thePersonName);
	$aGroup = Aura\Groups::getByClue($theGroupName);

	if($aUser === null) {
		echo 'Não conheço a pessoa ' . $thePersonName.'.';
		
	} else if($aGroup === null) {
		echo 'Não conheço o grupo ' . $theGroupName.'.';
	}
	
	if($aUser !== null && $aGroup !== null) {
		Aura\Groups::addUser($aGroup['id'], $aUser['login']);
		echo 'Ok, '.$thePersonName.' agora faz parte do grupo '.$theGroupName.'.';
	}
}

Aura\Interpreter::addSentenseHandler('addGroupMember', '/(adicione|adicionar?|coloque|colocar?|bote|botar?|ponha)( o| a)? ([\w\W]*) (ao|no) grupo ([\w\W]*)/', array(3, 5));

?>