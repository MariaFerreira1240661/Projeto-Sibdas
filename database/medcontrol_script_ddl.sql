-- =========================================================
-- MedControl - Script DDL da Base de Dados
-- Gera a estrutura da base de dados MySQL do projeto.
-- Importar com a base de dados do aluno já selecionada.
-- =========================================================

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `contratos`;
DROP TABLE IF EXISTS `documentos`;
DROP TABLE IF EXISTS `equipamento_fornecedores`;
DROP TABLE IF EXISTS `equipamentos`;
DROP TABLE IF EXISTS `fornecedores`;
DROP TABLE IF EXISTS `localizacoes`;
DROP TABLE IF EXISTS `logs_eventos`;
DROP TABLE IF EXISTS `mensagens_contacto`;
DROP TABLE IF EXISTS `conteudos_publicos`;
DROP TABLE IF EXISTS `utilizadores`;
DROP TABLE IF EXISTS `agents`;
DROP TABLE IF EXISTS `categorias_equipamento`;
DROP TABLE IF EXISTS `criticidades`;
DROP TABLE IF EXISTS `estados_contrato`;
DROP TABLE IF EXISTS `estados_documento`;
DROP TABLE IF EXISTS `estados_equipamento`;
DROP TABLE IF EXISTS `estados_localizacao`;
DROP TABLE IF EXISTS `prioridades_manutencao`;
DROP TABLE IF EXISTS `tipos_contrato`;
DROP TABLE IF EXISTS `tipos_documento`;
DROP TABLE IF EXISTS `tipos_entrada`;
DROP TABLE IF EXISTS `tipos_fornecedor`;
DROP TABLE IF EXISTS `tipos_relacao_fornecedor`;

-- Estrutura da tabela `agents`
CREATE TABLE IF NOT EXISTS `agents` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(150) DEFAULT NULL,
  `email` varbinary(255) DEFAULT NULL,
  `name` varbinary(255) NOT NULL,
  `display_name` varchar(150) DEFAULT NULL,
  `passwrd` varchar(255) NOT NULL,
  `profile` varchar(60) NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Estrutura da tabela `categorias_equipamento`
