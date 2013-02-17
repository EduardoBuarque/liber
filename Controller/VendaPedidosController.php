<?php

//#TODO criar alerta caso o(s) pedido(s) totalize(m) um valor maior que o limite de credito  
//#TODO ao cancelar um pedido de venda a conta a receber nao é cancela. Cancelar?
class VendaPedidosController extends AppController {
	var $name = 'VendaPedidos';
	var $components = array ('Geral','ContasReceber');
	var $helpers = array('CakePtbr.Estados', 'Javascript','CakePtbr.Formatacao','Geral');
	var $paginate = array (
		'limit' => 10,
		'order' => array (
			'VendaPedido.id' => 'desc'
		)
	);
	
	/**
	 * Obtem dados necessarios ao decorrer deste controller.
	 * Os dados sao setados em variaveis a serem utilizadas nas views 
	 */
	function _obter_opcoes() {
		$this->VendaPedido->PagamentoTipo->recursive = -1;
		$opcoes_pagamento_tipo = $this->VendaPedido->PagamentoTipo->find('list',array('fields'=>array('PagamentoTipo.id','PagamentoTipo.nome')));
		$this->set('opcoes_forma_pamamento',$opcoes_pagamento_tipo);
		
		$this->VendaPedido->Empresa->recursive = -1;
		$opcoes_empresas = $this->VendaPedido->Empresa->findEmpresa();
		$this->set('opcoes_empresas',$opcoes_empresas);
		
		$opcoes_situacoes = array(
			'A'=>'Aberto',
			'O' => 'Orçamento',
			'C' => 'Cancelado',
			'V' => 'Vendido',
			'T' => 'Travado',
		);
		$this->set('opcoes_situacoes',$opcoes_situacoes);
		
		$this->loadModel('Usuario');
		$opcoes_vendedores = $this->Usuario->findVendedor();
		$this->set('opcoes_vendedores',$opcoes_vendedores);
		
		// view pesquisa
		$this->Usuario->recursive = -1;	
		$opcoes_usuarios = $this->Usuario->findAtivo();
		$this->set('opcoes_usuarios',$opcoes_usuarios);
	}
	
	/**
	* Recupero itens dinamicos que podem ter sido acrescentados a pagina
	*/
	function _recuperar_itens_dinamicos() {
		$data = $this->request->data;
		if (isset($data['VendaPedidoItem'])) {
			$itens = $data['VendaPedidoItem'];
			$i = 0;
			$campos_ja_inseridos = array();
			foreach ($itens as $item) {
                    $this->VendaPedido->VendaPedidoItem->Produto->recursive = -1;
				$n = $this->VendaPedido->VendaPedidoItem->Produto->findById($item['produto_id'],array('Produto.nome'));
				$campos_ja_inseridos[$i] = array('produto_id'=>$item['produto_id']);
				$campos_ja_inseridos[$i] += array('produto_nome'=>$n['Produto']['nome']);
				$campos_ja_inseridos[$i] += array('quantidade'=>$item['quantidade']);
				$campos_ja_inseridos[$i] += array('preco_venda'=>$item['preco_venda']);
				$i++;
			}
			$this->set('campos_ja_inseridos',$campos_ja_inseridos);
		}
		
		if (isset($data['VendaPedido']['cliente_id'])) {
			$this->VendaPedido->Cliente->recursive = -1;
			$clienteNome = $this->VendaPedido->Cliente->findById($this->request->data['VendaPedido']['cliente_id'],array('Cliente.nome'));
			$this->request->data['VendaPedido'] = array_merge($this->request->data['VendaPedido'], array('pesquisar_nome_cliente'=>$clienteNome['Cliente']['nome']));
		}
		
		return 1;
	}
	
