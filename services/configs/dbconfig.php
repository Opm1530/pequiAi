<?php
$host = 'localhost';
$dbname = 'u986966016_pequiai';
$username = 'u986966016_pequiai';
$password = 'Ginanye.123$';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}
?>
