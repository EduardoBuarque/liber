<?php

class ProdutosController extends AppController {
	var $name = 'Produtos';
	var $components = array('RequestHandler');
	var $helpers = array('Ajax', 'Javascript');

	/**
	 * Obtem dados necessarios ao decorrer deste controller.
	 * Os dados sao setados em variaveis a serem utilizadas nas views 
	 */
	function _obter_opcoes() {
		$this->loadModel('ProdutoCategoria');
		$this->ProdutoCategoria->recursive = -1;
		$consulta1 = $this->ProdutoCategoria->find('list',array('fields'=>array('ProdutoCategoria.id','ProdutoCategoria.nome')));
		$this->set('opcoes_categoria_produto',array_merge(array(0=>''),$consulta1));
		$opcoes_tipos = array (
			'V' => 'Para venda',
			'M' => 'Matéria prima',
			//'A' => 'Matéria prima e venda',
			//'C' => 'Produto composto',
		);
		$this->set('opcoes_tipos',$opcoes_tipos);
		$opcoes_situacoes = array('E'=>'Em linha','F'=>'Fora de linha');
		$this->set('opcoes_situacoes',$opcoes_situacoes);
	}
	
	function index() {
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		$this->paginate = array (
			'limit' => 10,
			'order' => array (
				'Produto.id' => 'desc'
			),
		    'contain' => array()
		);
		$dados = $this->paginate('Produto');
		$this->set('consulta',$dados);
	}
	
	function cadastrar() {
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		$this->_obter_opcoes();
		if (! empty($this->request->data)) {
			if ($this->request->data['Produto']['produto_categoria_id'] == 0) $this->request->data['Produto']['produto_categoria_id'] = null;
			if ($this->Produto->save($this->request->data)) {
				$this->Session->setFlash('Produto cadastrado com sucesso.','flash_sucesso');
				$this->redirect($this->referer(array('action' => 'index')));
			}
			else {
				$this->Session->setFlash('Erro ao cadastrar o produto.','flash_erro');
			}
		}
	}
	
	function editar($id=NULL) {
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		$this->_obter_opcoes();
		if (empty ($this->request->data)) {
			$this->Produto->recursive = -1;
			$this->Produto->id = $id;
			$this->request->data = $this->Produto->read();
			if ( ! $this->request->data) {
				$this->Session->setFlash("Produto $id não encontrado.",'flash_erro');
				$this->redirect(array('action'=>'index'));
			}
		}
		else {
			$this->request->data['Produto']['id'] = $id;
			if ($this->request->data['Produto']['produto_categoria_id'] == 0) $this->request->data['Produto']['produto_categoria_id'] = null;
			if ($this->Produto->save($this->request->data)) {
				$this->Session->setFlash("Produto $id atualizado com sucesso.",'flash_sucesso');
				$this->redirect(array('action'=>'index'));
			}
			else {
				$this->Session->setFlash('Erro ao atualizar o produto.','flash_erro');
			}
		}
	}
	
	function excluir($id=NULL) {
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		if (! empty($id)) {
			if ($this->Produto->delete($id)) $this->Session->setFlash("Produto $id excluído com sucesso.",'flash_sucesso');
			else $this->Session->setFlash("Produto $id não pode ser excluído.",'flash_erro');
			$this->redirect(array('action'=>'index'));
		}
		else {
			$this->Session->setFlash('Produto não informado.','flash_erro');
		}
	}

