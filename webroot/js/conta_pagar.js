jQuery(document).ready(function() {
	
	$(".datepicker").datepicker();
	
	$('#ContaPagarValor').priceFormat();
	
	function valor_padrao() {
		$('label[for=ContaPagarClienteFornecedorId]').html('Código');
		$('#pesquisar_cliente_fornecedor')
			.attr('disabled','disabled')
			.val('Selecione uma opção no menu acima.');
		$("#ContaPagarClienteFornecedorId")
			.attr('disabled','disabled')
			.val('');
	}
	
	// Aplica, as opcoes de pesquisa pelo codigo ou pelo nome
	// do cliente ou fornecedor
	function definir_pesquisa (cliente_ou_fornecedor) {
		
		if (cliente_ou_fornecedor == 'cliente') ajaxPesquisa = ajaxPesqCliente;
		else if (cliente_ou_fornecedor == 'fornecedor') ajaxPesquisa = ajaxPesqFornecedor;
		else return 0;
		
		//autocomplete
		$("#pesquisar_cliente_fornecedor").autocomplete({
			source: ajaxPesquisa + "nome",
			minLength: 3,
			select: function(event, ui) {
				if (ui.item.bloqueado) {
					alert (cliente_ou_fornecedor+' está bloqueado!');
					$('#pesquisar_cliente_fornecedor').val('');
					$("#ContaPagarClienteFornecedorId").val('');
					event.preventDefault();
					return null;
				}
				if (ui.item.inativo) {
					alert (cliente_ou_fornecedor+' está inativo!');
					$('#pesquisar_cliente_fornecedor').val('');
					$("#ContaPagarClienteFornecedorId").val('');
					event.preventDefault();
					return null;
				}
				$("#ContaPagarClienteFornecedorId").val(ui.item.id);
				$('#pesquisar_cliente_fornecedor').val(ui.item.nome);
			}
		});
		// ao digitar o codigo
		$('#ContaPagarClienteFornecedorId')
		.off()
		.on('blur',function(){
			codigo = $(this).val();
			if (codigo == null || codigo == '') return null;
			$.getJSON(ajaxPesquisa + 'codigo', {'term': codigo}, function(data) {
				if (data == null) {
					alert (cliente_ou_fornecedor+' com o código '+codigo+' não foi encontrado!');
					$('#pesquisar_cliente_fornecedor').val('');
					$("#ContaPagarClienteFornecedorId")
						.val('')
						.focus();
				}
				else { //encontrou resultados
					data = data[0];
					if (data.bloqueado) {
						alert (cliente_ou_fornecedor+' está bloqueado!');
						$('#pesquisar_cliente_fornecedor').val('');
						$("#ContaPagarClienteFornecedorId").val('');
						return null;
					}
					if (data.inativo) {
						alert (cliente_ou_fornecedor+' está inativo!');
						$('#pesquisar_cliente_fornecedor').val('');
						$("#ContaPagarClienteFornecedorId").val('');
						return null;
					}
					$("#ContaPagarClienteFornecedorId").val(data.id);
					$('#pesquisar_cliente_fornecedor').val(data.nome);
				}
			});
		});
	}
	
	// de acordo com o selecionado, defino o que será pesquisado
	$('#ContaPagarEhClienteOuFornecedor').change(function(){
		
		$('#pesquisar_cliente_fornecedor')
			.removeAttr('disabled')
			.val('');
		$("#ContaPagarClienteFornecedorId")
			.removeAttr('disabled')
			.val('');
		
		if ($(this).val() == 'C') {
			$('label[for=ContaPagarClienteFornecedorId]').html('Cliente');
			definir_pesquisa('cliente');
		}
		else if ($(this).val() == 'F') {
			$('label[for=ContaPagarClienteFornecedorId]').html('Fornecedor');
			definir_pesquisa('fornecedor');
		}
		else {
			valor_padrao();
		}
		
	});
	
	// para situações onde a página é carrega ja com as informacoes
	if ($("#ContaPagarClienteFornecedorId").val() == '') valor_padrao();
	
	if ( $('#ContaPagarEhClienteOuFornecedor').val() == 'C' )  {
		$('label[for=ContaPagarClienteFornecedorId]').html('Cliente');
		definir_pesquisa('cliente');
	}
	else if ( $('#ContaPagarEhClienteOuFornecedor').val() == 'F' )  {
		$('label[for=ContaPagarClienteFornecedorId]').html('Fornecedor');
		definir_pesquisa('fornecedor');
	}
	
}); // fim document ready
