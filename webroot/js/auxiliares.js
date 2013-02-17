jQuery(document).ready(function() {
	// ######################################
	//   Principais funções Jquery com ajax
	// ######################################
	
	// abre links com a classe "ajax_link_dialog" via ajax e jquery ui
	$('a.ajax_link_dialog').click(function() {
		url = this.href;
		largura = $(this).attr('data-ajax_link_dialog_largura');
		altura = $(this).attr('data-ajax_link_dialog_altura');
		titulo = $(this).attr('data-ajax_link_dialog_titulo');
		
		if (largura == undefined || largura == '') {
			largura = 'auto';
		}
		if (altura == undefined || altura == '') {
			altura = 'auto';
		}
		if (titulo == undefined || titulo == '') {
			titulo = 'Liber';
		}
		else {
			titulo = 'Liber - '+titulo;
		}
		
		abrir_dialogo_ajax(url,largura,altura,titulo);
		return false;
	});
	
	/*
	$('a[title="Excluir"]').click(function(evento) {
		evento.preventDefault();
		dialogoDeletar($(this));
		
	});
	*/
	
	$('a.ajax_link').click(function(objetoEvento) {
	//$('a').click(function(objetoEvento) {
		if ( $(this).attr('class') == 'nao-ajax' ) return true;
		var objetoLink = $(this);
		var areaConteudo = $('#conteudo');
		
		var requisicaoAjax = $.ajax({
			url: objetoLink.attr('href'),
			type: 'GET',
			cache: false,
			dataType: 'html'
		});
		requisicaoAjax
			.done(function(dados) {
				areaConteudo.html(dados);
				history.pushState(null, null, objetoLink.attr('href'));
				document.title = "Liber";
			})
			.fail(function(jqXHR, textStatus) {
				alert('Erro ao carregar a página.');
				var mensagemErro = '<div class="alert alert-error">\n\
									<button class="close" data-dismiss="alert" type="button">×</button>\n\
									<strong>Erro ao carregar a página, tente novamente.</strong> '+jqXHR.responseText+'\n\
								</div>';
				$('#flash').html(mensagemErro);
				$('#flash_ajax').html(mensagemErro);
			})
			/*.always(function(dados, statusTexto, objeto) {
				// a cada requisicao
			});*/
		// previne que o Browser siga o link
		return (false);
	});
	
	// ##########################
	//    Funcoes globais Ajax
	// ##########################
	
	/**
	 * Uma chave para definir se ha alguma operacao ajax em andamento.
	 */
	var ajax_carregando = false;
	
	/*
	 * Este evento é disparado se uma solicitação Ajax é iniciada e nenhuma
	 * outra solicitação Ajax está atualmente em execução.
	 */
	$('#conteudo').ajaxStart(function(){
		$('.indicador_carregando').css('display','block');
		ajax_carregando = true;
	});
	
	/*
	 * Este evento global é acionado se houverem solicitações Ajax não mais
	 * sendo processadas.
	 */
	$('#conteudo').ajaxStop(function(){
		$('.indicador_carregando').css('display','none');
		ajax_carregando = false;
	});	
	
	/*
	 * Este evento é chamado cada vez que uma solicitação Ajax termina, independentemente
	 * do pedido ser bem-sucedido ou não. É recebido sempre um retorno completo,
	 * mesmo para pedidos síncronos.
	 */
	$('#conteudo').ajaxComplete(function(evento, jqXHR, opcoes) {
		/*liber_log_ajax = $('#liber_log_ajax');
		if ( liber_log_ajax != null) {
			// move log para a area do layout gerado sem ajax
			liber_log = $('#liber_log');
			liber_log.html(liber_log_ajax.html());
			liber_log_ajax.html('');
			// cria logs ajax
			if( console ) {
				console.group('Link ajax');
				console.log('Status %d (%s). ReadyState: %d',jqXHR.status,jqXHR.statusText,jqXHR.readyState);
				console.log('Objeto: ',jqXHR);
				console.groupEnd();
			}
		//}
	*/});
	
	/*
	 * Este evento é chamado caso alguma requisição Ajax tenha algum erro.
	 */
	$('#conteudo').ajaxError(function(evento, jqxhr, opcoes, excecao) {
		// Acesso negado
		if ( jqxhr.status == 403 ) {
			var mensagemErro = '<div class="alert alert-error">\n\
								<button class="close" data-dismiss="alert" type="button">×</button>\n\
								<strong>Sessão expirada.</strong>\n\
								Faça <a href="'+site_raiz+'/usuarios/login/">login</a> novamente\n\
							</div>';
			$('#flash').html(mensagemErro);
			$('#flash_ajax').html(mensagemErro);
		}
	});
	
	// #########################
	//         Fim ajax
	// #########################
	
	
	/**
	 * Define se é necessário avisar ao usuário que ele está saindo da página.
	 */
	var avisar_saida_site = true;
	
	/**
	 * Aviso, genérico, ao usuário de que ele está saindo da página enquanto
	 * uma requisição ajax está sendo processada.
	 */
	window.onbeforeunload = function (e) {
		if (ajax_carregando && avisar_saida_site) {
			e = e || window.event;
			// Para IE<8 e Firefox < 4
			if (e) {
				e.returnValue = 'Você está deixando o sistema enquanto ações estão sendo processadas';
			}
			return 'Você está deixando o sistema enquanto ações estão sendo processadas';
		}
	}

}); // fim document.ready

