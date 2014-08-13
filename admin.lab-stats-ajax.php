<?php
	header("Content-Type: text/html; charset=UTF-8");
	
	require_once dirname(__FILE__).'/inc/globals.php';
	require_once dirname(__FILE__).'/admin/aura/globals.php';

	authRestritoAdmin();
	
	$aLabId = isset($_REQUEST['lab']) ? (int)$_REQUEST['lab'] : 0;

	$aLab			= Aura\Groups::getByClue($aLabId);
	$aDevices 		= Aura\Groups::findDevices($aLabId);
	$aUsers	  		= Aura\Pings::findActiveUsers($aDevices);
	$aActiveDevices = Aura\Pings::findActiveDevices($aDevices);
	$aInternet		= Aura\Utils::hasInternetAccess($aActiveDevices);

	// Improve that!
	echo '<!-- id: '.$aLabId.' -->';
	
	// Id of the block containing the devices list.
	$aDevicesBlockName = 'devices-'.$aLabId;
	
	// Computadores
	echo '<div class="span4 aura-bloco">';
		$aAtivos = count($aActiveDevices);

		if($aAtivos > 0) {
			echo '<ul class="aura-bloco-opts">';
			echo '<div class="btn-group">';
			echo '<a class="btn btn-mini dropdown-toggle" data-toggle="dropdown" href="#"><i class="icon-cog icon-black"></i><span class="caret"></span></a>';
			echo '<ul class="dropdown-menu">';
			echo '<li><a href="javascript:void(0)" onclick="AURA.sendCommand(\'Desligue os computadores do '.$aLab['name'].'\');"><i class="icon-off"></i> Desligar todos</a></li>';
			echo '<li><a href="javascript:void(0)" onclick="AURA.sendCommand(\'Reinicie os computadores do '.$aLab['name'].'\');"><i class="icon-repeat"></i> Reiniciar todos</a></li>';
			echo '<li class="divider"></li>';
			echo '<li><a href="javascript:void(0)" onclick="AURA.sendCommand(\'Formate os computadores do '.$aLab['name'].'\');"><i class="icon-fire"></i> Formatar todos</a></li>';
			echo '</ul>';
			echo '</div>';
			echo '</ul>';
		}
	
		// TODO: fix this in-line javascript
		echo '<a href="javascript:void(0)" onclick="$(\'#'.$aDevicesBlockName.'\').slideToggle();"><img src="./img/icos/computador.png" title="Computadores" border="0" /></a>';
		
		echo '<h2>Computadores</h2>';
		$aTotalDispositivos = count($aDevices);
		if($aTotalDispositivos == 0) {
			echo '<p>Nenhum cadastrado</p>';
		} else {
			echo '<p>Total <strong>'.$aTotalDispositivos.'</strong>, ligados <strong>'.count($aActiveDevices).'</strong></p>';
		}
	echo '</div>';

	// Usuários
	echo '<div class="span4 aura-bloco">';
		$aLogados = count($aUsers);
			
		if($aLogados > 0) {
			echo '<ul class="aura-bloco-opts">';
				echo '<div class="btn-group">';
					echo '<a class="btn btn-mini dropdown-toggle" data-toggle="dropdown" href="#"><i class="icon-cog icon-black"></i><span class="caret"></span></a>';
					echo '<ul class="dropdown-menu">';
						echo '<li><a href="javascript:void(0)" onclick="AURA.sendCommand(\'Deslogue os usuários do '.$aLab['name'].'\');"><i class="icon-remove"></i> Deslogar todos</a></li>';
					echo '</ul>';
				echo '</div>';
			echo '</ul>';
		}
	
		echo '<img src="./img/icos/pessoa.png" title="Usuários" />';
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
	echo '<div class="span4 aura-bloco">';
		echo '<ul class="aura-bloco-opts">';
			echo '<div class="btn-group">';
				echo '<a class="btn btn-mini dropdown-toggle" data-toggle="dropdown" href="#"><i class="icon-cog icon-black"></i><span class="caret"></span></a>';
				echo '<ul class="dropdown-menu">';
					echo '<li><a href="javascript:void(0)" onclick="AURA.sendCommand(\'Desligue a internet do '.$aLab['name'].'\');"><i class="icon-ban-circle"></i> Desativar internet</a></li>';
					echo '<li><a href="javascript:void(0)" onclick="AURA.sendCommand(\'Ligue a internet do '.$aLab['name'].'\');"><i class="icon-ok-sign"></i> Ativar internet</a></li>';
				echo '</ul>';
			echo '</div>';
		echo '</ul>';

		echo '<img src="./img/icos/internet.png" title="Internet" />';
		echo '<h2>Internet</h2>';
		if($aInternet['status'] == 'desconhecida') {
			echo '<span class="label label-warning">Desconhecida</span>';
		} else {
			echo $aInternet['status'] == 'online' ? '<span class="label label-success">Online</span>' : '<span class="label label-important">Offline</span>';
		}
	echo '</div>';
	
	echo '<div id="'.$aDevicesBlockName.'" class="span12 aura-bloco-devices">';
		$aTotalDispositivos = count($aDevices);

		if($aTotalDispositivos != 0) {
			$aDevicesList = Aura\Devices::findByIds($aDevices);

			echo '<table class="table table-hover">';
				echo '<thead>';
					echo '<tr>';
						echo '<th>Id</th>';
						echo '<th>Nome</th>';
						echo '<th>OS</th>';
						echo '<th>Status</th>';
						echo '<th></th>';
					echo '</tr>';
				echo '</thead>';

				echo '<tbody>';
					foreach($aDevicesList as $aDevice) {
						echo '<tr class="error">';
							echo '<td>'.$aDevice['id'].'</td>';
							echo '<td style="width: 40%;">'.$aDevice['name'].' <small class="fraco">'.$aDevice['hash'].'</small></td>';
							echo '<td>'.(isset($aActiveDevices[$aDevice['id']]) ? substr($aActiveDevices[$aDevice['id']]['os'], 0, 35) : '?').'</td>';
							echo '<td>';
								$aPowerStatus 		= 'important';
								$aInternetStatus 	= 'default';
								$aHdStats 			= '';
								$aLastPing			= '';
								$aUsers				= '';
								
								if (isset($aActiveDevices[$aDevice['id']])) {
									$aInfos 			= unserialize($aActiveDevices[$aDevice['id']]['data']);

									$aPowerStatus 		= 'success';
									$aInternetStatus 	= $aInfos['ping_ip'] <= 75 ? 'success' : 'important';
									$aHdStats 			= (int)($aInfos['storage_total'] / $aInfos['storage_available']) . '%';
									$aLastPing			= $aActiveDevices[$aDevice['id']]['time'];
									$aLastPing			= '0:' . (time() - $aLastPing); // TODO: make human readable

									$aUsers				= unserialize($aInfos['users']);
									$aUsers				= count($aUsers) > 0 ? count($aUsers) : '';
								}
								
								echo '<span class="label label-'.$aPowerStatus.'" style="padding:5px;"><i class="icon-off icon-white" title="Ligado/Desligado"></i></span> ';
								echo '<span class="label label-'.$aInternetStatus.'" style="padding:5px;"><i class="icon-signal icon-white" title="Conexão com a Internet"></i></span> ';
								if($aHdStats != '') {
									echo '<span class="label label-default" style="padding:5px;"><i class="icon-th-large icon-white" title="Uso do disco"></i> '.$aHdStats.'</span> ';
								}
								
								if($aUsers != '') {
									echo '<span class="label label-default" style="padding:5px;"><i class="icon-user icon-white" title="Usuários ativos"></i> '.$aUsers.'</span> ';
								}
								
								echo '<span class="label label-default" style="padding:5px;"><i class="icon-refresh icon-white" title="Tempo desde a última atualização com a Aura."></i> '.$aLastPing.'</span> ';
							echo '</td>';
							echo '<td>';
								echo '<div class="btn-group">';
									echo '<a class="btn btn-mini dropdown-toggle" data-toggle="dropdown" href="#"><i class="icon-cog icon-black"></i><span class="caret"></span></a>';
									echo '<ul class="dropdown-menu">';
										echo '<li><a href="javascript:void(0)" onclick="AURA.sendCommand(\'Desligue o computador '.$aDevice['name'].'\');"><i class="icon-off"></i> Desligar</a></li>';
										echo '<li><a href="javascript:void(0)" onclick="AURA.sendCommand(\'Reinicie o computador '.$aDevice['name'].'\');"><i class="icon-repeat"></i> Reiniciar</a></li>';
										echo '<li class="divider"></li>';
										echo '<li><a href="javascript:void(0)" onclick="AURA.sendCommand(\'Desligue a internet do computador '.$aDevice['name'].'\');"><i class="icon-signal"></i> Bloquear internet</a></li>';
										echo '<li><a href="javascript:void(0)" onclick="AURA.sendCommand(\'Deslogue os usuarios do computador '.$aDevice['name'].'\');"><i class="icon-user"></i> Deslogar usuários</a></li>';
										echo '<li class="divider"></li>';
										echo '<li><a href="javascript:void(0)" onclick="AURA.sendCommand(\'Formate o computador '.$aDevice['name'].'\');"><i class="icon-fire"></i> Formatar</a></li>';
									echo '</ul>';
								echo '</div>';
							echo '</td>';
						echo '</tr>';
					}
				echo '</tbody>';
			echo '</table>';
		}
		echo '<p class="pull-right"><a href="javascript:void(0)" onclick="$(\'#'.$aDevicesBlockName.'\').slideToggle();"><i class="icon-eye-close"></i> Ocultar</a></p>';

	echo '</div>';
	
	exit();
?>