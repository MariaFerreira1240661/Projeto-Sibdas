-- =========================================================
-- MedControl - Script apenas com INSERTS
-- Este ficheiro contém apenas os dados da base de dados.
-- Deve ser importado depois do script DDL/criação das tabelas.
-- =========================================================

INSERT INTO `agents` (`id`, `nome`, `email`, `name`, `display_name`, `passwrd`, `profile`, `last_login`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'Maria Ferreira', _binary 0xb6c0054a95b63767b3dc962da2bc17dcc932b39a2f27423fbeb06ae7f53cb833, _binary 0xb6c0054a95b63767b3dc962da2bc17dcc932b39a2f27423fbeb06ae7f53cb833, 'Maria Ferreira', 'admin123', 'admin', '2026-06-22 18:21:17', '2026-06-22 16:04:04', '2026-06-22 18:30:29', NULL),
	(2, 'Tiago Martins', _binary 0x3e8f39c64f5d6255f33625a17ec008922c182a4e3ce6cdedcc6cce47d0b8d235, _binary 0x3e8f39c64f5d6255f33625a17ec008922c182a4e3ce6cdedcc6cce47d0b8d235, 'Tiago Martins', 'tecnico123', 'tecnico', '2026-06-22 18:30:29', '2026-06-22 16:04:04', '2026-06-22 18:30:29', NULL),
	(3, 'Ana Costa', _binary 0x07c12fd75429d83d06acacdb30cc0ba88bfa121c7bfb9f6783a3cdba6d6e98c2, _binary 0x07c12fd75429d83d06acacdb30cc0ba88bfa121c7bfb9f6783a3cdba6d6e98c2, 'Ana Costa', 'saude123', 'profissional_saude', '2026-06-22 18:23:31', '2026-06-22 16:04:04', '2026-06-22 18:30:29', NULL),
	(4, 'João Pereira', _binary 0x35b34d53fcf1f6cf04fb2e808703126566953491954e67714354f27869806b80, _binary 0x35b34d53fcf1f6cf04fb2e808703126566953491954e67714354f27869806b80, 'João Pereira', 'logistica123', 'gestor_logistica', '2026-06-22 16:58:59', '2026-06-22 16:04:04', '2026-06-22 18:30:29', NULL);

INSERT INTO `categorias_equipamento` (`id`, `nome`) VALUES
	(1, 'Monitorização'),
	(2, 'Suporte de vida'),
	(3, 'Terapia'),
	(4, 'Diagnóstico'),
	(5, 'Outro');

INSERT INTO `conteudos_publicos` (`id`, `chave`, `valor`, `atualizado_em`) VALUES
	(1, 'logo_texto', 'MedControl', '2026-06-24 02:06:07'),
	(2, 'nav_inicio', 'Início', '2026-06-24 02:06:07'),
	(3, 'nav_sobre', 'Sobre Nós', '2026-06-24 02:06:07'),
	(4, 'nav_servicos', 'Serviços', '2026-06-24 02:06:07'),
	(5, 'nav_contactos', 'Contactos', '2026-06-24 02:06:07'),
	(6, 'nav_area_reservada', 'Área Reservada', '2026-06-24 02:06:08'),
	(7, 'inicio_titulo', 'MedControl', '2026-06-24 02:06:08'),
	(8, 'inicio_texto', 'Sistema web para gestão de inventário hospitalar de equipamentos médicos, documentação técnica, fornecedores, garantias e contratos.', '2026-06-24 02:06:08'),
	(9, 'inicio_botao', 'Conhecer serviços', '2026-06-24 02:06:08'),
	(10, 'sobre_subtitulo', 'Sobre a MedControl', '2026-06-24 02:06:08'),
	(11, 'sobre_titulo', 'Uma solução criada para simplificar a gestão hospitalar', '2026-06-24 02:06:08'),
	(12, 'sobre_texto_1', 'A MedControl é uma aplicação web desenvolvida para apoiar instituições de saúde na organização do inventário hospitalar de equipamentos médicos.', '2026-06-24 02:06:08'),
	(13, 'sobre_texto_2', 'A plataforma permite centralizar informação sobre equipamentos, fornecedores, documentação técnica, garantias e contratos, tornando a consulta mais rápida, simples e organizada.', '2026-06-24 02:06:08'),
	(14, 'sobre_lista_titulo', 'O que torna a MedControl útil?', '2026-06-24 02:06:09'),
	(15, 'sobre_lista_item_1', 'Reduz a dependência de folhas de cálculo dispersas.', '2026-06-24 02:06:09'),
	(16, 'sobre_lista_item_2', 'Facilita a localização e consulta de equipamentos.', '2026-06-24 02:06:09'),
	(17, 'sobre_lista_item_3', 'Centraliza documentação técnica e administrativa.', '2026-06-24 02:06:09'),
	(18, 'sobre_lista_item_4', 'Apoia a rastreabilidade e a gestão do ciclo de vida dos equipamentos.', '2026-06-24 02:06:09'),
	(19, 'servicos_titulo', 'Serviços', '2026-06-24 02:06:09'),
	(20, 'servicos_texto', 'A MedControl disponibiliza ferramentas para apoiar a organização, consulta e controlo do inventário hospitalar.', '2026-06-24 02:06:09'),
	(21, 'servico_equipamentos_titulo', 'Gestão de Equipamentos', '2026-06-24 02:06:09'),
	(22, 'servico_equipamentos_texto', 'Registo, consulta e atualização dos equipamentos médicos existentes no hospital.', '2026-06-24 02:06:09'),
	(23, 'servico_localizacao_titulo', 'Gestão de Localizações', '2026-06-24 02:06:10'),
	(24, 'servico_localizacao_texto', 'Organização dos equipamentos por edifício, piso, serviço e sala.', '2026-06-24 02:06:10'),
	(25, 'servico_fornecedores_titulo', 'Gestão de Fornecedores', '2026-06-24 02:06:10'),
	(26, 'servico_fornecedores_texto', 'Registo de fornecedores, fabricantes e entidades responsáveis pela assistência técnica.', '2026-06-24 02:06:10'),
	(27, 'servico_documentacao_titulo', 'Gestão Documental', '2026-06-24 02:06:10'),
	(28, 'servico_documentacao_texto', 'Associação de manuais, certificados, faturas, relatórios técnicos e outros documentos relevantes.', '2026-06-24 02:06:10'),
	(29, 'servico_contratos_titulo', 'Garantias e Contratos', '2026-06-24 02:06:10'),
	(30, 'servico_contratos_texto', 'Consulta de datas de garantia, contratos de manutenção e prazos importantes.', '2026-06-24 02:06:10'),
	(31, 'servico_pesquisa_titulo', 'Pesquisa e Filtragem', '2026-06-24 02:06:10'),
	(32, 'servico_pesquisa_texto', 'Pesquisa rápida por nome, categoria, estado, localização ou fornecedor.', '2026-06-24 02:06:10'),
	(33, 'contactos_titulo', 'Contactos', '2026-06-24 02:06:11'),
	(34, 'contactos_intro', 'Entre em contacto com a equipa MedControl para saber mais sobre a solução.', '2026-06-24 02:06:11'),
	(35, 'contactos_info_titulo', 'Informações', '2026-06-24 02:06:11'),
	(36, 'contactos_email', 'geral@medcontrol.pt', '2026-06-24 02:06:11'),
	(37, 'contactos_telefone', '+351 222 000 000', '2026-06-24 02:06:11'),
	(38, 'contactos_localizacao', 'Porto, Portugal', '2026-06-24 02:06:11'),
	(39, 'contactos_label_nome', 'Nome', '2026-06-24 02:06:11'),
	(40, 'contactos_label_email', 'Email', '2026-06-24 02:06:11'),
	(41, 'contactos_label_mensagem', 'Mensagem', '2026-06-24 02:06:11'),
	(42, 'contactos_botao', 'Enviar mensagem', '2026-06-24 02:06:11'),
	(43, 'footer_texto_1', '© 2025 MedControl. Todos os direitos reservados.', '2026-06-24 02:06:12'),
	(44, 'footer_texto_2', 'Sistema web de apoio ao inventário hospitalar de equipamentos médicos.', '2026-06-24 02:06:12');

