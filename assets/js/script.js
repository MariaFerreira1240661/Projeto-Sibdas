const formLogin = document.getElementById("form-login");

if (formLogin) {
    formLogin.addEventListener("submit", function (event) {
        event.preventDefault();

        const utilizador = document.getElementById("utilizador").value;
        const password = document.getElementById("password").value;
        const mensagem = document.getElementById("mensagem-login");

        if (utilizador === "admin" && password === "medcontrol123") {
            mensagem.textContent = "Login efetuado com sucesso.";
            mensagem.style.color = "green";

            setTimeout(function () {
                window.location.href = "../backend/index.html";
            }, 800);
        } else {
            mensagem.textContent = "Utilizador ou palavra-passe incorretos.";
            mensagem.style.color = "#10233f";
        }
    });
}

const graficoEstados = document.getElementById("graficoEstados");
const graficoCategorias = document.getElementById("graficoCategorias");
const graficoLocalizacoes = document.getElementById("graficoLocalizacoes");

if (graficoEstados) {
    new Chart(graficoEstados, {
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
}

if (graficoCategorias) {
    new Chart(graficoCategorias, {
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
}

if (graficoLocalizacoes) {
    new Chart(graficoLocalizacoes, {
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
}


const pesquisaEquipamento = document.getElementById("pesquisaEquipamento");
const filtroEstado = document.getElementById("filtroEstado");
const filtroCategoria = document.getElementById("filtroCategoria");
const filtroCriticidade = document.getElementById("filtroCriticidade");
const tabelaEquipamentos = document.getElementById("tabelaEquipamentos");
const semResultados = document.getElementById("semResultados");

function filtrarEquipamentos() {
    if (!tabelaEquipamentos) {
        return;
    }

    const textoPesquisa = pesquisaEquipamento.value.toLowerCase();
    const estadoSelecionado = filtroEstado.value;
    const categoriaSelecionada = filtroCategoria.value;
    const criticidadeSelecionada = filtroCriticidade.value;

    const linhas = tabelaEquipamentos.querySelectorAll("tbody tr");
    let resultadosVisiveis = 0;

    linhas.forEach(function (linha) {
        const textoLinha = linha.textContent.toLowerCase();
        const estadoLinha = linha.getAttribute("data-estado");
        const categoriaLinha = linha.getAttribute("data-categoria");
        const criticidadeLinha = linha.getAttribute("data-criticidade");

        const correspondePesquisa = textoLinha.includes(textoPesquisa);
        const correspondeEstado = estadoSelecionado === "" || estadoLinha === estadoSelecionado;
        const correspondeCategoria = categoriaSelecionada === "" || categoriaLinha === categoriaSelecionada;
        const correspondeCriticidade = criticidadeSelecionada === "" || criticidadeLinha === criticidadeSelecionada;

        if (correspondePesquisa && correspondeEstado && correspondeCategoria && correspondeCriticidade) {
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

if (pesquisaEquipamento && filtroEstado && filtroCategoria && filtroCriticidade) {
    pesquisaEquipamento.addEventListener("input", filtrarEquipamentos);
    filtroEstado.addEventListener("change", filtrarEquipamentos);
    filtroCategoria.addEventListener("change", filtrarEquipamentos);
    filtroCriticidade.addEventListener("change", filtrarEquipamentos);
}

const formEquipamento = document.getElementById("formEquipamento");

function abrirTabEquipamento(idBotaoTab) {
    const botaoTab = document.getElementById(idBotaoTab);

    if (botaoTab) {
        botaoTab.classList.remove("disabled");

        const tab = new bootstrap.Tab(botaoTab);
        tab.show();
    }
}

function validarInformacaoGeralEquipamento() {
    const codigo = document.getElementById("codigo").value.trim();
    const designacao = document.getElementById("designacao").value.trim();
    const categoria = document.getElementById("categoria").value;
    const marca = document.getElementById("marca").value.trim();
    const modelo = document.getElementById("modelo").value.trim();
    const serie = document.getElementById("serie").value.trim();
    const fabricante = document.getElementById("fabricante").value.trim();
    const anoFabrico = document.getElementById("anoFabrico").value.trim();
    const estado = document.getElementById("estado").value;
    const criticidade = document.getElementById("criticidade").value;
    const dataAquisicao = document.getElementById("dataAquisicao").value;
    const custo = document.getElementById("custo").value.trim();

    return (
        codigo !== "" &&
        designacao !== "" &&
        categoria !== "" &&
        marca !== "" &&
        modelo !== "" &&
        serie !== "" &&
        fabricante !== "" &&
        anoFabrico !== "" &&
        estado !== "" &&
        criticidade !== "" &&
        dataAquisicao !== "" &&
        custo !== ""
    );
}

function validarLocalizacaoEquipamento() {
    const localizacao = document.getElementById("localizacao").value;
    const edificio = document.getElementById("edificioEquipamento").value.trim();
    const piso = document.getElementById("pisoEquipamento").value.trim();
    const sala = document.getElementById("salaEquipamento").value.trim();
    const responsavel = document.getElementById("responsavelLocalizacaoEquipamento").value.trim();
    const contacto = document.getElementById("contactoLocalizacaoEquipamento").value.trim();

    return (
        localizacao !== "" &&
        edificio !== "" &&
        piso !== "" &&
        sala !== "" &&
        responsavel !== "" &&
        contacto !== ""
    );
}

function validarFornecedorEquipamento() {
    const fornecedor = document.getElementById("fornecedorEquipamento").value;
    const tipoFornecedor = document.getElementById("tipoFornecedorEquipamento").value;
    const contacto = document.getElementById("contactoFornecedorEquipamento").value.trim();
    const telefone = document.getElementById("telefoneFornecedorEquipamento").value.trim();
    const email = document.getElementById("emailFornecedorEquipamento").value.trim();
    const nif = document.getElementById("nifFornecedorEquipamento").value.trim();

    return (
        fornecedor !== "" &&
        tipoFornecedor !== "" &&
        contacto !== "" &&
        telefone !== "" &&
        email !== "" &&
        nif !== ""
    );
}

function validarDocumentacaoEquipamento() {
    const manual = document.getElementById("manualEquipamento").value.trim();
    const certificado = document.getElementById("certificadoEquipamento").value.trim();
    const contrato = document.getElementById("contratoDocumentoEquipamento").value.trim();
    const relatorio = document.getElementById("relatorioTecnicoEquipamento").value.trim();

    return (
        manual !== "" &&
        certificado !== "" &&
        contrato !== "" &&
        relatorio !== ""
    );
}

function validarContratosEquipamento() {
    const estadoGarantia = document.getElementById("estadoGarantiaEquipamento").value;
    const dataInicio = document.getElementById("dataInicioGarantiaEquipamento").value;
    const dataFim = document.getElementById("dataFimGarantiaEquipamento").value;
    const tipoContrato = document.getElementById("tipoContratoEquipamento").value;
    const periodicidade = document.getElementById("periodicidadeManutencaoEquipamento").value;
    const valor = document.getElementById("valorContratoEquipamento").value.trim();

    return (
        estadoGarantia !== "" &&
        dataInicio !== "" &&
        dataFim !== "" &&
        tipoContrato !== "" &&
        periodicidade !== "" &&
        valor !== ""
    );
}

if (formEquipamento) {
    const mensagem = document.getElementById("mensagemEquipamento");

    const avancarLocalizacao = document.getElementById("avancarLocalizacao");
    const avancarFornecedor = document.getElementById("avancarFornecedor");
    const avancarDocumentacao = document.getElementById("avancarDocumentacao");
    const avancarContratos = document.getElementById("avancarContratos");

    const voltarInfo = document.getElementById("voltarInfo");
    const voltarLocalizacao = document.getElementById("voltarLocalizacao");
    const voltarFornecedor = document.getElementById("voltarFornecedor");

    const guardarEquipamento = document.getElementById("guardarEquipamento");

    function mostrarMensagemEquipamento(texto, cor) {
        mensagem.textContent = texto;
        mensagem.style.color = cor;
        mensagem.style.fontWeight = "700";
        mensagem.style.display = "block";
        mensagem.style.marginTop = "20px";

        mensagem.scrollIntoView({
            behavior: "smooth",
            block: "center"
        });
    }

    function guardarNovoEquipamento() {
        if (!validarInformacaoGeralEquipamento()) {
            mostrarMensagemEquipamento("Preencha todos os campos obrigatórios da Informação geral.", "#10233f");
            abrirTabEquipamento("info-tab");
        } else if (!validarLocalizacaoEquipamento()) {
            mostrarMensagemEquipamento("Preencha todos os campos obrigatórios da Localização.", "#10233f");
            abrirTabEquipamento("localizacao-tab");
        } else if (!validarFornecedorEquipamento()) {
            mostrarMensagemEquipamento("Preencha todos os campos obrigatórios do Fornecedor.", "#10233f");
            abrirTabEquipamento("fornecedor-tab");
        } else if (!validarDocumentacaoEquipamento()) {
            mostrarMensagemEquipamento("Preencha todos os campos obrigatórios da Documentação.", "#10233f");
            abrirTabEquipamento("documentacao-tab");
        } else if (!validarContratosEquipamento()) {
            mostrarMensagemEquipamento("Preencha todos os campos obrigatórios dos Contratos.", "#10233f");
            abrirTabEquipamento("garantia-tab");
        } else {
            abrirTabEquipamento("garantia-tab");
            mostrarMensagemEquipamento("Equipamento registado com sucesso. Esta ação será ligada à base de dados numa fase posterior.", "green");
        }
    }

    if (avancarLocalizacao) {
        avancarLocalizacao.addEventListener("click", function () {
            if (validarInformacaoGeralEquipamento()) {
                mensagem.textContent = "";
                abrirTabEquipamento("localizacao-tab");
            } else {
                mostrarMensagemEquipamento("Preencha todos os campos obrigatórios da Informação geral antes de avançar.", "#10233f");
                abrirTabEquipamento("info-tab");
            }
        });
    }

    if (avancarFornecedor) {
        avancarFornecedor.addEventListener("click", function () {
            if (validarLocalizacaoEquipamento()) {
                mensagem.textContent = "";
                abrirTabEquipamento("fornecedor-tab");
            } else {
                mostrarMensagemEquipamento("Preencha todos os campos obrigatórios da Localização antes de avançar.", "#10233f");
                abrirTabEquipamento("localizacao-tab");
            }
        });
    }

    if (avancarDocumentacao) {
        avancarDocumentacao.addEventListener("click", function () {
            if (validarFornecedorEquipamento()) {
                mensagem.textContent = "";
                abrirTabEquipamento("documentacao-tab");
            } else {
                mostrarMensagemEquipamento("Preencha todos os campos obrigatórios do Fornecedor antes de avançar.", "#10233f");
                abrirTabEquipamento("fornecedor-tab");
            }
        });
    }

    if (avancarContratos) {
        avancarContratos.addEventListener("click", function () {
            if (validarDocumentacaoEquipamento()) {
                mensagem.textContent = "";
                abrirTabEquipamento("garantia-tab");
            } else {
                mostrarMensagemEquipamento("Preencha todos os campos obrigatórios da Documentação antes de avançar.", "#10233f");
                abrirTabEquipamento("documentacao-tab");
            }
        });
    }

    if (voltarInfo) {
        voltarInfo.addEventListener("click", function () {
            abrirTabEquipamento("info-tab");
        });
    }

    if (voltarLocalizacao) {
        voltarLocalizacao.addEventListener("click", function () {
            abrirTabEquipamento("localizacao-tab");
        });
    }

    if (voltarFornecedor) {
        voltarFornecedor.addEventListener("click", function () {
            abrirTabEquipamento("fornecedor-tab");
        });
    }

    if (guardarEquipamento) {
        guardarEquipamento.addEventListener("click", guardarNovoEquipamento);
    }
}

const formEditarEquipamento = document.getElementById("formEditarEquipamento");

function abrirTabEditarEquipamento(idBotaoTab) {
    const botaoTab = document.getElementById(idBotaoTab);

    if (botaoTab) {
        const tab = new bootstrap.Tab(botaoTab);
        tab.show();
    }
}

if (formEditarEquipamento) {
    formEditarEquipamento.addEventListener("submit", function (event) {
        event.preventDefault();

        const codigo = document.getElementById("editCodigo").value.trim();
        const designacao = document.getElementById("editDesignacao").value.trim();
        const categoria = document.getElementById("editCategoria").value;
        const marca = document.getElementById("editMarca").value.trim();
        const modelo = document.getElementById("editModelo").value.trim();
        const serie = document.getElementById("editSerie").value.trim();
        const fabricante = document.getElementById("editFabricante").value.trim();
        const anoFabrico = document.getElementById("editAnoFabrico").value.trim();
        const estado = document.getElementById("editEstado").value;
        const criticidade = document.getElementById("editCriticidade").value;
        const dataAquisicao = document.getElementById("editDataAquisicao").value;
        const custo = document.getElementById("editCusto").value.trim();

        const localizacao = document.getElementById("editLocalizacao").value;
        const edificio = document.getElementById("editEdificioEquipamento").value.trim();
        const piso = document.getElementById("editPisoEquipamento").value.trim();
        const sala = document.getElementById("editSalaEquipamento").value.trim();
        const responsavelLocalizacao = document.getElementById("editResponsavelLocalizacaoEquipamento").value.trim();
        const contactoLocalizacao = document.getElementById("editContactoLocalizacaoEquipamento").value.trim();

        const fornecedor = document.getElementById("editFornecedorEquipamento").value;
        const tipoFornecedor = document.getElementById("editTipoFornecedorEquipamento").value;
        const contactoFornecedor = document.getElementById("editContactoFornecedorEquipamento").value.trim();
        const telefoneFornecedor = document.getElementById("editTelefoneFornecedorEquipamento").value.trim();
        const emailFornecedor = document.getElementById("editEmailFornecedorEquipamento").value.trim();
        const nifFornecedor = document.getElementById("editNifFornecedorEquipamento").value.trim();

        const manual = document.getElementById("editManualEquipamento").value.trim();
        const certificado = document.getElementById("editCertificadoEquipamento").value.trim();
        const documentoContrato = document.getElementById("editContratoDocumentoEquipamento").value.trim();
        const relatorio = document.getElementById("editRelatorioTecnicoEquipamento").value.trim();

        const estadoGarantia = document.getElementById("editEstadoGarantiaEquipamento").value;
        const dataInicioGarantia = document.getElementById("editDataInicioGarantiaEquipamento").value;
        const dataFimGarantia = document.getElementById("editDataFimGarantiaEquipamento").value;
        const tipoContrato = document.getElementById("editTipoContratoEquipamento").value;
        const periodicidade = document.getElementById("editPeriodicidadeManutencaoEquipamento").value;
        const valorContrato = document.getElementById("editValorContratoEquipamento").value.trim();

        const mensagem = document.getElementById("mensagemEditarEquipamento");

        if (
            codigo === "" ||
            designacao === "" ||
            categoria === "" ||
            marca === "" ||
            modelo === "" ||
            serie === "" ||
            fabricante === "" ||
            anoFabrico === "" ||
            estado === "" ||
            criticidade === "" ||
            dataAquisicao === "" ||
            custo === ""
        ) {
            mensagem.textContent = "Preencha todos os campos obrigatórios da Informação geral.";
            mensagem.style.color = "#10233f";
            abrirTabEditarEquipamento("edit-info-tab");
        } else if (
            localizacao === "" ||
            edificio === "" ||
            piso === "" ||
            sala === "" ||
            responsavelLocalizacao === "" ||
            contactoLocalizacao === ""
        ) {
            mensagem.textContent = "Preencha todos os campos obrigatórios da Localização.";
            mensagem.style.color = "#10233f";
            abrirTabEditarEquipamento("edit-localizacao-tab");
        } else if (
            fornecedor === "" ||
            tipoFornecedor === "" ||
            contactoFornecedor === "" ||
            telefoneFornecedor === "" ||
            emailFornecedor === "" ||
            nifFornecedor === ""
        ) {
            mensagem.textContent = "Preencha todos os campos obrigatórios do Fornecedor.";
            mensagem.style.color = "#10233f";
            abrirTabEditarEquipamento("edit-fornecedor-tab");
        } else if (
            manual === "" ||
            certificado === "" ||
            documentoContrato === "" ||
            relatorio === ""
        ) {
            mensagem.textContent = "Preencha todos os campos obrigatórios da Documentação.";
            mensagem.style.color = "#10233f";
            abrirTabEditarEquipamento("edit-documentacao-tab");
        } else if (
            estadoGarantia === "" ||
            dataInicioGarantia === "" ||
            dataFimGarantia === "" ||
            tipoContrato === "" ||
            periodicidade === "" ||
            valorContrato === ""
        ) {
            mensagem.textContent = "Preencha todos os campos obrigatórios dos Contratos.";
            mensagem.style.color = "#10233f";
            abrirTabEditarEquipamento("edit-contratos-tab");
        } else {
            mensagem.textContent = "Equipamento registado com sucesso. Esta ação será ligada à base de dados numa fase posterior.";
            mensagem.style.color = "green";
            mensagem.style.fontWeight = "700";
            mensagem.style.display = "block";

            mensagem.scrollIntoView({
                behavior: "smooth",
                block: "center"
            });
        }
    });
}

const confirmarRemocao = document.getElementById("confirmarRemocao");

if (confirmarRemocao) {
    confirmarRemocao.addEventListener("click", function () {
        const mensagem = document.getElementById("mensagemRemocao");

        mensagem.textContent = "Equipamento removido/arquivado com sucesso. Esta ação será ligada à base de dados numa fase posterior.";
        mensagem.style.color = "green";

        setTimeout(function () {
            window.location.href = "index.html";
        }, 1200);
    });
}

const pesquisaFornecedor = document.getElementById("pesquisaFornecedor");
const filtroTipoFornecedor = document.getElementById("filtroTipoFornecedor");
const filtroEstadoFornecedor = document.getElementById("filtroEstadoFornecedor");
const tabelaFornecedores = document.getElementById("tabelaFornecedores");
const semResultadosFornecedores = document.getElementById("semResultadosFornecedores");

function filtrarFornecedores() {
    if (!tabelaFornecedores) {
        return;
    }

    const textoPesquisa = pesquisaFornecedor.value.toLowerCase();
    const tipoSelecionado = filtroTipoFornecedor.value;
    const estadoSelecionado = filtroEstadoFornecedor.value;

    const linhas = tabelaFornecedores.querySelectorAll("tbody tr");
    let resultadosVisiveis = 0;

    linhas.forEach(function (linha) {
        const textoLinha = linha.textContent.toLowerCase();
        const tipoLinha = linha.getAttribute("data-tipo");
        const estadoLinha = linha.getAttribute("data-estado");

        const correspondePesquisa = textoLinha.includes(textoPesquisa);
        const correspondeTipo = tipoSelecionado === "" || tipoLinha === tipoSelecionado;
        const correspondeEstado = estadoSelecionado === "" || estadoLinha === estadoSelecionado;

        if (correspondePesquisa && correspondeTipo && correspondeEstado) {
            linha.style.display = "";
            resultadosVisiveis++;
        } else {
            linha.style.display = "none";
        }
    });

    if (semResultadosFornecedores) {
        semResultadosFornecedores.style.display = resultadosVisiveis === 0 ? "block" : "none";
    }
}

if (pesquisaFornecedor && filtroTipoFornecedor && filtroEstadoFornecedor) {
    pesquisaFornecedor.addEventListener("input", filtrarFornecedores);
    filtroTipoFornecedor.addEventListener("change", filtrarFornecedores);
    filtroEstadoFornecedor.addEventListener("change", filtrarFornecedores);
}

const formFornecedor = document.getElementById("formFornecedor");

if (formFornecedor) {
    formFornecedor.addEventListener("submit", function (event) {
        event.preventDefault();

        const codigo = document.getElementById("codigoFornecedor").value.trim();
        const nome = document.getElementById("nomeFornecedor").value.trim();
        const nif = document.getElementById("nifFornecedor").value.trim();
        const tipo = document.getElementById("tipoFornecedor").value;
        const telefone = document.getElementById("telefoneFornecedor").value.trim();
        const email = document.getElementById("emailFornecedor").value.trim();
        const estado = document.getElementById("estadoFornecedor").value;
        const mensagem = document.getElementById("mensagemFornecedor");

        if (
            codigo === "" ||
            nome === "" ||
            nif === "" ||
            tipo === "" ||
            telefone === "" ||
            email === "" ||
            estado === ""
        ) {
            mensagem.textContent = "Preencha todos os campos obrigatórios assinalados com *.";
            mensagem.style.color = "#10233f";
        } else {
            mensagem.textContent = "Fornecedor registado com sucesso. Esta ação será ligada à base de dados numa fase posterior.";
            mensagem.style.color = "green";
        }
    });
}

const formEditarFornecedor = document.getElementById("formEditarFornecedor");

if (formEditarFornecedor) {
    formEditarFornecedor.addEventListener("submit", function (event) {
        event.preventDefault();

        const codigo = document.getElementById("editCodigoFornecedor").value.trim();
        const nome = document.getElementById("editNomeFornecedor").value.trim();
        const nif = document.getElementById("editNifFornecedor").value.trim();
        const tipo = document.getElementById("editTipoFornecedor").value;
        const telefone = document.getElementById("editTelefoneFornecedor").value.trim();
        const email = document.getElementById("editEmailFornecedor").value.trim();
        const estado = document.getElementById("editEstadoFornecedor").value;
        const mensagem = document.getElementById("mensagemEditarFornecedor");

        if (
            codigo === "" ||
            nome === "" ||
            nif === "" ||
            tipo === "" ||
            telefone === "" ||
            email === "" ||
            estado === ""
        ) {
            mensagem.textContent = "Preencha todos os campos obrigatórios assinalados com *.";
            mensagem.style.color = "#10233f";
        } else {
            mensagem.textContent = "Alterações guardadas com sucesso. Esta ação será ligada à base de dados numa fase posterior.";
            mensagem.style.color = "green";
        }
    });
}

const confirmarRemocaoFornecedor = document.getElementById("confirmarRemocaoFornecedor");

if (confirmarRemocaoFornecedor) {
    confirmarRemocaoFornecedor.addEventListener("click", function () {
        const mensagem = document.getElementById("mensagemRemocaoFornecedor");

        mensagem.textContent = "Fornecedor removido/arquivado com sucesso. Esta ação será ligada à base de dados numa fase posterior.";
        mensagem.style.color = "green";

        setTimeout(function () {
            window.location.href = "index.html";
        }, 1200);
    });
}


const pesquisaLocalizacao = document.getElementById("pesquisaLocalizacao");
const filtroServicoLocalizacao = document.getElementById("filtroServicoLocalizacao");
const filtroEstadoLocalizacao = document.getElementById("filtroEstadoLocalizacao");
const tabelaLocalizacoes = document.getElementById("tabelaLocalizacoes");
const semResultadosLocalizacoes = document.getElementById("semResultadosLocalizacoes");

function filtrarLocalizacoes() {
    if (!tabelaLocalizacoes) {
        return;
    }

    const textoPesquisa = pesquisaLocalizacao.value.toLowerCase();
    const servicoSelecionado = filtroServicoLocalizacao.value;
    const estadoSelecionado = filtroEstadoLocalizacao.value;

    const linhas = tabelaLocalizacoes.querySelectorAll("tbody tr");
    let resultadosVisiveis = 0;

    linhas.forEach(function (linha) {
        const textoLinha = linha.textContent.toLowerCase();
        const servicoLinha = linha.getAttribute("data-servico");
        const estadoLinha = linha.getAttribute("data-estado");

        const correspondePesquisa = textoLinha.includes(textoPesquisa);
        const correspondeServico = servicoSelecionado === "" || servicoLinha === servicoSelecionado;
        const correspondeEstado = estadoSelecionado === "" || estadoLinha === estadoSelecionado;

        if (correspondePesquisa && correspondeServico && correspondeEstado) {
            linha.style.display = "";
            resultadosVisiveis++;
        } else {
            linha.style.display = "none";
        }
    });

    if (semResultadosLocalizacoes) {
        semResultadosLocalizacoes.style.display = resultadosVisiveis === 0 ? "block" : "none";
    }
}

if (pesquisaLocalizacao && filtroServicoLocalizacao && filtroEstadoLocalizacao) {
    pesquisaLocalizacao.addEventListener("input", filtrarLocalizacoes);
    filtroServicoLocalizacao.addEventListener("change", filtrarLocalizacoes);
    filtroEstadoLocalizacao.addEventListener("change", filtrarLocalizacoes);
}


const formLocalizacao = document.getElementById("formLocalizacao");

if (formLocalizacao) {
    formLocalizacao.addEventListener("submit", function (event) {
        event.preventDefault();

        const codigo = document.getElementById("codigoLocalizacao").value.trim();
        const edificio = document.getElementById("edificioLocalizacao").value.trim();
        const piso = document.getElementById("pisoLocalizacao").value.trim();
        const servico = document.getElementById("servicoLocalizacao").value;
        const sala = document.getElementById("salaLocalizacao").value.trim();
        const estado = document.getElementById("estadoLocalizacao").value;
        const mensagem = document.getElementById("mensagemLocalizacao");

        if (
            codigo === "" ||
            edificio === "" ||
            piso === "" ||
            servico === "" ||
            sala === "" ||
            estado === ""
        ) {
            mensagem.textContent = "Preencha todos os campos obrigatórios assinalados com *.";
            mensagem.style.color = "#10233f";
        } else {
            mensagem.textContent = "Localização registada com sucesso. Esta ação será ligada à base de dados numa fase posterior.";
            mensagem.style.color = "green";
        }
    });
}


const formEditarLocalizacao = document.getElementById("formEditarLocalizacao");

if (formEditarLocalizacao) {
    formEditarLocalizacao.addEventListener("submit", function (event) {
        event.preventDefault();

        const codigo = document.getElementById("editCodigoLocalizacao").value.trim();
        const edificio = document.getElementById("editEdificioLocalizacao").value.trim();
        const piso = document.getElementById("editPisoLocalizacao").value.trim();
        const servico = document.getElementById("editServicoLocalizacao").value;
        const sala = document.getElementById("editSalaLocalizacao").value.trim();
        const estado = document.getElementById("editEstadoLocalizacao").value;
        const mensagem = document.getElementById("mensagemEditarLocalizacao");

        if (
            codigo === "" ||
            edificio === "" ||
            piso === "" ||
            servico === "" ||
            sala === "" ||
            estado === ""
        ) {
            mensagem.textContent = "Preencha todos os campos obrigatórios assinalados com *.";
            mensagem.style.color = "#10233f";
        } else {
            mensagem.textContent = "Alterações guardadas com sucesso. Esta ação será ligada à base de dados numa fase posterior.";
            mensagem.style.color = "green";
        }
    });
}


const confirmarRemocaoLocalizacao = document.getElementById("confirmarRemocaoLocalizacao");

if (confirmarRemocaoLocalizacao) {
    confirmarRemocaoLocalizacao.addEventListener("click", function () {
        const mensagem = document.getElementById("mensagemRemocaoLocalizacao");

        mensagem.textContent = "Localização removida/arquivada com sucesso. Esta ação será ligada à base de dados numa fase posterior.";
        mensagem.style.color = "green";

        setTimeout(function () {
            window.location.href = "index.html";
        }, 1200);
    });
}

const pesquisaDocumento = document.getElementById("pesquisaDocumento");
const filtroTipoDocumento = document.getElementById("filtroTipoDocumento");
const filtroEstadoDocumento = document.getElementById("filtroEstadoDocumento");
const filtroEquipamentoDocumento = document.getElementById("filtroEquipamentoDocumento");
const tabelaDocumentos = document.getElementById("tabelaDocumentos");
const semResultadosDocumentos = document.getElementById("semResultadosDocumentos");

function filtrarDocumentos() {
    if (!tabelaDocumentos) {
        return;
    }

    const textoPesquisa = pesquisaDocumento.value.toLowerCase();
    const tipoSelecionado = filtroTipoDocumento.value;
    const estadoSelecionado = filtroEstadoDocumento.value;
    const equipamentoSelecionado = filtroEquipamentoDocumento.value;

    const linhas = tabelaDocumentos.querySelectorAll("tbody tr");
    let resultadosVisiveis = 0;

    linhas.forEach(function (linha) {
        const textoLinha = linha.textContent.toLowerCase();
        const tipoLinha = linha.getAttribute("data-tipo");
        const estadoLinha = linha.getAttribute("data-estado");
        const equipamentoLinha = linha.getAttribute("data-equipamento");

        const correspondePesquisa = textoLinha.includes(textoPesquisa);
        const correspondeTipo = tipoSelecionado === "" || tipoLinha === tipoSelecionado;
        const correspondeEstado = estadoSelecionado === "" || estadoLinha === estadoSelecionado;
        const correspondeEquipamento = equipamentoSelecionado === "" || equipamentoLinha === equipamentoSelecionado;

        if (correspondePesquisa && correspondeTipo && correspondeEstado && correspondeEquipamento) {
            linha.style.display = "";
            resultadosVisiveis++;
        } else {
            linha.style.display = "none";
        }
    });

    if (semResultadosDocumentos) {
        semResultadosDocumentos.style.display = resultadosVisiveis === 0 ? "block" : "none";
    }
}

if (pesquisaDocumento && filtroTipoDocumento && filtroEstadoDocumento && filtroEquipamentoDocumento) {
    pesquisaDocumento.addEventListener("input", filtrarDocumentos);
    filtroTipoDocumento.addEventListener("change", filtrarDocumentos);
    filtroEstadoDocumento.addEventListener("change", filtrarDocumentos);
    filtroEquipamentoDocumento.addEventListener("change", filtrarDocumentos);
}

const formDocumento = document.getElementById("formDocumento");

if (formDocumento) {
    formDocumento.addEventListener("submit", function (event) {
        event.preventDefault();

        const codigo = document.getElementById("codigoDocumento").value.trim();
        const nome = document.getElementById("nomeDocumento").value.trim();
        const tipo = document.getElementById("tipoDocumento").value;
        const equipamento = document.getElementById("equipamentoDocumento").value;
        const data = document.getElementById("dataDocumento").value;
        const estado = document.getElementById("estadoDocumento").value;
        const ficheiro = document.getElementById("ficheiroDocumento").value.trim();
        const mensagem = document.getElementById("mensagemDocumento");

        if (
            codigo === "" ||
            nome === "" ||
            tipo === "" ||
            equipamento === "" ||
            data === "" ||
            estado === "" ||
            ficheiro === ""
        ) {
            mensagem.textContent = "Preencha todos os campos obrigatórios assinalados com *.";
            mensagem.style.color = "#10233f";
        } else {
            mensagem.textContent = "Documento registado com sucesso. Esta ação será ligada à base de dados numa fase posterior.";
            mensagem.style.color = "green";
        }
    });
}

const formEditarDocumento = document.getElementById("formEditarDocumento");

if (formEditarDocumento) {
    formEditarDocumento.addEventListener("submit", function (event) {
        event.preventDefault();

        const codigo = document.getElementById("editCodigoDocumento").value.trim();
        const nome = document.getElementById("editNomeDocumento").value.trim();
        const tipo = document.getElementById("editTipoDocumento").value;
        const equipamento = document.getElementById("editEquipamentoDocumento").value;
        const data = document.getElementById("editDataDocumento").value;
        const estado = document.getElementById("editEstadoDocumento").value;
        const ficheiro = document.getElementById("editFicheiroDocumento").value.trim();
        const mensagem = document.getElementById("mensagemEditarDocumento");

        if (
            codigo === "" ||
            nome === "" ||
            tipo === "" ||
            equipamento === "" ||
            data === "" ||
            estado === "" ||
            ficheiro === ""
        ) {
            mensagem.textContent = "Preencha todos os campos obrigatórios assinalados com *.";
            mensagem.style.color = "#10233f";
        } else {
            mensagem.textContent = "Alterações guardadas com sucesso. Esta ação será ligada à base de dados numa fase posterior.";
            mensagem.style.color = "green";
        }
    });
}

const confirmarRemocaoDocumento = document.getElementById("confirmarRemocaoDocumento");

if (confirmarRemocaoDocumento) {
    confirmarRemocaoDocumento.addEventListener("click", function () {
        const mensagem = document.getElementById("mensagemRemocaoDocumento");

        mensagem.textContent = "Documento removido/arquivado com sucesso. Esta ação será ligada à base de dados numa fase posterior.";
        mensagem.style.color = "green";

        setTimeout(function () {
            window.location.href = "index.html";
        }, 1200);
    });
}

const pesquisaContrato = document.getElementById("pesquisaContrato");
const filtroTipoContrato = document.getElementById("filtroTipoContrato");
const filtroEstadoContrato = document.getElementById("filtroEstadoContrato");
const filtroEquipamentoContrato = document.getElementById("filtroEquipamentoContrato");
const tabelaContratos = document.getElementById("tabelaContratos");
const semResultadosContratos = document.getElementById("semResultadosContratos");

function filtrarContratos() {
    if (!tabelaContratos) {
        return;
    }

    const textoPesquisa = pesquisaContrato.value.toLowerCase();
    const tipoSelecionado = filtroTipoContrato.value;
    const estadoSelecionado = filtroEstadoContrato.value;
    const equipamentoSelecionado = filtroEquipamentoContrato.value;

    const linhas = tabelaContratos.querySelectorAll("tbody tr");
    let resultadosVisiveis = 0;

    linhas.forEach(function (linha) {
        const textoLinha = linha.textContent.toLowerCase();
        const tipoLinha = linha.getAttribute("data-tipo");
        const estadoLinha = linha.getAttribute("data-estado");
        const equipamentoLinha = linha.getAttribute("data-equipamento");

        const correspondePesquisa = textoLinha.includes(textoPesquisa);
        const correspondeTipo = tipoSelecionado === "" || tipoLinha === tipoSelecionado;
        const correspondeEstado = estadoSelecionado === "" || estadoLinha === estadoSelecionado;
        const correspondeEquipamento = equipamentoSelecionado === "" || equipamentoLinha === equipamentoSelecionado;

        if (correspondePesquisa && correspondeTipo && correspondeEstado && correspondeEquipamento) {
            linha.style.display = "";
            resultadosVisiveis++;
        } else {
            linha.style.display = "none";
        }
    });

    if (semResultadosContratos) {
        semResultadosContratos.style.display = resultadosVisiveis === 0 ? "block" : "none";
    }
}

if (pesquisaContrato && filtroTipoContrato && filtroEstadoContrato && filtroEquipamentoContrato) {
    pesquisaContrato.addEventListener("input", filtrarContratos);
    filtroTipoContrato.addEventListener("change", filtrarContratos);
    filtroEstadoContrato.addEventListener("change", filtrarContratos);
    filtroEquipamentoContrato.addEventListener("change", filtrarContratos);
}

const formContrato = document.getElementById("formContrato");

if (formContrato) {
    formContrato.addEventListener("submit", function (event) {
        event.preventDefault();

        const codigo = document.getElementById("codigoContrato").value.trim();
        const equipamento = document.getElementById("equipamentoContrato").value;
        const fornecedor = document.getElementById("fornecedorContrato").value;
        const tipo = document.getElementById("tipoContrato").value;
        const dataInicio = document.getElementById("dataInicioContrato").value;
        const dataFim = document.getElementById("dataFimContrato").value;
        const estado = document.getElementById("estadoContrato").value;
        const mensagem = document.getElementById("mensagemContrato");

        if (
            codigo === "" ||
            equipamento === "" ||
            fornecedor === "" ||
            tipo === "" ||
            dataInicio === "" ||
            dataFim === "" ||
            estado === ""
        ) {
            mensagem.textContent = "Preencha todos os campos obrigatórios assinalados com *.";
            mensagem.style.color = "#10233f";
        } else {
            mensagem.textContent = "Contrato registado com sucesso. Esta ação será ligada à base de dados numa fase posterior.";
            mensagem.style.color = "green";
        }
    });
}

const formEditarContrato = document.getElementById("formEditarContrato");

if (formEditarContrato) {
    formEditarContrato.addEventListener("submit", function (event) {
        event.preventDefault();

        const codigo = document.getElementById("editCodigoContrato").value.trim();
        const equipamento = document.getElementById("editEquipamentoContrato").value;
        const fornecedor = document.getElementById("editFornecedorContrato").value;
        const tipo = document.getElementById("editTipoContrato").value;
        const dataInicio = document.getElementById("editDataInicioContrato").value;
        const dataFim = document.getElementById("editDataFimContrato").value;
        const estado = document.getElementById("editEstadoContrato").value;
        const mensagem = document.getElementById("mensagemEditarContrato");

        if (
            codigo === "" ||
            equipamento === "" ||
            fornecedor === "" ||
            tipo === "" ||
            dataInicio === "" ||
            dataFim === "" ||
            estado === ""
        ) {
            mensagem.textContent = "Preencha todos os campos obrigatórios assinalados com *.";
            mensagem.style.color = "#10233f";
        } else {
            mensagem.textContent = "Alterações guardadas com sucesso. Esta ação será ligada à base de dados numa fase posterior.";
            mensagem.style.color = "green";
        }
    });
}

const confirmarRemocaoContrato = document.getElementById("confirmarRemocaoContrato");

if (confirmarRemocaoContrato) {
    confirmarRemocaoContrato.addEventListener("click", function () {
        const mensagem = document.getElementById("mensagemRemocaoContrato");

        mensagem.textContent = "Contrato removido/arquivado com sucesso. Esta ação será ligada à base de dados numa fase posterior.";
        mensagem.style.color = "green";

        setTimeout(function () {
            window.location.href = "index.html";
        }, 1200);
    });
}


const formConteudosPublicos = document.getElementById("formConteudosPublicos");
const botaoReporConteudos = document.getElementById("reporConteudosPublicos");

function carregarConteudosNoFormulario() {
    const camposConteudo = [
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

    camposConteudo.forEach(function (idCampo) {
        const campo = document.getElementById(idCampo);
        const valorGuardado = localStorage.getItem(idCampo);

        if (campo && valorGuardado) {
            campo.value = valorGuardado;
        }
    });
}

if (formConteudosPublicos) {
    carregarConteudosNoFormulario();

    formConteudosPublicos.addEventListener("submit", function (event) {
        event.preventDefault();

        const camposConteudo = [
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

        const mensagem = document.getElementById("mensagemConteudosPublicos");
        let formularioValido = true;

        camposConteudo.forEach(function (idCampo) {
            const campo = document.getElementById(idCampo);

            if (campo && campo.value.trim() === "") {
                formularioValido = false;
            }
        });

        if (!formularioValido) {
            mensagem.textContent = "Preencha todos os campos antes de guardar.";
            mensagem.style.color = "#10233f";
        } else {
            camposConteudo.forEach(function (idCampo) {
                const campo = document.getElementById(idCampo);

                if (campo) {
                    localStorage.setItem(idCampo, campo.value.trim());
                }
            });

            mensagem.textContent = "Conteúdos guardados com sucesso. Abra o site público para visualizar as alterações.";
            mensagem.style.color = "green";
        }
    });
}

if (botaoReporConteudos) {
    botaoReporConteudos.addEventListener("click", function () {
        const camposConteudo = [
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

        camposConteudo.forEach(function (idCampo) {
            localStorage.removeItem(idCampo);
        });

        window.location.reload();
    });
}

function aplicarTextoPublico(idElemento, idConteudo) {
    const elemento = document.getElementById(idElemento);
    const valorGuardado = localStorage.getItem(idConteudo);

    if (elemento && valorGuardado) {
        elemento.textContent = valorGuardado;
    }
}

aplicarTextoPublico("logoTextoPublico", "conteudoLogoTexto");

aplicarTextoPublico("navInicioPublico", "conteudoNavInicio");
aplicarTextoPublico("navSobrePublico", "conteudoNavSobre");
aplicarTextoPublico("navServicosPublico", "conteudoNavServicos");
aplicarTextoPublico("navContactosPublico", "conteudoNavContactos");
aplicarTextoPublico("navAreaReservadaPublico", "conteudoNavAreaReservada");

aplicarTextoPublico("tituloInicioPublico", "conteudoTituloInicio");
aplicarTextoPublico("textoInicioPublico", "conteudoTextoInicio");
aplicarTextoPublico("botaoInicioPublico", "conteudoBotaoInicio");

aplicarTextoPublico("sobreSubtituloPublico", "conteudoSobreSubtitulo");
aplicarTextoPublico("sobreTituloPublico", "conteudoSobreTitulo");
aplicarTextoPublico("sobreNosPublico", "conteudoSobreNos");
aplicarTextoPublico("sobreTexto2Publico", "conteudoSobreTexto2");
aplicarTextoPublico("sobreListaTituloPublico", "conteudoSobreListaTitulo");
aplicarTextoPublico("sobreListaItem1Publico", "conteudoSobreListaItem1");
aplicarTextoPublico("sobreListaItem2Publico", "conteudoSobreListaItem2");
aplicarTextoPublico("sobreListaItem3Publico", "conteudoSobreListaItem3");
aplicarTextoPublico("sobreListaItem4Publico", "conteudoSobreListaItem4");

aplicarTextoPublico("servicosTituloPublico", "conteudoServicosTitulo");
aplicarTextoPublico("servicosPublico", "conteudoServicos");
aplicarTextoPublico("servicoEquipamentosTituloPublico", "conteudoServicoEquipamentosTitulo");
aplicarTextoPublico("servicoEquipamentosTextoPublico", "conteudoServicoEquipamentosTexto");
aplicarTextoPublico("servicoLocalizacaoTituloPublico", "conteudoServicoLocalizacaoTitulo");
aplicarTextoPublico("servicoLocalizacaoTextoPublico", "conteudoServicoLocalizacaoTexto");
aplicarTextoPublico("servicoFornecedoresTituloPublico", "conteudoServicoFornecedoresTitulo");
aplicarTextoPublico("servicoFornecedoresTextoPublico", "conteudoServicoFornecedoresTexto");
aplicarTextoPublico("servicoDocumentacaoTituloPublico", "conteudoServicoDocumentacaoTitulo");
aplicarTextoPublico("servicoDocumentacaoTextoPublico", "conteudoServicoDocumentacaoTexto");
aplicarTextoPublico("servicoContratosTituloPublico", "conteudoServicoContratosTitulo");
aplicarTextoPublico("servicoContratosTextoPublico", "conteudoServicoContratosTexto");
aplicarTextoPublico("servicoPesquisaTituloPublico", "conteudoServicoPesquisaTitulo");
aplicarTextoPublico("servicoPesquisaTextoPublico", "conteudoServicoPesquisaTexto");

aplicarTextoPublico("contactosTituloPublico", "conteudoContactosTitulo");
aplicarTextoPublico("contactosIntroPublico", "conteudoContactosIntro");
aplicarTextoPublico("contactosInfoTituloPublico", "conteudoContactosInfoTitulo");
aplicarTextoPublico("emailPublico", "conteudoEmail");
aplicarTextoPublico("telefonePublico", "conteudoTelefone");
aplicarTextoPublico("localizacaoPublico", "conteudoLocalizacao");
aplicarTextoPublico("labelNomePublico", "conteudoLabelNome");
aplicarTextoPublico("labelEmailPublico", "conteudoLabelEmail");
aplicarTextoPublico("labelMensagemPublico", "conteudoLabelMensagem");
aplicarTextoPublico("botaoContactoPublico", "conteudoBotaoContacto");

aplicarTextoPublico("footerTexto1Publico", "conteudoFooterTexto1");
aplicarTextoPublico("footerTexto2Publico", "conteudoFooterTexto2");

