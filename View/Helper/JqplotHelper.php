<?php

/**
 * Classe para utilização do plugin jquery, Jqplot
 * 
 * #TODO criar metodo para javascript->link nao utilizar o scripts_for_layout
 */
class JqplotHelper extends AppHelper {
	
	var $helpers = array('Html','Javascript');
	
	function showHeaders () {
		$retorno = null;
		print $this->Html->script('jqplot/jquery.jqplot');
		print $this->Html->script('jqplot/plugins/jqplot.donutRenderer');
		print $this->Html->script('jqplot/plugins/jqplot.pieRenderer');
		
		$retorno += '
			<!--[if lt IE 9]><script language="javascript" type="text/javascript" src="jqplot/excanvas.js"></script><![endif]-->
		';
		$retorno += $this->Html->css('jquery.jqplot.css');
		
		
		return $this->output($retorno);
	}
	
}


?>