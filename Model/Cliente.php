<?php

App::uses('BrValidation', 'Localized.Lib');

class Cliente extends AppModel {
	var $name = 'Cliente';
	var $actsAs = array('Containable','Empresa');
	var $belongsTo = array(
		'ClienteCategoria' => array(
			'className' => 'ClienteCategoria'
		),
		'Empresa' => array(
			'className' => 'Empresa'
		),
		'Usuario' => array(
			'className' => 'Usuario',
			'foreignKey' => 'usuario_cadastrou',
		),
		'Usuario2' => array(
			'className' => 'Usuario',
			'foreignKey' => 'usuario_alterou',
		)
	);
	var $hasMany = array(
		'ServicoOrdem' => array(
			'className' => 'ServicoOrdem'
		),
		'VendaPedido' => array(
			'className' => 'VendaPedido'
		)
	);
	var $validate = array(
		'tipo_pessoa' => array (
			'allowEmpty' => false,
			'rule' => array('inList', array('F','J')),
			'message' => 'Escolha uma das opções.'
		),
		
		'situacao' => array (
			'allowEmpty' => false,
			'rule' => array('inList', array('A','I','B')),
			'message' => 'Escolha uma das opções.'
		),
		
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
			),
		    /* 'caracteres' => array (
			   // se a expressao regular nao for verdadeira
			   'rule' => array('custom', '/[^0-9]/i'), 
			   'message' => 'Há caracteres inválidos.'
		    ),*/
		),
		
		'nome_fantasia' => array(
			'allowEmpty' => false,
			'rule' => 'notEmpty',
			'message' => 'Campo obrigatório.'
		),
		
		'logradouro_nome' => array(
			'allowEmpty' => false,
			'rule' => 'notEmpty',
			'message' => 'Campo obrigatório.'
		),
		
		'logradouro_numero' => array(
			'allowEmpty' => false,
			'rule' => 'notEmpty',
			'message' => 'Campo obrigatório.'
		),
		
		'bairro' => array(
			'rule' => 'notEmpty',
			'message' => 'Campo obrigatório.'
		),
		
		'cidade' => array(
			'allowEmpty' => false,
			'rule' => 'notEmpty',
			'message' => 'Campo obrigatório.'
		),
		
		'uf' => array(
			'rule' => 'notEmpty',
			'message' => 'Campo obrigatório.'
		),
		
		'cep' => array(
			'obrigatorio' => array (
				'rule' => 'notEmpty',
				'message' => 'Campo obrigatório.'
			),
			'valido' => array(
				'rule' => array('postal',null,'br'),
				'message' => 'CEP inválido.'
			),
		),
		
		'endereco_email' => array (
			'allowEmpty' => true,
			'rule' => 'email',
			'message' => 'Endereço de e-mail inválido.'
		),
		
		'cnpj' => array(
			'numerico' => array(
				'allowEmpty' => true,
				'rule' => 'numeric',
				'message' => 'Somente números.'
			),
			'unico' => array(
				'allowEmpty' => true,
				'rule' => 'isUnique',
				'message' => 'Já cadastrado.'
			),
		    'tamanho' => array(
			   'rule' => array('minLength', '14'),
			   'message' => 'São necessários 14 dígitios'
		    ),
		    'valido' => array(
			   'rule' => array('ssn', null,'br'),
			   'message' => 'CNPJ inválido.',
		    ),
		),
		
		'inscricao_estadual' => array(
			'numerico' => array(
				'allowEmpty' => true,
				'rule' => 'numeric',
				'message' => 'Somente números.'
			),
			'unico' => array(
				'allowEmpty' => true,
				'rule' => 'isUnique',
				'message' => 'Já cadastrado.'
			),
		    'tamanho' => array(
			   'rule' => array('minLength', '12'),
			   'message' => 'São necessários 12 dígitios'
		    )
		),
		
		'cpf' => array(
			'numerico' => array(
				'allowEmpty' => true,
				'rule' => 'numeric',
				'message' => 'Somente números.'
			),
			'unico' => array(
				'allowEmpty' => true,
				'rule' => 'isUnique',
				'message' => 'Já cadastrado.'
			),
		    'tamanho' => array(
			   'rule' => array('minLength', '11'),
			   'message' => 'São necessários 11 dígitios'
		    ),
		    'valido' => array(
			   'rule' => array('ssn', null,'br'),
			   'message' => 'CPF inválido.',
		    ),
		),
		
		'rg' => array(
			'alphanumerico' => array(
				'allowEmpty' => true,
				'rule' => 'alphanumeric',
				'message' => 'Somente letras e números.'
			),
			'unico' => array(
				'allowEmpty' => true,
				'rule' => 'isUnique',
				'message' => 'Já cadastrado.'
			)
		),
		'empresa_id' => array(
			'allowEmpty' => false,
			'rule' => 'notEmpty',
			'message' => 'Campo obrigatório.'
		),
		'cliente_categoria_id' => array(
			'allowEmpty' => false,
			'rule' => 'notEmpty',
			'message' => 'Campo obrigatório.'
		),
	);
	
	
}

?>
