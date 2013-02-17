<?php

class Produto extends AppModel {
	var $name = 'Produto';
	var $actsAs = array('CakePtbr.AjusteFloat','Empresa');
	var $belongsTo = array('ProdutoCategoria','Empresa');
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
		'produto_categoria_id' => array(
			'rule' => 'notEmpty',
			'message' => 'Campo obrigatório.'
		),
		'tipo_produto' => array(
			'rule' => 'notEmpty',
			'message' => 'Campo obrigatório.'
		),
		'codigo_ean' => array(
			'allowEmpty' => true,
			'rule' => 'numeric',
			'message' => 'Campo obrigatório. Somente números.'
		),
		'codigo_dun' => array(
			'allowEmpty' => true,
			'rule' => 'numeric',
			'message' => 'Campo obrigatório. Somente números.'
		),
		'preco_custo' => array(
			'rule' => 'notEmpty',
			'message' => 'Campo obrigatório.'
		),
		'preco_venda' => array(
			'rule' => 'notEmpty',
			'message' => 'Campo obrigatório.'
		),
		'margem_lucro' => array(
			'rule' => 'notEmpty',
			'message' => 'Campo obrigatório.'
		),
		'tem_estoque_ilimitado' => array(
			'rule' => 'notEmpty',
			'message' => 'Campo obrigatório.'
		),
		'estoque_minimo' => array(
			'allowEmpty' => true,
			'rule' => 'numeric',
			'message' => 'Campo obrigatório. Somente números.'
		),
		'unidade' => array(
			'rule' => 'notEmpty',
			'message' => 'Campo obrigatório.'
		),
		'quantidade_estoque_fiscal' => array(
			'rule' => 'notEmpty',
			'message' => 'Campo obrigatório.'
		),
		'quantidade_estoque_nao_fiscal' => array(
			'rule' => 'notEmpty',
			'message' => 'Campo obrigatório.'
		),
		'situacao' => array(
			'rule' => 'notEmpty',
			'message' => 'Campo obrigatório.'
		),
	    'empresa_id' => array(
			'allowEmpty' => false,
			'rule' => 'notEmpty',
			'message' => 'Campo obrigatório.'
		),
	);
	
	function findAtivo($tipo='list',$opcoes=array()) {
	    $opcoesPadrao = array (
			'fields' => array('Produto.id','Produto.nome'),
			'conditions' => array('Fornecedor.situacao'=>'L'),
			'recursive' => -1
	    );
		$opcoes = array_merge($opcoesPadrao,$opcoes);
		return $this->find($tipo,$opcoes);
	}
}

?>