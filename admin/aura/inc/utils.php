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
	
	public static function generateLabReport($thePingData) {
		$aRet = array(
			'computers' => array(), 
			'users'	 	=> array(),
			'internet' 	=> null,
		);
		
		if(!empty($thePingData)) {
			foreach($thePingData as $aIdDevice => $aPingsDevice) {
				
				$aRet['computers'][] = $aIdDevice;
				
				if(count($aPingsDevice) > 0) {
					foreach($aPingsDevice as $aInfo) {
						$aData = @unserialize($aInfo['data']);
						
						if($aData !== false) {
							if($aData['ping_ip'] <= 10) {
								$aRet['internet'] = true;
							}
							
							if(!empty($aData['users'])) {
								$aUsers = unserialize($aData['users']);
								
								if($aUsers !== false) {
									foreach($aUsers as $aUser) {
										$aRet['users'][$aUser['name']] = true;
									}
								}
							}
						}
					}
				}
			}
		}
		
		if(count($aRet['users']) > 0) {
			$aRet['users'] = array_keys($aRet['users']);
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
	
	public static function normalizeToAsciiText($theText) {
		$aChars = array(
		    'Š'=>'S', 'š'=>'s', 'Ð'=>'Dj','Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 
		    'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 
		    'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 
		    'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss','à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 
		    'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 
		    'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 
		    'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', 'ƒ'=>'f'
		);
		
		return strtr($theText, $aChars);
	}
} 

?>