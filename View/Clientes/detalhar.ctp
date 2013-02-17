<div class="row-fluid">
		
	<fieldset class="descricao_cabecalho">
		<legend>Visualizar Cliente</legend>

		<div class="row-fluid">
			
			<div class="span6">
				<dl class="dl-horizontal">
					<dt>Nome:</dt> <dd><?php print $cliente['Cliente']['nome'] ?></dd>
					<dt>Nome fantasia:</dt> <dd><?php  print $cliente['Cliente']['nome_fantasia'];?></dd>
					<dt>Tipo pessoa:</dt> <?php
									if ( $cliente['Cliente']['tipo_pessoa'] == 'J'):
										print '<dd>Jurídica</dd>';
									elseif ( $cliente['Cliente']['tipo_pessoa'] == 'F' ):
										print '<dd>Física</dd>';
									endif;
								?>
					<dt>Criado em:</dt> <?php print '<dd>'.$cliente['Cliente']['data_cadastrado'].'</dd>'; ?>
					<dt>por</dt> <?php print '<dd>'.$cliente['Usuario']['nome'].'</dd>'; ?>

					<dt>Atualizado em:</dt> <?php print '<dd>'.$cliente['Cliente']['atualizado'].'</dd>'; ?>
					<dt>por</dt> <?php print '<dd>'.$cliente['Usuario2']['nome'].'</dd>';?>
					<dt>Observação:</dt> <dd><textarea rows="5" readonly="readonly"><?php  print $cliente['Cliente']['observacao'];?></textarea></dd>

				</dl>
			</div>

			<div class="span5">
				<dl class="dl-horizontal">
					<dt>Logradouro nome:</dt> <dd><?php  print $cliente['Cliente']['logradouro_nome'];?></dd>
					<dt>Logradouro número:</dt> <dd><?php  print $cliente['Cliente']['logradouro_numero'];?></dd>
					<dt>Logradouro complemento:</dt> <dd><?php  print $cliente['Cliente']['logradouro_complemento'];?></dd>
					<dt>Bairro:</dt> <dd><?php  print $cliente['Cliente']['bairro'];?></dd>
					<dt>Cidade:</dt> <dd><?php  print $cliente['Cliente']['cidade'];?></dd>
					<dt>UF:</dt> <dd><?php  print $cliente['Cliente']['uf'];?></dd>
					<dt>CEP:</dt> <dd><?php  print $cliente['Cliente']['cep'];?></dd>
					<?php if ( $cliente['Cliente']['tipo_pessoa'] == 'J'): ?>
						<dt>CNPJ</dt> <dd><?php  print $cliente['Cliente']['cnpj'];?></dd>
						<dt>IE.</dt> <dd><?php  print $cliente['Cliente']['inscricao_estadual'];?></dd>
					<?php else: ?>
						<dt>CPF</dt> <dd><?php  print $cliente['Cliente']['cpf'];?></dd>
						<dt>RG:</dt> <dd><?php  print $cliente['Cliente']['rg'];?></dd>
					<?php endif; ?>
					<dt>Telefone:</dt> <dd><?php  print $cliente['Cliente']['numero_telefone'];?></dd>
					<dt>Celular:</dt> <dd><?php  print $cliente['Cliente']['numero_celular'];?></dd>
					<dt>E-mail:</dt> <dd><?php  print $cliente['Cliente']['endereco_email'];?></dd>
				</dl>
			</div>
			
		</div>

	</fieldset>
	
</div>