<?php
class VendaPedido extends AppModel {
	var $name = 'VendaPedido';
	var $actsAs = array('CakePtbr.AjusteFloat','CakePtbr.AjusteData','Containable','Empresa');

	var $belongsTo = array(
		'Cliente' => array(
			'className' => 'Cliente',
			'foreignKey' => 'cliente_id'
		),
		'PagamentoTipo' => array(
			'className' => 'PagamentoTipo',
			'foreignKey' => 'pagamento_tipo_id'
		),
		'UsuarioCadastrou' => array(
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
	    'Vendedor' => array(
			'className' => 'Usuario',
			'foreignKey' => 'vendedor_id'
		),
	);
	
	var $hasMany = array(
		'VendaPedidoItem' => array(
			'className' => 'VendaPedidoItem',
			'foreignKey' => 'venda_pedido_id'
		),
		'CarregamentoItem' => array(
			'className' => 'CarregamentoItem',
			'foreignKey' => 'venda_pedido_id'
		)
	);
	
	var $validate = array(
		'cliente_id'  => array(
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
		'situacao' => array(
			'required' => 'true',
			'rule' => 'notEmpty',
			'message' => 'Campo obrigatório.'
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
		'data_venda' => array(
			'required' => 'true',
			'allowEmpty' => false,
			'rule' => 'date',
			'message' => 'Data inválida.'
		),
		'empresa_id' => array(
			'required' => 'true',
			'rule' => 'notEmpty',
			'message' => 'Campo obrigatório.'
		),
		'vendedor_id' => array(
			'required' => 'true',
			'rule' => 'notEmpty',
			'message' => 'Campo obrigatório.'
		),
	);
}
