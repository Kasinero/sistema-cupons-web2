<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$raiz = $_SERVER['DOCUMENT_ROOT']; 
if (file_exists($raiz . '/conexao.php')) {
    require_once $raiz . '/conexao.php';
} elseif (file_exists($raiz . '/htdocs/conexao.php')) {
    require_once $raiz . '/htdocs/conexao.php';
} else {
    require_once '../conexao.php';
}

$cats = $pdo->query("SELECT * FROM categoria")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Criar Conta Completa - Cupons Leila</title>
    <link rel="stylesheet" href="../style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script>
        function mascaraCPF(i) {
            var v = i.value;
            if(isNaN(v[v.length-1])){
               i.value = v.substring(0, v.length-1);
               return;
            }
            i.setAttribute("maxlength", "14");
            if (v.length == 3 || v.length == 7) i.value += ".";
            if (v.length == 11) i.value += "-";
        }

        function mascaraCNPJ(i) {
            var v = i.value;
            if(isNaN(v[v.length-1])){
               i.value = v.substring(0, v.length-1);
               return;
            }
            i.setAttribute("maxlength", "18");
            if (v.length == 2 || v.length == 6) i.value += ".";
            if (v.length == 10) i.value += "/";
            if (v.length == 15) i.value += "-";
        }

        function mascaraTelefone(i) {
            var v = i.value;
            if(isNaN(v[v.length-1])){
               i.value = v.substring(0, v.length-1);
               return;
            }
            i.setAttribute("maxlength", "15");
            if (v.length == 1) i.value = "(" + i.value;
            if (v.length == 3) i.value += ") ";
            if (v.length == 10) i.value += "-";
        }

        function mascaraCEP(i) {
            var v = i.value;
            if(isNaN(v[v.length-1])){
               i.value = v.substring(0, v.length-1);
               return;
            }
            i.setAttribute("maxlength", "9");
            if (v.length == 5) i.value += "-";
        }
        function mudarTipo(tipo) {
            var inputDoc = document.getElementById('inputDoc');
            inputDoc.value = ""

            if (tipo === 'associado') {
                document.getElementById('labelDoc').innerText = 'CPF:';
                inputDoc.placeholder = '000.000.000-00';
                inputDoc.oninput = function() { mascaraCPF(this) };

                document.getElementById('labelNome').innerText = 'Nome Completo:';
                document.getElementById('divAssociado').style.display = 'block';
                document.getElementById('divComercio').style.display = 'none';
            } else {
                document.getElementById('labelDoc').innerText = 'CNPJ:';
                inputDoc.placeholder = '00.000.000/0000-00';
                inputDoc.oninput = function() { mascaraCNPJ(this) }; 

                document.getElementById('labelNome').innerText = 'Razão Social:';
                document.getElementById('divAssociado').style.display = 'none';
                document.getElementById('divComercio').style.display = 'block';
            }
        }
    </script>
</head>
<body class="login-wrapper">
    <div class="login-card" style="max-width: 600px; margin: 20px;">
        <div class="login-header">
            <h1>Cupons<span>Leila</span></h1>
            <p style="color: var(--gray);">Cadastro Completo</p>
        </div>

        <form action="../controllers/CadastroController.php" method="POST">
            
            <div style="display:flex; justify-content:center; gap: 20px; background: #f8f9fd; padding: 15px; border-radius: 12px;">
                <label style="cursor:pointer; display:flex; align-items:center; gap:5px;">
                    <input type="radio" name="tipo" value="associado" checked onclick="mudarTipo('associado')"> Sou Associado
                </label>
                <label style="cursor:pointer; display:flex; align-items:center; gap:5px;">
                    <input type="radio" name="tipo" value="comercio" onclick="mudarTipo('comercio')"> Sou Comércio
                </label>
            </div>

            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:15px;">
                <div>
                    <label id="labelDoc">CPF:</label>
                    <input type="text" name="documento" id="inputDoc" required oninput="mascaraCPF(this)">
                </div>
                <div>
                    <label id="labelNome">Nome Completo:</label>
                    <input type="text" name="nome" required>
                </div>
            </div>

            <div id="divAssociado">
                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:15px;">
                    <div>
                        <label>Data de Nascimento:</label>
                        <input type="date" name="dtn_associado">
                    </div>
                    <div>
                        <label>Celular:</label>
                        <input type="text" name="cel_associado" placeholder="(00) 00000-0000" oninput="mascaraTelefone(this)">
                    </div>
                </div>
            </div>

            <div id="divComercio" style="display:none;">
                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:15px;">
                    <div>
                        <label>Nome Fantasia:</label>
                        <input type="text" name="nom_fantasia">
                    </div>
                    <div>
                        <label>Telefone Contato:</label>
                        <input type="text" name="con_comercio" placeholder="(00) 0000-0000" oninput="mascaraTelefone(this)">
                    </div>
                </div>
                <div style="margin-top:10px;">
                    <label>Categoria:</label>
                    <select name="id_categoria" style="width:100%;">
                        <?php foreach($cats as $c): ?>
                            <option value="<?php echo $c['id_categoria']; ?>"><?php echo $c['nom_categoria']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <h3 style="margin-top:20px; border-bottom:1px solid #eee;">📍 Endereço</h3>
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:15px;">
                <div>
                    <label>CEP:</label>
                    <input type="text" name="cep" placeholder="00000-000" oninput="mascaraCEP(this)">
                </div>
                <div>
                    <label>UF:</label>
                    <input type="text" name="uf" placeholder="Ex: SP" maxlength="2" style="text-transform: uppercase;">
                </div>
            </div>
            <div style="display:grid; grid-template-columns: 2fr 1fr; gap:15px;">
                <input type="text" name="cidade" placeholder="Cidade">
                <input type="text" name="bairro" placeholder="Bairro">
            </div>
            <input type="text" name="endereco" placeholder="Rua, Número e Complemento">

            <h3 style="margin-top:20px; border-bottom:1px solid #eee;">🔒 Acesso</h3>
            <input type="email" name="email" placeholder="E-mail" required>
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:15px;">
                <input type="password" name="senha" placeholder="Senha" required>
                <input type="password" name="confirma_senha" placeholder="Confirmar" required>
            </div>

            <button type="submit" name="cadastrar" style="margin-top:10px;">CADASTRAR COMPLETO</button>
        </form>
        
        <div style="text-align: center; margin-top: 15px;">
            <a href="login.php" style="color: var(--primary);">Voltar para Login</a>
        </div>
    </div>
</body>
</html>