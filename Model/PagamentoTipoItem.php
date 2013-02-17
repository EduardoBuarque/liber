<?php

class PagamentoTipoItem extends AppModel {
	var $name='PagamentoTipoItem';
	var $belongsTo = array(
		'PagamentoTipo'
	);
}
	
?>