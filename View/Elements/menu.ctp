<ul class="sf-menu">
	<li>
		<?php print $this->Html-> link('Início', "/",array('class' => 'ajax_link'));?>
	</li>
	
	<li>
		<a href="#">Compras</a>
		<ul>
			<li>
				<?php print $this->Html-> link('Fornecedores', "/fornecedores",array('class' => 'ajax_link'));?>
				<ul>
					<li><?php print $this->Html-> link('Cadastrar', "/fornecedores/cadastrar",array('class' => 'ajax_link'));?></li>
					<li><?php print $this->Html-> link('Pesquisar', "/fornecedores/pesquisar");?></li>
					<li><?php print $this->Html-> link('Listar', "/fornecedores/",array('class' => 'ajax_link'));?></li>
					<li>
						<?php print $this->Html-> link('Categorias', "/fornecedorCategorias");?>
						<ul>
							<li>
								<?php print $this->Html-> link('Cadastrar', "/fornecedorCategorias/cadastrar",
									   array('class' => '-ajax_link_dialog',
										'data-ajax_link_dialog_altura'=>'300',
										'data-ajax_link_dialog_largura'=>'500',
										'data-ajax_link_dialog_titulo'=>'Categoria de fornecedores'));?>
							</li>
							<li><?php print $this->Html-> link('Listar', "/fornecedorCategorias",array('class' => 'ajax_link'));?></li>
						</ul>
					</li>
				</ul>
			</li>
			<li>
				<?php print $this->Html-> link('Produtos', "/produtos/",array('class' => 'ajax_link'));?>
				<ul>
					<li><?php print $this->Html-> link('Cadastrar', "/produtos/cadastrar",array('class' => 'ajax_link'));?></li>
					<li><?php print $this->Html-> link('Pesquisar', "/produtos/pesquisar");?></li>
					<li><?php print $this->Html-> link('Listar', "/produtos/",array('class' => 'ajax_link'));?></li>
					<li>
						<?php print $this->Html-> link('Categorias', "/produtoCategorias",array('class' => 'ajax_link'));?>
						<ul>
							<li>
								<?php print $this->Html-> link('Cadastrar', "/produtoCategorias/cadastrar",
									    array('class' => '-ajax_link_dialog',
										'data-ajax_link_dialog_altura'=>'300',
										'data-ajax_link_dialog_largura'=>'500',
										'data-ajax_link_dialog_titulo'=>'Categoria de produtos'));?>
							</li>
							<li><?php print $this->Html-> link('Listar', "/produtoCategorias",array('class' => 'ajax_link'));?></li>
						</ul>
					</li>
				</ul>
			</li>
			<li>
				<?php print $this->Html-> link('Pedidos', "/compraPedidos/",array('class' => 'ajax_link'));?>
				<ul>
					<li><?php print $this->Html-> link('Cadastrar', "/compraPedidos/cadastrar",array('class' => 'ajax_link'));?></li>
					<li><?php print $this->Html-> link('Pesquisar', "/compraPedidos/pesquisar");?></li>
					<li><?php print $this->Html-> link('Listar', "/compraPedidos/",array('class' => 'ajax_link'));?></li>
				</ul>
			</li>
		</ul>
	</li>
	
	<li>
		<a href="#">Vendas</a>
		<ul>
			<li>
				<?php print $this->Html-> link('Clientes', "/clientes",array('class' => 'ajax_link'));?>
				<ul>
					<li><?php print $this->Html-> link('Cadastrar', "/clientes/cadastrar",array('class' => 'ajax_link'));?></li>
					<li><?php print $this->Html-> link('Pesquisar', "/clientes/pesquisar");?></li>
					<li><?php print $this->Html-> link('Listar', "/clientes/",array('class' => 'ajax_link'));?></li>
					<li>
						<?php print $this->Html-> link('Categorias', "/clienteCategorias",array('class' => 'ajax_link'));?>
						<ul>
							<li>
								<?php print $this->Html-> link('Cadastrar', "/clienteCategorias/cadastrar",
								    array('class' => '-ajax_link_dialog',
										'data-ajax_link_dialog_altura'=>'300',
										'data-ajax_link_dialog_largura'=>'500',
										'data-ajax_link_dialog_titulo'=>'Categoria de clientes'));?>
							</li>
							<li><?php print $this->Html-> link('Listar', "/clienteCategorias",array('class' => 'ajax_link'));?></li>
						</ul>
					</li>
				</ul>
			</li>
			<li>
				<?php print $this->Html-> link('Pedido de venda', "/vendaPedidos",array('class' => 'ajax_link'));?>
				<ul>
					<li><?php print $this->Html-> link('Cadastrar', "/vendaPedidos/cadastrar");?></li>
					<li><?php print $this->Html-> link('Pesquisar', "/vendaPedidos/pesquisar");?></li>
					<li><?php print $this->Html-> link('Listar', "/vendaPedidos/",array('class' => 'ajax_link'));?></li>
				</ul>
			</li>
			<li>
				<a href="#">Relatórios</a>
				<ul>
					<li>
						<a href="#">Clientes</a>
						<ul>
							<li> <?php print $this->Html->link('Listagem de clientes cadastrados',array('controller'=>'Clientes','action'=>'RelClientesListagem'),array('target'=>'_blank')) ?> </li>
						</ul>
					</li>
					
				</ul>
			</li>
		</ul>
	</li>
	
	<li>
		<a href="#">Serviços</a>
		<ul>
			<li>
				<?php print $this->Html-> link('Ordens de serviço', "/servicoOrdens",array('class' => 'ajax_link'));?>
				<ul>
					<li><?php print $this->Html-> link('Cadastrar', "/servicoOrdens/cadastrar");?></li>
					<li><?php print $this->Html-> link('Pesquisar', "/servicoOrdens/pesquisar");?></li>
					<li><?php print $this->Html-> link('Listar', "/servicoOrdens",array('class' => 'ajax_link'));?></li>
				</ul>
			</li>
			<li>
				<?php print $this->Html-> link('Serviços', "/servicos",array('class' => 'ajax_link')); ?>
				<ul>
					<li>
						<?php print $this->Html-> link('Cadastrar', "/servicos/cadastrar",
							   array('class' => '-ajax_link_dialog',
							'data-ajax_link_dialog_altura'=>'400',
							'data-ajax_link_dialog_largura'=>'500',
							'data-ajax_link_dialog_titulo'=>'Cadastrar serviço'));?>
					</li>
					<li><?php print $this->Html-> link('Listar', "/servicos",array('class' => 'ajax_link'));?></li>
				</ul>
			</li>
			<li>
				<?php print $this->Html-> link('Categorias', "/servicoCategorias",array('class' => 'ajax_link')); ?>
				<ul>
					<li>
						<?php print $this->Html-> link('Cadastrar', "/servicoCategorias/cadastrar",
						array('class' => '-ajax_link_dialog',
							'data-ajax_link_dialog_altura'=>'300',
							'data-ajax_link_dialog_largura'=>'500',
							'data-ajax_link_dialog_titulo'=>'Categoria de serviços'));?>
					</li>
					<li><?php print $this->Html-> link('Listar', "/servicoCategorias/index",array('class' => 'ajax_link')); ?></li>
				</ul>
			</li>
			
		</ul>
	</li>
	
	<li>
		<a href="#">Expedição</a>
		<ul>
			<li>
				<?php print $this->Html-> link('Carregamento', "/carregamentos/",array('class' => 'ajax_link'));?>
				<ul>
					<li><?php print $this->Html-> link('Cadastrar', "/carregamentos/cadastrar",array('class' => 'ajax_link'));?></li>
					<li><?php print $this->Html-> link('Enviar', "/carregamentos/enviar",array('class' => 'ajax_link'));?></li>
					<li><?php print $this->Html-> link('Pesquisar', "/carregamentos/pesquisar");?></li>
					<li><?php print $this->Html-> link('Listar', "/carregamentos/",array('class' => 'ajax_link'));?></li>
				</ul>
			</li>
			
			<li>
				<?php print $this->Html-> link('Motoristas', "/motoristas",array('class' => 'ajax_link')); ?>
				<ul>
					<li>
						<?php print $this->Html-> link('Cadastrar', "/motoristas/cadastrar",
						array('class' => '-ajax_link_dialog',
							'data-ajax_link_dialog_altura'=>'400',
							'data-ajax_link_dialog_largura'=>'500',
							'data-ajax_link_dialog_titulo'=>'Cadastro de motorista'));?>
					</li>
					<li><?php print $this->Html-> link('Listar', "/motoristas/index",array('class' => 'ajax_link')); ?></li>
				</ul>
			</li>
			
			<li>
				<?php print $this->Html-> link('Veículos', "/veiculos",array('class' => 'ajax_link')); ?>
				<ul>
					<li>
						<?php print $this->Html-> link('Cadastrar', "/veiculos/cadastrar",
						array('class' => '-ajax_link_dialog',
							'data-ajax_link_dialog_altura'=>'400',
							'data-ajax_link_dialog_largura'=>'500',
							'data-ajax_link_dialog_titulo'=>'Cadastro de veículo'));?>
					</li>
					<li><?php print $this->Html-> link('Listar', "/veiculos/index",array('class' => 'ajax_link')); ?></li>
				</ul>
			</li>
			
		</ul>
	</li>

	<li>
		<a href="#" >Financeiro</a>
		<ul>
			<li>
				<?php print $this->Html-> link('Contas a receber', "/contaReceber/",array('class' => 'ajax_link'));?>
				<ul>
					<li><?php print $this->Html-> link('Cadastrar', "/contaReceber/cadastrar",array('class' => 'ajax_link'));?></li>
					<li><?php print $this->Html-> link('Pesquisar', "/contaReceber/pesquisar");?></li>
					<li><?php print $this->Html-> link('Resumo', "/contaReceber/resumo",array('class' => 'ajax_link'));?></li>
					<li><?php print $this->Html-> link('Listar', "/contaReceber/",array('class' => 'ajax_link'));?></li>
				</ul>
			</li>
			<li>
				<?php print $this->Html-> link('Contas a pagar', "/contaPagar/",array('class' => 'ajax_link'));?>
				<ul>
					<li><?php print $this->Html-> link('Cadastrar', "/contaPagar/cadastrar",array('class' => 'ajax_link'));?></li>
					<li><?php print $this->Html-> link('Pesquisar', "/contaPagar/pesquisar");?></li>
					<li><?php print $this->Html-> link('Resumo', "/contaPagar/resumo",array('class' => 'ajax_link'));?></li>
					<li><?php print $this->Html-> link('Listar', "/contaPagar/",array('class' => 'ajax_link'));?></li>
				</ul>
			</li>
			<li>
				<?php print $this->Html-> link('Plano de contas', "/contaPlanos/",array('class' => 'ajax_link'));?>
				<ul>
					<li> <?php print $this->Html-> link('Cadastrar', "/contaPlanos/cadastrar",
						   array('class' => '-ajax_link_dialog',
							'data-ajax_link_dialog_altura'=>'300',
							'data-ajax_link_dialog_largura'=>'500',
							'data-ajax_link_dialog_titulo'=>'Cadastro de item do plano de contas'));?>
					</li>
					<li> <?php print $this->Html-> link('Listar', "/contaPlanos/",array('class' => 'ajax_link'));?> </li>
				</ul>
			</li>
			<li>
				<?php print $this->Html-> link('Tipos de documentos', "/documentoTipos/",array('class' => 'ajax_link'));?>
				<ul>
					<li>
						<?php print $this->Html-> link('Cadastrar', "/documentoTipos/cadastrar",
							   array('class' => '-ajax_link_dialog',
							'data-ajax_link_dialog_altura'=>'300',
							'data-ajax_link_dialog_largura'=>'500',
							'data-ajax_link_dialog_titulo'=>'Cadastro de tipo de documento'));?>
					</li>
					<li> <?php print $this->Html-> link('Listar', "/documentoTipos/",array('class' => 'ajax_link'));?> </li>
				</ul>
			</li>
			<li class="separador"></li>
			<li>
				<?php print $this->Html-> link('Formas de pagamento', "/pagamentoTipos",array('class' => 'ajax_link'));?>
				<ul>
					<li>
						<?php print $this->Html-> link('Cadastrar', "/pagamentoTipos/cadastrar",
							   array('class' => '-ajax_link_dialog',
							'data-ajax_link_dialog_altura'=>'500',
							'data-ajax_link_dialog_largura'=>'600',
							'data-ajax_link_dialog_titulo'=>'Cadastro de forma de pagamento'));?>
					</li>
					<li>	<?php print $this->Html-> link('Listar', "/pagamentoTipos",array('class' => 'ajax_link')); ?>	</li>
				</ul>
			</li>
			<li>
				<?php print $this->Html-> link('Contas', "/contas/",array('class' => 'ajax_link'));?>
				<ul>
					<li> <?php print $this->Html-> link('Cadastrar', "/contas/cadastrar",
						   array('class' => '-ajax_link_dialog',
							'data-ajax_link_dialog_altura'=>'400',
							'data-ajax_link_dialog_largura'=>'500',
							'data-ajax_link_dialog_titulo'=>'Cadastro de conta'));?>
					</li>
					<li> <?php print $this->Html-> link('Listar', "/contas/",array('class' => 'ajax_link'));?> </li>
				</ul>
			</li>
		</ul>
	</li>

	<li>
		<a href="#">Ferramentas</a>
		<ul>
			<li>
				<?php print $this->Html-> link('Empresas', "/empresas",array('class' => 'ajax_link'));?>
				<ul>
					<li><?php print $this->Html-> link('Cadastrar', "/empresas/cadastrar",array('class' => 'ajax_link'));?></li>
					<li><?php print $this->Html-> link('Listar', "/empresas/",array('class' => 'ajax_link'));?></li>
				</ul>
			</li>
			<li>
				<?php print $this->Html-> link('Usuários', "/usuarios/",array('class' => 'ajax_link'));?>
				<ul>
					<li> <?php print $this->Html->link('Cadastrar','/usuarios/cadastrar',array('class' => 'ajax_link')); ?> </li>
					<li> <?php print $this->Html->link('Listar','/usuarios/',array('class' => 'ajax_link')); ?> </li>
					<!-- <li> <?php print $this->Html->link('Log de acesso','/usuarios/acessoLog',array('class' => 'ajax_link')); ?> </li> -->
				</ul>
			</li>
			<li>
				<?php print $this->Html-> link('Grupos', "/grupos/",array('class' => 'ajax_link'));?>
				<ul>
					<li> <?php print $this->Html->link('Cadastrar','/grupos/cadastrar',array('class' => 'ajax_link')); ?> </li>
					<li> <?php print $this->Html->link('Listar','/grupos/',array('class' => 'ajax_link')); ?> </li>
				</ul>
			</li>
			<li><?php print $this->Html-> link('Opções', "/sistemaOpcoes/",array('class' => 'ajax_link'));?></li>
			<li>
				<?php print $this->Html-> link('Ajuda', "/sistema/ajuda",
					   array('class' => '-ajax_link_dialog',
						  'data-ajax_link_dialog_altura'=>'500',
						  'data-ajax_link_dialog_largura'=>'500',
						  'data-ajax_link_dialog_titulo'=>'Ajuda'));
				?>
			</li>
			<li>
				<?php print $this->Html-> link('Sobre', "/sistema/sobre",
				   array('class' => '-ajax_link_dialog',
						  'data-ajax_link_dialog_altura'=>'500',
						  'data-ajax_link_dialog_largura'=>'500',
						  'data-ajax_link_dialog_titulo'=>'Sobre'));
				?>
			</li>
			<li><?php print $this->Html-> link('Sair', "/usuarios/logout",array('class'=>'nao-ajax'),'Deseja realmente sair?');?></li>
		</ul>
	</li>
</ul>
