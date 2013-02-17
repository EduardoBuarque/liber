<?php print $this->Html->docType('html5'); ?>
<html>
	<head>
		<?php print $this->Html->charset()."\n"; ?>
		<?php print $this->fetch('meta'); ?>
		<title>
			<?php print 'Liber - '.$title_for_layout."\n"; ?>
		</title>
		<?php
			print $this->Html->meta('icon');
			print $this->Html->css('estilo_limpo.css');
			print $this->fetch('css');
			print $this->Html->script('jquery');
			print $this->Html->script('auxiliares');
			print $this->fetch('script');
			print $this->Js->writeBuffer();
		?>
	</head>
	
	<body>
		
		<div id="conteudo">
			
			<div id="flash">
				<?php
				//print $this->Session->flash();
				print $this->Session->flash('auth', array('element' => 'flash_notificacao'));
				?>
			</div>
			
			<?php print $this->fetch('content') ?>
			
		</div>

	</body>
	
</html>
