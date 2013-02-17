<script type="text/javascript">
	$(function(){
		$(".datepicker").datepicker();
	});
</script>
<style type="text/css">
form .required label:after {
	content: '' !important;
}
label.required:after {
	content: '' !important;
}
</style>

<div class="row-fluid">
	<div class="span12">
		<fieldset>
			<legend class="descricao_cabecalho">Pesquisar conta a receber</legend>
			<?php print $this->Form->create(null,array('action'=>'pesquisar','autocomplete'=>'off'));
			$this->Form->defineRow(array(3,3,3,3));
			print $this->Form->input('id', array('label'=>'Código','type'=>'text'));
			print $this->Form->input('valor',array('label'=>'Valor'));
			print $this->Form->input('cliente_fornecedor_id',array('label'=>'Código cliente/fornecedor','type'=>'text'));
			print $this->Form->input('data_inicio',array('label'=>'Data inicial','class'=>'datepicker mascara_data'));
			$this->Form->defineRow(array(3,3,3,3));
			print $this->Form->input('data_fim',array('label'=>'Data final','class'=>'datepicker mascara_data'));			
			print $this->Form->input('eh_cliente_ou_fornecedor', array('label'=>'Cliente ou fornecedor?',
			'options'=>array('C'=>'Cliente','F'=>'Fornecedor'),'empty'=>'Selecione'));
			print $this->Form->input('numero_documento',array('label'=>'Número do documento'));
			print $this->Form->input('documento_tipo_id',array('label'=>'Tipo do documento','options'=>$opcoes_documento_tipo,'empty'=>'Selecione'));
			$this->Form->defineRow(array(3,3,3));
			print $this->Form->input('conta_origem',array('label'=>'Conta de origem','options'=>$opcoes_conta_origem,'empty'=>'Selecione'));
			print $this->Form->input('conta_plano_id',array('label'=>'Plano de contas','options'=>$opcoes_conta_planos,'empty'=>'Selecione'));
			print $this->Form->input('situacao',array('label'=>'Situação','options'=>$opcoes_situacoes,'empty'=>'Selecione'));
			?>

			<div class="clearfix"></div>
			<?php print $this->Form->end(array('label'=>__('Pesquisar'))); ?>

			<?php if (isset($num_resultados) && $num_resultados > 0) : ?>
				<fieldset>
					<legend><?php print __('Resultados'); ?></legend>

					<table class="table table-striped">
						<thead>
							<tr>
								<th><?php print $this->Paginator->sort('id','Cód'); ?></th>
								<th><?php print $this->Paginator->sort('eh_cliente_ou_fornecedor','Cliente ou fornecedor?'); ?></th>
								<th><?php print $this->Paginator->sort('cliente_fornecedor_id','Cliente/fornecedor'); ?></th>
								<th><?php print $this->Paginator->sort('documento_tipo_id','Tipo documento'); ?></th>
								<th><?php print $this->Paginator->sort('numero_documento','Número documento'); ?></th>
								<th><?php print $this->Paginator->sort('valor','Valor'); ?></th>
								<th><?php print $this->Paginator->sort('conta_origem','Conta origem'); ?></th>
								<th><?php print $this->Paginator->sort('conta_plano_id','Plano de contas'); ?></th>
								<th colspan="2">Ações</th>
							</tr>
						</thead>

						<tbody>
							<?php foreach ($resultados as $r) :
								if (strtoupper($r['ContaPagar']['eh_cliente_ou_fornecedor']) == 'C') $tipo='cliente';
								else $tipo='fornecedor';
								?>
								<tr>
									<td><?php print $r['ContaPagar']['id']; ?></td>
									<td><?php print ucfirst($tipo); ?></td>
									<td>
										<?php
										if ($tipo == 'cliente') print $this->Html->link($r['Cliente']['nome'],'editar/' . $r['ContaPagar']['id']) ;
										else if ($tipo == 'fornecedor') print $this->Html->link($r['Fornecedor']['nome'],'editar/' . $r['ContaPagar']['id']) ;
										?>
									</td>
									<td><?php print $r['ContaPagar']['documento_tipo_id'].' '.$r['DocumentoTipo']['nome']; ?></td>
									<td><?php print $r['ContaPagar']['numero_documento']; ?></td>
									<td><?php print $r['ContaPagar']['valor'] ?></td>
									<td><?php print $r['ContaPagar']['conta_origem'].' '.$r['Conta']['nome'] ?></td>
									<td><?php print $r['ContaPagar']['conta_plano_id'].' '.$r['ContaPlano']['nome'] ; ?></td>
									<td>
										<?php print $this->element('painel_editar',array('id'=>$r['ContaPagar']['id'])) ;?>
									</td>
									<td>
										<?php print $this->element('painel_excluir',array('id'=>$r['ContaPagar']['id'])) ;?>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>

					<?php
					$this->Paginator->options (array (
						'update' => '#conteudo',
						'before' => $this->Js->get('.indicador_carregando')->effect('fadeIn', array('buffer' => false)),
						'complete' => $this->Js->get('.indicador_carregando')->effect('fadeOut', array('buffer' => false)),
					));
					print $this->Paginator->pagination(); ?>
				</fieldset>
			<?php endif; ?>
		
		</fieldset>
	</div>
</div>