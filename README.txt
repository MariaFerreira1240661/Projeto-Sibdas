README - MedControl
Sistema Web de Apoio à Gestão do Inventário Hospitalar de Equipamentos Médicos

=====================================================================
1. IDENTIFICAÇÃO DO PROJETO
=====================================================================

Nome do projeto: MedControl
Unidade curricular: Sistemas de Informação e Bases de Dados Aplicados à Saúde
Curso: Licenciatura em Engenharia Biomédica
Ano letivo: 2025/2026

Estudante: Cristina Costa
Número de estudante: 1240661

Descrição breve:
A aplicação MedControl é um sistema web desenvolvido para apoiar a gestão do inventário hospitalar de equipamentos médicos. Permite registar, consultar, editar e remover/arquivar equipamentos, localizações, fornecedores, documentação técnica, contratos e garantias. Inclui ainda uma área pública institucional e uma área privada protegida por autenticação.

=====================================================================
2. TECNOLOGIAS UTILIZADAS
=====================================================================

- HTML5
- CSS3
- JavaScript
- Bootstrap
- Bootstrap Icons
- jQuery
- DataTables
- Chart.js
- PHP
- MySQL

=====================================================================
3. ESTRUTURA PRINCIPAL DO PROJETO
=====================================================================

medcontrol/
├── assets/
│   ├── bootstrap/             Recursos Bootstrap e Bootstrap Icons
│   ├── css/                   Ficheiro CSS próprio do projeto
│   ├── datatables/            Biblioteca DataTables
│   ├── img/                   Imagens e logótipos da aplicação
│   ├── jquery/                Biblioteca jQuery
│   ├── js/                    Ficheiro JavaScript próprio e Chart.js
│   └── uploads/documentos/    Documentos PDF associados aos equipamentos
│
├── config/
│   └── config.php             Configurações globais e ligação à base de dados
│
├── public/
│   ├── index.php              Área pública da aplicação
│   ├── login.php              Página de login
│   └── logout.php             Termina a sessão do utilizador
│
├── private/
│   ├── index.php              Dashboard da área privada
│   ├── processa_login.php     Processamento da autenticação
│   ├── equipamentos/          Gestão de equipamentos
│   ├── localizacoes/          Gestão de localizações
│   ├── fornecedores/          Gestão de fornecedores
│   ├── documentacao/          Gestão de documentação
│   ├── contratos/             Gestão de contratos e garantias
│   ├── conteudos/             Gestão dos conteúdos da área pública
│   └── includes/              Ficheiros auxiliares reutilizáveis
│
├── base_dados/                Ficheiros da base de dados
│   ├── medcontrol_base_dados_completa.sql
│   ├── medcontrol_script_ddl.sql
│   ├── medcontrol_inserts.sql
│   └── medcontrol_modelo_relacional.dbml
│
└── README.txt

=====================================================================
4. INSTRUÇÕES DE INSTALAÇÃO E EXECUÇÃO
=====================================================================

1. Colocar a pasta do projeto no servidor/local utilizado para executar PHP.

   Estrutura esperada no browser:
   http://127.0.0.1/sibdas/1240661/medcontrol

2. Importar a base de dados MySQL.

   O ficheiro principal para recriar a base de dados completa é:
   base_dados/medcontrol_base_dados_completa.sql

   Este ficheiro contém a estrutura da base de dados e os dados necessários para testar a aplicação.

3. Confirmar as configurações da base de dados no ficheiro:
   config/config.php

   Neste ficheiro encontram-se definidos o servidor, porta, nome da base de dados, utilizador e palavra-passe de acesso ao MySQL.

4. Aceder à área pública através do browser:
   http://127.0.0.1/sibdas/1240661/medcontrol/public/index.php

5. Aceder à página de login:
   http://127.0.0.1/sibdas/1240661/medcontrol/public/login.php

6. Após autenticação, o utilizador é encaminhado para a área privada:
   http://127.0.0.1/sibdas/1240661/medcontrol/private/index.php

=====================================================================
5. CREDENCIAIS DE ACESSO À ÁREA PRIVADA
=====================================================================

Perfil: Administrador
Email: admin@medcontrol.pt
Palavra-passe: admin123

Perfil: Técnico biomédico
Email: tecnico@medcontrol.pt
Palavra-passe: tecnico123

Perfil: Profissional de saúde
Email: profissional@medcontrol.pt
Palavra-passe: saude123

Perfil: Gestor de logística
Email: logistica@medcontrol.pt
Palavra-passe: logistica123

Nota:
Cada perfil possui permissões diferentes. O administrador tem acesso completo à aplicação. Os restantes perfis têm acesso ajustado às respetivas funções.

=====================================================================
6. FUNCIONALIDADES PRINCIPAIS PARA TESTE
=====================================================================

6.1. Área pública
- Aceder à página pública da MedControl.
- Navegar pelas secções Início, Sobre Nós, Serviços e Contactos.
- Enviar uma mensagem através do formulário de contacto.
- Confirmar, na área privada, que a mensagem fica registada.

