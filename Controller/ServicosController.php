<?php

class ServicosController extends AppController {
	var $name = 'Servicos';
	var $components = array('RequestHandler');
	var $helpers = array('Ajax', 'Javascript');

	/**
	 * Obtem dados necessarios ao decorrer deste controller.
	 * Os dados sao setados em variaveis a serem utilizadas nas views 
	 */
	function _obter_opcoes() {
		$this->Servico->ServicoCategoria->recursive = -1;
		$consulta1 = $this->Servico->ServicoCategoria->find('list',array('fields'=>array('ServicoCategoria.id','ServicoCategoria.nome')));
		$this->set('opcoes_servico_categoria',$consulta1);
		
		return null;
	}
	
	
	function index() {
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		$this->paginate = array (
			'limit' => 10,
			'order' => array (
				'Servico.id' => 'desc'
			),
		    'contain' => array('ServicoCategoria'),
		);
		$dados = $this->paginate('Servico');
		$this->set('consulta',$dados);
	}
	
	function cadastrar() {
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		$this->_obter_opcoes();
		if (! empty($this->request->data)) {
			
			if ($this->Servico->save($this->request->data)) {
				$this->Session->setFlash('Serviço cadastrado com sucesso.','flash_sucesso');
				if ( ! $this->RequestHandler->isAjax() ) {
					$this->redirect($this->referer(array('action' => 'index')));
				}
			}
			else {
				$this->Session->setFlash('Erro ao cadastrar o serviço.','flash_erro');
			}
		}
	}
	
	function editar($id=NULL) {
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		$this->_obter_opcoes();
		if (empty ($this->request->data)) {
			$this->Servico->contain('ServicoCategoria');
			$this->Servico->id = $id;
			$this->request->data = $this->Servico->read();
			if ( ! $this->request->data) {
				$this->Session->setFlash('Serviço não encontrado.','flash_erro');
				$this->redirect(array('action'=>'index'));
			}
		}
		else {
			$this->request->data['Servico']['id'] = $id;
			
			if ($this->Servico->save($this->request->data)) {
				$this->Session->setFlash('Serviço atualizado com sucesso.','flash_sucesso');
				if ( ! $this->RequestHandler->isAjax() ) {
					$this->redirect($this->referer(array('action' => 'index')));
				}
			}
			else {
				$this->Session->setFlash('Erro ao atualizar o serviço.','flash_erro');
			}
		}
	}
	
	function excluir($id=NULL) {
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		if (! empty($id)) {
			if ($this->Servico->delete($id)) $this->Session->setFlash("Serviço $id excluído com sucesso.",'flash_sucesso');
			else $this->Session->setFlash("Serviço $id não pode ser excluído.",'flash_erro');
			$this->redirect(array('action'=>'index'));
		}
		else {
			$this->Session->setFlash('Serviço não informado.','flash_erro');
		}
	}
	
	function pesquisaAjaxServico($campo_a_pesquisar,$termo = null) {
		if (strtoupper($campo_a_pesquisar) == "NOME") $campo = 'nome';
		else if (strtoupper($campo_a_pesquisar) == "CODIGO") $campo = 'id';
		else return null;
		if (! isset($termo)) $termo = $this->request['url']['term'];
		if ( $this->RequestHandler->isAjax() ) {
			$i=0;
			$resultados=array();
			$retorno=array();
			$r = array();
   			Configure::write ('debug',0);
   			$this->autoRender=false;
			if ($campo == 'id') {
				$condicoes = array('Servico.id'=>$termo);
			}
			else {
				$condicoes = array("Servico.$campo LIKE" => '%'.$termo.'%');
			}
			$this->Servico->recursive = -1;
			$resultados = $this->Servico->find('all',array('fields' => array('id','nome','valor'),'conditions'=>$condicoes));
			if (!empty($resultados)) {
				foreach ($resultados as $r) {
					$retorno[$i]['label'] = $r['Servico']['nome'];
					$retorno[$i]['value'] = $r['Servico'][$campo];
					$retorno[$i]['id'] = $r['Servico']['id'];
					$retorno[$i]['valor'] = $r['Servico']['valor'];
					$i++; 
				}
				print json_encode($retorno);
			}
		}
	}
	
}

?>