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
    fetchInstancesStatus();
});

// Opcional: Fechar o popup ao clicar fora da área do conteúdo
window.addEventListener("click", function(event) {
    if (event.target === popup) {
        popup.style.display = "none";
    }
});


document.getElementById("sendDataBtn").addEventListener("click", function() {
    const inputData = document.getElementById("name").value; // Pega o valor do input
    console.log(inputData)
    // Envia o dado via requisição POST para o PHP
    fetch("../../../../services/instances/instanceCreate.php", {
        method: "POST", // Método POST
        headers: {
            "Content-Type": "application/json" // Cabeçalho informando que estamos enviando JSON
        },
        body: JSON.stringify({ instanceName: inputData })// Altera para "instanceName" aqui
        
    })
    .then(response => response.json()) // Responde com JSON
    .then(responseData => {
        console.log("Resposta do PHP:", responseData);
            
        if (responseData.qrcode && responseData.qrcode.base64) {
            
            // Obtém o elemento da imagem com o id 'qrcode-image'
            const img = document.getElementById("qrcode-image");

            const elemento = document.getElementById("contentPopup");

            // Define o display como none
            elemento.style.display = "none";

            // Preenche o src com o base64 do QR Code
            img.src = responseData.qrcode.base64;

            // Opcionalmente, defina o texto alternativo (alt) se necessário
            img.alt = "QR Code";

        } else {
            console.log("QR Code não encontrado");
        }
        
    })
    .catch(error => {
        console.error("Erro ao enviar dados:", error);
    });
});

function fetchInstancesStatus() {
    // Realiza a requisição GET para o PHP
    fetch('../../../../services/instances/instanceList.php', {
        method: 'GET', // ou 'POST' se for necessário
    })
    .then(response => response.json()) // Converte a resposta para JSON
    .then(data => {
        if (data.status === 'success') {
        console.log(data.instances)
           displayInstances(data.instances)
        } else {
            console.error('Erro ao buscar as instâncias:', data.message);
        }
    })
    .catch(error => {
        console.error('Erro ao fazer a requisição:', error);
    });
}
function displayInstances(instances) {
    const tbody = document.querySelector('.table tbody'); // Seleciona o corpo da tabela
    tbody.innerHTML = ''; // Limpa o conteúdo anterior

    instances.forEach((instance, index) => {
        // Cria uma nova linha na tabela
        const row = document.createElement('tr');

        // Cria a célula para o índice da instância (Número)
        const cellIndex = document.createElement('th');
        cellIndex.setAttribute('scope', 'row');
        cellIndex.textContent = index + 1; // Incrementa para exibir o número da linha

        // Cria a célula para o nome da instância
        const cellName = document.createElement('td');
        cellName.textContent = instance.name;

        // Acessa o status dentro da estrutura correta
        const status = instance.status && instance.status.instance && instance.status.instance.state 
            ? instance.status.instance.state 
            : 'Desconhecido';
        
        // Cria a célula para o status da instância
        const cellStatus = document.createElement('td');
        cellStatus.textContent = status;

        // Adiciona as células à linha
        row.appendChild(cellIndex);
        row.appendChild(cellName);
        row.appendChild(cellStatus);

        // Adiciona a linha ao corpo da tabela
        tbody.appendChild(row);
    });
}
document.addEventListener('DOMContentLoaded', function() {
    fetchInstancesStatus();
});
