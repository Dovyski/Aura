<?php

/**
 * Arquivo de configurações
 */

@define('AURA_DB_USER', 		'aura');
@define('AURA_DB_PASSWD', 		'aura');
@define('AURA_DB_NAME', 		'aura');
@define('AURA_DB_HOST', 		'localhost');

// Se true, Aura irá inserir automaticamente na lista de dispositivos qualquer
// dispositivo que envie informações ao cérebro através do aura-cli.

@define('AURA_AUTO_INCLUDE_DEVICES', true);

// Where Aura should store spyglass temp files.
@define('AURA_SPYGLASS_WORKING_FOLDER', '/home/fernando/spyglass/');

// Controle do site
define('AURA_DEBUG', 				true);
define('AURA_SESSION_NAME', 		'aurasid');

?>
