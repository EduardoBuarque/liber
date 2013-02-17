<div class="row-fluid">
		
	<div class="span12">
		<fieldset class="descricao_cabecalho">
			<legend class="descricao_cabecalho">
				Detalhando pedido de venda número <?php print $c['VendaPedido']['id']; ?>
			</legend>

				<fieldset>
					<legend><?php print __('Dados do pedido'); ?></legend>

					<dl class="dl-horizontal">
						<dt>Registro em:</dt> <dd><?php print $this->Formatacao->dataHora($c['VendaPedido']['data_hora_cadastrado']);?></dd>
						<dt>Cliente</dt> <dd><?php print $c['VendaPedido']['cliente_id'].' '.$c['Cliente']['nome'];?></dd>
						<dt>Pagamento:</dt> <dd><?php print $c['VendaPedido']['pagamento_tipo_id'].' '.$c['PagamentoTipo']['nome'] ;?></dd>
						<dt>Situação</dt> <dd><?php print $opcoes_situacoes[$c['VendaPedido']['situacao']] ;?></dd>
					</dl>

				</fieldset>

				<fieldset>
					<legend><?php print __('Produtos incluídos'); ?></legend>

					<table class="table table-striped">
						<thead>
							<tr>
								<th>Cód.</th>
								<th>Nome</th>
								<th>Quantidade</th>
								<th>Preço de venda</th>
							</tr>
						</thead>

						<tbody>
							<?php
							foreach ($c['VendaPedidoItem'] as $r):?>
							<tr>
								<td><?php print $r['produto_id'] ?></td>
								<td><?php print $r['produto_nome'] ?></td>
								<td><?php print $r['quantidade'] ?></td>
								<td><?php print $r['preco_venda'] ?></td>
							</tr>
							<?php endforeach; ?>
						</tbody>

					</table>

					<div class="clearfix"></div>
					
					<dl class="dl-horizontal">
						<dt>Valor bruto</dt> <dd>R$<?php print $c['VendaPedido']['valor_bruto'] ;?></dd>
						<dt>Valor líquido</dt> <dd>R$<?php print $c['VendaPedido']['valor_liquido'] ;?></dd>
					</dl>
					
				</fieldset>

		</fieldset>
		
	</div>
	
</div>