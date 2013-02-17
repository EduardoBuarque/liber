<?php
class CompraPedidoItem extends AppModel {
	var $name = 'CompraPedidoItem';
	var $actsAs = array('CakePtbr.AjusteFloat');

	var $belongsTo = array (
		'CompraPedido' => array(
			'className' => 'CompraPedido',
			'foreignKey' => 'compra_pedido_id'
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
		'preco_custo' => array(
			'rule' => 'notEmpty',
			'message' => 'Campo obrigatório.'
		)
	);
}
