<?php

class ServicoCategoriasController extends AppController {
	var $name = 'ServicoCategorias';
	var $paginate = array (
		'limit' => 10,
		'order' => array (
			'ServicoCategoria.id' => 'desc'
		),
	    'contain' => array()
	);

	function index() {
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		$dados = $this->paginate('ServicoCategoria');
		$this->set('consulta',$dados);
	}
	
	function cadastrar() {
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		if (! empty($this->request->data)) {
			
			if ($this->ServicoCategoria->save($this->request->data)) {
				unset($this->request->data);
				$this->Session->setFlash('Categoria de serviço cadastrada com sucesso.','flash_sucesso');
				if ( ! $this->RequestHandler->isAjax() ) {
					$this->redirect($this->referer(array('action' => 'index')));
				}
			}
			else {
				$this->Session->setFlash('Erro ao cadastrar a categoria de serviço.','flash_erro');
			}
		}
	}
	
	function editar($id=NULL) {
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		if (empty ($this->request->data)) {
			$this->ServicoCategoria->recursive = -1;
			$this->ServicoCategoria->id = $id;
			$this->request->data = $this->ServicoCategoria->read();
			if ( ! $this->request->data) {
				$this->Session->setFlash('Categoria de serviço não encontrada.','flash_erro');
				if ( ! $this->RequestHandler->isAjax() ) {
					$this->redirect(array('action'=>'index'));
				}
			}
		}
		else {
			$this->request->data['ServicoCategoria']['id'] = $id;
			
			if ($this->ServicoCategoria->save($this->request->data)) {
				unset($this->request->data);
				$this->Session->setFlash('Categoria de serviço atualizada com sucesso.','flash_sucesso');
				if ( ! $this->RequestHandler->isAjax() ) {
					$this->redirect(array('action'=>'index'));
				}
			}
			else {
				$this->Session->setFlash('Erro ao atualizar a categoria de serviço.','flash_erro');
			}
		}
	}
	
	function excluir($id=NULL) {
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		if (! empty($id)) {
			if ($this->ServicoCategoria->delete($id)) $this->Session->setFlash("Categoria de serviço $id excluída com sucesso.",'flash_sucesso');
			else $this->Session->setFlash("Categoria de serviço $id não pode ser excluída.",'flash_erro');
			$this->redirect(array('action'=>'index'));
		}
		else {
			$this->Session->setFlash('Categoria de serviço não informada.','flash_erro');
		}
	}
	
}

?>