<?php

class ContaPagar extends AppModel {
	var $name = 'ContaPagar';
	var $actsAs = array('CakePtbr.AjusteFloat','CakePtbr.AjusteData' => 'data_vencimento','Containable','Empresa');
	var $useTable = 'conta_pagar';
	var $belongsTo = array(
		'ContaPlano' => array(
			'className' => 'ContaPlano'
		),
		'Conta' => array(
			'className' => 'Conta',
			'foreignKey' => 'conta_origem'
		),
		'DocumentoTipo' => array(
			'className' => 'DocumentoTipo'
		),
		'Cliente' => array(
			'className' => 'Cliente',
			'foreignKey' => 'cliente_fornecedor_id'
		),
		'Fornecedor' => array(
			'className' => 'Fornecedor',
			'foreignKey' => 'cliente_fornecedor_id'
		),
		'Empresa' => array(
			'className' => 'Empresa'
		)
	);
	var $validate = array(
		'eh_fiscal' => array(
			'rule' => 'notEmpty',
			'message' => 'Campo obrigatório.'
		),
		'eh_cliente_ou_fornecedor' => array(
			'rule' => 'notEmpty',
			'message' => 'Campo obrigatório.'
		),
		'cliente_fornecedor_id' => array(
			'rule' => 'notEmpty',
			'message' => 'Campo obrigatório.'
		),
		'documento_tipo_id' => array(
			'rule' => 'notEmpty',
			'message' => 'Campo obrigatório.'
		),
		'valor' => array(
			'rule' => 'notEmpty',
			'message' => 'Campo obrigatório.'
		),
		'conta_origem' => array(
			'rule' => 'notEmpty',
			'message' => 'Campo obrigatório.'
		),
		'conta_plano_id' => array(
			'rule' => 'notEmpty',
			'message' => 'Campo obrigatório.'
		),
		'data_vencimento' => array(
			'obrigatorio' => array (
				'rule' => 'notEmpty',
				'message' => 'Campo obrigatório.'
			),
			'data' => array (
				'rule' => 'date',
				'message' => 'Data inválida.'
			)
		),
		'empresa_id' => array(
			'rule' => 'notEmpty',
			'message' => 'Campo obrigatório.'
		),
		'situacao' => array(
			'rule' => 'notEmpty',
			'message' => 'Campo obrigatório.'
		)
	);
}

?>