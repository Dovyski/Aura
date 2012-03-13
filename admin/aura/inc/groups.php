<?php 

/**
 * Gerenciamento e manipulação de grupos.
 */

namespace Aura;

class Groups {
	public static function update($theInfo) {
		if(empty($theInfo['name'])) {
			throw new \Exception('O nome do grupo não pode ser vazio.');
		}
		
		$aInfo = Utils::prepareForSql($theInfo); 
		Db::execute("INSERT INTO ".Db::TABLE_GROUPS." (`".implode("`,`", array_keys($aInfo))."`) VALUES (".implode(',', $aInfo).")");
	}
	
	private static function checkGroupIsValid($theGroupId) {
		if(self::getByClue($theGroupId) == null) {
			throw new \Exception('O grupo informado não existe.');
		}
	}
	
	private static function checkDeviceIsValid($theDeviceId) {
		if(Devices::getByClue($theDeviceId) == null) {
			throw new \Exception('O dispositivo informado não existe.');
		}
	}
	
	private static function checkUserIsValid($theUserLogin) {
		if(Users::getByLogin($theUserLogin) == null) {
			throw new \Exception('Não conheço a pessoa informada.');
		}
	}
	
	public static function isDeviceMemberOf($theGroupId, $theDeviceId) {
		$theGroupId  = (int)$theGroupId;
		$theDeviceId = (int)$theDeviceId;
		$aResult 	 =  Db::execute("SELECT fk_group FROM ".Db::TABLE_GROOMING." WHERE fk_group = ".$theGroupId." AND fk_device = ".$theDeviceId);
		
		return Db::numRows($aResult) == 1;
	}
	
	public static function isUserMemberOf($theGroupId, $theUserLogin) {
		$theGroupId   = (int)$theGroupId;
		$theUserLogin = addslashes($theUserLogin);
		$aResult 	  = Db::execute("SELECT fk_group FROM ".Db::TABLE_TEAMS." WHERE fk_user LIKE '".$theUserLogin."' AND fk_group = ".$theGroupId);
	
		return Db::numRows($aResult) == 1;
	}
	
	public static function addDevice($theGroupId, $theDeviceId) {
		$theGroupId  = (int)$theGroupId;
		$theDeviceId = (int)$theDeviceId;
		
		self::checkGroupIsValid($theGroupId);
		self::checkDeviceIsValid($theDeviceId);
		
		if(self::isDeviceMemberOf($theGroupId, $theDeviceId)) {
			throw new \Exception('O dispositivo já faz parte desse grupo.');
		}

		return Db::execute("INSERT INTO ".Db::TABLE_GROOMING." (fk_group, fk_device, time) VALUES (".$theGroupId.",".$theDeviceId.",".time().")");
	}
	
	public static function removeDevice($theGroupId, $theDeviceId) {
		$theGroupId  = (int)$theGroupId;
		$theDeviceId = (int)$theDeviceId;
		
		self::checkGroupIsValid($theGroupId);
		self::checkDeviceIsValid($theDeviceId);
		
		return Db::execute("DELETE FROM ".Db::TABLE_GROOMING." WHERE fk_group = ".$theGroupId." AND fk_device = ".$theDeviceId);
	}
	
	public static function addUser($theGroupId, $theUserLogin) {
		$theGroupId   = (int)$theGroupId;
		$theUserLogin = addslashes($theUserLogin);
		
		self::checkGroupIsValid($theGroupId);
		self::checkUserIsValid($theUserLogin);
		
		if(self::isUserMemberOf($theGroupId, $theUserLogin)) {
			throw new \Exception('Essa pessoa já faz parte desse grupo.');
		}

		return Db::execute("INSERT INTO ".Db::TABLE_TEAMS." (fk_user, fk_group) VALUES ('".$theUserLogin."',".$theGroupId.")");
	}
	
	public static function removeUser($theGroupId, $theUserLogin) {
		$theGroupId   = (int)$theGroupId;
		$theUserLogin = addslashes($theUserLogin);
		
		self::checkGroupIsValid($theGroupId);
		self::checkUserIsValid($theUserLogin);
		
		return Db::execute("DELETE FROM ".Db::TABLE_TEAMS." WHERE fk_user LIKE '".$theUserLogin."' AND fk_group = ".$theGroupId);	
	}

	public static function findDevices($theGroupId) {
		$aRet	 	= array();
		$theGroupId = (int)$theGroupId;
		
		$aResult = Db::execute("SELECT fk_device FROM ".Db::TABLE_GROOMING." WHERE fk_group = ". $theGroupId);
		
		if(Db::numRows($aResult) > 0) {
			while($aRow = Db::fetchAssoc($aResult)) {
				$aRet[] = (int)$aRow['fk_device'];
			}
		}
		
		return $aRet;
	}
	
	public static function findUsers($theGroupId) {
		$aRet	 	= array();
		$theGroupId = (int)$theGroupId;
		
		$aResult = Db::execute("SELECT fk_user FROM ".Db::TABLE_TEAMS." WHERE fk_group = ". $theGroupId);
		
		if(Db::numRows($aResult) > 0) {
			while($aRow = Db::fetchAssoc($aResult)) {
				$aRet[] = $aRow['fk_user'];
			}
		}
		
		return $aRet;
	}
	
	public static function getByClue($theClue) {
		$aRet	 = null;
		$aWhere  = is_numeric($theClue) ? "id = ".$theClue : "name LIKE '".addslashes($theClue)."' OR alias LIKE '%".addslashes($theClue)."%'";
		$aResult = Db::execute("SELECT * FROM ".Db::TABLE_GROUPS." WHERE ". $aWhere);
		
		if(Db::numRows($aResult) == 1) {
			$aRet = Db::fetchAssoc($aResult);
		}
		
		return $aRet;
	}
	
	public static function findByName($theName) {
		$aRet	 = array();
		$theName = addslashes($theName);
		$aResult = Db::execute("SELECT * FROM ".Db::TABLE_GROUPS." WHERE name LIKE '".$theName."' OR alias LIKE '".$theName."'");
	
		if(Db::numRows($aResult) > 0) {
			while($aRow = Db::fetchAssoc($aResult)) {
				$aRet[] = $aRow;
			}
		}
	
		return $aRet;
	}
	
	/**
	 * Remove um grupo do banco de dados, com base em uma pista. Se a pista for
	 * um número, ela será tratada como o id; se for uma string, será considerada o nome.
	 * 
	 * @param mixed $theSlug um inteiro representando o id do grupo, ou uma string representando o nome do grupo.
	 * @return boolean true se conseguiu apagar algo, ou false caso contrário.
	 */
	public static function removeByClue($theClue) {
		$aWhere = is_numeric($theClue) ? "id = ".$theClue : "name like '".addslashes($theClue)."'"; 
		return Db::execute("DELETE FROM ".Db::TABLE_GROUPS." WHERE ". $aWhere);
	} 
}

?>