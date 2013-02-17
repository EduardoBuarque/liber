<?php

class ContaReceberController extends AppController {
	var $name = 'ContaReceber';
	var $helpers = array('CakePtbr.Formatacao','Javascript','Jqplot');
	
	/**
	 * Obtem dados necessarios ao decorrer deste controller.
	 * Os dados sao setados em variaveis a serem utilizadas nas views 
	 */
	function _obter_opcoes() {
		$this->ContaReceber->DocumentoTipo->recursive = -1;
		$consulta1 = $this->ContaReceber->DocumentoTipo->find('list',array('fields'=>array('DocumentoTipo.id','DocumentoTipo.nome')));
		$this->set('opcoes_documento_tipo',$consulta1);
		
		$this->ContaReceber->Conta->recursive = -1;
		$consulta2 = $this->ContaReceber->Conta->find('list',array('fields'=>array('Conta.id','Conta.apelido')));
		$this->set('opcoes_conta_origem',$consulta2);
		
		$this->ContaReceber->ContaPlano->recursive = -1;
		$consulta3 = $this->ContaReceber->ContaPlano->find('list',array('fields'=>array('ContaPlano.id','ContaPlano.nome')));
		$this->set('opcoes_conta_planos',$consulta3);
		
		$this->ContaReceber->Empresa->recursive = -1;
		$consulta4 = $this->ContaReceber->Empresa->findEmpresa();
		$this->set('opcoes_empresas',$consulta4);
		
		$opcoes_situacoes = array (
			'N' => 'Não paga',
			'P' => 'Paga',
		);
		$this->set('opcoes_situacoes',$opcoes_situacoes);
		
		return null;
	}
	
	function _recuperar_itens_dinamicos() {
		if ($this->request->data['ContaReceber']['cliente_fornecedor_id']) {
			if (strtoupper($this->request->data['ContaReceber']['eh_cliente_ou_fornecedor']) == 'C') {
				$this->ContaReceber->Cliente->recursive = -1;
				$clienteNome = $this->ContaReceber->Cliente->findById($this->request->data['ContaReceber']['cliente_fornecedor_id'],array('Cliente.nome'));
				$clienteFornecedorNome = $clienteNome['Cliente']['nome'];
			}
			else if (strtoupper($this->request->data['ContaReceber']['eh_cliente_ou_fornecedor']) == 'F') {
				$this->ContaReceber->Fornecedor->recursive = -1;
				$fornecedorNome = $this->ContaReceber->Fornecedor->findById($this->request->data['ContaReceber']['cliente_fornecedor_id'],array('Fornecedor.nome'));
				$clienteFornecedorNome = $fornecedorNome['Fornecedor']['nome'];
			}
			$this->request->data['ContaReceber'] = array_merge ($this->request->data['ContaReceber'], array('pesquisar_cliente_fornecedor'=>$clienteFornecedorNome) );
		}
	}
	
	function index() {
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		$this->paginate = array (
		    'limit' => 10,
			'order' => array (
				'ContaReceber.id' => 'desc'
			),
		    'contain' => array('DocumentoTipo.nome','Conta.nome','ContaPlano.nome','Empresa.nome','Cliente.nome','Fornecedor.nome'),
		);
		$dados = $this->paginate('ContaReceber');
		$this->set('consulta_conta_receber',$dados);
		$this->_obter_opcoes();
		
	}
	
