<?php 
	require_once dirname(__FILE__).'/../inc/globals.php';
	require_once dirname(__FILE__).'/aura/globals.php';
	
	authRestritoAdmin();
	header("Content-Type: text/html; charset=UTF-8");

	echo 'Olá, você falou: "'.htmlspecialchars($_REQUEST['command']).'", às ' . date('d/m/Y h:i:s');

	exit();
?>