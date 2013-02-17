<?php
print $this->Html->script('formatar_moeda');
?>

<script type="text/javascript">
	$(function(){
		$(".datepicker").datepicker();
	
		$('#CompraPedidoValorTotal').priceFormat();
		
		//pesquisa fornecedor
		//autocomplete
		$("#CompraPedidoFornecedorNome").autocomplete({
			source: "<?php print $this->Html->url('/',true); ?>/Fornecedors/pesquisaAjaxFornecedor/nome",
			minLength: 3,
			select: function(event, ui) {
				$("#CompraPedidoFornecedorId").val(ui.item.id);
				$('#CompraPedidoFornecedorNome').val(ui.item.nome);
			}
		});
		// ao digitar o codigo
		$('#CompraPedidoFornecedorId').blur(function(){
			codigo = $(this).val();
			if (codigo == null || codigo == '') return null;
			$.getJSON('<?php print $this->Html->url('/',true); ?>/Fornecedors/pesquisaAjaxFornecedor/codigo', {'term': codigo}, function(data) {
				if (data == null) {
					alert ('Fornecedor com o código '+codigo+' não foi encontrado!');
					$('#CompraPedidoFornecedorNome').val('');
					$("#CompraPedidoFornecedorId")
						.val('')
						.focus();
				}
				else { //encontrou resultados
					data = data[0];
					$("#CompraPedidoFornecedorId").val(data.id);
					$('#CompraPedidoFornecedorNome').val(data.nome);
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
			<legend class="descricao_cabecalho"><?php print __('Pesquisar pedido de compra');?></legend>
			
			<div class="row-fluid">
				
				<div class="span2 visible-desktop">

					<ul class="nav nav-pills nav-stacked">

						<li class="nav-header">
							Ações
						</li>
						<li>
							<a href="<?php print $this->Html->url(array('controller'=>'CompraPedidos','action'=>'index'));?>" onclick="formulario_cancelar(); return false;">
								<i class="icon-remove"></i>
								Cancelar
							</a>
						</li>

						<li class="nav-header">
							Pedidos de compra
						</li>
						<li>
							<a href="<?php print $this->Html->url(array('controller'=>'CompraPedidos','action'=>'cadastrar'));?>">
								<i class="icon-file"></i>
								Cadastrar
							</a>
						</li>
						<li>
							<a href="<?php print $this->Html->url(array('controller'=>'CompraPedidos','action'=>'editar'));?>">
								<i class="icon-edit"></i>
								Editar
							</a>
						</li>
						<li class="active">
							<a href="<?php print $this->Html->url(array('controller'=>'CompraPedidos','action'=>'pesquisar'));?>">
								<i class="icon-filter"></i>
								Pesquisar
							</a>
						</li>
						<li>
							<a href="<?php print $this->Html->url(array('controller'=>'CompraPedidos','action'=>'index'));?>">
								<i class="icon-list"></i>
								Listar
							</a>
						</li>

						<li class="nav-header">
							Produtos
						</li>
						<li>
							<a href="<?php print $this->Html->url(array('controller'=>'Produtos','action'=>'cadastrar'));?>">
								<i class="icon-file"></i>
								Cadastrar
							</a>
							<a href="<?php print $this->Html->url(array('controller'=>'Produtos','action'=>'editar'));?>">
								<i class="icon-edit"></i>
								Editar
							</a>
							<a href="<?php print $this->Html->url(array('controller'=>'Produtos','action'=>'pesquisar'));?>">
								<i class="icon-filter"></i>
								Pesquisar
							</a>
							<a href="<?php print $this->Html->url(array('controller'=>'Produtos','action'=>'index'));?>">
								<i class="icon-list"></i>
								Listar
							</a>
						</li>

					</ul>

				</div>

				<div class="span10">
					<?php
					if ($this->Ajax->isAjax()) {
						print $this->Ajax->form('pesquisar','post',array('autocomplete'=>'off','model'=>'CompraPedido','update'=>'conteudo_ajax'));
					}
					else {
						print $this->Form->create(null,array('controller'=>'compraPedido','action'=>'pesquisar','autocomplete'=>'off'));
					}

					$this->Form->defineRow(array(2,2,6,2));
					print $this->Form->input('CompraPedido.id',array('label'=>__('Código pedido'),'type'=>'text'));
					print $this->Form->input('CompraPedido.fornecedor_id',array('label'=>__('Código do fornecedor'),'type'=>'text'));
					print $this->Form->input('CompraPedido.fornecedor_nome',array('label'=>__('Nome do fornecedor'),'type'=>'text'));
					print $this->Form->input('CompraPedido.valor_total',array('label'=>__('Valor total'),'type'=>'text'));
					$this->Form->defineRow(array(2,2,2));
					print $this->Form->input('CompraPedido.data_hora_cadastrado',array('label'=>__('Data e hora do cadastro'),'type'=>'text','class'=>'datepicker mascara_data'));
					print $this->Form->input('situacao', array( 'label'=>__('Situação'), 'options'=>$opcoes_situacoes,'empty'=>'Selecione'));
					print $this->Form->input('usuario_cadastrou', array( 'label'=>__('Usuário cadastrou'), 'options'=>$opcoes_usuarios,'empty'=>'Selecione'));
					?>

					<?php print $this->Form->end(array('label'=>__('Pesquisar'))); ?>

					<?php if (isset($num_resultados) && $num_resultados > 0) : ?>
						<fieldset>
							<legend><?php print __('Resultados'); ?></legend>
							<table class="table table-striped">
								<thead>
									<tr>
										<th><?php print $this->Paginator->sort('id','Cód'); ?></th>
										<th><?php print $this->Paginator->sort('data_hora_cadastrado','Cadastrado em'); ?></th>
										<th><?php print $this->Paginator->sort('fornecedor_id','Fornecedor'); ?></th>
										<th><?php print $this->Paginator->sort('situacao','Situação'); ?></th>
										<th><?php print $this->Paginator->sort('valor_liquido','Valor total'); ?></th>
										<th colspan="3">Ações</th>
									</tr>
								</thead>

								<tbody>
									<?php foreach ($resultados as $r): ?>
										<tr>
											<td><?php print $r['CompraPedido']['id']; ?></td>
											<td><?php print $this->Html->link($this->Formatacao->dataHora($r['CompraPedido']['data_hora_cadastrado']),'editar/' . $r['CompraPedido']['id']) ;?></td>
											<td><?php print $r['CompraPedido']['fornecedor_id'].' '.$r['Fornecedor']['nome']; ?></td>
											<td><?php print $opcoes_situacoes[$r['CompraPedido']['situacao']]; ?></td>
											<td><?php print $r['CompraPedido']['valor_liquido']; ?></td>
											<td>
												<?php print $this->element('painel_detalhar',array('id'=>$r['CompraPedido']['id'])) ;?>
											</td>
											<td>
												<?php print $this->element('painel_editar',array('id'=>$r['CompraPedido']['id'])) ;?>
											</td>
											<td>
												<?php print $this->element('painel_excluir',array('id'=>$r['CompraPedido']['id'])) ;?>
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
							print $this->Paginator->pagination(); ?>
						</fieldset>
					<?php endif; ?>
				</div>
		</fieldset>
	
	</div>
</div>