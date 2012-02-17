<?php 
	require_once dirname(__FILE__).'/../inc/globals.php';

	authRestritoLogado();
	cabecalho('Inicial', '../');
	
	echo '<div class="hero-unit">';
		echo '<h1>Intranet</h1>';
		echo '<p>O centro de controle do NCC.</p>';
	echo '</div>';

	echo '<div class="row">';
		echo '<div class="span4">';
			echo '<h2>Coisa1 <span class="label label-warning">Novo</span></h2>';
			echo '<p>O NCC possui diversas vantagens e algumas obrigações para seus usuários. Aprenda todas elas rapidamente através dos 8 mandamentos do NCC, um guia simples e rápido de ler.</p>';
			echo '<p><a class="btn" href="guia.php">Ver detalhes &raquo;</a></p>';
		echo '</div>';
		
		echo '<div class="span4">';
			echo '<h2>Coisa2</h2>';
			echo '<p>Cada usuário do NCC possui um espaço reservado no servidor para guardar arquivos (documentos, trabalhos, etc). Saiba como usar esse espaço para guardar e proteger seus dados.</p>';
			echo '<p><a class="btn" href="armazenamento.php">Ver detalhes &raquo;</a></p>';
		echo '</div>';
		
		echo '<div class="span4">';
			echo '<h2>Coisa3</h2>';
			echo '<p>Não encontrou aquilo que procurava, está com dúvidas sobre o funcionamento do NCC, tem críticas ou sugestões? Fale conosco: <a href="mailto:ncc@uffs.edu.br">ncc@uffs.edu.br</a></p>';
		echo '</div>';
	echo '</div>';
	
	rodape('../');
?>