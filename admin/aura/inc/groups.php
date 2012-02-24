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
	
	public static function getByClue($theClue) {
		$aRet	 = null;
		$aWhere  = is_numeric($theClue) ? "id = ".$theClue : "name like '".addslashes($theClue)."'";
		$aResult = Db::execute("SELECT * FROM ".Db::TABLE_GROUPS." WHERE ". $aWhere);
		
		if(Db::numRows($aResult) == 1) {
			$aRet = Db::fetchAssoc($aResult);
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