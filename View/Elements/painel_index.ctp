<?php
/**
 * Painel utilizado no metodo index dos controladores.
 */
?>

<div class="botoes">
	<?php print $this->Html->image('add24x24.png',array('title'=>'Cadastrar',
		'alt'=>'Cadastrar','url'=>array('action'=>'cadastrar')));
	print '<a title="Imprimir" onclick="javascript: window.print();" href="#">'.
		$this->Html->image('print24x24.png', array('alt'=>'Imprimir')).'</a>';
	?>
</div>
