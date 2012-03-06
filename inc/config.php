<?php

define('URL_TROCA_SENHA', 			'https://central.inf.uffs.edu.br:1234');
define('TAMANHO_HOME', 				'100Mb');
define('EMAIL_NCC', 				'ncc@uffs.edu.br');
define('RESPONSAVEL_NCC_SENHA',		'prof. Fernando Bevilacqua');
define('DRIVE_HOME_WINDOWS',		'H:\\');
define('PASTA_HOME_UBUNTU',			'Pasta pessoal');
define('TEMP_WINDOWS',				'\\\central\temp');

define('LAYOUT_RESPONSIVE',			false);

// Informacoes do dominio 
define('NOME_DOMINIO',				'ncc.lan');
define('HOST_SERVIDOR',				'central.inf.uffs.edu.br');

// Informacoes sobre LDAP.
define('NCC_LDAP_SERVIDOR',			'central.inf.uffs.edu.br');
define('NCC_LDAP_BIND_RDN',			'uid=%s,ou=Users,dc=central,dc=inf,dc=uffs,dc=edu,dc=br');
define('NCC_LDAP_ROOT_DN',			'cn=ebox,dc=central,dc=inf,dc=uffs,dc=edu,dc=br');
define('NCC_LDAP_ROOT_DN_PASSWD',	getenv('NCC_LDAP_ROOT_PASSWD'));
define('NCC_LDAP_BASE_DN_USERS',	'ou=Users,dc=central,dc=inf,dc=uffs,dc=edu,dc=br');
define('NCC_LDAP_BASE_DN_GROUPS',	'ou=Groups,dc=central,dc=inf,dc=uffs,dc=edu,dc=br');
define('NCC_GRUPO_PROFESSORES',		'professores');
define('NCC_GRUPO_ALUNOS',			'alunos');

// Controle do site
define('MODO_DEBUG', 				true);
define('NOME_SESSAO', 				'nccsid');
?>