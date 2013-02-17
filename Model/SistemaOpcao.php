<?php

class SistemaOpcao extends AppModel {
	var $name = 'SistemaOpcao';
	var $belongsTo = array (
	    'ContaPlanoVendaPedido' => array (
		   'className' => 'ContaPlano',
		   'foreignKey' => 'item_conta_planos_venda_pedidos',
	    ),
	    'ContaPlanoOrdemServico' => array (
		   'className' => 'ContaPlano',
		   'foreignKey' => 'item_conta_planos_ordem_servicos',
	    ),
	);
	var $validate = array (
		'login_periodo_tentativas' => array(
			'obrigatorio' => array (
				'rule' => 'notEmpty',
				'message' => 'Campo obrigatório.'
			),
		    'numero' => array (
				'rule' => 'numeric',
				'message' => 'Número inválido.'
		    ),
		),
		'login_maximo_tentativas' => array(
			'obrigatorio' => array (
				'rule' => 'notEmpty',
				'message' => 'Campo obrigatório.'
			),
		    'numero' => array (
				'rule' => 'numeric',
				'message' => 'Número inválido.'
		    ),
		),
		'login_tempo_bloqueio' => array(
			'obrigatorio' => array (
				'rule' => 'notEmpty',
				'message' => 'Campo obrigatório.'
			),
		    'numero' => array (
				'rule' => 'numeric',
				'message' => 'Número inválido.'
		    ),
		)
	);
}

?>