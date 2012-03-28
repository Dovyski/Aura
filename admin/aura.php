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
		$aIdsLabs = array();
		
		foreach($aLabs as $aLab) {
			$aIdsLabs[]	= $aLab['id'];

			echo '<div class="row" style="margin-top: 30px;">';
				echo '<div class="span12">';
					echo '<h2>'.$aLab['name'].' <small>'.$aLab['desc'].'</small></h2>';
				echo '</div>';
			echo '</div>';
			
			echo '<div class="row" id="lab'.$aLab['id'].'"></div>';
		}
	} else {
		echo '<div class="row" style="margin-top: 10px;">';
			echo '<div class="span12">';
				echo '<h2>Nenhum laboratório</h2>';
				echo '<p>Não há laboratório com nomes <strong>lab-ncc-*</strong>.</p>';
			echo '</div>';
		echo '</div>';
	}
	
	echo '<script type="text/javascript">
			$(function() {
				AURA.init();
				AURA.refreshLabsDashboard(['.implode(',', $aIdsLabs).']);
			});
		  </script>';
	rodape('../');
?>