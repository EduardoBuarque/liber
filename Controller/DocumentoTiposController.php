<?php

class DocumentoTiposController extends AppController {
	var $name = 'DocumentoTipos';
	var $paginate = array (
		'limit' => 10,
		'order' => array (
			'DocumentoTipo.nome' => 'asc'
		),
	    'contain' => array()
	);

	function index() {
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		$dados = $this->paginate('DocumentoTipo');
		$this->set('consulta_documento_tipo',$dados);
	}
	
	function cadastrar() {
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		if (! empty($this->request->data)) {
			
			if ($this->DocumentoTipo->save($this->request->data)) {
				$this->Session->setFlash('Tipo de documento cadastrado com sucesso.','flash_sucesso');
				$this->redirect($this->referer(array('action' => 'index')));
			}
			else {
				$this->Session->setFlash('Erro ao cadastrar tipo de documento.','flash_erro');
			}
		}
	}
	
	function editar($id=NULL) {
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		if (empty ($this->request->data)) {
			$this->DocumentoTipo->recursive = -1;
			$this->DocumentoTipo->id = $id;
			$this->request->data = $this->DocumentoTipo->read();
			if ( ! $this->request->data) {
				$this->Session->setFlash('Tipo de documento não encontrado.','flash_erro');
				$this->redirect(array('action'=>'index'));
			}
		}
		else {
			$this->request->data['DocumentoTipo']['id'] = $id;
			
			if ($this->DocumentoTipo->save($this->request->data)) {
				$this->Session->setFlash('Tipo de documento atualizado com sucesso.','flash_sucesso');
				$this->redirect(array('action'=>'index'));
			}
			else {
				$this->Session->setFlash('Erro ao atualizar o Tipo de documento.','flash_erro');
			}
		}
	}
	
	function excluir($id=NULL) {
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		if (! empty($id)) {
			if ($this->DocumentoTipo->delete($id)) $this->Session->setFlash("Tipo de documento $id excluído com sucesso.",'flash_sucesso');
			else $this->Session->setFlash("Tipo de documento $id não pode ser excluído.",'flash_erro');
			$this->redirect(array('action'=>'index'));
		}
		else {
			$this->Session->setFlash('Tipo de documento não informado.','flash_erro');
		}
	}
	
}

?>