INSERT INTO `contratos` (`id`, `codigo`, `equipamento_id`, `fornecedor_id`, `tipo_contrato_id`, `data_inicio`, `data_fim`, `periodicidade`, `estado_contrato_id`, `valor`, `documento_id`, `observacoes`, `criado_em`, `atualizado_em`) VALUES
	(1, 'CON001', 1, 1, 1, '2025-02-09', '2026-12-30', 'Trimestral', 1, 1200.00, 7, NULL, '2026-06-22 00:53:51', '2026-06-22 00:53:51'),
	(2, 'CON002', 2, 2, 1, '2023-10-01', '2026-06-24', 'Mensal', 2, 7500.00, 14, NULL, '2026-06-22 01:02:22', '2026-06-22 01:02:22');

INSERT INTO `criticidades` (`id`, `nome`) VALUES
	(1, 'Alta'),
	(2, 'Média'),
	(3, 'Baixa');

INSERT INTO `documentos` (`id`, `codigo`, `equipamento_id`, `fornecedor_id`, `tipo_documento_id`, `nome`, `data_documento`, `data_validade`, `estado_documento_id`, `ficheiro`, `responsavel_registo`, `observacoes`, `criado_em`, `atualizado_em`) VALUES
	(1, 'DOC001', 1, 1, 1, 'Manual de utilizador', '2023-06-22', '2026-06-30', 1, 'assets/uploads/documentos/manual_utilizador_20260621235126_Documentos.pdf', NULL, NULL, '2026-06-22 00:51:26', '2026-06-22 00:51:26'),
	(2, 'DOC002', 1, 1, 2, 'Manual de serviço', '2023-01-22', '2026-06-30', 1, 'assets/uploads/documentos/manual_servico_20260621235126_Documentos.pdf', NULL, NULL, '2026-06-22 00:51:26', '2026-06-22 00:51:26'),
	(3, 'DOC003', 1, 1, 3, 'certificado de calibração', '2023-02-22', '2026-06-23', 1, 'assets/uploads/documentos/certificado_20260621235126_Documentos.pdf', NULL, NULL, '2026-06-22 00:51:26', '2026-06-22 00:51:26'),
	(4, 'DOC004', 1, 1, 4, 'declaração de conformidade', '2023-06-22', '2026-06-29', 1, 'assets/uploads/documentos/declaracao_conformidade_20260621235126_Documentos.pdf', NULL, NULL, '2026-06-22 00:51:26', '2026-06-22 00:51:26'),
	(5, 'DOC005', 1, 1, 5, 'Relatório técnico', '2022-06-22', '2026-06-17', 1, 'assets/uploads/documentos/relatorio_tecnico_20260621235126_Documentos.pdf', NULL, NULL, '2026-06-22 00:51:26', '2026-06-22 00:51:26'),
	(6, 'DOC006', 1, 1, 6, 'Fatura de aquisição', '2023-05-22', '2026-06-29', 1, 'assets/uploads/documentos/fatura_aquisicao_20260621235126_Documentos.pdf', NULL, NULL, '2026-06-22 00:51:26', '2026-06-22 00:51:26'),
	(7, 'DOC007', 1, 1, 7, 'Garantia', '2024-03-22', NULL, 1, 'assets/uploads/documentos/contrato_documento_20260621235351_Documentos.pdf', NULL, NULL, '2026-06-22 00:53:51', '2026-06-22 00:53:51'),
	(8, 'DOC008', 2, NULL, 1, 'Manual de utilizador', '2023-06-04', '2026-11-25', 1, 'assets/uploads/documentos/manual_utilizador_20260622000125_Documentos.pdf', NULL, NULL, '2026-06-22 01:01:25', '2026-06-22 01:01:25'),
	(9, 'DOC009', 2, 2, 2, 'Manual de serviço', '2026-06-02', '2028-10-24', 1, 'assets/uploads/documentos/manual_servico_20260622000125_Documentos.pdf', NULL, NULL, '2026-06-22 01:01:25', '2026-06-22 01:01:25'),
	(10, 'DOC010', 2, NULL, 3, 'Certificado de calibração', '2023-08-09', '2026-06-30', 1, 'assets/uploads/documentos/certificado_20260622000125_Documentos.pdf', NULL, NULL, '2026-06-22 01:01:25', '2026-06-22 01:01:25'),
	(11, 'DOC011', 2, 2, 4, 'Declaração de conformidade', '2023-05-22', '2029-11-06', 1, 'assets/uploads/documentos/declaracao_conformidade_20260622000125_Documentos.pdf', NULL, NULL, '2026-06-22 01:01:25', '2026-06-22 01:01:25'),
	(12, 'DOC012', 2, NULL, 5, 'Relatório técnico', '2026-06-01', '2029-02-22', 1, 'assets/uploads/documentos/relatorio_tecnico_20260622000125_Documentos.pdf', NULL, NULL, '2026-06-22 01:01:25', '2026-06-22 01:01:25'),
	(13, 'DOC013', 2, 2, 6, 'Fatura de aquisição', '2024-07-18', '2028-11-02', 1, 'assets/uploads/documentos/fatura_aquisicao_20260622000125_Documentos.pdf', NULL, NULL, '2026-06-22 01:01:25', '2026-06-22 01:01:25'),
	(14, 'DOC014', 2, 2, 7, 'Garantia', '2026-06-24', NULL, 1, 'assets/uploads/documentos/contrato_documento_20260622000222_Documentos.pdf', NULL, NULL, '2026-06-22 01:02:22', '2026-06-22 01:02:22');

