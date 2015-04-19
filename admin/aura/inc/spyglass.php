<?php

/**
 * Handles the server-side code of Spyglass.
 */

namespace Aura;

class Spyglass {
	public static function run($theDeviceInfo) {
		self::saveFrame($theDeviceInfo);
		return self::getRemoteInteractions($theDeviceInfo);
	}

	private static function saveFrame($theDeviceInfo) {
		// TODO: get this path from config file
		file_put_contents(AURA_SPYGLASS_WORKING_FOLDER . $theDeviceInfo['hash'] .'.jpg', file_get_contents('php://input'));
	}

	private static function getRemoteInteractions($theDeviceInfo) {
		$aActions = glob(AURA_SPYGLASS_WORKING_FOLDER . $theDeviceInfo['hash'] . '-interactions-*');
		$aRet = '';

		foreach($aActions as $aFile) {
			$aRet .= file_get_contents($aFile);
			unlink($aFile);
		}
		
		return $aRet;
	}

	public static function saveInteractions($theDeviceInfo, $theRequest) {
		file_put_contents(AURA_SPYGLASS_WORKING_FOLDER . $theDeviceInfo['hash'] .'-interactions-' . microtime(), $theRequest['interactions']);
		return array('success' => true);
	}

	public static function getFrame($theDeviceHash) {
		// TODO: secure hash coming from URL
		return file_get_contents(AURA_SPYGLASS_WORKING_FOLDER . $theDeviceHash .'.jpg');
	}
}

?>
