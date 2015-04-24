<?php
	require_once dirname(__FILE__).'/inc/globals.php';
	require_once dirname(__FILE__).'/admin/aura/globals.php';

	authRestritoAdmin();

	header("Content-Type: image/jpeg;");
	echo Aura\Spyglass::getFrame($_REQUEST['hash']);
?>
