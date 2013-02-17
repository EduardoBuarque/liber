<?php

class CompraPedido extends AppModel {
	var $name = 'CompraPedido';
	var $actsAs = array('CakePtbr.AjusteFloat','CakePtbr.AjusteData','Containable','Empresa');
	
	var $belongsTo = array(
		'Fornecedor' => array(
			'className' => 'Fornecedor',
			'foreignKey' => 'fornecedor_id'
		),
		'PagamentoTipo' => array(
			'className' => 'PagamentoTipo',
			'foreignKey' => 'pagamento_tipo_id'
		),
		'Comprador' => array(
			'className' => 'Usuario',
			'foreignKey' => 'comprador_id'
		),
		'Usuario' => array(
			'className' => 'Usuario',
			'foreignKey' => 'usuario_cadastrou'
		),
		'UsuarioAlterou' => array(
			'className' => 'Usuario',
			'foreignKey' => 'usuario_alterou'
		),
		'Empresa' => array(
			'className' => 'Empresa',
		),
	);
	
	var $hasMany = array(
		'CompraPedidoItem' => array(
			'className' => 'CompraPedidoItem',
			'foreignKey' => 'compra_pedido_id'
		),
	);
	
	var $validate = array(
		'situacao' => array(
			'rule' => 'notEmpty',
			'required' => true,
			'message' => 'Campo obrigatório.'
		),
		'fornecedor_id'  => array(
			'required' => 'true',
			'allowEmpty' => false,
			'rule' => 'numeric',
			'message' => 'Campo obrigatório. Somente números.'
		),
		'pagamento_tipo_id' => array(
			'required' => true,
			'allowEmpty' => false,
			'rule' => 'numeric',
			'message' => 'Campo obrigatório.'
		),
		'comprador_id' => array(
			'required' => 'true',
			'rule' => 'notEmpty',
			'message' => 'Campo obrigatório.'
		),
		'data_compra' => array(
			'required' => 'true',
			'allowEmpty' => false,
			'rule' => 'date',
			'message' => 'Data inválida.'
		),
		'data_saida' => array(
			'allowEmpty' => true,
			'rule' => 'date',
			'message' => 'Data inválida.'
		),
		'data_entrega' => array(
			'allowEmpty' => true,
			'rule' => 'date',
			'message' => 'Data inválida.'
		),
		'empresa_id' => array(
			'required' => 'true',
			'rule' => 'notEmpty',
			'message' => 'Campo obrigatório.'
		),
	);
	
	
}

