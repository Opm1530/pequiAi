// Referências aos elementos
const openPopupBtn = document.getElementById("openPopupBtn");
const closePopupBtn = document.getElementById("closePopupBtn");
const popup = document.getElementById("popup");

const nextButtons = document.querySelectorAll(".nextStep");
const prevButtons = document.querySelectorAll(".prevStep");
const steps = document.querySelectorAll(".popup > div");
let currentStep = 0;

// Função para abrir o popup
openPopupBtn.addEventListener("click", function() {
    popup.style.display = "flex"; // Torna o popup visível
    showStep(currentStep); // Exibe a primeira etapa
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
