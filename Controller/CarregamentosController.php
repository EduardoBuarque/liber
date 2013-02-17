<?php

/**
 * 
 * #TODO No nomento o usuario marca o carregamento como enviado, quando houver
 * a rotina de faturamento, ao faturar será marcado como enviado
 */

class CarregamentosController extends AppController {
	var $name = 'Carregamentos';
	var $components = array('ContasReceber','Geral','RequestHandler');
	var $helpers = array('CakePtbr.Formatacao','Javascript','Ajax');
	var $paginate = array (
		'limit' => 10,
		'order' => array (
			'Carregamento.id' => 'desc'
		),
	    'contain' => array(),
	);
	
        /**
         * Obtem dados necessarios ao decorrer deste controller.
         * Os dados sao setados em variaveis a serem utilizadas nas views
         */
	function _obter_opcoes() {
		$this->Carregamento->Motorista->recursive = -1;
		$motoristas = $this->Carregamento->Motorista->find('list',array('fields'=>array('Motorista.id','Motorista.nome')));
		$this->Carregamento->Veiculo->recursive = -1;
		$veiculos = $this->Carregamento->Veiculo->find('list',array('fields'=>array('Veiculo.id','Veiculo.placa')));
		$this->set('opcoes_motoristas',$motoristas);
		$this->set('opcoes_veiculos',$veiculos);
		$this->set('opcoes_situacoes',array ('L' => 'Livre','E' => 'Enviado'));
	}
	
	function index() {
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		$this->_obter_opcoes();
		$dados = $this->paginate('Carregamento');
		$this->set('consulta',$dados);
	}
	
