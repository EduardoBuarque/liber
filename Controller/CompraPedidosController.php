<?php

class CompraPedidosController extends AppController {
	var $name = 'CompraPedidos';
	var $components = array('Geral');
	var $helpers = array('CakePtbr.Estados', 'Javascript','CakePtbr.Formatacao','Geral');
	var $paginate = array (
		'limit' => 10,
		'order' => array (
			'CompraPedido.id' => 'desc'
		)
	);
	
	function _recuperar_itens_dinamicos() {
		$data = &$this->request->data;
		// fornecedor
		if ( (isset($data['CompraPedido']['fornecedor_id'])) && (! empty($data['CompraPedido']['fornecedor_id'])) ) {
			$consulta = $this->CompraPedido->Fornecedor->field('nome');
			$data['CompraPedido']['pesquisar_nome_fornecedor'] = $consulta;
		}
		// operacoes nos produtos
		if ( (isset($data['CompraPedidoItem'])) && (! empty($data['CompraPedidoItem'])) ) {
			$produtoNumero = 0;
			foreach ($data['CompraPedidoItem'] as $item) {
				$consulta = $this->CompraPedido->CompraPedidoItem->Produto->findAtivo('first',array('fields'=>array('Produto.nome'),'conditions'=>array('Produto.id'=>$item['produto_id'])));
				$data['CompraPedidoItem'][$produtoNumero]['produto_nome'] = $consulta['Produto']['nome'];
				$produtoNumero++;
			}
		}
	}
	
	/**
	 * Obtem dados necessarios ao decorrer deste controller.
	 * Os dados sao setados em variaveis a serem utilizadas nas views 
	 */
	function _obter_opcoes() {
		$this->CompraPedido->PagamentoTipo->recursive = -1;
		$opcoes_pagamento_tipo = $this->CompraPedido->PagamentoTipo->find('list',array('fields'=>array('PagamentoTipo.id','PagamentoTipo.nome')));
		$this->set('opcoes_forma_pamamento',$opcoes_pagamento_tipo);
		
		$opcoes_situacoes = array(
			'A'=>'Aberto',
			'C' => 'Cancelado',
			'I' => 'Incorporado ao estoque'
		);
		$this->set('opcoes_situacoes',$opcoes_situacoes);
		
		$opcoes_compradores = $this->CompraPedido->Usuario->findComprador();
		$this->set('opcoes_compradores',$opcoes_compradores);
		
		// view pesquisa
		$opcoes_usuarios = $this->CompraPedido->Usuario->findAtivo();
		$this->set('opcoes_usuarios',$opcoes_usuarios);
	}
	
	/**
	 * Calcula valor bruto e liquido do pedido de compra
	 * 
	 * @return array ('valor_bruto' => $valor_bruto, 'valor_liquido' => $valor_liquido)
	 */
	function _calcular_valores() {
		$data = &$this->request->data;
		$valor_bruto = 0;
		$valor_liquido = 0;
		foreach ($data['CompraPedidoItem'] as $c) {
			$valor_bruto += ($this->Geral->moeda2numero($c['quantidade'])) * ($this->Geral->moeda2numero($c['preco_compra']));
		}
		// se ha outros custos, somo para obter o valor bruto
		if (isset($data['CompraPedido']['custo_frete']) && (! empty($data['CompraPedido']['custo_frete']))) {
			$valor_bruto = $valor_bruto + ($this->Geral->moeda2numero($data['CompraPedido']['custo_frete']));
		}
		if (isset($data['CompraPedido']['custo_seguro']) && (! empty($data['CompraPedido']['custo_seguro']))) {
			$valor_bruto = $valor_bruto + ($this->Geral->moeda2numero($data['CompraPedido']['custo_seguro']));
		}
		if (isset($data['CompraPedido']['custo_outros']) && (! empty($data['CompraPedido']['custo_outros']))) {
			$valor_bruto = $valor_bruto + ($this->Geral->moeda2numero($data['CompraPedido']['custo_outros']));
		}
		$valor_liquido = $valor_bruto;
		// se ha desconto, subtraio para obter o valor liquido
		if (isset($data['CompraPedido']['desconto']) && (! empty($data['CompraPedido']['desconto']))) {
			$valor_liquido = $valor_bruto - ($this->Geral->moeda2numero($data['CompraPedido']['desconto']));
		}
		
		$retorno = array(
			'valor_bruto' => $valor_bruto,
			'valor_liquido' => $valor_liquido
		);
		
		return $retorno;
	}
	
