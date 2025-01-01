<?php
session_start();
require '../configs/dbconfig.php';

try {
    // Conexão com o banco de dados
    $db = new PDO($dsn, $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verifica se o usuário está logado
    session_start();
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['status' => 'erro', 'mensagem' => 'Usuário não está logado.']);
        exit;
    }

    $userId = $_SESSION['user_id']; // ID do usuário logado

    // Consulta SQL para verificar se a chave da OpenAI existe no banco
    $query = "SELECT openai_key FROM users WHERE id = :user_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);

    try {
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && !empty($user['openai_api_key'])) {
            echo json_encode(['status' => 'sucesso', 'mensagem' => 'Chave da OpenAI encontrada.']);
        } else {
            echo json_encode(['status' => 'erro', 'mensagem' => 'Chave da OpenAI não encontrada para o usuário logado.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'erro', 'mensagem' => 'Erro ao verificar a chave da OpenAI: ' . $e->getMessage()]);
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Erro na conexão com o banco de dados: ' . $e->getMessage()]);
}
?>
