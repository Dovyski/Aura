<?php 
	require_once dirname(__FILE__).'/inc/globals.php';
	
	cabecalho('Guia rápido');
	
	echo '<div class="hero-unit  fundo-icone icone-guia">';
		echo '<h1>Guia rápido</h1>';
		echo '<p>O que você precisa saber sobre o NCC e os laboratórios.</p>';
	echo '</div>';
	
	echo '<div class="row">';
		echo '<div class="span12">';
			echo '<p>Os laboratórios (salas com computadores) do curso são gerenciados por um grupo chamado <strong>NCC (Núcleo de Ciência da Computação)</strong>. A missão desse grupo é garantir o bom funcionamento e segurança das informações dos laboratórios, assim todos podem usufruir da infra-estrutura da UFFS da melhor forma.</p>';
			echo '<p>Para utilizar os computadores do NCC, você precisa de uma <strong>conta NCC</strong>. Essa conta é fornecida de forma gratuíta a alunos, professores e técnicos administrativos vinculados ao curso. A conta é composta de um <strong>nome de usuário</strong> (ex.: fulano) e de uma <strong>senha</strong>. Você precisa do seu nome de usuário e senha para efetuar login nos computadores, caso contrário não conseguirá utilizá-los. Veja abaixo tudo que você precisa saber sobre o NCC na forma de 8 mandamentos simples e rápidos.</p>';
		echo '</div>';
	echo '</div>';
	
	echo '<div class="row" id="mandamentos">';
		echo '<div class="span12">';
			echo '<h3>1. Nunca emprestarei meu usuário e senha.</h3>';
			echo '<p>Sua conta NCC é única, pessoal e intransferível. Cada fez que um usuário faz login, as atividades desse usuário são monitoradas pelo NCC, dessa forma atividades suspeitas ou maléficas podem ser rastreadas. Se você emprestar sua senha e alguém fizer algo de errado com ela, você <strong>também</strong> responderá pelo ocorrido, mesmo não tendo participado de qualquer ação.</p>';
		echo '</div>';
		echo '<div class="span12">';
			echo '<h3>2. Sempre farei logout depois de usar algum computador.</h3>';
			echo '<p>Quando você faz login em algum computador, qualquer atividade realizada nesse computador estará ligada a sua conta. Se você terminou de usar o computador, efetue logout para evitar que alguém altere seus dados ou vandalize sua conta.</p>';
		echo '</div>';
		echo '<div class="span12">';
			echo '<h3>3. Nunca salvarei arquivos no computador, apenas em minha pasta no servidor.</h3>';
			echo '<p>Os computadores do NCC são formatados <strong>diariamente</strong>, sem qualquer backup dos dados existentes neles. Se você tem algo importante para guardar, salve isso na sua <a href="armazenamento.php" target="_blank">pasta no servidor</a>.</p>';
		echo '</div>';
		echo '<div class="span12">';
			echo '<h3>4. Usarei a pasta "temp" para compartilhar arquivos entre os computadores.</h3>';
			echo '<p>Todos os computadores do NCC estão interligados por uma rede. Existe uma pasta especial na rede chamada <strong>temp</strong>, cujo conteúdo é <strong>apagado</strong> toda madrugada. Utilize essa pasta para compartilhar arquivos entre os computador, chega de pen-drives ou e-mails com anexo enquanto estiver dentro do NCC ;)</p>';
		echo '</div>';
		echo '<div class="span12">';
			echo '<h3>5. Não comerei ou beberei algo nas salas do NCC.</h3>';
			echo '<p>Máquinas não se dão bem com restos de comida, gordura e líquidos. Se deseja fazer um lanche ou beber algo, utilize o bar.</p>';
		echo '</div>';
		echo '<div class="span12">';
			echo '<h3>6. Colocarei de volta qualquer cabo que eu remover.</h3>';
			echo '<p>Se você remover algum cabo para conectar seu notebook ou celular à tomada, por exemplo, recoloque esse cabo quando terminar. Você é responsável também por manter a ordem e garantir que todos consigam usar os computadores sem precisarem conectar ou alterar cabos de lugar.</p>';
		echo '</div>';
		echo '<div class="span12">';
			echo '<h3>7. Cuidarei do patrimônio como se fosse meu.</h3>';
			echo '<p>Os computadores do NCC são ferramentas de trabalho, instrumentos que farão o conhecimento dos alunos crescer. Cuide-os e garanta que a infra-estrutura de seu curso de graduação possa maximizar seu aprendizado.</p>';
		echo '</div>';
		echo '<div class="span12">';
			echo '<h3>8. Mudarei minha senha se suspeitar que ela foi roubada ou descoberta.</h3>';
			echo '<p>Sua senha é muito importante. Se você acha que alguém descobriu ela ou ela foi roubada de alguma forma, <a href="senha.php" target="_blank">troque sua senha</a> imediatamente.</p>';
		echo '</div>';
	echo '</div>';
	
	rodape();
?>