	function index() {
		if ( $this->RequestHandler->isAjax() ) $this->layout = 'ajax';
		$this->_obter_opcoes();
		$dados = $this->paginate = array(
		    'contain' => array('Fornecedor.nome','PagamentoTipo.nome'),
		    'order' => 'CompraPedido.id DESC',
		    'limit' => 10,
		);
		$this->set('consulta',$this->paginate('CompraPedido'));
	}
	
	function cadastrar() {
		if ( $this->RequestHandler->isAjax() ) $this->layout = 'ajax';
		$this->set("title_for_layout","Pedido de compra"); 
		$this->_obter_opcoes();
		if (! empty($this->request->data)) {
			$this->_recuperar_itens_dinamicos();
			
			$r = $this->CompraPedido->Fornecedor->findAtivo('count',array('conditions'=>array('Fornecedor.id' => $this->request->data['CompraPedido']['fornecedor_id'])));
			if (empty($r)) {
				$this->Session->setFlash('Fornecedor não existe ou não está ativo.','flash_erro');
				return null;
			}
			$this->request->data['CompraPedido'] += array ('data_hora_cadastrado' => date('Y-m-d H:i:s'));
			$this->request->data['CompraPedido'] += array ('usuario_cadastrou' => $this->Auth->user('id'));
			$this->request->data['CompraPedido'] += array ('situacao' => 'A');
			$valores = $this->_calcular_valores();
			$valor_bruto = $valores['valor_bruto'];
			$valor_liquido = $valores['valor_liquido'];
			if ($valor_liquido <= 0) {
				$this->Session->setFlash('O valor total do pedido é R$ '.$this->Geral->numero2moeda($valor_liquido),'flash_erro');
				return null;
			}
			$this->request->data['CompraPedido'] += array ('valor_bruto' => $valor_bruto);
			$this->request->data['CompraPedido'] += array ('valor_liquido' => $valor_liquido);
			
			//Inicia uma transaction
			$this->CompraPedido->begin();
			if ($this->CompraPedido->saveAll($this->request->data,array('validate'=>'first'))) {
				$this->CompraPedido->commit();
				$this->Session->setFlash("Pedido de compra número {$this->CompraPedido->id} cadastrado com sucesso.",'flash_sucesso');
				if ( ! $this->RequestHandler->isAjax() ) $this->redirect($this->referer(array('action' => 'index')));
			}
			else {
				$this->Session->setFlash('Erro ao cadastrar o pedido de compra.','flash_erro');
				$this->CompraPedido->rollback();
			}
		}
	}
	
