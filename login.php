<?php
	require_once dirname(__FILE__).'/inc/globals.php';

	authAllowNonAuthenticated();

	$aLoginError 	= false;
	$aHasAccount 	= false;

	if(count($_POST) > 0) {
		if (isset($_POST['user'], $_POST['password'])) {

			// TODO: fix this because the login string might have . and -
			$aCpf = str_replace(array('.', '-', ' ', ','), '', $_POST['user']);
			$aCpf = ltrim($aCpf,  '0');

			$aHasAccount = authIsValidUser($aCpf, $_POST['password']);
			$aUser = '';

			if ($aHasAccount) {
				$aUser = $aCpf;

			} else {
				// TODO: it would be nice to have some sort of auth plugins here :)
				$aMoodleInfo = authLoginUsingMoodle($aCpf, $_POST['password']);

				if ($aMoodleInfo != null) {
					$aMoodleInfo['email'] = 'user@moodle.uffs'; // TODO: get this info from moodle
					$aHasAccount = authCreateLocalAccountUsingLoginMoodle($aMoodleInfo, $aCpf, $_POST['password']);

					if($aHasAccount) {
						$aUser = $aCpf;
					} else {
						$aLoginError = true;
					}
				} else {
					$aLoginError = true;
				}
			}

			if($aHasAccount) {
				authLogin($aUser);
				header('Location: index.php');
				exit();
			}
		} else {
			$aLoginError = true;
		}
	}

	cabecalho('Login');

	echo '<div class="row">';
		echo '<div class="col-md-4 col-md-offset-4">';
			echo '<form action="login.php" method="post">
			          <legend>Login</legend>
					  '.($aLoginError ? '<div class="alert alert-danger" role="alert">Usuário ou senha inválidos</div>' : '').'
			          <div class="form-group">
			            <label for="inputUsername">CPF</label>
			            <input name="user" class="form-control" type="text" id="inputUsername" placeholder="Ex.: 007.587.150-20">
			          </div>
					  <div class="form-group">
					    <label for="inputUsuario">Senha</label>
			            <input name="password" class="form-control" type="password" id="inputPassword" placeholder="Informe sua senha">
			          </div>
			          <button type="submit" class="btn btn-default">Entrar</button>
			      </form>';
		echo '</div>';
	echo '</div>';

	rodape();
?>
