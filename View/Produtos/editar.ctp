<?php
print $this->Html->script('formatar_moeda');
print $this->Html->script('produto');
?>

<div class="row-fluid">
	
	<div class="span2">
		<ul class="nav nav-pills nav-stacked" style="margin-top: 35px;">

			<li class="nav-header">
				Ações
			</li>
			<li>
				<a href="<?php print $this->Html->url(array('controller'=>'Produtos','action'=>'index'));?>" onclick="return(confirm('Cancelar edição?'));">
					<i class="icon-remove"></i>
					Cancelar
				</a>
			</li>
			<li>
				<input type="checkbox" name="ProdutoCalcularPreco" id="ProdutoCalcularPreco" checked="checked" />
				<small>Calcular preço de venda?</small>
			</li>

			<li class="nav-header">
				Produtos
			</li>
			<li>
				<a href="<?php print $this->Html->url(array('controller'=>'Produtos','action'=>'cadastrar'));?>">
					<i class="icon-file"></i>
					Cadastrar
				</a>
			</li>
			<li class="active">
				<a href="<?php print $this->Html->url(array('controller'=>'Produtos','action'=>'editar'));?>">
					<i class="icon-edit"></i>
					Editar
				</a>
			</li>
			<li>
				<a href="<?php print $this->Html->url(array('controller'=>'Produtos','action'=>'pesquisar'));?>">
					<i class="icon-filter"></i>
					Pesquisar
				</a>
			</li>
			<li>
				<a href="<?php print $this->Html->url(array('controller'=>'Produtos','action'=>'index'));?>">
					<i class="icon-list"></i>
					Listar
				</a>
			</li>

			<li class="nav-header">
				Categorias
			</li>
			<li>
				<a href="<?php print $this->Html->url(array('controller'=>'ProdutoCategorias','action'=>'cadastrar'));?>">
					<i class="icon-file"></i>
					Cadastrar
				</a>
				<a href="<?php print $this->Html->url(array('controller'=>'ProdutoCategorias','action'=>'index'));?>">
					<i class="icon-list"></i>
					Listar
				</a>
			</li>
		</ul>
	</div>

	<div class="span10">

		<?php
		if ($this->Ajax->isAjax()) {
			print $this->Ajax->form('editar','post',array('autocomplete'=>'off','model'=>'Produto','update'=>'conteudo_ajax'));

		}
		else {
			print $this->Form->create('Produto',array('autocomplete'=>'off','onsubmit'=>'submissaoFormulario(this); return false;'));
		}
		?>
		<fieldset>
			<legend class="descricao_cabecalho"><?php print __('Editar Produto');?></legend>
			<?php
			$this->Form->defineRow(array(3,3,3,3));
			print $this->Form->input('produto_categoria_id', array(
				'label'=>__('Categoria'),
				'options'=>$opcoes_categoria_produto
				));
			print $this->Form->input('situacao',array(
				'label'=>__('Situação'),
				'options'=>$opcoes_situacoes
				));
			print $this->Form->input('tipo_produto',array('label'=>__('Tipo'),'options'=>array($opcoes_tipos)));
			print $this->Form->input('tem_estoque_ilimitado',array('label'=>'Estoque ilimitado?','options'=>array('0'=>'Não','1'=>'Sim')));
			
			$this->Form->defineRow(array(6,3,3));
			print $this->Form->input('nome',array('label'=>'Nome'));
			print $this->Form->input('unidade',array('label'=>'Unidade'));
			print $this->Form->input('estoque_minimo',array('label'=>'Estoque mínimo'));
			
			$this->Form->defineRow(array(3,3,3,3));
			print $this->Form->input('quantidade_estoque_fiscal',array('label'=>'Qtd. estoque fiscal'));
			print $this->Form->input('quantidade_estoque_nao_fiscal',array('label'=>'Qtd. estoque não fiscal'));
			print $this->Form->input('codigo_ean',array('label'=>'Código EAN'));
			print $this->Form->input('codigo_dun',array('label'=>'Código DUN'));
			?>
			
			<?php
			print $this->Form->input('preco_custo',array('label'=>'Preço de custo','type'=>'text'));
			print $this->Form->input('preco_venda',array('label'=>'Preço de venda','type'=>'text'));
			print $this->Form->input('margem_lucro',array('label'=>'Margem de lucro (%)','type'=>'text'));
			?>
		</fieldset>

		<?php print $this->Form->end(array('label'=>__('Gravar'),'class'=>'btn btn-primary','div'=>array('class'=>'form-actions'))); ?>

	</div>
	
</div>