<div class="row-fluid">
	
	<div class="span12">
		
		<fieldset>
			<legend class="descricao_cabecalho">
				Exibindo os fornecedores cadastrados
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
						<th><?php print $this->Paginator->sort('id',__('Cód')); ?></th>
						<th><?php print $this->Paginator->sort('tipo_pessoa',__('Tipo')); ?></th>
						<th><?php print $this->Paginator->sort('nome',__('Nome')); ?></th>
						<th>CNPJ/CPF</th>
						<th>I.E/RG</th>
						<th><?php print $this->Paginator->sort('logradouro_nome',__('Logradouro')); ?></th>
						<th><?php print $this->Paginator->sort('logradouro_numero',__('Número')); ?></th>
						<th><?php print $this->Paginator->sort('logradouro_complemento',__('Comp.')); ?></th>
						<th><?php print $this->Paginator->sort('bairro',__('Bairro')); ?></th>
						<th><?php print $this->Paginator->sort('cidade',__('Cidade')); ?></th>
						<th><?php print $this->Paginator->sort('numero_telefone',__('Telefone')); ?></th>
						<th colspan="2">Ações</th>
					</tr>
				</thead>

				<tbody>

			<?php foreach ($consulta_fornecedor as $c):
				$tipo = strtoupper($c['Fornecedor']['tipo_pessoa']);
				if ($tipo == 'J') $tipo = 'JURIDICA';
				else $tipo = 'FISICA';
				?>

					<tr>
						<td><?php print $c['Fornecedor']['id'];?></td>
						<td><?php print $tipo; ?></td>
						<td><?php print $this->Html->link($c['Fornecedor']['nome'],'editar/' . $c['Fornecedor']['id']) ;?></td>
						<td>
							<?php
							if ($tipo == 'JURIDICA') print $c['Fornecedor']['cnpj'];
							else print $c['Fornecedor']['cpf'];
							?>
						</td>
						<td>
							<?php
							if ($tipo == 'JURIDICA') print $c['Fornecedor']['inscricao_estadual'];
							else print $c['Fornecedor']['rg']; 
							?>
						</td>
						<td><?php print $c['Fornecedor']['logradouro_nome'];?></td>
						<td><?php print $c['Fornecedor']['logradouro_numero'];?></td>
						<td><?php print $c['Fornecedor']['logradouro_complemento'];?></td>
						<td><?php print $c['Fornecedor']['bairro'];?></td>
						<td><?php print $c['Fornecedor']['cidade'];?></td>
						<td><?php print $c['Fornecedor']['numero_telefone'];?></td>
						<td>
							<?php print $this->element('painel_editar',array('id'=>$c['Fornecedor']['id'])) ;?>
						</td>
						<td>
							<?php print $this->element('painel_detalhar',array('id'=>$c['Fornecedor']['id'])) ;?>
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