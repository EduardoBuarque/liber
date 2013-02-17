<?php print $this->Html->script('fornecedor'); ?>

<div class="row-fluid">
	
	<div class="span2 visible-desktop">
		<ul class="nav nav-pills nav-stacked" style="margin-top: 35px;">

			<li class="nav-header">
				Ações
			</li>
			<li>
				<a href="<?php print $this->Html->url(array('controller'=>'Fornecedores','action'=>'index'));?>" onclick="formulario_cancelar(); return false;">
					<i class="icon-remove"></i>
					Cancelar
				</a>
			</li>

			<li class="nav-header">
				Fornecedores
			</li>
			<li class="active">
				<a href="<?php print $this->Html->url(array('controller'=>'Fornecedores','action'=>'cadastrar'));?>">
					<i class="icon-file"></i>
					Cadastrar
				</a>
			</li>
			<li>
				<a href="<?php print $this->Html->url(array('controller'=>'Fornecedores','action'=>'editar'));?>">
					<i class="icon-edit"></i>
					Editar
				</a>
			</li>
			<li>
				<a href="<?php print $this->Html->url(array('controller'=>'Fornecedores','action'=>'pesquisar'));?>">
					<i class="icon-filter"></i>
					Pesquisar
				</a>
			</li>
			<li>
				<a href="<?php print $this->Html->url(array('controller'=>'Fornecedores','action'=>'index'));?>">
					<i class="icon-list"></i>
					Listar
				</a>
			</li>

			<li class="nav-header">
				Categorias
			</li>
			<li>
				<a href="<?php print $this->Html->url(array('controller'=>'FornecedorCategorias','action'=>'cadastrar'));?>">
					<i class="icon-file"></i>
					Cadastrar
				</a>
				<a href="<?php print $this->Html->url(array('controller'=>'FornecedorCategorias','action'=>'index'));?>">
					<i class="icon-list"></i>
					Listar
				</a>
			</li>
		</ul>
	</div>

	<div class="span10">
		<?php
		if ($this->Ajax->isAjax()) {
			print $this->Ajax->form('cadastrar','post',array('autocomplete'=>'off','model'=>'Fornecedor','update'=>'conteudo_ajax'));
		}
		else {
			print $this->Form->create('Fornecedor',array('autocomplete'=>'off','onsubmit'=>'submissaoFormulario(this); return false;'));
		}
		?>

		<fieldset>
			<legend class="descricao_cabecalho"><?php print __('Adicionar fornecedor');?></legend>

			<?php
			$this->Form->defineRow(array(3,3,3));
			print $this->Form->input('tipo_pessoa', array(
				'label'=>__('Tipo pessoa'),
				'options'=>array(''=>'','F'=>'Física','J'=>'Jurídica')
				));

			print $this->Form->input('situacao',array(
			'label'=>__('Situação'),
			'options'=>array(''=>'','A'=>'Ativo','I'=>'Inativo','B'=>'Bloqueado')
			));

			print $this->Form->input('fornecedor_categoria_id',array(
			'label'=>__('Categoria do fornecedor'),
			'options'=>$opcoes_categoria_fornecedor
			));

			$this->Form->defineRow(array(6,6));
			print $this->Form->input('nome', array('label'=>__('Nome')));
			print $this->Form->input('nome_fantasia', array('label'=>__('Nome fantasia')));

			$this->Form->defineRow(array(3,2,2,3));
			print $this->Form->input('logradouro_nome', array('label'=>__('Logradouro')));
			print $this->Form->input('logradouro_numero', array('label'=>__('Número')));
			print $this->Form->input('logradouro_complemento', array('label'=>__('Complemento')));
			print $this->Form->input('bairro',array('label'=>__('Bairro')));

			$this->Form->defineRow(array(3,3,3));
			print $this->Form->input('cidade',array('label'=>__('Cidade')));
			?>	
			<div class="control-group  required span3">
				<label class="control-label" for="FornecedorUf">UF:</label>
				<div class="controls">
					<?php print $this->Estados->select('uf'); ?>
				</div>
			</div>
			<?php		
			print $this->Form->input('cep', array('label'=>__('CEP')));
			print $this->Form->input('numero_telefone', array('label'=>__('Número de telefone')));

			$this->Form->defineRow(array(3,3,3));
			print $this->Form->input('endereco_email', array('label'=>__('Endereço de e-mail')));
			print $this->Form->input('cnpj',array('label'=>__('CNPJ'), 'disabled'=>'disabled'));
			print $this->Form->input('inscricao_estadual',array('label'=>__('Inscrição estadual'), 'disabled'=>'disabled'));

			$this->Form->defineRow(array(3,3,3));
			print $this->Form->input('cpf',array('label'=>__('CPF'), 'disabled'=>'disabled'));
			print $this->Form->input('rg',array('label'=>__('RG'), 'disabled'=>'disabled'));
			print $this->Form->input('inscricao_municipal',array('label'=>__('Registro municipal')));

			print '<div class="clearfix"></div>';
			$this->Form->defineRow(array(12));
			print $this->Form->input('observacao', array('label'=>__('Observação')) );
			?>

		</fieldset>

		<?php print $this->Form->end(array('label'=>__('Gravar'),'class'=>'btn btn-primary','div'=>array('class'=>'form-actions'))); ?>

	</div>
	
</div>