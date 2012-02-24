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

			foreach(self::$mSentenses as $aFuncion => $aInfo) {
				$aParams = array();
				$aMatchs = array();
				
				if(preg_match_all($aInfo['pattern'], $aText, $aMatchs)) {
					if(MODO_DEBUG) {
						echo '<small>Match para '.$aFuncion.'() - "'.$aInfo['pattern'].'"</small>';
						var_dump($aMatchs);
					}
					
					if(count($aInfo['indexes']) > 0) {
						foreach($aInfo['indexes'] as $aIndex) {
							$aParams[] = count($aMatchs[$aIndex]) == 1 ? $aMatchs[$aIndex][0] : $aMatchs[$aIndex];
						}
					} else {
						$aParams = $aMatchs;
					}

					$aRet = call_user_func_array($aFuncion, $aParams);
					break;
				}
				
				unset($aParams);
				unset($aMatchs);
			}
		}
		return $aRet;
	}
	
	public static function addSentenseHandler($theFunction, $thePattern, $theWantedIndexes = array()) {
		self::$mSentenses[$theFunction] = array(
			'pattern' => $thePattern,
			'indexes' => $theWantedIndexes
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