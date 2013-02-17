<?php
//#TODO datas estao no formato do MySQL, generalizar, tornando possivel mudar facilmente o formato
// Definir timezone da aplicaçao
// date_default_timezone_set('America/Sao_Paulo');

class UsuariosController extends AppController {
	var $name = 'Usuarios';
	var $components = array('Auth','RequestHandler');
	var $helpers = array('Javascript','Ajax');
	var $paginate = array (
		'limit' => 10,
		'contain' => array(),
		'order' => array (
			'Usuario.id' => 'asc'
		),
	    'contain' => array()
	);
	
	public function beforeFilter() {
			parent::beforeFilter();
		}
	
	public function _obter_opcoes() {
		$this->Usuario->Grupo->recursive = -1;
		$grupos = $this->Usuario->Grupo->find('list',array('fields'=>array('Grupo.id','Grupo.nome')));
		$this->set('opcoes_grupos',$grupos);
		$this->set('opcoes_empresas',$this->Usuario->Empresa->findEmpresa());
	}
	
	public function login() {
		$this->layout = 'login';
		$this->loadModel('SistemaOpcao');
		$this->loadModel('UsuarioAcessoTentativa');
		// Autenticacao apenas via POST
		if (! $this->request->is('post')) {
			return NULL;
		}
		if (empty($this->request->data['Usuario']['login'])) return NULL;
		if (empty($this->request->data['Usuario']['senha'])) return NULL;
		$clienteNome = NULL;
		if ( ! empty($_SERVER['REMOTE_HOST']) ) $clienteNome = $_SERVER['REMOTE_HOST'];
		$opcoesLogin = $this->SistemaOpcao->find('first',array(
			'fields'=>array(
				'login_periodo_tentativas',
				'login_maximo_tentativas',
				'login_tempo_bloqueio',
		)));
		$opcoesLogin = $opcoesLogin['SistemaOpcao'];
		$tempoBloqueio = strtotime("-".$opcoesLogin['login_tempo_bloqueio']." min");
		$consultaBloqueio = $this->UsuarioAcessoTentativa->find('count',array (
				'conditions' => array (
				    'cliente_ip' => $_SERVER['REMOTE_ADDR'],
					'bloqueado' => 1,
					'data_hora >=' => date('Y-m-d H:i:s', $tempoBloqueio),
				)
			)
		);
		if (! empty($consultaBloqueio)) {
			$this->Session->setFlash(__("Desculpe, você foi bloqueado por excesso de tentativas de acesso ao sistema.<br/>
				Caso queira realizar uma nova tentativa, aguarde e tente novamente."));
			unset ($this->request->data['Usuario']['senha']);
			return NULL;
		}
		// consulta se usuario/senha existem na base
		$consultaUsuario = $this->Usuario->find('first',
				array('conditions'=>array(
					'Usuario.login' => $this->request->data['Usuario']['login'],
					'Usuario.senha' => $this->Auth->password($this->request->data['Usuario']['senha']),
					),'recursive'=>'-1'));
		// A combinação de usuário e senha não estavam corretos
		if ( empty($consultaUsuario) ) {
			// Gravo tentativa de acesso
			$dadosTentativaAcesso = array (
			    'UsuarioAcessoTentativa' => array (
				   'data_hora' => date('Y-m-d H:i:s'),
				   'login' => $this->request->data['Usuario']['login'],
				   'cliente_ip' => $_SERVER['REMOTE_ADDR'],
				   'cliente_nome' => $clienteNome,
				   'cliente_user_agent' => $_SERVER['HTTP_USER_AGENT'],
				   'servidor_ip' => $_SERVER['SERVER_ADDR'],
				   'servidor_nome' => $_SERVER['SERVER_NAME'],
			    )
			);
			$this->UsuarioAcessoTentativa->save($dadosTentativaAcesso);
			// Consulto quantas tentativas restam e se é necessario fazer o bloqueio
			//#TODO verificar uso quando ha virada do dia
			$numeroTentativas = $this->UsuarioAcessoTentativa->find('count',array (
					'conditions' => array (
					    'cliente_ip' => $_SERVER['REMOTE_ADDR'],
						'data_hora >=' => date('Y-m-d H:i:s', $tempoBloqueio),
					)
				)
			);
			if ($numeroTentativas >= $opcoesLogin['login_maximo_tentativas']) {
				// atualiza o ultimo registro, o id do registro foi definido pelo Cake ao fazer o ultimo save
				$this->UsuarioAcessoTentativa->save(array('UsuarioAcessoTentativa'=>array('bloqueado'=>1)));
				$this->Session->setFlash(__("Usuário e/ou senha incorreto(s). Acesso bloqueado."));
			}
			else {
				$this->Session->setFlash(__("Usuário e/ou senha incorreto(s). ".
					   ($opcoesLogin['login_maximo_tentativas'] - $numeroTentativas)." tentativas restantes."));
			}
			unset ($this->request->data['Usuario']['senha']);
			return NULL;
		}
		
		/** Usuário pode logar **/
		
		unset($consultaUsuario['Usuario']['senha']);
		$this->Usuario->Empresa->id = $consultaUsuario['Usuario']['empresa_id'];
		$consultaUsuario['Usuario'] = array_merge(
			   $consultaUsuario['Usuario'],array('empresa_nome'=>$this->Usuario->Empresa->field('nome'))
		);
		// registro uma sessao que contera alguns dados do usuario
		if ($this->Auth->login($consultaUsuario['Usuario'])) {
			$this->Usuario->id = $this->Auth->user('id');
			$this->Usuario->save( array('ultimo_login'=>date('Y-m-d H:i:s')) );
			// Gravo o acesso do usuario
			$this->loadModel('UsuarioAcessoLog');
			$dadosAcesso = array (
			    'UsuarioAcessoLog' => array (
				   'data_login' => date('Y-m-d'),
				   'hora_login' => date('H:i:s'),
				   'usuario_id' => $this->Usuario->id,
				   'cliente_ip' => $_SERVER['REMOTE_ADDR'],
				   'cliente_nome' => $clienteNome,
				   'cliente_user_agent' => $_SERVER['HTTP_USER_AGENT'],
				   'servidor_ip' => $_SERVER['SERVER_ADDR'],
				   'servidor_nome' => $_SERVER['SERVER_NAME'],
			    )
			);
			$this->UsuarioAcessoLog->save($dadosAcesso);
			$this->Session->delete('Auth.User.sessao_id'); // caso reste de uma seção anterior
			$this->Session->write('Auth.User.sessao_id', $this->UsuarioAcessoLog->id);
			return $this->redirect($this->Auth->redirect());
		}
		else {
			$this->Session->setFlash(__('Erro ao criar sessão'));
		}
		
	} // fim action login
	
	function logout() {
		$this->layout = 'login';
		if ($this->Auth->user()) {
			$this->Usuario->id = $this->Auth->user('id');
			$this->Usuario->save( array('ultimo_logout'=>date('Y-m-d H:i:s')) );
			if ( $this->Session->read('Auth.User.sessao_id') ) {
				$this->loadModel('UsuarioAcessoLog');
				$this->UsuarioAcessoLog->id = $this->Session->read('Auth.User.sessao_id');
				$d = array (
				    'UsuarioAcessoLog' => array (
					   'data_logout' => date('Y-m-d'),
					   'hora_logout' => date('H:i:s'),
				    )
				);
				$this->UsuarioAcessoLog->save($d);
				$this->Session->destroy();
			}
			$this->redirect($this->Auth->logout());
		}
		// Redireciona o usuário para o action do logoutRedirect
		 $this->redirect($this->Auth->logout());
	}
	
	function index() {
		$this->_obter_opcoes();
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		$this->set('consulta_usuario',$this->paginate('Usuario'));
	}
	
	function cadastrar() {
		$this->_obter_opcoes();
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		if (! empty($this->request->data)) {
			if ($this->request->data['Usuario']['senha'] == $this->request->data['Usuario']['senha_confirma']) {
				$this->Usuario->create();
				$this->request->data['Usuario'] += array ('tempo_criado' => date('Y-m-d H:i:s'));
				$this->request->data['Usuario']['senha'] = AuthComponent::password($this->data['Usuario']['senha']);
				if ($this->Usuario->save($this->request->data)) {
					$this->Session->setFlash('Usuário cadastrado com sucesso.','flash_sucesso');
					$this->redirect($this->referer(array('action' => 'index')));
				}
				else {
					$this->request->data['Usuario']['senha'] = NULL;
					$this->request->data['Usuario']['senha_confirma'] = NULL;
					$this->Session->setFlash('Erro ao cadastrar o usuário.','flash_erro');
				}
			}
			else {
				$this->request->data['Usuario']['senha'] = NULL;
				$this->request->data['Usuario']['senha_confirma'] = NULL;
				$this->Session->setFlash('As senhas digitadas não conferem.','flash_erro');
			}
		}
	}
	
	function editar ($id = NULL) {
		$this->_obter_opcoes();
		$this->Usuario->id = $id;
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		//popular o formulario, na primeira carga
		if (empty ($this->request->data)) {
			$this->Usuario->recursive = -1;
			$this->request->data = $this->Usuario->read();
			// formulario carrega sem as senhas
			unset($this->request->data['Usuario']['senha']);
			unset($this->request->data['Usuario']['senha_confirma']);
			if ( ! $this->request->data) {
				$this->Session->setFlash('Usuário não encontrado.','flash_erro');
				$this->redirect(array('action'=>'index'));
			}
		}
		//formulario ja estava populado
		else {
			if ($this->request->data['Usuario']['senha'] == $this->request->data['Usuario']['senha_confirma']) {
				if (empty($this->request->data['Usuario']['senha'])) {
					$this->Usuario->recursive = -1;
					$old = $this->Usuario->read();
					$this->request->data['Usuario']['senha'] = $old['Usuario']['senha'];
					$this->request->data['Usuario']['senha_confirma'] = $old['Usuario']['senha'];
				}
				else {
					$this->request->data['Usuario']['senha'] = AuthComponent::password($this->data['Usuario']['senha']);
				}
				if ($this->Usuario->save($this->request->data)) {
					$this->Session->setFlash(__('Usuário alterado com sucesso.'),'flash_sucesso');
					$this->redirect(array('action'=>'index'));
				}
				else {
					$this->request->data['Usuario']['senha'] = NULL;
					$this->request->data['Usuario']['senha_confirma'] = NULL;
					$this->Session->setFlash(__('Erro ao atualizar o usuário.'),'flash_erro');
				}
			}
			else {
				$this->request->data['Usuario']['senha'] = NULL;
				$this->request->data['Usuario']['senha_confirma'] = NULL;
				$this->Session->setFlash(__('As senhas digitadas não conferem.'),'flash_erro');
			}
		}
	}
	
	function inativar ($id=NULL) {
		if ( $this->RequestHandler->isAjax() ) {
			$this->layout = 'ajax';
		}
		$this->Usuario->id = $id;	
		if ( empty($id)) {
			$this->Session->setFlash(__('Usuário não informado.'),'flash_erro');
			return NULL;
		}
		if (! $this->Usuario->field('id')) {
			$this->Session->setFlash(__('Usuário não encontrado.'),'flash_erro');
			return NULL;
		}
		
		if ($this->Usuario->save( array('Usuario'=>array('ativo'=>0)) ) ) {
			$this->Session->setFlash(__("Usuário $id inativado com sucesso."),'flash_sucesso');
			$this->redirect(array('action'=>'index'));
		}
		else {
			$this->Session->setFlash(__("Não foi possível inativar o usuário $id."),'flash_erro');
			$this->redirect(array('action'=>'index'));
		}
	}
	
	function acessoLog() {
		$this->set('opcoes_usuarios',$this->Usuario->find('list',array('contain'=>array(),'fields'=>array('id','nome'))));
		//$this->redirect('RelUsuarioAcessoLog/');
		if ($this->data['Usuario']) {
			
		}
	}
	
	function RelUsuarioAcessoLog() {
		
	}
	
}

?>