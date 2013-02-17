<div id="conteudo_ajax">
	
	<?php
	print $this->fetch('meta');
	print $this->fetch('css');
	print $this->fetch('script');
	print $this->Js->writeBuffer();
	?>	
	
	<div id="flash_ajax">
		<?php
		print $this->Session->flash();
		print $this->Session->flash('auth', array('element' => 'flash_notificacao'));
		?>
	</div>
	
	<?php print $this->fetch('content') ?>
	
</div>

<div id="liber_log_ajax">
	<?php 
	// Substituido pelo Cake DebugKitToolbar
	//print $this->element('sql_dump');
	?>
</div>