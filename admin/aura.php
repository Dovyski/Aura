<?php 
	require_once dirname(__FILE__).'/../inc/globals.php';

	authRestritoAdmin();
	cabecalho('Inicial', '../');
	
	echo '<div class="hero-unit">';
		echo '<h1>Aura</h1>';
		echo '<p>Assistente virtual do NCC.</p>';
	echo '</div>';

	echo '<div class="row">';
		echo '<div class="span12">';
			echo '<p>Envie ordens para a Aura usando o console abaixo.</p>';
			echo '<div class="controls">';
				echo '<div class="input-prepend">';
					echo '<span class="add-on">Console</span>';
					echo '<input class="span11" id="prependedInput" size="16" type="text">';
				echo '</div>';
				echo '<p id="auraPainelResposta" class="aura-resposta-console" style="display: none;">Resposta...</p>';
            echo '</div>';
		echo '</div>';
	echo '</div>';
	
	echo '<div class="row" style="margin-top: 30px;">';
		echo '<div class="span12">';
			echo '<h2>Laboratório 1</h2>';
		echo '</div>';
	echo '</div>';
	
	echo '<div class="row">';
		echo '<div class="span4 aura-bloco">';
			echo '<img src="../img/icos/computador.png" title="sdsd" />';
			echo '<h2>Computadores</h2>';
			echo '<p>Ligados <strong>40</strong>, desligados <strong>10</strong>.</p>';
		echo '</div>';
		
		echo '<div class="span4 aura-bloco">';
			echo '<img src="../img/icos/pessoa.png" title="sdsd" />';
			echo '<h2>Usuários</h2>';
			echo '<p><strong>40</strong> usuários logados.</p>';
		echo '</div>';
		
		echo '<div class="span4 aura-bloco">';
			echo '<img src="../img/icos/internet.png" title="sdsd" />';
			echo '<h2>Internet</h2>';
			echo '<span class="label label-success">Ativa</span>';
		echo '</div>';
	echo '</div>';
	
	rodape('../');
?>