	function pesquisar() {
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		$this->_obter_opcoes();
		if (! empty($this->request->data)) {
			//usuario enviou os dados da pesquisa
			$url = array('action'=>'pesquisar');
			// codificando os parametros
			if( is_array($this->request->data['Produto']) ) {
				foreach($this->request->data['Produto'] as $chave => &$item) {
					if (empty($item)) {
						unset($this->request->data['Produto'][$chave]);
						continue;
					}
					// urlencode duas vezes para nao haver problema com / e \
					$item = htmlentities(urlencode(urlencode($item)));
				}
			}
			$params = array_merge($url,$this->request->data['Produto']);
			$this->redirect($params);
		}
		
		if (! empty($this->request->params['named'])) {
			//a instrucao acima redirecionou para cá
			foreach ($this->request->params['named'] as &$valor) {
				$valor = html_entity_decode(urldecode(urldecode($valor)));
			}
			$dados = $this->request->params['named'];
			$condicoes=array();
			if (! empty($dados['nome'])) $condicoes = array_merge($condicoes, array('Produto.nome LIKE'=>'%'.$dados['nome'].'%'));
			if (! empty($dados['produto_categoria_id'])) $condicoes = array_merge($condicoes, array('Produto.produto_categoria_id'=>$dados['produto_categoria_id']));
			if (! empty($dados['tipo_produto'])) $condicoes = array_merge($condicoes, array('Produto.tipo_produto'=>$dados['tipo_produto']));
			if (! empty($dados['codigo_ean'])) $condicoes = array_merge($condicoes, array('Produto.codigo_ean'=>$dados['codigo_ean']));
			if (! empty($dados['codigo_dun'])) $condicoes = array_merge($condicoes, array('Produto.codigo_dun'=>$dados['codigo_dun']));
			if (! empty($dados['unidade'])) $condicoes = array_merge($condicoes, array('Produto.unidade'=>$dados['unidade']));
			if (! empty($dados['quantidade_estoque_fiscal'])) $condicoes = array_merge($condicoes, array('Produto.quantidade_estoque_fiscal'=>$dados['quantidade_estoque_fiscal']));
			if (! empty($dados['situacao'])) $condicoes = array_merge($condicoes, array('Produto.situacao'=>$dados['situacao']));
			if (! empty ($condicoes)) {
				$this->paginate = array (
					'limit' => 10,
					'order' => array (
						'Produto.id' => 'desc'
					),
				'contain' => array()
				);
				$resultados = $this->paginate('Produto',$condicoes);
				if (! empty($resultados)) {
					$num_encontrados = count($resultados);
					$this->set('resultados',$resultados);
					$this->set('num_resultados',$num_encontrados);
					$this->Session->setFlash("Exibindo $num_encontrados produto(s)",'flash_sucesso');
				}
				else $this->Session->setFlash("Nenhum produto encontrado",'flash_erro');
			}
			else {
				$this->set('num_resultados','0');
				$this->Session->setFlash("Nenhum resultado encontrado",'flash_erro');
			}
		}
	}

	function pesquisaAjaxProduto($campo_a_pesquisar,$termo = null) {
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
				$condicoes = array('Produto.id'=>$termo);
			}
			else {
				$condicoes = array("Produto.$campo LIKE" => '%'.$termo.'%');
			}
			$this->Produto->recursive = -1;
			$resultados = $this->Produto->find('all',array('conditions'=>$condicoes));
			if (!empty($resultados)) {
				foreach ($resultados as $r) {
					$retorno[$i]['label'] = $r['Produto']['nome'];
					$retorno[$i]['value'] = $r['Produto'][$campo];
					$retorno[$i]['id'] = $r['Produto']['id'];
					$retorno[$i]['nome'] = $r['Produto']['nome'];
					$retorno[$i]['eh_vendido'] = ($r['Produto']['tipo_produto'] != 'M') ? 1 : 0;
					$retorno[$i]['tipo_produto'] = $r['Produto']['tipo_produto'];
					$retorno[$i]['fora_de_linha'] = ($r['Produto']['situacao'] == 'F') ? 1 : 0;
					$retorno[$i]['situacao'] = $r['Produto']['situacao'];
					$retorno[$i]['preco_custo'] = $r['Produto']['preco_custo'];
					$retorno[$i]['preco_venda'] = $r['Produto']['preco_venda'];
					$retorno[$i]['estoque_disponivel'] = $r['Produto']['quantidade_estoque_fiscal'] - $r['Produto']['quantidade_reservada'];
					$retorno[$i]['tem_estoque_ilimitado'] = $r['Produto']['tem_estoque_ilimitado'];
					$i++; 
				}
				print json_encode($retorno);
			}
		}
	}
	
}

?>