<?php

class ServicoOrdensController extends AppController {
	var $name = 'ServicoOrdens';
	var $components = array('RequestHandler','Geral','ContasReceber');
	var $helpers = array('CakePtbr.Estados','Ajax', 'Javascript','CakePtbr.Formatacao', 'Geral');
	
	/**
	 * Obtem dados necessarios ao decorrer deste controller.
	 * Os dados sao setados em variaveis a serem utilizadas nas views 
	 */
	function _obter_opcoes() {
		$this->ServicoOrdem->Usuario->recursive = -1;
		$consulta1 = $this->ServicoOrdem->Usuario->find('list',array('fields'=>array('Usuario.id','Usuario.nome'),
		'conditions'=>array('Usuario.eh_tecnico'=>'1','Usuario.ativo'=>'1')));
		$this->set('opcoes_tecnico',$consulta1);
		
		$this->ServicoOrdem->PagamentoTipo->recursive = -1;
		$consulta2 = $this->ServicoOrdem->PagamentoTipo->find('list',array('fields'=>array('PagamentoTipo.id','PagamentoTipo.nome')));
		$this->set('opcoes_forma_pamamento',$consulta2);
		
		$this->ServicoOrdem->Empresa->recursive = -1;
		$consulta3 = $this->ServicoOrdem->Empresa->findEmpresa();
		$this->set('opcoes_empresas',$consulta3);
		
		$situacoes = array (
			'O' => 'Orçamento',
			'S' => 'Em espera',
			'X' => 'Em execução',
			'F' => 'Finalizada',
			'E' => 'Entregue',
			'C' => 'Cancelada',
		);
		$this->set('opcoes_situacao',$situacoes);
	}
	
	function _obter_opcoes_pesquisa() {
		$this->ServicoOrdem->Usuario->recursive = -1;
		$consulta1 = $this->ServicoOrdem->Usuario->find('list',array('fields'=>array('Usuario.id','Usuario.nome'),
		'conditions'=>array('Usuario.eh_tecnico'=>'1','Usuario.ativo'=>'1')));
		$this->set('opcoes_tecnico',$consulta1);
		
		$consulta2 = $this->ServicoOrdem->Usuario->find('list',array('fields'=>array('Usuario.id','Usuario.nome'),
		'conditions'=>array('Usuario.ativo'=>'1')));
		$this->set('opcoes_usuarios',$consulta2);
		
		$situacoes = array (
			'O' => 'Orçamento',
			'S' => 'Em espera',
			'X' => 'Em execução',
			'F' => 'Finalizada',
			'E' => 'Entregue',
			'C' => 'Cancelada',
		);
		$this->set('opcoes_situacao',$situacoes);
	}
	
	/**
	* Recupero itens dinamicos que podem ter sido acrescentados a pagina
	*/
	function _recuperar_itens_dinamicos() {
		if ($this->request->data['ServicoOrdemItem']) {
			$itens = $this->request->data['ServicoOrdemItem'];
			$i = 0;
			$valor_total = 0;
			$campos_ja_inseridos = array();
			foreach ($itens as $item) {
				$this->ServicoOrdem->ServicoOrdemItem->Servico->recursive = -1;
				$ret = $this->ServicoOrdem->ServicoOrdemItem->Servico->findById($item['servico_id'],array('Servico.nome'));
				$campos_ja_inseridos[$i] = array('servico_id'=>$item['servico_id']);
				$campos_ja_inseridos[$i] += array('servico_nome'=>$ret['Servico']['nome']);
				$campos_ja_inseridos[$i] += array('quantidade'=>$item['quantidade']);
				$campos_ja_inseridos[$i] += array('valor'=>$item['valor']);
				$i++;
			}
			$this->set('campos_ja_inseridos',$campos_ja_inseridos);
		}
		
		if ($this->request->data['ServicoOrdem']['cliente_id']) {
			$this->ServicoOrdem->Cliente->recursive = -1;
			$clienteNome = $this->ServicoOrdem->Cliente->findById($this->request->data['ServicoOrdem']['cliente_id'],array('Cliente.nome'));
			$this->request->data['ServicoOrdem'] = array_merge($this->request->data['ServicoOrdem'],array('cliente_nome'=>$clienteNome['Cliente']['nome']));
		}
		
		return 1;
	}
	