6.2. Login e sessão
- Entrar na área privada com um dos utilizadores de teste.
- Confirmar que o utilizador autenticado é apresentado no menu lateral.
- Testar o botão de logout.
- Tentar aceder diretamente a uma página privada sem sessão ativa e verificar o redirecionamento para o login.

6.3. Dashboard
- Verificar os cartões com indicadores gerais.
- Consultar gráficos de equipamentos por estado, categoria e localização.
- Consultar alertas de gestão, equipamentos críticos e resumo operacional.

6.4. Equipamentos
- Listar equipamentos registados.
- Pesquisar e filtrar equipamentos por estado, categoria e criticidade.
- Registar um novo equipamento.
- Visualizar a ficha detalhada de um equipamento.
- Editar dados de um equipamento existente.
- Remover/arquivar um equipamento através da página de confirmação.
- Exportar a listagem em CSV, JSON ou PDF.

6.5. Localizações
- Listar localizações.
- Criar uma nova localização.
- Editar uma localização existente.
- Visualizar detalhes de uma localização.
- Remover/arquivar uma localização.
- Testar pesquisa, filtros e exportação.

6.6. Fornecedores
- Listar fornecedores.
- Criar um novo fornecedor.
- Editar dados de um fornecedor.
- Visualizar detalhes de um fornecedor.
- Remover/arquivar um fornecedor.
- Testar pesquisa, filtros e exportação.

6.7. Documentação
- Consultar documentos associados aos equipamentos.
- Filtrar por tipo de documento, estado ou equipamento.
- Visualizar documentos PDF associados.
- Remover/arquivar registos de documentação.
- Exportar a listagem em CSV, JSON ou PDF.

6.8. Contratos e garantias
- Consultar contratos e garantias associados aos equipamentos.
- Filtrar por tipo de contrato, estado ou equipamento.
- Visualizar detalhes de contratos e garantias.
- Remover/arquivar contratos.
- Exportar a listagem em CSV, JSON ou PDF.

6.9. Gestão da área pública
- Entrar com o perfil de administrador.
- Aceder à gestão dos conteúdos públicos.
- Alterar textos, contactos ou informações da área pública.
- Guardar alterações e confirmar o resultado na página pública.

6.10. Mensagens de contacto
- Entrar com o perfil de administrador.
- Consultar as mensagens recebidas através do formulário público.
- Marcar mensagens como lidas ou arquivadas.

=====================================================================
7. BASE DE DADOS
=====================================================================

A base de dados foi organizada segundo um modelo relacional, com tabelas principais, tabelas de apoio e tabelas relacionais.

Ficheiros incluídos:

- medcontrol_base_dados_completa.sql
  Contém a estrutura completa da base de dados e os dados necessários ao funcionamento da aplicação.

- medcontrol_script_ddl.sql
  Contém apenas os comandos de criação da estrutura da base de dados.

- medcontrol_inserts.sql
  Contém apenas os comandos INSERT com os dados da aplicação.

- medcontrol_modelo_relacional.dbml
  Contém a representação da base de dados em DBML, podendo ser utilizado no dbdiagram.io para gerar o modelo relacional visual.

Principais grupos de tabelas:
- Equipamentos
- Localizações
- Fornecedores
- Documentos
- Contratos
- Utilizadores
- Mensagens de contacto
- Conteúdos públicos
- Logs de eventos
- Tabelas de apoio para estados, categorias, criticidades e tipos

=====================================================================
8. PERFIS E PERMISSÕES
=====================================================================

Administrador:
Acesso completo à aplicação, incluindo gestão de equipamentos, fornecedores, localizações, documentação, contratos, conteúdos da área pública, mensagens de contacto e utilizadores.

Técnico biomédico:
Acesso orientado para o acompanhamento técnico do inventário, incluindo equipamentos, localizações, documentação e contratos.

Profissional de saúde:
Acesso mais orientado para consulta de informação relevante sobre equipamentos, documentação e contratos.

Gestor de logística:
Acesso relacionado com fornecedores, localizações, documentação e contratos, apoiando a gestão logística do inventário.

=====================================================================
9. NOTAS IMPORTANTES
=====================================================================

- A aplicação utiliza sessões PHP para controlar o acesso à área privada.
- As palavras-passe dos utilizadores encontram-se guardadas na base de dados sob a forma de hash.
- A remoção de alguns registos é feita através de desativação/arquivamento, evitando a perda definitiva de informação.
- Os documentos PDF associados aos equipamentos encontram-se na pasta assets/uploads/documentos/.
- Para testar uploads ou alteração de ficheiros, garantir que a pasta de uploads tem permissões de escrita no servidor.
- O ficheiro config/config.php deve ser ajustado caso o projeto seja executado num ambiente diferente.

=====================================================================
10. OBSERVAÇÃO FINAL
=====================================================================

Este projeto foi desenvolvido para simular uma aplicação web de apoio à gestão do inventário hospitalar de equipamentos médicos, integrando uma área pública institucional, uma área privada com autenticação, funcionalidades CRUD, dashboard, filtros, exportação de dados, gestão documental e base de dados relacional em MySQL.