	function cadastrar() {
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		$this->_obter_opcoes();
		if (! empty($this->request->data)) {
			$this->_recuperar_itens_dinamicos();
			if (strtoupper($this->request->data['ContaReceber']['eh_cliente_ou_fornecedor']) == 'C') {
				$this->ContaReceber->Cliente->recursive = -1;
				$r = $this->ContaReceber->Cliente->find('first',
					array('conditions'=>array(
						'Cliente.id' => $this->request->data['ContaReceber']['cliente_fornecedor_id'],
						'Cliente.situacao' => 'A')));
				if (! empty($r)) $cliente_fornecedor_encontrado = true;
			}
			else if (strtoupper($this->request->data['ContaReceber']['eh_cliente_ou_fornecedor']) == 'F') {
				$this->ContaReceber->Fornecedor->recursive = -1;
				$r = $this->ContaReceber->Fornecedor->find('first',
					array('conditions'=>array(
						'Fornecedor.id' => $this->request->data['ContaReceber']['cliente_fornecedor_id'],
						'Fornecedor.situacao' => 'A')));
				if (! empty($r)) $cliente_fornecedor_encontrado = true;
			}
			if ((! isset($cliente_fornecedor_encontrado)) || (! $cliente_fornecedor_encontrado)) {
				$this->Session->setFlash('Erro. Cliente/fornecedor não encontrado','flash_erro');
			}
			
			else {
				$this->request->data['ContaReceber'] += array ('data_hora_cadastrada' => date('Y-m-d H:i:s'));
				
				if ($this->ContaReceber->save($this->request->data)) {
					$this->Session->setFlash('Conta a receber cadastrada com sucesso.','flash_sucesso');
					$this->redirect($this->referer(array('action' => 'index')));
				}
				else {
					$this->Session->setFlash('Erro ao cadastrar a conta a receber.','flash_erro');
				}
			}
		}
	}
	
	function editar($id=null) {
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		$this->_obter_opcoes();
		if (empty ($this->request->data)) {
			$this->ContaReceber->contain('DocumentoTipo.nome','Conta.nome','ContaPlano.nome','Empresa.nome','Cliente.nome','Fornecedor.nome');
			$this->ContaReceber->id = $id;
			$this->request->data = $this->ContaReceber->read();
			if ( ! $this->request->data) {
				$this->Session->setFlash('Conta a receber não encontrada.','flash_erro');
				$this->redirect(array('action'=>'index'));
			}
			else {
				$this->_recuperar_itens_dinamicos();
				$this->request->data['ContaReceber']['data_vencimento'] = date('d/m/Y', strtotime($this->request->data['ContaReceber']['data_vencimento']));
			}
		}
		else {
			$this->_recuperar_itens_dinamicos();
			$this->request->data['ContaReceber']['id'] = $id;
			if (strtoupper($this->request->data['ContaReceber']['eh_cliente_ou_fornecedor']) == 'C') {
				$this->ContaReceber->Cliente->recursive = -1;
				$r = $this->ContaReceber->Cliente->find('first',
					array('conditions'=>array(
						'Cliente.id' => $this->request->data['ContaReceber']['cliente_fornecedor_id'],
						'Cliente.situacao' => 'A')));
				if (! empty($r)) $cliente_fornecedor_encontrado = true;
			}
			else if (strtoupper($this->request->data['ContaReceber']['eh_cliente_ou_fornecedor']) == 'F') {
				$this->ContaReceber->Fornecedor->recursive = -1;
				$r = $this->ContaReceber->Fornecedor->find('first',
					array('conditions'=>array(
						'Fornecedor.id' => $this->request->data['ContaReceber']['cliente_fornecedor_id'],
						'Fornecedor.situacao' => 'A')));
				if (! empty($r)) $cliente_fornecedor_encontrado = true;
			}
			if ((! isset($cliente_fornecedor_encontrado)) || (! $cliente_fornecedor_encontrado)) {
				$this->Session->setFlash('Erro. Cliente/fornecedor não encontrado','flash_erro');
			}
			
			else {
				if ($this->ContaReceber->save($this->request->data)) {
					$this->Session->setFlash('Conta a receber atualizada com sucesso.','flash_sucesso');
					$this->redirect(array('action'=>'index'));
				}
				else {
					$this->Session->setFlash('Erro ao atualizar a conta a receber.','flash_erro');
				}
			}
		}
	}
	
