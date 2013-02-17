<script type="text/javascript">
	$(function(){
		$('#sistema_opcoes').tabs();
	});
</script>

<div class="row-fluid">
	
	<div class="span12">
		
		<fieldset>
			<legend class="descricao_cabecalho">
				Opções do sistema
			</legend>
			<?php
			if ($this->Ajax->isAjax()) {
				print $this->Ajax->form('index','post',array('autocomplete'=>'off','model'=>'SistemaOpcao','update'=>'conteudo_ajax'));
			}
			else {
				print $this->Form->create('SistemaOpcao',array('autocomplete'=>'off','onsubmit'=>'submissaoFormulario(this); return false;'));
			}
			?>

			<div id="sistema_opcoes">
				<ul>
					<li> <a href="#opcoes_sistema_financeiro">Financeiro</a> </li>
					<li> <a href="#opcoes_sistema_seguranca">Segurança</a> </li>
				</ul>

				<div id="opcoes_sistema_financeiro">
					<?php
					print $this->Form->input('item_conta_planos_venda_pedidos',array(
						'label'=>__('Item do plano de contas utilizado nas contas a receber geradas por pedidos de venda.'),
						'options' => $opcoes_conta_planos,
						)
					);
					print $this->Form->input('item_conta_planos_ordem_servicos',array(
						'label'=>__('Item do plano de contas utilizado nas contas a receber geradas por ordens de serviço.'),
						'options' => $opcoes_conta_planos,
						)
					);
					?>
				</div>

				<div id="opcoes_sistema_seguranca">
					<?php
					print $this->Form->input('login_periodo_tentativas',array('label'=>'Período, em minutos, entre as tentativas de login'));
					print $this->Form->input('login_maximo_tentativas',array('label'=>'Máximo de tentativas de login no período'));
					print $this->Form->input('login_tempo_bloqueio',array('label'=>'Tempo, em minutos, que o usuário ficará bloqueado.'));
					?>
				</div>

			</div>

			<?php print $this->Form->end(array('label'=>__('Gravar'),'class'=>'btn btn-primary','div'=>array('class'=>'form-actions'))); ?>
	</div>
</div>