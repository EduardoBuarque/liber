<?php print $this->Html->script('pagamento_tipo'); ?>

<div class="row-fluid">
	
	<div class="span12">
		<fieldset>
			<legend class="descricao_cabecalho"><?php print __('Editar forma de pagamento'); ?></legend>

				<?php
				if ($this->Ajax->isAjax()) {
					print $this->Ajax->form('editar','post',array('autocomplete'=>'off','model'=>'PagamentoTipo','update'=>'conteudo_ajax'));
				}
				else {
					print $this->Form->create('PagamentoTipo',array('autocomplete'=>'off','onsubmit'=>'submissaoFormulario(this); return false;'));
				}
				$this->Form->defineRow(array(4,3,3));
				print $this->Form->input('nome',array('label'=>'Nome'));
				print $this->Form->input('documento_tipo_id',array('label'=>'Documento','options'=>$opcoes_documentos));
				print $this->Form->input('conta_principal',array('label'=>'Conta principal','options'=>$opcoes_contas));
				?>
			
			<div class="row-fluid">
				
				<div class="span6">
					<fieldset>
						<legend>Parcelas</legend>
							<table class="table table-striped">
								<thead>
									<tr>
										<th>Ordem</th>
										<th>Número de dias</th>
										<th>Ações</th>
									</tr>
								</thead>

								<tbody id="parcelas_inseridas">
									<?php //aqui ficam os itens incluidos
										if (isset($campos_ja_inseridos)) {
											$i = 0;
											$valor_total = 0;
											foreach ($campos_ja_inseridos as $item) {
												print '<tr>'.
												'<td> <input name="none" value="'.($i+1).'" class="noinput"/> '.
												'<td> <input type="text" name="data[PagamentoTipoItem]['.$i.'][dias_intervalo_parcela]" value="'.$item['dias_intervalo_parcela'].'" class="noinput item_id" /> </td>'.
												'<td> <img src="'.$this->Html->url('/',true).'/img/del24x24.png" class="remover_linha"/> </td>'.
												'</tr>'."\n";
												$i++;
											}
										}
										?>
								</tbody>
								<input type="hidden" id="numero_itens_incluidos" value="<?php if (isset($i)) print $i; else print '0';?>" />
							</table>
					</fieldset>
				</div>

				<div class="span6">
					<fieldset>
						<legend>Inserir parcelas</legend>
						<div id="form_inserir_parcelas">
							<div id="parcelas_inserir">
								<label for="[PesquisaPagamentoTipoItem][dias_intervalo_parcela]">Número de dias da parcela</label>
								<input type="text" name="[PesquisaPagamentoTipoItem][dias_intervalo_parcela]" id="PesquisaPagamentoTipoItemDiasIntervaloParcela" value="" class="required number" />
							</div>
							<p>
								<input type="button" id="botao_adicionar_parcela" value="Adicionar" class="btn btn-success" style="float: left; width: 40%;" />
								<input type="button" id="botao_limpar_pesquisa" value="Limpar" class="btn btn-danger" style="width: 40%; margin-left: 10%;"/>
							</p>
						</div>
					</fieldset>
				</div>
				
			</div>
			
		</fieldset>
		
		<?php print $this->Form->end(array('label'=>__('Gravar'),'class'=>'btn btn-primary','div'=>array('class'=>'form-actions'))); ?>
	
	</div>

</div>