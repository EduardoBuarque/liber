jQuery(document).ready(function() {
	
	selectTipoPessoa = $('#ClienteTipoPessoa');
	inputCnpj = $('#ClienteCnpj');
	inputIe = $('#ClienteInscricaoEstadual');
	inputCpf = $('#ClienteCpf');
	inputRg = $('#ClienteRg');
	inputNome = $('#ClienteNome');
	inputNomeFantasia = $('#ClienteNomeFantasia');
	divCnpj = inputCnpj.parent('div').parent('div');
	divIe = inputIe.parent('div').parent('div');
	divCpf = inputCpf.parent('div').parent('div');
	divRg = inputRg.parent('div').parent('div');
	
	inputNome.focusout(function() {
		if (inputNomeFantasia.val() == '') {
			inputNomeFantasia.val(inputNome.val());
		}
	});
	
	divIe.removeClass('required');
	divCnpj.removeClass('required');
	divRg.removeClass('required');
	divCpf.removeClass('required');
	
	//Para situações onde o formulario será carregado já populado
	if (selectTipoPessoa.val() == 'F' ) {
		inputCpf.removeAttr('disabled');
		divCpf.addClass('required');
		inputRg.removeAttr('disabled');
		divRg.addClass('required');
	}
	else if (selectTipoPessoa.val() == 'J' ) {
		inputCnpj.removeAttr('disabled');
		divCnpj.addClass('required');
		inputIe.removeAttr('disabled')
		divIe.addClass('required');
	}

	//Ao ser setado, manualmente, o tipo do cliente
	selectTipoPessoa.change(function(){
		if ($(this).val() == 'F' ) {
			inputCnpj
				.val("")
				.attr("disabled", "disabled");
			divCnpj.removeClass('required');
			inputIe
				.val("")
				.attr("disabled", "disabled")
			divIe.removeClass('required');

			inputCpf
				.removeAttr('disabled')
				.effect("highlight", {}, 3000);
			divCpf.addClass('required');
			inputRg
				.removeAttr('disabled')
				.effect("highlight", {}, 3000);
			divRg.addClass('required');
		}
		else if ($(this).val() == 'J' ) {
			inputCpf
				.val("")
				.attr("disabled", "disabled");
			divCpf.removeClass('required');
			inputRg
				.val("")
				.attr("disabled", "disabled");
			divRg.removeClass('required');

			inputCnpj
				.removeAttr('disabled')
				.effect("highlight", {}, 3000);
			divCnpj.addClass('required');
			inputIe
				.removeAttr('disabled')
				.effect("highlight", {}, 3000);
			divIe.addClass('required');
		}
	});

	/*$('input[value="Gravar"]').click(function () {
		if (selectTipoPessoa.val() == 'J' ){
			if ( inputCnpj.val() == "")  {
				alert ("Para pessoa jurídica o campo CNPJ é obrigatório.");
				return false;
			}
		}
		else if (selectTipoPessoa.val() == 'F' ) {
			if ( (inputCpf.val() == "") || ( inputRg.val() == "") ) {
				alert ("Para pessoa física os campos CPF e RG são obrigatórios.");
				return false;
			} 
		}

	});*/
	
}); // fim document ready