	function excluir($id=NULL) {
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		if (! empty($id)) {
			if ($this->ContaReceber->delete($id)) $this->Session->setFlash("Conta a receber $id excluída com sucesso.",'flash_sucesso');
			else $this->Session->setFlash("Conta a receber $id não pode ser excluída.",'flash_erro');
			$this->redirect(array('action'=>'index'));
		}
		else {
			$this->Session->setFlash('Conta a receber não informada.','flash_erro');
		}
	}

	function pesquisar() {
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		$this->_obter_opcoes();
		if (! empty($this->request->data)) {
			//usuario enviou os dados da pesquisa
			$url = array('controller'=>'contaReceber','action'=>'pesquisar');
			// codificando os parametros
			if( is_array($this->request->data['ContaReceber']) ) {
				foreach($this->request->data['ContaReceber'] as $chave => &$item) {
					if (empty($item)) {
						unset($this->request->data['ContaReceber'][$chave]);
						continue;
					}
					// urlencode duas vezes para nao haver problema com / e \
					$item = htmlentities(urlencode(urlencode($item)));
				}
			}
			$params = array_merge($url,$this->request->data['ContaReceber']);
			$this->redirect($params);
		}
		
		if (! empty($this->request->params['named'])) {
			//a instrucao acima redirecionou para cá
			foreach ($this->request->params['named'] as &$valor) {
				$valor = html_entity_decode(urldecode(urldecode($valor)));
			}
			$dados = $this->request->params['named'];
			$condicoes=array();
			if (! empty($dados['numero_documento'])) $condicoes = array_merge($condicoes, array('ContaReceber.numero_documento'=>$dados['numero_documento']));
			if (! empty($dados['valor'])) $condicoes = array_merge($condicoes, array('ContaReceber.valor'=>$dados['valor']));
			if (! empty($dados['eh_cliente_ou_fornecedor'])) $condicoes = array_merge($condicoes, array('ContaReceber.eh_cliente_ou_fornecedor'=>$dados['eh_cliente_ou_fornecedor']));
			if (! empty($dados['cliente_fornecedor_id'])) $condicoes = array_merge($condicoes, array('ContaReceber.cliente_fornecedor_id'=>$dados['cliente_fornecedor_id']));
			if (! empty($dados['id'])) $condicoes = array_merge($condicoes, array('ContaReceber.id'=>$dados['id']));
			if (! empty($dados['documento_tipo'])) $condicoes = array_merge($condicoes, array('ContaReceber.documento_tipo'=>$dados['documento_tipo']));
			if (! empty($dados['conta_origem'])) $condicoes = array_merge($condicoes, array('ContaReceber.conta_origem'=>$dados['conta_origem']));
			if (! empty($dados['conta_plano_id'])) $condicoes = array_merge($condicoes, array('ContaReceber.id'=>$dados['conta_plano_id']));
			if (! empty($dados['situacao'])) $condicoes = array_merge($condicoes, array('ContaReceber.situacao'=>$dados['situacao']));
			if (! empty($dados['data_inicio'])) $condicoes = array_merge($condicoes, array('ContaReceber.data_hora_cadastrada >='=> $dados['data_inicio'].' 00:00:00'));
			if (! empty($dados['data_fim'])) $condicoes = array_merge($condicoes, array('ContaReceber.data_hora_cadastrada <='=> $dados['data_fim'].' 00:00:00')); 
			if (! empty ($condicoes)) {
				$this->paginate = array (
				'limit' => 10,
					'order' => array (
						'ContaReceber.id' => 'desc'
					),
				'contain' => array('DocumentoTipo.nome','Conta.nome','ContaPlano.nome','Cliente.nome','Fornecedor.nome'),
				);
				$resultados = $this->paginate('ContaReceber',$condicoes);
				if (! empty($resultados)) {
					$num_encontrados = count($resultados);
					$this->set('resultados',$resultados);
					$this->set('num_resultados',$num_encontrados);
					$this->Session->setFlash("Exibindo  $num_encontrados conta(s) a receber",'flash_sucesso');
				}
				else $this->Session->setFlash("Nenhuma conta a receber encontrada",'flash_erro');
			}
			else {
				$this->set('num_resultados','0');
				$this->Session->setFlash("Nenhum resultado encontrado",'flash_erro');
			}
		}
	}

