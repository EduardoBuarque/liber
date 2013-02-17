<?php

/**
 * Componente para executar funcoes relacionadas as 'Contas a receber'
 *
 * @author tobias
 */
class ContasReceberComponent extends Component {

	function __construct(ComponentCollection $collection, $settings = array()) {
		parent::__construct($collection, $settings);
	}
	
	var $components = array('Session');
	
	/**
	 * Gera conta a receber
	 * Executar depois de se ter os dados prontos para serem inseridos no banco
	 * 
	 * @param $dados (array) conteudo de $this->request->data['Modelo'] mais $dados['numero_documento'],$dados['valor_total'], este ultimo em formato americano
	 * @param se $dados['conta_plano_id'] estiver vazio, será utilizado o valor de 'item_conta_planos_venda_pedidos' do model SistemaOpcao
	 * 
	 * @return false em caso de erros de validação. Seta mensagem no flash
	 * @return null em casos de argumentos faltantes. Não seta mensagem no flash
	 * @return true em caso de sucesso. Não seta mensagem no flash
	 * @return Dados já são inseridos no banco
	 */
	function gerarContaReceber ($dados=array()) {
		if (empty($dados)) return null;
		$padrao = array ();
		
		if ( empty($dados['conta_plano_id']) ) {
			$SistemaOpcao = ClassRegistry::init('SistemaOpcao'); // carrega model
			$dados['conta_plano_id'] = $SistemaOpcao->field('item_conta_planos_venda_pedidos', array('id'=>1));
		}
		
		$dados = array_merge($padrao,$dados);
		$valor_liquido = abs($dados['valor_total']);
		
		$PagamentoTipo = ClassRegistry::init('PagamentoTipo'); // carrega model
		$PagamentoTipoItem = ClassRegistry::init('PagamentoTipoItem');
		$pagamento_tipo = $PagamentoTipo->find('all',array('conditions'=>array('PagamentoTipo.id' => $dados['pagamento_tipo_id']),'recursive'=>'-1') );
		if (empty($pagamento_tipo)) {
			$this->Session->setFlash('Forma de pagamento não encontrada!','flash_erro');
			return false;
		}
		$pagamento_tipo = $pagamento_tipo[0]['PagamentoTipo'];
		// sao duas consultas pois a primeira estava retonando resultados desnecessarios
		$pagamento_tipo_itens = $PagamentoTipoItem->find('all',array('conditions'=>array('PagamentoTipoItem.pagamento_tipo_id' => $dados['pagamento_tipo_id']),'recursive'=>'-1') );
		
		$numero_parcelas = count($pagamento_tipo_itens);
		$valor_a_receber = $valor_liquido / $numero_parcelas;
		$valor_a_receber = number_format($valor_a_receber,2,'.','');
		if ($valor_a_receber <= 0) {
			$this->Session->setFlash('Valor da conta a receber é menor que zero!','flash_erro');
			return false;
		}
		$conta_receber['ContaReceber'] = array();
		$i = 0;
		// para cada parcela
		foreach ($pagamento_tipo_itens as $parcela) {
			$parcela = $parcela['PagamentoTipoItem'];
			$conta =  array (
					($i) => array (
						'data_hora_cadastrada' => date('Y-m-d H:i:s'),
						'eh_fiscal' => 0, //#TODO mudar quando houver nota fiscal e/ou uma abrangencia fiscal maior
						'eh_cliente_ou_fornecedor' => 'C',
						'cliente_fornecedor_id' => $dados['cliente_id'],
						'documento_tipo_id' => $pagamento_tipo['documento_tipo_id'],
						'numero_documento' => $dados['numero_documento'],
						'valor' => $valor_a_receber,
						'conta_origem' => $pagamento_tipo['conta_principal'],
						'conta_plano_id' => $dados['conta_plano_id'],
						'data_vencimento' => date("Y-m-d",time()+3600*24*($parcela['dias_intervalo_parcela'])),
						'situacao' => 'N',
						'empresa_id' => AuthComponent::user('empresa_id'),
					),
				);
				$conta_receber['ContaReceber'] = array_merge($conta_receber['ContaReceber'],$conta);
				$i++;
		}
		
		$ContaReceber = ClassRegistry::init('ContaReceber'); // carrega model
		if (! ($ContaReceber->saveAll($conta_receber['ContaReceber']))) {
			$this->Session->setFlash('Erro ao gerar a conta a receber.','flash_erro');
			return false;
		}
		
		return true;
		
	}
	
	
}

?>
