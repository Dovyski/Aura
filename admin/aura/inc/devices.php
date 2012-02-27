<?php 

/**
 * Gerenciamento e manipulação de dispositivos.
 */

namespace Aura;

class Devices {
	const TYPE_DESKTOP		= 1;
	const TYPE_LAPTOP		= 2;
	const TYPE_MOBILE		= 3;
	
	public static function update($theInfo) {
		if(empty($theInfo['name'])) {
			throw new \Exception('O nome do dispositivo não pode ser vazio.');
		}
		
		$aInfo = Utils::prepareForSql($theInfo);
		Db::execute("INSERT INTO ".Db::TABLE_DEVICES." (`".implode("`,`", array_keys($aInfo))."`) VALUES (".implode(',', $aInfo).")");
	}

	public static function getByClue($theClue) {
		$aRet	 = null;
		$aWhere  = is_numeric($theClue) ? "id = ".$theClue : "name LIKE '".addslashes($theClue)."' OR alias LIKE '%".addslashes($theClue)."%'";
		$aResult = Db::execute("SELECT * FROM ".Db::TABLE_DEVICES." WHERE ". $aWhere);
		
		if(Db::numRows($aResult) == 1) {
			$aRet = Db::fetchAssoc($aResult);
		}
		
		return $aRet;
	}

	public static function getByHash($theDeviceId) {

	}
}


?>