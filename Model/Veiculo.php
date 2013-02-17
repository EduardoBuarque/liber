<?php

class Veiculo extends AppModel {
	var $name = 'Veiculo';
	var $actsAs = array('Containable','Empresa');
	var $belongsTo = array(
	    'Empresa' => array(
			'className' => 'Empresa'
		),
	);
	var $hasMany = array(
		'Motorista' => array(
			'className' => 'motorista',
			'foreignKey' => 'veiculo_padrao'
		),
		'Carregamento' => array (
			'className' => 'carregamento',
			'foreignKey' => 'veiculo_id'
		)
	);
	var $validate = array(
		'modelo' => array(
			'rule' => 'notEmpty',
			'message' => 'Campo obrigatório.'
		),
		'placa' => array(
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
		'ano' => array(
			'allowEmpty' => true,
			'rule' => 'numeric',
			'message' => 'Somente números.'
		),
	    'empresa_id' => array(
			'allowEmpty' => false,
			'rule' => 'notEmpty',
			'message' => 'Campo obrigatório.'
		),
	);
}

?>