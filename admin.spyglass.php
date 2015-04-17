<?php
	require_once dirname(__FILE__).'/inc/globals.php';
	require_once dirname(__FILE__).'/admin/aura/globals.php';

	authRestritoAdmin();

	echo '<html>';
	echo '<head><title>Spyglass</title></head>';

	echo '<body style="margin: 0;">';

	// TODO: secure hash coming from URL
	echo '<img src="admin.spyglass-feed.php?hash='.$_REQUEST['hash'].'&rand='.rand(0, 99999).'" />';

	// TODO: create a proper JS method for this.
	echo '<script type="text/javascript">
			setTimeout(function() { location.reload(); }, 1000);
		  </script>';

	echo '</body>';
	echo '</html>';
?>
