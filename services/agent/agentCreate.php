<?php
session_start();
require '../configs/dbconfig.php';

header('Content-Type: application/json');

$response = [
    'status' => 'error',
    'mensagem' => 'Erro inesperado.'
];

try {
    if (!isset($_SESSION['user_id'])) {
        throw new Exception('Usuário não está logado.');
    }

    $data = json_decode(file_get_contents('php://input'), true);
    if (!isset($data['name'], $data['model'], $data['instructions'])) {
        throw new Exception('Dados incompletos recebidos.');
    }

    $name = htmlspecialchars(trim($data['name']));
    $model = htmlspecialchars(trim($data['model']));
    $instructions = htmlspecialchars(trim($data['instructions']));
    $userId = $_SESSION['user_id'];

    $query = "SELECT openai_key FROM users WHERE id = :user_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$user || empty($user['openai_key'])) {
        throw new Exception('Chave da OpenAI não encontrada para o usuário logado.');
    }

    $apiKey = $user['openai_key'];
    $url = 'https://api.openai.com/v1/assistants';
    $headers = [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $apiKey,
        'OpenAI-Beta: assistants=v2',
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        'instructions' => $instructions,
        'name' => $name,
        'tools' => [['type' => 'code_interpreter']],
        'model' => $model,
    ]));

    $responseCurl = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if ($responseCurl === false || $httpCode !== 200) {
        throw new Exception('Erro ao conectar com a OpenAI: ' . curl_error($ch));
    }

    curl_close($ch);

    $dataOpenAI = json_decode($responseCurl, true);
    if (!$dataOpenAI || !isset($dataOpenAI['data'])) {
        throw new Exception('Falha na conexão com a OpenAI, sem dados retornados.');
    }

    $response['status'] = 'success';
    $response['mensagem'] = 'Conexão bem-sucedida com a OpenAI!';
    $response['data'] = $dataOpenAI;

} catch (Exception $e) {
    $response['mensagem'] = $e->getMessage();
}

echo json_encode($response);
exit;
?>
