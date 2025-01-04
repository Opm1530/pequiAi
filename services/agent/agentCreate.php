<?php
session_start();
require '../configs/dbconfig.php';

try {
    // Verifica se o usuário está logado
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['status' => 'error', 'mensagem' => 'Usuário não está logado.']);
        exit;
    }

    $userId = $_SESSION['user_id']; // ID do usuário logado

    // Recebe os dados via POST
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['name']) && isset($data['model']) && isset($data['instructions'])) {
        $name = $data['name'];
        $model = $data['model'];
        $instructions = $data['instructions'];

        // Consulta SQL para verificar se a chave da OpenAI existe no banco
        $query = "SELECT openai_key FROM users WHERE id = :user_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);

        try {
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && !empty($user['openai_key'])) {
                // Chave da OpenAI encontrada, vamos testar a conexão com a API
                $apiKey = $user['openai_key'];

                // Fazendo a requisição para a OpenAI
                $url = 'https://api.openai.com/v1/assistants';
                $headers = [
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $apiKey,
                    'OpenAI-Beta: assistants=v2'
                ];

                // Usando cURL para fazer a requisição
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_TIMEOUT, 30);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
                    "instructions" => $instructions,
                    "name" => $name,
                    "tools" => [["type" => "code_interpreter"]],
                    "model" => $model
                ]));

                $response = curl_exec($ch);

                if ($response === false) {
                    $error = curl_error($ch);
                    curl_close($ch);
                    echo json_encode(['status' => 'error', 'mensagem' => 'Erro ao conectar com a OpenAI: ' . $error]);
                    exit;
                }

                curl_close($ch);

                // Decodificando a resposta da OpenAI
                $data = json_decode($response, true);

                if ($data && isset($data['data'])) {
                    echo json_encode([
                        'status' => 'success',
                        'mensagem' => 'Conexão bem-sucedida com a OpenAI!',
                        'response' => $data
                    ]);
                } else {
                    echo json_encode(['status' => 'error', 'mensagem' => 'Falha na conexão com a OpenAI, sem dados retornados.']);
                }
            } else {
                echo json_encode(['status' => 'error', 'mensagem' => 'Chave da OpenAI não encontrada para o usuário logado.']);
            }
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'mensagem' => 'Erro ao verificar a chave da OpenAI: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['status' => 'error', 'mensagem' => 'Dados incompletos recebidos.']);
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'mensagem' => 'Erro na conexão com o banco de dados: ' . $e->getMessage()]);
}
?>
