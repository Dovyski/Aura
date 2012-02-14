<?php

require_once dirname(__FILE__).'/config.php';

/** 
 * Efetua login de um usuário no domínio do NCC utilizando LDAP.
 * 
 * @param string $theUsuario nome do usuário, ex.: fernando
 * @param string $theSenha senha do usuário.
 * @return bool <code>true</code> se o usuário foi autenticado com sucesso, ou <code>false</code> caso contrário.
 */
function authLoginDominio($theUsuario, $theSenha) {
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

function authListUsuarios() {
	/**
	 *
	 $ad = ldap_connect("central.inf.uffs.edu.br");

    //Set some variables
    ldap_set_option($ad, LDAP_OPT_PROTOCOL_VERSION, 3);
    ldap_set_option($ad, LDAP_OPT_REFERRALS, 0);
    
    //Bind to the ldap directory
    // Adaptado daqui: http://br2.php.net/manual/en/function.ldap-bind.php#105620
    $bd = ldap_bind($ad, "uid=fernando,ou=Users,dc=central,dc=inf,dc=uffs,dc=edu,dc=br", "arroz44")
        or die("Couldn't bind to AD!");

    //Search the directory
    $result = ldap_search($ad, "ou=Users,dc=central,dc=inf,dc=uffs,dc=edu,dc=br", "(CN=*)");    

    //Create result set
    $entries = ldap_get_entries($ad, $result);
    
    //Sort and print
    echo "User count: " . $entries["count"] . "<br /><br /><b>Users:</b><br />";

    echo '<pre>';
    print_r($entries);
    echo '</pre>';
    
    for ($i=0; $i < $entries["count"]; $i++)
    {
        echo $entries[$i]["displayname"][0]."<br />";
    }

    //never forget to unbind!
    ldap_unbind($ad);
	 */
}

?>