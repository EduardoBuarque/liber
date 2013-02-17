<?php print $this->Html->script('formatar_moeda'); ?>

<script type="text/javascript">
	$(function(){
		$(".datepicker").datepicker();
	
		$('#ServicoOrdemValorTotal').priceFormat();
		
		//pesquisa cliente
		//autocomplete
		$("#ServicoOrdemClienteNome").autocomplete({
			source: site_raiz+"/Clientes/pesquisaAjaxCliente/nome",
			minLength: 3,
			select: function(event, ui) {
				$("#ServicoOrdemClienteId").val(ui.item.id);
				$('#ServicoOrdemClienteNome').val(ui.item.nome);
			}
		});
		// ao digitar o codigo
		$('#ServicoOrdemClienteId').blur(function(){
			codigo = $(this).val();
			if (codigo == null || codigo == '') return null;
			$.getJSON(site_raiz+'/Clientes/pesquisaAjaxCliente/codigo', {'term': codigo}, function(data) {
				if (data == null) {
					alert ('Cliente com o código '+codigo+' não foi encontrado!');
					$('#ServicoOrdemClienteNome').val('');
					$("#ServicoOrdemClienteId")
						.val('')
						.focus();
				}
				else { //encontrou resultados
					data = data[0];
					$("#ServicoOrdemClienteId").val(data.id);
					$('#ServicoOrdemClienteNome').val(data.nome);
				}
			});
		});

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
			<legend class="descricao_cabecalho"><?php print __('Pesquisar ordem de serviço');?></legend>

			<?php
			/**
			* Elimino as divs dos campos input para que nao apareça quais campos
			* sao marcados como obrigatorios no BD
			*/
			if ($this->Ajax->isAjax()) {
				print $this->Ajax->form('pesquisar','post',array('autocomplete'=>'off','model'=>'ServicoOrdem','update'=>'conteudo_ajax'));
			}
			else {
				print $this->Form->create(null,array('controller'=>'servicoOrdens','action'=>'pesquisar','autocomplete'=>'off'));
			}
			?>

			<div class="row-fluid">
				
				<div class="span6">
					<?php
					$this->Form->defineRow(array(12));
					print $this->Form->input('id',array('label'=>__('Código')));
					$this->Form->defineRow(array(12));
					print $this->Form->input('data_hora_cadastrada',array('label'=>__('Cadastrada em'),'class'=>'datepicker mascara_data'));
					$this->Form->defineRow(array(12));
					print $this->Form->input('data_hora_inicio',array('label'=>__('Iniciada em'),'class'=>'datepicker mascara_data'));
					$this->Form->defineRow(array(12));
					print $this->Form->input('data_hora_fim',array('label'=>__('Terminada em'),'class'=>'datepicker mascara_data'));
					$this->Form->defineRow(array(12));
					print $this->Form->input('valor_total',array('label'=>__('Valor total')));
					?>
				</div>

				<div class="span6">
					<?php
					$this->Form->defineRow(array(2,10));
					print $this->Form->input('cliente_id',array('label'=>__('Cód.'),'type'=>'text'));
					print $this->Form->input('cliente_nome',array('label'=>__('Nome cliente')));
					$this->Form->defineRow(array(4));
					print $this->Form->input('tecnico',array('label'=>__('Técnico'),'options'=>$opcoes_tecnico,'empty'=>'Selecione'));
					$this->Form->defineRow(array(4));
					print $this->Form->input('situacao',array('label'=>__('Situação'),'options'=>$opcoes_situacao,'empty'=>'Selecione'));
					$this->Form->defineRow(array(4));
					print $this->Form->input('usuario_cadastrou',array('label'=>__('Usuário que cadastrou'),'options'=>$opcoes_usuarios,'empty'=>'Selecione'));
					?>
				</div>
				
			</div>
			
			<?php print $this->Form->end(array('label'=>__('Pesquisar'))); ?>

				<?php if (isset($num_resultados) && $num_resultados > 0) : ?>
					<table class="table table-striped">
						<thead>
							<tr>
								<th><?php print $this->Paginator->sort('id','Cód'); ?></th>
								<th><?php print $this->Paginator->sort('data_hora_cadastrada','Cadastrada em'); ?></th>
								<th><?php print $this->Paginator->sort('cliente_id','Cliente'); ?></th>
								<th><?php print $this->Paginator->sort('situacao','Situação'); ?></th>
								<th><?php print $this->Paginator->sort('valor_liquido','Valor total'); ?></th>
								<th colspan="3">Ações</th>
							</tr>
						</thead>

						<tbody>
							<?php foreach ($resultados as $r): ?>
								<tr>
									<td><?php print $r['ServicoOrdem']['id']; ?></td>
									<td><?php print $this->Html->link($this->Formatacao->dataHora($r['ServicoOrdem']['data_hora_cadastrada']),'editar/' . $r['ServicoOrdem']['id']) ;?></td>
									<td><?php print $r['ServicoOrdem']['cliente_id'].' '.$r['Cliente']['nome']; ?></td>
									<td><?php print $opcoes_situacao[$r['ServicoOrdem']['situacao']]; ?></td>
									<td><?php print $r['ServicoOrdem']['valor_liquido']; ?></td>
									<td>
										<?php print $this->element('painel_detalhar',array('id'=>$r['ServicoOrdem']['id'])) ;?>
									</td>
									<td>
										<?php print $this->element('painel_editar',array('id'=>$r['ServicoOrdem']['id'])) ;?>
									</td>
									<td>
										<?php print $this->element('painel_excluir',array('id'=>$r['ServicoOrdem']['id'])) ;?>
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
				print $this->Paginator->pagination();

			endif;
			?>
		</fieldset>
	
	</div>
	
</div>
