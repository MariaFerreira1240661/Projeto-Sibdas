const $ = id => document.getElementById(id);
const valor = id => ($(id)?.value || "").trim();

function mensagem(id, texto, cor = "#10233f") {
    const el = $(id);

    if (!el) {
        return;
    }

    el.textContent = texto;
    el.style.color = cor;
    el.style.fontWeight = "700";
    el.style.display = texto ? "block" : "none";
}

function campoPreenchido(id) {
    const el = $(id);

    if (!el) {
        return false;
    }

    if (el.type === "file") {
        return el.files.length > 0;
    }

    return el.value.trim() !== "";
}

function camposPreenchidos(ids) {
    return ids.every(campoPreenchido);
}

function nomeFicheiro(id) {
    const el = $(id);

    if (el?.type === "file" && el.files.length > 0) {
        return el.files[0].name;
    }

    return "";
}

/* Filtros de escrita */

const filtros = {
    numeros: valor => valor.replace(/[^0-9]/g, ""),
    letras: valor => valor.replace(/[^A-Za-zÀ-ÿ\s]/g, ""),
    telefone: valor => valor.replace(/[^0-9+\s]/g, "")
};

function filtrarInput(id, tipo) {
    const el = $(id);

    if (!el || !filtros[tipo]) {
        return;
    }

    el.addEventListener("input", function () {
        el.value = filtros[tipo](el.value);
    });
}

function aplicarFiltros(configs) {
    configs.forEach(function (config) {
        filtrarInput(config.id, config.tipo);
    });
}

/* Regras de validação */

const regras = {
    numeros: valor => /^[0-9]+$/.test(valor.trim()),

    letras: valor => /^[A-Za-zÀ-ÿ\s]+$/.test(valor.trim()),

    email: valor => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(valor.trim()),

    nif: valor => /^[0-9]{9}$/.test(valor.trim()),

    telefone: valor => {
        const telefone = valor.trim();
        const formatoValido = /^[0-9+\s]+$/.test(telefone);
        const quantidadeNumeros = telefone.replace(/[^0-9]/g, "").length;

        return formatoValido && quantidadeNumeros >= 9 && quantidadeNumeros <= 15;
    },

    website: valor => {
        if (valor.trim() === "") {
            return true;
        }

        return /^(https?:\/\/)?([\w-]+\.)+[\w-]{2,}(\/.*)?$/.test(valor.trim());
    }
    ,

    pdf: valor => /\.pdf$/i.test(valor.trim()),

    positivo: valor => Number(valor) > 0
};

const mensagensValidacao = {
    obrigatorio: "Preencha todos os campos obrigatórios assinalados com *.",
    nif: "O NIF deve conter exatamente 9 números.",
    telefone: "O telefone deve conter apenas números, espaços ou o sinal +, e ter entre 9 e 15 números.",
    email: "Insira um email válido.",
    website: "Insira um website válido, por exemplo www.empresa.pt.",
    letras: "Este campo deve conter apenas letras.",
    piso: "O piso deve conter apenas números.",
    contacto: "O contacto interno deve conter apenas números.",
    numeroPisos: "O número de pisos deve conter apenas números."
    ,
    pdf: "O ficheiro/documento associado deve terminar em .pdf.",
    positivo: "O valor associado deve ser superior a 0."
};

function abrirTab(id) {
    if (!id) {
        return;
    }

    const tab = $(id);

    if (tab && typeof bootstrap !== "undefined") {
        tab.classList.remove("disabled");
        new bootstrap.Tab(tab).show();
    }
}

function mostrarErro(idMensagem, texto, tabId) {
    mensagem(idMensagem, texto, "#9b1c1c");
    abrirTab(tabId);

    const el = $(idMensagem);

    if (el) {
        el.scrollIntoView({
            behavior: "smooth",
            block: "center"
        });
    }
}

function validarCampos(campos, idMensagem, tabId) {
    for (const campo of campos) {
        const el = $(campo.id);

        if (!el) {
            continue;
        }

        const valorCampo = el.value.trim();

        if (campo.obrigatorio && valorCampo === "") {
            mostrarErro(idMensagem, campo.msgObrigatorio || mensagensValidacao.obrigatorio, tabId);
            el.focus();
            return false;
        }

        if (valorCampo !== "" && campo.regra && !regras[campo.regra](valorCampo)) {
            mostrarErro(idMensagem, campo.msg, tabId);
            el.focus();
            return false;
        }
    }

    return true;
}

