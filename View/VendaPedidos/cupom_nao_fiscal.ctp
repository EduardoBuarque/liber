<!--
	Para uma impressora com 80mm de largura,
	equivalentes a cerca de 302 pixels
-->
<style type="text/css">
	body {font-size:80%;}
	#cupom {
		width: 295px;
	}
	p {
		margin-top: 0px;
		margin-bottom: 5px;
	}
</style>

<script type="text/javascript">
	window.print();
</script>

<div id="cupom">
	
	<div id="cabecalho">
		
		<h1 style="font-weight: bold;" style="text-align: center;"> Liber software </h1>
		<p>
			<?php print "{$venda['Empresa']['nome']} - {$venda['Empresa']['logradouro']} {$venda['Empresa']['numero']}
					{$venda['Empresa']['complemento']} {$venda['Empresa']['bairro']}
					{$venda['Empresa']['cidade']} - {$venda['Empresa']['estado']}"; ?>
		</p>
		
		<span class="separador"></span>
		
		<p>
			ID nº <?php print $venda['VendaPedido']['id']; ?> 
			em <?php (! empty($venda['VendaPedido']['data_hora_cadastrado'])) ? print $this->Formatacao->dataHora($venda['VendaPedido']['data_hora_cadastrado']) : print ''; ?> 
			<br/>
			Cliente: <?php print $venda['Cliente']['nome']; ?> 
			<br/>
			Pagamento: <?php print $venda['PagamentoTipo']['nome']; ?> 
		</p>
		
	</div>
	
	<div id="itens">
		
		<table>
			<thead>
				<th style="width: 70%; text-align: left;">Descrição</th>
				<th style="width: 10%; text-align: left;">Qtd.</th>
				<th style="width: 20%; text-align: left;">Valor uni.</th>
			</thead>
			<tbody>
				<?php
				foreach ($venda['VendaPedidoItem'] as $item) {
					print "<tr>";
					print "<td> ${item['produto_nome']} </td>";
					print "<td> ${item['quantidade']} </td>";
					print "<td>". $this->Geral->numero2moeda($item['preco_venda'])." </td>";
					print "</tr>\n";
				}
				?>
			</tbody>
		</table>
		
	</div>
	
	<br/>
	
	<p>
		<span style="font-weight: bold;">Valor bruto</span>: R$<?php print $this->Geral->numero2moeda($venda['VendaPedido']['valor_bruto']); ?>
		<br/>
		<span style="font-weight: bold;">Valor total</span>: R$<?php print $this->Geral->numero2moeda($venda['VendaPedido']['valor_liquido']); ?>
	</p>
	
	<p>
		Ass.:
		__________________________________
	</p>
	
</div>