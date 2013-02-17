<?php

class FornecedoresController extends AppController {
	var $name = 'Fornecedores';
	var $helpers = array('CakePtbr.Estados','Javascript','Ajax');
	var $components = array('RequestHandler');

	/**
	 * Obtem dados necessarios ao decorrer deste controller.
	 * Os dados sao setados em variaveis a serem utilizadas nas views 
	 */
	function _obter_opcoes() {
		$this->loadModel('FornecedorCategoria');
		$this->FornecedorCategoria->recursive = -1;
		$consulta1 = $this->FornecedorCategoria->find('list',array('fields'=>array('FornecedorCategoria.id','FornecedorCategoria.descricao')));
		$this->set('opcoes_categoria_fornecedor',$consulta1);
		
		$this->loadModel('Empresa');
		$this->Empresa->recursive = -1;
		$consulta2 = $this->Empresa->findEmpresa();
		$this->set('opcoes_empresa',$consulta2);
		return null;
	}
	
	
	/**
	 * Lista todos os Fornecedores
	 */
	function index() {
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		$this->paginate = array (
			'limit' => 10,
			'order' => array (
				'Fornecedor.id' => 'desc'
			),
		    'contain' => array(),
		);
		$dados = $this->paginate('Fornecedor');
		$this->set('consulta_fornecedor',$dados);
	}
	
	function cadastrar() {
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		$this->_obter_opcoes();
		if (! empty($this->request->data)) {
			$this->request->data['Fornecedor'] += array ('data_cadastrado' => date('Y-m-d H:i:s'));
			$this->request->data['Fornecedor'] += array ('usuario_cadastrou' => $this->Auth->user('id'));
			
			if ($this->Fornecedor->save($this->request->data)) {
				$this->Session->setFlash('Fornecedor cadastrado com sucesso.','flash_sucesso');
				$this->redirect(array('controller'=>'Fornecedores'));
			}
			else {
				$this->Session->setFlash('Erro ao cadastrar o Fornecedor.','flash_erro');
			}
		}
	}
	
	function editar($id=NULL) {
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		$this->_obter_opcoes();
		if (empty ($this->request->data)) {
			$this->Fornecedor->id = $id;
			$this->Fornecedor->recursive = -1;
			$this->request->data = $this->Fornecedor->read();
			if ( ! $this->request->data) {
				$this->Session->setFlash('Fornecedor não encontrado.','flash_erro');
				$this->redirect(array('controller'=>'Fornecedores','action'=>'pesquisar'));
			}
		}
		else {
			$this->request->data['Fornecedor']['id'] = $id;
			$this->request->data['Fornecedor'] += array ('atualizado' => date('Y-m-d H:i:s'));
			$this->request->data['Fornecedor'] += array ('usuario_alterou' => $this->Auth->user('id'));
			
			if ($this->Fornecedor->save($this->request->data)) {
				$this->Session->setFlash('Fornecedor atualizado com sucesso.','flash_sucesso');
				$this->redirect(array('action' => 'index'));
			}
			else {
				$this->Session->setFlash('Erro ao atualizar o Fornecedor.','flash_erro');
			}
		}
	}
	
