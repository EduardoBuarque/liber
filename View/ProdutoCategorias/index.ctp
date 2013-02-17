<div class="row-fluid">
	
	<div class="span12">
		
		<fieldset>
			
			<legend class="descricao_cabecalho">
				Exibindo as categorias de produto
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
						<th><?php print $this->Paginator->sort('descricao','Descrição'); ?></th>
						<th colspan="2">Ações</th>
					</tr>
				</thead>

				<tbody>

			<?php foreach ($consulta as $c): ?>

					<tr>
						<td><?php print $c['ProdutoCategoria']['id'];?></td>
						<td><?php print $this->Html->link($c['ProdutoCategoria']['nome'],'editar/' . $c['ProdutoCategoria']['id']) ;?></td>
						<td>
							<?php print $this->element('painel_editar',array('id'=>$c['ProdutoCategoria']['id'])) ;?>
						</td>
						<td>
							<?php print $this->element('painel_excluir',array('id'=>$c['ProdutoCategoria']['id'])) ;?>
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
