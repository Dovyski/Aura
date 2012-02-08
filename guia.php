<?php 
	require_once dirname(__FILE__).'/inc/layout.php';
	
	cabecalho('Troca de senha');
	
	echo '<div class="hero-unit">';
		echo '<h1>Guia rápido</h1>';
		echo '<p>As primeiras coisas primeiro.</p>';
	echo '</div>';
	
	echo '<div class="row">';
		echo '<div class="span4">';
			echo '<h2>Guia rápido <span class="label label-info">Novo!</span></h2>';
			echo '<p>O NCC possui diversas vantagens e algumas obrigações para seus usuários. Aprenda todas elas rapidamente através dos 10 mandamentos do NCC, um guia simples e rápido de ler.</p>';
			echo '<p><a class="btn" href="guia.php">Ver detalhes &raquo;</a></p>';
		echo '</div>';
	echo '</div>';
	
	rodape();
?>