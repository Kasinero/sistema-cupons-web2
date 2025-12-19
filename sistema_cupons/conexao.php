<?php
$host = 'sql308.byetcluster.com';
$dbname = 'if0_40715359_cupons';
$user = 'if0_40715359';
$pass = 'Leila2025Unaerp';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexao com o banco: " . $e->getMessage());
}
?>