<?php 
	require_once dirname(__FILE__).'/inc/globals.php';
	
	cabecalho('Troca de senha');
	
	echo '<div class="hero-unit fundo-icone icone-senha">';
		echo '<h1>Troca de senha</h1>';
		echo '<p>Sua conta e sua senha são pessoais e intransferíveis.</p>';
	echo '</div>';
	
	echo '<div class="row">';
		echo '<div class="span6">';
			echo '<h2>Sei a minha senha atual</h2>';
			echo '<p>Se você sabe a sua senha atual, você pode fazer a troca dela pela web através da URL <a href="'.URL_TROCA_SENHA.'" target="_blank">'.URL_TROCA_SENHA.'</a> (essa URL <strong>só funciona se você estiver dentro da UFFS</strong>).</p>'; 
			echo '<p>Ao acessar essa página, informe seu usuário e senha atual. Depois de efetuar login, você será levado a uma tela onde poderá escolher uma senha nova.</p>';
		echo '</div>';
		
		echo '<div class="span6">';
			echo '<h2>Não sei minha senha atual</h2>';
			echo '<p>Você precisará conversar pessoalmente com alguém do NCC que tenha funções administrativas, assim você conseguirá uma senha nova.</p>';
			echo '<p>Atualmente você pode conversar com <strong>'.RESPONSAVEL_NCC_SENHA.'</strong>.</p>';
		echo '</div>';
	echo '</div>';
	
	rodape();
?>