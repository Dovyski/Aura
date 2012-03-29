<?php 
	require_once dirname(__FILE__).'/inc/globals.php';

	cabecalho('Inicial');
	
	echo '<div class="hero-unit">';
		echo '<h1>Acesso restrito</h1>';
		echo '<p>Você não tem privilégios para acessar essa página.</p>';
	echo '</div>';

	echo '<div class="row">';
		echo '<div class="span12">';
		echo '</div>';
	echo '</div>';
	
	rodape();
?>