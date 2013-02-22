<?php
// app/Controller/AppController.php
App::uses('Controller', 'Controller');
class AppController extends Controller {
	
	// Componentes utilizados por toda a aplicação
	var $components = array('Session', 'Cookie','Auth','RequestHandler','DebugKit.Toolbar');
	// Helpers utilizados por toda a aplicacao
	//'Js' => array('Jquery')
	var $helpers = array('Session','Js',
		'Html' => array('className' => 'TwitterBootstrap.BootstrapHtml'),
		'Form' => array('className' => 'TwitterBootstrap.BootstrapForm'),
		'Paginator' => array('className' => 'TwitterBootstrap.BootstrapPaginator')
	);
	
	function beforeFilter() {
		
		// Parametros do AuthComponent
		// utilizado para autenticacao de usuarios
		$this->Auth->authenticate = array(
		     // define parametros para o metodo de autenticacao baseado em formulario
		    'Form' => array (
			   // model a ser utilizado pelo AuthComponent
			   'userModel' => 'Usuario',
			   // os campos que irão definir usuario e senha
			   'fields' => array ('username' => 'login', 'password' => 'senha'),
			   // Condicao de usuario ativo/valido (opcional)
			   'userScope' => array('Usuario.ativo' => true),
			)
		);
		// action da tela de login
		$this->Auth->loginAction = array ('controller' => 'usuarios','action' => 'login');
		// Para onde o usuario ira depois de fazer login e
		// sem ter url de referencia
		$this->Auth->loginRedirect = array ('controller' => 'sistema','action' => 'inicio');
		// Action para redirecionamento apos o logout
		$this->Auth->logoutRedirect = array( 'controller' => 'usuarios','action' => 'login');
		// Erro a exibir quando o usuário tenta acessar um objeto ou ação
		// para a qual eles não têm acesso.
		$this->Auth->authError = 'Você precisa fazer login para acessar o sistema';
		// View a ser renderizada quando uma chamada ajax está sendo feita e o 
		// usuario precisa logar
		// #TODO implementar
		//$this->Auth->ajaxLogin = '';
		
	}
	
	function beforeRender() {
		parent::beforeRender();
		// Se cliente nao estiver logado
		if ( ! $this->Auth->loggedIn() ) {
			/*
			* Altera o layout para todos os erros do Cake
			* http://groups.google.com/group/cake-php/browse_thread/thread/090eb7d3bbe179cb
			*/
			if ($this->name === 'CakeError') {
				$this->layout = 'erro';
			}
		}
	}
	
}


?>