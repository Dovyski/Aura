<?php 
	require_once dirname(__FILE__).'/../inc/globals.php';
	require_once dirname(__FILE__).'/aura/globals.php';

	authRestritoAdmin();
	cabecalho('Inicial', '../');
	
	echo '<div class="hero-unit">';
		echo '<h1>Aura</h1>';
		echo '<p>Assistente virtual do NCC.</p>';
	echo '</div>';

	echo '<div class="row" id="linhaConsoleAura">';
		echo '<div class="span12">';
			echo '<p>Envie ordens para a Aura usando o console abaixo.</p>';
			echo '<div class="controls">';
				echo '<div class="input-prepend">';
					echo '<span class="add-on"><i class="icon-list-alt"></i></span>';
					echo '<form action="" method="post" id="formAura">';
					echo '<input name="command" id="consoleAura" class="span11" id="prependedInput" size="16" type="text">';
					echo '</form>';
				echo '</div>';
				echo '<p id="auraPainelResposta" class="aura-resposta-console" style="display: none;">Resposta...</p>';
            echo '</div>';
		echo '</div>';
	echo '</div>';
	
	$aLabs = Aura\Groups::findByName('lab-ncc-%');
	
	if(count($aLabs) > 0) {
		foreach($aLabs as $aLab) {
			$aDevices = Aura\Groups::findDevices($aLab['id']);
			$aPings	  = Aura\Pings::findByDevices($aDevices, time() - 60*10);
			$aReport  = Aura\Utils::generateLabReport($aPings);

			echo '<div class="row" style="margin-top: 30px;">';
				echo '<div class="span12">';
					echo '<h2>'.$aLab['name'].' <small>'.$aLab['desc'].'</small></h2>';
				echo '</div>';
			echo '</div>';
			
			echo '<div class="row">';
				// Computadores
				echo '<div class="span4 aura-bloco">';
					$aAtivos = count($aDevices);
					
					if($aAtivos > 0) {
						echo '<ul class="aura-bloco-opts">';
							echo '<div class="btn-group">';
								echo '<a class="btn btn-mini dropdown-toggle" data-toggle="dropdown" href="#"><i class="icon-cog icon-black"></i><span class="caret"></span></a>';
								echo '<ul class="dropdown-menu">';
									echo '<li><a href="javascript:void(0)" onclick="AURA.typeConsoleCommand(\'Desligue os computadores do '.$aLab['name'].'\');"><i class="icon-off"></i> Desligar todos</a></li>';
								echo '</ul>';
							echo '</div>';
						echo '</ul>';
					}

					echo '<img src="../img/icos/computador.png" title="Computadores" />';
					echo '<h2>Computadores</h2>';
					echo '<p>Total <strong>'.count($aDevices).'</strong>, ligados <strong>'.count($aReport['computers']).'</strong></p>';
				echo '</div>';
				
				// Usuários
				echo '<div class="span4 aura-bloco">';
					$aLogados = count($aReport['users']);
					
					if($aLogados > 0) {
						echo '<ul class="aura-bloco-opts">';
							echo '<div class="btn-group">';
								echo '<a class="btn btn-mini dropdown-toggle" data-toggle="dropdown" href="#"><i class="icon-cog icon-black"></i><span class="caret"></span></a>';
								echo '<ul class="dropdown-menu">';
									echo '<li><a href="javascript:void(0)" onclick="AURA.typeConsoleCommand(\'Deslogue os usuários do '.$aLab['name'].'\');"><i class="icon-remove"></i> Deslogar todos</a></li>';
								echo '</ul>';
							echo '</div>';
						echo '</ul>';
					}
				
					echo '<img src="../img/icos/pessoa.png" title="Usuários" />';
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
					if($aReport['internet'] !== null) {
						echo '<ul class="aura-bloco-opts">';
							echo '<div class="btn-group">';
								echo '<a class="btn btn-mini dropdown-toggle" data-toggle="dropdown" href="#"><i class="icon-cog icon-black"></i><span class="caret"></span></a>';
								echo '<ul class="dropdown-menu">';
									if($aReport['internet']) {
										echo '<li><a href="javascript:void(0)" onclick="AURA.typeConsoleCommand(\'Desligue a internet do '.$aLab['name'].'\');"><i class="icon-ban-circle"></i> Desativar internet</a></li>';									
									} else {
										echo '<li><a href="javascript:void(0)" onclick="AURA.typeConsoleCommand(\'Ligue a internet do '.$aLab['name'].'\');"><i class="icon-ok-sign"></i> Ativar internet</a></li>';
									}
								echo '</ul>';
							echo '</div>';
						echo '</ul>';
					}
				
					echo '<img src="../img/icos/internet.png" title="Internet" />';
					echo '<h2>Internet</h2>';
					if($aReport['internet'] === null) {
						echo '<span class="label label-warning">Desconhecida</span>';
					} else {
						echo $aReport['internet'] ? '<span class="label label-success">Online</span>' : '<span class="label label-important">Offline</span>';
					}
				echo '</div>';
			echo '</div>';
		}
	} else {
		echo '<div class="row" style="margin-top: 10px;">';
			echo '<div class="span12">';
				echo '<h2>Nenhum laboratório</h2>';
				echo '<p>Não há laboratório com nomes <strong>lab-ncc-*</strong>.</p>';
			echo '</div>';
		echo '</div>';
	}
	
	echo '<script type="text/javascript"> $(function() { AURA.init(); }); </script>';
	rodape('../');
?>