	function pesquisar() {
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		
		if (! empty($this->request->data)) {
			//usuario enviou os dados da pesquisa
			$url = array('controller'=>'Fornecedores','action'=>'pesquisar');
			//convertendo alguns caracteres
			if( is_array($this->request->data['Fornecedor']) ) {
				foreach($this->request->data['Fornecedor'] as $chave => &$item) {
					if (empty($item)) {
						unset($this->request->data['Fornecedor'][$chave]);
						continue;
					}
					// urlencode duas vezes para nao haver problema com / e \
					$item = htmlentities(urlencode(urlencode($item)));
				}
			}
			$params = array_merge($url,$this->request->data['Fornecedor']);
			$this->redirect($params);
		}
		
		if (! empty($this->request->params['named'])) {
			//a instrucao acima redirecionou para cá
			foreach ($this->request->params['named'] as &$valor) {
				$valor = html_entity_decode(urldecode(urldecode($valor)));
			}
			$dados = $this->request->params['named'];
			$condicoes=array();
			if (! empty($dados['nome'])) $condicoes = array_merge($condicoes, array('Fornecedor.nome LIKE'=>'%'.$dados['nome'].'%'));
			if (! empty($dados['nome_fantasia'])) $condicoes = array_merge($condicoes, array('Fornecedor.nome_fantasia LIKE'=>'%'.$dados['nome_fantasia'].'%'));
			if (! empty($dados['bairro'])) $condicoes = array_merge($condicoes, array('Fornecedor.bairro'=>$dados['bairro']));
			if (! empty($dados['cidade'])) $condicoes = array_merge($condicoes, array('Fornecedor.cidade'=>$dados['cidade']));
			if (! empty($dados['cnpj'])) $condicoes = array_merge($condicoes, array('Fornecedor.cnpj'=>$dados['cnpj']));
			if (! empty($dados['inscricao_estadual'])) $condicoes = array_merge($condicoes, array('Fornecedor.inscricao_estadual'=>$dados['inscricao_estadual']));
			if (! empty($dados['cpf'])) $condicoes = array_merge($condicoes, array('Fornecedor.cpf'=>$dados['cpf']));
			if (! empty($dados['rg'])) $condicoes = array_merge($condicoes, array('Fornecedor.rg'=>$dados['rg']));
			if (! empty ($condicoes)) {
				$this->paginate = array (
					'limit' => 10,
					'order' => array (
						'Fornecedor.id' => 'desc'
					),
					'contain' => array('Usuario'),
				);
				$resultados = $this->paginate('Fornecedor',$condicoes);
				if (! empty($resultados)) {
					$num_encontrados = count($resultados);
					$this->set('resultados',$resultados);
					$this->set('num_resultados',$num_encontrados);
					$this->Session->setFlash("Exibindo $num_encontrados fornecedor(s)",'flash_sucesso');
				}
				else $this->Session->setFlash("Nenhum fornecedor encontrado",'flash_erro');
			}
			else {
				$this->set('num_resultados','0');
				$this->Session->setFlash("Nenhum resultado encontrado",'flash_erro');
			}
		}
	}
	
	function detalhar($id = NULL) {
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		if ($id) {
			$this->Fornecedor->id = $id;
			$this->Fornecedor->contain('Usuario','Usuario2');
			$r = $this->Fornecedor->read();
			if (empty($r)) {
				$this->Session->setFlash("Fornecedor $id não encontrado");
				return null;
			}
			else $this->set('fornecedor',$r);
		}
		else {
			$this->Session->setFlash('Nenhum fornecedor informado.','flash_erro');
			$this->redirect(array('controller'=>'Fornecedores'));
		}
	}

	function pesquisaAjaxFornecedor($campo_a_pesquisar,$termo = null) {
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
				$condicoes = array('Fornecedor.id'=>$termo);
			}
			else {
				$condicoes = array("Fornecedor.$campo LIKE" => '%'.$termo.'%');
			}
			$this->Fornecedor->recursive = -1;
			$resultados = $this->Fornecedor->find('all',array('fields' => array('id','nome','situacao'),'conditions'=>$condicoes));
			if (!empty($resultados)) {
				foreach ($resultados as $r) {
					$retorno[$i]['label'] = $r['Fornecedor']['nome'];
					$retorno[$i]['value'] = $r['Fornecedor'][$campo];
					$retorno[$i]['id'] = $r['Fornecedor']['id'];
					$retorno[$i]['nome'] = $r['Fornecedor']['nome'];
					$retorno[$i]['bloqueado'] = ($r['Fornecedor']['situacao'] == 'B') ? 1 : 0;
					$retorno[$i]['inativo'] = ($r['Fornecedor']['situacao'] == 'I') ? 1 : 0;
					$retorno[$i]['situacao'] = $r['Fornecedor']['situacao'];
					$i++; 
				}
				print json_encode($retorno);
			}
		}
	}
	
}

?>