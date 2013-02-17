<?php

/**
 * Provê métodos para diversos fins
 * 
 * @see GeralHelper
 */
class GeralComponent extends Component {

	function __construct(ComponentCollection $collection, $settings = array()) {
		parent::__construct($collection, $settings);
	}
	
	/**
	 * Recebe um numero em formato brasileiro e retorna-o em formato americano
	 * @return number
	 */
	function moeda2numero ($variavel) {
		// toda 'moeda' tem uma virgula; eu espero
		if (! preg_match_all('/,/', $variavel,$retorno)) return $variavel;
		$variavel = preg_replace('/\./', '', $variavel);
		$variavel = preg_replace('/,/', '.', $variavel);
		return number_format($variavel,2,'.','');
	}
	
	/**
	 * Recebe um numero em formato americano e formata para formato brasileiro
	 * @return number/string
	 */
	function numero2moeda ($variavel) {
		// este metodo deve receber apenas numeros
		if (! is_numeric($numero)) return $numero;
		return number_format($variavel,2,',','.');
	}
	
	/**
	 * $data para o formato ano-mes-dia
	 * 
	 * Baseado na função do Juan Bastos
	 */
	function ajustarData($data) {
		if (isset($data) && preg_match('/\d{1,2}\/\d{1,2}\/\d{2,4}/', $data)) {
			list($dia, $mes, $ano) = explode('/', $data);
			if (strlen($ano) == 2) {
				if ($ano > 50) {
					$ano += 1900;
				} else {
					$ano += 2000;
				}
			}
			$retorno = "$ano-$mes-$dia";
			return $retorno;
		}
		else return false;
	}


}

?>
