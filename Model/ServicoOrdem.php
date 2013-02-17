<?php
class ServicoOrdem extends AppModel {
	var $name = 'ServicoOrdem';
	var $actsAs = array('CakePtbr.AjusteFloat','Containable','Empresa');
	var $belongsTo = array(
		'Cliente' => array(
			'className' => 'Cliente',
			'foreignKey' => 'cliente_id'
		),
		'Usuario' => array(
			'className' => 'Usuario',
			'foreignKey' => 'usuario_id'
		),
		'PagamentoTipo' => array(
			'className' => 'PagamentoTipo',
			'foreignKey' => 'pagamento_tipo_id',
		),
		'usuario_cadastrou' => array(
			'className' => 'Usuario',
			'foreignKey' => 'usuario_cadastrou'
		),
		'usuario_alterou' => array(
			'className' => 'Usuario',
			'foreignKey' => 'usuario_alterou'
		),
		'Empresa' => array(
			'className' => 'Empresa',
		)
	);
	var $hasMany = array(
		'ServicoOrdemItem' => array(
			'className' => 'ServicoOrdemItem',
			'foreignKey' => 'servico_ordem_id'
			//'dependent' => true
		)
	);
	var $validate = array(
		'cliente_id'  => array(
			'required' => true,
			'allowEmpty' => false,
			'rule' => 'numeric',
			'message' => 'Campo obrigatório. Somente números.'
		),
		'usuario_id' => array(
			'allowEmpty' => false,
			'required' => true,
			'rule' => 'numeric',
			'message' => 'Campo obrigatório.'
		),
		'pagamento_tipo_id' => array(
			'required' => true,
			'rule' => 'notEmpty',
			'message' => 'Campo obrigatório.'
		),
		'situacao' => array(
			'required' => true,
			'rule' => 'notEmpty',
			'message' => 'Campo obrigatório.'
		),
		'data_hora_inicio' => array(
			'required' => true,
			'rule' => 'notEmpty',
			'message' => 'Campo obrigatório.'
		),
		'empresa_id' => array(
			'rule' => 'notEmpty',
			'message' => 'Campo obrigatório.'
		),
	);
	

}
