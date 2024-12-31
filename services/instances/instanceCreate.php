<?php
require '../configs/dbconfig.php';
session_start();

// Verifica se o ID do usuário está na sessão
$userId = $_SESSION['user_id'] ?? null;

if (!$userId) {
    echo json_encode(["status" => "error", "message" => "Usuário não autenticado."]);
    exit;
}
// Receber dados enviados via POST
$data = json_decode(file_get_contents("php://input"), true);

// Verifica se os dados necessários foram recebidos
if (isset($data['instanceName'])) {

    $stmt = $pdo->prepare('INSERT INTO instances (name,user_id,instances_id) VALUES (:name, :user_id, :instances_id)');

    function generateHashToken($length = 64) {
        $bytes = openssl_random_pseudo_bytes($length / 2);
        return hash('sha256', $bytes);
    }
    
    
    $token = generateHashToken(); 

    

    if ($stmt->execute(['name' => $data['instanceName'],'user_id' => $userId, 'instances_id' => $token])) {
        
        // Definindo os dados a serem enviados para a API
        $postData = array(
            "instanceName" => $token,
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
        echo 'Erro ao registrar usuário. Tente novamente.';
    }
    

} else {
    // Caso não tenha recebido os dados necessários
    echo json_encode(["status" => "error", "message" => $data['instanceName']]);
}
?>
