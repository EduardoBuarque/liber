<div class="row-fluid">
	
	<div class="span12">
		
		<fieldset>
			<legend class="descricao_cabecalho">
				Exibindo os carregamentos cadastrados
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
						<th><?php print $this->Paginator->sort('data_hora_criado','Criado em'); ?></th>
						<th><?php print $this->Paginator->sort('descricao','Descrição'); ?></th>
						<th><?php print $this->Paginator->sort('situacao','Situação'); ?></th>
						<th colspan="2">Ações</th>
					</tr>
				</thead>

				<tbody>

			<?php foreach ($consulta as $carregamento): ?>

					<tr>
						<td><?php print $carregamento['Carregamento']['id'];?></td>
						<td><?php print $this->Html->link($this->Formatacao->dataHora($carregamento['Carregamento']['data_hora_criado']),'detalhar/' . $carregamento['Carregamento']['id']) ;?></td>
						<td><?php print $carregamento['Carregamento']['descricao']; ?></td>
						<td><?php print $opcoes_situacoes[$carregamento['Carregamento']['situacao']]; ?></td>
						<td>
							<?php print $this->element('painel_detalhar',array('id'=>$carregamento['Carregamento']['id'])) ;?>
						</td>
						<td>
							<?php print $this->element('painel_excluir',array('id'=>$carregamento['Carregamento']['id'])) ;?>
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
