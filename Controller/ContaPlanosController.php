<?php

class ContaPlanosController extends AppController {
	var $name = 'ContaPlanos';
	var $helpers = array('Ajax');

	function _obter_opcoes() {
		$opcoes = array(
			'D'=>'Despesas',
			'R'=>'Receitas',
			'E'=>'Especiais'
		);
		$this->set('opcoes',$opcoes);
	}
	
	function index() {
		$this->_obter_opcoes();
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		$this->paginate = array (
			'limit' => 30,
			'order' => array (
				'ContaPlano.nome' => 'asc'
			),
		    'contain' => array()
		    
		);
		$dados = $this->paginate('ContaPlano');
		$this->set('consulta_conta_planos',$dados);
	}
	
	function cadastrar() {
		$this->_obter_opcoes();
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		if (! empty($this->request->data)) {
			
			if ($this->ContaPlano->save($this->request->data)) {
				$this->Session->setFlash('Item do plano de contas cadastrado com sucesso.','flash_sucesso');
				$this->redirect($this->referer(array('action' => 'index')));
			}
			else {
				$this->Session->setFlash('Erro ao cadastrar item do plano de contas.','flash_erro');
			}
		}
	}
	
	function editar($id=NULL) {
		$this->_obter_opcoes();
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		if (empty ($this->request->data)) {
			$this->ContaPlano->recursive = -1;
			$this->ContaPlano->id = $id;
			$this->request->data = $this->ContaPlano->read();
			if ( ! $this->request->data) {
				$this->Session->setFlash('Item do plano de contas não encontrado.','flash_erro');
				$this->redirect(array('action'=>'index'));
			}
		}
		else {
			$this->request->data['ContaPlano']['id'] = $id;
			
			if ($this->ContaPlano->save($this->request->data)) {
				$this->Session->setFlash('Plano de contas atualizado com sucesso.','flash_sucesso');
				$this->redirect(array('action'=>'index'));
			}
			else {
				$this->Session->setFlash('Erro ao atualizar o plano de contas.','flash_erro');
			}
		}
	}
	
	function excluir($id=NULL) {
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		if (! empty($id)) {
			if ($this->ContaPlano->delete($id)) $this->Session->setFlash("Item $id do plano de contas excluído com sucesso.",'flash_sucesso');
			else $this->Session->setFlash("Item $id do plano de contas não pode ser excluída.",'flash_erro');
			$this->redirect(array('action'=>'index'));
		}
		else {
			$this->Session->setFlash('Item do plano de contas não informado.','flash_erro');
		}
	}
	
}

?>