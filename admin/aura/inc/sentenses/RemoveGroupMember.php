<?php 

function removeGroupMember($thePersonName, $theGroupName) {
	$aUser 	= Aura\Users::getByClue($thePersonName);
	$aGroup = Aura\Groups::getByClue($theGroupName);

	if($aUser === null) {
		echo 'Não conheço a pessoa ' . $thePersonName.'.';
		
	} else if($aGroup === null) {
		echo 'Não conheço o grupo ' . $theGroupName.'.';
	}
	
	if($aUser !== null && $aGroup !== null) {
		Aura\Groups::removeUser($aGroup['id'], $aUser['login']);
		echo 'Ok, '.$thePersonName.' não faz mais parte do grupo '.$theGroupName.'.';
	}
}

Aura\Interpreter::addSentenseHandler('removeGroupMember', '/(remova|remover?|tire|tirar?|exclua|excluir?|deletar?|delete)( o| a)? ([\w\W]*) (do |de )(grupo )?([\w\W]*)/', array(3, 6));

?>