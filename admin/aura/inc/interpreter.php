<?php 

/**
 * Interpreta comandos em linguagem "pseudo-estruturada" e em linguagem natural
 * (da melhor forma possível, lógico...)
 */

namespace Aura;

require_once dirname(__FILE__).'/interpreter.sentenses.php';

class Interpreter {	
	/**
	 * Interpreta uma frase, retornando um árvore com os parâmetros e comandos
	 * encontrados.
	 * 
	 * @param string $theSentense frase a ser interpretada.
	 * @return array vetor assossiativo com todas as informações obtidas na frase em questão.
	 */
	public static function analyze($theSentense) {
		$aRet = array(
			'command' => AURA_NOT_UNDERSTOOD
		);
		 
		if(!empty($theSentense)) {
			$aText  	= strtolower(preg_replace('/\s+/', ' ', $theSentense));
			$aMatchs 	= array();
			
			foreach(Interpreter\Sentenses::$mSentenses as $aInfo) {
				if(preg_match_all($aInfo['pattern'], $aText, $aMatchs)) {
					$aRet['command'] 	= $aInfo['command'];
					
					if(isset($aInfo['indexes'])) {
						foreach($aInfo['indexes'] as $aIndex) {
							$aRet['params'][] = $aMatchs[$aIndex];							
						}
					} else {
						$aRet['params'] = $aMatchs;
					}

					break;
				}
			}
		}

		return $aRet;
	}
}

?>