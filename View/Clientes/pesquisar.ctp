<script type="text/javascript">
		$(function(){
			//pesquisa cliente
			
			//autocomplete
			$("#ClienteNome").autocomplete({
				source: "<?php print $this->Html->url('/',true); ?>/Clientes/pesquisaAjaxCliente/nome",
				minLength: 3,
				select: function(event, ui) {
					$("#ClienteId").val(ui.item.id);
					$('#ClienteNome').val(ui.item.nome);
				}
			});
			// ao digitar o codigo
			$('#ClienteId').blur(function(){
				codigo = $(this).val();
				if (codigo == null || codigo == '') return null;
				$.getJSON('<?php print $this->Html->url('/',true); ?>/Clientes/pesquisaAjaxCliente/codigo', {'term': codigo}, function(data) {
					if (data == null) {
						alert ('Cliente com o código '+codigo+' não foi encontrado!');
						$('#ClienteNome').val('');
						$("#ClienteId")
							.val('')
							.focus();
					}
					else { //encontrou resultados
						data = data[0];
						$("#ClienteId").val(data.id);
						$('#ClienteNome').val(data.nome);
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
			<legend class="descricao_cabecalho">Pesquisar cliente</legend>

			<?php
			print $this->Form->create(null,array('controller'=>'clientes','action'=>'pesquisar','autocomplete'=>'off'));
			?>

			<div class="row-fluid">
				
				<div class="span6">
					<?php
					$this->Form->defineRow(array(2,10));
					print $this->Form->input('id', array('label'=>__('Cód.'),'type'=>'text'));
					print $this->Form->input('nome', array('label'=>__('Nome')));

					$this->Form->defineRow(array(6,6));
					print $this->Form->input('nome_fantasia', array('label'=>__('Nome fantasia')));
					print $this->Form->input('bairro',array('label'=>__('Bairro')));

					$this->Form->defineRow(array(6));
					print $this->Form->input('cidade',array('label'=>__('Cidade')));
					?>
				</div>

				<div class="span5">
					<?php
					$this->Form->defineRow(array(6,6));
					print $this->Form->input('cnpj',array('label'=>__('CNPJ')));
					print $this->Form->input('inscricao_estadual',array('label'=>__('Inscrição estadual')));

					$this->Form->defineRow(array(6,6));
					print $this->Form->input('cpf',array('label'=>__('CPF')));
					print $this->Form->input('rg',array('label'=>__('RG')));
					?>
				</div>
				
			</div>
			
			<?php print $this->Form->end(array('label'=>__('Pesquisar'))); ?>

			<?php if (isset($num_resultados) && $num_resultados > 0) : ?>
				<fieldset>
					<legend><?php print __('Resultados'); ?></legend>
					
					<table class="table table-striped">
						<thead>
							<tr>
								<th><?php print $this->Paginator->sort('id','Cód'); ?></th>
								<th><?php print $this->Paginator->sort('tipo_pessoa','Pessoa'); ?></th>
								<th><?php print $this->Paginator->sort('nome','Nome'); ?></th>
								<th><?php print $this->Paginator->sort('nome_fantasia','Nome fantasia'); ?></th>
								<th><?php print $this->Paginator->sort('cidade','Cidade'); ?></th>
								<th>CPF/CNPJ</th>
								<th>RG/IE</th>
								<th><?php print $this->Paginator->sort('usuario_cadastrou','Usuário cadastrou'); ?></th>
								<th colspan="2">Ações</th>
							</tr>
						</thead>

						<tbody>
							<?php foreach ($resultados as $r) : ?>
								<tr>
									<td><?php print $r['Cliente']['id']; ?></td>
									<td><?php print $r['Cliente']['tipo_pessoa']; ?></td>
									<td><?php print $this->Html->link($r['Cliente']['nome'],'editar/' . $r['Cliente']['id']) ;?></td>
									<td><?php print $r['Cliente']['nome_fantasia']; ?></td>
									<td><?php print $r['Cliente']['cidade']; ?></td>
									<td><?php print $r['Cliente']['cpf'].$r['Cliente']['cnpj']; ?></td>
									<td><?php print $r['Cliente']['rg'].$r['Cliente']['inscricao_estadual']; ?></td>
									<td><?php print $r['Usuario']['login']; ?></td>
									<td>
										<?php print $this->element('painel_detalhar',array('id'=>$r['Cliente']['id'])) ;?>
									</td>
									<td>
										<?php print $this->element('painel_editar',array('id'=>$r['Cliente']['id'])) ;?>
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
		</fieldset>
	
	</div>
	
</div>