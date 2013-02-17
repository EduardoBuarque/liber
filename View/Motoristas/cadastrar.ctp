<div class="row-fluid">
	
	<div class="span12">
		<fieldset>
			<legend class="descricao_cabecalho"><?php print __('Cadastrar motorista'); ?></legend>

			<?php
			if ($this->Ajax->isAjax()) {
				print $this->Ajax->form('cadastrar','post',array('autocomplete'=>'off','model'=>'Motorista','update'=>'conteudo_ajax'));
			}
			else {
				print $this->Form->create('Motorista',array('autocomplete'=>'off','onsubmit'=>'submissaoFormulario(this); return false;'));
			}
			?>
			
			<div class="row-fluid">
				
				<div class="span6">
					<?php
					$this->Form->defineRow(array(12));
					print $this->Form->input('nome',array('label'=>__('Nome')));
					$this->Form->defineRow(array(12));
					print $this->Form->input('logradouro_nome',array('label'=>__('Logradouro')));
					$this->Form->defineRow(array(12));
					print $this->Form->input('logradouro_numero',array('label'=>__('Número')));
					$this->Form->defineRow(array(12));
					print $this->Form->input('logradouro_complemento',array('label'=>__('Complemento')));
					?>
				</div>
				<div class="span6">
					<?php
					$this->Form->defineRow(array(12));
					print $this->Form->input('cnh_numero_registro',array('label'=>__('Número de registro da C.N.H.')));
					$this->Form->defineRow(array(12));
					print $this->Form->input('cnh_data_validade',array('label'=>__('Data de validade da C.N.H.'),'type'=>'text','class'=>'datepicker mascara_data'));
					$this->Form->defineRow(array(12));
					print $this->Form->input('cnh_categoria',array('label'=>__('Categoria da C.N.H.')));
					$this->Form->defineRow(array(12));
					print $this->Form->input('veiculo_padrao',array('label'=>__('Principal veículo'),'options'=>$opcoes_veiculo));
					?>
				</div>
				
			</div>
			
			<?php print $this->Form->end(array('label'=>__('Gravar'),'class'=>'btn btn-primary','div'=>array('class'=>'form-actions'))); ?>
		</fieldset>
	</div>

</div>