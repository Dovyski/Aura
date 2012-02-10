<?php 
	require_once dirname(__FILE__).'/inc/layout.php';
	
	cabecalho('Armazenamento');
	
	echo '<div class="hero-unit fundo-icone icone-armazenamento">';
		echo '<h1>Armazenamento</h1>';
		echo '<p>Salve seus materiais acadêmicos em segurança.</p>';
	echo '</div>';
	
	echo '<div class="row">';
		echo '<div class="span12">';
			echo '<p>Cada usuário do NCC possui uma pasta especial no servidor onde pode guardar arquivos importantes. Essa pasta, cujo conceito denominada-se <strong>home</strong>, possui capacidade de armazenar até <strong>'.TAMANHO_HOME.'</strong> e está disponível para acesso através da rede em qualquer computador, bastando que o usuário esteja autenticado.</p>';
			echo '<p>Cada home é <strong>exclusiva</strong> e somente o usuário dono dela pode ler ou gravar arquivos, os demais usuários não possuem acesso à pasta. Veja abaixo como utilizar sua home de acordo com o sistema operacional.</p>';
		echo '</div>';
	echo '</div>';
	
	echo '<div class="row" style="margin-top: 20px;">';
		echo '<div class="span1">';
			echo '<p><img src="./img/os/ubuntu_small.png" border="" title="Ubuntu"></p>';
		echo '</div>';
		echo '<div class="span5">';
			echo '<h1>Ubuntu</h1>';
			echo '<p><a class="btn" href="armazenamento_ubuntu.php">Ver detalhes &raquo;</a></p>';
		echo '</div>';
		
		echo '<div class="span1">';
			echo '<p><img src="./img/os/windows_small.png" border="" title="Ubuntu"></p>';
		echo '</div>';
		echo '<div class="span5">';
			echo '<h1>Windows</h1>';
			echo '<p><a class="btn" href="armazenamento_windows.php">Ver detalhes &raquo;</a></p>';
		echo '</div>';
	echo '</div>';
	
	rodape();
?>