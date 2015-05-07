<?php
	header("Content-Type: text/html; charset=UTF-8");

	require_once dirname(__FILE__).'/inc/globals.php';

	authAllowAdmin();

	$aLabId = isset($_REQUEST['lab']) ? (int)$_REQUEST['lab'] : 0;

	$aLab			= Aura\Groups::getByClue($aLabId);
	$aDevices 		= Aura\Groups::findDevices($aLabId);
	$aUsers	  		= Aura\Pings::findActiveUsers($aDevices);
	$aActiveDevices = Aura\Pings::findActiveDevices($aDevices);
	$aInternet		= Aura\Utils::hasInternetAccess($aActiveDevices);
	$aLastTask		= Aura\Tasks::findLastTaskByDevices($aDevices);

	// Improve that!
	echo '<!-- id: '.$aLabId.' -->';

	// Id of the block containing the devices list.
	$aDevicesBlockName = 'devices-'.$aLabId;

	// Computadores
	echo '<div class="col-md-4 aura-bloco">';
		$aAtivos = count($aActiveDevices);

		if($aAtivos > 0) {
			echo '<ul class="aura-bloco-opts">';
			echo '<div class="btn-group">';
			echo '<a class="btn btn-mini dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-cog"></i> <span class="caret"></span></a>';
			echo '<ul class="dropdown-menu">';
			echo '<li><a href="javascript:void(0)" onclick="AURA.sendCommand(\'Ligue os computadores do '.$aLab['name'].'\');"><i class="fa fa-plug"></i> Ligar todos</a></li>';
			echo '<li><a href="javascript:void(0)" onclick="AURA.sendCommand(\'Desligue os computadores do '.$aLab['name'].'\');"><i class="fa fa-power-off"></i> Desligar todos</a></li>';
			echo '<li><a href="javascript:void(0)" onclick="AURA.sendCommand(\'Reinicie os computadores do '.$aLab['name'].'\');"><i class="fa fa-refresh"></i> Reiniciar todos</a></li>';
			echo '<li class="divider"></li>';
			echo '<li><a href="javascript:void(0)" onclick="AURA.sendCommand(\'Formate os computadores do '.$aLab['name'].'\');"><i class="fa fa-fire"></i> Formatar todos (usando última imagem)</a></li>';
			echo '<li><a href="javascript:void(0)" onclick="AURA.formatUsingImage(\'os computadores do '.$aLab['name'].'\');"><i class="fa fa-fire"></i> Formatar todos (escolhendo img)</a></li>';
			echo '<li class="divider"></li>';
			echo '<li><a href="javascript:void(0)" onclick="AURA.runArbitraryCommand(\'nos computadores do '.$aLab['name'].'\');"><i class="fa fa-terminal"></i> Rodar comando em todos</a></li>';
			echo '</ul>';
			echo '</div>';
			echo '</ul>';
		}

		// TODO: fix this in-line javascript
		echo '<a href="javascript:void(0)" onclick="$(\'#'.$aDevicesBlockName.'\').slideToggle();"><i class="fa fa-desktop fa-5x"></i></a>';

		echo '<h2>Computadores</h2>';
		$aTotalDispositivos = count($aDevices);
		if($aTotalDispositivos == 0) {
			echo '<p>Nenhum cadastrado</p>';
		} else {
			echo '<p>Total <strong>'.$aTotalDispositivos.'</strong>, ligados <strong>'.count($aActiveDevices).'</strong></p>';
		}
	echo '</div>';

	// Usuários
	echo '<div class="col-md-4 aura-bloco">';
		$aLogados = count($aUsers);

		if($aLogados > 0) {
			echo '<ul class="aura-bloco-opts">';
				echo '<div class="btn-group">';
					echo '<a class="btn btn-mini dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-cog"></i> <span class="caret"></span></a>';
					echo '<ul class="dropdown-menu">';
						echo '<li><a href="javascript:void(0)" onclick="AURA.sendCommand(\'Deslogue os usuários do '.$aLab['name'].'\');"><i class="fa fa-user-times"></i> Deslogar todos</a></li>';
					echo '</ul>';
				echo '</div>';
			echo '</ul>';
		}

		echo '<i class="fa fa-users fa-5x"></i>';
		echo '<h2>Usuários</h2>';

		if($aLogados == 0) {
			echo '<p>Ninguém logado</p>';
		} else if($aLogados == 1) {
			echo '<p><strong>Um</strong> usuário logado</p>';
		} else {
			echo '<p><strong>'.$aLogados.'</strong> usuários logados</p>';
		}
	echo '</div>';

	// Internet
	echo '<div class="col-md-4 aura-bloco">';
		echo '<ul class="aura-bloco-opts">';
			echo '<div class="btn-group">';
				echo '<a class="btn btn-mini dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-cog"></i> <span class="caret"></span></a>';
				echo '<ul class="dropdown-menu">';
					echo '<li><a href="javascript:void(0)" onclick="AURA.sendCommand(\'Desligue a internet do '.$aLab['name'].'\');"><i class="fa fa-ban"></i> Desativar internet</a></li>';
					echo '<li><a href="javascript:void(0)" onclick="AURA.sendCommand(\'Ligue a internet do '.$aLab['name'].'\');"><i class="fa fa-check-circle"></i> Ativar internet</a></li>';
				echo '</ul>';
			echo '</div>';
		echo '</ul>';

		echo '<i class="fa fa-signal fa-5x"></i>';
		echo '<h2>Internet</h2>';
		if($aInternet['status'] == 'desconhecida') {
			echo '<span class="label label-warning">Desconhecida</span>';
		} else {
			echo $aInternet['status'] == 'online' ? '<span class="label label-success">Online</span>' : '<span class="label label-danger">Offline</span>';
		}
	echo '</div>';

	echo '<div id="'.$aDevicesBlockName.'" class="col-md-12 aura-bloco-devices">';
		$aTotalDispositivos = count($aDevices);

		if($aTotalDispositivos != 0) {
			$aDevicesList = Aura\Devices::findByIds($aDevices);

			echo '<table class="table table-hover">';
				echo '<thead>';
					echo '<tr>';
						echo '<th>Id</th>';
						echo '<th>Nome</th>';
						echo '<th>Status</th>';
						echo '<th></th>';
					echo '</tr>';
				echo '</thead>';

				echo '<tbody>';
					foreach($aDevicesList as $aDevice) {
						echo '<tr class="error">';
							echo '<td>'.$aDevice['id'].'</td>';
							echo '<td style="width: 35%;">'.$aDevice['name'].' <small class="fraco">'.$aDevice['hash'].'</small></td>';
							echo '<td>';
								$aPowerStatus 		= 'danger';
								$aInternetStatus 	= 'default';
								$aHdStats 			= '';
								$aLastPing			= '';
								$aUsers				= '';
								$aOs				= isset($aActiveDevices[$aDevice['id']]) ? substr($aActiveDevices[$aDevice['id']]['os'], 0, 35) : '?';
								$aOsStatus			= $aOs != '?' ? 'success' : 'default';
								$aOsIcon			= strpos($aOs, 'Win') !== false ? 'windows' : 'linux';
								$Task				= isset($aLastTask[$aDevice['id']]) ? $aLastTask[$aDevice['id']] : null;

								if (isset($aActiveDevices[$aDevice['id']])) {
									$aInfos 			= unserialize($aActiveDevices[$aDevice['id']]['data']);

									$aPowerStatus 		= 'success';
									$aInternetStatus 	= $aInfos['ping_ip'] <= 75 ? 'success' : 'danger';
									$aHdStats 			= (int)($aInfos['storage_total'] / $aInfos['storage_available']) . '%';
									$aLastPing			= $aActiveDevices[$aDevice['id']]['time'];
									$aLastPing			= '0:' . (time() - $aLastPing); // TODO: make human readable

									$aUsers				= unserialize($aInfos['users']);
									$aUsers				= count($aUsers) > 0 ? count($aUsers) : '';
								}

								echo '<i class="fa fa-'.$aOsIcon.'" title="'.$aOs.'"></i> ';

								echo '<span class="label label-'.$aPowerStatus.'" style="padding:5px;"><i class="fa fa-power-off" title="Ligado/Desligado"></i></span> ';
								echo '<span class="label label-'.$aInternetStatus.'" style="padding:5px;"><i class="fa fa-signal" title="Conexão com a Internet"></i></span> ';

								if($aUsers != '') {
									echo '<span class="label label-default" style="padding:5px;"><i class="fa fa-user" title="Usuários ativos"></i> '.$aUsers.'</span> ';
								}

								//echo '<span class="label label-default" style="padding:5px;"><i class="fa fa-refresh" title="Tempo desde a última atualização com a Aura."></i> '.$aLastPing.'</span> ';

								if($Task != null) {
									// TODO: show task output in a nice modal/tooltip instead of i#title.
									$aDateEnd = is_numeric($Task['time_end']) ? date('d/m/Y h:i:s', $Task['time_end']) : '?';
									echo '<span class="label label-default" style="padding:5px;"><i class="fa '.(is_numeric($Task['time_end']) ? 'fa-check' : 'fa-cog fa-spin').'" title="Tarefa '.$Task['fk_task'].' | Data: '.($Task['time_end'] == '' ? 'Rodando' : $aDateEnd)."\n".$Task['result'].'"></i> </span> ';
								}
							echo '</td>';
							echo '<td>';
								echo '<div class="btn-group">';
									echo '<a class="btn btn-mini dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-cog"></i> <span class="caret"></span></a>';
									echo '<ul class="dropdown-menu">';
										echo '<li><a href="javascript:void(0)" onclick="AURA.sendCommand(\'Ligue o computador '.$aDevice['name'].'\');"><i class="fa fa-plug"></i> Ligar</a></li>';
										echo '<li><a href="javascript:void(0)" onclick="AURA.sendCommand(\'Desligue o computador '.$aDevice['name'].'\');"><i class="fa fa-power-off"></i> Desligar</a></li>';
										echo '<li><a href="javascript:void(0)" onclick="AURA.sendCommand(\'Reinicie o computador '.$aDevice['name'].'\');"><i class="fa fa-refresh"></i> Reiniciar</a></li>';
										echo '<li class="divider"></li>';
										echo '<li><a href="javascript:void(0)" onclick="AURA.spyglass(\''.$aDevice['hash'].'\', \''.$aDevice['name'].'\');"><i class="fa fa-send"></i> Acesso remoto</a></li>';
										echo '<li class="divider"></li>';
										echo '<li><a href="javascript:void(0)" onclick="AURA.sendCommand(\'Desligue a internet do computador '.$aDevice['name'].'\');"><i class="fa fa-signal"></i> Bloquear internet</a></li>';
										echo '<li><a href="javascript:void(0)" onclick="AURA.sendCommand(\'Deslogue os usuarios do computador '.$aDevice['name'].'\');"><i class="fa fa-user"></i> Deslogar usuários</a></li>';
										echo '<li class="divider"></li>';
										echo '<li><a href="javascript:void(0)" onclick="AURA.sendCommand(\'Formate o computador '.$aDevice['name'].'\');"><i class="fa fa-fire"></i> Formatar com última img</a></li>';
										echo '<li><a href="javascript:void(0)" onclick="AURA.formatUsingImage(\'o computador '.$aDevice['name'].'\');"><i class="fa fa-fire"></i> Formatar escolhendo img</a></li>';
										echo '<li class="divider"></li>';
										echo '<li><a href="javascript:void(0)" onclick="AURA.runArbitraryCommand(\'no computador '.$aDevice['name'].'\');"><i class="fa fa-terminal"></i> Rodar comando</a></li>';
									echo '</ul>';
								echo '</div>';
							echo '</td>';
						echo '</tr>';
					}
				echo '</tbody>';
			echo '</table>';
		}
		echo '<p class="pull-right"><a href="javascript:void(0)" onclick="$(\'#'.$aDevicesBlockName.'\').slideToggle();"><i class="fa fa-eye-slash"></i> Ocultar</a></p>';

	echo '</div>';

	exit();
?>
