<?php

require_once dirname(__FILE__).'/config.php';

session_start();
session_name(NOME_SESSAO);

require_once dirname(__FILE__).'/utils.php';
require_once dirname(__FILE__).'/ldap.php';
require_once dirname(__FILE__).'/auth.php';
require_once dirname(__FILE__).'/layout.php';

?>