/* Login */

const formLogin = $("form-login");

if (formLogin) {
    formLogin.addEventListener("submit", function (event) {
        event.preventDefault();

        const email = valor("emailLogin");
        const password = valor("password");

        if (!email || !password) {
            mensagem("mensagem-login", "Preencha o email e a palavra-passe.", "#10233f");
            return;
        }

        if (!regras.email(email)) {
            mensagem("mensagem-login", "Insira um email válido.", "#10233f");
            return;
        }

        mensagem("mensagem-login", "Login efetuado com sucesso.", "green");

        setTimeout(function () {
            window.location.href = "../private/index.php";
        }, 800);
    });
}

/* Gráficos */

function lerDadosGrafico(idCanvas) {
    const canvas = document.getElementById(idCanvas);

    if (!canvas) {
        return null;
    }

    let labels = [];
    let valores = [];

    try {
        labels = JSON.parse(canvas.getAttribute("data-labels") || "[]");
        valores = JSON.parse(canvas.getAttribute("data-valores") || "[]");
    } catch (erro) {
        console.error("Erro ao ler dados do gráfico:", idCanvas, erro);
        return null;
    }

    if (!Array.isArray(labels) || !Array.isArray(valores)) {
        return null;
    }

    return {
        canvas: canvas,
        labels: labels,
        valores: valores.map(Number)
    };
}

