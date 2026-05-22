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