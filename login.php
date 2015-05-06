<?php
	require_once dirname(__FILE__).'/inc/globals.php';

	authRestritoNaoLogado();
	$aErroLogin = false;

	if(count($_POST) > 0) {
		if(isset($_POST['usuario'], $_POST['senha']) && authIsUsuarioValido($_POST['usuario'], $_POST['senha'])) {
			authLogin($_POST['usuario']);

			header('Location: index.php');
			exit();
		} else {
			$aErroLogin = true;
		}
	}

	cabecalho('Login');

	echo '<div class="row">';
		echo '<div class="col-md-4 col-md-offset-4">';
			echo '<form action="login.php" method="post">
			          <legend>Login</legend>
			          <div class="form-group">
			            <label for="inputUsername">CPF</label>
			            <input name="usuario" class="form-control" type="text" id="inputUsername" placeholder="Ex.: 007.587.150-20">
			          </div>
					  <div class="form-group">
					    <label for="inputUsuario">Senha</label>
			            <input name="senha" class="form-control" type="password" id="inputPassword" placeholder="Informe sua senha">
						'.($aErroLogin ? '<span class="help-inline">Usuário ou senha inválidos.</span>' : '').'
			          </div>
			          <button type="submit" class="btn btn-default">Entrar</button>
			      </form>';
		echo '</div>';
	echo '</div>';

	rodape();
?>
