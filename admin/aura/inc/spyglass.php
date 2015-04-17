<?php

/**
 * Handles the server-side code of Spyglass.
 */

namespace Aura;

class Spyglass {
	public static function run($theDeviceInfo) {
		// TODO: get this path from config file
		file_put_contents('c:\wamp\\' . $theDeviceInfo['hash'] .'.jpg', file_get_contents('php://input'));
	}

	public static function getFrame($theDeviceHash) {
		// TODO: secure hash coming from URL
		return file_get_contents('c:\wamp\\img' . $theDeviceHash .'.jpg');
	}
}

?>
