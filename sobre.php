<?php 
	require_once dirname(__FILE__).'/inc/layout.php';
	
	cabecalho('Sobre');
	
	echo '<div class="hero-unit fundo-icone icone-sobre">';
		echo '<h1>Sobre</h1>';
		echo '<p>O que é o Núcleo de Ciência da Computação.</p>';
	echo '</div>';
	
	echo '<div class="row">';
		echo '<div class="span6">';
			echo '<h2>Função</h2>';
			echo '<p>O NCC (Núcleo de Ciência da Computação) tem como objetivo garantir o bom funcionamento dos laboratórios do curso, propiciando a seus usuários uma excelente experiência da uso das máquinas.</p>';
			echo '<p>A equipe do NCC mantém os computadores formatados, rápidos e livres de softwares maliciosos. Além disso, proporciona aos usuários ferramentas que auxiliem atividades do dia-a-dia, como compartilhamento de arquivos e armazenamento de documentos importantes (trabalhos de aula, materiais, etc).</p>';
		echo '</div>';
		
		echo '<div class="span6">';
			echo '<h2>Atividades</h2>';
			echo '<p>Os integrandes do NCC realizam diversas atividades ligadas a hardware e software, dentre elas:</p>';
			echo '<p>';
				echo '<ul>';
					echo '<li>Utilização e desenvolvimento de soluções para automatização de tarefas;</li>';
					echo '<li>Estudo, desenvolvimento e utilização de soluções de virtualização de máquinas e servidores;</li>';
					echo '<li>Pesquisas e aplicação de IA (inteligiência artificial) para facilitar a vida dos usuários;</li>';
					echo '<li>Atendimento aos usuários do NCC;</li>';
					echo '<li>Estudo aprofundado sobre sistemas operacionais e tecnologias de rede.</li>';
				echo '</ul>';
			echo '</p>';
		echo '</div>';
	echo '</div>';
	
	echo '<div class="row" style="margin-top: 30px;">';		
		echo '<div class="span12">';
			echo '<h2>Equipe</h2>';
			echo '<p>O NCC conta com a seguinte equipe no momento:</p>';
		echo '</div>';
	echo '</div>';
		
	echo '<div class="row">';		
		echo '<div class="span12">';
			echo '<div class="membro-equipe">';
				echo '<h1>Prof. Fernando Bevilacqua</h1>';
				echo '<p>fernando.bevilacqua@uffs.edu.br</p>';
				echo '<p><strong>Coordenador</strong></p>';
			echo '</div>';
		
			echo '<div class="membro-equipe">';
				echo '<h1>Aluno Voluntário 1</h1>';
				echo '<p>Seja um voluntário</p>';
			echo '</div>';
		
			echo '<div class="membro-equipe">';
				echo '<h1>Aluno Voluntário 2</h1>';
				echo '<p>Seja um voluntário</p>';
			echo '</div>';
		echo '</div>';
	echo '</div>';
	
	rodape();
?>