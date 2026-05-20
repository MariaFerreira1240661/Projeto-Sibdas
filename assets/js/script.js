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