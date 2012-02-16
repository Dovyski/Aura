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
	// TODO: proteger os dados com algum escape?
	
	// Connect
	$aAd = ldap_connect(NCC_LDAP_SERVIDOR);
	
	// Set some variables
	ldap_set_option($aAd, LDAP_OPT_PROTOCOL_VERSION, 3);
	ldap_set_option($aAd, LDAP_OPT_REFERRALS, 0);
	
	// Bind to the ldap directory
	// Adaptado daqui: http://br2.php.net/manual/en/function.ldap-bind.php#105620
	$aBind = @ldap_bind($aAd, sprintf(NCC_LDAP_BIND_RDN, $theUsuario), $theSenha);
	
	// never forget to unbind!
	ldap_unbind($aAd);
	
	return $aBind;
}

function authFindUsuarios($theFilto = '(CN=*)') {
	$aRet 	= array();
	$aAd 	= ldap_connect(NCC_LDAP_SERVIDOR);
	
	// Set some variables
	ldap_set_option($aAd, LDAP_OPT_PROTOCOL_VERSION, 3);
	ldap_set_option($aAd, LDAP_OPT_REFERRALS, 0);
	
	// Bind to the ldap directory
	// Adaptado daqui: http://br2.php.net/manual/en/function.ldap-bind.php#105620
	$aBind  	= @ldap_bind($aAd);
	$aResult	= ldap_search($aAd, NCC_LDAP_BASE_DN_USERS, $theFilto);
	$aEntradas  = ldap_get_entries($aAd, $aResult);

	foreach($aEntradas as $aNum => $aInfo) {
		$aUid = $aInfo['uid'][0];
		
		if(!empty($aUid)) {
			$aRet[$aUid] = array(
				'uid'		=> $aUid,
				'gid'		=> $aInfo['gidnumber'],				
				'dn'		=> $aInfo['dn'],
				'nome'		=> $aInfo['cn'][0],
				'shell'		=> $aInfo['loginshell'][0],
				'home'		=> $aInfo['homedirectory'][0],
				'sambahome'	=> $aInfo['sambahomepath'][0],
				'meta'		=> $aInfo['description'][0]
			);
		} 
	}
	
	ldap_unbind($aAd);
	return $aRet;
}

function authLogin($theUsuario) {
	$_SESSION['logado'] = true;
	$aInfos 			= authFindUsuarios('(uid='.$theUsuario.')');
	 
	if(true) { // TODO: testar se é admin...
		$_SESSION['admin'] 	 = true;
		$_SESSION['usuario'] = $aInfos[$theUsuario];
	}
}

function authRestritoNaoLogado() {
	if(authIsLogado()) {
		header('Location: ' . (authIsAdmin() ? 'admin/' : 'index.php'));
		exit();
	}
}

function authRestritoAdmin() {
	if(!authIsAdmin()) {
		header('Location: ../login.php');
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