<?php
// Receber dados enviados via POST
$data = json_decode(file_get_contents("php://input"), true);

// Verifica se os dados necessários foram recebidos
if (isset($data['instanceName'])) {


    // Definindo os dados a serem enviados para a API
    $postData = array(
        "instanceName" => $data['instanceName'],
        "qrcode" => true,
        "integration" => "WHATSAPP-BAILEYS"
    );

    // URL da API para criação da instância
    $url = "https://pequix.online/instance/create";
    
    // Inicializa o cURL
    $ch = curl_init($url);

    // Definindo os cabeçalhos da requisição
    $headers = array(
        "Content-Type: application/json",
        "apikey: 429683C4C977415CAAFCCE10F7D57E11"
    );

    // Configurações do cURL
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Retornar o resultado como string
    curl_setopt($ch, CURLOPT_POST, true); // Definir método POST
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); // Adicionar cabeçalhos
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData)); // Enviar dados como JSON

    // Executar a requisição e obter a resposta
    $response = curl_exec($ch);
    
    // Verifica se ocorreu algum erro durante a requisição cURL
    if(curl_errno($ch)) {
        echo "Erro cURL: " . curl_error($ch);
    } else {
        // Exibe a resposta da API
        echo $response;
    }

    // Fecha a conexão cURL
    curl_close($ch);

} else {
    // Caso não tenha recebido os dados necessários
    echo json_encode(["status" => "error", "message" => $data['instanceName']]);
}
?>
