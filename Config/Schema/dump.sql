SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

DROP SCHEMA IF EXISTS `liber` ;
CREATE SCHEMA IF NOT EXISTS `liber` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `liber` ;

-- -----------------------------------------------------
-- Table `liber`.`empresas`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `liber`.`empresas` ;

CREATE  TABLE IF NOT EXISTS `liber`.`empresas` (
  `id` INT(5) NOT NULL AUTO_INCREMENT ,
  `nome` VARCHAR(100) NOT NULL ,
  `cnpj` CHAR(14) NULL ,
  `inscricao_estadual` CHAR(12) NULL ,
  `telefone` CHAR(10) NULL ,
  `fax` CHAR(10) NULL ,
  `site` VARCHAR(100) NULL ,
  `endereco_email_principal` VARCHAR(100) NULL ,
  `endereco_email_secundario` VARCHAR(100) NULL ,
  `logradouro` VARCHAR(100) NOT NULL ,
  `numero` CHAR(10) NOT NULL ,
  `bairro` VARCHAR(100) NOT NULL ,
  `complemento` VARCHAR(30) NULL ,
  `cidade` VARCHAR(100) NOT NULL ,
  `estado` CHAR(2) NOT NULL ,
  `cep` CHAR(8) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `liber`.`grupos`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `liber`.`grupos` ;

CREATE  TABLE IF NOT EXISTS `liber`.`grupos` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `nome` VARCHAR(50) NOT NULL ,
  `empresa_id` INT(5) NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_grupos_empresas1` (`empresa_id` ASC) ,
  CONSTRAINT `fk_grupos_empresas1`
    FOREIGN KEY (`empresa_id` )
    REFERENCES `liber`.`empresas` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `liber`.`usuarios`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `liber`.`usuarios` ;

CREATE  TABLE IF NOT EXISTS `liber`.`usuarios` (
  `id` INT(5) NOT NULL AUTO_INCREMENT ,
  `nome` VARCHAR(100) NOT NULL ,
  `login` VARCHAR(50) NOT NULL ,
  `senha` CHAR(40) NOT NULL ,
  `grupo_id` INT NOT NULL ,
  `ativo` TINYINT(1) NOT NULL DEFAULT 1 ,
  `email` VARCHAR(100) NULL ,
  `tempo_criado` DATETIME NOT NULL ,
  `ultimo_login` DATETIME NULL ,
  `ultimo_logout` DATETIME NULL ,
  `eh_tecnico` TINYINT(1) NOT NULL DEFAULT 0 ,
  `eh_vendedor` TINYINT(1) NOT NULL DEFAULT 0 ,
  `empresa_id` INT(5) NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `usuario_UNIQUE` (`login` ASC) ,
  INDEX `fk_usuarios_grupos1` (`grupo_id` ASC) ,
  INDEX `fk_usuarios_empresas1` (`empresa_id` ASC) ,
  CONSTRAINT `fk_usuarios_grupos1`
    FOREIGN KEY (`grupo_id` )
    REFERENCES `liber`.`grupos` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_usuarios_empresas1`
    FOREIGN KEY (`empresa_id` )
    REFERENCES `liber`.`empresas` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `liber`.`cliente_categorias`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `liber`.`cliente_categorias` ;

CREATE  TABLE IF NOT EXISTS `liber`.`cliente_categorias` (
  `id` INT(5) NOT NULL AUTO_INCREMENT ,
  `descricao` VARCHAR(100) NOT NULL ,
  `empresa_id` INT(5) NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_cliente_categorias_empresas1` (`empresa_id` ASC) ,
  CONSTRAINT `fk_cliente_categorias_empresas1`
    FOREIGN KEY (`empresa_id` )
    REFERENCES `liber`.`empresas` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `liber`.`clientes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `liber`.`clientes` ;

CREATE  TABLE IF NOT EXISTS `liber`.`clientes` (
  `id` INT(5) NULL AUTO_INCREMENT ,
  `data_cadastrado` DATETIME NOT NULL ,
  `tipo_pessoa` CHAR(1) NULL ,
  `nome` VARCHAR(100) NOT NULL ,
  `nome_fantasia` VARCHAR(100) NULL ,
  `logradouro_nome` VARCHAR(100) NOT NULL ,
  `logradouro_numero` CHAR(10) NOT NULL ,
  `logradouro_complemento` VARCHAR(50) NULL ,
  `bairro` VARCHAR(100) NOT NULL ,
  `cidade` VARCHAR(100) NULL ,
  `uf` CHAR(2) NULL ,
  `cep` CHAR(8) NULL ,
  `cnpj` CHAR(14) NULL ,
  `inscricao_estadual` CHAR(12) NULL ,
  `cpf` CHAR(11) NULL ,
  `rg` VARCHAR(50) NULL ,
  `inscricao_municipal` VARCHAR(100) NULL ,
  `numero_telefone` CHAR(10) NULL ,
  `numero_celular` CHAR(10) NULL ,
  `endereco_email` VARCHAR(100) NULL ,
  `observacao` TEXT NULL ,
  `situacao` CHAR(1) NOT NULL COMMENT 'Situacao:\nA -> Ativo\nI -> Inativo\nB -> Bloqueado' ,
  `cliente_categoria_id` INT(5) NOT NULL ,
  `empresa_id` INT(5) NOT NULL ,
  `atualizado` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
  `usuario_cadastrou` INT(5) NOT NULL ,
  `usuario_alterou` INT(5) NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_clientes_usuarios1` (`usuario_cadastrou` ASC) ,
  INDEX `fk_clientes_usuarios2` (`usuario_alterou` ASC) ,
  INDEX `fk_clientes_empresas1` (`empresa_id` ASC) ,
  INDEX `fk_clientes_cliente_categorias1` (`cliente_categoria_id` ASC) ,
  CONSTRAINT `fk_clientes_usuarios1`
    FOREIGN KEY (`usuario_cadastrou` )
    REFERENCES `liber`.`usuarios` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_clientes_usuarios2`
    FOREIGN KEY (`usuario_alterou` )
    REFERENCES `liber`.`usuarios` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_clientes_empresas1`
    FOREIGN KEY (`empresa_id` )
    REFERENCES `liber`.`empresas` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_clientes_cliente_categorias1`
    FOREIGN KEY (`cliente_categoria_id` )
    REFERENCES `liber`.`cliente_categorias` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `liber`.`fornecedor_categorias`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `liber`.`fornecedor_categorias` ;

CREATE  TABLE IF NOT EXISTS `liber`.`fornecedor_categorias` (
  `id` INT(5) NOT NULL AUTO_INCREMENT ,
  `descricao` VARCHAR(100) NOT NULL ,
  `empresa_id` INT(5) NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_fornecedor_categorias_empresas1` (`empresa_id` ASC) ,
  CONSTRAINT `fk_fornecedor_categorias_empresas1`
    FOREIGN KEY (`empresa_id` )
    REFERENCES `liber`.`empresas` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `liber`.`fornecedores`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `liber`.`fornecedores` ;

CREATE  TABLE IF NOT EXISTS `liber`.`fornecedores` (
  `id` INT(5) NULL AUTO_INCREMENT ,
  `data_cadastrado` DATETIME NOT NULL ,
  `tipo_pessoa` CHAR(1) NULL ,
  `nome` VARCHAR(100) NOT NULL ,
  `nome_fantasia` VARCHAR(100) NULL ,
  `logradouro_nome` VARCHAR(100) NOT NULL ,
  `logradouro_numero` CHAR(10) NOT NULL ,
  `logradouro_complemento` VARCHAR(50) NULL ,
  `bairro` VARCHAR(100) NOT NULL ,
  `cidade` VARCHAR(100) NULL ,
  `uf` CHAR(2) NULL ,
  `cep` CHAR(8) NULL ,
  `cnpj` CHAR(14) NULL ,
  `inscricao_estadual` CHAR(12) NULL ,
  `cpf` CHAR(11) NULL ,
  `rg` VARCHAR(50) NULL ,
  `inscricao_municipal` VARCHAR(100) NULL ,
  `numero_telefone` CHAR(10) NULL ,
  `numero_celular` CHAR(10) NULL ,
  `endereco_email` VARCHAR(100) NULL ,
  `observacao` TEXT NULL ,
  `situacao` CHAR(1) NOT NULL COMMENT 'Situacao:\nA -> Ativo\nI -> Inativo\nB -> Bloqueado' ,
  `fornecedor_categoria_id` INT(5) NOT NULL ,
  `empresa_id` INT(5) NOT NULL ,
  `atualizado` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
  `usuario_cadastrou` INT(5) NOT NULL ,
  `usuario_alterou` INT(5) NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_fornecedores_usuarios1` (`usuario_cadastrou` ASC) ,
  INDEX `fk_fornecedores_usuarios2` (`usuario_alterou` ASC) ,
  INDEX `fk_fornecedores_empresas1` (`empresa_id` ASC) ,
  INDEX `fk_fornecedores_fornecedor_categorias1` (`fornecedor_categoria_id` ASC) ,
  CONSTRAINT `fk_fornecedores_usuarios1`
    FOREIGN KEY (`usuario_cadastrou` )
    REFERENCES `liber`.`usuarios` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_fornecedores_usuarios2`
    FOREIGN KEY (`usuario_alterou` )
    REFERENCES `liber`.`usuarios` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_fornecedores_empresas1`
    FOREIGN KEY (`empresa_id` )
    REFERENCES `liber`.`empresas` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_fornecedores_fornecedor_categorias1`
    FOREIGN KEY (`fornecedor_categoria_id` )
    REFERENCES `liber`.`fornecedor_categorias` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `liber`.`conta_planos`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `liber`.`conta_planos` ;

CREATE  TABLE IF NOT EXISTS `liber`.`conta_planos` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `nome` VARCHAR(50) NOT NULL ,
  `tipo` CHAR(1) NOT NULL COMMENT 'Tipo:\nD=Despesas\nR=Receitas\nE=Especiais' ,
  `empresa_id` INT(5) NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_plano_contas_empresas1` (`empresa_id` ASC) ,
  CONSTRAINT `fk_plano_contas_empresas1`
    FOREIGN KEY (`empresa_id` )
    REFERENCES `liber`.`empresas` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `liber`.`contas`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `liber`.`contas` ;

CREATE  TABLE IF NOT EXISTS `liber`.`contas` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `nome` VARCHAR(100) NOT NULL ,
  `apelido` VARCHAR(50) NOT NULL ,
  `banco` VARCHAR(50) NULL ,
  `agencia` VARCHAR(50) NULL ,
  `conta` VARCHAR(50) NULL ,
  `titular` VARCHAR(100) NULL ,
  `empresa_id` INT(5) NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_contas_empresas1` (`empresa_id` ASC) ,
  CONSTRAINT `fk_contas_empresas1`
    FOREIGN KEY (`empresa_id` )
    REFERENCES `liber`.`empresas` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `liber`.`documento_tipos`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `liber`.`documento_tipos` ;

CREATE  TABLE IF NOT EXISTS `liber`.`documento_tipos` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `nome` VARCHAR(50) NOT NULL ,
  `empresa_id` INT(5) NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_tipo_documentos_empresas1` (`empresa_id` ASC) ,
  CONSTRAINT `fk_tipo_documentos_empresas1`
    FOREIGN KEY (`empresa_id` )
    REFERENCES `liber`.`empresas` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `liber`.`pagamento_tipos`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `liber`.`pagamento_tipos` ;

CREATE  TABLE IF NOT EXISTS `liber`.`pagamento_tipos` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `nome` VARCHAR(50) NOT NULL ,
  `conta_principal` INT NOT NULL ,
  `documento_tipo_id` INT NOT NULL ,
  `empresa_id` INT(5) NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_forma_pagamentos_contas1` (`conta_principal` ASC) ,
  INDEX `fk_forma_pagamentos_documento_tipos1` (`documento_tipo_id` ASC) ,
  INDEX `fk_forma_pagamentos_empresas1` (`empresa_id` ASC) ,
  CONSTRAINT `fk_forma_pagamentos_contas1`
    FOREIGN KEY (`conta_principal` )
    REFERENCES `liber`.`contas` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_forma_pagamentos_documento_tipos1`
    FOREIGN KEY (`documento_tipo_id` )
    REFERENCES `liber`.`documento_tipos` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_forma_pagamentos_empresas1`
    FOREIGN KEY (`empresa_id` )
    REFERENCES `liber`.`empresas` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `liber`.`produto_categorias`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `liber`.`produto_categorias` ;

CREATE  TABLE IF NOT EXISTS `liber`.`produto_categorias` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `nome` VARCHAR(100) NOT NULL ,
  `empresa_id` INT(5) NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_produto_categorias_empresas1` (`empresa_id` ASC) ,
  CONSTRAINT `fk_produto_categorias_empresas1`
    FOREIGN KEY (`empresa_id` )
    REFERENCES `liber`.`empresas` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `liber`.`produtos`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `liber`.`produtos` ;

CREATE  TABLE IF NOT EXISTS `liber`.`produtos` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `nome` VARCHAR(100) NOT NULL ,
  `produto_categoria_id` INT NOT NULL ,
  `tipo_produto` CHAR(1) NOT NULL COMMENT 'Tipo_produto:\nPara venda\nMatéria-prima\nMatéria-prima e venda\nProduto composto' ,
  `codigo_ean` VARCHAR(45) NULL ,
  `codigo_dun` VARCHAR(45) NULL ,
  `preco_custo` FLOAT NULL ,
  `preco_venda` FLOAT NULL ,
  `margem_lucro` FLOAT NULL ,
  `tem_estoque_ilimitado` TINYINT(1) NOT NULL DEFAULT 0 ,
  `estoque_minimo` INT(10) NULL ,
  `unidade` VARCHAR(45) NULL ,
  `quantidade_estoque_fiscal` FLOAT NOT NULL ,
  `quantidade_estoque_nao_fiscal` FLOAT NOT NULL ,
  `quantidade_reservada` FLOAT NULL ,
  `situacao` CHAR(1) NOT NULL DEFAULT 'L' COMMENT 'L = Em linha\nF = Fora de linha' ,
  `empresa_id` INT(5) NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_produtos_categoria_produtos1` (`produto_categoria_id` ASC) ,
  INDEX `fk_produtos_empresas1` (`empresa_id` ASC) ,
  CONSTRAINT `fk_produtos_categoria_produtos1`
    FOREIGN KEY (`produto_categoria_id` )
    REFERENCES `liber`.`produto_categorias` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_produtos_empresas1`
    FOREIGN KEY (`empresa_id` )
    REFERENCES `liber`.`empresas` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `liber`.`conta_pagar`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `liber`.`conta_pagar` ;

CREATE  TABLE IF NOT EXISTS `liber`.`conta_pagar` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `data_hora_cadastrada` DATETIME NOT NULL ,
  `eh_fiscal` TINYINT(1) NOT NULL ,
  `eh_cliente_ou_fornecedor` CHAR(1) NOT NULL ,
  `cliente_fornecedor_id` INT(5) NOT NULL ,
  `documento_tipo_id` INT NOT NULL ,
  `numero_documento` VARCHAR(20) NULL ,
  `valor` FLOAT(5) NOT NULL ,
  `conta_origem` INT NOT NULL ,
  `conta_plano_id` INT NOT NULL ,
  `data_vencimento` DATE NOT NULL ,
  `observacao` TEXT NULL ,
  `empresa_id` INT(5) NOT NULL ,
  `situacao` CHAR(1) NOT NULL COMMENT 'P = Paga\nN = Não paga' ,
  `numero_parcelas` INT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_contas_pagar_contas1` (`conta_origem` ASC) ,
  INDEX `fk_contas_pagar_conta_planos1` (`conta_plano_id` ASC) ,
  INDEX `fk_contas_pagar_documento_tipos1` (`documento_tipo_id` ASC) ,
  INDEX `fk_pagar_contas_empresas1` (`empresa_id` ASC) ,
  CONSTRAINT `fk_contas_pagar_contas1`
    FOREIGN KEY (`conta_origem` )
    REFERENCES `liber`.`contas` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_contas_pagar_conta_planos1`
    FOREIGN KEY (`conta_plano_id` )
    REFERENCES `liber`.`conta_planos` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_contas_pagar_documento_tipos1`
    FOREIGN KEY (`documento_tipo_id` )
    REFERENCES `liber`.`documento_tipos` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_pagar_contas_empresas1`
    FOREIGN KEY (`empresa_id` )
    REFERENCES `liber`.`empresas` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `liber`.`conta_receber`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `liber`.`conta_receber` ;

CREATE  TABLE IF NOT EXISTS `liber`.`conta_receber` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `data_hora_cadastrada` DATETIME NOT NULL ,
  `eh_fiscal` TINYINT(1) NOT NULL ,
  `eh_cliente_ou_fornecedor` CHAR(1) NOT NULL ,
  `cliente_fornecedor_id` INT(5) NOT NULL ,
  `documento_tipo_id` INT NOT NULL ,
  `numero_documento` VARCHAR(20) NULL ,
  `valor` FLOAT(5) NOT NULL ,
  `conta_origem` INT NOT NULL ,
  `conta_plano_id` INT NOT NULL ,
  `data_vencimento` DATE NOT NULL ,
  `observacao` TEXT NULL ,
  `empresa_id` INT(5) NOT NULL ,
  `situacao` CHAR(1) NOT NULL COMMENT 'P = Paga\nN = Não paga' ,
  `numero_parcelas` INT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_contas_pagar_contas1` (`conta_origem` ASC) ,
  INDEX `fk_contas_pagar_plano_contas1` (`conta_plano_id` ASC) ,
  INDEX `fk_contas_pagar_tipo_documentos1` (`documento_tipo_id` ASC) ,
  INDEX `fk_receber_contas_empresas1` (`empresa_id` ASC) ,
  CONSTRAINT `fk_contas_pagar_contas10`
    FOREIGN KEY (`conta_origem` )
    REFERENCES `liber`.`contas` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_contas_pagar_conta_planos10`
    FOREIGN KEY (`conta_plano_id` )
    REFERENCES `liber`.`conta_planos` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_contas_pagar_documento_tipos10`
    FOREIGN KEY (`documento_tipo_id` )
    REFERENCES `liber`.`documento_tipos` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_receber_contas_empresas1`
    FOREIGN KEY (`empresa_id` )
    REFERENCES `liber`.`empresas` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `liber`.`produto_estoque_logs`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `liber`.`produto_estoque_logs` ;

CREATE  TABLE IF NOT EXISTS `liber`.`produto_estoque_logs` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `data_hora_registro` DATETIME NOT NULL ,
  `produto_id` INT NOT NULL ,
  `quantidade_estoque_fiscal` FLOAT NOT NULL ,
  `quantidade_estoque_nao_fiscal` FLOAT NOT NULL ,
  `quantidade_reservada` FLOAT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_log_estoque_produtos_produtos1` (`produto_id` ASC) ,
  CONSTRAINT `fk_log_estoque_produtos_produtos1`
    FOREIGN KEY (`produto_id` )
    REFERENCES `liber`.`produtos` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `liber`.`venda_pedidos`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `liber`.`venda_pedidos` ;

CREATE  TABLE IF NOT EXISTS `liber`.`venda_pedidos` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `data_hora_cadastrado` DATETIME NOT NULL ,
  `cliente_id` INT(5) NOT NULL ,
  `pagamento_tipo_id` INT NOT NULL ,
  `data_saida` DATE NULL DEFAULT NULL ,
  `data_entrega` DATE NULL DEFAULT NULL ,
  `data_venda` DATE NULL DEFAULT NULL ,
  `custo_frete` FLOAT(5) NULL ,
  `custo_seguro` FLOAT(5) NULL ,
  `custo_outros` FLOAT(5) NULL ,
  `desconto` FLOAT(5) NULL ,
  `situacao` CHAR(1) NOT NULL COMMENT 'A = Aberto\nO = Orçamento\nC = Cancelado\nV = Vendido' ,
  `observacao` TEXT NULL ,
  `usuario_cadastrou` INT(5) NOT NULL ,
  `vendedor_id` INT(5) NOT NULL ,
  `valor_bruto` FLOAT NOT NULL ,
  `valor_liquido` FLOAT NOT NULL ,
  `usuario_alterou` INT(5) NULL ,
  `empresa_id` INT(5) NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_pedido_vendas_clientes1` (`cliente_id` ASC) ,
  INDEX `fk_pedido_vendas_pagamento_tipos1` (`pagamento_tipo_id` ASC) ,
  INDEX `fk_pedido_vendas_usuarios2` (`usuario_alterou` ASC) ,
  INDEX `fk_pedido_vendas_usuarios3` (`usuario_cadastrou` ASC) ,
  INDEX `fk_pedido_vendas_empresas1` (`empresa_id` ASC) ,
  INDEX `fk_pedido_vendas_usuarios1` (`vendedor_id` ASC) ,
  CONSTRAINT `fk_pedido_vendas_clientes1`
    FOREIGN KEY (`cliente_id` )
    REFERENCES `liber`.`clientes` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_pedido_vendas_pagamento_tipos1`
    FOREIGN KEY (`pagamento_tipo_id` )
    REFERENCES `liber`.`pagamento_tipos` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_pedido_vendas_usuarios2`
    FOREIGN KEY (`usuario_alterou` )
    REFERENCES `liber`.`usuarios` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_pedido_vendas_usuarios3`
    FOREIGN KEY (`usuario_cadastrou` )
    REFERENCES `liber`.`usuarios` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_pedido_vendas_empresas1`
    FOREIGN KEY (`empresa_id` )
    REFERENCES `liber`.`empresas` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_pedido_vendas_usuarios1`
    FOREIGN KEY (`vendedor_id` )
    REFERENCES `liber`.`usuarios` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `liber`.`servico_ordens`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `liber`.`servico_ordens` ;

CREATE  TABLE IF NOT EXISTS `liber`.`servico_ordens` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `data_hora_cadastrada` DATETIME NOT NULL ,
  `cliente_id` INT(5) NOT NULL ,
  `usuario_id` INT(5) NOT NULL ,
  `pagamento_tipo_id` INT NOT NULL ,
  `situacao` CHAR(1) NOT NULL COMMENT 'O = Orçamento\nS = Em espera\nX = Em execução\nF = Finalizada\nE = Entregue\nC = Cancelada' ,
  `dias_garantia` INT(3) NULL ,
  `data_hora_inicio` DATETIME NOT NULL ,
  `data_hora_fim` DATETIME NULL ,
  `custo_outros` FLOAT NULL ,
  `desconto` FLOAT NULL ,
  `defeitos_relatados` TEXT NULL ,
  `laudo_tecnico` TEXT NULL ,
  `observacao` TEXT NULL ,
  `valor_bruto` FLOAT NOT NULL ,
  `valor_liquido` FLOAT NOT NULL ,
  `usuario_cadastrou` INT(5) NOT NULL ,
  `usuario_alterou` INT(5) NULL ,
  `empresa_id` INT(5) NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_servico_ordens_clientes1` (`cliente_id` ASC) ,
  INDEX `fk_servico_ordens_usuarios1` (`usuario_id` ASC) ,
  INDEX `fk_servico_ordens_pagamento_tipos1` (`pagamento_tipo_id` ASC) ,
  INDEX `fk_servico_ordens_usuarios2` (`usuario_alterou` ASC) ,
  INDEX `fk_servico_ordens_usuarios3` (`usuario_cadastrou` ASC) ,
  INDEX `fk_servico_ordens_empresas1` (`empresa_id` ASC) ,
  CONSTRAINT `fk_servico_ordens_clientes1`
    FOREIGN KEY (`cliente_id` )
    REFERENCES `liber`.`clientes` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_servico_ordens_usuarios1`
    FOREIGN KEY (`usuario_id` )
    REFERENCES `liber`.`usuarios` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_servico_ordens_pagamento_tipos1`
    FOREIGN KEY (`pagamento_tipo_id` )
    REFERENCES `liber`.`pagamento_tipos` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_servico_ordens_usuarios2`
    FOREIGN KEY (`usuario_alterou` )
    REFERENCES `liber`.`usuarios` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_servico_ordens_usuarios3`
    FOREIGN KEY (`usuario_cadastrou` )
    REFERENCES `liber`.`usuarios` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_servico_ordens_empresas1`
    FOREIGN KEY (`empresa_id` )
    REFERENCES `liber`.`empresas` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `liber`.`servico_categorias`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `liber`.`servico_categorias` ;

CREATE  TABLE IF NOT EXISTS `liber`.`servico_categorias` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `nome` VARCHAR(50) NOT NULL ,
  `empresa_id` INT(5) NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_servico_categorias_empresas1` (`empresa_id` ASC) ,
  CONSTRAINT `fk_servico_categorias_empresas1`
    FOREIGN KEY (`empresa_id` )
    REFERENCES `liber`.`empresas` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `liber`.`servicos`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `liber`.`servicos` ;

CREATE  TABLE IF NOT EXISTS `liber`.`servicos` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `nome` VARCHAR(50) NOT NULL ,
  `servico_categoria_id` INT NOT NULL ,
  `valor` FLOAT(5) NOT NULL ,
  `empresa_id` INT(5) NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_servico_tipos_servico_categorias1` (`servico_categoria_id` ASC) ,
  INDEX `fk_servicos_empresas1` (`empresa_id` ASC) ,
  CONSTRAINT `fk_servico_tipos_servico_categorias1`
    FOREIGN KEY (`servico_categoria_id` )
    REFERENCES `liber`.`servico_categorias` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_servicos_empresas1`
    FOREIGN KEY (`empresa_id` )
    REFERENCES `liber`.`empresas` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `liber`.`servico_ordem_itens`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `liber`.`servico_ordem_itens` ;

CREATE  TABLE IF NOT EXISTS `liber`.`servico_ordem_itens` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `servico_ordem_id` INT NOT NULL ,
  `servico_id` INT NOT NULL ,
  `quantidade` INT(5) NOT NULL ,
  `valor` FLOAT(5) NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_servico_ordem_itens_servicos1` (`servico_id` ASC) ,
  INDEX `fk_servico_ordem_itens_servico_ordens1` (`servico_ordem_id` ASC) ,
  CONSTRAINT `fk_servico_ordem_itens_servicos1`
    FOREIGN KEY (`servico_id` )
    REFERENCES `liber`.`servicos` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_servico_ordem_itens_servico_ordens1`
    FOREIGN KEY (`servico_ordem_id` )
    REFERENCES `liber`.`servico_ordens` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `liber`.`veiculos`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `liber`.`veiculos` ;

CREATE  TABLE IF NOT EXISTS `liber`.`veiculos` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `placa` VARCHAR(45) NULL ,
  `fabricante` VARCHAR(50) NULL ,
  `modelo` VARCHAR(50) NULL ,
  `ano` YEAR NULL ,
  `empresa_id` INT(5) NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_veiculos_empresas1` (`empresa_id` ASC) ,
  CONSTRAINT `fk_veiculos_empresas1`
    FOREIGN KEY (`empresa_id` )
    REFERENCES `liber`.`empresas` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `liber`.`motoristas`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `liber`.`motoristas` ;

CREATE  TABLE IF NOT EXISTS `liber`.`motoristas` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `nome` VARCHAR(100) NOT NULL ,
  `cnh_numero_registro` INT NULL ,
  `cnh_data_validade` DATE NULL ,
  `cnh_categoria` CHAR(1) NULL ,
  `logradouro_nome` VARCHAR(100) NULL ,
  `logradouro_numero` VARCHAR(10) NULL ,
  `logradouro_complemento` VARCHAR(50) NULL ,
  `veiculo_padrao` INT NULL ,
  `empresa_id` INT(5) NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_motoristas_veiculos1` (`veiculo_padrao` ASC) ,
  INDEX `fk_motoristas_empresas1` (`empresa_id` ASC) ,
  CONSTRAINT `fk_motoristas_veiculos1`
    FOREIGN KEY (`veiculo_padrao` )
    REFERENCES `liber`.`veiculos` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_motoristas_empresas1`
    FOREIGN KEY (`empresa_id` )
    REFERENCES `liber`.`empresas` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `liber`.`carregamentos`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `liber`.`carregamentos` ;

CREATE  TABLE IF NOT EXISTS `liber`.`carregamentos` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `data_hora_criado` DATETIME NOT NULL ,
  `situacao` CHAR(1) NOT NULL COMMENT 'Situacao\nL = Livre\nE = Enviada' ,
  `descricao` VARCHAR(50) NOT NULL ,
  `motorista_id` INT NOT NULL ,
  `veiculo_id` INT NOT NULL ,
  `observacao` TEXT NULL ,
  `empresa_id` INT(5) NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_carregamentos_motoristas1` (`motorista_id` ASC) ,
  INDEX `fk_carregamentos_veiculos1` (`veiculo_id` ASC) ,
  INDEX `fk_carregamentos_empresas1` (`empresa_id` ASC) ,
  CONSTRAINT `fk_carregamentos_motoristas1`
    FOREIGN KEY (`motorista_id` )
    REFERENCES `liber`.`motoristas` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_carregamentos_veiculos1`
    FOREIGN KEY (`veiculo_id` )
    REFERENCES `liber`.`veiculos` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_carregamentos_empresas1`
    FOREIGN KEY (`empresa_id` )
    REFERENCES `liber`.`empresas` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `liber`.`carregamento_itens`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `liber`.`carregamento_itens` ;

CREATE  TABLE IF NOT EXISTS `liber`.`carregamento_itens` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `carregamento_id` INT NOT NULL ,
  `venda_pedido_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_carregamento_itens_carregamentos1` (`carregamento_id` ASC) ,
  INDEX `fk_carregamento_itens_venda_pedidos1` (`venda_pedido_id` ASC) ,
  CONSTRAINT `fk_carregamento_itens_carregamentos1`
    FOREIGN KEY (`carregamento_id` )
    REFERENCES `liber`.`carregamentos` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_carregamento_itens_venda_pedidos1`
    FOREIGN KEY (`venda_pedido_id` )
    REFERENCES `liber`.`venda_pedidos` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `liber`.`venda_pedido_itens`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `liber`.`venda_pedido_itens` ;

CREATE  TABLE IF NOT EXISTS `liber`.`venda_pedido_itens` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `venda_pedido_id` INT NOT NULL ,
  `produto_id` INT NOT NULL ,
  `quantidade` FLOAT NOT NULL ,
  `preco_venda` FLOAT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_pedido_venda_itens_venda_pedidos1` (`venda_pedido_id` ASC) ,
  INDEX `fk_pedido_venda_itens_produtos1` (`produto_id` ASC) ,
  CONSTRAINT `fk_pedido_venda_itens_venda_pedidos1`
    FOREIGN KEY (`venda_pedido_id` )
    REFERENCES `liber`.`venda_pedidos` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_pedido_venda_itens_produtos1`
    FOREIGN KEY (`produto_id` )
    REFERENCES `liber`.`produtos` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `liber`.`pagamento_tipo_itens`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `liber`.`pagamento_tipo_itens` ;

CREATE  TABLE IF NOT EXISTS `liber`.`pagamento_tipo_itens` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `pagamento_tipo_id` INT NOT NULL ,
  `dias_intervalo_parcela` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_forma_pagamento_itens_pagamento_tipos1` (`pagamento_tipo_id` ASC) ,
  CONSTRAINT `fk_forma_pagamento_itens_pagamento_tipos1`
    FOREIGN KEY (`pagamento_tipo_id` )
    REFERENCES `liber`.`pagamento_tipos` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `liber`.`usuario_acesso_logs`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `liber`.`usuario_acesso_logs` ;

CREATE  TABLE IF NOT EXISTS `liber`.`usuario_acesso_logs` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `data_login` DATE NOT NULL ,
  `hora_login` TIME NOT NULL ,
  `usuario_id` INT(5) NOT NULL ,
  `cliente_ip` VARCHAR(15) NULL ,
  `cliente_nome` TEXT NULL ,
  `cliente_user_agent` TEXT NULL ,
  `servidor_ip` VARCHAR(15) NULL ,
  `servidor_nome` TEXT NULL ,
  `data_logout` DATE NULL ,
  `hora_logout` TIME NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_usuario_log_acessos_usuarios1` (`usuario_id` ASC) ,
  CONSTRAINT `fk_usuario_log_acessos_usuarios1`
    FOREIGN KEY (`usuario_id` )
    REFERENCES `liber`.`usuarios` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `liber`.`usuario_acesso_tentativas`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `liber`.`usuario_acesso_tentativas` ;

CREATE  TABLE IF NOT EXISTS `liber`.`usuario_acesso_tentativas` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `data_hora` DATETIME NOT NULL ,
  `login` VARCHAR(100) NULL ,
  `cliente_ip` VARCHAR(15) NULL ,
  `cliente_nome` TEXT NULL COMMENT 'O DNS reverso do cliente. É necessário configuração prévia. Vide http://php.net/manual/pt_BR/reserved.variables.server.php' ,
  `cliente_user_agent` TEXT NULL ,
  `servidor_ip` VARCHAR(15) NULL ,
  `servidor_nome` TEXT NULL ,
  `bloqueado` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Marca se o usuario está bloqueado' ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `liber`.`sistema_opcoes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `liber`.`sistema_opcoes` ;

CREATE  TABLE IF NOT EXISTS `liber`.`sistema_opcoes` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `login_periodo_tentativas` INT NOT NULL ,
  `login_maximo_tentativas` INT NOT NULL ,
  `login_tempo_bloqueio` INT NOT NULL COMMENT 'Tempo, em minutos, que o usuário ficará impedido de acessar o sistema.' ,
  `item_conta_planos_venda_pedidos` INT NOT NULL ,
  `item_conta_planos_ordem_servicos` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_sistema_opcoes_conta_planos1` (`item_conta_planos_venda_pedidos` ASC) ,
  INDEX `fk_sistema_opcoes_conta_planos2` (`item_conta_planos_ordem_servicos` ASC) ,
  CONSTRAINT `fk_sistema_opcoes_conta_planos1`
    FOREIGN KEY (`item_conta_planos_venda_pedidos` )
    REFERENCES `liber`.`conta_planos` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_sistema_opcoes_conta_planos2`
    FOREIGN KEY (`item_conta_planos_ordem_servicos` )
    REFERENCES `liber`.`conta_planos` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `liber`.`compra_pedidos`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `liber`.`compra_pedidos` ;

CREATE  TABLE IF NOT EXISTS `liber`.`compra_pedidos` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `data_hora_cadastrado` DATETIME NOT NULL ,
  `situacao` CHAR(1) NOT NULL ,
  `fornecedor_id` INT(5) NOT NULL ,
  `pagamento_tipo_id` INT NOT NULL ,
  `comprador_id` INT(5) NOT NULL ,
  `data_compra` DATE NULL ,
  `data_saida` DATE NULL ,
  `data_entrega` DATE NULL ,
  `custo_frete` FLOAT(5) NULL ,
  `custo_seguro` FLOAT(5) NULL ,
  `custo_outros` FLOAT(5) NULL ,
  `desconto` FLOAT(5) NULL ,
  `observacao` TEXT NULL ,
  `valor_bruto` FLOAT(5) NOT NULL ,
  `valor_liquido` FLOAT(5) NOT NULL ,
  `usuario_cadastrou` INT(5) NOT NULL ,
  `usuario_alterou` INT(5) NULL ,
  `empresa_id` INT(5) NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_compra_pedidos_fornecedores1` (`fornecedor_id` ASC) ,
  INDEX `fk_compra_pedidos_pagamento_tipos1` (`pagamento_tipo_id` ASC) ,
  INDEX `fk_compra_pedidos_usuarios1` (`comprador_id` ASC) ,
  INDEX `fk_compra_pedidos_usuarios2` (`usuario_cadastrou` ASC) ,
  INDEX `fk_compra_pedidos_usuarios3` (`usuario_alterou` ASC) ,
  INDEX `fk_compra_pedidos_empresas1` (`empresa_id` ASC) ,
  CONSTRAINT `fk_compra_pedidos_fornecedores1`
    FOREIGN KEY (`fornecedor_id` )
    REFERENCES `liber`.`fornecedores` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_compra_pedidos_pagamento_tipos1`
    FOREIGN KEY (`pagamento_tipo_id` )
    REFERENCES `liber`.`pagamento_tipos` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_compra_pedidos_usuarios1`
    FOREIGN KEY (`comprador_id` )
    REFERENCES `liber`.`usuarios` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_compra_pedidos_usuarios2`
    FOREIGN KEY (`usuario_cadastrou` )
    REFERENCES `liber`.`usuarios` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_compra_pedidos_usuarios3`
    FOREIGN KEY (`usuario_alterou` )
    REFERENCES `liber`.`usuarios` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_compra_pedidos_empresas1`
    FOREIGN KEY (`empresa_id` )
    REFERENCES `liber`.`empresas` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `liber`.`compra_pedido_itens`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `liber`.`compra_pedido_itens` ;

CREATE  TABLE IF NOT EXISTS `liber`.`compra_pedido_itens` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `compra_pedido_id` INT NOT NULL ,
  `produto_id` INT NOT NULL ,
  `quantidade` FLOAT NOT NULL ,
  `preco_compra` FLOAT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_compra_pedido_itens_compra_pedidos1` (`compra_pedido_id` ASC) ,
  INDEX `fk_compra_pedido_itens_produtos1` (`produto_id` ASC) ,
  CONSTRAINT `fk_compra_pedido_itens_compra_pedidos1`
    FOREIGN KEY (`compra_pedido_id` )
    REFERENCES `liber`.`compra_pedidos` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_compra_pedido_itens_produtos1`
    FOREIGN KEY (`produto_id` )
    REFERENCES `liber`.`produtos` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `liber`.`empresas`
-- -----------------------------------------------------
START TRANSACTION;
USE `liber`;
INSERT INTO `liber`.`empresas` (`id`, `nome`, `cnpj`, `inscricao_estadual`, `telefone`, `fax`, `site`, `endereco_email_principal`, `endereco_email_secundario`, `logradouro`, `numero`, `bairro`, `complemento`, `cidade`, `estado`, `cep`) VALUES (NULL, 'Empresa teste', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'logradouro', '123', '', NULL, 'Cidade', 'MG', '36900000');

COMMIT;

-- -----------------------------------------------------
-- Data for table `liber`.`grupos`
-- -----------------------------------------------------
START TRANSACTION;
USE `liber`;
INSERT INTO `liber`.`grupos` (`id`, `nome`, `empresa_id`) VALUES (1, 'Administradores', 1);

COMMIT;

-- -----------------------------------------------------
-- Data for table `liber`.`usuarios`
-- -----------------------------------------------------
START TRANSACTION;
USE `liber`;
INSERT INTO `liber`.`usuarios` (`id`, `nome`, `login`, `senha`, `grupo_id`, `ativo`, `email`, `tempo_criado`, `ultimo_login`, `ultimo_logout`, `eh_tecnico`, `eh_vendedor`, `empresa_id`) VALUES (1, 'Liber administrador', 'liber', '000a6a780d09a56081176224ad67ee48e15b8cfe', 1, 1, 'liber@gnu.eti.br', '2011-06-28 23:34:00', '', '', 0, 0, 1);

COMMIT;

-- -----------------------------------------------------
-- Data for table `liber`.`cliente_categorias`
-- -----------------------------------------------------
START TRANSACTION;
USE `liber`;
INSERT INTO `liber`.`cliente_categorias` (`id`, `descricao`, `empresa_id`) VALUES (1, 'Padrão', 1);

COMMIT;

-- -----------------------------------------------------
-- Data for table `liber`.`fornecedor_categorias`
-- -----------------------------------------------------
START TRANSACTION;
USE `liber`;
INSERT INTO `liber`.`fornecedor_categorias` (`id`, `descricao`, `empresa_id`) VALUES (1, 'Padrão', 1);

COMMIT;

-- -----------------------------------------------------
-- Data for table `liber`.`conta_planos`
-- -----------------------------------------------------
START TRANSACTION;
USE `liber`;
INSERT INTO `liber`.`conta_planos` (`id`, `nome`, `tipo`, `empresa_id`) VALUES (1, 'Assinaturas', 'R', 1);
INSERT INTO `liber`.`conta_planos` (`id`, `nome`, `tipo`, `empresa_id`) VALUES (2, 'Contas mensais', 'D', 1);
INSERT INTO `liber`.`conta_planos` (`id`, `nome`, `tipo`, `empresa_id`) VALUES (NULL, 'Despesas gerais', 'D', 1);
INSERT INTO `liber`.`conta_planos` (`id`, `nome`, `tipo`, `empresa_id`) VALUES (NULL, 'Imobilizado', 'D', 1);
INSERT INTO `liber`.`conta_planos` (`id`, `nome`, `tipo`, `empresa_id`) VALUES (NULL, 'Impostos e taxas', 'D', 1);
INSERT INTO `liber`.`conta_planos` (`id`, `nome`, `tipo`, `empresa_id`) VALUES (NULL, 'Investimentos', 'D', 1);
INSERT INTO `liber`.`conta_planos` (`id`, `nome`, `tipo`, `empresa_id`) VALUES (NULL, 'Material de escritório', 'D', 1);
INSERT INTO `liber`.`conta_planos` (`id`, `nome`, `tipo`, `empresa_id`) VALUES (NULL, 'Outros', 'E', 1);
INSERT INTO `liber`.`conta_planos` (`id`, `nome`, `tipo`, `empresa_id`) VALUES (NULL, 'Pagamento fornecedor', 'D', 1);
INSERT INTO `liber`.`conta_planos` (`id`, `nome`, `tipo`, `empresa_id`) VALUES (NULL, 'Receita serviço', 'R', 1);
INSERT INTO `liber`.`conta_planos` (`id`, `nome`, `tipo`, `empresa_id`) VALUES (NULL, 'Receita venda', 'R', 1);
INSERT INTO `liber`.`conta_planos` (`id`, `nome`, `tipo`, `empresa_id`) VALUES (NULL, 'Receita venda (cupom fiscal)', 'R', 1);
INSERT INTO `liber`.`conta_planos` (`id`, `nome`, `tipo`, `empresa_id`) VALUES (NULL, 'Receitas gerais', 'R', 1);
INSERT INTO `liber`.`conta_planos` (`id`, `nome`, `tipo`, `empresa_id`) VALUES (NULL, 'Retorno de empréstimos', 'R', 1);
INSERT INTO `liber`.`conta_planos` (`id`, `nome`, `tipo`, `empresa_id`) VALUES (NULL, 'Retorno de investimentos', 'R', 1);
INSERT INTO `liber`.`conta_planos` (`id`, `nome`, `tipo`, `empresa_id`) VALUES (NULL, 'Transferência entre contas', 'E', 1);
INSERT INTO `liber`.`conta_planos` (`id`, `nome`, `tipo`, `empresa_id`) VALUES (NULL, 'Vales e empréstimos', 'D', 1);

COMMIT;

-- -----------------------------------------------------
-- Data for table `liber`.`contas`
-- -----------------------------------------------------
START TRANSACTION;
USE `liber`;
INSERT INTO `liber`.`contas` (`id`, `nome`, `apelido`, `banco`, `agencia`, `conta`, `titular`, `empresa_id`) VALUES (1, 'Caixa interno da empresa', 'Caixa interno', NULL, NULL, NULL, NULL, 1);

COMMIT;

-- -----------------------------------------------------
-- Data for table `liber`.`documento_tipos`
-- -----------------------------------------------------
START TRANSACTION;
USE `liber`;
INSERT INTO `liber`.`documento_tipos` (`id`, `nome`, `empresa_id`) VALUES (NULL, 'Dinheiro', 1);
INSERT INTO `liber`.`documento_tipos` (`id`, `nome`, `empresa_id`) VALUES (NULL, 'Cheque', 1);
INSERT INTO `liber`.`documento_tipos` (`id`, `nome`, `empresa_id`) VALUES (NULL, 'Boleto', 1);
INSERT INTO `liber`.`documento_tipos` (`id`, `nome`, `empresa_id`) VALUES (NULL, 'Cartão de crédito', 1);
INSERT INTO `liber`.`documento_tipos` (`id`, `nome`, `empresa_id`) VALUES (NULL, 'Cartão de débito', 1);
INSERT INTO `liber`.`documento_tipos` (`id`, `nome`, `empresa_id`) VALUES (NULL, 'Duplicata', 1);
INSERT INTO `liber`.`documento_tipos` (`id`, `nome`, `empresa_id`) VALUES (NULL, 'Carnê', 1);
INSERT INTO `liber`.`documento_tipos` (`id`, `nome`, `empresa_id`) VALUES (NULL, 'Transferência eletrônica', 1);
INSERT INTO `liber`.`documento_tipos` (`id`, `nome`, `empresa_id`) VALUES (NULL, 'Sem documento', 1);

COMMIT;

-- -----------------------------------------------------
-- Data for table `liber`.`produto_categorias`
-- -----------------------------------------------------
START TRANSACTION;
USE `liber`;
INSERT INTO `liber`.`produto_categorias` (`id`, `nome`, `empresa_id`) VALUES (1, 'Padrão', 1);

COMMIT;

-- -----------------------------------------------------
-- Data for table `liber`.`sistema_opcoes`
-- -----------------------------------------------------
START TRANSACTION;
USE `liber`;
INSERT INTO `liber`.`sistema_opcoes` (`id`, `login_periodo_tentativas`, `login_maximo_tentativas`, `login_tempo_bloqueio`, `item_conta_planos_venda_pedidos`, `item_conta_planos_ordem_servicos`) VALUES (NULL, 5, 5, 15, 11, 10);

COMMIT;
