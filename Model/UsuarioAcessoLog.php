<?php

class UsuarioAcessoLog extends AppModel {
	var $name = 'UsuarioAcessoLog';
	var $belongsTo = array(
		'Usuario' => array(
			'className' => 'usuario',
			'foreignKey' => 'usuario_id'
		),
	);
	var $validate = array(
		'data_login' => array(
			'rule' => 'notEmpty',
			'message' => 'Campo obrigatório.'
		),
		'hora_login' => array(
			'rule' => 'notEmpty',
			'message' => 'Campo obrigatório.'
		),
		'usuario_id' => array(
			'rule' => 'notEmpty',
			'message' => 'Campo obrigatório.'
		)
	);
}

?>