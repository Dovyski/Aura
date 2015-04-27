<?php
	require_once dirname(__FILE__).'/inc/globals.php';

	authRestritoAdmin();
	cabecalho('Aura');

	echo '<div class="row" id="linhaConsoleAura">';
		echo '<div class="col-md-12" style="text-align: center;">';
			echo '<p style="font-size: 100px;"><i id="aura-icon" class="fa fa-circle-o"></i></p>';
			echo '<div class="form-group">';
				echo '<form action="" method="post" id="formAura" class="form-inline">';
					echo '<div class="form-group">';
						echo '<input name="command" id="consoleAura" class="form-control" style="width: 700px;" id="prependedInput" type="text" placeholder="Digite seu comando para a Aura. Ex.: que horas são?">';
					echo '</div>';
					echo '<i class="fa fa-bug"></i><input name="debug" type="checkbox" />';
				echo '</form>';
				echo '<p id="auraPainelResposta" class="aura-resposta-console" style="display: none;">Resposta...</p>';
            echo '</div>';
		echo '</div>';
	echo '</div>';

	echo '<script type="text/javascript">
			$(function() {
				AURA.init();
			});
		  </script>';
	rodape();
?>