INSERT INTO `equipamento_fornecedores` (`id`, `equipamento_id`, `fornecedor_id`, `tipo_relacao_fornecedor_id`, `pessoa_contacto`, `telefone_contacto`, `email_contacto`, `principal`, `observacoes`, `criado_em`) VALUES
	(5, 2, 1, 5, 'Carlos Mendes', '934787777', 'carlosmendes@gmail.com', 1, NULL, '2026-06-22 19:25:03'),
	(6, 1, 1, 5, 'Carlos Mendes', '+351 910 100 100', 'carlos.mendes@medtech.pt', 1, NULL, '2026-06-22 20:02:52'),
	(7, 10, 1, 5, 'Carlos Mendes', '+351 222 100 100', 'geral@medtech.pt', 0, 'Fornecedor principal da bomba de infusão.', '2026-06-23 00:45:44'),
	(8, 11, 3, 5, 'Rita Almeida', '+351 222 300 300', 'geral@tecnosaudenorte.pt', 0, 'Fornecedor principal do desfibrilhador.', '2026-06-23 00:45:44'),
	(9, 12, 4, 5, 'Mariana Lopes', '+351 222 400 400', 'info@clinimed.pt', 0, 'Fornecedor responsável pelo eletrocardiógrafo.', '2026-06-23 00:45:44'),
	(10, 13, 5, 5, 'Pedro Costa', '+351 222 500 500', 'apoio@hospitaltech.pt', 0, 'Fornecedor do ecógrafo portátil.', '2026-06-23 00:45:44'),
	(11, 14, 3, 5, 'Rita Almeida', '+351 222 300 300', 'geral@tecnosaudenorte.pt', 0, 'Fornecedor responsável pelo aspirador cirúrgico.', '2026-06-23 00:45:44'),
	(12, 15, 1, 5, 'Carlos Mendes', '+351 222 100 100', 'geral@medtech.pt', 0, 'Fornecedor principal do monitor de sinais vitais.', '2026-06-23 00:45:44'),
	(20, 17, 4, 5, 'Mariana Lopes', '+351 222 400 400', 'info@clinimed.pt', 1, 'Fornecedor principal do oxímetro de pulso.', '2026-06-24 08:30:06'),
	(21, 18, 5, 5, 'Pedro Costa', '+351 222 500 500', 'apoio@hospitaltech.pt', 1, 'Fornecedor principal do ventilador de transporte.', '2026-06-24 08:30:06'),
	(22, 19, 4, 5, 'Mariana Lopes', '+351 222 400 400', 'info@clinimed.pt', 1, 'Fornecedor principal do monitor fetal.', '2026-06-24 08:30:06'),
	(23, 20, 3, 5, 'Rita Almeida', '+351 222 300 300', 'geral@tecnosaudenorte.pt', 1, 'Fornecedor principal da autoclave.', '2026-06-24 08:30:06'),
	(24, 21, 5, 5, 'Pedro Costa', '+351 222 500 500', 'apoio@hospitaltech.pt', 1, 'Fornecedor principal da centrífuga laboratorial.', '2026-06-24 08:30:06'),
	(25, 22, 1, 5, 'Carlos Mendes', '+351 222 100 100', 'geral@medtech.pt', 1, 'Fornecedor principal da bomba de seringa.', '2026-06-24 08:30:06'),
	(26, 23, 5, 5, 'Pedro Costa', '+351 222 500 500', 'apoio@hospitaltech.pt', 1, 'Fornecedor principal da cama hospitalar elétrica.', '2026-06-24 08:30:06'),
	(27, 24, 3, 5, 'Rita Almeida', '+351 222 300 300', 'geral@tecnosaudenorte.pt', 1, 'Fornecedor principal do sistema de aspiração portátil.', '2026-06-24 08:30:06'),
	(28, 25, 3, 5, 'Rita Almeida', '+351 222 300 300', 'geral@tecnosaudenorte.pt', 1, 'Fornecedor principal do eletrobisturi.', '2026-06-24 08:30:06'),
	(29, 26, 4, 5, 'Mariana Lopes', '+351 222 400 400', 'info@clinimed.pt', 1, 'Fornecedor principal do ecógrafo fixo.', '2026-06-24 08:30:06'),
	(30, 27, 4, 5, 'Mariana Lopes', '+351 222 400 400', 'info@clinimed.pt', 1, 'Fornecedor principal do analisador bioquímico.', '2026-06-24 08:30:06'),
	(31, 28, 1, 5, 'Carlos Mendes', '+351 222 100 100', 'geral@medtech.pt', 1, 'Fornecedor principal do monitor de pressão arterial.', '2026-06-24 08:30:06'),
	(32, 29, 1, 5, 'Carlos Mendes', '+351 222 100 100', 'geral@medtech.pt', 1, 'Fornecedor principal do desfibrilhador automático externo.', '2026-06-24 08:30:06'),
	(33, 30, 4, 5, 'Mariana Lopes', '+351 222 400 400', 'info@clinimed.pt', 1, 'Fornecedor principal do negatoscópio.', '2026-06-24 08:30:06'),
	(34, 31, 5, 5, 'Pedro Costa', '+351 222 500 500', 'apoio@hospitaltech.pt', 1, 'Fornecedor principal da balança médica digital.', '2026-06-24 08:30:06'),
	(35, 32, 3, 5, 'Rita Almeida', '+351 222 300 300', 'geral@tecnosaudenorte.pt', 1, 'Fornecedor principal da incubadora neonatal.', '2026-06-24 08:30:06'),
	(36, 33, 5, 5, 'Pedro Costa', '+351 222 500 500', 'apoio@hospitaltech.pt', 1, 'Fornecedor principal do aquecedor de fluidos.', '2026-06-24 08:30:06'),
	(37, 34, 3, 5, 'Rita Almeida', '+351 222 300 300', 'geral@tecnosaudenorte.pt', 1, 'Fornecedor principal do ventilador não invasivo.', '2026-06-24 08:30:06'),
	(38, 35, 4, 5, 'Mariana Lopes', '+351 222 400 400', 'info@clinimed.pt', 1, 'Fornecedor principal do capnógrafo portátil.', '2026-06-24 08:30:06'),
	(39, 36, 5, 5, 'Pedro Costa', '+351 222 500 500', 'apoio@hospitaltech.pt', 1, 'Fornecedor principal do carro de emergência.', '2026-06-24 08:30:06');

