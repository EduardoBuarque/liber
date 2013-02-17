<div class="row-fluid">
	
	<div class="span12">
		
		<fieldset>
			<legend class="descricao_cabecalho">
				Exibindo as formas de pagamento cadastradas
				<?php
				if ($this->Ajax->isAjax()) {
					print $this->element('painel_index_ajax');
				}
				else {
					print $this->element('painel_index');
				}
				?>
			</legend>

			<table class="table table-bordered">
				<thead>
					<tr>
						<th><?php print $this->Paginator->sort('id','Código'); ?></th>
						<th><?php print $this->Paginator->sort('nome','Nome'); ?></th>
						<th><?php print $this->Paginator->sort('conta_pricipal','Conta principal'); ?></th>
						<th colspan="2">Ações</th>
					</tr>
				</thead>

				<tbody>

			<?php foreach ($consulta_pagamento_tipo as $consulta): ?>

					<tr>
						<td><?php print $consulta['PagamentoTipo']['id'];?></td>
						<td><?php print $this->Html->link($consulta['PagamentoTipo']['nome'],'editar/' . $consulta['PagamentoTipo']['id']) ;?></td>
						<td><?php print $consulta['PagamentoTipo']['conta_principal'].' '.
						$consulta['Conta']['nome']; ?></td>
						<td>
							<?php print $this->element('painel_editar',array('id'=>$consulta['PagamentoTipo']['id'])) ;?>
						</td>
						<td>
							<?php print $this->element('painel_excluir',array('id'=>$consulta['PagamentoTipo']['id'])) ;?>
						</td>
					</tr>

			<?php endforeach ?>

				</tbody>
			</table>

			<?php
			$this->Paginator->options (array (
				'update' => '#conteudo',
				'before' => $this->Js->get('.indicador_carregando')->effect('fadeIn', array('buffer' => false)),
				'complete' => $this->Js->get('.indicador_carregando')->effect('fadeOut', array('buffer' => false)),
			));

			print $this->Paginator->pagination();
			?>

		</fieldset>
		
	</div>
	
</div>