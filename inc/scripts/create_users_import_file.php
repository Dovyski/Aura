<?php

/**
 * Cria um arquivo com informações de usuários que pode ser usado pelos scripts do Zentyal para
 * importar/criar usuários em massa. Esse script usa como base um arquivo CSV com os nomes, matrículas
 * e e-mails dos alunos
 * 
 * http://doc.zentyal.org/en/develop.html
 */

require_once dirname(__FILE__) . '/../aura/inc/utils.php';

function createLogin($thePartsName, $theLogins) {
	$aLogin = strtolower($thePartsName[0]);
	$i 		= 1;
	
	while(isset($theLogins[$aLogin])) {
		$aLogin = '';
		for($j = 0; $j < $i; $j++) {
			$aLogin .= strtolower($thePartsName[$j][0]);
		}
		$aLogin .= strtolower($thePartsName[count($thePartsName) - 1]);
		$i++;
	}
	return $aLogin;
}

$aOut 		= array();
$aData 		= array();
$aLogins 	= array();

if(count($argv) < 2) {
	echo "Uso: php ".basename($_SERVER['PHP_SELF'])." lista1.csv lista2.csv ... listaN.csv\n";
	exit(3);
}

for($i = 1; $i <= count($argv) - 1; $i++) {
	if (($handle = @fopen($argv[$i], "r")) !== FALSE) {
		while (($aLine = fgetcsv($handle, 1000, ",")) !== FALSE) {
			$aData[$aLine[2]] = $aLine;
		}
		echo 'Importado: ' . $argv[$i] . "\n";
		fclose($handle);
	} else {
		echo 'Arquivo inexistente: ' . $argv[$i] . "\n";
	}
}

if(count($aData) == 0) {
	echo "Nenhum dado a ser processado.\n";
	exit(1);
}

ksort($aData);

foreach($aData as $aMatricula => $aDados) {
	// CSV computação:
	// N°, CPF, Matrícula, Data de Nascimento, Aluno (nome), e-mail, Fixo, Celular
	//
	// CSV Zentyal
	// $username, $givenname, $surname, $password
	$aName	  	= ucwords(strtolower(Aura\Utils::normalizeToAsciiText($aDados[4])));
	$aPartsName = explode(' ', $aName);
	$aGivenName = $aPartsName[0];
	$aUsername 	= createLogin($aPartsName, $aLogins);
	
	// Registramos o login como ocupado
	$aLogins[$aUsername] = true;
	
	unset($aPartsName[0]);
	
	$aOut[$aUsername] = array(
			'matricula'	=> $aDados[2],
			'username' 	=> $aUsername,
			'givenname' => $aGivenName,
			'surname' 	=> implode(' ', $aPartsName),
			'password' 	=> 'teste'
	);
	
	echo implode(',', $aOut[$aUsername]) . "\n";
}

?>