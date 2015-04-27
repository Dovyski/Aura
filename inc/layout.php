<?php

function barraNavegacao() {
	$aPagina = basename($_SERVER['PHP_SELF']);

	echo '<nav class="navbar navbar-default navbar-fixed-top">';
		echo '<div class="container">';
			echo '<div class="navbar-header">';
				echo '<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">';
					echo '<span class="sr-only">Toggle navigation</span>';
					echo '<span class="icon-bar"></span>';
					echo '<span class="icon-bar"></span>';
				echo '</button>';
				echo '<a class="navbar-brand" href="index.php">A</a>';
			echo '</div>';

			if(authIsLogado()) {
				echo '<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">';
					echo '<ul class="nav navbar-nav">';
						echo '<li '.($aPagina == 'index.php' ? 'class="active"' : '').'><a href="index.php">Dashboard</a></li>';
						echo '<li '.($aPagina == 'aura.php'  ? 'class="active"' : '').'><a href="aura.php">Aura</a></li>';
					echo '</ul>';

					echo '<ul class="nav navbar-nav navbar-right">';
						layoutBarraUsuarioLogado();
					echo '</ul>';
				echo '</div><!--/.nav-collapse -->';
			} else {
				echo '<p class="navbar-text navbar-right"><a href="login.php" class="navbar-link">Login</a></p>';
			}
		echo '</div>';
	echo '</nav>';
}



function layoutBarraUsuarioLogado() {
	echo '<li class="dropdown">';
		echo '<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-user"></i> Fernando '.$_SESSION['usuario']['cn'].'</a>';

		echo '<ul class="dropdown-menu" role="menu">';
			echo '<li><a href="logout.php">Sair</a></li>';
		echo '</ul>';
	echo '</li>';
}

function cabecalho($theTitulo, $theBaseUrl = '.') {
	echo '<!DOCTYPE html>';
	echo '<html lang="en">';
	echo '<head>';
		echo '<meta charset="utf-8">';
		echo '<title>'.(empty($theTitulo) ? '' : $theTitulo).' | Aura</title>';
		echo '<meta name="description" content="Nucleo de Ciência da Computação do curso de Ciencia da Computação da Universidade Federal da Fronteira Sul">';
		echo '<meta name="author" content="NCC">';

		echo '<!-- Le HTML5 shim, for IE6-8 support of HTML elements -->';
		echo '<!--[if lt IE 9]>';
		echo '<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>';
		echo '<![endif]-->';

		$aRandURLs = AURA_DEBUG ? '?'.rand(20, 9999) : '';

		echo '<!-- Le styles -->';
		echo '<link href="'.$theBaseUrl.'/css/bootstrap.min.css" rel="stylesheet">';
		echo '<link href="'.$theBaseUrl.'/css/font-awesome.min.css" rel="stylesheet">';
		echo '<link href="'.$theBaseUrl.'/css/style.css'.$aRandURLs.'" rel="stylesheet">';

		echo '<!-- Le fav and touch icons -->';
		echo '<link rel="shortcut icon" href="img/favicon.ico">';
		echo '<link rel="apple-touch-icon" href="/img/apple-touch-icon.png">';
		echo '<link rel="apple-touch-icon" sizes="72x72" href="/img/apple-touch-icon-72x72.png">';
		echo '<link rel="apple-touch-icon" sizes="114x114" href="/img/apple-touch-icon-114x114.png">';

		echo '<script src="'.$theBaseUrl.'/js/jquery.min.js'.$aRandURLs.'"></script>';
		echo '<script src="'.$theBaseUrl.'/js/bootstrap.min.js'.$aRandURLs.'"></script>';
		echo '<script src="'.$theBaseUrl.'/js/aura.js'.$aRandURLs.'"></script>';
	echo '</head>';

	echo '<body>';

	barraNavegacao();

	echo '<div class="container">';
}

function rodape($theBaseUrl = '.') {
		echo '<hr>';

		echo '<footer>';
			echo '<p style="float:left;"></p>'; // TODO: add footnotes
		echo '</footer>';

	if(AURA_DEBUG) {
		echo '<div class="row" style="margin-top: 80px;">';
			echo '<div class="span12">';
				echo '<h2>Debug</h2>';
				echo 'IP <pre>'.$_SERVER['REMOTE_ADDR'].'</pre>';
				echo 'Sessão ';
				var_dump($_SESSION);
			echo '</div>';
		echo '</div>';
	}

	echo '</div> <!-- /container -->';

	echo '</body>';
	echo '</html>';
}

?>
