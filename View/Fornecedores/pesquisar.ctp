<script type="text/javascript">
		$(function(){
			//pesquisa cliente
			//autocomplete
			$("#FornecedorNome").autocomplete({
				source: "<?php print $this->Html->url('/',true); ?>/Fornecedores/pesquisaAjaxFornecedor/nome",
				minLength: 3,
				select: function(event, ui) {
					$("#FornecedorId").val(ui.item.id);
					$('#FornecedorNome').val(ui.item.nome);
				}
			});
			// ao digitar o codigo
			$('#FornecedorId').blur(function(){
				codigo = $(this).val();
				if (codigo == null || codigo == '') return null;
				$.getJSON('<?php print $this->Html->url('/',true); ?>/Fornecedores/pesquisaAjaxFornecedor/codigo', {'term': codigo}, function(data) {
					if (data == null) {
						alert ('Fornecedor com o código '+codigo+' não foi encontrado!');
						$('#FornecedorNome').val('');
						$("#FornecedorId")
							.val('')
							.focus();
					}
					else { //encontrou resultados
						data = data[0];
						$("#FornecedorId").val(data.id);
						$('#FornecedorNome').val(data.nome);
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
			<legend class="descricao_cabecalho">Pesquisar fornecedor</legend>

			<?php
			if ($this->Ajax->isAjax()) {
				print $this->Ajax->form('pesquisar','post',array('autocomplete'=>'off','model'=>'Fornecedor','update'=>'conteudo_ajax'));
			}
			else {
				print $this->Form->create(null,array('controller'=>'fornecedores','action'=>'pesquisar','autocomplete'=>'off'));
			}
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
									<td><?php print $r['Fornecedor']['id']; ?></td>
									<td><?php print $r['Fornecedor']['tipo_pessoa']; ?></td>
									<td><?php print $this->Html->link($r['Fornecedor']['nome'],'editar/' . $r['Fornecedor']['id']) ;?></td>
									<td><?php print $r['Fornecedor']['nome_fantasia']; ?></td>
									<td><?php print $r['Fornecedor']['cidade']; ?></td>
									<td><?php print $r['Fornecedor']['cpf'].$r['Fornecedor']['cnpj']; ?></td>
									<td><?php print $r['Fornecedor']['rg'].$r['Fornecedor']['inscricao_estadual']; ?></td>
									<td><?php print $r['Usuario']['login']; ?></td>
									<td>
										<?php print $this->element('painel_detalhar',array('id'=>$r['Fornecedor']['id'])) ;?>
									</td>
									<td>
										<?php print $this->element('painel_editar',array('id'=>$r['Fornecedor']['id'])) ;?>
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