<?php

class VeiculosController extends AppController {
	var $name = 'Veiculos';
	var $components = array('RequestHandler');
	var $paginate = array (
		'limit' => 10,
		'order' => array (
			'Veiculo.id' => 'asc'
		),
	    'contain' => array()
	);

	function index() {
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		$dados = $this->paginate('Veiculo');
		$this->set('consulta_veiculo',$dados);
	}
	
	function cadastrar() {
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		if (! empty($this->request->data)) {
			
			if ($this->Veiculo->save($this->request->data)) {
				$this->Session->setFlash('Veiculo cadastrado com sucesso.','flash_sucesso');
				if ( ! $this->RequestHandler->isAjax() ) {
					$this->redirect($this->referer(array('action' => 'index')));
				}
			}
			else {
				$this->Session->setFlash('Erro ao cadastrar o veículo.','flash_erro');
			}
		}
	}
	
	function editar($id=NULL) {
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		if (empty ($this->request->data)) {
			$this->Veiculo->recursive = -1;
			$this->Veiculo->id = $id;
			$this->request->data = $this->Veiculo->read();
			if ( ! $this->request->data) {
				$this->Session->setFlash('Veículo não encontrado.','flash_erro');
				if ( ! $this->RequestHandler->isAjax() ) {
					$this->redirect(array('action'=>'index'));
				}
			}
		}
		else {
			$this->request->data['Veiculo']['id'] = $id;
			
			if ($this->Veiculo->save($this->request->data)) {
				$this->Session->setFlash('Veículo atualizado com sucesso.','flash_sucesso');
				$this->redirect(array('action'=>'index'));
			}
			else {
				$this->Session->setFlash('Erro ao atualizar o veículo.','flash_erro');
			}
		}
	}
	
	function excluir($id=NULL) {
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		if (! empty($id)) {
			if ($this->Veiculo->delete($id)) $this->Session->setFlash("Veículo $id excluído com sucesso.",'flash_sucesso');
			else $this->Session->setFlash("Veículo $id não pode ser excluído.",'flash_erro');
			if ( ! $this->RequestHandler->isAjax() ) {
					$this->redirect(array('action'=>'index'));
				}
		}
		else {
			$this->Session->setFlash('Veículo não informado.','flash_erro');
		}
	}
	
}

?>