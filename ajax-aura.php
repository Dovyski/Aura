<?php
	header("Content-Type: text/html; charset=UTF-8");

	require_once dirname(__FILE__).'/inc/globals.php';

	authRestritoAdmin();

	/*Aura\Tasks::add(
		array(
			'time' => time(),
			'status' => Aura\Tasks::STATUS_RUNNING,
			'exec' => serialize(array(
				'win' => serialize(array('dir', 'php -v', 'javac -version')),
				'linux' => 'sdddd',
				'mac' => 'dsdsd'
			))
		),
		array(1));
	*/
    /*
	Aura\Tasks::add(
			array(
					'time' => time(),
					'status' => Aura\Tasks::STATUS_RUNNING,
					'exec' => serialize(array(
							'win' => 'php -v',
							'linux' => 'sdddd',
							'mac' => 'dsdsd'
					))
			),
			array(1));
    */
	Aura\Interpreter::loadSentenseHandlers();
	$aReturn = Aura\Interpreter::process($_REQUEST['command']);

	if($aReturn === false) {
		echo 'Não entendi o que você falou.';
	}

	exit();
?>
