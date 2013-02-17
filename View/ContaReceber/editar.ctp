<script type="text/javascript">
	// variaveis a serem utilizadas no arquivo conta_receber.js
	var ajaxPesqCliente = "<?php print $this->Html->url(array('controller'=>'Clientes','action'=>'pesquisaAjaxCliente')); ?>/";
	var ajaxPesqFornecedor = "<?php print $this->Html->url(array('controller'=>'Fornecedores','action'=>'pesquisaAjaxFornecedor')); ?>/";
</script>

<?php
print $this->Html->script('formatar_moeda');
print $this->Html->script('conta_receber');
?>

<div class="row-fluid">
	
	<div class="span2 visible-desktop">
		<ul class="nav nav-pills nav-stacked" style="margin-top: 35px;">

			<li class="nav-header">
				Ações
			</li>
			<li>
				<a href="<?php print $this->Html->url(array('controller'=>'ContaReceber','action'=>'index'));?>" onclick="return(confirm('Cancelar edição?'));">
					<i class="icon-remove"></i>
					Cancelar
				</a>
			</li>

			<li class="nav-header">
				Contas a receber
			</li>
			<li>
				<a href="<?php print $this->Html->url(array('controller'=>'ContaReceber','action'=>'cadastrar'));?>">
					<i class="icon-file"></i>
					Cadastrar
				</a>
			</li>
			<li class="active">
				<a href="<?php print $this->Html->url(array('controller'=>'ContaReceber','action'=>'editar'));?>">
					<i class="icon-edit"></i>
					Editar
				</a>
			</li>
			<li>
				<a href="<?php print $this->Html->url(array('controller'=>'ContaReceber','action'=>'pesquisar'));?>">
					<i class="icon-filter"></i>
					Pesquisar
				</a>
			</li>
			<li>
				<a href="<?php print $this->Html->url(array('controller'=>'ContaReceber','action'=>'index'));?>">
					<i class="icon-list"></i>
					Listar
				</a>
			</li>

			<li class="nav-header">
				Contas
			</li>
			<li>
				<a href="<?php print $this->Html->url(array('controller'=>'Contas','action'=>'cadastrar'));?>">
					<i class="icon-file"></i>
					Cadastrar
				</a>
				<a href="<?php print $this->Html->url(array('controller'=>'Contas','action'=>'index'));?>">
					<i class="icon-list"></i>
					Listar
				</a>
			</li>
			<li class="nav-header">
				Formas de pagamento
			</li>
			<li>
				<a href="<?php print $this->Html->url(array('controller'=>'PagamentoTipos','action'=>'cadastrar'));?>">
					<i class="icon-file"></i>
					Cadastrar
				</a>
				<a href="<?php print $this->Html->url(array('controller'=>'PagamentoTipos','action'=>'index'));?>">
					<i class="icon-list"></i>
					Listar
				</a>
			</li>
		</ul>
	</div>

	<div class="span10">

		<?php
		if ($this->Ajax->isAjax()) {
			print $this->Ajax->form('editar','post',array('autocomplete'=>'off','model'=>'ContaReceber','update'=>'conteudo_ajax'));

		}
		else {
			print $this->Form->create('ContaReceber',array('autocomplete'=>'off','onsubmit'=>'submissaoFormulario(this); return false;'));
		}
		?>
		
		<fieldset>
			<legend class="descricao_cabecalho"><?php print __('Editar conta a receber');?></legend>
			
			<?php
			$this->Form->defineRow(array(3,3,3));
			print $this->Form->input('eh_cliente_ou_fornecedor', array(
				'label'=>__('Cliente ou fornecedor?'),
				'options'=>array(''=>'','C'=>'Cliente','F'=>'Fornecedor')
				));
			print $this->Form->input('eh_fiscal', array(
				'label'=>__('É fiscal?'),
				'options'=>array('0'=>'Não','1'=>'Sim')
				));
			print $this->Form->input('situacao', array(
				'label'=>__('Situação'),
				'options'=>$opcoes_situacoes
				));
			?>
				
			<div class="row-fluid">
				
				<div class="span6">
					<?php
					$this->Form->defineRow(array(2,10));
					print $this->Form->input('cliente_fornecedor_id', array(
						'label'=>__('Código'),
						'type'=>'text',
						));
					print $this->Form->input('pesquisar_cliente_fornecedor',array(
						'label'=>__('Nome'),
						'id'=>'pesquisar_cliente_fornecedor',
						'type'=>'text',
						));

					$this->Form->defineRow(array(12));
					print $this->Form->input('documento_tipo_id',array('label'=>'Tipo documento','options'=>$opcoes_documento_tipo));
					$this->Form->defineRow(array(12));
					print $this->Form->input('numero_documento',array('label'=>'Número documento'));
					$this->Form->defineRow(array(12));
					print $this->Form->input('valor',array('label'=>'Valor'));
					?>
				</div>
			
				<div class="span6">
					<?php
					$this->Form->defineRow(array(12));
					print $this->Form->input('conta_origem',array('label'=>'Conta de origem','options'=>$opcoes_conta_origem));
					$this->Form->defineRow(array(12));
					print $this->Form->input('conta_plano_id',array('label'=>'Plano de contas','options'=>$opcoes_conta_planos));
					$this->Form->defineRow(array(12));
					print $this->Form->input('data_vencimento',array(
						'label'=>'Data do vencimento',
						'type'=>'text',
						'class'=>'datepicker mascara_data'
					));
					$this->Form->defineRow(array(12));
					print $this->Form->input('observacao',array('label'=>'Observação'));
					?>
				</div>
				
			</div>
				
		</fieldset>

		<?php print $this->Form->end(array('label'=>__('Gravar'),'class'=>'btn btn-primary','div'=>array('class'=>'form-actions'))); ?>

	</div>
	
</div>
