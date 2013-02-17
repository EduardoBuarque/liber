<?php

class ClienteCategoriasController extends AppController {
	var $name = 'ClienteCategorias';
	var $components = array('RequestHandler');
	var $helpers = array('Javascript','Ajax');
	var $paginate = array (
		'limit' => 10,
		'order' => array (
			'ClienteCategoria.id' => 'desc'
		),
	    'contain' => array(),
	);

	function index() {
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		$this->ClienteCategoria->recursive = 0;
		$this->set('consulta',$this->paginate());
	
	}
	
	function cadastrar() {
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		if (! empty($this->request->data)) {
			
			if ($this->ClienteCategoria->save($this->request->data)) {
				$this->Session->setFlash('Categoria de cliente cadastrada com sucesso.','flash_sucesso');
				unset($this->request->data);
				if ( ! $this->RequestHandler->isAjax() ) {
					$this->redirect($this->referer(array('action' => 'index')));
				}
			}
			else {
				$this->Session->setFlash('Erro ao cadastrar a categoria de cliente.','flash_erro');
			}
		}
	}
	
	function editar($id=NULL) {
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		if (empty ($this->request->data)) {
			$this->ClienteCategoria->id = $id;
			$this->ClienteCategoria->recursive = -1;
			$this->request->data = $this->ClienteCategoria->read();
			if ( ! $this->request->data) {
				$this->Session->setFlash('Categoria de cliente não encontrada.','flash_erro');
				if ( ! $this->RequestHandler->isAjax() ) {
					$this->redirect(array('action'=>'index'));
				}
			}
		}
		else {
			$this->request->data['ClienteCategoria']['id'] = $id;
			
			if ($this->ClienteCategoria->save($this->request->data)) {
				unset($this->request->data);
				$this->Session->setFlash('Categoria de cliente atualizada com sucesso.','flash_sucesso');
				if ( ! $this->RequestHandler->isAjax() ) {
					$this->redirect(array('action'=>'index'));
				}
			}
			else {
				$this->Session->setFlash('Erro ao atualizar a categoria de cliente.','flash_erro');
			}
		}
	}
	
	function excluir($id=NULL) {
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		if (! empty($id)) {
			if ($this->ClienteCategoria->delete($id)) $this->Session->setFlash("Categoria de cliente $id excluída com sucesso.",'flash_sucesso');
			else $this->Session->setFlash("Categoria de cliente $id não pode ser excluída.",'flash_erro');
			$this->redirect(array('action'=>'index'));
		}
		else {
			$this->Session->setFlash('Categoria de cliente não informada.','flash_erro');
		}
	}
	
}

?>