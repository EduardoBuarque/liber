<div class="row-fluid">
	
	<div class="span12">
		
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
					<li>
						<a href="<?php print $this->Html->url(array('controller'=>'CompraPedidos','action'=>'pesquisar'));?>">
							<i class="icon-filter"></i>
							Pesquisar
						</a>
					</li>
					<li class="active">
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
				
				<fieldset>
					<legend class="descricao_cabecalho">
						Exibindo os pedidos de compra
						<?php
						if ($this->Ajax->isAjax()) print $this->element('painel_index_ajax');
						else print $this->element('painel_index');
						?>
					</legend>

					<table class="table table-bordered">
						<thead>
							<tr>
								<th><?php print $this->Paginator->sort('id','Código'); ?></th>
								<th><?php print $this->Paginator->sort('fornecedor_id','Fornecedor'); ?></th>
								<th><?php print $this->Paginator->sort('pagamento_tipo_id','Forma de pagamento'); ?></th>
								<th><?php print $this->Paginator->sort('data_compra','Data compra'); ?></th>
								<th><?php print $this->Paginator->sort('valor_bruto','Valor bruto'); ?></th>
								<th><?php print $this->Paginator->sort('valor_liquido','Valor líquido'); ?></th>
								<th><?php print $this->Paginator->sort('situacao','Situação'); ?></th>
								<th colspan="3">Ações</th>
							</tr>
						</thead>

						<tbody>

							<?php
							foreach ($consulta as $c): ?>

									<tr>
										<td><?php print $c['CompraPedido']['id'];?></td>
										<td><?php print $c['CompraPedido']['fornecedor_id'].' '.$c['Fornecedor']['nome']; ?></td>
										<td><?php print $c['CompraPedido']['pagamento_tipo_id'].' '.$c['PagamentoTipo']['nome']; ?></td>
										<td><?php if(isset($c['CompraPedido']['data_compra']) && ($c['CompraPedido']['data_compra'] != '0000-00-00') ) print $this->Formatacao->data($c['CompraPedido']['data_compra']); ?></td>
										<td><?php print $c['CompraPedido']['valor_bruto']; ?></td>
										<td><?php print $c['CompraPedido']['valor_liquido']; ?></td>
										<td><?php print $opcoes_situacoes[$c['CompraPedido']['situacao']]; ?></td>

										<td>
											<?php print $this->element('painel_editar',array('id'=>$c['CompraPedido']['id'])) ;?>
										</td>

										<td>
											<?php print $this->element('painel_detalhar',array('id'=>$c['CompraPedido']['id'])) ;?>
										</td>

										<td>
											<?php print $this->element('painel_excluir',array('id'=>$c['CompraPedido']['id'])) ;?>
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
					?>

				</fieldset>
				
			</div>

		</div>
		
	</div>
	
</div>