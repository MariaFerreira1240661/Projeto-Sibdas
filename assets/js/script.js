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

if (formEquipamento) {
    formEquipamento.addEventListener("submit", function (event) {
        event.preventDefault();

        const codigo = document.getElementById("codigo").value.trim();
        const designacao = document.getElementById("designacao").value.trim();
        const categoria = document.getElementById("categoria").value;
        const marca = document.getElementById("marca").value.trim();
        const modelo = document.getElementById("modelo").value.trim();
        const serie = document.getElementById("serie").value.trim();
        const localizacao = document.getElementById("localizacao").value;
        const estado = document.getElementById("estado").value;
        const criticidade = document.getElementById("criticidade").value;
        const mensagem = document.getElementById("mensagemEquipamento");

        if (
            codigo === "" ||
            designacao === "" ||
            categoria === "" ||
            marca === "" ||
            modelo === "" ||
            serie === "" ||
            localizacao === "" ||
            estado === "" ||
            criticidade === ""
        ) {
            mensagem.textContent = "Preencha todos os campos obrigatórios assinalados com *.";
            mensagem.style.color = "#10233f";
        } else {
            mensagem.textContent = "Equipamento registado com sucesso. Esta ação será ligada à base de dados numa fase posterior.";
            mensagem.style.color = "green";
        }
    });
}

const formEditarEquipamento = document.getElementById("formEditarEquipamento");

if (formEditarEquipamento) {
    formEditarEquipamento.addEventListener("submit", function (event) {
        event.preventDefault();

        const codigo = document.getElementById("editCodigo").value.trim();
        const designacao = document.getElementById("editDesignacao").value.trim();
        const marca = document.getElementById("editMarca").value.trim();
        const modelo = document.getElementById("editModelo").value.trim();
        const serie = document.getElementById("editSerie").value.trim();
        const mensagem = document.getElementById("mensagemEditarEquipamento");

        if (
            codigo === "" ||
            designacao === "" ||
            marca === "" ||
            modelo === "" ||
            serie === ""
        ) {
            mensagem.textContent = "Preencha todos os campos obrigatórios assinalados com *.";
            mensagem.style.color = "#10233f";
        } else {
            mensagem.textContent = "Alterações guardadas com sucesso. Esta ação será ligada à base de dados numa fase posterior.";
            mensagem.style.color = "green";
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

