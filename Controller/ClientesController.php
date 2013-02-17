<?php

class ClientesController extends AppController {
	var $name = 'Clientes';
	var $components = array('RequestHandler');
	var $helpers = array('CakePtbr.Estados','Ajax', 'Javascript');
           
	/**
	 * Obtem dados necessarios ao decorrer deste controller.
	 * Os dados sao setados em variaveis a serem utilizadas nas views 
	 */
	function _obter_opcoes() {
		$this->loadModel('ClienteCategoria');
		$this->ClienteCategoria->recursive = -1;
		$consulta1 = $this->ClienteCategoria->find('list',array('fields'=>array('ClienteCategoria.id','ClienteCategoria.descricao')));
		$this->set('opcoes_categoria_cliente',$consulta1);
		
		$this->loadModel('Empresa');
		$this->Empresa->recursive = -1;
		$consulta2 = $this->Empresa->findEmpresa();
		$this->set('opcoes_empresa',$consulta2);
		
		$tipos = array(
		    ''=>'','F'=>'Física','J'=>'Jurídica'
		);
		$this->set('opcoes_tipos',$tipos);
		
		$situacoes = array (
		    ''=>'','A'=>'Ativo','I'=>'Inativo','B'=>'Bloqueado'
		);
		$this->set('opcoes_situacoes',$situacoes);
		return null;
	}
	
	
	/**
	 * Lista todos os Clientes
	 */
	function index() {
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		$this->paginate = array (
			'limit' => 10,
			'order' => array (
				'Cliente.id' => 'desc'
			),
			'contain' => array(),
		);
		$dados = $this->paginate('Cliente');
		$this->set('consulta_cliente',$dados);
	}
	
	function cadastrar() {
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		$this->_obter_opcoes();
		if (! empty($this->request->data)) {
			$this->request->data['Cliente'] += array ('data_cadastrado' => date('Y-m-d H:i:s'));
			$this->request->data['Cliente'] += array ('usuario_cadastrou' => $this->Auth->user('id'));
			
			/**
			 * letras e numero, espaço, ponto, &
			 */
			/*if ( preg_match('/[^[:alnum:].& ]/i',$this->request->data['Cliente']['nome']) ) {
				$this->Cliente->invalidate('nome','Há caracteres inválidos');
			}*/
			
			
			if ($this->Cliente->validates() && $this->Cliente->save($this->request->data)) {
				$this->Session->setFlash('Cliente cadastrado com sucesso.','flash_sucesso');
				$this->redirect($this->referer(array('action' => 'index')));
			}
			else {
				$this->Session->setFlash('Erro ao cadastrar o cliente.','flash_erro');
			}
		}
	}
	
	function editar($id=NULL) {
		
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		$this->_obter_opcoes();
		if (empty ($this->request->data)) {
			$this->Cliente->id = $id;
			$this->Cliente->recursive = -1;
			$this->request->data = $this->Cliente->read();
			if ( ! $this->request->data) {
				$this->Session->setFlash('Cliente não encontrado.','flash_erro');
				$this->redirect(array('controller'=>'Clientes','action'=>'pesquisar'));
			}
		}
		else {
			$this->request->data['Cliente']['id'] = $id;
			$this->request->data['Cliente'] += array ('atualizado' => date('Y-m-d H:i:s'));
			$this->request->data['Cliente'] += array ('usuario_alterou' => $this->Auth->user('id'));
			
			if ($this->Cliente->save($this->request->data)) {
				$this->Session->setFlash('Cliente atualizado com sucesso.','flash_sucesso');
				$this->redirect(array('controller'=>'Clientes'));
			}
			else {
				$this->Session->setFlash('Erro ao atualizar o cliente.','flash_erro');
			}
		}
	}
	
