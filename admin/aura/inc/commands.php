<?php

/**
 * Gerenciamento e manipulação de comandos. 
 */

namespace Aura;

class Commands {
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
	 * 
	 * @param array $theCommand array assossiativo com os campos do comando em questão, no formato [campo] => valor.
	 * @param array $theDevices array com os ids dos dispositivos que deverão executar o comando. Se esse parâmetro não for informado, entende-se que a ordem é para a Aura em si. O método *não* testa se os ids são válidos.
	 */
	public static function add($theCommand, $theDevices = array()) {
		if(!is_array($theCommand) || count($theCommand) == 0) {
			throw new \Exception('Informações inválidas para criação de um comando.');
		}
		
		if(!isset($theCommand['status']) || !in_array($theCommand['status'], self::$mKnownStatus)) {
			throw new \Exception('O status "'.$theCommand['status'].'" é inválido para um comando.');
		}
		
		if(empty($theCommand['exec'])) {
			throw new \Exception('Nenhuma ordem atribuida ao comando.');
		}
		
		$aInfo = Utils::prepareForSql($theCommand);
		Db::execute("INSERT INTO ".Db::TABLE_COMMANDS." (`".implode("`,`", array_keys($aInfo))."`) VALUES (".implode(',', $aInfo).")");
		
		$aIdCommand 	= Db::lastInsertedId();
		$aValuesDevices = "";
		
		if(count($theDevices) > 0) {
			// A ordem é para um monte de dispositivos. Vamos inserir
			// a ordem para cada um deles.
			foreach($theDevices as $aIdDevice) {
				$aValuesDevices .= "(".$aIdCommand.", ".$aIdDevice.", 0, 0, ''),";				
			}
			$aValuesDevices = substr($aValuesDevices, 0, strlen($aValuesDevices) - 1);
		} else {
			// A ordem é para a Aura.
			$aValuesDevices = "(".$aIdCommand.", 0, 0, 0, '')";
		}
		
		Db::execute("INSERT INTO ".Db::TABLE_COMMAND_LOG." (fk_command, fk_device, time_start, time_end, result) VALUES ".$aValuesDevices);
	}
	
	public static function getById($theId) {
		$aRet	 = null;
		$theId	 = (int)$theId;
		$aResult = Db::execute("SELECT * FROM ".Db::TABLE_COMMANDS." WHERE id = ". $theId);
	
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
			Db::execute("DELETE FROM ".Db::TABLE_COMMAND_LOG." WHERE fk_command = " . $theId);
			Db::execute("DELETE FROM ".Db::TABLE_COMMANDS." WHERE id = " . $theId);
			$aRet = true;
		}
		
		return $aRet;
	}
	
	/**
	 * Atualiza a tabela de logs de comandos.
	 * 
	 * @param int $theIdDevice id do dispositivo que está atualizado os logs.
	 * @param int $theIdCommand id do comando que o dispositivo está executando.
	 * @param array $theData array assossiativo com os dados a serem atualizados. As chaves desse array devem ser os campos do banco de dados, no formato [campo] => valor.
	 * @return  bool true se tudo deu certo, ou false caso contrário.
	 */
	public static function updateLog($theIdDevice, $theIdCommand, $theData) {
		$theIdDevice 	= (int)$theIdDevice;
		$theIdCommand	= (int)$theIdCommand;
		$aCheck			= !empty($theData['time_end']) && is_numeric($theData['time_end']) && $theData['time_end'] > 0;
		
		if(!is_array($theData) || count($theData) == 0) return false;
		 
		$aSets = Utils::generateUpdateStatement($theData);
		$aRet  = Db::execute("UPDATE ".Db::TABLE_COMMAND_LOG." SET ".$aSets." WHERE fk_command = ".$theIdCommand." AND fk_device = ".$theIdDevice);

		if($aRet && $aCheck && self::countDevicesRunningCommand($theIdCommand) == 0) {
			// Todos os envolvidos em executar uma tarefa completaram ela. Vamos
			// atualizar a tupla de comandos para refletir isso.
			
			Db::execute("UPDATE ".Db::TABLE_COMMANDS." SET status = ".self::STATUS_COMPLETED." WHERE status = ".self::STATUS_RUNNING." AND id = ".$theIdCommand);
		}
		
		return $aRet;
	}
	
	private static function countDevicesRunningCommand($theCommandId) {
		$aRet	 		= 0;
		$theCommandId	= (int)$theCommandId;
		$aResult 		= Db::execute("SELECT COUNT(*) AS count FROM ".Db::TABLE_COMMAND_LOG." WHERE time_end = 0 AND fk_command = ". $theCommandId);
		
		if(Db::numRows($aResult) == 1) {
			$aTemp = Db::fetchAssoc($aResult);
			$aRet  = (int)$aTemp['count'];
		}
		
		return $aRet;
	}
	
	public static function findPendingCommandsByDevice($theDeviceId) {
		$aRet	 		= array();
		$theDeviceId 	= (int)$theDeviceId;
		$aResult 		= Db::execute("SELECT
											c.id, c.priority, c.time, c.exec
												
									   FROM ".Db::TABLE_COMMANDS." AS c JOIN
									   		".Db::TABLE_COMMAND_LOG." AS l ON c.id = l.fk_command
									    		
									   WHERE l.fk_device = ".$theDeviceId." AND l.time_end = 0 AND c.status = " . self::STATUS_RUNNING . "
									   ORDER BY c.priority ASC");
		
		if(Db::numRows($aResult) >= 0) {
			while($aRow = Db::fetchAssoc($aResult)) {
				$aRet[$aRow['id']] = $aRow;
			}
		}
		
		return $aRet;
	}
}

?>