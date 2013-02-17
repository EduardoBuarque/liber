<div class="row-fluid">
		
	<fieldset class="descricao_cabecalho">
		<legend>Visualizar fornecedor</legend>

		<div class="row-fluid">
			
			<div class="span6">
				<dl class="dl-horizontal">
					<dt>Nome:</dt> <dd><?php print $fornecedor['Fornecedor']['nome'] ?></dd>
					<dt>Nome fantasia:</dt> <dd><?php  print $fornecedor['Fornecedor']['nome_fantasia'];?></dd>
					<dt>Tipo pessoa:</dt> <?php
									if ( $fornecedor['Fornecedor']['tipo_pessoa'] == 'J'):
										print '<dd>Jurídica</dd>';
									elseif ( $fornecedor['Fornecedor']['tipo_pessoa'] == 'F' ):
										print '<dd>Física</dd>';
									endif;
								?>
					<dt>Criado em:</dt> <?php print '<dd>'.$fornecedor['Fornecedor']['data_cadastrado'].'</dd>'; ?>
					<dt>por</dt> <?php print '<dd>'.$fornecedor['Usuario']['nome'].'</dd>'; ?>

					<dt>Atualizado em:</dt> <?php print '<dd>'.$fornecedor['Fornecedor']['atualizado'].'</dd>'; ?>
					<dt>por</dt> <?php print '<dd>'.$fornecedor['Usuario2']['nome'].'</dd>';?>
					<dt>Observação:</dt> <dd><textarea rows="5" readonly="readonly"><?php  print $fornecedor['Fornecedor']['observacao'];?></textarea></dd>

				</dl>
			</div>

			<div class="span6">
				<dl class="dl-horizontal">
					<dt>Logradouro nome:</dt> <dd><?php  print $fornecedor['Fornecedor']['logradouro_nome'];?></dd>
					<dt>Logradouro número:</dt> <dd><?php  print $fornecedor['Fornecedor']['logradouro_numero'];?></dd>
					<dt>Logradouro complemento:</dt> <dd><?php  print $fornecedor['Fornecedor']['logradouro_complemento'];?></dd>
					<dt>Bairro:</dt> <dd><?php  print $fornecedor['Fornecedor']['bairro'];?></dd>
					<dt>Cidade:</dt> <dd><?php  print $fornecedor['Fornecedor']['cidade'];?></dd>
					<dt>UF:</dt> <dd><?php  print $fornecedor['Fornecedor']['uf'];?></dd>
					<dt>CEP:</dt> <dd><?php  print $fornecedor['Fornecedor']['cep'];?></dd>
					<?php if ( $fornecedor['Fornecedor']['tipo_pessoa'] == 'J'): ?>
						<dt>CNPJ</dt> <dd><?php  print $fornecedor['Fornecedor']['cnpj'];?></dd>
						<dt>IE.</dt> <dd><?php  print $fornecedor['Fornecedor']['inscricao_estadual'];?></dd>
					<?php else: ?>
						<dt>CPF</dt> <dd><?php  print $fornecedor['Fornecedor']['cpf'];?></dd>
						<dt>RG:</dt> <dd><?php  print $fornecedor['Fornecedor']['rg'];?></dd>
					<?php endif; ?>
					<dt>Telefone:</dt> <dd><?php  print $fornecedor['Fornecedor']['numero_telefone'];?></dd>
					<dt>Celular:</dt> <dd><?php  print $fornecedor['Fornecedor']['numero_celular'];?></dd>
					<dt>E-mail:</dt> <dd><?php  print $fornecedor['Fornecedor']['endereco_email'];?></dd>
				</dl>
			</div>
			
		</div>

	</fieldset>
	
</div>