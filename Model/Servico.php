<?php
class Servico extends AppModel {
	var $name = 'Servico';
	var $actsAs = array('CakePtbr.AjusteFloat','Containable','Empresa');
	var $belongsTo = array(
		'ServicoCategoria' => array(
			'className' => 'ServicoCategoria',
			'foreignKey' => 'servico_categoria_id'
		),
	    'Empresa' => array(
			'className' => 'Empresa'
		),
	);
	var $hasMany = array(
		'ServicoOrdemItem' => array(
			'className' => 'ServicoOrdemItem',
			'foreignKey' => 'servico_id',
		)
	);
	var $validate = array(
		'nome' => array(
			'obrigatorio' => array (
				'allowEmpty' => false,
				'rule' => 'notEmpty',
				'message' => 'Campo obrigatório.'
			),
			'unico' => array(
				'allowEmpty' => false,
				'rule' => 'isUnique',
				'message' => 'Já cadastrado.'
			)
		),
		'servico_categoria_id' => array(
			'obrigatorio' => array (
				'required'   => true,
				'allowEmpty' => false,
				'rule' => 'notEmpty',
				'message' => 'Campo obrigatório.'
			),
		),
		'valor' => array(
			'obrigatorio' => array (
				'allowEmpty' => false,
				'rule' => 'notEmpty',
				'message' => 'Campo obrigatório.'
			),
			'numerico' => array(
				'rule' => array('numeric')
			)
		),
	    'empresa_id' => array(
			'allowEmpty' => false,
			'rule' => 'notEmpty',
			'message' => 'Campo obrigatório.'
		),
	);

}
