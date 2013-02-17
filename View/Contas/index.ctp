<div class="row-fluid">
	
	<div class="span12">
		
		<fieldset>
			<legend class="descricao_cabecalho">
				Exibindo as contas cadastradas
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
						<th><?php print $this->Paginator->sort('id','Código'); ?></th>
						<th><?php print $this->Paginator->sort('nome','Nome'); ?></th>
						<th><?php print $this->Paginator->sort('apelido','Apelido'); ?></th>
						<th><?php print $this->Paginator->sort('banco','Banco'); ?></th>
						<th><?php print $this->Paginator->sort('agencia','Agência'); ?></th>
						<th><?php print $this->Paginator->sort('conta','Conta'); ?></th>
						<th><?php print $this->Paginator->sort('titular','Titular'); ?></th>
						<th colspan="2">Ações</th>
					</tr>
				</thead>

				<tbody>

			<?php foreach ($consulta_conta as $conta): ?>

					<tr>
						<td><?php print $conta['Conta']['id'];?></td>
						<td><?php print $this->Html->link($conta['Conta']['nome'],'editar/' . $conta['Conta']['id']) ;?></td>
						<td><?php print $conta['Conta']['apelido']; ?></td>
						<td><?php print $conta['Conta']['banco']; ?></td>
						<td><?php print $conta['Conta']['agencia']; ?></td>
						<td><?php print $conta['Conta']['conta']; ?></td>
						<td><?php print $conta['Conta']['titular']; ?></td>
						<td>
							<?php print $this->element('painel_editar',array('id'=>$conta['Conta']['id'])) ;?>
						</td>
						<td>
							<?php print $this->element('painel_excluir',array('id'=>$conta['Conta']['id'])) ;?>
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