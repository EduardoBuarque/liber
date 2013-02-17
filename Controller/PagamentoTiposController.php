<?php

class PagamentoTiposController extends AppController {
	var $name = 'PagamentoTipos';
	var $components = array('RequestHandler');
	var $helpers = array('Javascript');
	var $paginate = array (
		'limit' => 10,
		'order' => array (
			'PagamentoTipo.id' => 'asc'
		)
	);
	
	/**
	 * Obtem dados necessarios ao decorrer deste controller.
	 * Os dados sao setados em variaveis a serem utilizadas nas views 
	 */
	function _obter_opcoes() {
		$this->PagamentoTipo->Conta->recursive = -1;
		$opcoes_contas = $this->PagamentoTipo->Conta->find('list',array('fields'=>array('Conta.id','Conta.nome')));
		$this->set('opcoes_contas',$opcoes_contas);
		
		$this->PagamentoTipo->DocumentoTipo->recursive = -1;
		$r = $this->PagamentoTipo->DocumentoTipo->find('list',array('fields'=>array('DocumentoTipo.id','DocumentoTipo.nome')));
		$this->set('opcoes_documentos',$r);
	}
	
	/**
	* caso algum produto seja enviado (erro na validacao, editando registro, etc),
	* o insiro na pagina
	*/
	function _recupera_itens_inseridos() {
		if (isset($this->request->data['PagamentoTipoItem'])) {
			$itens = $this->request->data['PagamentoTipoItem'];
			$i = 0;
			$valor_total = 0;
			$campos_ja_inseridos = array();
			foreach ($itens as $item) {
				$campos_ja_inseridos[$i] = array('dias_intervalo_parcela'=>$item['dias_intervalo_parcela']);
				$i++;
			}
			$this->set('campos_ja_inseridos',$campos_ja_inseridos);
			return 1;
		}
		
		return 0;
	}
	
	function index() {
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		$dados = $this->paginate('PagamentoTipo');
		$this->set('consulta_pagamento_tipo',$dados);
	}
	
	function cadastrar() {
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		
		$this->_obter_opcoes();
		
		if (! empty($this->request->data)) {
			
			if ($this->PagamentoTipo->saveAll($this->request->data,array('validate'=>'first'))) {
				$this->Session->setFlash('Forma de pagamento cadastrada com sucesso.','flash_sucesso');
				$this->redirect($this->referer(array('action' => 'index')));
			}
			else {
				$this->Session->setFlash('Erro ao cadastrar a forma de pagamento.','flash_erro');
				$this->_recupera_itens_inseridos();
			}
		}
	}
	
	function editar($id=NULL) {
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		$this->_obter_opcoes();
		
		if (empty ($this->request->data)) {
			$this->PagamentoTipo->id = $id;
			$this->PagamentoTipo->contain('PagamentoTipoItem');
			$this->request->data = $this->PagamentoTipo->read();
			if ( ! $this->request->data) {
				$this->Session->setFlash('Forma de pagamento não encontrada.','flash_erro');
				$this->redirect(array('action'=>'index'));
			}
			else {
				$this->_recupera_itens_inseridos();
			}
		}
		else {
			$this->_recupera_itens_inseridos();
			$this->request->data['PagamentoTipo']['id'] = $id;
			
			// #TODO seria bom nao deletar e reinserir todos os registros
			// deleto os itens que pertenciam a forma de pagamento
			if( ! ($this->PagamentoTipo->PagamentoTipoItem->deleteAll(array('pagamento_tipo_id'=>$id),false))) {
				$this->Session->setFlash('Erro ao atualizar a forma de pagamento','flash_erro');
				return null;
			}
			
			if ($this->PagamentoTipo->saveAll($this->request->data,array('validate'=>'first'))) {
				$this->Session->setFlash('Forma de pagamento atualizada com sucesso.','flash_sucesso');
				$this->redirect(array('action'=>'index'));
			}
			else {
				$this->Session->setFlash('Erro ao atualizar a forma de pagamento.','flash_erro');
			}
		}
	}
	
	function excluir($id=NULL) {
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		#FIXME nao estah deletando
		if (! empty($id)) {
			if ($this->PagamentoTipo->deleteAll(array('PagamentoTipoItem.pagamento_tipo_id'=>$id))) {
				$this->Session->setFlash("Forma de pagamento $id excluída com sucesso.",'flash_sucesso');
			}
			else $this->Session->setFlash("Forma de pagamento $id não pode ser excluída.",'flash_erro');
			//$this->redirect(array('action'=>'index'));
		}
		else {
			$this->Session->setFlash('Forma de pagamento não informada.','flash_erro');
		}
	}
	
}

?>