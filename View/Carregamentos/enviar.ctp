<div class="row-fluid">
	
	<div class="span12">
		<fieldset>
			<legend class="descricao_cabecalho"><?php print __('Enviar carregamento'); ?></legend>

			<?php
			if ($this->Ajax->isAjax()) {
				print $this->Ajax->form('enviar','post',array('autocomplete'=>'off','model'=>'Carregamento','update'=>'conteudo_ajax'));

			}
			else {
				print $this->Form->create(null,
					array('autocomplete'=>'off','onsubmit'=>'submissaoFormulario(this); return false;','action'=>'enviar', 'method'=>'post'));
			}
			print $this->Form->input('id',array('label'=>'Número','type'=>'text'));
			print $this->Form->end('Enviar');
			?>
		</fieldset>
	</div>

</div>