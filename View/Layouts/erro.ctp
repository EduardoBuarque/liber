<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="robots" content="noindex" />
		<title>
			Liber - Oooops!
		</title>
		<?php
		print $this->Html->meta('icon');
		print $this->Html->css('bootstrap.min');
		print $this->Html->css('bootstrap-responsive.min');
		print $this->Html->css('erro.css');
		print $this->Html->script('jquery');
		print $this->Html->script('bootstrap.min');
		?>
	</head>
	
	<body>
		
		<div id="container">
			<div id="topo" style="float: left">
				<?php print $this->Html->image('logotipo.png'); ?>
			</div>

			<a href="javascript:history.go(-1)" class="link_botao">Voltar</a>
			<a href="<?php print $this->Html->url('/'); ?>" class="link_botao">Início</a>
			
			<div class="limpar"></div>
			
			<div id="conteudo">

				<div id="flash">
					<?php print $this->Session->flash(); ?>
				</div>

				<?php print $this->fetch('content') ?>

			</div>
		</div>
		
	</body>
</html>