<?php

class Grupo extends AppModel {
	var $name = 'Grupo';
	var $actsAs = array('Empresa');
	var $hasMany = array(
		'Usuario' => array (
			'className' => 'Usuario',
			'foreignKey' => 'grupo_id'
		),
	);
	var $belongsTo = array(
	    'Empresa' => array(
			'className' => 'Empresa'
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
			),
		    'empresa_id' => array(
				'allowEmpty' => false,
				'rule' => 'notEmpty',
				'message' => 'Campo obrigatório.'
			),
		),
	);
}

?>