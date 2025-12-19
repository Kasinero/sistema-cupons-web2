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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cadastrar'])) {
    
    $tipo = $_POST['tipo'];
    $documento = preg_replace('/[^0-9]/', '', $_POST['documento']);
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $confirma = $_POST['confirma_senha'];

    $end = $_POST['endereco'];
    $bai = $_POST['bairro'];
    $cep = $_POST['cep'];
    $cid = $_POST['cidade'];
    $uf  = $_POST['uf'];

    if ($senha !== $confirma) {
        echo "<script>alert('Senhas não conferem!'); window.location='../views/cadastro.php';</script>";
        exit;
    }

    try {
        if ($tipo == 'associado') {
            if (strlen($documento) != 11) die("CPF invalido.");

            $dtn = $_POST['dtn_associado'];
            $cel = $_POST['cel_associado'];

            $sql = "INSERT INTO associado (
                        cpf_associado, nom_associado, dtn_associado, 
                        end_associado, bai_associado, cep_associado, cid_associado, uf_associado, 
                        cel_associado, email_associado, sen_associado
                    ) VALUES (
                        :doc, :nome, :dtn, 
                        :end, :bai, :cep, :cid, :uf, 
                        :cel, :email, :senha
                    )";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':doc' => $documento, ':nome' => $nome, ':dtn' => $dtn,
                ':end' => $end, ':bai' => $bai, ':cep' => $cep, ':cid' => $cid, ':uf' => $uf,
                ':cel' => $cel, ':email' => $email, ':senha' => $senha
            ]);

        } else {
            if (strlen($documento) != 14) die("CNPJ invalido.");

            $fantasia = $_POST['nom_fantasia'];
            $contato = $_POST['con_comercio'];
            $id_cat = $_POST['id_categoria'];

            $sql = "INSERT INTO comercio (
                        cnpj_comercio, id_categoria, raz_social_comercio, nom_fantasia_comercio,
                        end_comercio, bai_comercio, cep_comercio, cid_comercio, uf_comercio,
                        con_comercio, email_comercio, sen_comercio
                    ) VALUES (
                        :doc, :idcat, :raz, :fan,
                        :end, :bai, :cep, :cid, :uf,
                        :con, :email, :senha
                    )";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':doc' => $documento, ':idcat' => $id_cat, ':raz' => $nome, ':fan' => $fantasia,
                ':end' => $end, ':bai' => $bai, ':cep' => $cep, ':cid' => $cid, ':uf' => $uf,
                ':con' => $contato, ':email' => $email, ':senha' => $senha
            ]);
        }

        header("Location: ../views/login.php?msg=criado");

    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            echo "<script>alert('Erro: CPF ou CNPJ ja cadastrado!'); window.location='../views/cadastro.php';</script>";
        } else {
            die("Erro SQL: " . $e->getMessage());
        }
    }
}
?>