// Referências aos elementos
const openPopupBtn = document.getElementById("openPopupBtn");
const closePopupBtn = document.getElementById("closePopupBtn");
const sendDataButton = document.getElementById("sendDataButton");

const popup = document.getElementById("popup");
const checkAPIButton = document.getElementById("checkAPIButton");
const nextButtons = document.querySelectorAll(".nextStep");
const prevButtons = document.querySelectorAll(".prevStep");

const steps = document.querySelectorAll(".popup > div");
let currentStep = 0;

// Função para abrir o popup
openPopupBtn.addEventListener("click", function() {
    popup.style.display = "flex"; // Torna o popup visível
    showStep(currentStep); // Exibe a primeira etapa
    checkAPI()
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

// Função para exibir a etapa atual e esconder as outras
function showStep(stepIndex) {
    steps.forEach((step, index) => {
        step.style.display = (index === stepIndex) ? "block" : "none";
    });
}

// Adicionar eventos para "Avançar"
nextButtons.forEach(button => {
    button.addEventListener("click", function() {
        if (currentStep < steps.length - 1) {
            currentStep++;
            showStep(currentStep);
        }
    });
});

// Adicionar eventos para "Voltar"
prevButtons.forEach(button => {
    button.addEventListener("click", function() {
        if (currentStep > 0) {
            currentStep--;
            showStep(currentStep);
        }
    });
});





async function checkAPI() {
    const divInputApi = document.getElementById("dontHaveApi");
    const divHaveApi = document.getElementById("haveApi");
    const divLoading = document.getElementById("loading");
    divLoading.style.display = "flex";

    try {
        const response = await fetch('../../../../services/agent/apiKeyCheck.php');
        

        // Converte a resposta para JSON
        const data = await response.json();
        console.log(data);
        // Verifica se o status retornado é "success"
        if (data.status !== "success") {
            divInputApi.style.display = "flex";
            divHaveApi.style.display = "none";
            divLoading.style.display = "none";
            throw new Error(`Erro: ${response.status} - ${response.statusText}`);
        }

        divInputApi.style.display = "none";
        divHaveApi.style.display = "flex";
        divLoading.style.display = "none";
    } catch (error) {
        divInputApi.style.display = "flex";
        divHaveApi.style.display = "none";
        divLoading.style.display = "none";
        console.error('Erro ao buscar os dados:', error);
    }
};


sendDataButton.addEventListener("click", async function() {
    const name = document.getElementById('nameAgent').value;  // Pega o valor do campo 'nameAgent'
    const model = document.getElementById('typeAgent').value;  // Pega o valor do campo 'typeAgent'
    const instructions = document.getElementById('instructionsAgent').value;  // Pega o valor do campo 'instructionsAgent'
    
    try {
        // Envia os dados para o PHP via POST
        const response = await fetch('../../../../services/agent/agentCreate.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                name: name,
                model: model,
                instructions: instructions
            })
        });

        // Verifica se a resposta foi bem-sucedida
        if (!response.ok) {
            throw new Error(`Erro: ${response.status} - ${response.statusText}`);
        }

        // Converte a resposta para JSON
        const data = await response.json();
        console.log(data); // Exibe os dados no console

        // Exemplo: Verifica se o status é "success" e atualiza a interface
        if (data.status === "success") {
            console.log('Sucesso:', data.mensagem);
        } else {
            console.error('Erro:', data.mensagem);
        }
    } catch (error) {
        console.error('Erro ao enviar os dados:', error);
    }
});



