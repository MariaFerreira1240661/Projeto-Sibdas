
function obterElemento(id) {
    return document.getElementById(id);
}

function obterValor(id) {
    const elemento = obterElemento(id);
    return elemento ? elemento.value.trim() : "";
}

function mostrarMensagem(idMensagem, texto, cor) {
    const mensagem = obterElemento(idMensagem);

    if (mensagem) {
        mensagem.textContent = texto;
        mensagem.style.color = cor;
        mensagem.style.fontWeight = "700";
        mensagem.style.display = "block";
    }
}

function camposPreenchidos(idsCampos) {
    return idsCampos.every(function (idCampo) {
        return obterValor(idCampo) !== "";
    });
}

function irParaIndexDepoisDeMensagem(idMensagem, texto) {
    mostrarMensagem(idMensagem, texto, "green");

    setTimeout(function () {
        window.location.href = "index.html";
    }, 1200);
}

/* Login */

const formLogin = document.getElementById("form-login");

if (formLogin) {
    formLogin.addEventListener("submit", function (event) {
        event.preventDefault();

        const email = document.getElementById("emailLogin").value.trim();
        const password = document.getElementById("password").value.trim();
        const mensagem = document.getElementById("mensagem-login");

        if (email === "" || password === "") {
            mensagem.textContent = "Preencha o email e a palavra-passe.";
            mensagem.style.color = "#10233f";
        } else if (!email.includes("@") || !email.includes(".")) {
            mensagem.textContent = "Insira um email válido.";
            mensagem.style.color = "#10233f";
        } else {
            mensagem.textContent = "Login efetuado com sucesso.";
            mensagem.style.color = "green";

            setTimeout(function () {
                window.location.href = "../private/index.html";
            }, 800);
        }
    });
}

/* Gráficos da dashboard */

function criarGrafico(idCanvas, configuracao) {
    const canvas = obterElemento(idCanvas);

    if (canvas && typeof Chart !== "undefined") {
        new Chart(canvas, configuracao);
    }
}

criarGrafico("graficoEstados", {
    type: "doughnut",
    data: {
        labels: ["Ativo", "Em manutenção", "Inativo", "Em calibração"],
        datasets: [{
            data: [4, 2, 1, 1],
            backgroundColor: ["#7bbf8b", "#f5b55b", "#d65b5b", "#f28c8c"]
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: "bottom"
            }
        }
    }
});