	function resumo() {
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		//grafico de percentual do uso dos itens do plano de contas
		$consulta = $this->ContaReceber->query("SELECT conta_receber.conta_plano_id,COUNT(*) AS numero_ocorrencias, conta_plano.nome
		FROM conta_receber AS conta_receber, conta_planos AS conta_plano
		WHERE conta_receber.conta_plano_id = conta_plano.id
		GROUP BY conta_receber.conta_plano_id
		ORDER BY numero_ocorrencias DESC;");
		$retorno = array();
		foreach ($consulta as $r) {
			$retorno[] = array($r['conta_plano']['nome'],$r[0]['numero_ocorrencias']) ; 
		}
		$retorno = json_encode($retorno,JSON_NUMERIC_CHECK);
		$this->set('data1',$retorno);
		
		//grafico de itens do plano de contas ordenados por valor
		$consulta = $this->ContaReceber->query("SELECT conta_receber.conta_plano_id,sum(valor) AS valores, conta_plano.nome
		FROM conta_receber AS conta_receber, conta_planos AS conta_plano
		WHERE conta_receber.conta_plano_id = conta_plano.id
		GROUP BY conta_receber.conta_plano_id
		ORDER BY valores DESC;");
		$retorno = array();
		foreach ($consulta as $r) {
			$retorno[] = array($r['conta_plano']['nome'],$r[0]['valores']) ; 
		}
		$retorno = json_encode($retorno,JSON_NUMERIC_CHECK);
		$this->set('data2',$retorno);
		
		// grafico de clientes/fornecedores com mais contas a receber
		// #FIXME está exibindo apenas clientes
		$consulta = $this->ContaReceber->query("SELECT conta_receber.cliente_fornecedor_id, count(conta_receber.cliente_fornecedor_id) AS contador, conta_receber.eh_cliente_ou_fornecedor,
		case conta_receber.eh_cliente_ou_fornecedor
			when 'C' then clientes.nome
			when 'F' then fornecedores.nome
		end AS nome
		FROM conta_receber, clientes,fornecedores
		WHERE conta_receber.cliente_fornecedor_id = clientes.id
		GROUP BY conta_receber.eh_cliente_ou_fornecedor
		ORDER BY contador DESC");
		$retorno = array();
		foreach ($consulta as $r) {
			$retorno[] = array($r[0]['nome'],$r[0]['contador']) ; 
		}
		$retorno = json_encode($retorno,JSON_NUMERIC_CHECK);
		$this->set('data3',$retorno);
		
		// grafico de clientes/fornecedores com mais contas a receber vencidas
		// #FIXME está exibindo apenas clientes
		$consulta = $this->ContaReceber->query("SELECT conta_receber.cliente_fornecedor_id, count(conta_receber.cliente_fornecedor_id) AS contador, conta_receber.eh_cliente_ou_fornecedor,
		case conta_receber.eh_cliente_ou_fornecedor
			when 'C' then clientes.nome
			when 'F' then fornecedores.nome
		end AS nome
		FROM conta_receber, clientes,fornecedores
		WHERE conta_receber.cliente_fornecedor_id = clientes.id
		AND conta_receber.data_vencimento > '".date('m-d-Y')."'
		AND conta_receber.situacao = 'N'
		GROUP BY conta_receber.eh_cliente_ou_fornecedor
		ORDER BY contador DESC");
		$retorno = array();
		foreach ($consulta as $r) {
			$retorno[] = array($r[0]['nome'],$r[0]['contador']) ; 
		}
		$retorno = json_encode($retorno,JSON_NUMERIC_CHECK);
		$this->set('data4',$retorno);
	}
	
}

?>