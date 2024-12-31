<?php
require '../configs/dbconfig.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $whatsapp = $_POST['whatsapp'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $repeatPassword = $_POST['repeatPassword'];

    if ($password != $repeatPassword) {
        echo 'Senhas não conferem';
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo 'E-mail inválido. Por favor, insira um e-mail válido.';
        exit;
    }

    // Verifica se o usuário já existe
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email');
    $stmt->execute(['email' => $email]);
    $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existingUser) {
        echo 'Nome de usuário já está em uso. Escolha outro.';
        exit;
    }

    // Insere o novo usuário com a senha hash
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare('INSERT INTO users (name,email,number,password) VALUES (:name, :email,:number,:password)');
    
    if ($stmt->execute(['name' => $name,'email' => $email, 'number' => $whatsapp, 'password' => $hashedPassword])) {
        echo 'Registro realizado com sucesso! <a href="index.php">Faça login</a>.';
    } else {
        echo 'Erro ao registrar usuário. Tente novamente.';
    }
}
?>
