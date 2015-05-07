<?php
	require_once dirname(__FILE__).'/inc/globals.php';

	authAllowAdmin();
	cabecalho('Inicial');

	$aLabs = Aura\Groups::findByName('lab-%');

	if(count($aLabs) > 0) {
		$aIdsLabs = array();

		foreach($aLabs as $aLab) {
			$aIdsLabs[]	= $aLab['id'];

			echo '<div class="row" style="margin-bottom: 30px;">';
				echo '<div class="col-md-12">';
					echo '<h2><i class="fa fa-cube"></i> '.$aLab['name'].' <small>'.$aLab['desc'].'</small></h2>';
				echo '</div>';
			echo '</div>';

			echo '<div class="row" id="lab'.$aLab['id'].'"></div>';
		}
	} else {
		echo '<div class="row" style="margin-top: 10px;">';
			echo '<div class="col-md-12">';
				echo '<h2>Nenhum laboratório</h2>';
				echo '<p>Não há laboratório com nome <strong>lab-ncc-*</strong>.</p>';
			echo '</div>';
		echo '</div>';
	}

	if(isset($aIdsLabs)) {
		echo '<script type="text/javascript">
				$(function() {
					AURA.refreshLabsDashboard(['.implode(',', $aIdsLabs).']);
				});
			  </script>';
	}
	rodape();
?>
