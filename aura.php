<?php
	require_once dirname(__FILE__).'/inc/globals.php';

	authAllowAdmin();
	cabecalho('Aura');

	echo '<div class="row" id="linhaConsoleAura">';
		echo '<div class="col-md-8 col-md-offset-2" style="text-align: center;">';
			echo '<p style="font-size: 100px;"><i id="aura-icon" class="fa fa-circle-o"></i></p>';
			echo '<div class="form-group">';
				echo '<form action="" method="post" id="formAura">';
					echo '<div class="form-group">';
						echo '<input name="command" id="consoleAura" class="form-control" type="text" placeholder="Digite seu comando para a Aura. Ex.: que horas são?">';

						echo ' <a href="javascript:void(0);" id="listen-button">';
							echo '<span class="fa-stack fa-lg" id="microphone-icon">';
								echo '<i class="fa fa-circle-o fa-stack-2x"></i>';
								echo '<i class="fa fa-microphone fa-stack-1x"></i>';
							echo '</span>';
						echo '</a>';
					echo '</div>';
					echo '<br /><input name="debug" type="checkbox" /><i class="fa fa-bug" title="Debug?"></i>';
				echo '</form>';
				echo '<p id="auraPainelResposta" class="aura-resposta-console" style="display: none;">Resposta...</p>';
			echo '</div>';
		echo '</div>';
	echo '</div>';

	echo '<script type="text/javascript">
			$(function() {
				AURA.init();
				AURA.speech.init();
			});
		  </script>';
	rodape();
?>
