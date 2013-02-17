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
			<legend class="descricao_cabecalho">Pesquisar produto</legend>

			<?php
			print $this->Form->create(null,array('controller'=>'produtos','action'=>'pesquisar','autocomplete'=>'off'));
			?>

			<?php
			$this->Form->defineRow(array(5,3,3));
			print $this->Form->input('nome', array('label'=>__('Nome')));
			print $this->Form->input('codigo_ean',array('label'=>__('Código EAN')));
			print $this->Form->input('codigo_dun',array('label'=>__('Código DUN')));
			
			$this->Form->defineRow(array(3,3,3,3));
			print $this->Form->input('unidade',array('label'=>__('Unidade')));
			print $this->Form->input('quantidade_estoque_fiscal',array('label'=>__('Qtd. estoque fiscal')));
			print $this->Form->input('produto_categoria_id', array('label'=>__('Categoria'),'options'=>$opcoes_categoria_produto));
			print $this->Form->input('tipo_produto',array('label'=>__('Tipo'),'options'=>$opcoes_tipos));
			
			$this->Form->defineRow(array(3));
			print $this->Form->input('situacao',array('label'=>__('Situação'),'options'=>$opcoes_situacoes));
			?>
			
			<?php print $this->Form->end(array('label'=>__('Pesquisar'))); ?>

			<?php if (isset($num_resultados) && $num_resultados > 0) : ?>
				<table class="table table-striped">
					<thead>
						<tr>
							<th><?php print $this->Paginator->sort('id','Cód'); ?></th>
							<th><?php print $this->Paginator->sort('nome','Nome'); ?></th>
							<th><?php print $this->Paginator->sort('produto_categoria_id','Categoria'); ?></th>
							<th><?php print $this->Paginator->sort('tipo_produto','Tipo produto'); ?></th>
							<th><?php print $this->Paginator->sort('preco_custo','Preço de custo'); ?></th>
							<th><?php print $this->Paginator->sort('preco_venda','Preço de venda'); ?></th>
							<th><?php print $this->Paginator->sort('quantidade_estoque_fiscal','Quantidade'); ?></th>
							<th><?php print $this->Paginator->sort('situacao','Situação'); ?></th>
							<th colspan="2">Ações</th>
						</tr>
					</thead>

					<tbody>
						<?php foreach ($resultados as $r) : ?>
							<tr>
								<td><?php print $r['Produto']['id']; ?></td>
								<td><?php print $this->Html->link($r['Produto']['nome'],'editar/' . $r['Produto']['id']) ;?></td>
								<td><?php print $r['Produto']['produto_categoria_id'].' '.$r['ProdutoCategoria']['nome']; ?></td>
								<td><?php print $r['Produto']['tipo_produto']; ?></td>
								<td><?php print $r['Produto']['preco_custo']; ?></td>
								<td><?php print $r['Produto']['preco_venda']; ?></td>
								<td><?php print $r['Produto']['quantidade_estoque_fiscal']; ?></td>
								<td><?php print $opcoes_situacoes[$r['Produto']['situacao']]; ?></td>
								<td>
									<?php print $this->element('painel_detalhar',array('id'=>$r['Produto']['id'])) ;?>
								</td>
								<td>
									<?php print $this->element('painel_editar',array('id'=>$r['Produto']['id'])) ;?>
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