CREATE TABLE IF NOT EXISTS `categorias_equipamento` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome` (`nome`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Estrutura da tabela `criticidades`
CREATE TABLE IF NOT EXISTS `criticidades` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome` (`nome`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Estrutura da tabela `estados_contrato`
CREATE TABLE IF NOT EXISTS `estados_contrato` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome` (`nome`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Estrutura da tabela `estados_documento`
CREATE TABLE IF NOT EXISTS `estados_documento` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome` (`nome`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Estrutura da tabela `estados_equipamento`
CREATE TABLE IF NOT EXISTS `estados_equipamento` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome` (`nome`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Estrutura da tabela `estados_localizacao`
CREATE TABLE IF NOT EXISTS `estados_localizacao` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome` (`nome`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Estrutura da tabela `prioridades_manutencao`
CREATE TABLE IF NOT EXISTS `prioridades_manutencao` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome` (`nome`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Estrutura da tabela `tipos_contrato`
CREATE TABLE IF NOT EXISTS `tipos_contrato` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome` (`nome`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Estrutura da tabela `tipos_documento`
CREATE TABLE IF NOT EXISTS `tipos_documento` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome` (`nome`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Estrutura da tabela `tipos_entrada`
CREATE TABLE IF NOT EXISTS `tipos_entrada` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome` (`nome`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Estrutura da tabela `tipos_fornecedor`
CREATE TABLE IF NOT EXISTS `tipos_fornecedor` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome` (`nome`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Estrutura da tabela `tipos_relacao_fornecedor`
CREATE TABLE IF NOT EXISTS `tipos_relacao_fornecedor` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome` (`nome`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Estrutura da tabela `utilizadores`
CREATE TABLE IF NOT EXISTS `utilizadores` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `perfil` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'admin',
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `ultimo_login` datetime DEFAULT NULL,
  `criado_em` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `atualizado_em` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Estrutura da tabela `conteudos_publicos`
CREATE TABLE IF NOT EXISTS `conteudos_publicos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `chave` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `valor` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `atualizado_em` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `chave` (`chave`)
) ENGINE=InnoDB AUTO_INCREMENT=1365 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Estrutura da tabela `mensagens_contacto`
CREATE TABLE IF NOT EXISTS `mensagens_contacto` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `mensagem` text NOT NULL,
  `estado` varchar(30) NOT NULL DEFAULT 'Nova',
  `data_envio` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lida_em` datetime DEFAULT NULL,
  `arquivada` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Estrutura da tabela `localizacoes`
CREATE TABLE IF NOT EXISTS `localizacoes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `codigo` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `edificio` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `numero_pisos` int DEFAULT NULL,
  `piso` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `servico` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sala` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `estado_localizacao_id` int NOT NULL,
  `responsavel` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contacto_interno` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `observacoes` text COLLATE utf8mb4_unicode_ci,
  `criado_em` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `atualizado_em` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo` (`codigo`),
  UNIQUE KEY `uk_localizacao_fisica` (`edificio`,`piso`,`servico`,`sala`),
  KEY `fk_localizacoes_estado` (`estado_localizacao_id`),
  CONSTRAINT `fk_localizacoes_estado` FOREIGN KEY (`estado_localizacao_id`) REFERENCES `estados_localizacao` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Estrutura da tabela `fornecedores`
CREATE TABLE IF NOT EXISTS `fornecedores` (
  `id` int NOT NULL AUTO_INCREMENT,
  `codigo` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nome` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nif` varchar(9) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo_fornecedor_id` int DEFAULT NULL,
  `telefone` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `website` varchar(180) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pessoa_contacto` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefone_contacto` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `morada` text COLLATE utf8mb4_unicode_ci,
  `observacoes` text COLLATE utf8mb4_unicode_ci,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `criado_em` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `atualizado_em` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo` (`codigo`),
  UNIQUE KEY `nif` (`nif`),
  KEY `fk_fornecedores_tipo` (`tipo_fornecedor_id`),
  CONSTRAINT `fk_fornecedores_tipo` FOREIGN KEY (`tipo_fornecedor_id`) REFERENCES `tipos_fornecedor` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Estrutura da tabela `equipamentos`
CREATE TABLE IF NOT EXISTS `equipamentos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `codigo` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `designacao` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `categoria_equipamento_id` int NOT NULL,
  `marca` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `modelo` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `numero_serie` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fabricante` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ano_fabrico` smallint NOT NULL,
  `estado_equipamento_id` int NOT NULL,
  `criticidade_id` int NOT NULL,
  `prioridade_manutencao_id` int NOT NULL,
  `utilizacao_clinica` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `data_aquisicao` date NOT NULL,
  `custo` decimal(10,2) NOT NULL,
  `tipo_entrada_id` int NOT NULL,
  `componente_outro_equipamento` tinyint(1) NOT NULL DEFAULT '0',
  `equipamento_principal_id` int DEFAULT NULL,
  `tem_consumiveis` tinyint(1) NOT NULL DEFAULT '0',
  `consumiveis` text COLLATE utf8mb4_unicode_ci,
  `localizacao_id` int NOT NULL,
  `localizacao_piso` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `localizacao_servico` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `localizacao_sala` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `observacoes` text COLLATE utf8mb4_unicode_ci,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `criado_em` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `atualizado_em` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo` (`codigo`),
  UNIQUE KEY `uk_equipamento_serie_fabricante_modelo` (`fabricante`,`modelo`,`numero_serie`),
  KEY `fk_equipamentos_categoria` (`categoria_equipamento_id`),
  KEY `fk_equipamentos_estado` (`estado_equipamento_id`),
  KEY `fk_equipamentos_criticidade` (`criticidade_id`),
  KEY `fk_equipamentos_prioridade` (`prioridade_manutencao_id`),
  KEY `fk_equipamentos_tipo_entrada` (`tipo_entrada_id`),
  KEY `fk_equipamentos_localizacao` (`localizacao_id`),
  KEY `fk_equipamentos_principal` (`equipamento_principal_id`),
  CONSTRAINT `fk_equipamentos_categoria` FOREIGN KEY (`categoria_equipamento_id`) REFERENCES `categorias_equipamento` (`id`),
  CONSTRAINT `fk_equipamentos_criticidade` FOREIGN KEY (`criticidade_id`) REFERENCES `criticidades` (`id`),
  CONSTRAINT `fk_equipamentos_estado` FOREIGN KEY (`estado_equipamento_id`) REFERENCES `estados_equipamento` (`id`),
  CONSTRAINT `fk_equipamentos_localizacao` FOREIGN KEY (`localizacao_id`) REFERENCES `localizacoes` (`id`),
  CONSTRAINT `fk_equipamentos_principal` FOREIGN KEY (`equipamento_principal_id`) REFERENCES `equipamentos` (`id`),
  CONSTRAINT `fk_equipamentos_prioridade` FOREIGN KEY (`prioridade_manutencao_id`) REFERENCES `prioridades_manutencao` (`id`),
  CONSTRAINT `fk_equipamentos_tipo_entrada` FOREIGN KEY (`tipo_entrada_id`) REFERENCES `tipos_entrada` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Estrutura da tabela `documentos`
CREATE TABLE IF NOT EXISTS `documentos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `codigo` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `equipamento_id` int NOT NULL,
  `fornecedor_id` int DEFAULT NULL,
  `tipo_documento_id` int NOT NULL,
  `nome` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `data_documento` date NOT NULL,
  `data_validade` date DEFAULT NULL,
  `estado_documento_id` int NOT NULL,
  `ficheiro` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `responsavel_registo` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `observacoes` text COLLATE utf8mb4_unicode_ci,
  `criado_em` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `atualizado_em` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo` (`codigo`),
  KEY `fk_documentos_equipamento` (`equipamento_id`),
  KEY `fk_documentos_fornecedor` (`fornecedor_id`),
  KEY `fk_documentos_tipo` (`tipo_documento_id`),
  KEY `fk_documentos_estado` (`estado_documento_id`),
  CONSTRAINT `fk_documentos_equipamento` FOREIGN KEY (`equipamento_id`) REFERENCES `equipamentos` (`id`),
  CONSTRAINT `fk_documentos_estado` FOREIGN KEY (`estado_documento_id`) REFERENCES `estados_documento` (`id`),
  CONSTRAINT `fk_documentos_fornecedor` FOREIGN KEY (`fornecedor_id`) REFERENCES `fornecedores` (`id`),
  CONSTRAINT `fk_documentos_tipo` FOREIGN KEY (`tipo_documento_id`) REFERENCES `tipos_documento` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Estrutura da tabela `equipamento_fornecedores`
CREATE TABLE IF NOT EXISTS `equipamento_fornecedores` (
  `id` int NOT NULL AUTO_INCREMENT,
  `equipamento_id` int NOT NULL,
  `fornecedor_id` int NOT NULL,
  `tipo_relacao_fornecedor_id` int NOT NULL,
  `pessoa_contacto` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefone_contacto` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_contacto` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `principal` tinyint(1) NOT NULL DEFAULT '0',
  `observacoes` text COLLATE utf8mb4_unicode_ci,
  `criado_em` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_equipamento_fornecedor_relacao` (`equipamento_id`,`fornecedor_id`,`tipo_relacao_fornecedor_id`),
  KEY `fk_eqfor_fornecedor` (`fornecedor_id`),
  KEY `fk_eqfor_relacao` (`tipo_relacao_fornecedor_id`),
  CONSTRAINT `fk_eqfor_equipamento` FOREIGN KEY (`equipamento_id`) REFERENCES `equipamentos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_eqfor_fornecedor` FOREIGN KEY (`fornecedor_id`) REFERENCES `fornecedores` (`id`),
  CONSTRAINT `fk_eqfor_relacao` FOREIGN KEY (`tipo_relacao_fornecedor_id`) REFERENCES `tipos_relacao_fornecedor` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Estrutura da tabela `contratos`
CREATE TABLE IF NOT EXISTS `contratos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `codigo` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `equipamento_id` int NOT NULL,
  `fornecedor_id` int NOT NULL,
  `tipo_contrato_id` int NOT NULL,
  `data_inicio` date NOT NULL,
  `data_fim` date NOT NULL,
  `periodicidade` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado_contrato_id` int NOT NULL,
  `valor` decimal(10,2) DEFAULT NULL,
  `documento_id` int DEFAULT NULL,
  `observacoes` text COLLATE utf8mb4_unicode_ci,
  `criado_em` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `atualizado_em` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo` (`codigo`),
  KEY `fk_contratos_equipamento` (`equipamento_id`),
  KEY `fk_contratos_fornecedor` (`fornecedor_id`),
  KEY `fk_contratos_tipo` (`tipo_contrato_id`),
  KEY `fk_contratos_estado` (`estado_contrato_id`),
  KEY `fk_contratos_documento` (`documento_id`),
  CONSTRAINT `fk_contratos_documento` FOREIGN KEY (`documento_id`) REFERENCES `documentos` (`id`),
  CONSTRAINT `fk_contratos_equipamento` FOREIGN KEY (`equipamento_id`) REFERENCES `equipamentos` (`id`),
  CONSTRAINT `fk_contratos_estado` FOREIGN KEY (`estado_contrato_id`) REFERENCES `estados_contrato` (`id`),
  CONSTRAINT `fk_contratos_fornecedor` FOREIGN KEY (`fornecedor_id`) REFERENCES `fornecedores` (`id`),
  CONSTRAINT `fk_contratos_tipo` FOREIGN KEY (`tipo_contrato_id`) REFERENCES `tipos_contrato` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Estrutura da tabela `logs_eventos`
CREATE TABLE IF NOT EXISTS `logs_eventos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `utilizador_id` int DEFAULT NULL,
  `tipo_evento` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `entidade` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `entidade_id` int DEFAULT NULL,
  `descricao` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `criado_em` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_logs_utilizador` (`utilizador_id`),
  CONSTRAINT `fk_logs_utilizador` FOREIGN KEY (`utilizador_id`) REFERENCES `utilizadores` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


SET FOREIGN_KEY_CHECKS = 1;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
