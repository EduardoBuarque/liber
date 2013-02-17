<div class="row-fluid">
		
	<div class="span12">
		
		<fieldset class="descricao_cabecalho">
			<legend><?php print __('Detalhando ordem de serviço número ').$c['ServicoOrdem']['id']; ?></legend>

			<div class="row-fluid">
				
				<div class="span6">
					<dl class="dl-horizontal">
						<dt>Registro:</dt> <dd><?php print $this->Formatacao->dataHora($c['ServicoOrdem']['data_hora_cadastrada']);?></dd>
						<dt>Cliente:</dt> <dd><?php print $c['ServicoOrdem']['cliente_id'].' '.$c['Cliente']['nome'];?></dd>
						<dt>Pagamento:</dt> <dd> <?php print $c['ServicoOrdem']['pagamento_tipo_id'].' '.$c['PagamentoTipo']['nome'] ;?> </dd>
						<dt>Situação:</dt> <dd> <?php print $opcoes_situacao[$c['ServicoOrdem']['situacao']] ;?></dd>

						<dt>Iniciado em:</dt> <dd><?php print $this->Formatacao->dataHora($c['ServicoOrdem']['data_hora_inicio']) ;?></dd>
						<dt>Termino em:</dt> <dd><?php if (isset($c['ServicoOrdem']['data_hora_fim']))  print $this->Formatacao->dataHora($c['ServicoOrdem']['data_hora_fim']);?></dd>
					</dl>
				</div>

				<div class="span6">
					<fieldset>
						<legend>Serviços incluídos</legend>

						<table class="table table-striped">
							<thead>
								<tr>
									<th>Cód.</th>
									<th>Nome</th>
									<th>Quantidade</th>
									<th>Valor</th>
								</tr>
							</thead>

							<tbody>
								<?php
								foreach ($c['ServicoOrdemItem'] as $r):?>
								<tr>
									<td><?php print $r['servico_id'] ?></td>
									<td><?php print $r['servico_nome'] ?></td>
									<td><?php print $r['quantidade'] ?></td>
									<td><?php print $r['valor'] ?></td>
								</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</fieldset>
				</div>
				
			</div>

		</fieldset>
		
	</div>
	
	<div class="span12">
		<dl class="dl-horizontal">
			<dt>Valor bruto:</dt> <dd> R$<?php print $c['ServicoOrdem']['valor_bruto'] ;?> </dd>
			<dt>Valor líquido</dt> <dd> R$<?php print $c['ServicoOrdem']['valor_liquido'] ;?> </dd>
		</dl>
	</div>
	
</div>