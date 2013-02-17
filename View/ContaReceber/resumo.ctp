<?php
print $this->Html->script('jqplot/jquery.jqplot');
print $this->Html->script('jqplot/plugins/jqplot.donutRenderer');
print $this->Html->script('jqplot/plugins/jqplot.pieRenderer');

print $this->Html->css('jquery.jqplot.css');
?>

<script type="text/javascript">
	$(document).ready(function(){
		
		var data1 = <?php print $data1 ?>;
		var grafico1 = jQuery.jqplot ('grafico1', [data1], {
			title: 'Itens mais utilizados no plano de contas',
			seriesDefaults: {
				// Cria um grafico de pizza
				renderer: jQuery.jqplot.PieRenderer,
				rendererOptions: {
					// Coloque rótulos de dados sobre as fatias de pizza.
					 // Por padrão rótulos mostram a porcentagem da fatia.
					showDataLabels: true
				}
			},
			legend: { show:true, location: 'e' }
			}
		);
		
		var data2 = <?php print $data2 ?>;
		var grafico2 = jQuery.jqplot ('grafico2', [data2], {
			title: 'Itens do plano de contas com maiores valores (R$)',
			seriesDefaults: {
				// Cria um grafico de pizza
				renderer: jQuery.jqplot.PieRenderer,
				rendererOptions: {
					// Coloque rótulos de dados sobre as fatias de pizza.
					 // Por padrão rótulos mostram a porcentagem da fatia.
					showDataLabels: true,
					dataLabels: 'value'
				},
			},
			legend: { show:true, location: 'e' }
			}
		);
		
		var data3 = <?php print $data3 ?>;
		var grafico3 = jQuery.jqplot ('grafico3', [data3], {
			title: 'Clientes/fornecedores com mais contas a receber',
			seriesDefaults: {
				// Cria um grafico de pizza
				renderer: jQuery.jqplot.PieRenderer,
				rendererOptions: {
					// Coloque rótulos de dados sobre as fatias de pizza.
					 // Por padrão rótulos mostram a porcentagem da fatia.
					showDataLabels: true,
				},
			},
			legend: { show:true, location: 'e' }
			}
		);
			
		var data4 = <?php print $data4 ?>;
		var grafico3 = jQuery.jqplot ('grafico4', [data4], {
			title: 'Clientes/fornecedores com mais contas a receber vencidas e não pagas',
			seriesDefaults: {
				// Cria um grafico de pizza
				renderer: jQuery.jqplot.PieRenderer,
				rendererOptions: {
					// Coloque rótulos de dados sobre as fatias de pizza.
					 // Por padrão rótulos mostram a porcentagem da fatia.
					showDataLabels: true,
				},
			},
			legend: { show:true, location: 'e' }
			}
		);
		
	});
</script>

<fieldset>
	<legend class="descricao_cabecalho">Resumo das contas a receber</legend>

	<?php
	if ( ($data1 == '[]') && ($data2 == '[]') && ($data3 == '[]') ) {
		print '<div style="text-align: center; font-size: 300%;"> <b>Nenhum resultado encontrado</b> </div>';
		return null;
	}
	?>


	<div class="span4">
		<div id="grafico1" class="grafico">
		</div>
	</div>

	<div class="span4">
		<div id="grafico2" class="grafico">
		</div>
	</div>

	<div class="span4">
		<div id="grafico3" class="grafico">
		</div>
	</div>

	<div class="clearfix">&nbsp;</div>

	<div class="span4">
		<div id="grafico4" class="grafico">
		</div>
	</div>

	<div class="clearfix">&nbsp;</div>
</fieldset>