	function pesquisar() {
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		if (! empty($this->request->data)) {
			//usuario enviou os dados da pesquisa
			$url = array('controller'=>'Clientes','action'=>'pesquisar');
			//convertendo alguns caracteres
			if( is_array($this->request->data['Cliente']) ) {
				foreach($this->request->data['Cliente'] as $chave => &$item) {
					if (empty($item)) {
						unset($this->request->data['Cliente'][$chave]);
						continue;
					}
					// urlencode duas vezes para nao haver problema com / e \
					$item = htmlentities(urlencode(urlencode($item)));
				}
			}
			$params = array_merge($url,$this->request->data['Cliente']);
			$this->redirect($params);
		}
		
		if (! empty($this->request->params['named'])) {
			//a instrucao acima redirecionou para cá
			foreach ($this->request->params['named'] as &$valor) {
				$valor = html_entity_decode(urldecode(urldecode($valor)));
			}
			$dados = $this->request->params['named'];
			$condicoes=array();
			if (! empty($dados['id'])) $condicoes = array_merge($condicoes, array('Cliente.id'=>$dados['id']));
			if (! empty($dados['nome'])) $condicoes = array_merge($condicoes, array('Cliente.nome LIKE'=>'%'.$dados['nome'].'%'));
			if (! empty($dados['nome_fantasia'])) $condicoes = array_merge($condicoes, array('Cliente.nome_fantasia LIKE'=>'%'.$dados['nome_fantasia'].'%'));
			if (! empty($dados['bairro'])) $condicoes = array_merge($condicoes, array('Cliente.bairro'=>$dados['bairro']));
			if (! empty($dados['cidade'])) $condicoes = array_merge($condicoes, array('Cliente.cidade'=>$dados['cidade']));
			if (! empty($dados['cnpj'])) $condicoes = array_merge($condicoes, array('Cliente.cnpj'=>$dados['cnpj']));
			if (! empty($dados['inscricao_estadual'])) $condicoes = array_merge($condicoes, array('Cliente.inscricao_estadual'=>$dados['inscricao_estadual']));
			if (! empty($dados['cpf'])) $condicoes = array_merge($condicoes, array('Cliente.cpf'=>$dados['cpf']));
			if (! empty($dados['rg'])) $condicoes = array_merge($condicoes, array('Cliente.rg'=>$dados['rg']));
			if (! empty ($condicoes)) {
				$this->paginate = array (
					'limit' => 10,
					'order' => array (
						'Cliente.id' => 'desc'
					),
					'contain' => array('Usuario'),
				);
				$resultados = $this->paginate('Cliente',$condicoes);
				if (! empty($resultados)) {
					$num_encontrados = count($resultados);
					$this->set('resultados',$resultados);
					$this->set('num_resultados',$num_encontrados);
					$this->Session->setFlash("Exibindo $num_encontrados cliente(s)",'flash_sucesso');
				}
				else $this->Session->setFlash("Nenhum cliente encontrado",'flash_erro');
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
			$this->Cliente->id = $id;
			$this->Cliente->contain('Usuario','Usuario2');
			$r = $this->Cliente->read();
			if (empty($r)) {
				$this->Session->setFlash("Cliente $id não encontrado");
				return null;
			}
			else $this->set('cliente',$r);
		}
		else {
			$this->Session->setFlash('Erro: nenhum cliente informado.','flash_erro');
			$this->redirect(array('controller'=>'Clientes'));
		}
	}
	
	function pesquisaAjaxCliente($campo_a_pesquisar,$termo = null) {
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
				$condicoes = array('Cliente.id'=>$termo);
			}
			else {
				$condicoes = array("Cliente.$campo LIKE" => '%'.$termo.'%');
			}
			$this->Cliente->recursive = -1;
			$resultados = $this->Cliente->find('all',array('fields' => array('id','nome','situacao'),'conditions'=>$condicoes));
			if (!empty($resultados)) {
				foreach ($resultados as $r) {
					$retorno[$i]['label'] = $r['Cliente']['nome'];
					$retorno[$i]['value'] = $r['Cliente'][$campo];
					$retorno[$i]['id'] = $r['Cliente']['id'];
					$retorno[$i]['nome'] = $r['Cliente']['nome'];
					$retorno[$i]['bloqueado'] = ($r['Cliente']['situacao'] == 'B') ? 1 : 0;
					$retorno[$i]['inativo'] = ($r['Cliente']['situacao'] == 'I') ? 1 : 0;
					$retorno[$i]['situacao'] = $r['Cliente']['situacao'];
					$i++; 
				}
				print json_encode($retorno);
			}
		}
	}
	
	function RelClientesListagem() {
		$this->_obter_opcoes();
		$this->layout = 'relatorio1';
		$this->Cliente->recursive = -1;
		$consulta = $this->Cliente->find('all');
		$this->set('consulta',$consulta);
		unset($consulta);
	}

}

?>