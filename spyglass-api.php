<?php
	require_once dirname(__FILE__).'/inc/globals.php';

	$aAction 		= isset($_REQUEST['action']) 	? $_REQUEST['action'] 	: 'feed';
	$aHash	 		= isset($_REQUEST['hash']) 		? $_REQUEST['hash'] 	: ''; // TODO: secure/sanitize HASH!

	$aContentType 	= 'Content-Type: application/json';
	$aOut			= '';

	switch($aAction) {
		case 'save-client-data':
			$aRet = Aura\Spyglass::run($aHash);
			$aOut = json_encode($aRet);
			break;

		case 'save-web-interactions':
			$aRet = Aura\Spyglass::saveInteractions($aHash, $_REQUEST);
			$aOut = json_encode($aRet);
			break;

		case 'client-connection-info':
			$aRet = Aura\Spyglass::getClientConnectionInfo($aHash);
			$aOut = json_encode($aRet);
			break;

		case 'feed':
		default:
			// TODO: secure this action somehow. Authentication, password, anything.
			$aContentType = 'image/jpeg';
			$aOut = Aura\Spyglass::getFrame($aHash);
			break;
	}

	header('Content-Type: ' . $aContentType);
	echo $aOut;
?>
