<?php

/**
 * Gerenciamento e manipulação de tarefas. 
 */

namespace Aura;

class Users {
	const STUDENT			= 0;
	const PROFESSOR			= 1;
	const TEC				= 2;
	const OTHER				= 4;
	
	private static $mKnownTypes	= array(
		self::STUDENT,
		self::PROFESSOR,
		self::TEC,
		self::OTHER
	);
	
	public static function getById($theId) {
		$aRet	 = null;
		$theId	 = (int)$theId;
		$aResult = Db::execute("SELECT * FROM ".Db::TABLE_USERS." WHERE id = ". $theId);
	
		if(Db::numRows($aResult) == 1) {
			$aRet = Db::fetchAssoc($aResult);
		}
	
		return $aRet;
	}
	
	/**
	 * Remove um usuário.
	 * 
	 * @param int $theId id do usuário a ser removido.
	 * @return bool true se o usuário foi removido, ou false caso contrário (usuário não existe).
	 */
	public static function removeById($theId) {
		$aRet	 = false;
		$theId	 = (int)$theId;
		
		if(self::getById($theId) !== null) {
			Db::execute("DELETE FROM ".Db::TABLE_USERS." WHERE id = " . $theId);
			$aRet = true;
		}
		
		return $aRet;
	}
}

?>