	function editar($id=NULL) {
		if ( $this->RequestHandler->isAjax() ) $this->layout = 'ajax';
		$this->set("title_for_layout","Pedido de compra"); 
		$this->CompraPedido->id = $id;
		$this->_obter_opcoes();
		
		if (empty ($this->request->data)) {
			$this->CompraPedido->contain('CompraPedidoItem');
			// array data é sobrescrito
			if ( ! ($this->request->data = $this->CompraPedido->read()) ) {
				$this->Session->setFlash('Pedido de compra não encontrado.','flash_erro');
				return null;
			}
			// para redefinir o id do pedido de compra, pois o array data foi sobrescrito
			$this->request->data['CompraPedido'] += array ('id' => $id);
			$this->_recuperar_itens_dinamicos();
		}
		else {
			// para redefinir o id do pedido de compra, pois o array data foi sobrescrito
			$this->request->data['CompraPedido'] += array ('id' => $id);
			//$this->_recuperar_itens_dinamicos();
			$r = $this->CompraPedido->Fornecedor->findAtivo('count',array('conditions'=>array('Fornecedor.id' => $this->request->data['CompraPedido']['fornecedor_id'])));
			if (empty($r)) {
				$this->Session->setFlash('Fornecedor não existe ou não está ativo.','flash_erro');
				return null;
			}
			//o pedido de compra pode ser editado apenas se nao tiver sido cancelado ou incorporado ao estoque
			$s = strtoupper($this->CompraPedido->field('situacao'));
			if ( ($s == 'C') || ($s == 'I') ) {
				// #TODO o usuario pode cancelar um pedido de compra na situacao 'incorporado ao estoque'
				// desde que o estoque nao fique negativo
				if (strtoupper($this->request->data['CompraPedido']['situacao']) != 'C') {
					$this->Session->setFlash('A situação deste pedido de compra impede que seja editado','flash_erro');
					return null;
				}
			}
			$this->request->data['CompraPedido'] += array ('usuario_alterou' => $this->Auth->user('id'));
			$valores = $this->_calcular_valores();
			$valor_bruto = $valores['valor_bruto'];
			$valor_liquido = $valores['valor_liquido'];
			if ($valor_liquido <= 0) {
				$this->Session->setFlash('Erro. O valor total do pedido é R$ '.$this->Geral->numero2moeda($valor_liquido),'flash_erro');
				return null;
			}
			$this->request->data['CompraPedido'] += array ('valor_bruto' => $valor_bruto);
			$this->request->data['CompraPedido'] += array ('valor_liquido' => $valor_liquido);
			
			// evito que o conteudo seja preenchido com 0000-00-00
			if (empty($this->request->data['CompraPedido']['data_saida'])) $this->request->data['CompraPedido']['data_saida'] = null;
			if (empty($this->request->data['CompraPedido']['data_entrega'])) $this->request->data['CompraPedido']['data_entrega'] = null;
			if (empty($this->request->data['CompraPedido']['data_compra'])) $this->request->data['CompraPedido']['data_compra'] = null;
			
			//Inicia uma transaction
			$this->CompraPedido->begin();
			
			// deleto os itens que pertenciam a pedido de compra
			if( ! ($this->CompraPedido->CompraPedidoItem->deleteAll(array('compra_pedido_id'=>$id),false))) {
				$this->Session->setFlash('Erro ao atualizar o pedido de compra','flash_erro');
				$this->CompraPedido->rollback();
				return null;
			}

			// insiro o que foi enviado agora, inclusive os itens
			if ($this->CompraPedido->saveAll($this->request->data,array('validate'=>'first'))) {
				$this->CompraPedido->commit();
				$this->Session->setFlash("Pedido de compra alterado com sucesso.",'flash_sucesso');
				if ( ! $this->RequestHandler->isAjax() ) $this->redirect(array('action'=>'index'));
			}
			else {
				$this->Session->setFlash('Erro ao editar o pedido de compra.','flash_erro');
				$this->CompraPedido->rollback();
			}
		}
	}
	
	function excluir($id=NULL) {
		if ( $this->RequestHandler->isAjax() ) $this->layout = 'ajax';
		if (empty($id)) {
			$this->Session->setFlash('Pedido de compra não informado.','flash_erro');
			if ( ! $this->RequestHandler->isAjax() ) $this->redirect(array('action'=>'index'));
		}
		$this->CompraPedido->id = $id;
		$situacao = $this->CompraPedido->field('situacao');
		if (empty ($situacao)) {
			$this->Session->setFlash('Pedido de compra não encontrado','flash_erro');
			if ( ! $this->RequestHandler->isAjax() ) $this->redirect(array('action'=>'index'));
			return false;
		}
		// #TODO Um pedido de compra apenas pode ser deletado se o estoque nao ficar negativo
		$situacao = strtoupper($situacao);
		if ( ($situacao != 'A') ) {
			$this->Session->setFlash("A situação do pedido de compra ${id} impede a sua exclusão. Talvez você deva apenas cancelá-lo",'flash_erro');
			if ( ! $this->RequestHandler->isAjax() ) $this->redirect(array('action'=>'index'));
			return false;
		}
		$this->CompraPedido->begin();
		if ($this->CompraPedido->CompraPedidoItem->deleteAll(array('CompraPedidoItem.compra_pedido_id'=>$id))) {
			if ($this->CompraPedido->delete($id)) {
				$this->Session->setFlash("Pedido de compra número $id foi excluído com sucesso.",'flash_sucesso');
				$this->CompraPedido->commit();
				if ( ! $this->RequestHandler->isAjax() ) $this->redirect(array('action'=>'index'));
			}
			else {
				$this->Session->setFlash("Pedido de compra $id não pode ser excluído",'flassh_erro');
				$this->CompraPedido->rollback();
			}
		}
		else {
			$this->Session->setFlash("Pedido de compra número $id não pode ter seus itens excluídos.",'flash_erro');
			$this->CompraPedido->rollback();
		}
	}
	
