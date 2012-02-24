<?php 

function addGroup($theGroupName, $theParams = array()) {
	$aParams 			= Aura\Utils::parseParamList($theParams);
	$aParams['name'] 	= !empty($theGroupName) ? $theGroupName : @$aParams['name'];
	
	Aura\Groups::update($aParams);
	echo 'Ok, grupo criado.';
}

Aura\Interpreter::addSentenseHandler('addGroup', '/(adicione|adicionar|crie|criar|faça|insira|inserir) (o |um )?grupo ([\w\W]* )?(de |com )([\w\W]*)/', array(3, 5));
Aura\Interpreter::addSentenseHandler('addGroup', '/(adicione|adicionar|crie|criar|faça|insira|inserir) (o |um )?grupo ([\w\W]*)/', array(3));

?>