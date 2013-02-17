<?php

class SistemaOpcoesController extends AppController {
	var $name = 'SistemaOpcoes';

	function _obter_opcoes() {
		$this->SistemaOpcao->ContaPlanoVendaPedido->recursive = -1;
		$r = $this->SistemaOpcao->ContaPlanoVendaPedido->find('list',array('fields'=>array('id','nome')));
		$this->set('opcoes_conta_planos',$r);
	}
	
	public function beforeFilter() {
			parent::beforeFilter();
		}
		
	function index() {
		$this->SistemaOpcao->id = 1;
		$this->_obter_opcoes();
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		if (empty ($this->request->data)) {
			$this->SistemaOpcao->recursive = -1;
			$this->request->data = $this->SistemaOpcao->read();
			if ( ! $this->request->data) {
				$this->Session->setFlash('Opções não encontradas.','flash_erro');
				if ( ! $this->RequestHandler->isAjax() ) {
					$this->redirect(array('action'=>'index'));
				}
			}
		}
		else {		
			if ($this->SistemaOpcao->save($this->request->data)) {
				$this->Session->setFlash('Opções atualizadas com sucesso.','flash_sucesso');
				if ( ! $this->RequestHandler->isAjax() ) {
					$this->redirect(array('action'=>'index'));
				}
			}
			else {
				$this->Session->setFlash('Erro ao atualizar as opções.','flash_erro');
			}
		}
	}
	
}

?>