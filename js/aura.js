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
				  $('#auraPainelResposta').html(data);
			  },
			  error: function() {
				  $('#auraPainelResposta').html("Erro ao enviar ordem. Tente de novo.");  
			  }
		});
		
	    $(':input','#formAura').val('');
		return false;
	};

	this.init = function() {
		$('#formAura').submit(AURA.submitOrder);	
	};
};