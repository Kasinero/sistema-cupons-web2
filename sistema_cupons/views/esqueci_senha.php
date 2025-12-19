<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Senha - Cupons Leila</title>
    <link rel="stylesheet" href="../style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body class="login-wrapper">
    <div class="login-card" style="max-width: 450px;">
        <div class="login-header">
            <h1>Recuperar <span>Senha</span></h1>
            <p style="color: var(--gray);">Digite seu e-mail para receber um link de redefinição.</p>
        </div>

        <form onsubmit="event.preventDefault(); alert('✅ Simulação: Um link de redefinição foi enviado para seu e-mail!'); window.location.href='login.php';">
            <div>
                <label>Seu E-mail Cadastrado:</label>
                <input type="email" placeholder="exemplo@email.com" required>
            </div>

            <button type="submit">Enviar Link de Recuperação</button>
        </form>
        
        <div style="text-align: center; margin-top: 25px;">
            <a href="login.php" style="color: var(--primary); font-weight: 700;">Voltar para o Login</a>
        </div>
    </div>
</body>
</html>