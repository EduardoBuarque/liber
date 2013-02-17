<div class="row-fluid">
	
	<div class="span12">
		<fieldset>
			<legend class="descricao_cabecalho"><?php print __('Log de acesso dos usuários'); ?></legend>

			<?php print $this->Form->create('Usuario', array('autocomplete'=>'off','onsubmit'=>'submissaoFormulario(this); return false;'));
			print $this->Form->input('id', array('label'=>'Usuário','options'=>$opcoes_usuarios));
			print $this->Form->input('data_inicial',array('label'=>'Data inicial','type'=>'datetime','dateFormat' => 'DMY','timeFormat'=>24));
			print $this->Form->input('data_final',array('label'=>'Data final','type'=>'datetime','dateFormat' => 'DMY','timeFormat'=>24));		
			print $this->Form->end('Gerar');
			?>
		</fieldset>
	</div>
	
</div>
