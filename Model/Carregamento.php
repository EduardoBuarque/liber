<?php

class Carregamento extends AppModel {
	var $name = 'Carregamento';
	var $actsAs = array('Empresa');
	var $hasMany = array('CarregamentoItem');
	var $belongsTo = array(
		'Motorista' => array(
			'className' => 'motorista',
			'foreignKey' => 'motorista_id'
		),
		'Veiculo' => array(
			'className' => 'veiculo',
			'foreignKey' => 'veiculo_id'
		),
	    'Empresa' => array(
			'className' => 'Empresa'
		),
	);
	var $validate = array(
		'descricao' => array(
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
		'motorista_id' => array(
			'rule' => 'notEmpty',
			'message' => 'Campo obrigatório.'
		),
		'veiculo_id' => array(
			'rule' => 'notEmpty',
			'message' => 'Campo obrigatório.'
		),
	    'empresa_id' => array(
			'allowEmpty' => false,
			'rule' => 'notEmpty',
			'message' => 'Campo obrigatório.'
		),
	);
}

?>