	function detalhar($id = null) {
		$this->_obter_opcoes();
		if ( $this->RequestHandler->isAjax() ) $this->layout = 'ajax';
		$this->set("title_for_layout","Pedido de compra");
		$this->CompraPedido->contain('CompraPedidoItem','Fornecedor.nome','PagamentoTipo.nome');
		$this->CompraPedido->id = $id;
		$this->request->data = $this->CompraPedido->read();
		if ( ! $this->request->data ) {
			$this->Session->setFlash('Pedido de compra não encontrado','flash_erro');
			return false;
		}
		$this->_recuperar_itens_dinamicos();
	}
	
	function pesquisar() {
		if ( $this->RequestHandler->isAjax() ) $this->layout = 'ajax';
		$this->set("title_for_layout","Pedido de compra");
		$this->_obter_opcoes();
		if (! empty($this->request->data)) {
			//usuario enviou os dados da pesquisa
			$url = array('controller'=>'CompraPedidos','action'=>'pesquisar');
			//trocando as barras dos campos de data, pois estes parametros, caso existam, vao para a url
			if (!empty($this->request->data['CompraPedido']['data_hora_cadastrado'])) $this->request->data['CompraPedido']['data_hora_cadastrado'] = preg_replace('/\//', '-', $this->request->data['CompraPedido']['data_hora_cadastrado']);
			// codificando os parametros
			if( is_array($this->request->data['CompraPedido']) ) {
				foreach($this->request->data['CompraPedido'] as $chave => &$item) {
					if (empty($item)) {
						unset($this->request->data['CompraPedido'][$chave]);
						continue;
					}
					// urlencode duas vezes para nao haver problema com / e \
					$item = htmlentities(urlencode(urlencode($item)));
				}
			}
			$params = array_merge($url,$this->request->data['CompraPedido']);
			$this->redirect($params);
		}
		
		if (! empty($this->request->params['named'])) {
			//a instrucao acima redirecionou para cá
			foreach ($this->request->params['named'] as &$valor) {
				$valor = html_entity_decode(urldecode(urldecode($valor)));
			}
			$dados = $this->request->params['named'];
			$condicoes=array();
			if (! empty($dados['id'])) $condicoes = array_merge($condicoes,array('CompraPedido.id'=>$dados['id']));
			if (! empty($dados['fornecedor_id'])) $condicoes = array_merge($condicoes, array('CompraPedido.fornecedor_id'=>$dados['fornecedor_id']));
			if (! empty($dados['fornecedor_nome'])) $condicoes = array_merge($condicoes,array('Fornecedor.nome LIKE'=>'%'.$dados['fornecedor_nome'].'%'));
			if (! empty($dados['situacao'])) $condicoes = array_merge($condicoes,array('CompraPedido.situacao'=>$dados['situacao']));
			if (! empty($dados['valor_total'])) $condicoes = array_merge($condicoes,array('CompraPedido.valor_liquido'=>$dados['valor_total']));
			if (! empty($dados['usuario_cadastrou'])) $condicoes = array_merge($condicoes,array('CompraPedido.usuario_cadastrou'=>$dados['usuario_cadastrou']));
			// pesquiso todos os registros cadastrados entre o intervalo do dia informado pelo usuario
			if (! empty($dados['data_hora_cadastrado'])) $condicoes[] = array('CompraPedido.data_hora_cadastrado BETWEEN ? AND ?'=>array($dados['data_hora_cadastrado'].' 00:00:00',$dados['data_hora_cadastrado'].' 23:59:59'));
			
			if (! empty ($condicoes)) {
				$this->paginate = array(
				    'limit' => 10,
				    'order' => 'CompraPedido.id ASC',
				    'contain' => array ('Fornecedor.nome'),
				);
				$resultados = $this->paginate('CompraPedido',$condicoes);
				if (! empty($resultados)) {
					$num_encontrados = count($resultados);
					$this->set('resultados',$resultados);
					$this->set('num_resultados',$num_encontrados);
					$this->Session->setFlash("Exibindo $num_encontrados pedido(s) de compra",'flash_sucesso');
				}
				else $this->Session->setFlash("Nenhum pedido de compra encontrado",'flash_erro');
			}
			else {
				$this->set('num_resultados','0');
				$this->Session->setFlash("Informe algum campo para realizar a pesquisa",'flash_erro');
			}
		}
	}
	
}

