<?php 

namespace Aura;

class Utils {
	/** 
	 * Transforma uma lista de parâmetros em texto no formato "param=value" em um array
	 * assossiativo indexado pelo nome do parâmetro.
	 * 
	 * @param texto $theText lista de parâmetros, no formato <code>param="dser", param2=felreori, param3=54, param4=rekrj lkjerk</code>.
	 * @return array vetor assossiativo no formato [param] => valor.
	 */
	public static function parseParamList($theText) {
		$aRet = array();
		
		if(!empty($theText)) {
			$aMatchs = array();
			preg_match_all('/((?:"[^"]*"|[^=,])*)=((?:"[^"]*"|[^=,])*)/', $theText, $aMatchs);

			foreach($aMatchs[1] as $aIndex => $aName) {
				$aKey 			= trim($aName);
				$aRet[$aKey] 	= trim($aMatchs[2][$aIndex]);
				
				if($aRet[$aKey][0] == '"' && $aRet[$aKey][strlen($aRet[$aKey]) - 1] == '"') {
					$aSize = strlen($aRet[$aKey]);
					$aRet[$aKey] = substr($aRet[$aKey], 1, $aSize - 2);
				} 
			}
		}
		
		return $aRet;
	}
	
	/** 
	 * Escapa todos os caractares nocivos que estejam como valor de um array
	 * assossiativo, tornando a insersão desse array no banco de dados segura.
	 * 
	 * @param array $theArray array assossiativo no formato [chave] => valor.
	 * @return array um array assossiativo com os valores escapados.
	 */
	public static function prepareForSql($theArray) {
		$aRet = array();
		
		if(is_array($theArray) && count($theArray) > 0) {
			foreach($theArray as $aKey => $aValue) {
				$aRet[$aKey] = is_numeric($aValue) ? $aValue : "'".addslashes($aValue)."'";
			}
		}
		
		return $aRet;
	}
	
	public static function generateUpdateStatement($theArray) {
		$aRet = "";
		$aEscaped = self::prepareForSql($theArray);
		
		foreach($aEscaped as $aField => $aValue) {
			$aRet .= $aField . " = " . $aValue . ",";
		}
		$aRet = substr($aRet, 0, strlen($aRet) - 1);
		return $aRet;
	}
} 

?>