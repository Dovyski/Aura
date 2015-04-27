/**
 * API para manipulação Aura através.
 */

var AURA = new function() {
	var showLoading = function(theStatus) {
		if(theStatus) {
			$('#aura-icon').attr('class', 'fa fa-circle-o-notch fa-spin');
		} else {
			$('#aura-icon').attr('class', 'fa fa-circle-o');
		}
	};

	this.submitOrder = function() {
		showLoading(true);

		$('#auraPainelResposta').html('').slideUp('fast');

		$.ajax({
			  url: 		"./api/",
			  context: 	document.body,
			  data: 	$('#formAura').serialize(),

			  success: function(data){
				  showLoading(false);

				  $('html,body').animate({scrollTop: $("#linhaConsoleAura").offset().top - 60}, 'fast', function() {
					  $('#auraPainelResposta').html(data).slideDown('fast');
				  });
			  },
			  error: function() {
				  $('#auraPainelResposta').html("Erro ao enviar ordem. Tente de novo.");
			  }
		});

	    $(':input','#formAura').val('');
		return false;
	};

	/**
	 * Imita a interação humana com o console da aura, colocando o texto
	 * informado dentro do console e pressionando o botão de enviar.
	 */
	this.typeConsoleCommand = function(theCommand) {
	    $(':input','#formAura').val(theCommand);
	    AURA.submitOrder();
	};


	this.formatUsingImage = function(theMachines) {
		var aImg = prompt('Qual o nome da imagem?');

		if(aImg != '') {
			AURA.sendCommand('Formate com a imagem "'+aImg+'" ' + theMachines);
		}
	};

	this.runArbitraryCommand = function(theMachines) {
		var aCommand = prompt('Qual o comando a ser executado?');

		if(aCommand != '') {
			AURA.sendCommand('Execute o comando "'+aCommand+'" ' + theMachines);
		}
	};

	/**
	 * Envia um comando para a Aura, mostrando o resultado desse comando
	 * como um popup na tela.
	 */
	this.sendCommand = function(theCommand, theCallback) {
		$.ajax({
			url: 		"./api/",
			context: 	document.body,
			data: 	{command: theCommand},

			success: function(theData){
				if(theCallback) {
					theCallback(theData);
				} else {
					alert(theData);
				}
			},
			error: function() {
				alert('Não foi possível executar a ação!');
			}
		});
	};

	this.refreshLabsDashboard = function(theIdsLabs) {
		var aId = '';

		for(var i = 0; i < theIdsLabs.length; i++) {
			aId = theIdsLabs[i];

			var aFunc = function() {
				$.ajax({
					  url: 		"ajax-lab-stats.php",
					  context: 	document.body,
					  data: 	'lab=' + aId,

					  success: function(data){
						 var aReg = /<!-- id: (.*) -->/g;
						 var aLabId = aReg.exec(data)[1];

						 $('#lab' + aLabId).fadeOut('fast', function() {
							  $('#lab' + aLabId).html(data);
							  $('#lab' + aLabId).fadeIn();
						 });
					  },
					  error: function() {
						  $('#lab' + aId).html("Erro ao obter dados. Tente recarregar a página.");
					  }
				});
			}
			aFunc();
			setInterval(aFunc, 60000);
		}
	};

	/**
	 *
	 */
	this.spyglass = function(theDeviceHash, theDeviceName) {
		window.open('spyglass.php?hash=' + theDeviceHash + '&name=' + encodeURIComponent(theDeviceName),'Spyglass','width=1920,height=1200,toolbar=0,menubar=0,location=0');
	};

	this.init = function() {
		$('#formAura').submit(AURA.submitOrder);
	};
};
