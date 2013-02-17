<?php

/**
 * Este é um model que pode vir a consumir muitos recursos, pois em diversos
 * outros model's ha um usuario associado.
 */

// Carrego o AuthComponent
//App::uses('AuthComponent', 'Controller/Component');
class Usuario extends AppModel {
	var $name = "Usuario";
	var $actsAs = array('Containable');
	var $hasMany = array(
		'Cliente_usuario_cadastrou' => array(
			'className' => 'Cliente',
			'foreignKey' => 'usuario_cadastrou'
		),
		'Cliente_usuario_alterou' => array(
			'className' => 'Cliente',
			'foreignKey' => 'usuario_alterou'
		),
		'Fornecedor_usuario_cadastrou' => array(
			'className' => 'Fornecedor',
			'foreignKey' => 'usuario_cadastrou'
		),
		'Fornecedor_usuario_alterou' => array(
			'className' => 'Fornecedor',
			'foreignKey' => 'usuario_alterou'
		),
		'Pedidos_usuario_cadastrou' => array(
			'className' => 'VendaPedido',
			'foreignKey' => 'usuario_cadastrou'
		),
		'Pedidos_usuario_alterou' => array(
			'className' => 'VendaPedido',
			'foreignKey' => 'usuario_alterou'
		),
		'ServicoOrdens_tecnico' => array(
			'className' => 'ServicoOrdem',
			'foreignKey' => 'usuario_id'
		),
		'ServicoOrdens_cadastrou' => array(
			'className' => 'ServicoOrdem',
			'foreignKey' => 'usuario_cadastrou'
		),
		'ServicoOrdens_alterou' => array(
			'className' => 'ServicoOrdem',
			'foreignKey' => 'usuario_alterou'
		),
		 'UsuarioAcessoLog_usuario' => array(
			'className' => 'UsuarioAcessoLog',
			'foreignKey' => 'usuario_id'
		),
		
	);
	var $belongsTo = array(
		'Grupo' => array(
			'className' => 'Grupo'
		),
		 'Empresa' => array(
			'className' => 'Empresa'
		),
	);
	var $validate = array(
		'nome' => array (
			'allowEmpty' => false,
			'rule' => 'notEmpty',
			'message' => 'Campo obrigatório.'
		),
		'login' => array(
			'alphanumerico' => array(
				'allowEmpty' => false,
				'rule' => 'alphaNumeric',
				'message' => 'Somente letras e números.'
			),
			'unico' => array(
				'allowEmpty' => false,
				'rule' => 'isUnique',
				'message' => 'Já cadastrado.'
			)
		),
		'senha' => array(
			 'on' => 'create',
			'allowEmpty' => false,
			'rule' => 'notEmpty',
			'message' => 'Campo obrigatório.'
		),
		'senha_confirma' => array(
			 'on' => 'create',
			'allowEmpty' => false,
			'rule' => 'notEmpty',
			'message' => 'Campo obrigatório.'
		),
		'email' => array(
			'allowEmpty' => true,
			'rule' => array('email'),
			'message' => 'E-mail inválido.'
		),
		'tipo' => array(
			'allowEmpty' => false,
			'rule' => array('inList', array('0','1','2','3','4','5')),
			'message' => 'Campo obrigatório.'
		),
		'ativo' => array(
			'allowEmpty' => true,
			'rule' => array('boolean'),
			'message' => 'Valor incorreto.'
		),
		 'empresa_id' => array(
			'allowEmpty' => false,
			'rule' => 'notEmpty',
			'message' => 'Campo obrigatório.'
		),
	);
	
	/**
	 * Cada vez que um um usuario for salvo, faz hash da senha dele, que sera
	 * gravada no banco
	 * @return boolean 
	 */
	/*public function beforeSave() {
		if (isset($this->data[$this->alias]['senha'])) {
			$this->data[$this->alias]['senha'] = AuthComponent::password($this->data[$this->alias]['senha']);
		}
	return true;
	 }*/
	 
	 function findAtivo($tipo='list',$opcoes=array()) {
		$opcoesPadrao = array (
			'fields' => array('Usuario.id','Usuario.nome'),
			'conditions' => array('Usuario.ativo'=>'1'),
			'recursive' => -1,
		);
		$opcoes = array_merge($opcoesPadrao,$opcoes);
		return $this->find($tipo,$opcoes);
	 }
	 
	 function findTecnico($tipo='list',$opcoes=array()) {
		$opcoesPadrao = array (
			'fields' => array('Usuario.id','Usuario.nome'),
			'conditions' => array('Usuario.ativo'=>'1','Usuario.eh_tecnico'=>'1'),
			'recursive' => -1,
		);
		$opcoes = array_merge($opcoesPadrao,$opcoes);
		return $this->find($tipo,$opcoes);
	 }
	 
	 function findVendedor($tipo='list',$opcoes=array()) {
		$opcoesPadrao = array (
			'fields' => array('Usuario.id','Usuario.nome'),
			'conditions' => array('Usuario.ativo'=>'1','Usuario.eh_vendedor'=>'1'),
			'recursive' => -1,
		);
		$opcoes = array_merge($opcoesPadrao,$opcoes);
		return $this->find($tipo,$opcoes);
	 }
	 
	 function findComprador($tipo='list',$opcoes=array()) {
		 return $this->findAtivo($tipo, $opcoes);
	 }
}

?>