<?php

class GruposController extends AppController {
	var $name = 'Grupos';
	var $components = array('RequestHandler');
	var $paginate = array (
		'limit' => 10,
		'order' => array (
			'Grupo.id' => 'asc'
		),
	    'contain' => array()
	);

	public function beforeFilter() {
			parent::beforeFilter();
		}
		
	function index() {
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		$dados = $this->paginate('Grupo');
		$this->set('consulta_grupo',$dados);
	}
	
	function cadastrar() {
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		if (! empty($this->request->data)) {
			
			if ($this->Grupo->save($this->request->data)) {
				$this->Session->setFlash('Grupo cadastrado com sucesso.','flash_sucesso');
				if ( ! $this->RequestHandler->isAjax() ) {
					$this->redirect($this->referer(array('action' => 'index')));
				}
			}
			else {
				$this->Session->setFlash('Erro ao cadastrar o grupo.','flash_erro');
			}
		}
	}
	
	function editar($id=NULL) {
		$this->Grupo->id = $id;
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		if (empty ($this->request->data)) {
			$this->Grupo->recursive = -1;
			$this->request->data = $this->Grupo->read();
			if ( ! $this->request->data) {
				$this->Session->setFlash('Grupo não encontrado.','flash_erro');
				if ( ! $this->RequestHandler->isAjax() ) {
					$this->redirect(array('action'=>'index'));
				}
			}
		}
		else {
			if ($this->Grupo->save($this->request->data)) {
				$this->Session->setFlash('Grupo atualizado com sucesso.','flash_sucesso');
				$this->redirect(array('action'=>'index'));
			}
			else {
				$this->Session->setFlash('Erro ao atualizar o grupo.','flash_erro');
			}
		}
	}
	
	function excluir($id=NULL) {
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		if (! empty($id)) {
			if ($this->Grupo->delete($id)) $this->Session->setFlash("Grupo $id excluído com sucesso.",'flash_sucesso');
			else $this->Session->setFlash("Grupo $id não pode ser excluído.",'flash_erro');
			if ( ! $this->RequestHandler->isAjax() ) {
					$this->redirect(array('action'=>'index'));
				}
		}
		else {
			$this->Session->setFlash('Grupo não informado.','flash_erro');
		}
	}
	
}

?>