	function cadastrar() {
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		if (! empty($this->request->data)) {
			$this->request->data['Carregamento'] += array ('data_hora_criado' => date('Y-m-d H:i:s'));
			$this->request->data['Carregamento'] += array ('situacao' => 'L');
			
			$this->Carregamento->begin();
			
			if ($this->Carregamento->saveAll($this->request->data,array('validate'=>'first'))) {
				// atualiza a situacao dos pedidos para Travado
				foreach ($this->request->data['CarregamentoItem'] as $item) {
					$this->Carregamento->CarregamentoItem->VendaPedido->id = $item['venda_pedido_id'];
					$situacao_venda_pedido = $this->Carregamento->CarregamentoItem->VendaPedido->field('situacao');
					if (strtoupper($situacao_venda_pedido) != 'A') {
						$this->Session->setFlash("O pedido de venda ".$item['venda_pedido_id']." está sendo adicionado ao 
						carregamento mas sua situação não é 'Aberto'. Outro usuário pode ter editado o pedido de venda a
						pouco tempo.",'flash_erro');
						$this->Carregamento->rollback();
						break;
					}
					$r = $this->Carregamento->CarregamentoItem->VendaPedido->save(array('VendaPedido'=>array('situacao'=>'T')));
					if (! $r) {
						$this->Session->setFlash('Erro ao atualizar os itens do carregamento','flash_erro');
						$this->Carregamento->rollback();
						break;
					}
				}
				
				$this->Carregamento->commit();
				$this->Session->setFlash('Carregamento cadastrado com sucesso.','flash_sucesso');
				$this->redirect($this->referer(array('action' => 'index')));
			}
			else {
				$this->Session->setFlash('Erro ao cadastrar o carregamento.','flash_erro');
				$this->Carregamento->rollback();
			}
		}
		else {
			// O carregamento será montado com os pedidos que estao em aberto
			$consulta = $this->Carregamento->CarregamentoItem->VendaPedido->find('all',array('conditions'=>array('VendaPedido.situacao'=>'A')));
			$this->set('consulta_pedidos',$consulta);
			$this->_obter_opcoes();
		}
	}
	
	
	function excluir($id=NULL) {
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		if (! empty($id)) {
			$this->Carregamento->id = $id;
			
			$this->Carregamento->recursive = -1;
			$carregamento = $this->Carregamento->read();
			if (! $carregamento) {
				$this->Session->setFlash('Carregamento não encontrado','flash_erro');
				$this->redirect(array('action'=>'index'));
				return null;
			}
			
			if (strtoupper($carregamento['Carregamento']['situacao']) != 'L') {
				$this->Session->setFlash('A situação do carregamento impede que seja excluído','flash_erro');
				$this->redirect(array('action'=>'index'));
				return NULL;
			} 
			
			if (empty($carregamento['CarregamentoItem'])) {
				$this->Session->setFlash('Carregamento não possui itens!','flash_erro');
				$this->redirect(array('action'=>'index'));
				return NULL;
			}
			$this->Carregamento->begin();
			// atualiza a situacao dos pedidos para Aberto
			foreach ($carregamento['CarregamentoItem'] as $item) {
				$this->Carregamento->CarregamentoItem->VendaPedido->id = $item['id'];
				$r = $this->Carregamento->CarregamentoItem->VendaPedido->save(array('VendaPedido'=>array('situacao'=>'A')));
				if (! $r) {
					$this->Session->setFlash('Erro ao atualizar os itens do carregamento','flash_erro');
					$this->Carregamento->rollback();
					break;
				}
			}
			
			if ($this->Carregamento->CarregamentoItem->deleteAll(array('CarregamentoItem.carregamento_id'=>$id))) {
				if ($this->Carregamento->delete($id)) {
					$this->Session->setFlash("Carregamento $id excluído com sucesso.",'flash_sucesso');
					$this->Carregamento->commit();
				}
			}
			else {
				$this->Carregamento->rollback();
				$this->Session->setFlash("Carregamento $id não pode ser excluído.",'flash_erro');
			}
			$this->redirect(array('action'=>'index'));
		}
		else {
			$this->Session->setFlash('Carregamento não informado.','flash_erro');
		}
	}
	
	function pesquisar() {
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		$this->_obter_opcoes();
		if (! empty($this->request->data)) {
			//usuario enviou os dados da pesquisa
			$url = array('controller'=>'Carregamentos','action'=>'pesquisar');
			//convertendo alguns caracteres
			if( is_array($this->request->data['Carregamento']) ) {
				foreach($this->request->data['Carregamento'] as $chave => &$item) {
					if (empty($item)) {
						unset($this->request->data['Carregamento'][$chave]);
						continue;
					}
					// urlencode duas vezes para nao haver problema com / e \
					$item = htmlentities(urlencode(urlencode($item)));
				}
				$params = array_merge($url,$this->request->data['Carregamento']);
			}
			$this->redirect($params);
		}
		
		if (! empty($this->request->params['named'])) {
			//a instrucao acima redirecionou para cá
			foreach ($this->request->params['named'] as &$valor) {
				$valor = html_entity_decode(urldecode(urldecode($valor)));
			}
			$dados = $this->request->params['named'];
			$condicoes=array();
			if (! empty($dados['data_inicial'])) $condicoes = array_merge($condicoes, array('Carregamento.data_hora_criado >='=>$dados['data_inicial'].' 00:00:00'));
			if (! empty($dados['data_final']))	$condicoes = array_merge($condicoes, array('Carregamento.data_hora_criado <='=>$dados['data_final'].' 00:00:00'));
			if (! empty($dados['situacao'])) $condicoes = array_merge($condicoes, array('Carregamento.situacao'=>$dados['situacao']));
			if (! empty($dados['descricao'])) $condicoes = array_merge($condicoes, array('Carregamento.descricao'=>$dados['descricao']));
			if (! empty($dados['motorista'])) $condicoes = array_merge($condicoes, array('Motorista.id'=>$dados['motorista']));
			if (! empty($dados['veiculo'])) $condicoes = array_merge($condicoes, array('Veiculo.id'=>$dados['veiculo']));
			if (! empty ($condicoes)) {
				$resultados = $this->paginate('Carregamento',$condicoes);
				if (! empty($resultados)) {
					$num_encontrados = count($resultados);
					$this->set('resultados',$resultados);
					$this->set('num_resultados',$num_encontrados);
					$this->Session->setFlash("Exibindo $num_encontrados carregamento(s)",'flash_sucesso');
				}
				else $this->Session->setFlash("Nenhum carregamento encontrado",'flash_erro');
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
			$this->Carregamento->id = $id;
			$r = $this->Carregamento->read();
			if (empty($r)) {
				$this->Session->setFlash("Carregamento $id não encontrado",'flash_erro');
				$this->redirect(array('action'=>'index'));
			}
			else $this->set('carregamento',$r);
		}
		else {
			$this->Session->setFlash('Erro: nenhum carregamento informado.','flash_erro');
			$this->redirect(array('action'=>'index'));
		}
	}
	
	function enviar() {
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		if (empty($this->request->data)) {
			
		}
		else {
			$id=$this->request->data['Carregamento']['id'];
			
			$carregamento = $this->Carregamento->find('first',array('conditions'=>array('Carregamento.id'=>$id)));
			
			if (! $carregamento) {
				$this->Session->setFlash("Carregamento $id não encontrado.",'flash_erro');
				return false;
			}
			if (strtoupper($carregamento['Carregamento']['situacao']) != 'L' ) {
				$this->Session->setFlash("A situação do carregamento $id impede que seja enviado.",'flash_erro');
				return false;
			}
			
			// marco o(s) pedido(s) com a situacao vendido
			// futuramente, quando houver faturamento, os pedidos serao marcados
			// apenas depois de faturados
			$dados = array(
				'VendaPedido' => array(
					'situacao' => 'V'
				)
			);
			
			$this->Carregamento->begin();
			foreach ($carregamento['CarregamentoItem'] as $c) {
				
				// gera conta a receber
				$venda_pedido = $this->Carregamento->CarregamentoItem->VendaPedido->find('first',
					array('conditions'=>array('VendaPedido.id'=>$c['venda_pedido_id']),'recursive'=>'-1' ) );
				$dados_conta_receber = array_merge (
					// quando o valor é recuperado do banco ele vem em formato pt-br. Converto para formato americano
					array('valor_total'=>$this->Geral->moeda2numero($venda_pedido['VendaPedido']['valor_liquido'])),
					array('numero_documento'=>$c['venda_pedido_id']),
					$venda_pedido['VendaPedido']
				);
				if ($this->ContasReceber->gerarContaReceber($dados_conta_receber) !== true) {
					$this->Session->setFlash("Erro ao gerar a conta a receber para o pedido ".$c['venda_pedido_id'].". Operação abortada",'flash_erro');
					$this->Carregamento->rollback();
					break;
				}
				
				// atualiza a situacao do pedido de venda
				$this->Carregamento->CarregamentoItem->VendaPedido->id = $c	['venda_pedido_id'];
				if (! $this->Carregamento->CarregamentoItem->VendaPedido->save($dados) ) {
					$this->Session->setFlash("Erro ao atualizar a situação do pedido de venda ".$c['venda_pedido_id'],'flash_erro');
					$this->Carregamento->rollback();
					break;
				}
			} //fim do loop
			$this->Carregamento->id = $id;
			$this->Carregamento->save(array('Carregamento'=>array('situacao'=>'E')));
			$this->Carregamento->commit();
			$this->Session->setFlash("Operação finalizada com sucesso para o carregamento $id.",'flash_sucesso');
			$this->request->data=NULL;
		}
		
	}
	
}

?>