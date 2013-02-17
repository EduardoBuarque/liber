<div class="row-fluid">
	
	<div class="span12">
		<fieldset>
			<legend class="descricao_cabecalho"><?php print __('Cadastrar empresa'); ?></legend>

			<?php
			if ($this->Ajax->isAjax()) {
				print $this->Ajax->form('cadastrar','post',array('autocomplete'=>'off','model'=>'Empresa','update'=>'conteudo_ajax'));
			}
			else {
				print $this->Form->create('Empresa',array('autocomplete'=>'off','onsubmit'=>'submissaoFormulario(this); return false;'));
			}
			?>
			
			<div class="row-fluid">
				<div class="span4">
					<?php
					print $this->Form->input('nome',array('label'=>__('Nome'),'class'=>'span12'));
					print $this->Form->input('cnpj',array('label'=>__('CNPJ'),'class'=>'span12'));
					print $this->Form->input('inscricao_estadual',array('label'=>__('Inscrição estadual'),'class'=>'span12'));
					print $this->Form->input('telefone',array('label'=>__('Número de telefone'),'class'=>'span12'));
					print $this->Form->input('fax',array('label'=>__('Número de fax'),'class'=>'span12'));
					?>
				</div>
				<div class="span4">
					<?php
					print $this->Form->input('site',array('label'=>__('Site da empresa'),'class'=>'span12'));
					print $this->Form->input('endereco_email_principal',array('label'=>__('Endereço de e-mail principal'),'class'=>'span12'));
					print $this->Form->input('endereco_email_secundario',array('label'=>__('Endereço de e-mail secundário'),'class'=>'span12'));
					print $this->Form->input('logradouro',array('label'=>__('Logradouro'),'class'=>'span12'));
					print $this->Form->input('numero',array('label'=>__('Número'),'class'=>'span12'));
					?>
				</div>

				<div class="span4">
					<?php
					print $this->Form->input('bairro',array('label'=>__('Bairro'),'class'=>'span12'));
					print $this->Form->input('complemento',array('label'=>__('Complemento'),'class'=>'span12'));
					print $this->Form->input('cidade',array('label'=>__('Cidade'),'class'=>'span12'));
					?>
					<div class="control-group required">
						<label class="control-label" for="EmpresaUf">UF:</label>
						<div class="controls">
							<?php print $this->Estados->select('estado'); ?>
						</div>
					</div>
				</div>
			</div>
		
			<?php print $this->Form->end(array('label'=>__('Gravar'),'class'=>'btn btn-primary','div'=>array('class'=>'form-actions'))); ?>
		</fieldset>
	</div>

</div>