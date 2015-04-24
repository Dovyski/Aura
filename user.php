<?php 
	require_once dirname(__FILE__).'/inc/globals.php';
	require_once dirname(__FILE__).'/admin/aura/globals.php';
	
	authRestritoLogado();
	
	$aDadosAtualizados = false;
	$aContatos = array(
		'Gtalk' 	=> 'fulano@gmail.com',
		'Twitter'	=> '@fulano',
		'MSN'		=> 'fulano@hotmail.com'
	);
	
	if(count($_POST) > 0) {
		$aInfoContato = array();
		
		foreach($aContatos as $aMeio) {
			if(isset($_POST[$aMeio]) && !empty($_POST[$aMeio])) {
				$aInfoContato[$aMeio] = $_POST[$aMeio];
			}			
		}
 		$aEmail = isset($_POST['uemail']) ? $_POST['uemail'] : '';
		$aDadosAtualizados = true;
		
		Aura\Db::execute("UPDATE ".Aura\Db::TABLE_USERS." SET email='".addslashes($aEmail)."', contact='".addslashes(serialize($aInfoContato))."' WHERE login LIKE '".$_SESSION['usuario']['uid']."'");
	}


	
	$aDados 			= Aura\Users::getByLogin($_SESSION['usuario']['uid']);
	$aArrayContact 		= @unserialize($aDados['contact']);
	$aDados['contact'] 	= !is_array($aArrayContact) ? array() : $aArrayContact;
	
	cabecalho('Sua conta');
	
	echo '<div class="hero-unit fundo-icone icone-senha">';
		echo '<h1>Sua conta</h1>';
		echo '<p>Gerencie as informações de sua conta NCC.</p>';
	echo '</div>';
	
	echo '<div class="row">';
		echo '<div class="span12">';
				if($aDadosAtualizados) {
					echo '<div class="alert alert-success">';
					echo '<strong>Sucesso!</strong> Seus dados foram atualizados.';
					echo '</div>';
				}
		
			echo '<form class="form-horizontal" action="conta.php" method="post">
			        <fieldset>
			          <legend>Dados</legend>
			          <div class="control-group error">
			            <label class="control-label">Login</label>
			            <div class="controls docs-input-sizes disabled">
			              <input name="login" class="span3 disabled" type="text" value="'.$_SESSION['usuario']['uid'].'" disabled>
						  <p class="help-inline">Para alterar esses campos, procure alguém do NCC.</p>
			            </div>
			            <label class="control-label">Nome</label>
			            <div class="controls docs-input-sizes">
			              <input name="nome" class="span3 disabled" type="text" value="'.$_SESSION['usuario']['cn'].'" disabled>
			            </div>
			          </div>';
			
				echo '<div class="control-group">';
					echo '<label class="control-label">E-mail</label>
						  <div class="controls docs-input-sizes">
							<input name="uemail" class="span3" type="text" value="'.(isset($_POST['uemail']) ? $_POST['uemail'] : $aDados['email']).'" placeholder="E-mail para contato">
						  </div>';
				
					foreach($aContatos as $aNomeContato => $aPlaceholder) {
						echo '<label class="control-label">'.$aNomeContato.'</label>';
						echo '<div class="controls docs-input-sizes">';
							echo '<input name="'.$aNomeContato.'" class="span3" type="text" value="'.(isset($aDados['contact'][$aNomeContato]) ? $aDados['contact'][$aNomeContato] : '').'"  placeholder="'.$aPlaceholder.'">';
						echo '</div>';
					}
				
				echo '</div>';
			
			echo '
			          <div class="form-actions">
			            <button type="submit" class="btn btn-primary">Salvar</button>
			            <button type="reset" class="btn">Limpar</button>
			          </div>
			        </fieldset>
			      </form>'; 
		echo '</div>';
		/*
		echo '<div class="span3">';
			echo '<h2>Atenção</h2>';
			echo '<p>Para efetuar login na intranet, você precisa fazer parte da equipe do NCC.</p>';
			echo '<p>Se quiser fazer parte da equipe, dê uma olhada <a href="sobre.php">aqui</a>.</p>';
		echo '</div>';*/
	echo '</div>';
	
	rodape();
?>