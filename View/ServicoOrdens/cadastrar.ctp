<script type="text/javascript">
	// variaveis a serem utilizadas no arquivo ordem_servico.js
	var ajaxPesqCliente = "<?php print $this->Html->url(array('controller'=>'Clientes','action'=>'pesquisaAjaxCliente')); ?>/";
	var ajaxPesqServico = "<?php print $this->Html->url(array('controller'=>'Servicos','action'=>'pesquisaAjaxServico')); ?>/";
	var ajaxPesqPagamentoTipo = "<?php print $this->Html->url(array('controller'=>'PagamentoTipos','action'=>'pesquisaAjaxNumeroMaximoParcelas')); ?>/";
</script>

<?php
print $this->Html->script('formatar_moeda');
print $this->Html->script('jquery-ui-timepicker',array('inline'=>false));
print $this->Html->script('ordem_servico');
?>

<legend class="descricao_cabecalho"><?php print __('Cadastrar ordem de serviço'); ?></legend>

<?php
if ($this->Ajax->isAjax()) {
	print $this->Ajax->form('cadastrar','post',array('autocomplete'=>'off','model'=>'ServicoOrdem','update'=>'conteudo_ajax'));
}
else {
	print $this->Form->create('ServicoOrdem',array('autocomplete'=>'off','onsubmit'=>'submissaoFormulario(this); return false;'));
}
?>

