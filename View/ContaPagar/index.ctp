<div class="row-fluid">
	
	<div class="span12">
		
		<fieldset>
			<legend class="descricao_cabecalho">
				Exibindo as contas a pagar
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
						<th><?php print $this->Paginator->sort('eh_cliente_ou_fornecedor','Cliente ou fornecedor?'); ?></th>
						<th><?php print $this->Paginator->sort('cliente_fornecedor_id','Cliente / fornecedor'); ?></th>
						<th><?php print $this->Paginator->sort('documento_tipo_id','Documento'); ?></th>
						<th><?php print $this->Paginator->sort('numero_documento','N. documento'); ?></th>
						<th><?php print $this->Paginator->sort('valor','Valor'); ?></th>
						<th><?php print $this->Paginator->sort('situacao','Situação'); ?></th>
						<th><?php print $this->Paginator->sort('conta_plano_id','Plano de contas'); ?></th>
						<th><?php print $this->Paginator->sort('data_vencimento','Vencimento'); ?></th>
						<th colspan="2">Ações</th>
					</tr>
				</thead>

				<tbody>

			<?php foreach ($consulta_conta_pagar as $c):
				if (strtoupper($c['ContaPagar']['eh_cliente_ou_fornecedor']) == 'C') $tipo = 'cliente';
				else $tipo = 'fornecedor';
			?>

					<tr>
						<td><?php print $c['ContaPagar']['id'];?></td>
						<td><?php print ucfirst($tipo); ?></td>
						<td>
							<?php
							print $c['ContaPagar']['cliente_fornecedor_id'].' ';
							if ($tipo == 'cliente') print $this->Html->link($c['Cliente']['nome'],'editar/'.$c['ContaPagar']['id']);
							else if ($tipo == 'fornecedor') print $this->Html->link($c['Fornecedor']['nome'],'editar/'.$c['ContaPagar']['id']);
							?>
						</td>
						<td><?php print $c['ContaPagar']['documento_tipo_id'].' '.$c['DocumentoTipo']['nome']; ?></td>
						<td><?php print $c['ContaPagar']['numero_documento']; ?></td>
						<td><?php print $c['ContaPagar']['valor']; ?></td>
						<td><?php print $opcoes_situacoes[$c['ContaPagar']['situacao']] ?></td>
						<td><?php print $c['ContaPagar']['conta_plano_id'].' '.$c['ContaPlano']['nome']; ?></td>
						<td><?php print $this->Formatacao->data($c['ContaPagar']['data_vencimento']); ?></td>
						<td>
							<?php print $this->element('painel_editar',array('id'=>$c['ContaPagar']['id'])) ;?>
						</td>
						<td>
							<?php print $this->element('painel_excluir',array('id'=>$c['ContaPagar']['id'])) ;?>
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