	function _calcular_valores($data) {
		$valor_bruto = 0;
		$valor_liquido = 0;
		foreach ($data['ServicoOrdemItem'] as $c) {
			$valor_bruto += ($c['quantidade']) * ($this->Geral->moeda2numero($c['valor']));
		}
		// se ha outros custos, somo para obter o valor bruto
		if (isset($data['ServicoOrdem']['custo_outros']) && (! empty($data['ServicoOrdem']['custo_outros']))) {
			$valor_bruto = $valor_bruto + ($this->Geral->moeda2numero($data['ServicoOrdem']['custo_outros']));
		}
		$valor_liquido = $valor_bruto;
		// se ha desconto, subtraio para obter o valor liquido
		if (isset($data['ServicoOrdem']['desconto']) && (! empty($data['ServicoOrdem']['desconto']))) {
			$valor_liquido = $valor_liquido - ($this->Geral->moeda2numero($data['ServicoOrdem']['desconto']));
		}
		
		$retorno = array(
			'valor_bruto' => $valor_bruto,
			'valor_liquido' => $valor_liquido
		);
		
		return $retorno;
	}
	
	/**
	 * @see Component ContasReceber
	 */
	function _gerar_conta_receber($valor_total=null) {
		// Apenas crio a conta a receber se a situacao do serviço for Finalizado ou Entregue
		if (strtoupper($this->request->data['ServicoOrdem']['situacao']) == 'F' || strtoupper($this->request->data['ServicoOrdem']['situacao']) == 'E'  ) {
			$this->loadModel('SistemaOpcao');
			$dados = array_merge (
				array('valor_total'=>$valor_total),
				array('numero_documento'=>$this->ServicoOrdem->id),
				array('conta_plano_id'=>$this->SistemaOpcao->field('item_conta_planos_ordem_servicos', array('id'=>1))),
				$this->request->data['ServicoOrdem']
				);
			return $this->ContasReceber->gerarContaReceber($dados);
			
		}
		return true;
	}
	
	function index() {
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		$this->_obter_opcoes();
		$this->paginate = array (
			'limit' => 10,
			'order' => array (
				'ServicoOrdem.id' => 'desc'
			),
		     'contain' => array('Cliente.nome','Usuario.nome')
		);
		$dados = $this->paginate('ServicoOrdem');
		$this->set('consulta',$dados);
	}
	
	function cadastrar() {
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		$this->set("title_for_layout","Ordem de serviço"); 
		$this->_obter_opcoes();
		if (! empty($this->request->data)) {
			$this->_recuperar_itens_dinamicos();
			$this->ServicoOrdem->Cliente->recursive = -1;
			$r = $this->ServicoOrdem->Cliente->find('first',
				array('conditions'=>array(
					'Cliente.id' => $this->request->data['ServicoOrdem']['cliente_id'],
					'Cliente.situacao' => 'A')));
			if (empty($r)) {
				$this->Session->setFlash('Erro. Cliente não existe ou não está ativo.','flash_erro');
				return null;
			}
			$this->request->data['ServicoOrdem'] += array ('data_hora_cadastrada' => date('Y-m-d H:i:s'));
			$this->request->data['ServicoOrdem'] += array ('usuario_cadastrou' => $this->Auth->user('id'));
			$valores = $this->_calcular_valores($this->request->data);
			$valor_bruto = $valores['valor_bruto'];
			$valor_liquido = $valores['valor_liquido'];
			if ($valor_liquido <= 0) {
				$this->Session->setFlash('O valor total da ordem de serviço é R$ '.$this->Geral->numero2moeda($valor_liquido),'flash_erro');
				return null;
			}
			$this->request->data['ServicoOrdem'] += array ('valor_bruto' => $valor_bruto);
			$this->request->data['ServicoOrdem'] += array ('valor_liquido' => $valor_liquido);
			
			if (empty($this->request->data['ServicoOrdem']['data_hora_fim'])) $this->request->data['ServicoOrdem']['data_hora_fim'] = null;
			
			//Inicia uma transaction
			$this->ServicoOrdem->begin();
			
			if ($this->ServicoOrdem->saveAll($this->request->data,array('validate'=>'first'))) {
				if ( $this->_gerar_conta_receber($valor_liquido) !== true ) {
					$this->ServicoOrdem->rollback();
				}
				else {
					$this->ServicoOrdem->commit();
					$this->Session->setFlash('Ordem de serviço cadastrada com sucesso.','flash_sucesso');
					$this->redirect($this->referer(array('action' => 'index')));
				}
			}
			else {
				$this->Session->setFlash('Erro ao cadastrar a ordem de serviço.','flash_erro');
				$this->ServicoOrdem->rollback();
			}
		}
	}
	
