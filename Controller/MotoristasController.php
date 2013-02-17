<?php

class MotoristasController extends AppController {
	var $name = 'Motoristas';
	var $components = array('Geral','RequestHandler');
	var $helpers = array('CakePtbr.Formatacao');
	var $paginate = array (
		'limit' => 10,
		'order' => array (
			'Motorista.id' => 'asc'
		)
	);

	/**
	 * Obtem dados necessarios ao decorrer deste controller.
	 * Os dados sao setados em variaveis a serem utilizadas nas views 
	 */
	function _obter_opcoes() {
		$this->Motorista->Veiculo->recursive = -1;
		$consulta = $this->Motorista->Veiculo->find('list',array('fields'=>array('Veiculo.id','Veiculo.placa')));
		// o indice para o valor '' é definido como sendo 0 pois no minimo
		// o registro legítimo terá o indice 1
		$consulta = array_merge(array(0=>''),$consulta);
		$this->set('opcoes_veiculo',$consulta);
	}
	
	function index() {
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		$dados = $this->paginate('Motorista');
		$this->set('consulta_motorista',$dados);
	}
	
	function cadastrar() {
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		$this->_obter_opcoes();
		if (! empty($this->request->data)) {
			
			if (isset($this->request->data['Motorista']['cnh_data_validade']) && ! empty($this->request->data['Motorista']['cnh_data_validade'])) {
				if (strtotime($this->Geral->AjustarData($this->request->data['Motorista']['cnh_data_validade'])) <= strtotime(date('Y-m-d'))) {
					$this->Session->setFlash('Data de validade da C.N.H. não pode ser menor ou igual a data atual.','flash_erro');
					return false;
				}
			}
			// caso motorista seja um campo vazio, que na view esta com o
			// indice 0, definido no metodo $this->_obter_opcoes()
			if ($this->request->data['Motorista']['veiculo_padrao'] == 0) {
				$this->request->data['Motorista']['veiculo_padrao'] = null;
			}
			if ($this->Motorista->save($this->request->data)) {
				$this->Session->setFlash('Motorista cadastrado com sucesso.','flash_sucesso');
				if ( ! $this->RequestHandler->isAjax() ) {
					$this->redirect($this->referer(array('action' => 'index')));
				}
			}
			else {
				$this->Session->setFlash('Erro ao cadastrar o motorista.','flash_erro');
			}
		}
	}
	
	function editar($id=NULL) {
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		$this->_obter_opcoes();
		if (empty ($this->request->data)) {
			$this->Motorista->recursive = -1;
			$this->Motorista->id = $id;
			$this->request->data = $this->Motorista->read();
			if ( ! $this->request->data) {
				$this->Session->setFlash('Motorista não encontrado.','flash_erro');
				$this->redirect(array('action'=>'index'));
			}
			else {
				if ($this->request->data['Motorista']['cnh_data_validade'] == '0000-00-00') $this->request->data['Motorista']['cnh_data_validade'] = null;
				else $this->request->data['Motorista']['cnh_data_validade'] = date('d/m/Y', strtotime($this->request->data['Motorista']['cnh_data_validade']));
			}
		}
		else {
			$this->request->data['Motorista']['id'] = $id;
			
			if (isset($this->request->data['Motorista']['cnh_data_validade']) && ! empty($this->request->data['Motorista']['cnh_data_validade'])) {
				if (strtotime($this->Geral->AjustarData($this->request->data['Motorista']['cnh_data_validade'])) <= strtotime(date('Y-m-d'))) {
					$this->Session->setFlash('Data de validade da C.N.H. não pode ser menor ou igual a data atual.','flash_erro');
					return false;
				}
			}
			// caso motorista seja um campo vazio, que na view esta com o
			// indice 0, definido no metodo $this->_obter_opcoes()
			if ($this->request->data['Motorista']['veiculo_padrao'] == 0) {
				$this->request->data['Motorista']['veiculo_padrao'] = null;
			}
			if ($this->Motorista->save($this->request->data)) {
				$this->Session->setFlash('Motorista atualizado com sucesso.','flash_sucesso');
				if ( ! $this->RequestHandler->isAjax() ) {
					$this->redirect(array('action'=>'index'));
				}
			}
			else {
				$this->Session->setFlash('Erro ao atualizar o motorista.','flash_erro');
			}
		}
	}
	
	function excluir($id=NULL) {
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		if (! empty($id)) {
			if ($this->Motorista->delete($id)) $this->Session->setFlash("Motorista $id excluído com sucesso.",'flash_sucesso');
			else $this->Session->setFlash("Motorista $id não pode ser excluído.",'flash_erro');
			if ( ! $this->RequestHandler->isAjax() ) {
				$this->redirect(array('action'=>'index'));
			}
		}
		else {
			$this->Session->setFlash('Motorista não informado.','flash_erro');
		}
	}
	
}

?>