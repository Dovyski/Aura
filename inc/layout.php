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
						echo '<li '.($aPagina == 'index.php' 			? 'class="active"' : '').'><a href="index.php">Inicial</a></li>';
						echo '<li '.($aPagina == 'guia.php' 			? 'class="active"' : '').'><a href="guia.php">Guia rápido</a></li>';
						echo '<li '.(strstr($aPagina, 'armazenamento') !== false ? 'class="active"' : '').'><a href="armazenamento.php">Armazenamento</a></li>';
						echo '<li '.($aPagina == 'senha.php' 			? 'class="active"' : '').'><a href="senha.php">Troca de senha</a></li>';
						echo '<li '.($aPagina == 'sobre.php' 			? 'class="active"' : '').'><a href="sobre.php">Sobre</a></li>';
					echo '</ul>';
					
					layoutBarraUsuarioLogado();
											
				echo '</div><!--/.nav-collapse -->';
			echo '</div>';
		echo '</div>';
	echo '</div>';
}

function barraNavegacaoAdmin() {
	$aPagina = basename($_SERVER['PHP_SELF']);

	echo '<div class="navbar navbar-fixed-top">';
		echo '<div class="navbar-inner">';
			echo '<div class="container">';
				echo '<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">';
					echo '<span class="icon-bar"></span>';
					echo '<span class="icon-bar"></span>';
					echo '<span class="icon-bar"></span>';
				echo '</a>';
				echo '<a class="brand" href="index.php">Intranet</a>';
				
				echo '<div class="nav-collapse">';
					echo '<ul class="nav">';
						echo '<li '.($aPagina == 'index.php' 			? 'class="active"' : '').'><a href="index.php">Inicial</a></li>';
						echo '<li '.($aPagina == 'aura.php' 			? 'class="active"' : '').'><a href="aura.php">Aura</a></li>';
					echo '</ul>';
					
					layoutBarraUsuarioLogado();
					
				echo '</div><!--/.nav-collapse -->';
			echo '</div>';
		echo '</div>';
	echo '</div>';
}

function layoutBarraUsuarioLogado() {
	$aLinkProfile = utilIsNavegandoIntranet() ? '../conta.php' : './conta.php';
	$aClassLink	  = authIsAdmin() ? 'btn-danger' : 'btn-primary';
	
	echo '<ul class="nav pull-right">';
		echo '<div class="btn-group">';
			if(authIsLogado()) {
				echo '<a class="btn '.$aClassLink.'" href="'.$aLinkProfile.'"><i class="icon-user icon-white"></i> '.$_SESSION['usuario']['cn'].'</a>';
				echo '<a class="btn '.$aClassLink.' dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span></a>';
					
				echo '<ul class="dropdown-menu">';
				echo '<li><a href="'.(utilIsNavegandoIntranet() ? '../logout.php' : './logout.php').'"><i class="icon-remove"></i> Sair</a></li>';
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
		
		echo '<!-- Le styles -->';
		echo '<link href="'.$theBaseUrl.'/css/bootstrap.css" rel="stylesheet">';
		echo '<link href="'.$theBaseUrl.'/css/style.css" rel="stylesheet">';
		
		if(LAYOUT_RESPONSIVE) {
			echo '<link href="'.$theBaseUrl.'/css/bootstrap-responsive.css" rel="stylesheet">';
		}
		
		echo '<!-- Le fav and touch icons -->';
		echo '<link rel="shortcut icon" href="img/favicon.ico">';
		echo '<link rel="apple-touch-icon" href="/img/apple-touch-icon.png">';
		echo '<link rel="apple-touch-icon" sizes="72x72" href="/img/apple-touch-icon-72x72.png">';
		echo '<link rel="apple-touch-icon" sizes="114x114" href="/img/apple-touch-icon-114x114.png">';
		
		echo '<script src="'.$theBaseUrl.'/js/jquery.js"></script>';
		echo '<script src="'.$theBaseUrl.'/js/bootstrap.js"></script>';
		echo '<script src="'.$theBaseUrl.'/js/aura.js"></script>';
	echo '</head>';
	
	echo '<body>';
	
	if(authIsLogado() && utilIsNavegandoIntranet()) {
		barraNavegacaoAdmin();
	} else {
		barraNavegacao();
	}
	
	echo '<div class="container">';
}

function rodape($theBaseUrl = '.') {
		echo '<hr>';
		
		echo '<footer>';
			echo '<p style="float:left;">NCC - Ciência da Computação - UFFS</p>';
			if(utilIsNavegandoIntranet()) {
				echo '<p style="float:right;"><a href="../" title="Acesso à área pública do site do NCC.">Site</a></p>';
			} else {
				echo '<p style="float:right;"><a href="./admin" title="Acesso à intranet do NCC.">Intranet</a></p>';
			}
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