	function editar($id=NULL) {
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		$this->set("title_for_layout","Ordem de serviço"); 
		$this->_obter_opcoes();
		if (empty ($this->request->data)) {
			$this->ServicoOrdem->contain('ServicoOrdemItem');
			$this->ServicoOrdem->id = $id;
			$this->request->data = $this->ServicoOrdem->read();
			if ( ! $this->request->data) {
				$this->Session->setFlash('Ordem de serviço não encontrada.','flash_erro');
				$this->redirect(array('action'=>'index'));
			}
			else $this->_recuperar_itens_dinamicos();
		}
		else {
			$this->ServicoOrdem->Cliente->recursive = -1;
			$r = $this->ServicoOrdem->Cliente->find('first',
				array('conditions'=>array(
					'Cliente.id' => $this->request->data['ServicoOrdem']['cliente_id'],
					'Cliente.situacao' => 'A')));
			if (empty($r)) {
				$this->Session->setFlash('Erro. Cliente não existe ou não está ativo.','flash_erro');
				return null;
			}
			//a ordem de serviço pode ser editada apenas se nao tiver sido cancelada ou entregue
			$this->ServicoOrdem->recursive = -1;
			$s = strtoupper($this->ServicoOrdem->field('situacao'));
			if ( ($s == 'E') || ($s == 'C') ) {
				$this->Session->setFlash('A situação desta ordem de serviço impede que seja editada','flash_erro');
				return false;
			}
			$this->_recuperar_itens_dinamicos();
			$this->request->data['ServicoOrdem']['id'] = $id;
			$this->request->data['ServicoOrdem'] += array ('usuario_alterou' => $this->Auth->user('id'));
			$valores = $this->_calcular_valores($this->request->data);
			$valor_bruto = $valores['valor_bruto'];
			$valor_liquido = $valores['valor_liquido'];
			if ($valor_liquido <= 0) {
				$this->Session->setFlash('O valor total da ordem de serviço é R$ '.$this->Geral->numero2moeda($valor_liquido),'flash_erro');
				return null;
			}
			$this->request->data['ServicoOrdem'] += array ('valor_bruto' => $valor_bruto);
			$this->request->data['ServicoOrdem'] += array ('valor_liquido' => $valor_liquido);
			
			if (empty($this->request->data['ServicoOrdem']['data_hora_fim'])) $this->request->data['ServicoOrdem']['data_hora_fim'] = null;
			
			//Inicia uma transaction
			$this->ServicoOrdem->begin();
			
			// #TODO seria bom nao deletar e reinserir todos os registros
			// deleto os itens que pertenciam a ordem de serviço
			if( ! ($this->ServicoOrdem->ServicoOrdemItem->deleteAll(array('servico_ordem_id'=>$id),false))) {
				$this->Session->setFlash('Erro ao salvar a ordem de serviço','flash_erro');
				$this->ServicoOrdem->rollback();
				return false;
			}
			// insiro o que foi enviado agora, inclusive os itens
			if ($this->ServicoOrdem->saveAll($this->request->data,array('validate'=>'first'))) {
				$s2 = $this->request->data['ServicoOrdem']['situacao'];
				if ($s2 == 'F' || $s2 == 'E') { //se a situacao for Finalizada ou Entregue
				$fim = $this->ServicoOrdem->field('data_hora_fim');
					if (empty($fim)) {
						// se a data final nao foi preenchida
						$this->ServicoOrdem->save(array('data_hora_fim'=>date('Y-m-d H:i:s')));
					}
				}
				if ( $this->_gerar_conta_receber($valor_liquido) !== true ) {
					$this->ServicoOrdem->rollback();
				}
				else {
					$this->ServicoOrdem->commit();
					$this->Session->setFlash('Ordem de serviço atualizada com sucesso.','flash_sucesso');
					$this->redirect(array('action'=>'index'));
				}
			}
			else {
				$this->Session->setFlash('Erro ao atualizar a ordem de serviço.','flash_erro');
				$this->ServicoOrdem->rollback();
			}
		}
	}
	
