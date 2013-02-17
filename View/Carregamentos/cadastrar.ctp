<script type="text/javascript">
	$(function(){
		$('#selecionar_checkbox').click(function(){
			if ( $(this).is(':checked') ) {
				$('form :checkbox').each(function(){
					$(this).attr('checked','checked');
				});
			}
			else {
				$('form :checkbox').each(function(){
					$(this).removeAttr('checked');
				});
			}
		});
		
		$('*[type=submit]').click(function(){
			if ($('#CarregamentoDescricao').val() == '') {
				alert ('O campo descrição é obrigatório!');
				return false;
			}
			
			if ($('#CarregamentoVeiculoId option:selected').val() == '') {
				alert ('Defina um veículo!');
				return false;
			}
			
			cont = 0;
			$('form :checkbox').each(function(){
				if ( $(this).is(':checked') ) cont++;
			});
			if (cont == 0) {
				alert ('Marque ao menos um pedido!');
				return false;
			}
		});
	});
</script>

<div class="row-fluid">
	
	<div class="span12">
		<fieldset>
			<legend class="descricao_cabecalho"><?php print __('Cadastrar carregamento'); ?></legend>

				<?php
				// #XXX implementar o escolha automatica do veiculo assim que escolhe o motorista
				// com base no veiculo definido como padrao no cadastro do motorista
				if ($this->Ajax->isAjax()) {
					print $this->Ajax->form('cadastrar','post',array('autocomplete'=>'off','model'=>'Carregamento','update'=>'conteudo_ajax'));

				}
				else {
					print $this->Form->create('Carregamento',array('autocomplete'=>'off','onsubmit'=>'submissaoFormulario(this); return false;'));
				}
				
				$this->Form->defineRow(array(3,2,2));
				print $this->Form->input('descricao',array('label'=>__('Descrição')));
				print $this->Form->input('motorista_id',array(
					'label'=>__('Motorista'),
					'options'=>$opcoes_motoristas
					));
				print $this->Form->input('veiculo_id',array(
					'label'=>__('Veículo'),
					'options'=>$opcoes_veiculos
					));
				?>

				<fieldset>
					<legend>Pedidos</legend>
					<table class="table table-striped">
						<thead>
							<th> <input type="checkbox" name="selecionar_checkbox" id="selecionar_checkbox" /> Selecionar</th>
							<th>Pedido</th>
							<th>Cliente</th>
							<th>Valor bruto</th>
							<th>Valor líquido</th>
						</thead>

						<tbody>
							<?php
							$cont = 0;
							foreach ($consulta_pedidos as $c):
								$id_pedido = $c['VendaPedido']['id'];
								$id = "CarregamentoItemVendaPedidoId${id_pedido}";
								$nome = "data[CarregamentoItem][${cont}][venda_pedido_id]";
								$valor = $id_pedido;
							?>
								<tr>
									<td> <input type="checkbox" name="<?php print $nome;?>" id="<?php print $id;?>" value="<?php print $valor; ?>" /> </td>
									<td><?php print $c['VendaPedido']['id']?></td>
									<td><?php print $c['Cliente']['nome']?></td>
									<td><?php print $c['VendaPedido']['valor_bruto']?></td>
									<td><?php print $c['VendaPedido']['valor_liquido']?></td>
								</tr>
							<?php
								$cont++;
							endforeach;
							?>
						</tbody>

					</table>
				</fieldset>
			<?php print $this->Form->input('observacao',array('label'=>__('Observação'))); ?>
			<div class="clearfix"></div>
			<?php print $this->Form->end(array('label'=>__('Gravar'),'class'=>'btn btn-primary','div'=>array('class'=>'form-actions'))); ?>
		</fieldset>
	</div>

</div>