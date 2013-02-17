<?php
class VendaPedidoItem extends AppModel {
	var $name = 'VendaPedidoItem';
	var $actsAs = array('CakePtbr.AjusteFloat');

	var $belongsTo = array(
		'VendaPedido' => array(
			'className' => 'VendaPedido',
			'foreignKey' => 'venda_pedido_id'
		),
		'Produto' => array(
			'className' => 'Produto',
			'foreignKey' => 'produto_id'
		)
	);
	
	var $validate = array (
		'produto_id' => array(
			'allowEmpty' => false,
			'rule' => 'numeric',
			'message' => 'Campo obrigatório. Somente números.'
		),
		'quantidade' => array(
			'rule' => 'notEmpty',
			'message' => 'Campo obrigatório.'
		),
		'preco_venda' => array(
			'rule' => 'notEmpty',
			'message' => 'Campo obrigatório.'
		)
	);
}