	function detalhar($id = null) {
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		$this->_obter_opcoes();
		$this->set("title_for_layout","Ordem de serviço");
		$this->ServicoOrdem->contain('Cliente.nome','PagamentoTipo.nome','ServicoOrdemItem');
		$consulta = $this->ServicoOrdem->findById($id);
		if (empty($consulta)) {
			$this->Session->setFlash('Ordem de serviço não encontrada','flash_erro');
			$this->redirect(array('action'=>'index'));
		}
		else {
			// adiciono, no array resultante, o nome do servico correspondente
			$this->loadModel('Servico');
			$i = 0;
			foreach ($consulta['ServicoOrdemItem'] as $x) {
				$nome = $this->Servico->field('nome',array('Servico.id'=>$x['servico_id']));
				$consulta['ServicoOrdemItem'][$i]['servico_nome'] = $nome;
				$i++;
			}
			$this->set('c',$consulta);
		}
	}

	function excluir($id=NULL) {
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		if (! empty($id)) {
			$this->ServicoOrdem->id = $id;
			$r = $this->ServicoOrdem->field('situacao');
			if (empty ($r)) {
				$this->Session->setFlash('Ordem de serviço não encontrada','flash_erro');
				$this->redirect(array('action'=>'pesquisar'));
				return false;
			}
			//Uma ordem de serviço apenas pode ser deletada se sua situacao for 'Orçamento' ou 'Em execução'
			$r = strtoupper($r);
			if ( ($r != 'O') && ($r != 'E') ) {
				$this->Session->setFlash('A situação da ordem de serviço impede a sua exclusão. Talvez você deva apenas cancelá-la','flash_erro');
				$this->redirect(array('action'=>'index'));
				return false;
			}
			if ($this->ServicoOrdem->ServicoOrdemItem->deleteAll(array('ServicoOrdemItem.servico_ordem_id'=>$id))) {
				if ($this->ServicoOrdem->delete($id)) {
					$this->Session->setFlash("Ordem de serviço número $id foi excluída com sucesso.",'flash_sucesso');
					$this->redirect(array('action'=>'index'));
				}
				else $this->Session->setFlash("Ordem de serviço $id não pode ser excluída",'flassh_erro');
			}
			else $this->Session->setFlash("Ordem de serviço número $id não pode ser excluída.",'flash_erro');
			$this->redirect(array('action'=>'pesquisar'));
		}
		else {
			$this->Session->setFlash('Ordem de serviço não informada.','flash_erro');
			$this->redirect(array('action'=>'pesquisar'));
		}
	}
	
