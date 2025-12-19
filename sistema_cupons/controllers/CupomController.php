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

function gerarHash($tamanho = 12) {
    return substr(bin2hex(random_bytes($tamanho)), 0, $tamanho);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['criar_cupom'])) {
    $titulo = $_POST['titulo'];
    $inicio = $_POST['inicio'];
    $fim = $_POST['fim'];
    $desconto = $_POST['desconto'];
    $qtd = $_POST['quantidade'];
    $cnpj = $_SESSION['usuario']['cnpj_comercio'];

    if (strtotime($inicio) > strtotime($fim)) {
        echo "<script>alert('Erro: A data de início não pode ser maior que a data de fim!'); window.location='../views/dashboard_comercio.php';</script>";
        exit;
    }

    for ($i = 0; $i < $qtd; $i++) {
        $hash = gerarHash();
        $sql = "INSERT INTO cupom (num_cupom, tit_cupom, cnpj_comercio, dta_emissao_cupom, dta_inicio_cupom, dta_termino_cupom, per_desc_cupom) 
                VALUES (:hash, :titulo, :cnpj, NOW(), :inicio, :fim, :desconto)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':hash' => $hash, ':titulo' => $titulo, ':cnpj' => $cnpj,
            ':inicio' => $inicio, ':fim' => $fim, ':desconto' => $desconto
        ]);
    }
    header("Location: ../views/dashboard_comercio.php?msg=criado");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reservar_cupom'])) {
    $num_cupom = $_POST['num_cupom'];
    
    if (!isset($_SESSION['usuario']['cpf_associado'])) {
        die("Erro: Acesso negado.");
    }

    $cpf = $_SESSION['usuario']['cpf_associado'];

    try {
        $check = $pdo->prepare("SELECT * FROM cupom_associado WHERE num_cupom = :num");
        $check->execute([':num' => $num_cupom]);
        
        if($check->rowCount() > 0) {
            die("Erro: Cupom ja reservado.");
        }

        $sql = "INSERT INTO cupom_associado (num_cupom, cpf_associado, dta_cupom_associado) 
                VALUES (:num, :cpf, NOW())";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':num' => $num_cupom, ':cpf' => $cpf]);
        
        header("Location: ../views/dashboard_associado.php?msg=reservado");
    } catch (PDOException $e) {
        die("Erro: " . $e->getMessage());
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['validar_uso'])) {
    $codigo = $_POST['codigo_cupom'];
    $cnpj = $_SESSION['usuario']['cnpj_comercio'];

    $sql = "SELECT c.num_cupom, ca.id_cupom_associado, ca.dta_uso_cupom_associado 
            FROM cupom c
            JOIN cupom_associado ca ON c.num_cupom = ca.num_cupom
            WHERE c.num_cupom = :cod AND c.cnpj_comercio = :cnpj";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':cod' => $codigo, ':cnpj' => $cnpj]);
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$resultado) {
        header("Location: ../views/dashboard_comercio.php?msg=erro_cupom");
    } elseif ($resultado['dta_uso_cupom_associado'] != null) {
        header("Location: ../views/dashboard_comercio.php?msg=ja_usado");
    } else {
        $update = $pdo->prepare("UPDATE cupom_associado SET dta_uso_cupom_associado = NOW() WHERE id_cupom_associado = :id");
        $update->execute([':id' => $resultado['id_cupom_associado']]);
        
        header("Location: ../views/dashboard_comercio.php?msg=sucesso_uso");
    }
    exit;
}
?>