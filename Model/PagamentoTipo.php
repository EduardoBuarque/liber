<?php

class PagamentoTipo extends AppModel {
	var $name='PagamentoTipo';
	var $actsAs = array('Containable','Empresa');
	var $belongsTo = array (
		'Conta' => array (
			'className' => 'Conta',
			'foreignKey' => 'conta_principal'
		),
		'DocumentoTipo' => array(
		    'className' => 'DocumentoTipo',
		    'foreignKey' => 'documento_tipo_id'
		),
	    'Empresa' => array(
			'className' => 'Empresa'
		),
	);
	var $hasMany = array (
		'PagamentoTipoItem' => array(
			'class_name' => 'PagamentoTipoItem',
		),
		'ServicoOrdem' => array(
			'className' => 'ServicoOrdem'
		),
		'VendaPedido' => array(
			'className' => 'VendaPedido'
		)
	);
	var $validate = array (
		'nome' => array (
			'rule' => 'notEmpty',
			'message' => 'Campo obrigatório.'
		),
		'conta_principal' => array(
			'rule' => 'notEmpty',
			'message' => 'Campo obrigatório.'
		),
		'documento_tipo_id' => array(
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