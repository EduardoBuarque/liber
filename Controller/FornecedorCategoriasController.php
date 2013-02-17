<?php

class FornecedorCategoriasController extends AppController {
	var $name = 'FornecedorCategorias';
	var $components = array('RequestHandler');
	var $helpers = array('Js' => array('Jquery'),'Ajax');
	var $paginate = array (
		'limit' => 10,
		'order' => array (
			'FornecedorCategoria.id' => 'desc'
		)
	);

	function index() {
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		$this->FornecedorCategoria->recursive = -1;
		$this->set('consulta',$this->paginate());
	}
	
	function cadastrar() {
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		if (! empty($this->request->data)) {
			
			if ($this->FornecedorCategoria->save($this->request->data)) {
				$this->Session->setFlash('Categoria de fornecedor cadastrada com sucesso.','flash_sucesso');
				unset($this->request->data);
				if ( ! $this->RequestHandler->isAjax() ) {
					$this->redirect($this->referer(array('action' => 'index')));
				}
			}
			else {
				$this->Session->setFlash('Erro ao cadastrar a categoria de fornecedor.','flash_erro');
			}
		}
	}
	
	function editar($id=NULL) {
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		if (empty ($this->request->data)) {
			$this->FornecedorCategoria->recursive = -1;
			$this->FornecedorCategoria->id = $id;
			$this->request->data = $this->FornecedorCategoria->read();
			if ( ! $this->request->data) {
				$this->Session->setFlash('Categoria de fornecedor não encontrada.','flash_erro');
				if ( ! $this->RequestHandler->isAjax() ) {
					$this->redirect(array('action'=>'index'));
				}
			}
		}
		else {
			$this->request->data['FornecedorCategoria']['id'] = $id;
			
			if ($this->FornecedorCategoria->save($this->request->data)) {
				unset($this->request->data);
				$this->Session->setFlash('Categoria de fornecedor atualizada com sucesso.','flash_sucesso');
				if ( ! $this->RequestHandler->isAjax() ) {
					$this->redirect(array('action'=>'index'));
				}
			}
			else {
				$this->Session->setFlash('Erro ao atualizar a categoria de fornecedor.','flash_erro');
			}
		}
	}
	
	function excluir($id=NULL) {
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		if (! empty($id)) {
			if ($this->FornecedorCategoria->delete($id)) $this->Session->setFlash("Categoria de fornecedor $id excluída com sucesso.",'flash_sucesso');
			else $this->Session->setFlash("Categoria de fornecedor $id não pode ser excluída.",'flash_erro');
			$this->redirect(array('action'=>'index'));
		}
		else {
			$this->Session->setFlash('Categoria de fornecedor não informada.','flash_erro');
		}
	}
	
}

?>