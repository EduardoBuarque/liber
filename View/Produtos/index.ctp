<div class="row-fluid">
	
	<div class="span12">
		
		<fieldset>
			<legend class="descricao_cabecalho">
				Exibindo os produtos cadastrados
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
					<th><?php print $this->Paginator->sort('id',__('Código')); ?></th>
					<th><?php print $this->Paginator->sort('nome',__('Nome')); ?></th>
					<th><?php print $this->Paginator->sort('produto_categoria_id',__('Categoria')); ?></th>
					<th><?php print $this->Paginator->sort('preco_custo',__('Preço de custo')); ?></th>
					<th><?php print $this->Paginator->sort('preco_venda',__('Preço de venda')); ?></th>
					<th><?php print $this->Paginator->sort('margem_lucro',__('Margem de lucro')); ?></th>
					<th><?php print $this->Paginator->sort('quantidade_estoque_fiscal',__('Quantidade estoque fiscal')); ?></th>
					<th><?php print $this->Paginator->sort('quantidade_estoque_nao_fiscal',__('Quantidade estoque não fiscal')); ?></th>
					<th colspan="1">Ações</th>
				</tr>
			</thead>

			<tbody>

		<?php foreach ($consulta as $c): ?>

				<tr>
					<td><?php print $c['Produto']['id'];?></td>
					<td><?php print $this->Html->link($c['Produto']['nome'],'editar/' . $c['Produto']['id']) ;?></td>
					<td><?php print $c['ProdutoCategoria']['nome']; ?></td>
					<td><?php print $c['Produto']['preco_custo']; ?></td>
					<td><?php print $c['Produto']['preco_venda']; ?></td>
					<td><?php print $c['Produto']['margem_lucro']; ?></td>
					<td><?php print $c['Produto']['quantidade_estoque_fiscal']; ?></td>
					<td><?php print $c['Produto']['quantidade_estoque_nao_fiscal']; ?></td>
					<td>
						<?php print $this->element('painel_editar',array('id'=>$c['Produto']['id'])) ;?>
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