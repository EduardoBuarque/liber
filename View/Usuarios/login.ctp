<div class="row">
	
	<div id="login">

		<?php print $this->Form->create('Usuario', array('action'=>'login','autocomplete'=>'off')); ?>
		
		<fieldset>
			<legend> <?php print __('Autenticação'); ?></legend>
			
				<?php
				print $this->Form->input('login',array('label'=>__('Usuário')));
				print $this->Form->input('senha', array('label'=>__('Senha'),'type'=>'password'));
				print $this->Form->submit(__('Entrar'),array('class'=>'btn','type' => 'submit'));
				?>
			
		</fieldset>
	</div>

</div>