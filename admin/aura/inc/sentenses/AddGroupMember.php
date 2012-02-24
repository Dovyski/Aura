<?php 

function addGroupMember($thePersonName, $theGroupName) {
	echo 'addGroupMember('.$thePersonName.', '.$theGroupName.') called!';
}

Aura\Interpreter::addSentenseHandler('addGroupMember', '/(adicione|coloque|bote|ponha) ([\w\W]*) (ao|no) grupo ([\w\W]*)/', array(2, 4));

?>