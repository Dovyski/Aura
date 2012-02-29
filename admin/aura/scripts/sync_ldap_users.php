<?php
	/**
	 * Sincroniza todos os usuários da base LDAP com a tabela
	 * de usuários da Aura.
	 */
	require_once dirname(__FILE__).'/../globals.php';
	require_once dirname(__FILE__).'/../../../inc/globals.php';
	
	$aUsers = ldapFind(NCC_LDAP_BASE_DN_USERS);
	unset($aUsers['count']);
	
	echo "Syncronizing LDAP users...\n";
	
	foreach($aUsers as $aInfo) {
		$aProfessors = ldapFindGrupos(NCC_GRUPO_PROFESSORES);
		$aStudents 	 = ldapFindGrupos(NCC_GRUPO_ALUNOS);
		
		$aProfessors = $aProfessors[NCC_GRUPO_PROFESSORES];
		$aStudents	 = $aStudents[NCC_GRUPO_ALUNOS];
		
		$aLogin = addslashes($aInfo['uid'][0]);
		$aName 	= addslashes($aInfo['cn'][0]);
		$aType	= Aura\Users::OTHER;
		
		if(in_array($aLogin, $aProfessors['membros'])) {
			$aType= Aura\Users::PROFESSOR;
			
		} else if(in_array($aLogin, $aProfessors['membros'])) {
			$aType= Aura\Users::STUDENT;
		}
		
		echo " Adding ".$aLogin." (".$aName.")";
		
		Aura\Db::execute("INSERT IGNORE INTO ".Aura\Db::TABLE_USERS." (login, name, type, contact, email, alias) VALUES ('".$aLogin."', '".$aName."', ".$aType.", '', '', '') ON DUPLICATE KEY UPDATE name = '".$aName."', type = ".$aType);
		echo "\n";
	}
	
	echo "All done!\n";
?>