INSERT INTO `equipamentos` (`id`, `codigo`, `designacao`, `categoria_equipamento_id`, `marca`, `modelo`, `numero_serie`, `fabricante`, `ano_fabrico`, `estado_equipamento_id`, `criticidade_id`, `prioridade_manutencao_id`, `utilizacao_clinica`, `data_aquisicao`, `custo`, `tipo_entrada_id`, `componente_outro_equipamento`, `equipamento_principal_id`, `tem_consumiveis`, `consumiveis`, `localizacao_id`, `localizacao_piso`, `localizacao_servico`, `localizacao_sala`, `observacoes`, `ativo`, `criado_em`, `atualizado_em`) VALUES
	(1, 'EQ001', 'Monitor multiparamétrico', 1, 'Philips', 'IntelliVue MX450', 'SN-MON-001', 'Philips Healthcare', 2002, 1, 1, 3, 'Monitorização contínua', '2023-02-15', 8500.00, 1, 0, NULL, 1, 'Cabos ECG, sensores SpO2, braçadeiras NIBP', 1, '2', 'UCI', 'Sala 201', NULL, 1, '2026-06-22 00:40:16', '2026-06-22 18:23:19'),
	(2, 'EQ002', 'Ventilador pulmonar', 2, 'Dräger', 'Evita V600', 'SN-VENT-002', 'Dräger Medical', 2021, 1, 1, 1, 'Suporte ventilatório', '2026-06-09', 5000.00, 1, 0, NULL, 1, 'Circuitos respiratórios, filtros, sensores de fluxo', 1, '2', 'UCI', 'Sala 202', NULL, 1, '2026-06-22 00:56:58', '2026-06-22 01:02:22'),
	(10, 'EQ003', 'Bomba de infusão', 3, 'B. Braun', 'Infusomat Space', 'SN-INF-003', 'B. Braun Medical', 2021, 1, 2, 2, 'Administração controlada de medicamentos e fluidos intravenosos.', '2022-04-18', 2300.00, 1, 0, NULL, 1, 'Seringas, linhas de infusão e sistemas de administração intravenosa.', 2, '2', 'Medicina Interna', 'Sala 212', NULL, 1, '2026-06-23 00:45:44', '2026-06-23 00:45:44'),
	(11, 'EQ004', 'Desfibrilhador', 2, 'Zoll', 'R Series', 'SN-DESF-004', 'Zoll Medical', 2020, 1, 1, 1, 'Tratamento de arritmias cardíacas graves através de choque elétrico controlado.', '2021-09-10', 12000.00, 1, 0, NULL, 1, 'Pás de desfibrilhação, cabos ECG, bateria e papel térmico.', 1, '1', 'UCI', 'Sala 103', NULL, 1, '2026-06-23 00:45:44', '2026-06-23 00:45:44'),
	(12, 'EQ005', 'Eletrocardiógrafo', 4, 'GE Healthcare', 'MAC 2000', 'SN-ECG-005', 'GE Healthcare', 2022, 4, 1, 2, 'Realização de exames de eletrocardiograma para avaliação da atividade elétrica cardíaca.', '2023-01-25', 4100.00, 5, 0, NULL, 1, 'Elétrodos descartáveis, papel térmico e cabos de ligação.', 2, '1', 'Cardiologia', 'Sala 118', NULL, 1, '2026-06-23 00:45:44', '2026-06-23 00:45:44'),
	(13, 'EQ006', 'Ecógrafo portátil', 4, 'Mindray', 'M7 Premium', 'SN-ECO-006', 'Mindray', 2021, 1, 1, 1, 'Apoio ao diagnóstico por imagem em contexto clínico e de urgência.', '2022-11-07', 18500.00, 1, 0, NULL, 0, NULL, 3, '0', 'Urgência', 'Sala 004', NULL, 1, '2026-06-23 00:45:44', '2026-06-23 00:45:44'),
	(14, 'EQ007', 'Aspirador cirúrgico', 3, 'Medela', 'Basic 30', 'SN-ASP-007', 'Medela', 2019, 2, 2, 2, 'Aspiração de fluidos durante procedimentos clínicos e cirúrgicos.', '2020-03-12', 1700.00, 1, 0, NULL, 1, 'Filtros, tubos de aspiração e recipientes descartáveis.', 4, '1', 'Bloco Operatório', 'Sala 107', NULL, 1, '2026-06-23 00:45:44', '2026-06-23 00:45:44'),
	(15, 'EQ008', 'Monitor de sinais vitais', 1, 'Edan', 'iM8', 'SN-MON-008', 'Edan Instruments', 2022, 1, 1, 2, 'Monitorização de sinais vitais em contexto de internamento.', '2023-05-19', 5600.00, 2, 0, NULL, 1, 'Sensor SpO2, cabo ECG, manguito NIBP e sensor de temperatura.', 3, '2', 'Medicina Interna', 'Sala 216', NULL, 1, '2026-06-23 00:45:44', '2026-06-23 00:45:44'),
	(17, 'EQ009', 'Oxímetro de pulso', 1, 'Nonin', 'Onyx Vantage 9590', 'SN-OXI-009', 'Nonin Medical', 2022, 1, 2, 2, 'Medição da saturação periférica de oxigénio e frequência cardíaca.', '2023-03-10', 450.00, 1, 0, NULL, 1, 'Pilhas, sensores e capas de proteção.', 3, '1', 'Medicina Interna', 'Sala 214', NULL, 1, '2026-06-24 08:29:42', '2026-06-24 08:29:42'),
	(18, 'EQ010', 'Ventilador de transporte', 2, 'Hamilton', 'T1', 'SN-VENT-010', 'Hamilton Medical', 2021, 1, 1, 1, 'Suporte ventilatório durante transporte intra-hospitalar.', '2022-06-18', 15500.00, 1, 0, NULL, 1, 'Circuitos respiratórios, filtros e sensores de fluxo.', 1, '2', 'UCI', 'Sala 204', NULL, 1, '2026-06-24 08:29:42', '2026-06-24 08:29:42'),
	(19, 'EQ011', 'Monitor fetal', 1, 'GE Healthcare', 'Corometrics 250cx', 'SN-MF-011', 'GE Healthcare', 2020, 1, 2, 2, 'Monitorização fetal em contexto obstétrico.', '2021-11-05', 7200.00, 1, 0, NULL, 1, 'Transdutores, cintas elásticas e papel térmico.', 5, '1', 'Obstetrícia', 'Sala 102', NULL, 1, '2026-06-24 08:29:42', '2026-06-24 08:29:42'),
	(20, 'EQ012', 'Autoclave', 5, 'Tuttnauer', '3870 EA', 'SN-AUTO-012', 'Tuttnauer', 2019, 1, 2, 2, 'Esterilização de material clínico reutilizável.', '2020-04-20', 9800.00, 1, 0, NULL, 1, 'Filtros, indicadores químicos e papel de esterilização.', 4, '1', 'Esterilização', 'Sala 105', NULL, 1, '2026-06-24 08:29:42', '2026-06-24 08:29:42'),
	(21, 'EQ013', 'Centrífuga laboratorial', 4, 'Eppendorf', '5702', 'SN-CENT-013', 'Eppendorf', 2021, 1, 3, 3, 'Preparação e separação de amostras biológicas em laboratório.', '2022-02-14', 3200.00, 1, 0, NULL, 1, 'Tubos, adaptadores e rotores.', 2, '1', 'Laboratório', 'Sala 110', NULL, 1, '2026-06-24 08:29:42', '2026-06-24 08:29:42'),
	(22, 'EQ014', 'Bomba de seringa', 3, 'B. Braun', 'Perfusor Space', 'SN-SER-014', 'B. Braun Medical', 2022, 1, 2, 2, 'Administração controlada de fármacos por seringa.', '2023-01-12', 2100.00, 1, 0, NULL, 1, 'Seringas e linhas de extensão.', 2, '2', 'Medicina Interna', 'Sala 213', NULL, 1, '2026-06-24 08:29:42', '2026-06-24 08:29:42'),
	(23, 'EQ015', 'Cama hospitalar elétrica', 5, 'Linet', 'Eleganza 3', 'SN-CAMA-015', 'Linet', 2020, 1, 3, 3, 'Apoio ao posicionamento e conforto do doente internado.', '2021-05-08', 2800.00, 1, 0, NULL, 0, NULL, 5, '2', 'Internamento', 'Quarto 205', NULL, 1, '2026-06-24 08:29:42', '2026-06-24 08:29:42'),
	(24, 'EQ016', 'Sistema de aspiração portátil', 3, 'Laerdal', 'LSU 4000', 'SN-ASP-016', 'Laerdal Medical', 2021, 2, 2, 2, 'Aspiração de secreções em contexto clínico e de emergência.', '2022-09-22', 1350.00, 1, 0, NULL, 1, 'Tubos, filtros e recipientes descartáveis.', 1, '1', 'Urgência', 'Sala 009', NULL, 1, '2026-06-24 08:29:42', '2026-06-24 08:29:42'),
	(25, 'EQ017', 'Eletrobisturi', 3, 'Erbe', 'VIO 300D', 'SN-ELET-017', 'Erbe Elektromedizin', 2018, 1, 1, 1, 'Corte e coagulação em procedimentos cirúrgicos.', '2019-10-15', 14500.00, 1, 0, NULL, 1, 'Elétrodos, cabos e placas neutras.', 4, '1', 'Bloco Operatório', 'Sala 108', NULL, 1, '2026-06-24 08:29:42', '2026-06-24 08:29:42'),
	(26, 'EQ018', 'Ecógrafo fixo', 4, 'Samsung', 'HS40', 'SN-ECO-018', 'Samsung Medison', 2022, 1, 1, 1, 'Diagnóstico por imagem em consultas e exames clínicos.', '2023-04-02', 24500.00, 1, 0, NULL, 1, 'Sondas, gel ecográfico e capas de proteção.', 3, '1', 'Imagiologia', 'Sala 012', NULL, 1, '2026-06-24 08:29:42', '2026-06-24 08:29:42'),
	(27, 'EQ019', 'Analisador bioquímico', 4, 'Roche', 'Cobas c111', 'SN-ANA-019', 'Roche Diagnostics', 2019, 1, 1, 1, 'Análise bioquímica de amostras clínicas.', '2020-07-21', 18500.00, 1, 0, NULL, 1, 'Reagentes, cuvetes e controlos laboratoriais.', 2, '1', 'Laboratório', 'Sala 112', NULL, 1, '2026-06-24 08:29:42', '2026-06-24 08:29:42'),
	(28, 'EQ020', 'Monitor de pressão arterial', 1, 'Omron', 'HBP-1320', 'SN-PA-020', 'Omron Healthcare', 2022, 1, 3, 3, 'Medição não invasiva da pressão arterial.', '2023-02-09', 650.00, 1, 0, NULL, 1, 'Braçadeiras de diferentes tamanhos.', 3, '2', 'Consulta Externa', 'Gabinete 3', NULL, 1, '2026-06-24 08:29:42', '2026-06-24 08:29:42'),
	(29, 'EQ021', 'Desfibrilhador automático externo', 2, 'Philips', 'HeartStart FRx', 'SN-DEA-021', 'Philips Healthcare', 2021, 1, 1, 1, 'Suporte em situações de emergência cardíaca.', '2022-12-11', 1900.00, 1, 0, NULL, 1, 'Elétrodos, bateria e estojo de transporte.', 1, '0', 'Urgência', 'Entrada Principal', NULL, 1, '2026-06-24 08:29:42', '2026-06-24 08:29:42'),
	(30, 'EQ022', 'Negatoscópio', 4, 'Rimsa', 'Slim LED', 'SN-NEG-022', 'Rimsa', 2018, 3, 3, 3, 'Visualização de exames radiológicos em película.', '2019-03-18', 380.00, 1, 0, NULL, 0, NULL, 2, '1', 'Radiologia', 'Sala 116', 'Equipamento atualmente inativo.', 0, '2026-06-24 08:29:42', '2026-06-24 08:34:55'),
	(31, 'EQ023', 'Balança médica digital', 5, 'Seca', '769', 'SN-BAL-023', 'Seca', 2020, 1, 3, 3, 'Medição do peso corporal de utentes.', '2021-01-27', 520.00, 1, 0, NULL, 0, NULL, 3, '0', 'Consulta Externa', 'Gabinete 1', NULL, 1, '2026-06-24 08:29:42', '2026-06-24 08:29:42'),
	(32, 'EQ024', 'Incubadora neonatal', 2, 'Dräger', 'Isolette 8000', 'SN-INC-024', 'Dräger Medical', 2021, 1, 1, 1, 'Controlo térmico e suporte de recém-nascidos.', '2022-08-30', 21000.00, 1, 0, NULL, 1, 'Filtros, sensores de temperatura e humidificação.', 5, '1', 'Neonatologia', 'Sala 101', NULL, 1, '2026-06-24 08:29:42', '2026-06-24 08:29:42'),
	(33, 'EQ025', 'Aquecedor de fluidos', 3, 'Smiths Medical', 'Level 1', 'SN-AQF-025', 'Smiths Medical', 2019, 4, 2, 2, 'Aquecimento de fluidos intravenosos durante procedimentos clínicos.', '2020-11-06', 3500.00, 1, 0, NULL, 1, 'Tubos e conjuntos descartáveis.', 4, '1', 'Bloco Operatório', 'Sala 109', NULL, 1, '2026-06-24 08:29:42', '2026-06-24 08:29:42'),
	(34, 'EQ026', 'Ventilador não invasivo', 2, 'ResMed', 'Astral 150', 'SN-VNI-026', 'ResMed', 2022, 1, 1, 1, 'Suporte ventilatório não invasivo.', '2023-06-13', 8900.00, 1, 0, NULL, 1, 'Máscaras, filtros e circuitos.', 1, '2', 'UCI', 'Sala 205', NULL, 1, '2026-06-24 08:29:42', '2026-06-24 08:29:42'),
	(35, 'EQ027', 'Capnógrafo portátil', 1, 'Masimo', 'EMMA', 'SN-CAP-027', 'Masimo', 2021, 1, 2, 2, 'Monitorização de dióxido de carbono expirado.', '2022-05-25', 1800.00, 1, 0, NULL, 1, 'Adaptadores de via aérea e sensores.', 1, '1', 'Urgência', 'Sala 010', NULL, 1, '2026-06-24 08:29:42', '2026-06-24 08:29:42'),
	(36, 'EQ028', 'Carro de emergência', 5, 'Favero', 'Emergency Trolley', 'SN-CAR-028', 'Favero Health Projects', 2020, 1, 2, 2, 'Armazenamento e transporte de material de emergência.', '2021-09-14', 2400.00, 1, 0, NULL, 1, 'Selos, gavetas e acessórios de suporte.', 1, '0', 'Urgência', 'Sala de Emergência', NULL, 1, '2026-06-24 08:29:42', '2026-06-24 08:29:42');

