<?php 
	require_once dirname(__FILE__).'/inc/globals.php';
	
	authRestritoNaoLogado();
	$aErroLogin = false;
	
	if(count($_POST) > 0) {
		if(isset($_POST['usuario'], $_POST['senha']) && authIsUsuarioValido($_POST['usuario'], $_POST['senha'])) {
			authLogin($_POST['usuario']);
			
			header('Location: ' . (authIsAdmin() ? 'admin.index.php' : 'index.php'));
			exit();
		} else {
			$aErroLogin = true;
		}
	}
	
	cabecalho('Login');
	
	echo '<div class="hero-unit fundo-icone icone-senha">';
		echo '<h1>Intranet</h1>';
		echo '<p>Acesso à administração do NCC.</p>';
	echo '</div>';
	
	echo '<div class="row">';
		echo '<div class="span9">';
			echo '<form class="form-horizontal" action="login.php" method="post">
			        <fieldset>
			          <legend>Login</legend>
			          <div class="control-group '.($aErroLogin ? 'error' : '').'">
			            <label class="control-label">Usuário</label>
			            <div class="controls docs-input-sizes">
			              <input name="usuario" class="span3" type="text" placeholder="Seu usuário NCC">
			            </div>
			            <label class="control-label">Senha</label>
			            <div class="controls docs-input-sizes">
			              <input name="senha" class="span3" type="password" placeholder="sua senha">
						  '.($aErroLogin ? '<span class="help-inline">Usuário ou senha inválidos.</span>' : '').'
			            </div>
			          </div>
			          <div class="form-actions">
			            <button type="submit" class="btn btn-primary">Entrar</button>
			            <button type="reset" class="btn">Limpar</button>
			          </div>
			        </fieldset>
			      </form>'; 
		echo '</div>';
		
		echo '<div class="span3">';
			echo '<h2>Atenção</h2>';
			echo '<p>Para efetuar login, você precisa de uma conta NCC.</p>';
			echo '<p>Obtenha a sua conta gratuitamente com algum membro da equipe NCC.</p>';
		echo '</div>';
	echo '</div>';
	
	rodape();
?>