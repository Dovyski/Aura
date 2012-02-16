<?php 
	require_once dirname(__FILE__).'/inc/layout.php';
	
	cabecalho('Armazenamento Ubuntu');
	
	echo '<div class="hero-unit fundo-icone icone-ubuntu">';
		echo '<h1>Ubuntu</h1>';
		echo '<p>Como usar sua home no Ubuntu.</p>';
	echo '</div>';
	
	echo '<div class="row">';
		echo '<div class="span12">';
			echo '<p>No Ubuntu, todos os dados que estiverem contidos na pasta <code>'.PASTA_HOME_UBUNTU.'</code> serão salvos automaticamente em sua home no servidor. Veja a figura abaixo:</p>';
			echo '<p><img src="img/homes/ubuntu_minha_pasta.png" class="img-armazenamento img-armazenamento-small" title="Minha pasta, automaticamente salva em sua home."></p>';
			echo '<p>Os dados colocados em sua home serão salvos automaticamente no servidor, isso quer dizer que estarão disponíveis em qualquer computador que você efetue login. Lembre-se que <strong>apenas</strong> os dados colocados em sua home (pasta <code>'.PASTA_HOME_UBUNTU.'</code>) serão salvos ao longo do tempo, os dados colocados em outras pastas, como <strong>Lixeira</strong> ou <strong>/var/www</strong>, serão <strong>APAGADOS</strong> periodicamente.</p>';
		echo '</div>';
	echo '</div>';
	
	echo '<div class="row" style="margin-top: 20px;">';
		echo '<div class="span12">';
			echo '<h1>Temp <small>Local para troca de arquivos</small></h1>';
			echo '<p>Se você precisar trocar arquivos entre dois ou mais computadores, por exemplo, você pode utilizar a pasta <strong>temp</strong>. No Ubuntu, para acessar essa pasta, clique no ícone <code>Pasta pessoal</code> na barra de atalhos à esquerda da tela e, na janela que irá se abrir, clique na pasta <code>Navegar na rede</code>, que fica na sessão <code>Rede</code>, à esquerda da janela, conforme mostra a figura abaixo:</p>';
			echo '<p><img src="img/homes/temp1_ubuntu.png" class="img-armazenamento img-armazenamento-small" title="Pasta temp"></p>';
			echo '<p>Depois de clicar em <code>Navegar na rede</code>, você verá diversas pastas. Dê duplo-clique na pasta <code>CENTRAL</code>; depois de alguns segundos, outras pastas serão mostradas, entre elas a pasta <code>temp</code>, conforme a figura abaixo:</p>';
			echo '<p><img src="img/homes/temp2_ubuntu.png" class="img-armazenamento" title="Pasta temp"></p>';
			echo '<p>A pasta <code>temp</code> deve ser usada <strong>apenas</strong> como área temporária de transferência de arquivos, porque todo o conteúdo dela é <strong>APAGADO</strong> diariamente de madrugada.</p>';
		echo '</div>';
	echo '</div>';
	
	rodape();
?>