INSERT INTO `estados_contrato` (`id`, `nome`) VALUES
	(1, 'Ativo'),
	(2, 'A terminar'),
	(3, 'Expirado'),
	(4, 'Sem garantia registada');

INSERT INTO `estados_documento` (`id`, `nome`) VALUES
	(1, 'Válido'),
	(2, 'Expirado'),
	(3, 'Pendente');

INSERT INTO `estados_equipamento` (`id`, `nome`) VALUES
	(1, 'Ativo'),
	(2, 'Em manutenção'),
	(3, 'Inativo'),
	(4, 'Em calibração');

INSERT INTO `estados_localizacao` (`id`, `nome`) VALUES
	(1, 'Ativa'),
	(2, 'Inativa'),
	(3, 'Em manutenção');

INSERT INTO `fornecedores` (`id`, `codigo`, `nome`, `nif`, `tipo_fornecedor_id`, `telefone`, `email`, `website`, `pessoa_contacto`, `telefone_contacto`, `morada`, `observacoes`, `ativo`, `criado_em`, `atualizado_em`) VALUES
	(1, 'FOR001', 'MedTech Portugal', '501234567', NULL, '+351 222 100 100', 'geral@medtech.pt', 'www.medtech.pt', NULL, NULL, 'Rua da Saúde, 120, 4100-200 Porto', 'Fornecedor geral de equipamentos médicos e soluções hospitalares.', 1, '2026-06-22 00:25:45', '2026-06-22 00:25:45'),
	(2, 'FOR002', 'BioCare Equipamentos', '502345678', NULL, '+351 222 200 200', 'contacto@biocare.pt', 'www.biocare.pt', NULL, NULL, 'Avenida Central, 45, 1000-050 Lisboa', 'Empresa especializada em equipamentos de monitorização e diagnóstico.', 0, '2026-06-22 00:25:45', '2026-06-22 01:03:06'),
	(3, 'FOR003', 'TecnoSaúde Norte', '503456789', NULL, '+351 222 300 300', 'geral@tecnosaudenorte.pt', 'www.tecnosaudenorte.pt', NULL, NULL, 'Rua do Hospital, 88, 4700-310 Braga', 'Fornecedor de apoio técnico, manutenção e assistência a equipamentos clínicos.', 1, '2026-06-22 00:25:45', '2026-06-22 00:25:45'),
	(4, 'FOR004', 'Clinimed Solutions', '504567890', NULL, '+351 222 400 400', 'info@clinimed.pt', 'www.clinimed.pt', NULL, NULL, 'Rua das Ciências Médicas, 15, 3000-120 Coimbra', 'Entidade fornecedora de equipamentos para diagnóstico e cuidados clínicos.', 1, '2026-06-22 00:25:45', '2026-06-22 00:25:45'),
	(5, 'FOR005', 'HospitalTech Services', '505678901', NULL, '+351 222 500 500', 'apoio@hospitaltech.pt', 'www.hospitaltech.pt', NULL, NULL, 'Zona Industrial da Maia, Lote 7, 4470-150 Maia', 'Empresa de fornecimento e suporte técnico para equipamentos hospitalares.', 1, '2026-06-22 00:25:45', '2026-06-22 00:25:45');

