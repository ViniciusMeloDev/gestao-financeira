<?php

$host = 'br1096.hostgator.com.br';
$dbname = 'vmsdig03_giggles';
$username = 'vmsdig03_giggles';
$password = 'gestao_financ'; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro ao conectar com o banco de dados: " . $e->getMessage());
}
?>
