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
			<legend class="descricao_cabecalho">Pesquisar carregamento</legend>

			<?php
			print $this->Form->create(null,array('controller'=>'carregamentos','action'=>'pesquisar','autocomplete'=>'off')); ?>
			
			<div class="row-fluid">
				
				<div class="span6">
					<?php $this->Form->defineRow(array(4,4,4));
					print $this->Form->input('data_inicial', array('label'=>'Data Inicial','div'=>false,'class'=>'datepicker mascara_data'));
					print $this->Form->input('data_final',array('div'=>false,'class'=>'datepicker mascara_data'));
					print $this->Form->input('situacao',array('div'=>false,'label'=>'Situação','options'=>$opcoes_situacoes,'empty'=>'Selecione'));
					?>
				</div>
				<div class="span6">
					<?php
					$this->Form->defineRow(array(4,4,4));
					print $this->Form->input('descricao',array('label'=>'Descrição','div'=>false));
					print $this->Form->input('motorista',array('label'=>'Motorista','div'=>false,'options'=>$opcoes_motoristas,'empty'=>'Selecione'));
					print $this->Form->input('veiculo',array('label'=>'Veículo','div'=>false,'options'=>$opcoes_veiculos,'empty'=>'Selecione'));
					?>
				</div>
				
			</div>
			
			<?php print $this->Form->end(array('label'=>__('Pesquisar'))); ?>

			<?php if (isset($num_resultados) && $num_resultados > 0) : ?>
			
				<fieldset>
					<legend><?php print __('Resultados'); ?></legend>
					<table class="table table-condensed">
						<thead>
							<tr>
								<th><?php print $this->Paginator->sort('id','Cód'); ?></th>
								<th><?php print $this->Paginator->sort('data_hora_criado','Criado em'); ?></th>
								<th><?php print $this->Paginator->sort('situacao','Situação'); ?></th>
								<th><?php print $this->Paginator->sort('descricao','Descrição'); ?></th>
								<th><?php print $this->Paginator->sort('motorista','Motorista'); ?></th>
								<th><?php print $this->Paginator->sort('veiculo','Veículo'); ?></th>
								<th colspan="2">Ações</th>
							</tr>
						</thead>

						<tbody>
							<?php foreach ($resultados as $r) : ?>
								<tr>
									<td><?php print $r['Carregamento']['id']; ?></td>
									<td><?php print $this->Html->link($r['Carregamento']['data_hora_criado'],'detalhar/' . $r['Carregamento']['id']) ;?></td>
									<td><?php print $opcoes_situacoes[$r['Carregamento']['situacao']]; ?></td>
									<td><?php print $r['Carregamento']['descricao']; ?></td>
									<td><?php print $r['Motorista']['nome']; ?></td>
									<td><?php print $r['Veiculo']['placa']; ?></td>
									<td>
									<?php print '<a title="Excluir" onclick="javascript: return confirm(\'Deseja realmente excluir este registro?\')"
									href="'.$this->Html->url(array('action'=>'excluir')).'/'.$r['Carregamento']['id'].'">'.
									$this->Html->image('del24x24.png', array('alt'=>'Excluir'))
									.'</a>';?>
									</td>
									<td><?php print $this->Html->image('detalhar24x24.png',array('title'=>'Detalhar',
									'alt'=>'Detalhar','url'=>array('action'=>'detalhar',$r['Carregamento']['id']))) ?></td>
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