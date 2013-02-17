<?php
class ServicoCategoria extends AppModel {
	var $name = 'ServicoCategoria';
	var $actsAs = array('Empresa');
	var $belongsTo = array(
	    'Empresa' => array(
			'className' => 'Empresa'
		),
	);
	var $hasMany = array(
		'Servico' => array(
			'className' => 'Servico',
			'foreignKey' => 'servico_categoria_id',
			/*'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''*/
		)
	);
	var $validate = array(
		'id' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
			),
		),
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
	    'empresa_id' => array(
			'allowEmpty' => false,
			'rule' => 'notEmpty',
			'message' => 'Campo obrigatório.'
		),
	);

}
