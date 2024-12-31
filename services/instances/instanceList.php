<?php

session_start();

// Verifica se o ID do usuário está na sessão
$userId = $_SESSION['user_id'] ?? null;

if (!$userId) {
    echo json_encode(["status" => "error", "message" => "Usuário não autenticado."]);
    exit;
}

// Inicializa o PDO (certifique-se de ter configurado corretamente)
require 'db_connection.php'; // Inclua aqui o arquivo que inicializa o $pdo

// Função para fazer a requisição cURL
function getInstanceStatus($instanceId) {
    $url = "https://pequix.online/instance/connectionState/{$instanceId}";
    $headers = [
        "apikey: 429683C4C977415CAAFCCE10F7D57E11"
    ];

    // Inicializa o cURL
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    // Executa a requisição e captura a resposta
    $response = curl_exec($ch);

    // Verifica se ocorreu algum erro no cURL
    if (curl_errno($ch)) {
        return ['status' => 'error', 'message' => curl_error($ch)];
    }

    curl_close($ch);

    // Retorna a resposta da API, assumindo que ela esteja no formato JSON
    return json_decode($response, true);
}

try {
    // Busca todas as instâncias do usuário no banco de dados
    $stmt = $pdo->prepare('SELECT * FROM instances WHERE user_id = :user_id');
    $stmt->execute(['user_id' => $userId]);

    $instances = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($instances)) {
        echo json_encode(["status" => "error", "message" => "Nenhuma instância encontrada."]);
        exit;
    }

    // Array para armazenar os dados das instâncias com o status
    $instancesWithStatus = [];

    // Loop através das instâncias para buscar o status de cada uma
    foreach ($instances as $instance) {
        $instanceId = $instance['instances_id'];

        // Requisição para pegar o status da instância
        $status = getInstanceStatus($instanceId);

        // Adiciona o status à instância
        $instance['status'] = $status;

        // Adiciona a instância com o status ao array final
        $instancesWithStatus[] = $instance;
    }

    // Retorna as instâncias com seus status em formato JSON
    echo json_encode(["status" => "success", "instances" => $instancesWithStatus]);

} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Erro ao buscar instâncias no banco de dados.", "error" => $e->getMessage()]);
    exit;
}
?>