	/**
	 * Calcula valor bruto e liquido do pedido de venda
	 * 
	 * @param $data Geralmente é o mesmo que $this->request->data
	 * @return array ('valor_bruto' => $valor_bruto, 'valor_liquido' => $valor_liquido)
	 */
	function _calcular_valores($data) {
		$valor_bruto = 0;
		$valor_liquido = 0;
		foreach ($data['VendaPedidoItem'] as $c) {
			$valor_bruto += ($this->Geral->moeda2numero($c['quantidade'])) * ($this->Geral->moeda2numero($c['preco_venda']));
		}
		// se ha outros custos, somo para obter o valor bruto
		if (isset($data['VendaPedido']['custo_frete']) && (! empty($data['VendaPedido']['custo_frete']))) {
			$valor_bruto = $valor_bruto + ($this->Geral->moeda2numero($data['VendaPedido']['custo_frete']));
		}
		if (isset($data['VendaPedido']['custo_seguro']) && (! empty($data['VendaPedido']['custo_seguro']))) {
			$valor_bruto = $valor_bruto + ($this->Geral->moeda2numero($data['VendaPedido']['custo_seguro']));
		}
		if (isset($data['VendaPedido']['custo_outros']) && (! empty($data['VendaPedido']['custo_outros']))) {
			$valor_bruto = $valor_bruto + ($this->Geral->moeda2numero($data['VendaPedido']['custo_outros']));
		}
		$valor_liquido = $valor_bruto;
		// se ha desconto, subtraio para obter o valor liquido
		if (isset($data['VendaPedido']['desconto']) && (! empty($data['VendaPedido']['desconto']))) {
			$valor_liquido = $valor_bruto - ($this->Geral->moeda2numero($data['VendaPedido']['desconto']));
		}
		
		$retorno = array(
			'valor_bruto' => $valor_bruto,
			'valor_liquido' => $valor_liquido
		);
		
		return $retorno;
	}
	
	/**
	 * Caso o pedido de venda esteja finalizado é gerada uma conta a receber
	 * utilizando o Componente ContasReceber
	 * @see Component ContasReceber
	 */
	function _gerar_conta_receber($valor_total=null) {
		// Apenas crio a forma de pagamento se a situacao do pedido for 'Vendido'
		if (strtoupper($this->request->data['VendaPedido']['situacao']) == 'V' ) {
			$this->loadModel('SistemaOpcao');
			$dados = array_merge(
				array('valor_total'=>$valor_total),
				array('numero_documento'=>$this->VendaPedido->id),
				array('conta_plano_id'=>$this->SistemaOpcao->field('item_conta_planos_venda_pedidos', array('id'=>1))),
				$this->request->data['VendaPedido']
				);
			return $this->ContasReceber->gerarContaReceber($dados);
			
		}
		return true;
	}
	
	/**
	 * Faz a baixa do estoque dos itens contidos em $this->request->data['VendaPedidoItem']
	 * 
	 * @return true e dados modificados no banco em caso de sucesso
	 * @return false em caso de falha
	 * @return null caso a baixa no estoque nao seja aplicavel a situacao
	 */
	function _baixar_estoque() {
		// apenas baixa o estoque se o pedido estiver Vendido
		if (strtoupper($this->request->data['VendaPedido']['situacao']) != 'V' ) return null;
		
		foreach ($this->request->data['VendaPedidoItem'] as $item) {
			$erro=0;
			$temEstoqueIlimitado = $this->VendaPedido->VendaPedidoItem->Produto->field('tem_estoque_ilimitado',array('Produto.id'=>$item['produto_id']));
			if ($temEstoqueIlimitado) continue;
		
			$quantidadeNaoFiscal = $this->VendaPedido->VendaPedidoItem->Produto->field('quantidade_estoque_nao_fiscal',array('Produto.id'=>$item['produto_id']));
			if (empty($quantidadeNaoFiscal)) {
				$this->Session->setFlash('Erro na baixa de estoque: não foi possível recuperar a quantidade do produto.','flash_erro');
				return false;
			}
			
			$quantidadeFiscal = $this->VendaPedido->VendaPedidoItem->Produto->field('quantidade_estoque_fiscal',array('Produto.id'=>$item['produto_id']));
			if (empty($quantidadeFiscal)) {
				$this->Session->setFlash('Erro na baixa de estoque: não foi possível recuperar a quantidade do produto.','flash_erro');
				return false;
			}
			
			$quantidadeReservada = $this->VendaPedido->VendaPedidoItem->Produto->field('quantidade_reservada',array('Produto.id'=>$item['produto_id']));
			if (empty($quantidadeReservada)) $quantidadeReservada = 0;
			
			$quantidadeDisponivel = $quantidadeNaoFiscal - $quantidadeReservada;
			if (! is_numeric($quantidadeDisponivel)) {
				$this->Session->setFlash('Erro na baixa de estoque: não foi possível determinar a quantidade reservada.','flash_erro');
				return false;
			}
			
			if ($this->Geral->moeda2numero($item['quantidade']) > $quantidadeDisponivel) {
				$this->Session->setFlash("O produto ${item['produto_id']} não está disponível na quantidade informada.",'flash_erro');
				$erro++;
			}
			else if ($erro == 0) {
				$dados = array (
					'Produto' => array (
						'quantidade_estoque_fiscal' => $quantidadeFiscal - $this->Geral->moeda2numero($item['quantidade']),
						'quantidade_estoque_nao_fiscal' => $quantidadeNaoFiscal - $this->Geral->moeda2numero($item['quantidade']),
					),
				);
				$this->VendaPedido->VendaPedidoItem->Produto->id = $item['produto_id'];
				if (! $this->VendaPedido->VendaPedidoItem->Produto->save($dados) ) {
					$this->Session->setFlash('Erro ao realizar baixa no estoque!','flash_erro');
					return false;
				}
			}
		}
		
		if ( (isset($erro) && ($erro>1)) ) return false;
		
	}
	
