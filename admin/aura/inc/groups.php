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
	
	public static function getById($theGroupId) {
		
	}

	public static function getDevicesById($theGroupId) {
	
	}
}

?>