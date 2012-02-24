<?php 

function addGroup($theGroupName, $theParams) {
	echo 'addGroup('.$theGroupName.', '.print_r(Aura\Utils::parseParamList($theParams), true).') called!';
}

Aura\Interpreter::addSentenseHandler('addGroup', '/(adicione|crie|faça) (o |um )?grupo ([\w\W]* )?(de |com )([\w\W]*)/', array(3, 5));

?>