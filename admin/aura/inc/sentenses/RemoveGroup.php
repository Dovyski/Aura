<?php 

function removeGroup($theClue) {
	if($theClue[0] == '"' &&  $theClue[strlen($theClue) - 1] == '"') {
		$theClue = substr($theClue, 1, strlen($theClue) - 2);
	} 

	$aLabel = (is_numeric($theClue) ? 'de id '.$theClue : '"'.$theClue.'"');
	$aGroup = Aura\Groups::getByClue($theClue);

	if($aGroup == null) {
		echo 'Não conheço o grupo '.$aLabel.'.';
	} else {
		$aRet 	= Aura\Groups::removeByClue($theClue);

		
		if($aRet) {
			echo 'O grupo '.$aLabel.' foi removido.';
		} else {
			echo 'Não consegui remover o grupo '.$aLabel.'. Talvez ele não exista.'; 
		}
	}
}

Aura\Interpreter::addSentenseHandler('removeGroup', '/(remover?|remova|apagar?|apague|excluir?|exclua|deletar?|delete) (o |um )?grupo (de |com |com o )?(id |nome )?([\w\W]*)/', array(5));

?>