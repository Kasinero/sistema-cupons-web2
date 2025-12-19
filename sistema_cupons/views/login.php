<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login - Cupons Leila</title>
    <link rel="stylesheet" href="../style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script>
        function mascaraLogin(i) {
            var v = i.value;
            
            if(isNaN(v[v.length-1])){
                i.value = v.substring(0, v.length-1);
                return;
            }
            
            var clean = v.replace(/\D/g, '');
            
            if (clean.length <= 11) {
                 v = clean.replace(/(\d{3})(\d)/, "$1.$2");
                 v = v.replace(/(\d{3})(\d)/, "$1.$2");
                 v = v.replace(/(\d{3})(\d{1,2})$/, "$1-$2");
            } else {
                 v = clean.replace(/^(\d{2})(\d)/, "$1.$2");
                 v = v.replace(/^(\d{2})\.(\d{3})(\d)/, "$1.$2.$3");
                 v = v.replace(/\.(\d{3})(\d)/, ".$1/$2");
                 v = v.replace(/(\d{4})(\d)/, "$1-$2");
            }
            
            i.value = v;
        }
    </script>
</head>
<body class="login-wrapper">
    <div class="login-card">
        <div class="login-header">
            <h1>Cupons<span>Leila</span></h1>
            <p style="color: var(--gray);">Conectando moradores e comércios.</p>
        </div>
        
        <?php if(isset($_GET['msg']) && $_GET['msg'] == 'criado'): ?>
            <div class="alert alert-success">✅ Conta criada com sucesso!</div>
        <?php endif; ?>
        <?php if(isset($_GET['erro'])): ?>
             <div class="alert alert-error">❌ Usuário ou senha incorretos.</div>
        <?php endif; ?>
        
        <form action="../controllers/AuthController.php" method="POST">
            <div>
                <label>Usuário (CPF ou CNPJ)</label>
                <input type="text" name="login" placeholder="Digite apenas números" required oninput="mascaraLogin(this)" maxlength="18">
            </div>
            
            <div>
                <label>Senha</label>
                <input type="password" name="senha" placeholder="Sua senha secreta" required>
            </div>
            
            <button type="submit">Entrar</button>
        </form>
        
        <div style="text-align: center; margin-top: 25px;">
            <a href="esqueci_senha.php" style="font-size: 0.9rem; color: var(--gray);">Esqueci minha senha</a>
            <div style="margin-top: 20px; border-top: 1px solid #eee; padding-top: 20px;">
                <p style="font-size: 0.9rem; margin-bottom: 10px;">Novo por aqui?</p>
                <a href="cadastro.php" style="color: var(--primary); font-weight: 700;">Criar Conta Grátis</a>
            </div>
        </div>
    </div>
</body>
</html>