<?php

require_once dirname(__FILE__).'/config.php';

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
						echo '<li '.($aPagina == 'index.php' ? 'class="active"' : '').'><a href="index.php">Dashboard</a></li>';
						echo '<li '.($aPagina == 'aura.php'  ? 'class="active"' : '').'><a href="aura.php">Aura</a></li>';
					echo '</ul>';

					layoutBarraUsuarioLogado();

					if(authIsLogado()) {
						barraNavegacaoAdmin();
					}
				echo '</div><!--/.nav-collapse -->';
			echo '</div>';
		echo '</div>';
	echo '</div>';
}

function barraNavegacaoAdmin() {
	$aPagina = basename($_SERVER['PHP_SELF']);

	echo '<ul class="nav pull-right">';
		echo '<li class="dropdown pull-right">';
			echo '<a class="dropdown-toggle" data-toggle="dropdown" href="#">Administração <b class="caret"></b></a>';
			echo '<ul class="dropdown-menu">';
				echo '<li><a href="admin.index.php">Dashboard</a></li>';
				echo '<li><a href="admin.aura.php">Aura</a></li>';
				//echo '<li class="divider"></li>';
				//echo '<li><a href="#">Separated link</a></li>';
			echo '</ul>';
		echo '</li>';
	echo '</ul>';
}

function layoutBarraUsuarioLogado() {
	$aClassLink	  = authIsAdmin() ? 'btn-danger' : 'btn-primary';

	echo '<ul class="nav pull-right">';
		echo '<div class="btn-group">';
			if(authIsLogado()) {
				echo '<a class="btn '.$aClassLink.'" href="conta.php"><i class="icon-user icon-white"></i> '.$_SESSION['usuario']['cn'].'</a>';
				echo '<a class="btn '.$aClassLink.' dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span></a>';

				echo '<ul class="dropdown-menu">';
				echo '<li><a href="logout.php"><i class="icon-remove"></i> Sair</a></li>';
				echo '</ul>';
			} else {
				echo '<a class="btn '.$aClassLink.'" href="login.php"><i class="icon-user icon-white"></i> Login</a>';
			}
		echo '</div>';
	echo '</ul>';
}

function cabecalho($theTitulo, $theBaseUrl = '.') {
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

		$aRandURLs = MODO_DEBUG ? '?'.rand(20, 9999) : '';

		echo '<!-- Le styles -->';
		echo '<link href="'.$theBaseUrl.'/css/bootstrap.css" rel="stylesheet">';
		echo '<link href="'.$theBaseUrl.'/css/style.css'.$aRandURLs.'" rel="stylesheet">';

		if(LAYOUT_RESPONSIVE) {
			echo '<link href="'.$theBaseUrl.'/css/bootstrap-responsive.css" rel="stylesheet">';
		}

		echo '<!-- Le fav and touch icons -->';
		echo '<link rel="shortcut icon" href="img/favicon.ico">';
		echo '<link rel="apple-touch-icon" href="/img/apple-touch-icon.png">';
		echo '<link rel="apple-touch-icon" sizes="72x72" href="/img/apple-touch-icon-72x72.png">';
		echo '<link rel="apple-touch-icon" sizes="114x114" href="/img/apple-touch-icon-114x114.png">';

		echo '<script src="'.$theBaseUrl.'/js/jquery.js'.$aRandURLs.'"></script>';
		echo '<script src="'.$theBaseUrl.'/js/bootstrap.js'.$aRandURLs.'"></script>';
		echo '<script src="'.$theBaseUrl.'/js/aura.js'.$aRandURLs.'"></script>';
	echo '</head>';

	echo '<body>';

	barraNavegacao();

	echo '<div class="container">';
}

function rodape($theBaseUrl = '.') {
		echo '<hr>';

		echo '<footer>';
			echo '<p style="float:left;">NCC - Ciência da Computação - UFFS</p>';
		echo '</footer>';

	if(MODO_DEBUG) {
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