	function index() {
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		// Filtrando os dados solicitados na query
		$dados = $this->paginate = array(
		    'contain' => array('Cliente.nome','PagamentoTipo.nome'),
		    'order' => 'VendaPedido.id DESC',
		    'limit' => 10,
		);
		$dados = $this->paginate('VendaPedido');
		$this->set('consulta',$dados);
		$this->_obter_opcoes();
	}
	
	function cadastrar() {
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		$this->set("title_for_layout","Pedido de venda"); 
		$this->_obter_opcoes();
		if (! empty($this->request->data)) {
			$this->_recuperar_itens_dinamicos();
			$this->VendaPedido->Cliente->recursive = -1;
			$r = $this->VendaPedido->Cliente->find('first',
				array('conditions'=>array(
					'Cliente.id' => $this->request->data['VendaPedido']['cliente_id'],
					'Cliente.situacao' => 'A')));
			if (empty($r)) {
				$this->Session->setFlash('Cliente não existe ou não está ativo.','flash_erro');
				return null;
			}
			$this->request->data['VendaPedido'] += array ('data_hora_cadastrado' => date('Y-m-d H:i:s'));
			$this->request->data['VendaPedido'] += array ('usuario_cadastrou' => $this->Auth->user('id'));
			$valores = $this->_calcular_valores($this->request->data);
			$valor_bruto = $valores['valor_bruto'];
			$valor_liquido = $valores['valor_liquido'];
			if ($valor_liquido <= 0) {
				$this->Session->setFlash('O valor total do pedido é R$ '.$this->Geral->numero2moeda($valor_liquido),'flash_erro');
				return null;
			}
			$this->request->data['VendaPedido'] += array ('valor_bruto' => $valor_bruto);
			$this->request->data['VendaPedido'] += array ('valor_liquido' => $valor_liquido);
			
			// evito que o conteudo seja preenchido com 0000-00-00
			if (empty($this->request->data['VendaPedido']['data_saida'])) $this->request->data['VendaPedido']['data_saida'] = null;
			if (empty($this->request->data['VendaPedido']['data_entrega'])) $this->request->data['VendaPedido']['data_entrega'] = null;
			if (empty($this->request->data['VendaPedido']['data_venda'])) $this->request->data['VendaPedido']['data_venda'] = null;
			
			//Inicia uma transaction
			$this->VendaPedido->begin();
			
			if ($this->VendaPedido->saveAll($this->request->data,array('validate'=>'first'))) {
				if ( $this->_gerar_conta_receber($valor_liquido) !== true ) {
					$this->VendaPedido->rollback();
				}
				else if ($this->_baixar_estoque() === false) {
					$this->VendaPedido->rollback();
				}
				else {
					$this->VendaPedido->commit();
					$this->Session->setFlash("Pedido de venda, código {$this->VendaPedido->id}, cadastrado com sucesso.<br/>"
					."<a href='#' onclick=popup('cupomNaoFiscal/{$this->VendaPedido->id}','300','300') > Imprimir cupom não fiscal</a>"."
					",'flash_sucesso');
					$this->redirect($this->referer(array('action' => 'index')));
				}
			}
			else {
				$this->Session->setFlash('Erro ao cadastrar o pedido de venda.','flash_erro');
				$this->VendaPedido->rollback();
			}
		}
	}
	
	function editar($id=NULL) {
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		$this->set("title_for_layout","Pedido de venda"); 
		$this->_obter_opcoes();
		if (empty ($this->request->data)) {
			$this->VendaPedido->id = $id;
			$this->VendaPedido->contain('VendaPedidoItem');
			$this->request->data = $this->VendaPedido->read();
			if ( ! $this->request->data) {
				$this->Session->setFlash('Pedido de venda não encontrado.','flash_erro');
				$this->redirect(array('action'=>'index'));
			}
			else {
				$this->_recuperar_itens_dinamicos();
				
				if ($this->request->data['VendaPedido']['data_saida'] == '0000-00-00') $this->request->data['VendaPedido']['data_saida'] = null;
				else $this->request->data['VendaPedido']['data_saida'] = date('d/m/Y', strtotime($this->request->data['VendaPedido']['data_saida']));
				
				if ($this->request->data['VendaPedido']['data_entrega'] == '0000-00-00') $this->request->data['VendaPedido']['data_entrega'] = null;
				else $this->request->data['VendaPedido']['data_entrega'] = date('d/m/Y', strtotime($this->request->data['VendaPedido']['data_entrega']));
				
				if ($this->request->data['VendaPedido']['data_venda'] == '0000-00-00') $this->request->data['VendaPedido']['data_venda'] = null;
				else $this->request->data['VendaPedido']['data_venda'] = date('d/m/Y', strtotime($this->request->data['VendaPedido']['data_venda']));
			}
		}
		else {
			$this->_recuperar_itens_dinamicos();
			$this->VendaPedido->Cliente->recursive = -1;
			$r = $this->VendaPedido->Cliente->find('first',
				array('conditions'=>array(
					'Cliente.id' => $this->request->data['VendaPedido']['cliente_id'],
					'Cliente.situacao' => 'A')));
			if (empty($r)) {
				$this->Session->setFlash('Erro. Cliente não existe ou não está ativo.','flash_erro');
				return null;
			}
			//o pedido de venda pode ser editado apenas se nao tiver sido cancelado ou vendido
			$s = strtoupper($this->VendaPedido->field('situacao'));
			if ( ($s == 'V') || ($s == 'C') || ($s == 'T') ) {
				// o usuario pode cancelar um pedido de venda na situacao 'Vendido'
				if (strtoupper($this->request->data['VendaPedido']['situacao']) != 'C') {
					$this->Session->setFlash('A situação deste pedido de venda impede que seja editado','flash_erro');
					return null;
				}
			}
			$this->request->data['VendaPedido']['id'] = $id;
			$this->request->data['VendaPedido'] += array ('usuario_alterou' => $this->Auth->user('id'));
			$valores = $this->_calcular_valores($this->request->data);
			$valor_bruto = $valores['valor_bruto'];
			$valor_liquido = $valores['valor_liquido'];
			if ($valor_liquido <= 0) {
				$this->Session->setFlash('Erro. O valor total do pedido é R$ '.$this->Geral->numero2moeda($valor_liquido),'flash_erro');
				return null;
			}
			$this->request->data['VendaPedido'] += array ('valor_bruto' => $valor_bruto);
			$this->request->data['VendaPedido'] += array ('valor_liquido' => $valor_liquido);
			
			// evito que o conteudo seja preenchido com 0000-00-00
			if (empty($this->request->data['VendaPedido']['data_saida'])) $this->request->data['VendaPedido']['data_saida'] = null;
			if (empty($this->request->data['VendaPedido']['data_entrega'])) $this->request->data['VendaPedido']['data_entrega'] = null;
			if (empty($this->request->data['VendaPedido']['data_venda'])) $this->request->data['VendaPedido']['data_venda'] = null;
			
			//Inicia uma transaction
			$this->VendaPedido->begin();
			
			// #TODO seria bom nao deletar e reinserir todos os registros
			// deleto os itens que pertenciam a pedido de venda
			if( ! ($this->VendaPedido->VendaPedidoItem->deleteAll(array('venda_pedido_id'=>$id),false))) {
				$this->Session->setFlash('Erro ao atualizar o pedido de venda','flash_erro');
				$this->VendaPedido->rollback();
				return null;
			}

			// insiro o que foi enviado agora, inclusive os itens
			if ($this->VendaPedido->saveAll($this->request->data,array('validate'=>'first'))) {
				if ($this->_gerar_conta_receber($valor_liquido) !== true ) {
					$this->VendaPedido->rollback();
				}
				else if ($this->_baixar_estoque() === false) {
					$this->VendaPedido->rollback();
				}
				else {
					$this->VendaPedido->commit();
					$this->Session->setFlash("Pedido de venda alterado com sucesso.<br/>"
					."<a href='#' onclick=popup(site_raiz+'/vendaPedidos/cupomNaoFiscal/{$this->VendaPedido->id}','300','300') > Imprimir cupom não fiscal</a>"."
					",'flash_sucesso');
					$this->redirect(array('action'=>'index'));
				}
			}
			else {
				$this->Session->setFlash('Erro ao editar o pedido de venda.','flash_erro');
				$this->VendaPedido->rollback();
			}
			
		}
	}
	
	function detalhar($id = null) {
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		$this->set("title_for_layout","Pedido de venda");
		$this->VendaPedido->contain('VendaPedidoItem','Cliente.nome','PagamentoTipo.nome');
		$consulta = $this->VendaPedido->findById($id);
		if (empty($consulta)) {
			$this->Session->setFlash('Pedido de venda não encontrado','flash_erro');
			$this->redirect(array('action'=>'index'));
		}
		else {
			// adiciono, no array resultante, o nome do produto correspondente
			$i = 0;
			$this->loadModel('Produto');
			foreach ($consulta['VendaPedidoItem'] as $x) {
				$nome = $this->Produto->field('nome',array('Produto.id'=>$x['produto_id']));
				$consulta['VendaPedidoItem'][$i]['produto_nome'] = $nome;
				$i++;
			}
			$this->set('c',$consulta);
			$this->_obter_opcoes();
		}
	}

	function excluir($id=NULL) {
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		if (! empty($id)) {
			$this->VendaPedido->id = $id;
			$this->VendaPedido->recursive = -1;
			$r = $this->VendaPedido->field('situacao');
			if (empty ($r)) {
				$this->Session->setFlash('Pedido de venda não encontrado','flash_erro');
				$this->redirect(array('action'=>'pesquisar'));
				return false;
			}
			//Uma pedido de venda apenas pode ser deletado se sua situacao for 'Orçamento' ou 'Aberto'
			$r = strtoupper($r);
			if ( ($r != 'O') && ($r != 'A') ) {
				$this->Session->setFlash("A situação do pedido de venda ${id} impede a sua exclusão. Talvez você deva apenas cancelá-lo",'flash_erro');
				$this->redirect(array('action'=>'index'));
				return false;
			}
			if ($this->VendaPedido->VendaPedidoItem->deleteAll(array('VendaPedidoItem.venda_pedido_id'=>$id))) {
				if ($this->VendaPedido->delete($id)) {
					$this->Session->setFlash("Pedido de venda número $id foi excluído com sucesso.",'flash_sucesso');
					$this->redirect(array('action'=>'index'));
				}
				else $this->Session->setFlash("Pedido de venda $id não pode ser excluído",'flassh_erro');
			}
			else $this->Session->setFlash("Pedido de venda número $id não pode ser excluído.",'flash_erro');
			$this->redirect(array('action'=>'pesquisar'));
		}
		else {
			$this->Session->setFlash('Pedido de venda não informado.','flash_erro');
			$this->redirect(array('action'=>'pesquisar'));
		}
	}
	
	function pesquisar() {
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		$this->set("title_for_layout","Pedido de venda");
		$this->_obter_opcoes();
		if (! empty($this->request->data)) {
			//usuario enviou os dados da pesquisa
			$url = array('controller'=>'VendaPedidos','action'=>'pesquisar');
			//trocando as barras dos campos de data, pois estes parametros, caso existam, vao para a url
			if (!empty($this->request->data['VendaPedido']['data_hora_cadastrado'])) $this->request->data['VendaPedido']['data_hora_cadastrado'] = preg_replace('/\//', '-', $this->request->data['VendaPedido']['data_hora_cadastrado']);
			// codificando os parametros
			if( is_array($this->request->data['VendaPedido']) ) {
				foreach($this->request->data['VendaPedido'] as $chave => &$item) {
					if (empty($item)) {
						unset($this->request->data['VendaPedido'][$chave]);
						continue;
					}
					// urlencode duas vezes para nao haver problema com / e \
					$item = htmlentities(urlencode(urlencode($item)));
				}
			}
			$params = array_merge($url,$this->request->data['VendaPedido']);
			$this->redirect($params);
		}
		
		if (! empty($this->request->params['named'])) {
			//a instrucao acima redirecionou para cá
			foreach ($this->request->params['named'] as &$valor) {
				$valor = html_entity_decode(urldecode(urldecode($valor)));
			}
			$dados = $this->request->params['named'];
			$condicoes=array();
			if (! empty($dados['id'])) $condicoes = array_merge($condicoes,array('VendaPedido.id'=>$dados['id']));
			if (! empty($dados['cliente_id'])) $condicoes = array_merge($condicoes, array('VendaPedido.cliente_id'=>$dados['cliente_id']));
			if (! empty($dados['cliente_nome'])) $condicoes = array_merge($condicoes,array('Cliente.nome LIKE'=>'%'.$dados['cliente_nome'].'%'));
			if (! empty($dados['situacao'])) $condicoes = array_merge($condicoes,array('VendaPedido.situacao'=>$dados['situacao']));
			if (! empty($dados['valor_total'])) $condicoes = array_merge($condicoes,array('VendaPedido.valor_liquido'=>$dados['valor_total']));
			if (! empty($dados['usuario_cadastrou'])) $condicoes = array_merge($condicoes,array('VendaPedido.usuario_cadastrou'=>$dados['usuario_cadastrou']));
			// pesquiso todos os registros cadastrados entre o intervalo do dia informado pelo usuario
			if (! empty($dados['data_hora_cadastrado'])) {
				$data_hora_cadastrado = explode('-',$dados['data_hora_cadastrado']);
				$data_hora_cadastrado = $data_hora_cadastrado[2].'-'.$data_hora_cadastrado[1].'-'.$data_hora_cadastrado[0];
				$condicoes[] = array('VendaPedido.data_hora_cadastrado BETWEEN ? AND ?'=>array($data_hora_cadastrado.' 00:00:00',$data_hora_cadastrado.' 23:59:59'));
			}
			
			if (! empty ($condicoes)) {
				$this->paginate = array(
				    'limit' => 10,
				    'order' => 'VendaPedido.id ASC',
				    'contain' => array ('Cliente.nome'),
				);
				$resultados = $this->paginate('VendaPedido',$condicoes);
				if (! empty($resultados)) {
					$num_encontrados = count($resultados);
					$this->set('resultados',$resultados);
					$this->set('num_resultados',$num_encontrados);
					$this->Session->setFlash("Exibindo $num_encontrados pedido(s) de venda",'flash_sucesso');
				}
				else $this->Session->setFlash("Nenhum pedido de venda encontrado",'flash_erro');
			}
			else {
				$this->set('num_resultados','0');
				$this->Session->setFlash("Informe algum campo para realizar a pesquisa",'flash_erro');
			}
		}
	}

	/**
	 * Gera um simples cupom de venda, que não tem valor fiscal.
	 * @param $id do pedido de venda
	 */
	function cupomNaoFiscal ($id = null) {
		$this->layout = 'limpo';
		$this->set("title_for_layout","Cupom não fiscal (venda $id)");
		
		$this->VendaPedido->id = $id;
		$this->VendaPedido->contain('Empresa','Cliente.nome','PagamentoTipo.nome','VendaPedidoItem');
		$venda = $this->VendaPedido->read();
		$i=0;
		foreach ($venda['VendaPedidoItem'] as $item) {
			$this->VendaPedido->VendaPedidoItem->Produto->id = $item['produto_id'];
			$produto_nome = $this->VendaPedido->VendaPedidoItem->Produto->field('nome');
			$venda['VendaPedidoItem'][$i] = array_merge( $venda['VendaPedidoItem'][$i], array('produto_nome'=>$produto_nome));
			$i++;
		}
		$this->set('venda',$venda);
	}
	
}

?>