	function pesquisar() {
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		$this->set("title_for_layout","Ordem de serviço");
		$this->_obter_opcoes_pesquisa();
		if (! empty($this->request->data)) {
			//usuario enviou os dados da pesquisa
			$url = array('controller'=>'ServicoOrdens','action'=>'pesquisar');
			// codificando os parametros
			if( is_array($this->request->data['ServicoOrdem']) ) {
				foreach($this->request->data['ServicoOrdem'] as $chave => &$item) {
					if (empty($item)) {
						unset($this->request->data['ServicoOrdem'][$chave]);
						continue;
					}
					// urlencode duas vezes para nao haver problema com / e \
					$item = htmlentities(urlencode(urlencode($item)));
				}
			}
			$params = array_merge($url,$this->request->data['ServicoOrdem']);
			$this->redirect($params);
		}
		
		if (! empty($this->request->params['named'])) {
			//a instrucao acima redirecionou para cá
			foreach ($this->request->params['named'] as &$valor) {
				$valor = html_entity_decode(urldecode(urldecode($valor)));
			}
			$dados = $this->request->params['named'];
			$condicoes=array();
			if (! empty($dados['id'])) $condicoes = array_merge($condicoes, array('ServicoOrdem.id'=>$dados['id']));
			if (! empty($dados['cliente_id'])) $condicoes = array_merge($condicoes, array('ServicoOrdem.cliente_id'=>$dados['cliente_id']));
			if (! empty($dados['cliente_nome'])) $condicoes = array_merge($condicoes, array('Cliente.nome LIKE'=>'%'.$dados['cliente_nome'].'%'));
			if (! empty($dados['tecnico'])) $condicoes = array_merge($condicoes, array('ServicoOrdem.usuario_id'=>$dados['tecnico']));
			if (! empty($dados['situacao'])) $condicoes = array_merge($condicoes, array('ServicoOrdem.situacao'=>$dados['situacao']));
			if (! empty($dados['valor_total'])) $condicoes = array_merge($condicoes, array('ServicoOrdem.valor_liquido'=>$dados['valor_total']));
			if (! empty($dados['usuario_cadastrou'])) $condicoes = array_merge($condicoes, array('ServicoOrdem.usuario_cadastrou'=>$dados['usuario_cadastrou']));
			// pesquiso todos os registros cadastrados entre o intervalo do dia informado pelo usuario
			if (! empty($dados['data_hora_cadastrada'])) $condicoes = array_merge($condicoes, array('ServicoOrdem.data_hora_cadastrada BETWEEN ? AND ?'=>array($dados['data_hora_cadastrada'].' 00:00:00',$dados['data_hora_cadastrada'].' 23:59:59')));
			// pesquiso todos os registros cadastrados entre o intervalo do dia informado pelo usuario
			if (! empty($dados['data_hora_inicio'])) $condicoes = array_merge($condicoes, array('ServicoOrdem.data_hora_inicio BETWEEN ? AND ?'=>array($dados['data_hora_inicio'].' 00:00:00',$dados['data_hora_inicio'].' 23:59:59')));
			// pesquiso todos os registros cadastrados entre o intervalo do dia informado pelo usuario
			if (! empty($dados['data_hora_fim'])) $condicoes = array_merge($condicoes, array('ServicoOrdem.data_hora_fim BETWEEN ? AND ?'=>array($dados['data_hora_fim'].' 00:00:00',$dados['data_hora_fim'].' 23:59:59')));
			if (! empty ($condicoes)) {
				$this->paginate = array(
				    'limit' => 10,
					'order' => array (
						'ServicoOrdem.id' => 'desc'
					),
					'contain' => array('Cliente.nome')
				);
				$resultados = $this->paginate('ServicoOrdem',$condicoes);
				if (! empty($resultados)) {
					$num_encontrados = count($resultados);
					$this->set('resultados',$resultados);
					$this->set('num_resultados',$num_encontrados);
					$this->Session->setFlash("Exibindo $num_encontrados ordem(ns) de serviço(s)",'flash_sucesso');
				}
				else $this->Session->setFlash("Nenhuma ordem de serviço encontrada",'flash_erro');
			}
			else {
				$this->set('num_resultados','0');
				$this->Session->setFlash("Informe algum campo para realizar a pesquisa",'flash_erro');
			}
		}
	}
	
	
}

?>