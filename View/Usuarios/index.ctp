<div class="row-fluid">
	
	<div class="span12">
		
		<fieldset>
			<legend class="descricao_cabecalho">
				Exibindo os usuários cadastrados
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
						<th><?php print $this->Paginator->sort('nome','Nome'); ?></th>
						<th><?php print $this->Paginator->sort('login','Login'); ?></th>
						<th><?php print $this->Paginator->sort('grupo_id','Grupo'); ?></th>
						<th><?php print $this->Paginator->sort('ativo','Ativo'); ?></th>
						<th><?php print $this->Paginator->sort('email','E-mail'); ?></th>
						<th><?php print $this->Paginator->sort('ultimo_login','Último login'); ?></th>
						<th><?php print $this->Paginator->sort('ultimo_logout','Último logout'); ?></th>
						<th colspan="2">Ações</th>
					</tr>
				</thead>

				<tbody>

			<?php foreach ($consulta_usuario as $usuario): ?>

					<tr>
						<td><?php print $usuario['Usuario']['id'];?></td>
						<td><?php print $this->Html->link($usuario['Usuario']['nome'],'editar/' . $usuario['Usuario']['id']) ;?></td>
						<td><?php print $usuario['Usuario']['login']; ?></td>
						<td><?php print $opcoes_grupos[$usuario['Usuario']['grupo_id']]; ?></td>
						<td>
							<?php
							if ($usuario['Usuario']['ativo'] == 1) print "Sim";
							else print "Não";
							?>
						</td>
						<td><?php print $usuario['Usuario']['email']; ?></td>
						<td><?php print $usuario['Usuario']['ultimo_login']; ?></td>
						<td><?php print $usuario['Usuario']['ultimo_logout']; ?></td>
						<td>
							<?php print $this->element('painel_editar',array('id'=>$usuario['Usuario']['id'])) ;?>
						</td>
						<td>
							<?php
							$imagem = $this->Html->image('del24x24.png',array('title'=>"Inativar usuário {$usuario['Usuario']['id']}",'alt'=>"Inativar usuário {$usuario['Usuario']['id']}",));
							print $this->Html->link($imagem, array('action' => 'inativar',$usuario['Usuario']['id']), array('escape'=>false),'Deseja realmente inativar este usuário?', false);
							?>
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