<div class="row-fluid">
	
	<div class="span12">
		
		<fieldset>
			<legend class="descricao_cabecalho">
				Exibindo os clientes cadastrados
				<?php
				if ($this->Ajax->isAjax()) {
					print $this->element('painel_index_ajax');
				}
				else {
					print $this->element('painel_index');
				}
				?>
			</legend>

			<table class="table table-bordered">
				<thead>
					<tr>
						<th><?php print $this->Paginator->sort('id','Cód'); ?></th>
						<th><?php print $this->Paginator->sort('tipo_pessoa','Tipo'); ?></th>
						<th><?php print $this->Paginator->sort('nome','Nome'); ?></th>
						<th>CNPJ/CPF</th>
						<th>I.E/RG</th>
						<th><?php print $this->Paginator->sort('logradouro_nome','Logradouro'); ?></th>
						<th><?php print $this->Paginator->sort('logradouro_numero','Número'); ?></th>
						<th><?php print $this->Paginator->sort('logradouro_complemento','Comp.'); ?></th>
						<th><?php print $this->Paginator->sort('bairro','Bairro'); ?></th>
						<th><?php print $this->Paginator->sort('cidade','Cidade'); ?></th>
						<th><?php print $this->Paginator->sort('numero_telefone','Telefone'); ?></th>
						<th colspan="2">Ações</th>
					</tr>
				</thead>

				<tbody>

			<?php foreach ($consulta_cliente as $cliente):
				$tipo = strtoupper($cliente['Cliente']['tipo_pessoa']);
				if ($tipo == 'J') $tipo = 'JURIDICA';
				else $tipo = 'FISICA';
				?>

					<tr>
						<td><?php print $cliente['Cliente']['id'];?></td>
						<td><?php print $tipo; ?></td>
						<td><?php print $this->Html->link($cliente['Cliente']['nome'],'editar/' . $cliente['Cliente']['id']) ;?></td>
						<td>
							<?php
							if ($tipo == 'JURIDICA') print $cliente['Cliente']['cnpj'];
							else print $cliente['Cliente']['cpf'];
							?>
						</td>
						<td>
							<?php
							if ($tipo == 'JURIDICA') print $cliente['Cliente']['inscricao_estadual'];
							else print $cliente['Cliente']['rg']; 
							?>
						</td>
						<td><?php print $cliente['Cliente']['logradouro_nome'];?></td>
						<td><?php print $cliente['Cliente']['logradouro_numero'];?></td>
						<td><?php print $cliente['Cliente']['logradouro_complemento'];?></td>
						<td><?php print $cliente['Cliente']['bairro'];?></td>
						<td><?php print $cliente['Cliente']['cidade'];?></td>
						<td><?php print $cliente['Cliente']['numero_telefone'];?></td>
						<td>
							<?php print $this->element('painel_editar',array('id'=>$cliente['Cliente']['id'])) ;?>
						</td>
						<td>
							<?php print $this->element('painel_detalhar',array('id'=>$cliente['Cliente']['id'])) ;?>
						</td>
					</tr>

			<?php endforeach ?>

				</tbody>
			</table>

			<?php
			$this->Paginator->options (array (
				'update' => '#conteudo',
				'before' => $this->Js->get('.indicador_carregando')->effect('fadeIn', array('buffer' => false)),
				'complete' => $this->Js->get('.indicador_carregando')->effect('fadeOut', array('buffer' => false)),
			));

			print $this->Paginator->pagination();
			?>

		</fieldset>
		
	</div>
	
</div>