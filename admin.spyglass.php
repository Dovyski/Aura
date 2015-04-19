<?php
	require_once dirname(__FILE__).'/inc/globals.php';
	require_once dirname(__FILE__).'/admin/aura/globals.php';

	authRestritoAdmin();

	$aRandURLs 	= MODO_DEBUG ? '?'.rand(20, 9999) : '';
	$aHash		= $_REQUEST['hash']; // TODO: secure hash coming from URL

	echo '<html>';
	echo '<head>';
		echo '<title>Spyglass</title>';
		echo '<script src="./js/jquery.js'.$aRandURLs.'"></script>';
		echo '<script src="./js/aura.js'.$aRandURLs.'"></script>';
		echo '<script src="./js/aura.spyglass.js'.$aRandURLs.'"></script>';
	echo '</head>';

	echo '<body style="margin: 0;">';

	echo '<img id="screenCanvas" src="" />';

	echo "
		<script type=\"text/javascript\">
			$(function() {
				AURA.spyglass.init('" . $aHash . "');
			});
		</script>";

	echo '</body>';
	echo '</html>';
?>
