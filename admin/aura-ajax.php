<?php
	header("Content-Type: text/html; charset=UTF-8");
	
	require_once dirname(__FILE__).'/../inc/globals.php';
	require_once dirname(__FILE__).'/aura/globals.php';

	authRestritoAdmin();

	Aura\Interpreter::loadSentenseHandlers();
	$aReturn = Aura\Interpreter::process($_REQUEST['command']);
	
	if($aReturn === false) {
		echo 'Não entendi o que você falou.';
	}
	
	exit();
?>