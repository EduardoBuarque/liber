jQuery(document).ready(function() {

	$('#pedido_compra_abas').tabs();
	
	/*******************
	 * Aba informações 
	 ******************/
	$(".datepicker").datepicker();
	$('#CompraPedidoDesconto').priceFormat();
	$('#ProdutoPrecoCompra').priceFormat();
	data = new Date();
	data_fim = data.getDate()+'/'+(data.getMonth()+1)+'/'+data.getFullYear();
	if ($('#CompraPedidoDataCompra').val() == '') {
		$('#CompraPedidoDataCompra').val(data_fim);
	}
	
	/****************
	 * Aba fornecedor
	 ****************/
	
	//pesquisa fornecedor
	//autocomplete
	$("#CompraPedidoPesquisarNomeFornecedor").autocomplete({
		source: ajaxPesqFornecedor + "nome",
		minLength: 3,
		select: function(event, ui) {
			if (ui.item.bloqueado) {
				alert ('Fornecedor está bloqueado!');
				$('#CompraPedidoPesquisarNomeFornecedor').val('');
				$("#CompraPedidoFornecedorId").val('');
				event.preventDefault();
				return null;
			}
			if (ui.item.inativo) {
				alert ('Fornecedor está inativo!');
				$('#CompraPedidoPesquisarNomeFornecedor').val('');
				$("#CompraPedidoFornecedorId").val('');
				event.preventDefault();
				return null;
			}
			$("#CompraPedidoFornecedorId").val(ui.item.id);
			$('#CompraPedidoPesquisarNomeFornecedor').val(ui.item.nome);
		}
	});
	// ao digitar o codigo
	$('#CompraPedidoFornecedorId').blur(function(){
		codigo = $(this).val();
		if (codigo == null || codigo == '') return null;
		$.getJSON(ajaxPesqFornecedor + 'codigo', {'term': codigo}, function(data) {
			if (data == null) {
				alert ('Fornecedor com o código '+codigo+' não foi encontrado!');
				$('#CompraPedidoPesquisarNomeFornecedor').val('');
				$("#CompraPedidoFornecedorId")
					.val('')
					.focus();
			}
			else { //encontrou resultados
				data = data[0];
				if (data.bloqueado) {
					alert ('Fornecedor está bloqueado!');
					$('#CompraPedidoPesquisarNomeFornecedor').val('');
					$("#CompraPedidoFornecedorId").val('')
					return null;
				}
				if (data.inativo) {
					alert ('Fornecedor está inativo!');
					$('#CompraPedidoPesquisarNomeFornecedor').val('');
					$("#CompraPedidoFornecedorId").val('')
					return null;
				}
				$("#CompraPedidoFornecedorId").val(data.id);
				$('#CompraPedidoPesquisarNomeFornecedor').val(data.nome);
			}
		});
	});
	
	/****************
	 * Aba produtos
	 ****************/
	
	$("#ProdutoNome").autocomplete({
		source: ajaxPesqProduto + "nome",
		minLength: 3,
		select: function(event, ui) {
			if (ui.item.fora_de_linha) {
				alert ('Produto '+ui.item.id+' está fora de linha!');
				$('#ProdutoNome').val('');
				$('#ProdutoPrecoCompra').val('');
				$('#ProdutoQuantidade').val('');
				$('#ProdutoId')
					.val('')
					.focus();
				return false;
			}
			if (! ui.item.eh_vendido) {
				alert ('O tipo do produto '+ui.item.id+' impede que seja vendido!');
				$('#ProdutoNome').val('');
				$('#ProdutoPrecoCompra').val('');
				$('#ProdutoQuantidade').val('');
				$('#ProdutoId')
					.val('')
					.focus();
				return false;
			}
			$("#ProdutoId").val(ui.item.id);
			$('#ProdutoPrecoCompra').val(ui.item.preco_compra);
			$('#ProdutoQuantidade').focus();
		}
	});
	
	$('.remover_linha').live('click',function(evento){
		//passo a referencia a linha da tabela
		remover_linha($(this).parent().parent());
	});
	
	$('#ProdutoId').blur(function(){
		procurar_por_codigo($(this).val());
	});
	
	$('#produtos_pesquisar input').bind('keypress', function(e){
		if ( e.keyCode == 13 ) {
			e.preventDefault();
			adicionar_produto();
		}
	});
	
	$('#produtos_incluidos tr').live('click',function() {
		procurar_por_codigo( $(this).find('.item_id').val() );
		$('#produtos_pesquisar #ProdutoQuantidade').val( $(this).find('.item_qtd').val() );
		$('#produtos_pesquisar #ProdutoPrecoCompra').val( $(this).find('.item_val').val() );
	});
	
	function procurar_por_codigo(codigo) {
		if (codigo == null || codigo == '') return null;
		$.getJSON( ajaxPesqProduto + 'codigo', {'term': codigo}, function(data) {
			if (data == null) {
				alert ('Produto com o código '+codigo+' não foi encontrado!');
				$('#ProdutoNome').val('');
				$('#ProdutoPrecoCompra').val('');
				$('#ProdutoQuantidade').val('');
				$('#ProdutoId')
					.val('')
					.focus();
				return false;
			}
			else {
				data = data[0];
				if (data.fora_de_linha) {
					alert ('Produto '+codigo+' está fora de linha!');
					$('#ProdutoNome').val('');
					$('#ProdutoPrecoCompra').val('');
					$('#ProdutoQuantidade').val('');
					$('#ProdutoId')
					.val('')
					.focus();
					return false;
				}
				if (! data.eh_vendido) {
					alert ('O tipo do produto '+codigo+' impede que seja vendido!');
					$('#ProdutoNome').val('');
					$('#ProdutoPrecoCompra').val('');
					$('#ProdutoQuantidade').val('');
					$('#ProdutoId')
					.val('')
					.focus();
					return false;
				}

				$('#ProdutoId').val(data.id);
				$('#ProdutoNome').val(data.label);
				$('#ProdutoQuantidade').focus();
			}
		});
	}

	function adicionar_produto() {
		id = $('#ProdutoId').val();
		nome = $('#ProdutoNome').val();
		quantidade = $('#ProdutoQuantidade').val();
		valor = $('#ProdutoPrecoCompra').val();

		if ( (id == '') || (nome == '') || (quantidade == '') || (valor == '') ) {
			alert ('Há campos não preenchidos!');
			return false;
		}

		if ( ! eh_inteiro(id) ) {
			alert ('O campo código é inválido!');
			return false;
		}

		if ( ! eh_numero(moeda2numero(quantidade)) ) {
			alert ('A quantidade informada é inválida!');
			return false;
		}

		//se o item já foi inserido, removo o que havia
		$('#produtos_incluidos tr').each(function() {
			v = $(this).find('.item_id').val();
			if ( v == id ) {
				remover_linha($(this));
				// obtenho os novos valores
				id = $('#ProdutoId').val();
				nome = $('#ProdutoNome').val();
				quantidade = $('#ProdutoQuantidade').val();
				valor = $('#ProdutoPrecoCompra').val();
			}
		});

		numero_campo = parseInt($('#numero_itens_incluidos').val());

		novo_campo =
		'<tr>'+
			'<td> <input type="text" name="data[CompraPedidoItem]['+numero_campo+'][produto_id]" value="'+id+'" class="noinput item_id" /> </td>'+
			'<td> <input type="text" name="data[CompraPedidoItem]['+numero_campo+'][produto_nome]" value="'+nome+'" class="noinput item_nome" /> </td>'+
			'<td> <input type="text" name="data[CompraPedidoItem]['+numero_campo+'][quantidade]" value="'+quantidade+'" class="noinput item_qtd" /> </td>'+
			'<td> <input type="text" name="data[CompraPedidoItem]['+numero_campo+'][preco_compra]" value="'+valor+'" class="noinput item_val" /> </td>'+
			'<td> <img src="'+site_raiz+'/img/del24x24.png" class="remover_linha"/> </td>'+
		'</tr>'+"\n";

		$('#produtos_incluidos').append(novo_campo);
		$('#numero_itens_incluidos').val(numero_campo+1);

		valor_total = moeda2numero($('#valor_total').html());
		valor_total = parseFloat(valor_total);
		valor_total += moeda2numero(quantidade) * (moeda2numero(valor));
		valor_total = arredonda_float(valor_total);
		$('#valor_total').html(numero2moeda(valor_total));

		limpar_pesquisa();
		$('#ProdutoId').focus();
	}

	function remover_linha(objeto_jquery) {
		linha = objeto_jquery;
		id = linha.find('.item_id').val();
		nome = linha.find('.item_nome').val();
		quantidade = linha.find('.item_qtd').val();
		valor = linha.find('.item_val').val();

		valor_total = moeda2numero($('#valor_total').html());
		valor_total = parseFloat(valor_total);
		valor_total -= moeda2numero(quantidade) * (moeda2numero(valor));
		valor_total = arredonda_float(valor_total);

		if (valor_total == 0) {
			$('#valor_total').html('0,0');
		}
		else {
			$('#valor_total').html(numero2moeda(valor_total));
		}

		linha.remove();
	}

	function limpar_pesquisa() {
		$('#produtos_pesquisar input').each(function() {
			$(this).val('');
		});
	}
	
	$('#ProdutoAdicionar').bind('click',function(){
		adicionar_produto();
	});

	/***************
	 * Aba Outros
	 ***************/
	$('#CompraPedidoCustoFrete').priceFormat();
	$('#CompraPedidoCustoSeguro').priceFormat();
	$('#CompraPedidoCustoOutros').priceFormat();
	
	//#TODO exibir valor total, considerando todos os custos e desconto
	function calculaValortotal () {

	}
	
	/****************************
	 * Ao submeter o formulario
	 ****************************/ 
	$('input[type="submit"]').click(function(){
		// aba produtos
		registro = 0;
		$('#produtos_incluidos tr').each(function() {
			registro++;
		});
		if (registro < 1) {
			alert('É necessário incluir ao menos um produto!');
			return false;
		}
		
	});
	
}); // fim document ready