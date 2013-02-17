function submissaoFormulario(objeto) {
	// #FIXME nao funciona quando o formulario está sendo submetido via ajax
	var dialogo = $('<div title="Enviar"><p>Deseja enviar os dados?</p></div>');

	dialogo.dialog({
			resizable: false,
			autoOpen: true,
			modal: true,
			buttons: {
				'Sim': function() {
					$(this).dialog( "close" );
					document.forms[objeto.id].submit();
					$('#'+objeto.id).find('input:submit')
						.val('Enviando...')
						.attr("disabled", true);
				},
				'Não': function() {
					$(this).dialog( "close" );
				}
			}
		});
	// previne que o browser siga o link
	return false;
}

function dialogoDeletar(objeto) {
	// #FIXME não funciona, simular o click
	var $dialogo = $('<div title="Excluir" style="display:none"><p><span class="ui-icon ui-icon-alert"'+
	'style="float:left; margin:0 7px 20px 0;"></span>'+
	'Deseja excluir o registro?</p></div>');
	$dialogo.dialog({
			resizable: true,
			autoOpen: true,
			modal: true,
			buttons: {
				'Sim': function() {
					$(this).dialog( "close" );

					$('.'+c).trigger('click');
				},
				'Não': function() {
					$(this).dialog( "close" );
				}
			}
		});
	// previne que o browser siga o link
	return false;
}

/**
* formata uma $number com precisao de $decimals casas decimais
* utilizando $dec_point como separador decimal e $thousands_sep
* como separador de milhar
* @return $number formatado
*/
function number_format (number, decimals, dec_point, thousands_sep) {
	//http://phpjs.org/functions/number_format:481
	number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
	var n = !isFinite(+number) ? 0 : +number,
		prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
		sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
		dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
		s = '',
		toFixedFix = function (n, prec) {
			var k = Math.pow(10, prec);
			return '' + Math.round(n * k) / k;
		};
	// Fix for IE parseFloat(0.55).toFixed(0) = 0;
	s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
	if (s[0].length > 3) {
		s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
	}
	if ((s[1] || '').length < prec) {
		s[1] = s[1] || '';
		s[1] += new Array(prec - s[1].length + 1).join('0');
	}
	return s.join(dec);
}

/**
* Retorna x arredondado para n casas
* @param x variavel float
* @param n numero de casas decimais
*/
function arredonda_float(x,n){
	if ((n != null) && (n != '')) {
		if(!parseInt(n))
			var n=0;
	}
	else n = 2;
	if(!parseFloat(x))
		return x;
	return Math.round(x*Math.pow(10,n))/Math.pow(10,n);
}

/**
* converte uma string no formato 5.123,34 para um numero (int ou float)
* que possa ser utilizado em calculos
*/
function moeda2numero (variavel) {
	/*variavel = variavel.replace('.','');
	variavel = variavel.replace(',','.');
	variavel = parseFloat (variavel);
	return variavel;*/
	variavel = (variavel.replace(/\./g,'')).replace(/,/g,'.');
	return number_format(variavel,2,'.','');
}

/**
* converte uma variavel int ou float para a representação brasileira
* de moeda
*/
function numero2moeda (variavel) {
	/*if (variavel != null && variavel != '') {
		variavel = variavel + ''; //converte para string
		return variavel.replace('.',',');
	}*/
	return number_format(variavel,2,',','.');
}

function eh_numero (mixed_var) {
	// Returns true if value is a number or a numeric string  
	// 
	// version: 1109.2015
	// discuss at: http://phpjs.org/functions/is_numeric	// +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	// +   improved by: David
	// +   improved by: taith
	// +   bugfixed by: Tim de Koning
	// +   bugfixed by: WebDevHobo (http://webdevhobo.blogspot.com/)	// +   bugfixed by: Brett Zamir (http://brett-zamir.me)
	// *	 example 1: is_numeric(186.31);
	// *	 returns 1: true
	// *	 example 2: is_numeric('Kevin van Zonneveld');
	// *	 returns 2: false	// *	 example 3: is_numeric('+186.31e2');
	// *	 returns 3: true
	// *	 example 4: is_numeric('');
	// *	 returns 4: false
	// *	 example 4: is_numeric([]);	// *	 returns 4: false
	return (typeof(mixed_var) === 'number' || typeof(mixed_var) === 'string') && mixed_var !== '' && !isNaN(mixed_var);
}

