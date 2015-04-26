<?php

/**
 * Handles the server-side code of Spyglass.
 */

namespace Aura;

class Spyglass {
	private static function getBasePath($theDeviceHash) {
		return AURA_SPYGLASS_WORKING_FOLDER . $theDeviceHash;
	}

	public static function run($theDeviceHash) {
		self::saveFrame($theDeviceHash);
		file_put_contents(self::getBasePath($theDeviceHash) .'-timestamp', time());

		return self::getRemoteInteractions($theDeviceHash);
	}

	private static function saveFrame($theDeviceHash) {
		file_put_contents(self::getBasePath($theDeviceHash) .'.jpg', file_get_contents('php://input'));
	}

	private static function getRemoteInteractions($theDeviceHash) {
		$aActions = glob(self::getBasePath($theDeviceHash) . '-interactions-*');
		$aRet = '';

		foreach($aActions as $aFile) {
			$aRet .= file_get_contents($aFile);
			unlink($aFile);
		}

		return $aRet;
	}

	public static function saveInteractions($theDeviceHash, $theRequest) {
		file_put_contents(self::getBasePath($theDeviceHash) .'-interactions-' . microtime(), $theRequest['interactions']);
		return array('success' => true);
	}

	public static function getFrame($theDeviceHash) {
		// TODO: secure hash coming from URL
		return file_get_contents(self::getBasePath($theDeviceHash) .'.jpg');
	}

	// TODO: secure hash coming from URL
	public static function getClientConnectionInfo($theDeviceHash) {
		return array(
			'current_timestamp' => time(),
			'last_timestamp' 	=> file_get_contents(self::getBasePath($theDeviceHash) .'-timestamp') + 0
		);
	}
}

?>
