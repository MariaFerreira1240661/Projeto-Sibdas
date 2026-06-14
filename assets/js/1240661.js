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
    contacto: "O contacto interno deve conter apenas números."
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
            window.location.href = "../private/index.html";
        }, 800);
    });
}

/* Gráficos */

function criarGrafico(id, config) {
    const canvas = $(id);

    if (canvas && typeof Chart !== "undefined") {
        new Chart(canvas, config);
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
            { id: "editWebsiteFornecedor", regra: "website", obrigatorio: false, msg: mensagensValidacao.website },
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
        { id: "editPisoLocalizacao", tipo: "numeros" },
        { id: "editResponsavelLocalizacao", tipo: "letras" },
        { id: "editContactoInternoLocalizacao", tipo: "numeros" }
    ],
    campos: [
        { id: "editPisoLocalizacao", regra: "numeros", obrigatorio: true, msg: mensagensValidacao.piso },
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
        form: "formFornecedor",
        mensagem: "mensagemFornecedor",
        obrigatorios: ["codigoFornecedor", "nomeFornecedor", "nifFornecedor", "tipoFornecedor", "telefoneFornecedor", "emailFornecedor",  "pessoaContactoFornecedor"],
        sucesso: "Fornecedor registado com sucesso. ",
        validacao: validacoesFormulario.fornecedorNovo
    },
    {
        form: "formEditarFornecedor",
        mensagem: "mensagemEditarFornecedor",
        obrigatorios: ["editCodigoFornecedor", "editNomeFornecedor", "editNifFornecedor", "editTipoFornecedor", "editTelefoneFornecedor", "editEmailFornecedor",  "editPessoaContactoFornecedor"],
        sucesso: "Alterações guardadas com sucesso. ",
        validacao: validacoesFormulario.fornecedorEditar
    },
    {
        form: "formLocalizacao",
        mensagem: "mensagemLocalizacao",
        obrigatorios: ["codigoLocalizacao", "edificioLocalizacao", "pisoLocalizacao", "servicoLocalizacao", "salaLocalizacao", "estadoLocalizacao", "responsavelLocalizacao","contactoInternoLocalizacao"],
        sucesso: "Localização registada com sucesso. ",
        validacao: validacoesFormulario.localizacaoNova
    },
    {
        form: "formEditarLocalizacao",
        mensagem: "mensagemEditarLocalizacao",
        obrigatorios: ["editCodigoLocalizacao", "editEdificioLocalizacao", "editPisoLocalizacao", "editServicoLocalizacao", "editSalaLocalizacao", "editEstadoLocalizacao", "editResponsavelLocalizacao", "editContactoInternoLocalizacao"],
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
        form: "formEditarDocumento",
        mensagem: "mensagemEditarDocumento",
        obrigatorios: ["editCodigoDocumento", "editNomeDocumento", "editTipoDocumento", "editEquipamentoDocumento", "editDataDocumento", "editEstadoDocumento", "editFicheiroDocumento"],
        sucesso: "Alterações guardadas com sucesso.",
        validacao: validacoesFormulario.documentoEditar
    },
    {
        form: "formContrato",
        mensagem: "mensagemContrato",
        obrigatorios: ["codigoContrato", "equipamentoContrato", "fornecedorContrato", "tipoContrato", "dataInicioContrato", "dataFimContrato", "estadoContrato"],
        sucesso: "Contrato registado com sucesso.",
        validacao: validacoesFormulario.contratoNovo,
        datas: { inicio: "dataInicioContrato", fim: "dataFimContrato" }
    },
    {
        form: "formEditarContrato",
        mensagem: "mensagemEditarContrato",
        obrigatorios: ["editCodigoContrato", "editEquipamentoContrato", "editFornecedorContrato", "editTipoContrato", "editDataInicioContrato", "editDataFimContrato", "editEstadoContrato"],
        sucesso: "Alterações guardadas com sucesso.",
        validacao: validacoesFormulario.contratoEditar,
        datas: { inicio: "editDataInicioContrato", fim: "editDataFimContrato" }
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
            window.location.href = "index.html";
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
/* Documentos e contratos associados aos equipamentos */

function lerJSON(chave) {
    return JSON.parse(localStorage.getItem(chave)) || [];
}

function guardarJSON(chave, dados) {
    localStorage.setItem(chave, JSON.stringify(dados));
}

function obterDocumentosGuardados() {
    return lerJSON("medcontrolDocumentosEquipamentos");
}

function obterContratosGuardados() {
    return lerJSON("medcontrolContratosEquipamentos");
}

function guardarAssociacoesNovoEquipamento() {
    const equipamento = valor("designacao");
    const fornecedor = valor("fornecedorEquipamento");
    const documentos = obterDocumentosGuardados();

    const novosDocumentos = [
        {
            codigo: "D" + String(documentos.length + 1).padStart(3, "0"),
            nome: "Manual de utilizador",
            tipo: "Manual",
            tipoFiltro: "manual",
            equipamento,
            fornecedor,
            data: new Date().toLocaleDateString("pt-PT"),
            validade: "Não aplicável",
            estado: "Válido",
            estadoFiltro: "valido",
            ficheiro: nomeFicheiro("manualEquipamento")
        },
        {
            codigo: "D" + String(documentos.length + 2).padStart(3, "0"),
            nome: "Certificado de calibração",
            tipo: "Certificado",
            tipoFiltro: "certificado",
            equipamento,
            fornecedor,
            data: new Date().toLocaleDateString("pt-PT"),
            validade: valor("dataFimGarantiaEquipamento") || "Não aplicável",
            estado: "Válido",
            estadoFiltro: "valido",
            ficheiro: nomeFicheiro("certificadoEquipamento")
        },
        {
            codigo: "D" + String(documentos.length + 3).padStart(3, "0"),
            nome: "Relatório técnico",
            tipo: "Relatório técnico",
            tipoFiltro: "relatorio",
            equipamento,
            fornecedor,
            data: new Date().toLocaleDateString("pt-PT"),
            validade: "Não aplicável",
            estado: "Válido",
            estadoFiltro: "valido",
            ficheiro: nomeFicheiro("relatorioTecnicoEquipamento")
        }
    ];

    guardarJSON("medcontrolDocumentosEquipamentos", documentos.concat(novosDocumentos));

    const contratos = obterContratosGuardados();
    const tipoContrato = valor("tipoContratoEquipamento");
    const estadoContrato = valor("estadoGarantiaEquipamento");

    const novoContrato = {
        codigo: "C" + String(contratos.length + 1).padStart(3, "0"),
        equipamento,
        fornecedor,
        tipo: tipoContrato,
        tipoFiltro: tipoContrato.toLowerCase().includes("manutenção") ? "manutencao" : "garantia",
        dataInicio: valor("dataInicioGarantiaEquipamento"),
        dataFim: valor("dataFimGarantiaEquipamento"),
        periodicidade: valor("periodicidadeManutencaoEquipamento"),
        estado: estadoContrato,
        estadoFiltro: estadoContrato.toLowerCase().includes("expirada") ? "expirado" : "ativo",
        valor: valor("valorContratoEquipamento"),
        ficheiro: nomeFicheiro("contratoDocumentoEquipamento")
    };

    guardarJSON("medcontrolContratosEquipamentos", contratos.concat(novoContrato));
}

function removerBotoesNovoEditarDocumentosContratos() {
    const caminho = window.location.pathname;

    if (!caminho.includes("/documentacao/") && !caminho.includes("/contratos/")) {
        return;
    }

    document.querySelectorAll('a[href="novo.html"], a[href="editar.html"]').forEach(function (link) {
        link.remove();
    });
}

function inserirLinhasGuardadas(config) {
    const tabela = $(config.tabela);

    if (!tabela) {
        return;
    }

    const tbody = tabela.querySelector("tbody");
    const dados = config.dados();

    dados.forEach(function (item) {
        const linha = document.createElement("tr");

        linha.setAttribute("data-tipo", item.tipoFiltro);
        linha.setAttribute("data-estado", item.estadoFiltro);
        linha.setAttribute("data-equipamento", item.equipamento.toLowerCase());

        linha.innerHTML = config.html(item);
        tbody.appendChild(linha);
    });
}

function inserirDocumentosAssociadosNaListagem() {
    inserirLinhasGuardadas({
        tabela: "tabelaDocumentos",
        dados: obterDocumentosGuardados,
        html: function (documento) {
            return `
                <td>${documento.codigo}</td>
                <td>${documento.nome}</td>
                <td>${documento.tipo}</td>
                <td>${documento.equipamento}</td>
                <td>${documento.fornecedor}</td>
                <td>${documento.data}</td>
                <td>${documento.validade}</td>
                <td><span class="estado ativo">${documento.estado}</span></td>
                <td>${documento.ficheiro}</td>
                <td class="acoes-tabela">
                    <a href="detalhes.html" data-bs-toggle="tooltip" data-bs-title="Ver detalhes">
                        <i class="bi bi-eye"></i>
                    </a>
                    <a href="remover.html" data-bs-toggle="tooltip" data-bs-title="Remover documento">
                        <i class="bi bi-trash"></i>
                    </a>
                </td>
            `;
        }
    });
}

function inserirContratosAssociadosNaListagem() {
    inserirLinhasGuardadas({
        tabela: "tabelaContratos",
        dados: obterContratosGuardados,
        html: function (contrato) {
            return `
                <td>${contrato.codigo}</td>
                <td>${contrato.equipamento}</td>
                <td>${contrato.fornecedor}</td>
                <td>${contrato.tipo}</td>
                <td>${contrato.dataInicio}</td>
                <td>${contrato.dataFim}</td>
                <td>${contrato.periodicidade}</td>
                <td><span class="estado ativo">${contrato.estado}</span></td>
                <td class="acoes-tabela">
                    <a href="detalhes.html" data-bs-toggle="tooltip" data-bs-title="Ver detalhes">
                        <i class="bi bi-eye"></i>
                    </a>
                    <a href="remover.html" data-bs-toggle="tooltip" data-bs-title="Remover contrato">
                        <i class="bi bi-trash"></i>
                    </a>
                </td>
            `;
        }
    });
}

/* Novo equipamento por etapas */

const validacoesEquipamentoNovo = {
    localizacao: [
        { id: "pisoEquipamento", regra: "numeros", obrigatorio: true, msg: mensagensValidacao.piso },
        { id: "responsavelLocalizacaoEquipamento", regra: "letras", obrigatorio: true, msg: "O responsável da localização deve conter apenas letras." },
        { id: "contactoLocalizacaoEquipamento", regra: "numeros", obrigatorio: true, msg: mensagensValidacao.contacto }
    ],
    fornecedor: [
        { id: "contactoFornecedorEquipamento", regra: "letras", obrigatorio: true, msg: "A pessoa de contacto deve conter apenas letras." },
        { id: "telefoneFornecedorEquipamento", regra: "telefone", obrigatorio: true, msg: mensagensValidacao.telefone },
        { id: "emailFornecedorEquipamento", regra: "email", obrigatorio: true, msg: mensagensValidacao.email },
        { id: "nifFornecedorEquipamento", regra: "nif", obrigatorio: true, msg: mensagensValidacao.nif }
    ]
};

aplicarFiltros([
    { id: "pisoEquipamento", tipo: "numeros" },
    { id: "responsavelLocalizacaoEquipamento", tipo: "letras" },
    { id: "contactoLocalizacaoEquipamento", tipo: "numeros" },
    { id: "contactoFornecedorEquipamento", tipo: "letras" },
    { id: "telefoneFornecedorEquipamento", tipo: "telefone" },
    { id: "nifFornecedorEquipamento", tipo: "numeros" }
]);

const etapasNovoEquipamento = [
    {
        tab: "info-tab",
        percentagem: 20,
        texto: "Etapa 1 de 5: Informação geral",
        erro: "Preencha todos os campos obrigatórios da Informação geral.",
        campos: ["codigo", "designacao", "categoria", "marca", "modelo", "serie", "fabricante", "anoFabrico", "estado", "criticidade", "prioridadeManutencao","utilizacaoClinica", "dataAquisicao", "custo", "tipoEntrada"]
    },
    {
        tab: "localizacao-tab",
        percentagem: 40,
        texto: "Etapa 2 de 5: Localização",
        erro: "Preencha todos os campos obrigatórios da Localização.",
        campos: ["localizacao", "edificioEquipamento", "pisoEquipamento", "salaEquipamento", "responsavelLocalizacaoEquipamento", "contactoLocalizacaoEquipamento","estadoLocalizacaoEquipamento"],
        validacao: validacoesEquipamentoNovo.localizacao
    },
    {
        tab: "fornecedor-tab",
        percentagem: 60,
        texto: "Etapa 3 de 5: Fornecedor",
        erro: "Preencha todos os campos obrigatórios do Fornecedor.",
        campos: ["fornecedorEquipamento", "tipoFornecedorEquipamento", "contactoFornecedorEquipamento", "telefoneFornecedorEquipamento", "emailFornecedorEquipamento", "nifFornecedorEquipamento"],
        validacao: validacoesEquipamentoNovo.fornecedor
    },
    {
        tab: "documentacao-tab",
        percentagem: 80,
        texto: "Etapa 4 de 5: Documentação",
        erro: "Associe todos os documentos obrigatórios em PDF.",
        campos: ["manualEquipamento", "certificadoEquipamento", "relatorioTecnicoEquipamento", "contratoDocumentoEquipamento"]
    },
    {
        tab: "garantia-tab",
        percentagem: 100,
        texto: "Etapa 5 de 5: Contratos",
        erro: "Preencha todos os campos obrigatórios dos Contratos e associe o PDF.",
        campos: ["estadoGarantiaEquipamento", "dataInicioGarantiaEquipamento", "dataFimGarantiaEquipamento", "tipoContratoEquipamento", "periodicidadeManutencaoEquipamento", "valorContratoEquipamento", ]
    }
];

function atualizarProgresso(etapa) {
    const barra = $("barraProgressoEquipamento");
    const texto = $("textoProgressoEquipamento");
    const percentagem = $("percentagemProgressoEquipamento");

    if (!barra || !texto || !percentagem) {
        return;
    }

    barra.style.width = etapa.percentagem + "%";
    barra.setAttribute("aria-valuenow", etapa.percentagem);
    texto.textContent = etapa.texto;
    percentagem.textContent = etapa.percentagem + "%";
}

function abrirEtapa(tabId) {
    const etapa = etapasNovoEquipamento.find(item => item.tab === tabId);

    abrirTab(tabId);

    if (etapa) {
        atualizarProgresso(etapa);
    }
}

function erroNovoEquipamento(texto) {
    mostrarErro("mensagemEquipamento", texto);
}

function etapaValida(etapa) {
    if (!camposPreenchidos(etapa.campos)) {
        erroNovoEquipamento(etapa.erro);
        abrirEtapa(etapa.tab);
        return false;
    }

    if (etapa.validacao && !validarCampos(etapa.validacao, "mensagemEquipamento", etapa.tab)) {
        return false;
    }

    return true;
}

function validarNovoEquipamentoCompleto() {
    return etapasNovoEquipamento.every(etapaValida);
}

const formEquipamento = $("formEquipamento");

if (formEquipamento) {
    [
        ["avancarLocalizacao", 0, 1],
        ["avancarFornecedor", 1, 2],
        ["avancarDocumentacao", 2, 3],
        ["avancarContratos", 3, 4]
    ].forEach(function (item) {
        const botao = $(item[0]);

        if (!botao) {
            return;
        }

        botao.addEventListener("click", function () {
            const etapaAtual = etapasNovoEquipamento[item[1]];

            if (etapaValida(etapaAtual)) {
                mensagem("mensagemEquipamento", "");
                abrirEtapa(etapasNovoEquipamento[item[2]].tab);
            }
        });
    });

    [
        ["voltarInfo", "info-tab"],
        ["voltarLocalizacao", "localizacao-tab"],
        ["voltarFornecedor", "fornecedor-tab"]
    ].forEach(function (item) {
        const botao = $(item[0]);

        if (botao) {
            botao.addEventListener("click", function () {
                abrirEtapa(item[1]);
            });
        }
    });

    const guardar = $("guardarEquipamento");

    if (guardar) {
        guardar.addEventListener("click", function () {
            if (validarNovoEquipamentoCompleto()) {
                guardarAssociacoesNovoEquipamento();
                abrirEtapa("garantia-tab");
                mensagem("mensagemEquipamento", "Equipamento registado com sucesso. Documentos e contrato associados ao equipamento.", "green");
            }
        });
    }
}
/* Editar equipamento */

const validacoesEquipamentoEditar = {
    localizacao: [
        { id: "editPisoEquipamento", regra: "numeros", obrigatorio: true, msg: mensagensValidacao.piso },
        { id: "editResponsavelLocalizacaoEquipamento", regra: "letras", obrigatorio: true, msg: "O responsável da localização deve conter apenas letras." },
        { id: "editContactoLocalizacaoEquipamento", regra: "numeros", obrigatorio: true, msg: mensagensValidacao.contacto }
    ],
    fornecedor: [
        { id: "editContactoFornecedorEquipamento", regra: "letras", obrigatorio: true, msg: "A pessoa de contacto deve conter apenas letras." },
        { id: "editTelefoneFornecedorEquipamento", regra: "telefone", obrigatorio: true, msg: mensagensValidacao.telefone },
        { id: "editEmailFornecedorEquipamento", regra: "email", obrigatorio: true, msg: mensagensValidacao.email },
        { id: "editNifFornecedorEquipamento", regra: "nif", obrigatorio: true, msg: mensagensValidacao.nif }
    ]
};

aplicarFiltros([
    { id: "editPisoEquipamento", tipo: "numeros" },
    { id: "editResponsavelLocalizacaoEquipamento", tipo: "letras" },
    { id: "editContactoLocalizacaoEquipamento", tipo: "numeros" },
    { id: "editContactoFornecedorEquipamento", tipo: "letras" },
    { id: "editTelefoneFornecedorEquipamento", tipo: "telefone" },
    { id: "editNifFornecedorEquipamento", tipo: "numeros" }
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
        campos: ["editLocalizacao", "editEdificioEquipamento", "editPisoEquipamento", "editSalaEquipamento", "editResponsavelLocalizacaoEquipamento", "editContactoLocalizacaoEquipamento", "editEstadoLocalizacaoEquipamento"],
        validacao: validacoesEquipamentoEditar.localizacao
    },
    {
        tab: "edit-fornecedor-tab",
        erro: "Preencha todos os campos obrigatórios do Fornecedor.",
        campos: ["editFornecedorEquipamento", "editTipoFornecedorEquipamento", "editContactoFornecedorEquipamento", "editTelefoneFornecedorEquipamento", "editEmailFornecedorEquipamento", "editNifFornecedorEquipamento"],
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
    camposConteudoPublico.forEach(function (id) {
        const campo = $(id);
        const guardado = localStorage.getItem(id);

        if (campo && guardado) {
            campo.value = guardado;
        }
    });
}

function aplicarConteudosNoSitePublico() {
    ligacoesConteudoPublico.forEach(function (ligacao) {
        const elemento = $(ligacao[0]);
        const guardado = localStorage.getItem(ligacao[1]);

        if (elemento && guardado) {
            elemento.textContent = guardado;
        }
    });
}

const formConteudosPublicos = $("formConteudosPublicos");
const botaoReporConteudos = $("reporConteudosPublicos");

if (formConteudosPublicos) {
    carregarConteudosNoFormulario();

    formConteudosPublicos.addEventListener("submit", function (event) {
        event.preventDefault();

        if (!camposPreenchidos(camposConteudoPublico)) {
            mensagem("mensagemConteudosPublicos", "Preencha todos os campos antes de guardar.", "#10233f");
            return;
        }

        camposConteudoPublico.forEach(function (id) {
            localStorage.setItem(id, valor(id));
        });

        mensagem("mensagemConteudosPublicos", "Conteúdos guardados com sucesso. Abra o site público para visualizar as alterações.", "green");
    });
}

if (botaoReporConteudos) {
    botaoReporConteudos.addEventListener("click", function () {
        camposConteudoPublico.forEach(function (id) {
            localStorage.removeItem(id);
        });

        window.location.reload();
    });
}

aplicarConteudosNoSitePublico();
removerBotoesNovoEditarDocumentosContratos();
inserirDocumentosAssociadosNaListagem();
inserirContratosAssociadosNaListagem();

/* Tooltips Bootstrap */

document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function (el) {
    if (typeof bootstrap !== "undefined") {
        new bootstrap.Tooltip(el);
    }
});