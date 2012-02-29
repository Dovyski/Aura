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
	
	public static function getByClue($theClue) {
		$aRet	 = null;
		$theClue = addslashes($theClue); 
		$aResult = Db::execute("SELECT * FROM ".Db::TABLE_USERS." WHERE login LIKE '".$theClue."' OR alias LIKE '%".$theClue."%'");
		
		if(Db::numRows($aResult) == 1) {
			$aRet = Db::fetchAssoc($aResult);
		}
		
		return $aRet;
	}
	
	public static function getByLogin($theLogin) {
		$aRet	 	= null;
		$theLogin	= addslashes($theLogin);
		$aResult 	= Db::execute("SELECT * FROM ".Db::TABLE_USERS." WHERE login LIKE '". $theLogin . "'");
	
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
	public static function removeByLogin($theLogin) {
		$aRet	 	= false;
		$theLogin	= addslashes($theLogin);
		
		if(self::getByLogin($theLogin) !== null) {
			Db::execute("DELETE FROM ".Db::TABLE_USERS." WHERE login LIKE '" . $theLogin."'");
			$aRet = true;
		}
		
		return $aRet;
	}
}
?>