function criarGraficoDashboard(idCanvas) {
    const dados = lerDadosGrafico(idCanvas);

    if (!dados || typeof Chart === "undefined") {
        return;
    }

    if (dados.labels.length === 0 || dados.valores.length === 0) {
        return;
    }

    const configs = {
        graficoEstados: {
            type: "doughnut",
            data: {
                labels: dados.labels,
                datasets: [{
                    data: dados.valores
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
        },

        graficoCategorias: {
            type: "bar",
            data: {
                labels: dados.labels,
                datasets: [{
                    label: "N.º de equipamentos",
                    data: dados.valores
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
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        },

        graficoLocalizacoes: {
            type: "bar",
            data: {
                labels: dados.labels,
                datasets: [{
                    label: "N.º de equipamentos",
                    data: dados.valores
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
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        }
    };

    const config = configs[idCanvas];

    if (!config) {
        return;
    }

    new Chart(dados.canvas, config);
}

criarGraficoDashboard("graficoEstados");
criarGraficoDashboard("graficoCategorias");
criarGraficoDashboard("graficoLocalizacoes");

/* Filtros das tabelas */

function iniciarFiltroTabela(config) {
    const tabela = $(config.tabela);
    const pesquisa = $(config.pesquisa);
    const semResultados = $(config.semResultados);

    if (!tabela || !pesquisa) {
        return;
    }

    const filtrosTabela = config.filtros.map(function (filtro) {
        return {
            el: $(filtro.id),
            atributo: filtro.atributo
        };
    });

    function filtrar() {
        const texto = pesquisa.value.toLowerCase();
        const linhas = tabela.querySelectorAll("tbody tr");
        let visiveis = 0;

        linhas.forEach(function (linha) {
            const correspondeTexto = linha.textContent.toLowerCase().includes(texto);

            const correspondeFiltros = filtrosTabela.every(function (filtro) {
                if (!filtro.el || filtro.el.value === "") {
                    return true;
                }

                return linha.getAttribute("data-" + filtro.atributo) === filtro.el.value;
            });

            if (correspondeTexto && correspondeFiltros) {
                linha.style.display = "";
                visiveis++;
            } else {
                linha.style.display = "none";
            }
        });

        if (semResultados) {
            semResultados.style.display = visiveis === 0 ? "block" : "none";
        }
    }

    pesquisa.addEventListener("input", filtrar);

    filtrosTabela.forEach(function (filtro) {
        if (filtro.el) {
            filtro.el.addEventListener("change", filtrar);
        }
    });
}

[
    {
        tabela: "tabelaEquipamentos",
        pesquisa: "pesquisaEquipamento",
        semResultados: "semResultados",
        filtros: [
            { id: "filtroEstado", atributo: "estado" },
            { id: "filtroCategoria", atributo: "categoria" },
            { id: "filtroCriticidade", atributo: "criticidade" }
        ]
    },
    {
        tabela: "tabelaFornecedores",
        pesquisa: "pesquisaFornecedor",
        semResultados: "semResultadosFornecedores",
        filtros: [
            { id: "filtroTipoFornecedor", atributo: "tipo" },
            { id: "filtroEstadoFornecedor", atributo: "estado" }
        ]
    },
    {
        tabela: "tabelaLocalizacoes",
        pesquisa: "pesquisaLocalizacao",
        semResultados: "semResultadosLocalizacoes",
        filtros: [
            { id: "filtroServicoLocalizacao", atributo: "servico" },
            { id: "filtroEstadoLocalizacao", atributo: "estado" }
        ]
    },
    {
        tabela: "tabelaDocumentos",
        pesquisa: "pesquisaDocumento",
        semResultados: "semResultadosDocumentos",
        filtros: [
            { id: "filtroTipoDocumento", atributo: "tipo" },
            { id: "filtroEstadoDocumento", atributo: "estado" },
            { id: "filtroEquipamentoDocumento", atributo: "equipamento" }
        ]
    },
    {
        tabela: "tabelaContratos",
        pesquisa: "pesquisaContrato",
        semResultados: "semResultadosContratos",
        filtros: [
            { id: "filtroTipoContrato", atributo: "tipo" },
            { id: "filtroEstadoContrato", atributo: "estado" },
            { id: "filtroEquipamentoContrato", atributo: "equipamento" }
        ]
    }
].forEach(iniciarFiltroTabela);

/* Formulários simples */

const validacoesFormulario = {
    fornecedorNovo: {
        filtros: [
            { id: "nifFornecedor", tipo: "numeros" },
            { id: "telefoneFornecedor", tipo: "telefone" },
            { id: "pessoaContactoFornecedor", tipo: "letras" },
            { id: "telefoneContactoFornecedor", tipo: "telefone" }
        ],
        campos: [
            { id: "nifFornecedor", regra: "nif", obrigatorio: true, msg: mensagensValidacao.nif },
            { id: "telefoneFornecedor", regra: "telefone", obrigatorio: true, msg: mensagensValidacao.telefone },
            { id: "emailFornecedor", regra: "email", obrigatorio: true, msg: mensagensValidacao.email },
            { id: "websiteFornecedor", regra: "website", obrigatorio: false, msg: mensagensValidacao.website },
            { id: "pessoaContactoFornecedor", regra: "letras", obrigatorio: true, msg: "A pessoa de contacto deve conter apenas letras." },
            { id: "telefoneContactoFornecedor", regra: "telefone", obrigatorio: false, msg: "O telefone da pessoa de contacto deve conter apenas números, espaços ou o sinal +, e ter entre 9 e 15 números." }
        ]
    },

    fornecedorEditar: {
        filtros: [
            { id: "editNifFornecedor", tipo: "numeros" },
            { id: "editTelefoneFornecedor", tipo: "telefone" },
            { id: "editPessoaContactoFornecedor", tipo: "letras" },
            { id: "editTelefoneContactoFornecedor", tipo: "telefone" }
        ],
        campos: [
            { id: "editNifFornecedor", regra: "nif", obrigatorio: true, msg: mensagensValidacao.nif },
            { id: "editTelefoneFornecedor", regra: "telefone", obrigatorio: true, msg: mensagensValidacao.telefone },
            { id: "editEmailFornecedor", regra: "email", obrigatorio: true, msg: mensagensValidacao.email },
            { id: "editWebsiteFornecedor", regra: "website", obrigatorio: true, msg: mensagensValidacao.website },
            { id: "editPessoaContactoFornecedor", regra: "letras", obrigatorio: true, msg: "A pessoa de contacto deve conter apenas letras." },
            { id: "editTelefoneContactoFornecedor", regra: "telefone", obrigatorio: false, msg: "O telefone da pessoa de contacto deve conter apenas números, espaços ou o sinal +, e ter entre 9 e 15 números." }
        ]
    },

    localizacaoNova: {
    filtros: [
        { id: "pisoLocalizacao", tipo: "numeros" },
        { id: "responsavelLocalizacao", tipo: "letras" },
        { id: "contactoInternoLocalizacao", tipo: "numeros" }
    ],
    campos: [
        { id: "pisoLocalizacao", regra: "numeros", obrigatorio: true, msg: mensagensValidacao.piso },
        { id: "responsavelLocalizacao", regra: "letras", obrigatorio: true, msg: "O responsável da localização deve conter apenas letras." },
        { id: "contactoInternoLocalizacao", regra: "numeros", obrigatorio: true, msg: mensagensValidacao.contacto }
    ]
    },

    localizacaoEditar: {
    filtros: [
        { id: "editNumeroPisosLocalizacao", tipo: "numeros" },
        { id: "editResponsavelLocalizacao", tipo: "letras" },
        { id: "editContactoInternoLocalizacao", tipo: "numeros" }
    ],
    campos: [
        { id: "editNumeroPisosLocalizacao", regra: "numeros", obrigatorio: true, msg: mensagensValidacao.numeroPisos },
        { id: "editResponsavelLocalizacao", regra: "letras", obrigatorio: true, msg: "O responsável da localização deve conter apenas letras." },
        { id: "editContactoInternoLocalizacao", regra: "numeros", obrigatorio: true, msg: mensagensValidacao.contacto }
    ]
    }
    ,

    documentoNovo: {
        filtros: [
            { id: "responsavelDocumento", tipo: "letras" }
        ],
        campos: [
            { id: "codigoDocumento", obrigatorio: true },
            { id: "nomeDocumento", obrigatorio: true },
            { id: "tipoDocumento", obrigatorio: true },
            { id: "equipamentoDocumento", obrigatorio: true },
            { id: "dataDocumento", obrigatorio: true },
            { id: "estadoDocumento", obrigatorio: true },
            { id: "ficheiroDocumento", regra: "pdf", obrigatorio: true, msg: mensagensValidacao.pdf },
            { id: "responsavelDocumento", regra: "letras", obrigatorio: false, msg: "O responsável pelo registo deve conter apenas letras." }
        ]
    },

    documentoEditar: {
        filtros: [
            { id: "editResponsavelDocumento", tipo: "letras" }
        ],
        campos: [
            { id: "editCodigoDocumento", obrigatorio: true },
            { id: "editNomeDocumento", obrigatorio: true },
            { id: "editTipoDocumento", obrigatorio: true },
            { id: "editEquipamentoDocumento", obrigatorio: true },
            { id: "editDataDocumento", obrigatorio: true },
            { id: "editEstadoDocumento", obrigatorio: true },
            { id: "editFicheiroDocumento", regra: "pdf", obrigatorio: true, msg: mensagensValidacao.pdf },
            { id: "editResponsavelDocumento", regra: "letras", obrigatorio: false, msg: "O responsável pelo registo deve conter apenas letras." }
        ]
    },

    contratoNovo: {
        campos: [
            { id: "codigoContrato", obrigatorio: true },
            { id: "equipamentoContrato", obrigatorio: true },
            { id: "fornecedorContrato", obrigatorio: true },
            { id: "tipoContrato", obrigatorio: true },
            { id: "dataInicioContrato", obrigatorio: true },
            { id: "dataFimContrato", obrigatorio: true },
            { id: "estadoContrato", obrigatorio: true },
            { id: "valorContrato", regra: "positivo", obrigatorio: false, msg: mensagensValidacao.positivo },
            { id: "documentoContrato", regra: "pdf", obrigatorio: false, msg: mensagensValidacao.pdf }
        ]
    },

    contratoEditar: {
        campos: [
            { id: "editCodigoContrato", obrigatorio: true },
            { id: "editEquipamentoContrato", obrigatorio: true },
            { id: "editFornecedorContrato", obrigatorio: true },
            { id: "editTipoContrato", obrigatorio: true },
            { id: "editDataInicioContrato", obrigatorio: true },
            { id: "editDataFimContrato", obrigatorio: true },
            { id: "editEstadoContrato", obrigatorio: true },
            { id: "editValorContrato", regra: "positivo", obrigatorio: false, msg: mensagensValidacao.positivo },
            { id: "editDocumentoContrato", regra: "pdf", obrigatorio: false, msg: mensagensValidacao.pdf }
        ]
    }
};

function validarIntervaloDatas(inicioId, fimId, idMensagem) {
    const inicio = valor(inicioId);
    const fim = valor(fimId);

    if (inicio && fim && new Date(fim) < new Date(inicio)) {
        mostrarErro(idMensagem, "A data de fim não pode ser anterior à data de início.");
        $(fimId)?.focus();
        return false;
    }

    return true;
}
function iniciarFormulario(config) {
    const form = $(config.form);

    if (!form) {
        return;
    }

    aplicarFiltros(config.validacao?.filtros || []);

    form.addEventListener("submit", function (event) {
        event.preventDefault();

        if (!camposPreenchidos(config.obrigatorios)) {
            mensagem(config.mensagem, mensagensValidacao.obrigatorio, "#10233f");
            return;
        }

        if (config.validacao && !validarCampos(config.validacao.campos, config.mensagem)) {
            return;
        }
        if (config.datas && !validarIntervaloDatas(config.datas.inicio, config.datas.fim, config.mensagem)) {
            return;
        }

        mensagem(config.mensagem, config.sucesso, "green");
    });
}

[
    {
        form: "formEditarFornecedor",
        mensagem: "mensagemEditarFornecedor",
        obrigatorios: ["editCodigoFornecedor", "editNomeFornecedor", "editNifFornecedor", "editTelefoneFornecedor", "editEmailFornecedor", "editWebsiteFornecedor"],
        sucesso: "Alterações guardadas com sucesso. ",
        validacao: validacoesFormulario.fornecedorEditar
    },
    {
        form: "formEditarLocalizacao",
        mensagem: "mensagemEditarLocalizacao",
        obrigatorios: ["editCodigoLocalizacao", "editEdificioLocalizacao", "editNumeroPisosLocalizacao", "editEstadoLocalizacao", "editResponsavelLocalizacao", "editContactoInternoLocalizacao"],
        sucesso: "Alterações guardadas com sucesso. ",
        validacao: validacoesFormulario.localizacaoEditar
    }
    ,
    {
        form: "formDocumento",
        mensagem: "mensagemDocumento",
        obrigatorios: ["codigoDocumento", "nomeDocumento", "tipoDocumento", "equipamentoDocumento", "dataDocumento", "estadoDocumento", "ficheiroDocumento"],
        sucesso: "Documento registado com sucesso.",
        validacao: validacoesFormulario.documentoNovo
    },
    {
        form: "formContrato",
        mensagem: "mensagemContrato",
        obrigatorios: ["codigoContrato", "equipamentoContrato", "fornecedorContrato", "tipoContrato", "dataInicioContrato", "dataFimContrato", "estadoContrato"],
        sucesso: "Contrato registado com sucesso.",
        validacao: validacoesFormulario.contratoNovo,
        datas: { inicio: "dataInicioContrato", fim: "dataFimContrato" }
    }
].forEach(iniciarFormulario);

/* Remoções */

function iniciarRemocao(config) {
    const botao = $(config.botao);

    if (!botao) {
        return;
    }

    botao.addEventListener("click", function () {
        mensagem(config.mensagem, config.texto, "green");

        setTimeout(function () {
            window.location.href = "index.php";
        }, 1200);
    });
}

[
    ["confirmarRemocao", "mensagemRemocao", "Equipamento removido/arquivado com sucesso. "],
    ["confirmarRemocaoFornecedor", "mensagemRemocaoFornecedor", "Fornecedor removido/arquivado com sucesso. "],
    ["confirmarRemocaoLocalizacao", "mensagemRemocaoLocalizacao", "Localização removida/arquivada com sucesso. "],
    ["confirmarRemocaoDocumento", "mensagemRemocaoDocumento", "Documento removido/arquivado com sucesso. "],
    ["confirmarRemocaoContrato", "mensagemRemocaoContrato", "Contrato removido/arquivado com sucesso. "]
].forEach(function (item) {
    iniciarRemocao({
        botao: item[0],
        mensagem: item[1],
        texto: item[2]
    });
});
/* Editar equipamento */

const validacoesEquipamentoEditar = {
    localizacao: [
        { id: "editPisoEquipamento", regra: "numeros", obrigatorio: true, msg: mensagensValidacao.piso }
    ],
    fornecedor: [
        { id: "editContactoFornecedorEquipamento", regra: "letras", obrigatorio: true, msg: "A pessoa de contacto deve conter apenas letras." },
        { id: "editTelefoneContactoFornecedorEquipamento", regra: "telefone", obrigatorio: true, msg: mensagensValidacao.telefone },
        { id: "editEmailContactoFornecedorEquipamento", regra: "email", obrigatorio: true, msg: mensagensValidacao.email }
    ]
};

aplicarFiltros([
    { id: "editPisoEquipamento", tipo: "numeros" },
    { id: "pisoEquipamento", tipo: "numeros" },
    { id: "editContactoFornecedorEquipamento", tipo: "letras" },
    { id: "editTelefoneContactoFornecedorEquipamento", tipo: "telefone" },

    { id: "contactoFornecedorEquipamento", tipo: "letras" },
    { id: "telefoneContactoFornecedorEquipamento", tipo: "telefone" },

    { id: "contactoFornecedorEquipamento2", tipo: "letras" },
    { id: "telefoneContactoFornecedorEquipamento2", tipo: "telefone" },

    { id: "contactoFornecedorEquipamento3", tipo: "letras" },
    { id: "telefoneContactoFornecedorEquipamento3", tipo: "telefone" },
    { id: "nifFornecedorEquipamento3", tipo: "numeros" }
]);

const etapasEditarEquipamento = [
    {
        tab: "edit-info-tab",
        erro: "Preencha todos os campos obrigatórios da Informação geral.",
        campos: ["editCodigo", "editDesignacao", "editCategoria", "editMarca", "editModelo", "editSerie", "editFabricante", "editAnoFabrico", "editEstado", "editCriticidade","editPrioridadeManutencao","editUtilizacaoClinica", "editDataAquisicao", "editCusto","editTipoEntrada"]
    },
    { 
        tab: "edit-localizacao-tab",
        erro: "Preencha todos os campos obrigatórios da Localização.",
        campos: ["editLocalizacaoGeralEquipamento", "editPisoEquipamento", "editServicoEquipamento", "editSalaEquipamento"],
        validacao: validacoesEquipamentoEditar.localizacao
    },
    {
        tab: "edit-fornecedor-tab",
        erro: "Preencha todos os campos obrigatórios do Fornecedor.",
        campos: ["editFornecedorEquipamento", "editTipoFornecedorEquipamento", "editContactoFornecedorEquipamento", "editTelefoneContactoFornecedorEquipamento", "editEmailContactoFornecedorEquipamento"],
        validacao: validacoesEquipamentoEditar.fornecedor
    },
    {
        tab: "edit-documentacao-tab",
        erro: "Verifique a documentação associada ao equipamento.",
        campos: []
    },
    {
        tab: "edit-contratos-tab",
        erro: "Preencha todos os campos obrigatórios dos Contratos.",
        campos: ["editEstadoGarantiaEquipamento", "editDataInicioGarantiaEquipamento", "editDataFimGarantiaEquipamento", "editTipoContratoEquipamento", "editPeriodicidadeManutencaoEquipamento", "editValorContratoEquipamento"]
    }
];

const formEditarEquipamento = $("formEditarEquipamento");

if (formEditarEquipamento) {
    formEditarEquipamento.addEventListener("submit", function (event) {
        event.preventDefault();

        for (const etapa of etapasEditarEquipamento) {
            if (!camposPreenchidos(etapa.campos)) {
                mostrarErro("mensagemEditarEquipamento", etapa.erro, etapa.tab);
                return;
            }

            if (etapa.validacao && !validarCampos(etapa.validacao, "mensagemEditarEquipamento", etapa.tab)) {
                return;
            }
        }

        mensagem("mensagemEditarEquipamento", "Alterações guardadas com sucesso. ", "green");
    });
}

/* Relações e consumíveis */

function ligarCampoCondicional(config) {
    const seletor = $(config.seletor);
    const campo = $(config.campo);

    if (!seletor || !campo) {
        return;
    }

    function atualizar() {
        if (seletor.value === config.valorAtivo) {
            campo.disabled = false;
        } else {
            campo.disabled = true;
            campo.value = "";
        }
    }

    seletor.addEventListener("change", atualizar);
    atualizar();
}

[
    ["componenteOutroEquipamento", "equipamentoPrincipal"],
    ["temConsumiveis", "consumiveisEquipamento"],
    ["editComponenteOutroEquipamento", "editEquipamentoPrincipal"],
    ["editTemConsumiveis", "editConsumiveisEquipamento"]
].forEach(function (item) {
    ligarCampoCondicional({
        seletor: item[0],
        campo: item[1],
        valorAtivo: "Sim"
    });
});

removerBotoesNovoEditarDocumentosContratos();
inserirDocumentosAssociadosNaListagem();
inserirContratosAssociadosNaListagem();

/* Tooltips Bootstrap */

document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function (el) {
    if (typeof bootstrap !== "undefined") {
        new bootstrap.Tooltip(el);
    }
});

/* Preenchimento automático do login para testes */
document.querySelectorAll('[data-login-email][data-login-password]').forEach((botao) => {
    botao.addEventListener('click', () => {
        const formulario = document.forms['formulario'];

        if (!formulario) {
            return;
        }

        formulario['text_username'].value = botao.dataset.loginEmail;
        formulario['text_password'].value = botao.dataset.loginPassword;
    });
});
