<?php
class ContaPlano extends  Model {
	var $name = 'ContaPlano';
	var $actsAs = array('Empresa','Containable');
	var $belongsTo = array(
	    'Empresa' => array(
			'className' => 'Empresa'
		),
	);
	var $hasMany = array(
	    'ContaPagar' => array(
		   'className' => 'ContaPagar',
	    ),
	    'ContaReceber' => array (
		   'className' => 'ContaReceber',
	    ),
	    'SistemaOpcaoVendaPedido' => array (
		   'className' => 'SistemaOpcao',
		   'foreignKey' => 'item_conta_planos_venda_pedidos',
	    ),
	    'SistemaOpcaoOrdemServico' => array (
		   'className' => 'SistemaOpcao',
		   'foreignKey' => 'item_conta_planos_ordem_servicos',
	    ),
	);
	var $validate = array(
		'nome' => array(
			'obrigatorio' => array (
				'rule' => 'notEmpty',
				'message' => 'Campo obrigatório.'
			),
			'unico' => array(
				'allowEmpty' => false,
				'rule' => 'isUnique',
				'message' => 'Já cadastrado.'
			)
		),
		'tipo' => array (
			'rule' => 'notEmpty',
			'message' => 'Campo obrigatório.'
		),
	    'empresa_id' => array (
			'allowEmpty' => false,
			'rule' => 'notEmpty',
			'message' => 'Campo obrigatório.'
		),
	);
	
}

?>