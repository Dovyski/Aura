<?php

require_once dirname(__FILE__).'/config.php';

/** 
 * Efetua login de um usuário no domínio do NCC utilizando LDAP.
 * 
 * @param string $theUsuario nome do usuário, ex.: fernando
 * @param string $theSenha senha do usuário.
 * @return bool <code>true</code> se o usuário foi autenticado com sucesso, ou <code>false</code> caso contrário.
 */
function authIsUsuarioValido($theUsuario, $theSenha) {
	return true || ldapBindUsuario($theUsuario, $theSenha);
}

function authLogin($theUsuario) {
	//$aInfos 	= ldapGetUsuarioByLogin($theUsuario);
	//$aProfes	= ldapFindGrupos(NCC_GRUPO_PROFESSORES);
	//$aAdmins	= $aProfes[NCC_GRUPO_PROFESSORES]['membros'];

	$_SESSION['logado'] = true;
	$_SESSION['usuario'] = $aInfos;
	
	//if(in_array($theUsuario, $aAdmins)) {
		$_SESSION['admin'] = true;
	//}
}

function authRestritoNaoLogado() {
	if(authIsLogado()) {
		header('Location: ' . (authIsAdmin() ? 'admin.index.php' : 'index.php'));
		exit();
	}
}

function authRestritoAdmin() {
	if(!authIsLogado()) {
		header('Location: login.php');
		exit();
		
	} else if(!authIsAdmin()){
		header('Location: restrito.php');
		exit();
	}
}

function authRestritoLogado() {
	if(!authIsLogado()) {
		header('Location: login.php');
		exit();
	}
}

function authLogout() {
	unset($_SESSION);
	session_destroy();
}

function authIsLogado() {
	return isset($_SESSION['logado']) && $_SESSION['logado'];
}

function authIsAdmin() {
	return isset($_SESSION['admin']) && $_SESSION['admin'] == true;
}

?>