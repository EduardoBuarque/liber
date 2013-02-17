<?php

/**
 * Classe para lidar com a identificacao da empresa vinculada
 * ao usuario logado 
 */
class EmpresaBehavior extends ModelBehavior {
	
	/**
	* Seta as opcoes da classe.
	* @param Model $Model
	* @param array $settings 
	* @return void
	*/
	public function setup(Model $Model, $settings = array()) {
		if (!isset($this->settings[$Model->alias])) {
			$this->settings[$Model->alias] = array('campoEmpresa'=>'empresa_id');
		}
		$this->settings[$Model->alias] = array_merge($this->settings[$Model->alias], $settings);
	}
	
	/**
	 * Acrescenta o campo definido por $this->settings[$Model->alias]['campoEmpresa']
	 * como condicao para o find, o valor é definido na variavel de sessao
	 * chamada 'empresa_id', definda no login.
	 * 
	 * @param Model $Model
	 * @param array $query
	 * @return $query modificada
	 */
	public function beforeFind(Model $Model, $query) {
		if (! isset($query['conditions'])) {
			$query = array_merge($query,array('conditions'=>array()));
		}
		$condicoes = array (
		    $Model->alias.'.'.$this->settings[$Model->alias]['campoEmpresa']=>AuthComponent::user('empresa_id')
		);
		$query['conditions'] = array_merge($query['conditions'],$condicoes);
		return $query;
	}
	
	/**
	* Antes de salvar os dados é inserido o valor
	* do campo definido em $this->settings[$Model->alias]['campoEmpresa']
	* no conjunto de dados do modelo.
	* O valor a ser inserido é definido no inicio da sessao.
	* 
	*
	* @param Model $Model
	* @return void
	* @access public
	*/
	public function beforeSave(Model $Model) {
		// armazena o valor do campo 'empresa_id' armazenado em sessao e
		// definido no login
		$empresa_id = AuthComponent::user('empresa_id');
		if (empty($empresa_id)) {
			print 'Valor nao encontrado';
			return false;
		}
		$data =& $Model->data[$Model->alias];
		if (! isset($data[$this->settings[$Model->alias]['campoEmpresa']])) {
			$data = array_merge($data,array($this->settings[$Model->alias]['campoEmpresa']=>$empresa_id));
		}
	}
	
}
