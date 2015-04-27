<?php

/**
 * Gerenciamento e manipulação de tarefas.
 */

namespace Aura;

class Tasks {
	const STATUS_SCHEDULED			= 0;
	const STATUS_RUNNING			= 1;
	const STATUS_WAITING			= 2;
	const STATUS_PAUSED				= 3;
	const STATUS_COMPLETED			= 4;

	private static $mKnownStatus	= array(
		self::STATUS_SCHEDULED,
		self::STATUS_SCHEDULED,
		self::STATUS_RUNNING,
		self::STATUS_WAITING
	);

	/**
	 * Ordena que um grupo de dispositivos execute um determinado comando.
	 * Exemplo:
		Aura\Tasks::add(
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
	 *
	 * @param array $theTask array assossiativo com os campos do comando em questão, no formato [campo] => valor.
	 * @param array $theDevices array com os ids dos dispositivos que deverão executar o comando. Se esse parâmetro não for informado, entende-se que a ordem é para a Aura em si. O método *não* testa se os ids são válidos.
	 */
	public static function add($theTask, $theDevices = array()) {
		if(!is_array($theTask) || count($theTask) == 0) {
			throw new \Exception('Informações inválidas para criação de uma tarefa.');
		}

		if(!isset($theTask['status']) || !in_array($theTask['status'], self::$mKnownStatus)) {
			throw new \Exception('O status "'.$theTask['status'].'" é inválido para uma tarefa.');
		}

		if(empty($theTask['exec'])) {
			throw new \Exception('Nenhuma ordem atribuida à tarefa.');
		}

		$aDevices = Devices::findByIds($theDevices);
		if(count($theDevices) != count($aDevices)) {
			foreach($theDevices as $aId) {
				if(!isset($aDevices[$aId])) {
					throw new \Exception('O dispositivo de id "'.$aId.'" não existe.');
				}
			}
		}

		$aInfo = Utils::prepareForSql($theTask);
		Db::execute("INSERT INTO ".Db::TABLE_TASKS." (`".implode("`,`", array_keys($aInfo))."`) VALUES (".implode(',', $aInfo).")");

		$aIdTask 	= Db::lastInsertedId();
		$aValuesDevices = "";

		if(count($theDevices) > 0) {
			// A ordem é para um monte de dispositivos. Vamos inserir
			// a ordem para cada um deles.
			foreach($theDevices as $aIdDevice) {
				$aValuesDevices .= "(".$aIdTask.", ".$aIdDevice.", 0, 0, ''),";
			}
			$aValuesDevices = substr($aValuesDevices, 0, strlen($aValuesDevices) - 1);
		} else {
			// A ordem é para a Aura.
			$aValuesDevices = "(".$aIdTask.", 0, 0, 0, '')";
		}

		Db::execute("INSERT INTO ".Db::TABLE_TASKS_LOG." (fk_task, fk_device, time_start, time_end, result) VALUES ".$aValuesDevices);
	}

	public static function getById($theId) {
		$aRet	 = null;
		$theId	 = (int)$theId;
		$aResult = Db::execute("SELECT * FROM ".Db::TABLE_TASKS." WHERE id = ". $theId);

		if(Db::numRows($aResult) == 1) {
			$aRet = Db::fetchAssoc($aResult);
		}

		return $aRet;
	}

	/**
	 * Remove um comando da lista de comandos.
	 *
	 * @param int $theId id do comando a ser removido.
	 * @return bool true se o comando foi removido, ou false caso contrário (comando não existe).
	 */
	public static function removeById($theId) {
		$aRet	 = false;
		$theId	 = (int)$theId;

		if(self::getById($theId) !== null) {
			Db::execute("DELETE FROM ".Db::TABLE_TASKS_LOG." WHERE fk_task = " . $theId);
			Db::execute("DELETE FROM ".Db::TABLE_TASKS." WHERE id = " . $theId);
			$aRet = true;
		}

		return $aRet;
	}

	/**
	 * Atualiza a tabela de logs de comandos.
	 *
	 * @param int $theIdDevice id do dispositivo que está atualizado os logs.
	 * @param int $theIdTask id do comando que o dispositivo está executando.
	 * @param array $theData array assossiativo com os dados a serem atualizados. As chaves desse array devem ser os campos do banco de dados, no formato [campo] => valor.
	 * @return  bool true se tudo deu certo, ou false caso contrário.
	 */
	public static function updateLog($theIdDevice, $theIdTask, $theData) {
		$theIdDevice 	= (int)$theIdDevice;
		$theIdTask	= (int)$theIdTask;
		$aCheck			= !empty($theData['time_end']) && is_numeric($theData['time_end']) && $theData['time_end'] > 0;

		if(!is_array($theData) || count($theData) == 0) return false;

		$aSets = Utils::generateUpdateStatement($theData);
		$aRet  = Db::execute("UPDATE ".Db::TABLE_TASKS_LOG." SET ".$aSets." WHERE fk_task = ".$theIdTask." AND fk_device = ".$theIdDevice);

		if($aRet && $aCheck && self::countDevicesRunningTask($theIdTask) == 0) {
			// Todos os envolvidos em executar uma tarefa completaram ela. Vamos
			// atualizar a tupla de comandos para refletir isso.

			Db::execute("UPDATE ".Db::TABLE_TASKS." SET status = ".self::STATUS_COMPLETED." WHERE status = ".self::STATUS_RUNNING." AND id = ".$theIdTask);
		}

		return $aRet;
	}

	private static function countDevicesRunningTask($theTaskId) {
		$aRet	 		= 0;
		$theTaskId	= (int)$theTaskId;
		$aResult 		= Db::execute("SELECT COUNT(*) AS count FROM ".Db::TABLE_TASKS_LOG." WHERE time_end = 0 AND fk_task = ". $theTaskId);

		if(Db::numRows($aResult) == 1) {
			$aTemp = Db::fetchAssoc($aResult);
			$aRet  = (int)$aTemp['count'];
		}

		return $aRet;
	}

	public static function findPendingTasksByDevice($theDeviceId) {
		$aRet	 		= array();
		$theDeviceId 	= (int)$theDeviceId;
		$aResult 		= Db::execute("SELECT
											c.id, c.priority, c.time, c.exec

									   FROM ".Db::TABLE_TASKS." AS c JOIN
									   		".Db::TABLE_TASKS_LOG." AS l ON c.id = l.fk_task

									   WHERE l.fk_device = ".$theDeviceId." AND l.time_end = 0 AND c.status = " . self::STATUS_RUNNING . "
									   ORDER BY c.priority ASC");

		if(Db::numRows($aResult) >= 0) {
			while($aRow = Db::fetchAssoc($aResult)) {
				$aRet[$aRow['id']] = $aRow;
			}
		}

		return $aRet;
	}

	public static function findLastTaskByDevices($theDeviceIds) {
		$aRet	 		= array();
		$theDeviceIds 	= Utils::prepareForSql($theDeviceIds);
		$aDeviceIds		= implode(',', $theDeviceIds);

		$aResult 		= Db::execute("SELECT *
									   FROM (
											SELECT
												*
											FROM ".Db::TABLE_TASKS_LOG."
											WHERE fk_device IN (".$aDeviceIds.")
											ORDER BY fk_task DESC
									   ) AS sub
									   GROUP BY fk_device");

		if(Db::numRows($aResult) >= 0) {
			while($aRow = Db::fetchAssoc($aResult)) {
				$aRet[$aRow['fk_device']] = $aRow;
			}
		}

		return $aRet;
	}
}

?>
