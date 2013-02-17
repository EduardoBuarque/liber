<div class="row-fluid">
	
	<div class="span12">
		<fieldset>
			<legend class="descricao_cabecalho"><?php print __('Cadastrar categoria de cliente'); ?></legend>

			<?php
			if ($this->Ajax->isAjax()) {
				print $this->Ajax->form('cadastrar','post',array('autocomplete'=>'off','model'=>'ClienteCategoria','update'=>'conteudo_ajax'));
			}
			else {
				print $this->Form->create('ClienteCategoria',array('autocomplete'=>'off','onsubmit'=>'submissaoFormulario(this); return false;'));
			}

			print $this->Form->input('descricao',array('label'=>__('Descrição'),'class'=>'span4'));
			print $this->Form->end(array('label'=>__('Gravar'),'class'=>'btn btn-primary','div'=>array('class'=>'form-actions')));
			?>
		</fieldset>
	</div>

</div>