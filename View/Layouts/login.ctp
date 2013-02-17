<?php print $this->Html->docType('html5'); ?>
<html lang="pt-br">
	<head>
		<?php
		print $this->Html->charset()."\n";
		print $this->fetch('meta');
		print $this->Html->meta(array('name' => 'robots', 'content' => 'noindex'));
		?>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>
			Liber - login
		</title>
		<?php
			print $this->Html->meta('icon');
			print $this->Html->css('login.css');
			print $this->Html->css('bootstrap.min');
			print $this->Html->css('bootstrap-responsive.min');
			print $this->fetch('css');
			print $this->fetch('script');;
			print $this->Js->writeBuffer();
			print $this->Html->script('jquery');
		?>
		
		<script type="text/javascript">
			$(document).ready(function() {
				if ($('#UsuarioLogin').val() == undefined || $('#UsuarioLogin').val() == "") {
					$('#UsuarioLogin').focus();
				}
				else {
					$('#UsuarioSenha').focus();
				}
			});
		</script>
	</head>
	
	<body>
		
		
		<div id="cabecalho">
			
		</div>
		
		<div id="banner">
			&nbsp;
		</div>
		
		<div id="conteudo" class="container-fluid">
			
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
		
		<?php print $this->Html->script('bootstrap.min'); ?>
	</body>
	
</html>
