<?php

require_once dirname(__FILE__).'/config.php';

/** 
 * Conecta ao LDAP e tenta fazer uma liga��o de um usu�rio com uma senha. Se
 * o usu�rio e a senha forem corretas, ent�o quer dizer que esse usu�rio � v�lido. 
 * 
 * @param string $theUsuario nome do usu�rio, ex.: fernando
 * @param string $theSenha senha do usu�rio.
 * @return bool <code>true</code> se o usu�rio foi autenticado com sucesso, ou <code>false</code> caso contr�rio.
 */
function ldapBindUsuario($theUsuario, $theSenha) {
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

/** 
 * Faz uma busca na base de dados LDAP. A conex�o com a base � feita
 * de forma an�nima (bind sem usu�rio e senha).
 * 
 * @param string $theBaseDN DN b�sico do diret�rio LDAP. Ex.: ou=Groups,dc=central,dc=inf,dc=uffs,dc=edu,dc=br
 * @param string $theFiltro filtro que ser� aplicado � busca ao LDAP. Ex.: (CN=Fulano).
 * @return array array de elementos relacionados com a busca.
 */
function ldapFind($theBaseDN, $theFiltro = '(CN=*)') {
	$aAd = ldap_connect(NCC_LDAP_SERVIDOR);
	
	// Set some variables
	ldap_set_option($aAd, LDAP_OPT_PROTOCOL_VERSION, 3);
	ldap_set_option($aAd, LDAP_OPT_REFERRALS, 0);
	
	// Bind to the ldap directory
	// Adaptado daqui: http://br2.php.net/manual/en/function.ldap-bind.php#105620
	$aBind  	= @ldap_bind($aAd);
	$aResult	= ldap_search($aAd, $theBaseDN, $theFiltro);
	$aEntradas  = ldap_get_entries($aAd, $aResult);
	
	ldap_unbind($aAd);
	return $aEntradas;
}

/**
* Atualiza os dados de um usuário na base de dados. Por questões de simplicidade, todos
* os dados de um usuário são
* de forma an�nima (bind sem usu�rio e senha).
*
* @param string $theBaseDN DN b�sico do diret�rio LDAP. Ex.: ou=Groups,dc=central,dc=inf,dc=uffs,dc=edu,dc=br
* @param string $theFiltro filtro que ser� aplicado � busca ao LDAP. Ex.: (CN=Fulano).
* @return array array de elementos relacionados com a busca.
*/
function ldapUpdateUser($theUser, $theData) {
	$aAd = ldap_connect(NCC_LDAP_SERVIDOR);

	// Set some variables
	ldap_set_option($aAd, LDAP_OPT_PROTOCOL_VERSION, 3);
	ldap_set_option($aAd, LDAP_OPT_REFERRALS, 0);
	
	$aBind 		= @ldap_bind($aAd, NCC_LDAP_ROOT_DN, NCC_LDAP_ROOT_DN_PASSWD);
	$aResult	= ldap_modify($aAd, sprintf(NCC_LDAP_BIND_RDN, $theUser), array('description' => $theData));
	
	ldap_unbind($aAd);
	return $aResult;
}

function ldapGetUsuarioByLogin($theUsuario) {
	$aRet	= null;
	$aItens = ldapFind(NCC_LDAP_BASE_DN_USERS, '(uid='.$theUsuario.')');

	if($aItens['count'] == 1) {
		foreach($aItens[0] as $aChave => $aInfo) {
			if(!is_numeric($aChave) && $aChave != 'count') {
				$aRet[$aChave] = $aInfo['count'] == 1 ? $aInfo[0] : $aInfo;				
				if(is_array($aRet[$aChave])) {
					unset($aRet[$aChave]['count']);
				}
			}
		}
	}
	return $aRet;
}

function ldapFindGrupos($theNomeGrupo) {
	$aRet 	= array();
	$aItens = ldapFind(NCC_LDAP_BASE_DN_GROUPS, '(CN='.$theNomeGrupo.')');

	if($aItens['count'] > 0) {
		foreach($aItens as $aGrupo) {
			$aId = $aGrupo['cn'][0];
			
			if(!empty($aId)) {
				$aRet[$aId] = array(
					'guidnumber'		=> $aGrupo['gidnumber'][0],
					'descricao'			=> isset($aGrupo['description'][0]) ? $aGrupo['description'][0] : '',
					'nome'				=> isset($aGrupo['displayname'][0]) ? $aGrupo['displayname'][0] : '',
					'membros'			=> isset($aGrupo['memberuid']) ? $aGrupo['memberuid'] : array(),
					'dn'				=> $aGrupo['dn']
				);
				
				if(isset($aRet[$aId]['membros']) && is_array($aRet[$aId]['membros'])) {
					unset($aRet[$aId]['membros']['count']);
				}
			}
		}	
	}

	return $aRet;
}

?>