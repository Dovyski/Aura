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
	
	/** 
	 * Obtem informações sobre um grupo de dispositivos.
	 * 
	 * @param array $theIds array com os ids dos dispositivos a serem buscados.
	 * @param bool $theSimplificado se false (default), todos os dados dos dispositivos serão retornados, caso contrário apenas o nome e o id.
	 * @return array array assossiativo com informações dos dispositivos.
	 */
	public static function findByIds($theIds, $theSimplificado = false) {
		$aRet	 		= array();
		$aIds 			= Utils::prepareForSql($theIds);
		$aResult 		= Db::execute("SELECT ".($theSimplificado ? "id,name" : "*")." FROM ".Db::TABLE_DEVICES." WHERE id IN (".implode(',', $aIds).")");
		
		if(Db::numRows($aResult) > 0) {
			while($aRow = Db::fetchAssoc($aResult)) {
				$aRet[$aRow['id']] = $aRow;
			}
		}
		
		return $aRet;
	}
}


?>