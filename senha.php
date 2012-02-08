<?php 
	require_once dirname(__FILE__).'/inc/layout.php';
	
	cabecalho('Troca de senha');
	
	echo '<div class="hero-unit">';
		echo '<h1>Troca de senha</h1>';
		echo '<p>Sua conta e sua senha são pessoais e intransferíveis.</p>';
	echo '</div>';
	
	echo '<div class="row">';
		echo '<div class="span6">';
			echo '<h2>Guia rápido <span class="label label-info">Novo!</span></h2>';
			echo '<p>O NCC possui diversas vantagens e algumas obrigações para seus usuários. Aprenda todas elas rapidamente através dos 10 mandamentos do NCC, um guia simples e rápido de ler.</p>';
			echo '<p><a class="btn" href="guia.php">Ver detalhes &raquo;</a></p>';
		echo '</div>';
		
		echo '<div class="span6">';
			echo '<h2>Armazenamento</h2>';
			echo '<p>Cada usuário do NCC possui um espaço reservado no servidor para guardar arquivos (documentos, trabalhos, etc). Saiba como usar esse espaço para guardar e proteger seus dados.</p>';
			echo '<p><a class="btn" href="armazenamento.php">Ver detalhes &raquo;</a></p>';
		echo '</div>';
	echo '</div>';
	
	rodape();
?>