<?php 

function timeAndDate() {
	echo date('h:i:s d/m/Y');
}

Aura\Interpreter::addSentenseHandler('timeAndDate', '/(hora|data).?/');

?>