<?php print $this->Html->script('formatar_moeda'); ?>
<script type="text/javascript">
	$(function() {
		$('#ServicoValor').priceFormat();
	});
</script>

<div class="row-fluid">
	
	<div class="span12">
		
		<?php
		if ($this->Ajax->isAjax()) {
			print $this->Ajax->form('cadastrar','post',array('autocomplete'=>'off','model'=>'Servico','update'=>'conteudo_ajax'));

		}
		else {
			print $this->Form->create('Servico',array('autocomplete'=>'off','onsubmit'=>'submissaoFormulario(this); return false;'));
		}
		?>
		<fieldset>
			<legend><?php print __('Cadastrar serviço')?></legend>
			
			<div class="row-fluid">
				
				<div class="span6">
					<?php
					print $this->Form->input('nome',array('label'=>__('Nome'),'class'=>'span12'));
					print $this->Form->input('valor',array('label'=>__('Valor'),'class'=>'span12'));
					?>
				</div>

				<div class="span6">
				<?php print $this->Form->input('servico_categoria_id',array('label'=>__('Categoria'),'options'=>$opcoes_servico_categoria)); ?>
				</div>
				
			</div>
			
		</fieldset>
		
		<?php print $this->Form->end(array('label'=>__('Gravar'),'class'=>'btn btn-primary','div'=>array('class'=>'form-actions'))); ?>
		
	</div>
	
</div>