INSERT INTO `localizacoes` (`id`, `codigo`, `edificio`, `numero_pisos`, `piso`, `servico`, `sala`, `estado_localizacao_id`, `responsavel`, `contacto_interno`, `observacoes`, `criado_em`, `atualizado_em`) VALUES
	(1, 'LOC001', 'Edifício A', 7, '0', 'Geral', 'Geral', 1, 'Ana Martins', '2101', 'Edifício principal da unidade hospitalar.', '2026-06-22 00:14:19', '2026-06-23 16:07:29'),
	(2, 'LOC002', 'Edifício B', 5, '0', 'Geral', 'Geral', 1, 'João Costa', '2202', 'Edifício com serviços clínicos e gabinetes técnicos.', '2026-06-22 00:14:19', '2026-06-23 17:02:04'),
	(3, 'LOC003', 'Edifício C', 3, '0', 'Geral', 'Geral', 1, 'Maria Fernandes', '2303', 'Zona de apoio a consultas e exames.', '2026-06-22 00:14:19', '2026-06-22 00:14:19'),
	(4, 'LOC004', 'Bloco Técnico', 2, '0', 'Geral', 'Geral', 1, 'Ricardo Silva', '2404', 'Área destinada a manutenção e apoio técnico.', '2026-06-22 00:14:19', '2026-06-22 00:14:19'),
	(5, 'LOC005', 'Bloco Clínico', 3, '0', 'Geral', 'Geral', 1, 'Sofia Almeida', '2505', 'Espaço com áreas assistenciais e salas de tratamento.', '2026-06-22 00:14:19', '2026-06-22 00:14:19'),
	(6, 'LOC006', 'Anexo Hospitalar', 2, '0', 'Geral', 'Geral', 1, 'Marta Ribeiro', '2606', 'Zona complementar ligada ao edifício principal.', '2026-06-22 00:14:19', '2026-06-22 00:14:19'),
	(7, 'LOC007', 'Armazém Central', 1, '0', 'Geral', 'Geral', 1, 'Pedro Santos', '2707', 'Local de armazenamento de equipamentos e consumíveis.', '2026-06-22 00:14:19', '2026-06-22 00:14:19'),
	(8, 'LOC-TMP', 'Por definir', NULL, '0', 'Por definir', 'Por definir', 1, 'Sistema', '0000', 'Localização provisória usada durante o registo por etapas.', '2026-06-22 00:40:16', '2026-06-22 00:40:16');

