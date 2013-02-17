<script type="text/javascript">
	$(function(){
		$( ".column" ).sortable({
			connectWith: ".column"
		});

		$( ".anuncio" ).addClass( "ui-widget ui-widget-content ui-helper-clearfix ui-corner-all" )
			.find( ".anuncio-cabecalho" )
				.addClass( "ui-widget-header ui-corner-all" )
				.prepend( "<span class='ui-icon ui-icon-minusthick'></span>")
				.end()
			.find( ".anuncio-conteudo" );

		$( ".anuncio-cabecalho .ui-icon" ).click(function() {
			$( this ).toggleClass( "ui-icon-minusthick" ).toggleClass( "ui-icon-plusthick" );
			$( this ).parents( ".anuncio:first" ).find( ".anuncio-conteudo" ).toggle();
		});

		$( ".column" ).disableSelection();
	});
</script>

<style type="text/css">
	.anuncio{ margin: 0 1em 1em 0; }
	.anuncio-cabecalho { margin: 0.3em; padding-bottom: 4px; padding-left: 0.2em; }
	.anuncio-cabecalho .ui-icon { float: right; }
	.anuncio-conteudo { padding: 0.4em; }
	.ui-sortable-placeholder { border: 1px dotted black; visibility: visible !important; height: 50px !important; }
	.ui-sortable-placeholder * { visibility: hidden; }
</style>

<div id='anuncios'>
	
	<div class="row-fluid">
		
		<div class="span8">
			<div >
				<?php print $this->Html->image('logotipo_grande.png',array('alt'=>'Liber')); ?>
			</div>
		</div>
		
		<div class="span4">
			
			<div class="row-fluid">
				<div class="span12">
					<div class="anuncio">
						<div class="anuncio-cabecalho">Contas a receber</div>
						<div class="anuncio-conteudo">
							<?php if (!empty($contasReceber)) print $contasReceber ?>
						</div>
					</div>
				</div>
			</div>
			
			<div class="row-fluid">
				<div class="span12">
					<div class="anuncio">
						<div class="anuncio-cabecalho">Contas a pagar</div>
						<div class="anuncio-conteudo">
							<?php if (!empty($contasPagar)) print $contasPagar ?>
						</div>
					</div>
				</div>
			</div>
			
		</div>
		
	</div>
	
</div>