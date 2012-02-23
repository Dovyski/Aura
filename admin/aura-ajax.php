<?php 
	require_once dirname(__FILE__).'/../inc/globals.php';
	require_once dirname(__FILE__).'/aura/globals.php';
	
	authRestritoAdmin();
	header("Content-Type: text/html; charset=UTF-8");

	$aThought = Aura\Interpreter::analyze($_REQUEST['command']);
	
	switch($aThought['command']) {
		case AURA_ADD_GROUP_MEMBER:
			echo 'Vou adicionar alguem...' . print_r($aThought['params'], true);
			break;
			
		case AURA_DATE:
			echo date('h:i:s d/m/Y');
			break;
			
		default:
			echo 'Não entendi o que você falou.';
	}
	
	if(MODO_DEBUG) {
		var_dump($aThought);
	}
	
	exit();
?>