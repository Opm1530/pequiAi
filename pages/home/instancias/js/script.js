// Referências aos elementos
const openPopupBtn = document.getElementById("openPopupBtn");
const closePopupBtn = document.getElementById("closePopupBtn");
const popup = document.getElementById("popup");

// Função para abrir o popup
openPopupBtn.addEventListener("click", function() {
    popup.style.display = "flex"; // Torna o popup visível
});

// Função para fechar o popup
closePopupBtn.addEventListener("click", function() {
    popup.style.display = "none"; // Torna o popup invisível
});

// Opcional: Fechar o popup ao clicar fora da área do conteúdo
window.addEventListener("click", function(event) {
    if (event.target === popup) {
        popup.style.display = "none";
    }
});


// Função para enviar o dado para o PHP
document.getElementById("sendDataBtn").addEventListener("click", function() {

    const inputData = document.getElementById("name").value; // Pega o valor do input

    // Envia o dado via requisição POST para o PHP
    fetch("../../../../services/instances/instance.php", {
        method: "POST", // Método POST
        headers: {
            "Content-Type": "application/json" // Cabeçalho informando que estamos enviando JSON
        },
        body: JSON.stringify({ data: inputData }) // Envia o dado como JSON
    })
    .then(response => response.json()) // Responde com JSON
    .then(responseData => {
        console.log("Resposta do PHP:", responseData);
        alert("Dados enviados com sucesso!");
    })
    .catch(error => {
        console.error("Erro ao enviar dados:", error);
    });
});