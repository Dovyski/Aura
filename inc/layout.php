<?php

function barraNavegacao() {
	$aPagina = basename($_SERVER['PHP_SELF']);
	
	echo '<div class="navbar navbar-fixed-top">';
		echo '<div class="navbar-inner">';
			echo '<div class="container">';
				echo '<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">';
					echo '<span class="icon-bar"></span>';
					echo '<span class="icon-bar"></span>';
					echo '<span class="icon-bar"></span>';
				echo '</a>';
				echo '<a class="brand" href="index.php">NCC</a>';
			
				echo '<div class="nav-collapse">';
					echo '<ul class="nav">';
						echo '<li '.($aPagina == 'index.php' 			? 'class="active"' : '').'><a href="index.php">Inicial</a></li>';
						echo '<li '.($aPagina == 'guia.php' 			? 'class="active"' : '').'><a href="guia.php">Guia rápido</a></li>';
						echo '<li '.($aPagina == 'armazenamento.php' 	? 'class="active"' : '').'><a href="armazenamento.php">Armazenamento</a></li>';
						echo '<li '.($aPagina == 'senha.php' 			? 'class="active"' : '').'><a href="senha.php">Troca de senha</a></li>';
						echo '<li '.($aPagina == 'sobre.php' 			? 'class="active"' : '').'><a href="sobre.php">Sobre</a></li>';
					echo '</ul>';
				echo '</div><!--/.nav-collapse -->';
			echo '</div>';
		echo '</div>';
	echo '</div>';
}

function cabecalho($theTitulo) {
	echo '<!DOCTYPE html>';
	echo '<html lang="en">';
	echo '<head>';
		echo '<meta charset="utf-8">';
		echo '<title>'.(empty($theTitulo) ? '' : $theTitulo).' | NCC - Ciência da Computação - UFFS</title>';
		echo '<meta name="description" content="Nucleo de Ciência da Computação do curso de Ciencia da Computação da Universidade Federal da Fronteira Sul">';
		echo '<meta name="author" content="NCC">';
		
		echo '<!-- Le HTML5 shim, for IE6-8 support of HTML elements -->';
		echo '<!--[if lt IE 9]>';
		echo '<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>';
		echo '<![endif]-->';
		
		echo '<!-- Le styles -->';
		echo '<link href="./css/bootstrap.css" rel="stylesheet">';
		echo '<style type="text/css">';
		echo 'body {';
		echo '        padding-top: 60px;';
		echo '		  padding-bottom: 40px;';
		echo '}';
		echo '</style>';
		echo '<link href="./css/bootstrap-responsive.css" rel="stylesheet">';
		
		echo '<!-- Le fav and touch icons -->';
		echo '<link rel="shortcut icon" href="img/favicon.ico">';
		echo '<link rel="apple-touch-icon" href="img/apple-touch-icon.png">';
		echo '<link rel="apple-touch-icon" sizes="72x72" href="img/apple-touch-icon-72x72.png">';
		echo '<link rel="apple-touch-icon" sizes="114x114" href="img/apple-touch-icon-114x114.png">';
	echo '</head>';
	
	echo '<body>';
	
	barraNavegacao();
	
	echo '<div class="container">';
}

function rodape() {
		echo '<hr>';
		
		echo '<footer>';
			echo '<p>NCC - Ciência da Computação - UFFS</p>';
		echo '</footer>';
	
	echo '</div> <!-- /container -->';
	
	echo '<!-- Le javascript. Placed at the end of the document so the pages load faster -->';
	echo '<script src="./js/jquery.js"></script>';
	echo '<script src="./js/bootstrap-transition.js"></script>';
	echo '<script src="./js/bootstrap-alert.js"></script>';
	echo '<script src="./js/bootstrap-modal.js"></script>';
	echo '<script src="./js/bootstrap-dropdown.js"></script>';
	echo '<script src="./js/bootstrap-scrollspy.js"></script>';
	echo '<script src="./js/bootstrap-tab.js"></script>';
	echo '<script src="./js/bootstrap-tooltip.js"></script>';
	echo '<script src="./js/bootstrap-popover.js"></script>';
	echo '<script src="./js/bootstrap-button.js"></script>';
	echo '<script src="./js/bootstrap-collapse.js"></script>';
	echo '<script src="./js/bootstrap-carousel.js"></script>';
	echo '<script src="./js/bootstrap-typeahead.js"></script>';
	
	echo '</body>';
	echo '</html>';
}

?>