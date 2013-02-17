<div class="row-fluid">
		
	<div class="span12">
		<fieldset class="descricao_cabecalho">
			<legend class="descricao_cabecalho">
				Detalhando pedido de compra número <?php print $this->request->data['CompraPedido']['id']; ?>
			</legend>

				<fieldset>
					<legend><?php print __('Dados do pedido'); ?></legend>

					<dl class="dl-horizontal">
						<dt>Registro em:</dt> <dd><?php print $this->Formatacao->dataHora($this->request->data['CompraPedido']['data_hora_cadastrado']);?></dd>
						<dt>Fornecedor</dt> <dd><?php print $this->request->data['CompraPedido']['fornecedor_id'].' '.$this->request->data['Fornecedor']['nome'];?></dd>
						<dt>Pagamento:</dt> <dd><?php print $this->request->data['CompraPedido']['pagamento_tipo_id'].' '.$this->request->data['PagamentoTipo']['nome'] ;?></dd>
						<dt>Situação</dt> <dd><?php print $opcoes_situacoes[$this->request->data['CompraPedido']['situacao']] ;?></dd>
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
								<th>Preço de compra</th>
							</tr>
						</thead>

						<tbody>
							<?php
							foreach ($this->request->data['CompraPedidoItem'] as $r):?>
							<tr>
								<td><?php print $r['produto_id'] ?></td>
								<td><?php print $r['produto_nome'] ?></td>
								<td><?php print $r['quantidade'] ?></td>
								<td><?php print $r['preco_compra'] ?></td>
							</tr>
							<?php endforeach; ?>
						</tbody>

					</table>

					<div class="clearfix"></div>
					
					<dl class="dl-horizontal">
						<dt>Valor bruto</dt> <dd>R$<?php print $this->request->data['CompraPedido']['valor_bruto'] ;?></dd>
						<dt>Valor líquido</dt> <dd>R$<?php print $this->request->data['CompraPedido']['valor_liquido'] ;?></dd>
					</dl>
					
				</fieldset>

		</fieldset>
		
	</div>
	
</div>