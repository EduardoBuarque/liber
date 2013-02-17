<div class="row-fluid">
	
	<div class="span12">
		<fieldset>
			<legend class="descricao_cabecalho"><?php print __('Cadastrar usuário'); ?></legend>

			<?php
			if ($this->Ajax->isAjax()) {
				print $this->Ajax->form('cadastrar','post',array('autocomplete'=>'off','model'=>'Usuario','update'=>'conteudo_ajax'));
			}
			else {
				print $this->Form->create('Usuario',array('autocomplete'=>'off','onsubmit'=>'submissaoFormulario(this); return false;'));
			}
			?>
			<div class="row-fluid">
				<div class="span6">
					<?php
					print $this->Form->input('nome', array('label'=>__('Nome'),'class'=>'span12'));
					print $this->Form->input('login',array('label'=>__('Login'),'class'=>'span12'));
					print $this->Form->input('senha', array('label'=>__('Senha'),'type'=>'password','class'=>'span12'));
					print $this->Form->input('senha_confirma', array('label'=>__('Redigite a senha'),'type'=>'password','class'=>'span12'));
					?>
				</div>
				<div class="span6">
					<?php
					print $this->Form->input('grupo_id',array('label'=>__('Grupo'),'options'=>$opcoes_grupos,'class'=>'span12'));
					print $this->Form->input('empresa_id',array('label'=>__('Empresa'),'options'=>$opcoes_empresas,'class'=>'span12'));
					print $this->Form->input('email', array('label'=>__('Endereço de e-mail'),'class'=>'span12'));
					print $this->Form->input('ativo', array('label'=>__('Ativo?'),'checked'=>'checked'));
					print $this->Form->input('eh_tecnico',array('label'=>__('É técnico?')));
					print $this->Form->input('eh_vendedor',array('label'=>__('É vendedor?')));
					?>
				</div>
			</div>
	
			<?php print $this->Form->end(array('label'=>__('Gravar'),'class'=>'btn btn-primary','div'=>array('class'=>'form-actions'))); ?>
		</fieldset>
	</div>

</div>