criarGrafico("graficoCategorias", {
    type: "bar",
    data: {
        labels: ["Monitorização", "Suporte de vida", "Terapia", "Diagnóstico"],
        datasets: [{
            label: "N.º de equipamentos",
            data: [2, 3, 2, 1],
            backgroundColor: "#f28c8c"
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

criarGrafico("graficoLocalizacoes", {
    type: "bar",
    data: {
        labels: ["UCI", "Bloco Operatório", "Medicina Interna", "Urgência"],
        datasets: [{
            label: "N.º de equipamentos",
            data: [3, 2, 2, 1],
            backgroundColor: "#10233f"
        }]
    },
    options: {
        indexAxis: "y",
        responsive: true,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            x: {
                beginAtZero: true
            }
        }
    }
});

/* Filtros das tabelas */

function iniciarFiltroTabela(configuracao) {
    const tabela = obterElemento(configuracao.tabelaId);
    const campoPesquisa = obterElemento(configuracao.pesquisaId);
    const semResultados = obterElemento(configuracao.semResultadosId);

    if (!tabela || !campoPesquisa) {
        return;
    }

    const filtros = configuracao.filtros.map(function (filtro) {
        return {
            elemento: obterElemento(filtro.id),
            atributo: filtro.atributo
        };
    });

    function filtrar() {
        const textoPesquisa = campoPesquisa.value.toLowerCase();
        const linhas = tabela.querySelectorAll("tbody tr");
        let resultadosVisiveis = 0;

        linhas.forEach(function (linha) {
            const textoLinha = linha.textContent.toLowerCase();
            const correspondePesquisa = textoLinha.includes(textoPesquisa);

            const correspondeFiltros = filtros.every(function (filtro) {
                if (!filtro.elemento || filtro.elemento.value === "") {
                    return true;
                }

                return linha.getAttribute("data-" + filtro.atributo) === filtro.elemento.value;
            });

            if (correspondePesquisa && correspondeFiltros) {
                linha.style.display = "";
                resultadosVisiveis++;
            } else {
                linha.style.display = "none";
            }
        });

        if (semResultados) {
            semResultados.style.display = resultadosVisiveis === 0 ? "block" : "none";
        }
    }

    campoPesquisa.addEventListener("input", filtrar);

    filtros.forEach(function (filtro) {
        if (filtro.elemento) {
            filtro.elemento.addEventListener("change", filtrar);
        }
    });
}

iniciarFiltroTabela({
    tabelaId: "tabelaEquipamentos",
    pesquisaId: "pesquisaEquipamento",
    semResultadosId: "semResultados",
    filtros: [
        { id: "filtroEstado", atributo: "estado" },
        { id: "filtroCategoria", atributo: "categoria" },
        { id: "filtroCriticidade", atributo: "criticidade" }
    ]
});

iniciarFiltroTabela({
    tabelaId: "tabelaFornecedores",
    pesquisaId: "pesquisaFornecedor",
    semResultadosId: "semResultadosFornecedores",
    filtros: [
        { id: "filtroTipoFornecedor", atributo: "tipo" },
        { id: "filtroEstadoFornecedor", atributo: "estado" }
    ]
});

iniciarFiltroTabela({
    tabelaId: "tabelaLocalizacoes",
    pesquisaId: "pesquisaLocalizacao",
    semResultadosId: "semResultadosLocalizacoes",
    filtros: [
        { id: "filtroServicoLocalizacao", atributo: "servico" },
        { id: "filtroEstadoLocalizacao", atributo: "estado" }
    ]
});

iniciarFiltroTabela({
    tabelaId: "tabelaDocumentos",
    pesquisaId: "pesquisaDocumento",
    semResultadosId: "semResultadosDocumentos",
    filtros: [
        { id: "filtroTipoDocumento", atributo: "tipo" },
        { id: "filtroEstadoDocumento", atributo: "estado" },
        { id: "filtroEquipamentoDocumento", atributo: "equipamento" }
    ]
});

iniciarFiltroTabela({
    tabelaId: "tabelaContratos",
    pesquisaId: "pesquisaContrato",
    semResultadosId: "semResultadosContratos",
    filtros: [
        { id: "filtroTipoContrato", atributo: "tipo" },
        { id: "filtroEstadoContrato", atributo: "estado" },
        { id: "filtroEquipamentoContrato", atributo: "equipamento" }
    ]
});

/* Formulários simples */

function iniciarFormularioSimples(configuracao) {
    const formulario = obterElemento(configuracao.formId);

    if (!formulario) {
        return;
    }

    formulario.addEventListener("submit", function (event) {
        event.preventDefault();

        if (camposPreenchidos(configuracao.camposObrigatorios)) {
            mostrarMensagem(configuracao.mensagemId, configuracao.mensagemSucesso, "green");
        } else {
            mostrarMensagem(configuracao.mensagemId, "Preencha todos os campos obrigatórios assinalados com *.", "#10233f");
        }
    });
}

const formulariosSimples = [
    {
        formId: "formFornecedor",
        mensagemId: "mensagemFornecedor",
        camposObrigatorios: ["codigoFornecedor", "nomeFornecedor", "nifFornecedor", "tipoFornecedor", "telefoneFornecedor", "emailFornecedor", "estadoFornecedor"],
        mensagemSucesso: "Fornecedor registado com sucesso. "
    },
    {
        formId: "formEditarFornecedor",
        mensagemId: "mensagemEditarFornecedor",
        camposObrigatorios: ["editCodigoFornecedor", "editNomeFornecedor", "editNifFornecedor", "editTipoFornecedor", "editTelefoneFornecedor", "editEmailFornecedor", "editEstadoFornecedor"],
        mensagemSucesso: "Alterações guardadas com sucesso. "
    },
    {
        formId: "formLocalizacao",
        mensagemId: "mensagemLocalizacao",
        camposObrigatorios: ["codigoLocalizacao", "edificioLocalizacao", "pisoLocalizacao", "servicoLocalizacao", "salaLocalizacao", "estadoLocalizacao"],
        mensagemSucesso: "Localização registada com sucesso. "
    },
    {
        formId: "formEditarLocalizacao",
        mensagemId: "mensagemEditarLocalizacao",
        camposObrigatorios: ["editCodigoLocalizacao", "editEdificioLocalizacao", "editPisoLocalizacao", "editServicoLocalizacao", "editSalaLocalizacao", "editEstadoLocalizacao"],
        mensagemSucesso: "Alterações guardadas com sucesso. "
    },
    {
        formId: "formDocumento",
        mensagemId: "mensagemDocumento",
        camposObrigatorios: ["codigoDocumento", "nomeDocumento", "tipoDocumento", "equipamentoDocumento", "dataDocumento", "estadoDocumento", "ficheiroDocumento"],
        mensagemSucesso: "Documento registado com sucesso. "
    },
    {
        formId: "formEditarDocumento",
        mensagemId: "mensagemEditarDocumento",
        camposObrigatorios: ["editCodigoDocumento", "editNomeDocumento", "editTipoDocumento", "editEquipamentoDocumento", "editDataDocumento", "editEstadoDocumento", "editFicheiroDocumento"],
        mensagemSucesso: "Alterações guardadas com sucesso. "
    },
    {
        formId: "formContrato",
        mensagemId: "mensagemContrato",
        camposObrigatorios: ["codigoContrato", "equipamentoContrato", "fornecedorContrato", "tipoContrato", "dataInicioContrato", "dataFimContrato", "estadoContrato"],
        mensagemSucesso: "Contrato registado com sucesso. "
    },
    {
        formId: "formEditarContrato",
        mensagemId: "mensagemEditarContrato",
        camposObrigatorios: ["editCodigoContrato", "editEquipamentoContrato", "editFornecedorContrato", "editTipoContrato", "editDataInicioContrato", "editDataFimContrato", "editEstadoContrato"],
        mensagemSucesso: "Alterações guardadas com sucesso. "
    }
];

formulariosSimples.forEach(iniciarFormularioSimples);

/* Remoções */

function iniciarRemocao(botaoId, mensagemId, textoSucesso) {
    const botao = obterElemento(botaoId);

    if (botao) {
        botao.addEventListener("click", function () {
            irParaIndexDepoisDeMensagem(mensagemId, textoSucesso);
        });
    }
}

iniciarRemocao("confirmarRemocao", "mensagemRemocao", "Equipamento removido/arquivado com sucesso. ");
iniciarRemocao("confirmarRemocaoFornecedor", "mensagemRemocaoFornecedor", "Fornecedor removido/arquivado com sucesso. ");
iniciarRemocao("confirmarRemocaoLocalizacao", "mensagemRemocaoLocalizacao", "Localização removida/arquivada com sucesso. ");
iniciarRemocao("confirmarRemocaoDocumento", "mensagemRemocaoDocumento", "Documento removido/arquivado com sucesso. ");
iniciarRemocao("confirmarRemocaoContrato", "mensagemRemocaoContrato", "Contrato removido/arquivado com sucesso. ");

/* Novo equipamento por etapas */

const etapasNovoEquipamento = [
    {
        tabId: "info-tab",
        percentagem: 20,
        textoProgresso: "Etapa 1 de 5: Informação geral",
        mensagemErro: "Preencha todos os campos obrigatórios da Informação geral.",
        campos: ["codigo", "designacao", "categoria", "marca", "modelo", "serie", "fabricante", "anoFabrico", "estado", "criticidade", "dataAquisicao", "custo"]
    },
    {
        tabId: "localizacao-tab",
        percentagem: 40,
        textoProgresso: "Etapa 2 de 5: Localização",
        mensagemErro: "Preencha todos os campos obrigatórios da Localização.",
        campos: ["localizacao", "edificioEquipamento", "pisoEquipamento", "salaEquipamento", "responsavelLocalizacaoEquipamento", "contactoLocalizacaoEquipamento"]
    },
    {
        tabId: "fornecedor-tab",
        percentagem: 60,
        textoProgresso: "Etapa 3 de 5: Fornecedor",
        mensagemErro: "Preencha todos os campos obrigatórios do Fornecedor.",
        campos: ["fornecedorEquipamento", "tipoFornecedorEquipamento", "contactoFornecedorEquipamento", "telefoneFornecedorEquipamento", "emailFornecedorEquipamento", "nifFornecedorEquipamento"]
    },
    {
        tabId: "documentacao-tab",
        percentagem: 80,
        textoProgresso: "Etapa 4 de 5: Documentação",
        mensagemErro: "Preencha todos os campos obrigatórios da Documentação.",
        campos: ["manualEquipamento", "certificadoEquipamento", "contratoDocumentoEquipamento", "relatorioTecnicoEquipamento"]
    },
    {
        tabId: "garantia-tab",
        percentagem: 100,
        textoProgresso: "Etapa 5 de 5: Contratos",
        mensagemErro: "Preencha todos os campos obrigatórios dos Contratos.",
        campos: ["estadoGarantiaEquipamento", "dataInicioGarantiaEquipamento", "dataFimGarantiaEquipamento", "tipoContratoEquipamento", "periodicidadeManutencaoEquipamento", "valorContratoEquipamento"]
    }
];

function atualizarProgressoEquipamento(etapa) {
    const barra = obterElemento("barraProgressoEquipamento");
    const texto = obterElemento("textoProgressoEquipamento");
    const percentagem = obterElemento("percentagemProgressoEquipamento");

    if (barra && texto && percentagem) {
        barra.style.width = etapa.percentagem + "%";
        barra.setAttribute("aria-valuenow", etapa.percentagem);
        texto.textContent = etapa.textoProgresso;
        percentagem.textContent = etapa.percentagem + "%";
    }
}

function abrirTabEquipamento(tabId) {
    const botaoTab = obterElemento(tabId);
    const etapa = etapasNovoEquipamento.find(function (item) {
        return item.tabId === tabId;
    });

    if (botaoTab && typeof bootstrap !== "undefined") {
        botaoTab.classList.remove("disabled");
        new bootstrap.Tab(botaoTab).show();
    }

    if (etapa) {
        atualizarProgressoEquipamento(etapa);
    }
}

function mostrarMensagemEquipamento(texto, cor) {
    mostrarMensagem("mensagemEquipamento", texto, cor);

    const mensagem = obterElemento("mensagemEquipamento");

    if (mensagem) {
        mensagem.style.marginTop = "20px";
        mensagem.scrollIntoView({
            behavior: "smooth",
            block: "center"
        });
    }
}

function etapaValida(etapa) {
    return camposPreenchidos(etapa.campos);
}

function validarEtapasNovoEquipamento() {
    for (let i = 0; i < etapasNovoEquipamento.length; i++) {
        if (!etapaValida(etapasNovoEquipamento[i])) {
            mostrarMensagemEquipamento(etapasNovoEquipamento[i].mensagemErro, "#10233f");
            abrirTabEquipamento(etapasNovoEquipamento[i].tabId);
            return false;
        }
    }

    return true;
}

const formEquipamento = obterElemento("formEquipamento");

if (formEquipamento) {
    const botoesAvancar = [
        { botaoId: "avancarLocalizacao", etapaAtual: 0, proximaEtapa: 1 },
        { botaoId: "avancarFornecedor", etapaAtual: 1, proximaEtapa: 2 },
        { botaoId: "avancarDocumentacao", etapaAtual: 2, proximaEtapa: 3 },
        { botaoId: "avancarContratos", etapaAtual: 3, proximaEtapa: 4 }
    ];

    botoesAvancar.forEach(function (item) {
        const botao = obterElemento(item.botaoId);

        if (botao) {
            botao.addEventListener("click", function () {
                const etapaAtual = etapasNovoEquipamento[item.etapaAtual];

                if (etapaValida(etapaAtual)) {
                    mostrarMensagem("mensagemEquipamento", "", "#10233f");
                    abrirTabEquipamento(etapasNovoEquipamento[item.proximaEtapa].tabId);
                } else {
                    mostrarMensagemEquipamento(etapaAtual.mensagemErro.replace(".", " antes de avançar."), "#10233f");
                    abrirTabEquipamento(etapaAtual.tabId);
                }
            });
        }
    });

    const botoesVoltar = [
        { botaoId: "voltarInfo", tabId: "info-tab" },
        { botaoId: "voltarLocalizacao", tabId: "localizacao-tab" },
        { botaoId: "voltarFornecedor", tabId: "fornecedor-tab" }
    ];

    botoesVoltar.forEach(function (item) {
        const botao = obterElemento(item.botaoId);

        if (botao) {
            botao.addEventListener("click", function () {
                abrirTabEquipamento(item.tabId);
            });
        }
    });

    const guardarEquipamento = obterElemento("guardarEquipamento");

    if (guardarEquipamento) {
        guardarEquipamento.addEventListener("click", function () {
            if (validarEtapasNovoEquipamento()) {
                abrirTabEquipamento("garantia-tab");
                mostrarMensagemEquipamento("Equipamento registado com sucesso. ", "green");
            }
        });
    }
}

/* Editar equipamento */

const etapasEditarEquipamento = [
    {
        tabId: "edit-info-tab",
        mensagemErro: "Preencha todos os campos obrigatórios da Informação geral.",
        campos: ["editCodigo", "editDesignacao", "editCategoria", "editMarca", "editModelo", "editSerie", "editFabricante", "editAnoFabrico", "editEstado", "editCriticidade", "editDataAquisicao", "editCusto"]
    },
    {
        tabId: "edit-localizacao-tab",
        mensagemErro: "Preencha todos os campos obrigatórios da Localização.",
        campos: ["editLocalizacao", "editEdificioEquipamento", "editPisoEquipamento", "editSalaEquipamento", "editResponsavelLocalizacaoEquipamento", "editContactoLocalizacaoEquipamento"]
    },
    {
        tabId: "edit-fornecedor-tab",
        mensagemErro: "Preencha todos os campos obrigatórios do Fornecedor.",
        campos: ["editFornecedorEquipamento", "editTipoFornecedorEquipamento", "editContactoFornecedorEquipamento", "editTelefoneFornecedorEquipamento", "editEmailFornecedorEquipamento", "editNifFornecedorEquipamento"]
    },
    {
        tabId: "edit-documentacao-tab",
        mensagemErro: "Preencha todos os campos obrigatórios da Documentação.",
        campos: ["editManualEquipamento", "editCertificadoEquipamento", "editContratoDocumentoEquipamento", "editRelatorioTecnicoEquipamento"]
    },
    {
        tabId: "edit-contratos-tab",
        mensagemErro: "Preencha todos os campos obrigatórios dos Contratos.",
        campos: ["editEstadoGarantiaEquipamento", "editDataInicioGarantiaEquipamento", "editDataFimGarantiaEquipamento", "editTipoContratoEquipamento", "editPeriodicidadeManutencaoEquipamento", "editValorContratoEquipamento"]
    }
];

function abrirTabEditarEquipamento(tabId) {
    const botaoTab = obterElemento(tabId);

    if (botaoTab && typeof bootstrap !== "undefined") {
        new bootstrap.Tab(botaoTab).show();
    }
}

const formEditarEquipamento = obterElemento("formEditarEquipamento");

if (formEditarEquipamento) {
    formEditarEquipamento.addEventListener("submit", function (event) {
        event.preventDefault();

        for (let i = 0; i < etapasEditarEquipamento.length; i++) {
            const etapa = etapasEditarEquipamento[i];

            if (!camposPreenchidos(etapa.campos)) {
                mostrarMensagem("mensagemEditarEquipamento", etapa.mensagemErro, "#10233f");
                abrirTabEditarEquipamento(etapa.tabId);
                return;
            }
        }

        mostrarMensagem("mensagemEditarEquipamento", "Alterações guardadas com sucesso. ", "green");

        const mensagem = obterElemento("mensagemEditarEquipamento");

        if (mensagem) {
            mensagem.scrollIntoView({
                behavior: "smooth",
                block: "center"
            });
        }
    });
}

/* Gestão dos conteúdos públicos */

const camposConteudoPublico = [
    "conteudoLogoTexto",
    "conteudoNavInicio",
    "conteudoNavSobre",
    "conteudoNavServicos",
    "conteudoNavContactos",
    "conteudoNavAreaReservada",
    "conteudoTituloInicio",
    "conteudoTextoInicio",
    "conteudoBotaoInicio",
    "conteudoSobreSubtitulo",
    "conteudoSobreTitulo",
    "conteudoSobreNos",
    "conteudoSobreTexto2",
    "conteudoSobreListaTitulo",
    "conteudoSobreListaItem1",
    "conteudoSobreListaItem2",
    "conteudoSobreListaItem3",
    "conteudoSobreListaItem4",
    "conteudoServicosTitulo",
    "conteudoServicos",
    "conteudoServicoEquipamentosTitulo",
    "conteudoServicoEquipamentosTexto",
    "conteudoServicoLocalizacaoTitulo",
    "conteudoServicoLocalizacaoTexto",
    "conteudoServicoFornecedoresTitulo",
    "conteudoServicoFornecedoresTexto",
    "conteudoServicoDocumentacaoTitulo",
    "conteudoServicoDocumentacaoTexto",
    "conteudoServicoContratosTitulo",
    "conteudoServicoContratosTexto",
    "conteudoServicoPesquisaTitulo",
    "conteudoServicoPesquisaTexto",
    "conteudoContactosTitulo",
    "conteudoContactosIntro",
    "conteudoContactosInfoTitulo",
    "conteudoEmail",
    "conteudoTelefone",
    "conteudoLocalizacao",
    "conteudoLabelNome",
    "conteudoLabelEmail",
    "conteudoLabelMensagem",
    "conteudoBotaoContacto",
    "conteudoFooterTexto1",
    "conteudoFooterTexto2"
];

const ligacoesConteudoPublico = [
    ["logoTextoPublico", "conteudoLogoTexto"],
    ["navInicioPublico", "conteudoNavInicio"],
    ["navSobrePublico", "conteudoNavSobre"],
    ["navServicosPublico", "conteudoNavServicos"],
    ["navContactosPublico", "conteudoNavContactos"],
    ["navAreaReservadaPublico", "conteudoNavAreaReservada"],
    ["tituloInicioPublico", "conteudoTituloInicio"],
    ["textoInicioPublico", "conteudoTextoInicio"],
    ["botaoInicioPublico", "conteudoBotaoInicio"],
    ["sobreSubtituloPublico", "conteudoSobreSubtitulo"],
    ["sobreTituloPublico", "conteudoSobreTitulo"],
    ["sobreNosPublico", "conteudoSobreNos"],
    ["sobreTexto2Publico", "conteudoSobreTexto2"],
    ["sobreListaTituloPublico", "conteudoSobreListaTitulo"],
    ["sobreListaItem1Publico", "conteudoSobreListaItem1"],
    ["sobreListaItem2Publico", "conteudoSobreListaItem2"],
    ["sobreListaItem3Publico", "conteudoSobreListaItem3"],
    ["sobreListaItem4Publico", "conteudoSobreListaItem4"],
    ["servicosTituloPublico", "conteudoServicosTitulo"],
    ["servicosPublico", "conteudoServicos"],
    ["servicoEquipamentosTituloPublico", "conteudoServicoEquipamentosTitulo"],
    ["servicoEquipamentosTextoPublico", "conteudoServicoEquipamentosTexto"],
    ["servicoLocalizacaoTituloPublico", "conteudoServicoLocalizacaoTitulo"],
    ["servicoLocalizacaoTextoPublico", "conteudoServicoLocalizacaoTexto"],
    ["servicoFornecedoresTituloPublico", "conteudoServicoFornecedoresTitulo"],
    ["servicoFornecedoresTextoPublico", "conteudoServicoFornecedoresTexto"],
    ["servicoDocumentacaoTituloPublico", "conteudoServicoDocumentacaoTitulo"],
    ["servicoDocumentacaoTextoPublico", "conteudoServicoDocumentacaoTexto"],
    ["servicoContratosTituloPublico", "conteudoServicoContratosTitulo"],
    ["servicoContratosTextoPublico", "conteudoServicoContratosTexto"],
    ["servicoPesquisaTituloPublico", "conteudoServicoPesquisaTitulo"],
    ["servicoPesquisaTextoPublico", "conteudoServicoPesquisaTexto"],
    ["contactosTituloPublico", "conteudoContactosTitulo"],
    ["contactosIntroPublico", "conteudoContactosIntro"],
    ["contactosInfoTituloPublico", "conteudoContactosInfoTitulo"],
    ["emailPublico", "conteudoEmail"],
    ["telefonePublico", "conteudoTelefone"],
    ["localizacaoPublico", "conteudoLocalizacao"],
    ["labelNomePublico", "conteudoLabelNome"],
    ["labelEmailPublico", "conteudoLabelEmail"],
    ["labelMensagemPublico", "conteudoLabelMensagem"],
    ["botaoContactoPublico", "conteudoBotaoContacto"],
    ["footerTexto1Publico", "conteudoFooterTexto1"],
    ["footerTexto2Publico", "conteudoFooterTexto2"]
];

function carregarConteudosNoFormulario() {
    camposConteudoPublico.forEach(function (idCampo) {
        const campo = obterElemento(idCampo);
        const valorGuardado = localStorage.getItem(idCampo);

        if (campo && valorGuardado) {
            campo.value = valorGuardado;
        }
    });
}

function aplicarConteudosNoSitePublico() {
    ligacoesConteudoPublico.forEach(function (ligacao) {
        const elemento = obterElemento(ligacao[0]);
        const valorGuardado = localStorage.getItem(ligacao[1]);

        if (elemento && valorGuardado) {
            elemento.textContent = valorGuardado;
        }
    });
}

const formConteudosPublicos = obterElemento("formConteudosPublicos");
const botaoReporConteudos = obterElemento("reporConteudosPublicos");

if (formConteudosPublicos) {
    carregarConteudosNoFormulario();

    formConteudosPublicos.addEventListener("submit", function (event) {
        event.preventDefault();

        if (!camposPreenchidos(camposConteudoPublico)) {
            mostrarMensagem("mensagemConteudosPublicos", "Preencha todos os campos antes de guardar.", "#10233f");
            return;
        }

        camposConteudoPublico.forEach(function (idCampo) {
            localStorage.setItem(idCampo, obterValor(idCampo));
        });

        mostrarMensagem("mensagemConteudosPublicos", "Conteúdos guardados com sucesso. Abra o site público para visualizar as alterações.", "green");
    });
}

if (botaoReporConteudos) {
    botaoReporConteudos.addEventListener("click", function () {
        camposConteudoPublico.forEach(function (idCampo) {
            localStorage.removeItem(idCampo);
        });

        window.location.reload();
    });
}

aplicarConteudosNoSitePublico();

/* Tooltips Bootstrap */

const elementosTooltip = document.querySelectorAll('[data-bs-toggle="tooltip"]');

elementosTooltip.forEach(function (elemento) {
    if (typeof bootstrap !== "undefined") {
        new bootstrap.Tooltip(elemento);
    }
});