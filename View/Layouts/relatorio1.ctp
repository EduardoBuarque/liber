<?php print $this->Html->docType('html5'); ?>
<html>
	<head>
		<?php print $this->Html->charset()."\n"; ?>
		<?php print $this->fetch('meta'); ?>
		<title>
			Liber
		</title>
		<?php
			print $this->Html->meta('icon');
			print $this->Html->css('relatorio1.css',null,array('media'=>'all'));
			print $this->fetch('css');
			print $this->fetch('script');
		?>
	</head>
	
	<body>
		
		<div id="conteudo">
			<center>
				<h2>Liber <i>software</i></h2>
			</center>
			<div style="float: left">
				<?php print 'Empresa: ' . AuthComponent::user('empresa_nome'); ?>
			</div>
			<div style="float: right;">
				<?php print 'Gerado: ' .date('d/m/Y H:i:s'); ?>
			</div>
			
			<div class="limpar">&nbsp;</div>
			
			<div id="flash">
				<?php print $this->Session->flash(); ?>
			</div>
			
			<?php print $this->fetch('content') ?>
			
		</div>

	</body>
	
</html>
