<?php 
	require_once dirname(__FILE__).'/inc/globals.php';
	require_once dirname(__FILE__).'/admin/aura/globals.php';

	authRestritoAdmin();
	cabecalho('Inicial');
	
	echo '<div class="hero-unit">';
		echo '<h1>Dashboard</h1>';
		echo '<p>Visão geral dos laboratórios do NCC.</p>';
	echo '</div>';

	$aLabs = Aura\Groups::findByName('lab-%');
	
	if(count($aLabs) > 0) {
		$aIdsLabs = array();
		
		foreach($aLabs as $aLab) {
			$aIdsLabs[]	= $aLab['id'];

			echo '<div class="row" style="margin-top: 30px;">';
				echo '<div class="span12">';
					echo '<h2>'.$aLab['name'].' <small>'.$aLab['desc'].'</small></h2>';
				echo '</div>';
			echo '</div>';
			
			echo '<div class="row" id="lab'.$aLab['id'].'"></div>';
		}
	} else {
		echo '<div class="row" style="margin-top: 10px;">';
			echo '<div class="span12">';
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