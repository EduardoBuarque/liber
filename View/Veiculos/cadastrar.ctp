<div class="row-fluid">
	
	<div class="span12">
		<fieldset>
			<legend class="descricao_cabecalho"><?php print __('Cadastrar veículo'); ?></legend>

			<?php
			if ($this->Ajax->isAjax()) {
				print $this->Ajax->form('cadastrar','post',array('autocomplete'=>'off','model'=>'Veiculo','update'=>'conteudo_ajax'));

			}
			else {
				print $this->Form->create('Veiculo',array('autocomplete'=>'off','onsubmit'=>'submissaoFormulario(this); return false;'));
			}
			?>

			<div class="row-fluid">
				
				<div class="span6">
					<?php
					$this->Form->defineRow(array(12));
					print $this->Form->input('modelo',array('label'=>__('Modelo')));
					$this->Form->defineRow(array(12));
					print $this->Form->input('placa',array('label'=>__('Placa')));
					?>
				</div>
				<div class="span6">
					<?php
					$this->Form->defineRow(array(12));
					print $this->Form->input('fabricante',array('label'=>__('Fabricante')));
					$this->Form->defineRow(array(12));
					print $this->Form->input('ano',array('label'=>__('Ano'),'type'=>'text'));
					?>
				</div>
				
			</div>
			
			<?php print $this->Form->end(array('label'=>__('Gravar'),'class'=>'btn btn-primary','div'=>array('class'=>'form-actions'))); ?>
		</fieldset>
	</div>

</div>