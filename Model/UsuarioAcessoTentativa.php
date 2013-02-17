<?php

class UsuarioAcessoTentativa extends AppModel {
	var $name = 'UsuarioAcessoTentativa';
	var $validate = array(
		'data' => array(
			'rule' => 'notEmpty',
			'message' => 'Campo obrigatório.'
		),
		'hora' => array(
			'rule' => 'notEmpty',
			'message' => 'Campo obrigatório.'
		),
	);
}

?>