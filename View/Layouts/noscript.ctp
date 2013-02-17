<?php print $this->Html->docType('html5'); ?>
<html>
	<head>
		<?php print $this->Html->charset()."\n";?>
		<?php print $this->fetch('meta'); ?>
		<title>
			<?php print 'Liber - '.$title_for_layout."\n"; ?>
		</title>
		<?php
			print $this->Html->meta('icon');
			print $this->Html->css('estilo.css');
			print $this->Html->css('jquery-ui/jquery-ui.css');
			print $this->fetch('css');
		?>
	</head>
	
	<body>
		
		<div id="cabecalho">
			<div id="menu" name="menu">
				<ul class="sf-menu">
					<li>
						<?php print $this->Html-> link('Início', "/");?>
					</li>
					
				</ul>
			</div>
		</div>
		
		<div id="conteudo">
			
			<div id="flash">
				<?php
				print $this->Session->flash();
				print $this->Session->flash('auth', array('element' => 'flash_notificacao'));
				?>
			</div>
			
			<?php print $this->fetch('content') ?>
		
			<div id="rodape">
				
			</div>
			
		</div>

	</body>
	
</html>
