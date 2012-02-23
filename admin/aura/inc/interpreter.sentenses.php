<?php 

/**
 * Interpreta comandos em linguagem "pseudo-estruturada" e em linguagem natural
 * (da melhor forma possível, lógico...)
 */

namespace Aura\Interpreter;

class Sentenses {
	public static $mSentenses = array(
		array(
			'pattern' => '/(adicione|coloque|bote|ponha) ([\w\W]*) (ao|no) grupo ([\w\W]*).?/',
			'command' => AURA_ADD_GROUP_MEMBER,
			'indexes'	  => array(2, 4),
		),
		array(
			'pattern' => '/(hora|data).?/',
			'command' => AURA_DATE,
		),
	);
}

?>