<div class="row-fluid">
	
	<div class="span12">
		<fieldset>
			<legend class="descricao_cabecalho"><?php print __('Editar tipo de documento'); ?></legend>
			<?php
			if ($this->Ajax->isAjax()) {
				print $this->Ajax->form('editar','post',array('autocomplete'=>'off','model'=>'DocumentoTipos','update'=>'conteudo_ajax'));
			}
			else {
				print $this->Form->create('DocumentoTipo',array('autocomplete'=>'off','onsubmit'=>'submissaoFormulario(this); return false;'));
			}
			print $this->Form->input('nome',array('label'=>'Nome'));
			print $this->Form->end(array('label'=>__('Gravar'),'class'=>'btn btn-primary','div'=>array('class'=>'form-actions')));
			?>
		</fieldset>
	</div>

</div>