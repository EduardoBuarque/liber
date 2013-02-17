<div class="row-fluid">
	
	<div class="span12">
		<fieldset>
			<legend class="descricao_cabecalho"><?php print __('Cadastrar conta'); ?></legend>
			<?php
			if ($this->Ajax->isAjax()) {
				print $this->Ajax->form('cadastrar','post',array('autocomplete'=>'off','model'=>'DocumentoTipo','update'=>'conteudo_ajax'));
			}
			else {
				print $this->Form->create('Conta',array('autocomplete'=>'off','onsubmit'=>'submissaoFormulario(this); return false;'));
			}?>
			
			<div class="row-fluid">
				
				<div class="span6">
					<?php
					$this->Form->defineRow(array(12));
					print $this->Form->input('nome',array('label'=>'Nome'));
					$this->Form->defineRow(array(12));
					print $this->Form->input('apelido',array('label'=>'Apelido'));
					$this->Form->defineRow(array(12));
					print $this->Form->input('banco',array('label'=>'Banco'));
					?>
				</div>
				<div class="span6">
					<?php
					$this->Form->defineRow(array(12));
					print $this->Form->input('agencia',array('label'=>'Agência'));
					$this->Form->defineRow(array(12));
					print $this->Form->input('conta',array('label'=>'Conta'));
					$this->Form->defineRow(array(12));
					print $this->Form->input('titular',array('label'=>'Titular'));
					?>
				</div>
				
			</div>
			
			<?php print $this->Form->end(array('label'=>__('Gravar'),'class'=>'btn btn-primary','div'=>array('class'=>'form-actions'))); ?>
		</fieldset>
	</div>

</div>