<?php
	//header("Content-Type: audio/mpeg");

	require_once dirname(__FILE__).'/inc/globals.php';

	authAllowAdmin();

	$aText = isset($_REQUEST['text']) ? $_REQUEST['text'] : '';

	if($aText != '') {
		$aText 		= substr($aText, 0, 100); // Limit text size to 100 chars, because Google Translate API has that limit
		$aHash  	= md5($aText);
		$aFile 		= sys_get_temp_dir() . 'aura-speak-' . $aHash . '.mp3';
		$aContent 	= '';

		if (!file_exists($aFile)) {
			$aContent = file_get_contents('http://translate.google.com/translate_tts?el=UTF-8&tl=pt-BR&q=' . urlencode($aText));

			file_put_contents($aFile, $aContent);
		} else {
			$aContent = file_get_contents($aFile);
		}

		echo $aContent;
	}
?>
