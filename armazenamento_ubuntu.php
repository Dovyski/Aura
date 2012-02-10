<?php 
	require_once dirname(__FILE__).'/inc/layout.php';
	
	cabecalho('Armazenamento Ubuntu');
	
	echo '<div class="hero-unit fundo-icone icone-ubuntu">';
		echo '<h1>Ubuntu</h1>';
		echo '<p>Como usar sua home no Ubuntu.</p>';
	echo '</div>';
	
	echo '<div class="row">';
		echo '<div class="span12">';
			echo '<p>Para acessar sua home no Ubuntu, clique em algum local vazio da área de trabalho, depois clique no menu <code>Arquivo -> Conectar ao servidor...</code> (canto superior esquerdo da tela). Uma pequena janela com o título <code>Conectar ao servidor</code> aparecerá na tela, conforme a figura abaixo:</p>';
			echo '<p><img src="img/homes/drive_home1_ubuntu.png" class="img-armazenamento img-armazenamento-small" title=""></p>';
			
			echo '<p>Nessa janela, preencha os campos conforme descrito abaixo:</p>';
			echo '<ul>';
				echo '<li>No campo <code>Servidor</code> digite <code>'.HOST_SERVIDOR.'</code>.</li>';
				echo '<li>Em <code>Tipo</code> escolha <code>Compartilhamento Windows</code>.</li>';
				echo '<li>Em <code>Nome do domínio</code> digite <code>'.NOME_DOMINIO.'</code>.</li>';
				echo '<li>Em <code>Nome do usuário</code> digite seu nome de usuário NCC.</li>';
				echo '<li>No campo <code>Senha</code> digite sua senha NCC.</li>';
			echo '</ul>';
			
			echo '<p>Quando terminar de digitar os dados, clique no botão <code>Conectar</code>. A janela deve informar que está conectando, conforme mostra a figura:</p>';
			echo '<p><img src="img/homes/drive_home2_ubuntu.png" class="img-armazenamento" title=""></p>';
			
			echo '<p>Se nenhum dado for ditidado incorretamente, a janela de compartilhamento será aberta, como na figura abaixo:</p>';
			echo '<p><img src="img/homes/drive_home3_ubuntu.png" class="img-armazenamento" title=""></p>';
			
			echo '<p>Sua home é a pasta com o nome do <strong>seu usuário</strong>. Basta dar duplo-clique na sua home e acessar seus dados. Em alguns casos, seu usuário e senha podem ser requeridos novamente quando você tentar acessar sua home, como mostra a figura abaixo:</p>';
			echo '<p><img src="img/homes/drive_home4_ubuntu.png" class="img-armazenamento" title=""></p>';
			
			echo '<p>Se isso acontecer, digite seu usuário e senha nos campos apropriados e, no campo <code>Domínio</code>, digite <code>'.NOME_DOMINIO.'</code>. Em seguida, clique no botão <code>Conectar</code>.</p>';
			echo '<p>Os dados colocados em sua home serão salvos automaticamente no servidor, isso quer dizer que estarão disponíveis em qualquer computador que você efetue login. Lembre-se que <strong>apenas</strong> os dados colocados em sua home (<code>'.DRIVE_HOME_WINDOWS.'</code>) serão salvos ao longo do tempo, os dados colocados em outras pastas, como <strong>Documentos</strong> ou <strong>Imagens</strong>, serão <strong>APAGADOS</strong> periodicamente.</p>';
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