INSERT INTO `logs_eventos` (`id`, `utilizador_id`, `tipo_evento`, `entidade`, `entidade_id`, `descricao`, `ip`, `criado_em`) VALUES
	(1, 1, 'login_sucesso', 'utilizadores', 1, 'Login efetuado com sucesso.', '::1', '2026-06-23 14:29:10'),
	(2, 1, 'login_sucesso', 'utilizadores', 1, 'Login efetuado com sucesso.', '127.0.0.1', '2026-06-23 16:06:55'),
	(3, 1, 'dados_alterados', 'conteudos_publicos', NULL, 'Conteúdos da área pública atualizados.', '127.0.0.1', '2026-06-23 16:16:21'),
	(4, 1, 'dados_alterados', 'localizacoes', 2, 'Localização editada: LOC002 - Edifício B', '127.0.0.1', '2026-06-23 17:02:04'),
	(5, 1, 'exportacao_dados', 'localizacoes', NULL, 'Exportação de dados do módulo localizacoes em formato PDF.', '127.0.0.1', '2026-06-23 22:17:44'),
	(6, 1, 'exportacao_dados', 'localizacoes', NULL, 'Exportação de dados do módulo localizacoes em formato CSV.', '127.0.0.1', '2026-06-23 22:18:30'),
	(7, 1, 'exportacao_dados', 'localizacoes', NULL, 'Exportação de dados do módulo localizacoes em formato JSON.', '127.0.0.1', '2026-06-23 22:18:47'),
	(8, 1, 'login_falhado', 'utilizadores', NULL, 'Tentativa de login falhada para o email: admin@medcontrol.pt', '127.0.0.1', '2026-06-24 01:13:42'),
	(9, 1, 'dados_alterados', 'conteudos_publicos', NULL, 'Conteúdos da área pública atualizados.', '127.0.0.1', '2026-06-24 02:06:12'),
	(10, 2, 'login_sucesso', 'utilizadores', 2, 'Login efetuado com sucesso.', '127.0.0.1', '2026-06-24 02:45:10'),
	(11, 1, 'login_sucesso', 'utilizadores', 1, 'Login efetuado com sucesso.', '127.0.0.1', '2026-06-24 03:00:00'),
	(12, 4, 'login_sucesso', 'utilizadores', 4, 'Login efetuado com sucesso.', '127.0.0.1', '2026-06-24 07:04:10'),
	(13, 3, 'login_sucesso', 'utilizadores', 3, 'Login efetuado com sucesso.', '127.0.0.1', '2026-06-24 07:05:17'),
	(14, 2, 'login_sucesso', 'utilizadores', 2, 'Login efetuado com sucesso.', '127.0.0.1', '2026-06-24 07:05:44');

