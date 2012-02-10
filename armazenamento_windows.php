<?php 
	require_once dirname(__FILE__).'/inc/layout.php';
	
	cabecalho('Armazenamento Windows');
	
	echo '<div class="hero-unit armazenamento-windows">';
		echo '<h1>Windows</h1>';
		echo '<p>Como usar sua home no Windows.</p>';
	echo '</div>';
	
	echo '<div class="row">';
		echo '<div class="span12">';
			echo '<p>Ao efetuar login num computador rodando Windows, você poderá acessar sua home através do disco <code>'.DRIVE_HOME_WINDOWS.'</code>, que é automaticamente montado no momento do login. A figura abaixo mostra um exemplo:</p>';
			echo '<p><img src="img/homes/drive_home_windows.png" class="img-armazenamento" title="Drive '.DRIVE_HOME_WINDOWS.', sua home no Windows"></p>';
			echo '<p>Os dados colocados em sua home serão salvos automaticamente no servidor, isso quer dizer que estarão disponíveis em qualquer computador que você efetue login. Lembre-se que <strong>apenas</strong> os dados colocados em sua home (<code>'.DRIVE_HOME_WINDOWS.'</code>) serão salvos ao longo do tempo, os dados colocados em outras pastas, como <strong>Documentos</strong> ou <strong>Imagens</strong>, serão <strong>APAGADOS</strong> periodicamente.</p>';
		echo '</div>';
	echo '</div>';
	
	echo '<div class="row" style="margin-top: 20px;">';
		echo '<div class="span12">';
			echo '<h1>Temp <small>Local para troca de arquivos</small></h1>';
			echo '<p>Se você precisar trocar arquivos entre dois ou mais computadores, por exemplo, você pode utilizar a pasta <strong>temp</strong>. No Windows, essa pasta está acessível através do endereço <code>'.TEMP_WINDOWS.'</code></p>';
			echo '<p><img src="img/homes/temp_windows.png" class="img-armazenamento" title="Pasta '.TEMP_WINDOWS.'"></p>';
			echo '<p>A pasta <code>'.TEMP_WINDOWS.'</code> deve ser usada <strong>apenas</strong> como área temporária de transferência de arquivos, porque todo o conteúdo dela é <strong>APAGADO</strong> diariamente de madrugada.</p>';
		echo '</div>';
	echo '</div>';
	
	rodape();
?>