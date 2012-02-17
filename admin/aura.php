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
			echo '<h2>Coisa1 <span class="label label-warning">Novo</span></h2>';
			echo '<p>O NCC possui diversas vantagens e algumas obrigações para seus usuários. Aprenda todas elas rapidamente através dos 8 mandamentos do NCC, um guia simples e rápido de ler.</p>';
		echo '</div>';
	echo '</div>';
	
	rodape('../');
?>