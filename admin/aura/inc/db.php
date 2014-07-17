<?php 

/**
 * API para acesso ao bando de dados.
 */

namespace Aura;

class Db {
	const TABLE_COMMANDS 		= "commands";
	const TABLE_USERS 			= "users";
	const TABLE_TEAMS 			= "teams";
	const TABLE_TASKS 			= "tasks";
	const TABLE_TASKS_LOG 		= "tasks_log";
	const TABLE_DEVICES 		= "devices";
	const TABLE_GROUPS 			= "groups";
	const TABLE_GROOMING 		= "grooming";
	const TABLE_PINGS 			= "pings";
	const TABLE_ACTIVE_USERS 	= "active_users";
	const TABLE_ACTIVE_DEVICES 	= "active_devices";
	
	private static $mConnection = null;	
	
	private static function error($theQuery) {
		throw new \Exception(mysql_error() . ' ['.$theQuery.']', mysql_errno());
	}
	
	private static function connect() {
		self::$mConnection = mysql_connect(AURA_DB_HOST, AURA_DB_USER, AURA_DB_PASSWD) or self::error('connect');
		mysql_select_db(AURA_DB_NAME) or self::error('select_db');
		mysql_set_charset("utf8", self::$mConnection);
	}
	
	private static function query($theSql) {
		$aRet = mysql_query($theSql, self::$mConnection) or self::error($theSql);
		return $aRet;
	}
	
	public static function execute($theQuery) {
		if(self::$mConnection == null) {
			self::connect();
		}
	
		return self::query($theQuery);
	}
	
	public static function numRows($theResult) {
		return mysql_num_rows($theResult);
	}

	public static function fetchAssoc($theResult) {
		return mysql_fetch_assoc($theResult);
	}
	
	public static function lastInsertedId() {
		return mysql_insert_id();
	}
}

?>