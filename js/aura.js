/**
 * API para manipulação Aura através.
 */

var AURA = new function() {
	var showLoading = function() {
	    $('#auraPainelResposta').slideDown();
	    $('#auraPainelResposta').html('<img src="../img/ajax-loader.gif" align="absmiddle" title="Pensando..."/> <small>Pensando...</small>');
	};
	
	this.submitOrder = function() {
		showLoading();
		
		$.ajax({
			  url: 		"aura-ajax.php?action=order",
			  context: 	document.body,
			  data: 	$('#formAura').serialize(),
			  
			  success: function(data){
				  $('html,body').animate({scrollTop: $("#linhaConsoleAura").offset().top - 60}, 'slow', function() {
					  $('#auraPainelResposta').fadeOut('fast', function() {
						  $('#auraPainelResposta').html(data);
						  $('#auraPainelResposta').fadeIn();					  
					  });
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
	
	this.refreshLabsDashboard = function(theIdsLabs) {
		var aId = '';
		
		for(var i = 0; i < theIdsLabs.length; i++) {
			aId = theIdsLabs[i];
			
			var aFunc = function() {
				$.ajax({
					  url: 		"lab-stats-ajax.php",
					  context: 	document.body,
					  data: 	'lab=' + aId,
					  
					  success: function(data){
						 $('#lab' + aId).fadeOut('fast', function() {
							  $('#lab' + aId).html(data);
							  $('#lab' + aId).fadeIn();					  
						 });
					  },
					  error: function() {
						  $('#lab' + aId).html("Erro ao obter dados. Tente recarregar a página.");  
					  }
				});
			}
			aFunc();
			setInterval(aFunc, 30000);
		}
	};

	this.init = function() {
		$('#formAura').submit(AURA.submitOrder);	
	};
};