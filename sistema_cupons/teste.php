<?php
// Exibir erros na tela
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Iniciando Teste de Conexão via Include...</h1>";

try {
    // Tenta puxar o arquivo de conexão original
    if (file_exists('conexao.php')) {
        require_once 'conexao.php'; // Isso carrega suas variáveis $host, $user, $pass do outro arquivo
        
        // Verifica se a conexão foi criada (geralmente a variável é $pdo ou $conn)
        if (isset($pdo)) {
            echo "<h2 style='color:green;'>✅ SUCESSO! O arquivo conexao.php está funcionando.</h2>";
        } else {
            echo "<h2 style='color:orange;'>⚠️ O arquivo foi carregado, mas a variável de conexão não foi encontrada.</h2>";
        }
    } else {
        echo "<h2 style='color:red;'>❌ Erro: O arquivo conexao.php não foi encontrado na mesma pasta.</h2>";
    }
} catch (Exception $e) {
    echo "<h2 style='color:red;'>❌ Erro: " . $e->getMessage() . "</h2>";
}
?>