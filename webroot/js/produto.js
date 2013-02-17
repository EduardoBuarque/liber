$(function(){
		
	function calcularPrecoVenda() {
		if ($('#ProdutoCalcularPreco').is(':checked')) {
			precoCusto = $('#ProdutoPrecoCusto');
			precoVenda = $('#ProdutoPrecoVenda');
			margemLucro = $('#ProdutoMargemLucro');
			precoCusto.final = moeda2numero(precoCusto.val());
			precoVenda.final = moeda2numero(precoVenda.val());
			margemLucro.final = moeda2numero(margemLucro.val());
			margemLucro.final = margemLucro.final.replace('%','');

			r = (margemLucro.final/100)*precoCusto.final;
			r = parseFloat(precoCusto.final) + parseFloat(r);	
			precoVenda.val( numero2moeda(r) );
		}
	}

	$('#ProdutoPrecoCusto').blur(function(){
		calcularPrecoVenda();
	});

	$('#ProdutoMargemLucro').blur(function(){
		calcularPrecoVenda();
	});
	
	$('#ProdutoPrecoCusto').priceFormat();
	$('#ProdutoPrecoVenda').priceFormat();
	$('#ProdutoMargemLucro').priceFormat();

});