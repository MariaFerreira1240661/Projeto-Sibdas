<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedControl</title>

    <!-- favicon -->
    <link rel="shortcut icon" href="../assets/img/medcontrol-logo1.png" type="image/png">
    
    <!-- Bootstrap -->
    <link href="../assets/bootstrap/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="../assets/bootstrap/bootstrap-icons.min.css">
    
    <!-- CSS do projeto -->
    <link rel="stylesheet" href="../assets/css/1240661.css">
</head>
<body>

    <header>
        <div class="logo">
            <a href="#inicio">
                <img src="../assets/img/medcontrol-logo1.png" alt="Logótipo da MedControl">
            </a>
            <h1 id="logoTextoPublico">MedControl</h1>
        </div>

        <nav>
            <a href="#inicio" id="navInicioPublico">Início</a>
            <a href="#sobre-nos" id="navSobrePublico">Sobre Nós</a>
            <a href="#servicos" id="navServicosPublico">Serviços</a>
            <a href="#contactos" id="navContactosPublico">Contactos</a>
            <a href="login.php" id="navAreaReservadaPublico">Área Reservada</a>
        </nav>
    </header>

    <main>

    <section id="inicio">
        <div class="inicio-conteudo">
            <h2 id="tituloInicioPublico">MedControl</h2>
            <p id="textoInicioPublico">
                Sistema web para gestão de inventário hospitalar de equipamentos médicos,
                documentação técnica, fornecedores, garantias e contratos.
            </p>
            <a href="#servicos" class="botao-principal" id="botaoInicioPublico">Conhecer serviços</a>
        </div>
    </section>

    <section id="sobre-nos">
    <div class="sobre-layout">

        <div class="sobre-texto">
            <span class="subtitulo" id="sobreSubtituloPublico">Sobre a MedControl</span>
            <h2 id="sobreTituloPublico">Uma solução criada para simplificar a gestão hospitalar</h2>

            <p id="sobreNosPublico">
                A MedControl é uma aplicação web desenvolvida para apoiar instituições de saúde
                na organização do inventário hospitalar de equipamentos médicos.
            </p>

            <p id="sobreTexto2Publico">
                A plataforma permite centralizar informação sobre equipamentos, fornecedores,
                documentação técnica, garantias e contratos, tornando a consulta mais rápida,
                simples e organizada.
            </p>
        </div>

        <div class="sobre-lista">
            <h3 id="sobreListaTituloPublico">O que torna a MedControl útil?</h3>

            <ul>
                <li id="sobreListaItem1Publico">Reduz a dependência de folhas de cálculo dispersas.</li>
                <li  id="sobreListaItem2Publico">Facilita a localização e consulta de equipamentos.</li>
                <li id="sobreListaItem3Publico">Centraliza documentação técnica e administrativa.</li>
                <li id="sobreListaItem4Publico">Apoia a rastreabilidade e a gestão do ciclo de vida dos equipamentos.</li>
            </ul>
        </div>

    </div>
</section>

    <section id="servicos">
        <h2 id="servicosTituloPublico">Serviços</h2>
        <p class="servicos-intro" id="servicosPublico">
            A MedControl disponibiliza ferramentas para apoiar a organização,
            consulta e controlo do inventário hospitalar.
        </p>
    
        <div class="servicos-container">
    
            <div class="cartao-servico">
                <i class="bi bi-hospital"></i>
                <h3 id="servicoEquipamentosTituloPublico">Gestão de Equipamentos</h3>
                <p id="servicoEquipamentosTextoPublico">Registo, consulta e atualização dos equipamentos médicos existentes no hospital.</p>
            </div>
    
            <div class="cartao-servico">
                <i class="bi bi-geo-alt"></i>
                <h3 id="servicoLocalizacaoTituloPublico" >Gestão de Localizações</h3>
                <p id="servicoLocalizacaoTextoPublico">Organização dos equipamentos por serviço, piso, sala ou unidade hospitalar.</p>
            </div>
    
            <div class="cartao-servico">
                <i class="bi bi-truck"></i>
                <h3 id="servicoFornecedoresTituloPublico">Gestão de Fornecedores</h3>
                <p id="servicoFornecedoresTextoPublico">Registo de fornecedores, fabricantes e entidades responsáveis pela assistência técnica.</p>
            </div>
    
            <div class="cartao-servico">
                <i class="bi bi-file-earmark-text"></i>
                <h3 id="servicoDocumentacaoTituloPublico">Gestão Documental</h3>
                <p id="servicoDocumentacaoTextoPublico">Associação de manuais, certificados, contratos, faturas e relatórios técnicos.</p>
            </div>
    
            <div class="cartao-servico">
                <i class="bi bi-shield-check"></i>
                <h3 id="servicoContratosTituloPublico">Garantias e Contratos</h3>
                <p id="servicoContratosTextoPublico">Consulta de datas de garantia, contratos de manutenção e prazos importantes.</p>
            </div>
    
            <div class="cartao-servico">
                <i class="bi bi-search"></i>
                <h3 id="servicoPesquisaTituloPublico">Pesquisa e Filtragem</h3>
                <p  id="servicoPesquisaTextoPublico">Pesquisa rápida por nome, categoria, estado, localização ou fornecedor.</p>
            </div>
    
        </div>
    </section>

    <section id="contactos">
        <h2 id="contactosTituloPublico">Contactos</h2>
        <p class="contactos-intro"  id="contactosIntroPublico">
            Entre em contacto com a equipa MedControl para saber mais sobre a solução.
        </p>
    
        <div class="contactos-container">
    
            <div class="contactos-info">
                <h3 id="contactosInfoTituloPublico">Informações</h3>
    
                <p><i class="bi bi-envelope" ></i> <span id="emailPublico"> geral@medcontrol.pt </span></p>
                <p><i class="bi bi-telephone" ></i> <span id="telefonePublico"> +351 222 000 000 </span></p>
                <p><i class="bi bi-geo-alt" ></i> <span id="localizacaoPublico"> Porto, Portugal </span></p>
            </div>
    
            <form class="contactos-formulario">
                <label for="nome" id="labelNomePublico">Nome</label>
                <input type="text" id="nome" name="nome" placeholder="O seu nome">
    
                <label for="email" id="labelEmailPublico">Email</label>
                <input type="email" id="email" name="email" placeholder="O seu email">
    
                <label for="mensagem" id="labelMensagemPublico">Mensagem</label>
                <textarea id="mensagem" name="mensagem" rows="5" placeholder="Escreva a sua mensagem"></textarea>
    
                <button type="submit" id="botaoContactoPublico">Enviar mensagem</button>
            </form>
    
        </div>
    </section>

    </main>

    <footer>
        <p id="footerTexto1Publico">&copy; 2025 MedControl. Todos os direitos reservados.</p>
        <p id="footerTexto2Publico">Sistema web de apoio ao inventário hospitalar de equipamentos médicos.</p>
    </footer>

    <script src="../assets/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/1240661.js"></script>

</body>
</html>
