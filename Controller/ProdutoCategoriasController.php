<?php

class ProdutoCategoriasController extends AppController {
	var $name = 'ProdutoCategorias';
	var $components = array('RequestHandler');
	var $helpers = array('Javascript','Ajax');
	var $paginate = array (
		'limit' => 10,
		'order' => array (
			'ProdutoCategoria.id' => 'desc'
		)
	);

	function index() {
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		$dados = $this->paginate('ProdutoCategoria');
		$this->set('consulta',$dados);
	}
	
	function cadastrar() {
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		if (! empty($this->request->data)) {
			
			if ($this->ProdutoCategoria->save($this->request->data)) {
				unset($this->request->data);
				$this->Session->setFlash('Categoria de produto cadastrada com sucesso.','flash_sucesso');
				if ( ! $this->RequestHandler->isAjax() ) {
					$this->redirect($this->referer(array('action' => 'index')));
				}
			}
			else {
				$this->Session->setFlash('Erro ao cadastrar a categoria de produto.','flash_erro');
			}
		}
	}
	
	function editar($id=NULL) {
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		if (empty ($this->request->data)) {
			$this->ProdutoCategoria->id = $id;
			$this->ProdutoCategoria->recursive = -1;
			$this->request->data = $this->ProdutoCategoria->read();
			if ( ! $this->request->data) {
				$this->Session->setFlash('Categoria de produto não encontrada.','flash_erro');
				if ( ! $this->RequestHandler->isAjax() ) {
					$this->redirect(array('action'=>'index'));
				}
			}
		}
		else {
			$this->request->data['ProdutoCategoria']['id'] = $id;
			
			if ($this->ProdutoCategoria->save($this->request->data)) {
				unset($this->request->data);
				$this->Session->setFlash('Categoria de produto atualizada com sucesso.','flash_sucesso');
				if ( ! $this->RequestHandler->isAjax() ) {
					$this->redirect(array('action'=>'index'));
				}
			}
			else {
				$this->Session->setFlash('Erro ao atualizar a categoria de produto.');
			}
		}
	}
	
	function excluir($id=NULL) {
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		if (! empty($id)) {
			if ($this->ProdutoCategoria->delete($id)) $this->Session->setFlash("Categoria de produto $id excluída com sucesso.",'flash_sucesso');
			else $this->Session->setFlash("Categoria de produto $id não pode ser excluída.",'flash_erro');
			$this->redirect(array('action'=>'index'));
		}
		else {
			$this->Session->setFlash('Categoria de produto não informada.','flash_erro');
		}
	}
	
}

?>