INSERT INTO `mensagens_contacto` (`id`, `nome`, `email`, `mensagem`, `estado`, `data_envio`, `lida_em`, `arquivada`) VALUES
	(1, 'Maria Ferreira', 'mariabarros.maria07@gmail.com', 'Olá, pretendo saber mais sobre a MedControl', 'Arquivada', '2026-06-22 14:42:46', '2026-06-22 14:43:02', 1),
	(2, 'Maria Ferreira', 'mariabarros.maria07@gmail.com', 'ola, quero conhecer mais', 'Arquivada', '2026-06-22 23:30:18', '2026-06-22 23:31:22', 1),
	(3, 'Maria Ferreira', 'mariabarros.maria07@gmail.com', 'ola quero saber mais', 'Arquivada', '2026-06-23 01:08:19', '2026-06-23 01:08:36', 1),
	(4, 'Maria Ferreira', 'mariabarros.maria07@gmail.com', 'ola quero saber mais', 'Nova', '2026-06-23 01:31:47', NULL, 0),
	(5, 'Maria Ferreira', 'mariabarros.maria07@gmail.com', 'ola quero saber mais', 'Arquivada', '2026-06-23 01:47:22', '2026-06-23 11:23:25', 1);

INSERT INTO `prioridades_manutencao` (`id`, `nome`) VALUES
	(1, 'Alta'),
	(2, 'Média'),
	(3, 'Baixa'),
	(4, 'Moderada');

INSERT INTO `tipos_contrato` (`id`, `nome`) VALUES
	(1, 'Garantia'),
	(2, 'Contrato de manutenção'),
	(3, 'Assistência técnica'),
	(4, 'Fornecimento'),
	(5, 'Outro');

INSERT INTO `tipos_documento` (`id`, `nome`) VALUES
	(1, 'Manual de utilizador'),
	(2, 'Manual de serviço'),
	(3, 'Certificado de calibração'),
	(4, 'Declaração de conformidade'),
	(5, 'Relatório técnico'),
	(6, 'Fatura ou guia de aquisição'),
	(7, 'Contrato / garantia'),
	(8, 'Outro');

INSERT INTO `tipos_entrada` (`id`, `nome`) VALUES
	(1, 'Aquisição'),
	(2, 'Doação'),
	(3, 'Empréstimo'),
	(4, 'Outro'),
	(5, 'Compra');

INSERT INTO `tipos_fornecedor` (`id`, `nome`) VALUES
	(1, 'Fabricante'),
	(2, 'Distribuidor'),
	(3, 'Manutenção'),
	(4, 'Prestador de serviços'),
	(5, 'Outro');

INSERT INTO `tipos_relacao_fornecedor` (`id`, `nome`) VALUES
	(1, 'Fabricante'),
	(2, 'Distribuidor'),
	(3, 'Manutenção'),
	(4, 'Assistência técnica'),
	(5, 'Fornecedor principal'),
	(6, 'Outro');

INSERT INTO `utilizadores` (`id`, `nome`, `email`, `password_hash`, `perfil`, `ativo`, `ultimo_login`, `criado_em`, `atualizado_em`) VALUES
	(1, 'Maria Ferreira', 'admin@medcontrol.pt', '$2y$12$KFKwhtt/xvZ/tx0.FhroouIjSb8B2INpQnL4ND.4sl7H0mydbkiii', 'admin', 1, '2026-06-24 02:59:59', '2026-06-17 23:22:02', '2026-06-24 07:05:42'),
	(2, 'Tiago Martins', 'tecnico@medcontrol.pt', '$2y$12$9hWurEcA7GjevKMGfH8uweQbc/b9i3PEwhl7nG7K5/1lv83/rM9WG', 'tecnico', 1, '2026-06-24 07:05:43', '2026-06-22 18:58:00', '2026-06-24 07:05:43'),
	(3, 'Ana Costa', 'profissional@medcontrol.pt', '$2y$12$t.KCdBWkrHwMQ6gfPcJ/fOBzuIc4Iq0DX0dHtCXRhZzfz.1Tnpct2', 'profissional_saude', 1, '2026-06-24 07:05:16', '2026-06-22 18:58:00', '2026-06-24 07:05:42'),
	(4, 'João Pereira', 'logistica@medcontrol.pt', '$2y$12$uwTSCHQr8xWRLEFxGFx7AevKwC39W7/W97N8ylZ17gi3ZbDczYz6u', 'gestor_logistica', 1, '2026-06-24 07:04:10', '2026-06-22 18:58:00', '2026-06-24 07:05:42');