/**
* Retorna true se variavel é numero inteiro,
* false caso contrario
*/
function eh_inteiro(variavel) {
	if((parseFloat(variavel) == parseInt(variavel)) && !isNaN(variavel)) {
		return true;
	} else {
		return false;
	}
}

function eh_float (mixed_var) {
	// Returns true if variable is float point  
	// 
	// version: 1109.2015
	// discuss at: http://phpjs.org/functions/is_float	// +   original by: Paulo Freitas
	// +   bugfixed by: Brett Zamir (http://brett-zamir.me)
	// +   improved by: WebDevHobo (http://webdevhobo.blogspot.com/)
	// +   improved by: Rafał Kukawski (http://blog.kukawski.pl)
	// %		note 1: 1.0 is simplified to 1 before it can be accessed by the function, this makes	// %		note 1: it different from the PHP implementation. We can't fix this unfortunately.
	// *	 example 1: is_float(186.31);
	// *	 returns 1: true
	return +mixed_var === mixed_var && !!(mixed_var % 1);
}

function popup(destino,largura,altura,titulo) {
	if (titulo == undefined || titulo == '') {
		titulo = "Liber";
	}
	window.open(destino,titulo,"width="+largura+",height="+altura);
}

// substitui o alert padrao do javascript, por este que utiliza
// jqueryui dialog
function alert (mensagem, titulo) {
	if (titulo == undefined || titulo == '') titulo = 'Liber';

	// previne que tenha mais de uma janela
	$( "#janela_alerta_unica:ui-dialog" ).dialog("destroy");

	var dialogo = $('<div id="janela_alerta_unica" title="'+titulo+'">\n\
					<p>'+mensagem+'</p>\n\
				</div>');

	dialogo.dialog({
			resizable: false,
			autoOpen: true,
			modal: true,
			buttons: {
			Ok: function() {
					$( this ).dialog( "close" );
				}
			}
		});
	// previne que o browser siga o link
	return false;
}

function abrir_dialogo_ajax (url, largura, altura, titulo) {
	if (titulo == undefined || titulo == "") {
		titulo = "Liber";
	}

	// exibe um icone de carregamento, via css
	var dialog = $('<div style="display:none" class="carregando"></div>').appendTo('body');
	// abre o dialog
	dialog.dialog({
		// previne multiplas divs no documento
		close: function(event, ui) {
			// remove a div com todos os dados e eventos
			dialog.remove();
		},
		modal: false,
		width: largura,
		height: altura,
		title: titulo
	});
	
	// carrega conteudo remoto
	dialog.load (
		url,
		{}, // omitir este parametro emiti um pedido GET em vez de um pedido POST, caso contrário, você pode fornecer parâmetros post dentro do objeto
		function (responseText, textStatus, XMLHttpRequest) {
			// remove a classe que exibe a imagem de carregando
			dialog.removeClass('carregando');
		}
	);
	// previne que o browser siga o link
	return false;
}

function formulario_cancelar($formulario) {
	if ($formulario == null || $formulario == '') $formulario = $('form')
	
	var dialogo = $('<div title="Cancelar"><p>Deseja cancelar o formulário ?</p></div>');
	dialogo.dialog({
			resizable: false,
			autoOpen: true,
			buttons: {
				'Sim': function() {
					$(this).dialog( "close" );
					$formulario.find('input:text, input:password, input:file, select, textarea').val('');
					$formulario.find('input:radio, input:checkbox')
					.removeAttr('checked').removeAttr('selected');
				},
				'Não': function() {
					$(this).dialog( "close" );
				}
			}
		});
	return false;
}