<div class="row-fluid">
	<div class="span2 visible-desktop">
		
		<ul class="nav nav-pills nav-stacked">

			<li class="nav-header">
				Ações
			</li>
			<li>
				<a href="<?php print $this->Html->url(array('controller'=>'ServicoOrdens','action'=>'index'));?>" onclick="formulario_cancelar(); return false;">
					<i class="icon-remove"></i>
					Cancelar
				</a>
			</li>

			<li class="nav-header">
				Ordens de serviço
			</li>
			<li class="active">
				<a href="<?php print $this->Html->url(array('controller'=>'ServicoOrdens','action'=>'cadastrar'));?>">
					<i class="icon-file"></i>
					Cadastrar
				</a>
			</li>
			<li>
				<a href="<?php print $this->Html->url(array('controller'=>'ServicoOrdens','action'=>'editar'));?>">
					<i class="icon-edit"></i>
					Editar
				</a>
			</li>
			<li>
				<a href="<?php print $this->Html->url(array('controller'=>'ServicoOrdens','action'=>'pesquisar'));?>">
					<i class="icon-filter"></i>
					Pesquisar
				</a>
			</li>
			<li>
				<a href="<?php print $this->Html->url(array('controller'=>'ServicoOrdens','action'=>'index'));?>">
					<i class="icon-list"></i>
					Listar
				</a>
			</li>

			<li class="nav-header">
				Serviços
			</li>
			<li>
				<a href="<?php print $this->Html->url(array('controller'=>'Servicos','action'=>'cadastrar'));?>">
					<i class="icon-file"></i>
					Cadastrar
				</a>
				<a href="<?php print $this->Html->url(array('controller'=>'Servicos','action'=>'index'));?>">
					<i class="icon-list"></i>
					Listar
				</a>
			</li>
			
			<li class="nav-header">
				Categorias de serviço
			</li>
			<li>
				<a href="<?php print $this->Html->url(array('controller'=>'ServicoCategorias','action'=>'cadastrar'));?>">
					<i class="icon-file"></i>
					Cadastrar
				</a>
				<a href="<?php print $this->Html->url(array('controller'=>'ServicoCategorias','action'=>'index'));?>">
					<i class="icon-list"></i>
					Listar
				</a>
			</li>
		</ul>
		
	</div>
	
	<div class="span10">

		<div id="servico_ordem_abas">
			<ul>
				<li><a href="#informacoes">Informações</a></li>
				<li><a href="#servicos">Serviços</a></li>
				<li><a href="#outros">Outros</a></li>
			</ul>

			<div id="informacoes">
				
				<div class="row-fluid">
					
					<div class="span6">
						<?php
						$this->Form->defineRow(array(2,10));
						print $this->Form->input('cliente_id', array(
							'label'=>__('Código'),
							'type'=>'text',
							));
						print $this->Form->input('cliente_nome',array('label'=>__('Cliente nome'),'type'=>'text'));

						$this->Form->defineRow(array(6,6));
						print $this->Form->input('pagamento_tipo_id',array('label'=>'Forma de pagamento','options'=>$opcoes_forma_pamamento));
						print $this->Form->input('dias_garantia',array('label'=>'Dias de garantia'));
						?>
					</div>
					<div class="span6">
						<?php
						$this->Form->defineRow(array(6,6));
						print $this->Form->input('data_hora_inicio',array('label'=>'Data e hora do início','dateFormat' => 'DMY','timeFormat'=>24));
						unset($opcoes_situacao['C']);
						print $this->Form->input('situacao',array('label'=>'Situação','options'=>$opcoes_situacao));

						$this->Form->defineRow(array(6));
						print $this->Form->input('usuario_id',array('label'=>'Técnico','options'=>$opcoes_tecnico));
						?>
					</div>
					
				</div>
				
			</div> <!-- fim de informacoes -->

			<div id="servicos">
				
				<div class="row-fluid">
					
					<div class="span6">
						<fieldset id="fieldset_servicos_incluidos">
							<legend>Serviços incluídos</legend>
							<table class="table table-striped">
								<thead>
									<tr>
										<th style="width: 10%;">Cód.</th> <th>Nome</th> <th style="width: 15%;">Qtd.</th> <th style="width: 15%;">Valor</th>  <th>Ações</th>
									</tr>
								</thead>
								<tbody id="servicos_incluidos">
									<?php //aqui ficam os itens incluidos
									if (isset($campos_ja_inseridos)) {
										$i = 0;
										$valor_total = 0;
										foreach ($campos_ja_inseridos as $item) {
											print '<tr>'.
											'<td> <input type="text" name="data[ServicoOrdemItem]['.$i.'][servico_id]" value="'.$item['servico_id'].'" class="noinput item_id" /> </td>'.
											'<td> <input type="text" name="data[ServicoOrdemItem]['.$i.'][servico_nome]" value="'.$item['servico_nome'].'" class="noinput item_nome" /> </td>'.
											'<td> <input type="text" name="data[ServicoOrdemItem]['.$i.'][quantidade]" value="'.$item['quantidade'].'" class="noinput item_qtd" /> </td>'.
											'<td> <input type="text" name="data[ServicoOrdemItem]['.$i.'][valor]" value="'.$item['valor'].'" class="noinput item_val" /> </td>'.
											'<td> <img src="'.$this->Html->url('/',true).'/img/del24x24.png" class="remover_linha"/> </td>'.
											'</tr>'."\n";
											$i++;
											$valor_total += $item['quantidade'] * $this->Geral->moeda2numero($item['valor']);
										}
									}
									?>
								</tbody>
								<input type="hidden" id="numero_itens_incluidos" value="<?php if (isset($i)) print $i; else print '0';?>" />
							</table>
							<b>Valor total: </b> R$ <span id="valor_total"><?php if (isset($valor_total)) print $valor_total; else print '0'; ?></span>
						</fieldset>
					</div>	

					<div class="span6">
						<fieldset>
							<legend>Pesquisar serviço</legend>
							<div id="form_pesquisar_servicos">
								<div id="servicos_pesquisar">
									<?php
									$this->Form->defineRow(array(12));
									print $this->Form->input('Servico.id',array('label'=>'Cód.','type'=>'text','class'=>'span12'));
									$this->Form->defineRow(array(12));
									print $this->Form->input('Servico.nome',array('label'=>'Nome','type'=>'text','class'=>'span12'));
									$this->Form->defineRow(array(12));
									print $this->Form->input('Servico.categoria',array('type'=>'hidden'));
									$this->Form->defineRow(array(12));
									print $this->Form->input('Servico.quantidade',array('label'=>'Quantidade','type'=>'text','class'=>'span12'));
									$this->Form->defineRow(array(12));
									print $this->Form->input('Servico.valor',array('label'=>'Valor','type'=>'text','class'=>'span12'));
									?>
								</div>
								<p>
									<input type="button" id="botao_adicionar_servico" class="btn btn-success" value="Adicionar" class="botao_aviso" style="float: left; width: 40%;" />
									<input type="button" id="botao_limpar_pesquisa" class="btn btn-danger" value="Limpar" class="botao_erro" style="width: 40%; margin-left: 10%;" />
								</p>
							</div>
						</fieldset>
					</div>
					
				</div>
				
			</div> <!-- fim de servicos -->

			<div id="outros">
				<?php
				$this->Form->defineRow(array(6,6));
				print $this->Form->input('custo_outros',array('label'=>'Outros custos','type'=>'text'));
				print $this->Form->input('desconto',array('label'=>'Desconto','type'=>'text'));
				$this->Form->defineRow(array(12));
				print $this->Form->input('defeitos_relatados',array('label'=>'Defeitos relatados','rows'=>'3'));
				$this->Form->defineRow(array(12));
				print $this->Form->input('laudo_tecnico',array('label'=>'Laudo técnico','rows'=>'3'));
				$this->Form->defineRow(array(12));
				print $this->Form->input('observacao',array('label'=>'Observação','rows'=>'3'));
				?>
				<div class="clearfix"></div>
			</div> <!-- fim de outros -->
		
		</div> <!-- fim servico_ordem_abas -->
		
		<div class="clearfix">&nbsp;</div>
		
		<?php print $this->Form->end(array('label'=>__('Gravar'),'class'=>'btn btn-primary','div'=>array('class'=>'form-actions'))); ?>
		
	</div> <!-- span10 -->
</div> <!-- row-fluid -->