<?php
session_start();

$raiz = $_SERVER['DOCUMENT_ROOT']; 
if (file_exists($raiz . '/conexao.php')) {
    require_once $raiz . '/conexao.php';
} elseif (file_exists($raiz . '/htdocs/conexao.php')) {
    require_once $raiz . '/htdocs/conexao.php';
} else {
    require_once '../conexao.php';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = preg_replace('/[^0-9]/', '', $_POST['login']);
    $senha = $_POST['senha'];

    try {
        if (strlen($login) == 11) {
            $sql = "SELECT * FROM associado WHERE cpf_associado = :login AND sen_associado = :senha";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':login' => $login, ':senha' => $senha]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                $_SESSION['usuario'] = $user;
                $_SESSION['tipo'] = 'associado';
                header("Location: ../views/dashboard_associado.php");
                exit;
            }
        } elseif (strlen($login) == 14) {
            $sql = "SELECT * FROM comercio WHERE cnpj_comercio = :login AND sen_comercio = :senha";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':login' => $login, ':senha' => $senha]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                $_SESSION['usuario'] = $user;
                $_SESSION['tipo'] = 'comercio';
                header("Location: ../views/dashboard_comercio.php");
                exit;
            }
        }

        header("Location: ../views/login.php?erro=1");
        
    } catch (PDOException $e) {
        die("Erro: " . $e->getMessage());
    }
}
?>