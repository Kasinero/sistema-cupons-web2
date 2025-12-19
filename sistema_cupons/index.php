<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Bem-vindo - Cupons Leila</title>
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .hero {
            text-align: center;
            padding: 80px 20px;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .hero h1 {
            font-size: 3.5rem;
            line-height: 1.1;
            margin-bottom: 20px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero p {
            font-size: 1.2rem;
            color: var(--gray);
            margin-bottom: 40px;
        }

        .btn-group {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-outline {
            background: transparent;
            border: 2px solid var(--primary);
            color: var(--primary);
        }
        .btn-outline:hover {
            background: var(--primary);
            color: white;
            box-shadow: 0 5px 15px rgba(108, 92, 231, 0.3);
        }

        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            max-width: 1000px;
            margin: 50px auto;
            padding: 0 20px;
        }

        .feature-card {
            background: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            text-align: center;
            transition: 0.3s;
        }
        .feature-card:hover {
            transform: translateY(-10px);
        }

        .icon {
            font-size: 3rem;
            margin-bottom: 15px;
            display: block;
        }
    </style>
</head>
<body style="background: linear-gradient(180deg, #f8f9fd 0%, #ffffff 100%);">

    <div class="nav" style="max-width: 1000px; margin: 20px auto; background: rgba(255,255,255,0.8); backdrop-filter: blur(10px);">
        <div class="brand-logo">Cupons <span>Leila</span></div>
    </div>

    <div class="hero">
        <h1>Sistema de Cupons de Desconto</h1>
        <p>Conectando moradores aos melhores comércios do bairro de forma simples e segura.</p>
        
        <div class="btn-group">
            <a href="views/cadastro.php">
                <button>Criar Minha Conta</button>
            </a>
            <a href="views/login.php">
                <button class="btn-outline">Já tenho conta</button>
            </a>
        </div>
    </div>

    <div class="features">
        <div class="feature-card">
            <span class="icon">🏘️</span>
            <h3>Para Moradores</h3>
            <p>Encontre promoções exclusivas perto da sua casa e reserve com um clique.</p>
        </div>
        <div class="feature-card">
            <span class="icon">🏪</span>
            <h3>Para Lojas</h3>
            <p>Crie cupons em segundos, atraia novos clientes e valide tudo pelo sistema.</p>
        </div>
        <div class="feature-card">
            <span class="icon">🔒</span>
            <h3>Segurança Total</h3>
            <p>Cada cupom gera um código único que impede fraudes e duplicidade.</p>
        </div>
    </div>

    <footer style="text-align: center; padding: 40px; color: var(--gray); font-size: 0.9rem;">
        Relatório Final da Prática Extensionista - Desenvolvido por Fernando Biadelli (839676) e Bryan Abner Santos (769760)
    </footer>

</body>
</html>