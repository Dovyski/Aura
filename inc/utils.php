<?php

function utilIsNavegandoIntranet() {
	return strstr($_SERVER['REQUEST_URI'], '/admin/') !== false;
}

?>