<?php 

/**
 * Interpreta comandos em linguagem "pseudo-estruturada" e em linguagem natural
 * (da melhor forma possível, lógico...)
 */

namespace Aura;

class Interpreter {
	private static $mSentenses = array();
	
	/**
	 * Analisa uma frase, executando o comando que melhor se enquadra no que foi interpretado.
	 * A análise das frases é feita com a ajuda de plugins.
	 * 
	 * @param string $theSentense frase a ser interpretada.
	 * @return mixed false se não conseguiu interpretar e executar algo, ou algum texto (retorno do plugin) em caso de sucesso.
	 */
	public static function process($theSentense) {
		$aRet = false;
		
		if(!empty($theSentense)) {
			$aText  	= strtolower(preg_replace('/\s+/', ' ', $theSentense));
			$aMatchs 	= array();

			foreach(self::$mSentenses as $aHash => $aInfo) {
				$aParams = array();
				$aMatchs = array();

				if(preg_match_all($aInfo['pattern'], $aText, $aMatchs)) {
					if(count($aInfo['indexes']) > 0) {
						foreach($aInfo['indexes'] as $aIndex) {
							$aParams[] = count($aMatchs[$aIndex]) == 1 ? $aMatchs[$aIndex][0] : $aMatchs[$aIndex];
						}
					} else {
						$aParams = $aMatchs;
					}

					try {
						$aRet = call_user_func_array($aInfo['function'], $aParams);						
					} catch(\Exception $e) {
						$aRet = '';
						echo 'Opa, algum erro acontenceu. '.$e->getMessage();
					}

					if(MODO_DEBUG) {
						echo '<br /><br /><small>Match para '.$aInfo['function'].'() - "'.$aInfo['pattern'].'"</small>';
						var_dump($aMatchs);
					}
					break;
				}
				
				unset($aParams);
				unset($aMatchs);
			}
		}
		return $aRet;
	}
	
	public static function addSentenseHandler($theFunction, $thePattern, $theWantedIndexes = array()) {
		$aHash = md5($theFunction . $thePattern . implode('', $theWantedIndexes));
		
		self::$mSentenses[$aHash] = array(
			'function' => $theFunction,
			'pattern'  => $thePattern,
			'indexes'  => $theWantedIndexes
		);
	}
	
	public static function loadSentenseHandlers() {
		$aPath	  = dirname(__FILE__).'/sentenses/';
		$aPlugins = scandir($aPath);

		foreach($aPlugins as $aFile) {
			if($aFile != '.' && $aFile != '..') {
				require $aPath . $aFile;
			}
		}
	}
}

?>