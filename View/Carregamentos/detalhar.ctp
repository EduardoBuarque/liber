<div class="row-fluid">
	<div class="span12">
		<fieldset class="descricao_cabecalho">
			<legend><?php print __('Detalhar carregamento'); ?></legend>

			<div class="row-fluid">
				
				<div class="span6">
					<dl class="dl-horizontal">
						<dt>Criado em:</dt> <dd><?php print $this->Formatacao->dataHora($carregamento['Carregamento']['data_hora_criado']); ?></dd>
						<dt> Descricao: </dt> <dd><?php print $carregamento['Carregamento']['descricao'] ?></dd>
						<dt>Motorista: </dt> <dd><?php print $carregamento['Motorista']['nome'] ?></dd>
						<dt>Veículo: </dt> <dd><?php print $carregamento['Veiculo']['placa'] ?></dd>
					</dl>
				</div>

				<div class="span6">
					<legend>Pedidos de venda incluídos neste carregamento:</legend>
					<table class="table table-striped">
						<thead>
							<tr>
								<th>Número pedido de venda</th>
							</tr>
						</thead>

						<tbody>
							<? foreach ($carregamento['CarregamentoItem'] as $item) : ?> 
								<tr>
									<td><?php print $this->Html->link($item['venda_pedido_id'],'/vendaPedidos/detalhar/'.$item['venda_pedido_id']) ;?></td>
								</tr>
							<? endforeach; ?>
						</tbody>

					</table>
				</div>
				
			</div>
			
	</div>
	
	<div class="span12">
		Observação: <textarea rows="5" readonly="readonly"><?php  print $carregamento['Carregamento']['observacao'];?></textarea>
	</div>
	
</div>