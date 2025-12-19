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

if (!isset($_SESSION['usuario']) || $_SESSION['tipo'] != 'comercio') {
    header("Location: login.php");
    exit;
}
$cnpj = $_SESSION['usuario']['cnpj_comercio'];

$filtro = isset($_GET['filtro']) ? $_GET['filtro'] : 'ativos';
$sql = "SELECT * FROM cupom WHERE cnpj_comercio = :cnpj";

if ($filtro == 'ativos') {
    $sql .= " AND dta_termino_cupom >= CURDATE() ORDER BY dta_inicio_cupom DESC, tit_cupom ASC";
} elseif ($filtro == 'vencidos') {
    $sql .= " AND dta_termino_cupom < CURDATE() ORDER BY dta_inicio_cupom DESC, tit_cupom ASC";
} elseif ($filtro == 'utilizados') {
    $sql = "SELECT c.* FROM cupom c 
            JOIN cupom_associado ca ON c.num_cupom = ca.num_cupom 
            WHERE c.cnpj_comercio = :cnpj 
            AND ca.dta_uso_cupom_associado IS NOT NULL 
            ORDER BY ca.dta_uso_cupom_associado DESC";
} else {
    $sql .= " ORDER BY dta_inicio_cupom DESC, tit_cupom ASC";
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel da Loja - Cupons Leila</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <div class="nav">
            <div class="brand-logo">Cupons <span>Leila</span></div>
            <div class="user-info">
                <span>Olá, <strong><?php echo $_SESSION['usuario']['nom_fantasia_comercio']; ?></strong></span>
                <a href="logout.php" class="logout">Sair</a>
            </div>
        </div>

        <?php if(isset($_GET['msg'])): ?>
            <?php if($_GET['msg'] == 'sucesso_uso'): ?>
                <div class="alert alert-success">✅ <strong>Sucesso!</strong> Cupom validado e baixado.</div>
            <?php elseif($_GET['msg'] == 'ja_usado'): ?>
                <div class="alert alert-error">⚠️ <strong>Cuidado:</strong> Cupom JÁ UTILIZADO antes!</div>
            <?php elseif($_GET['msg'] == 'erro_cupom'): ?>
                <div class="alert alert-info">❌ <strong>Erro:</strong> Cupom inválido ou não encontrado.</div>
            <?php elseif($_GET['msg'] == 'criado'): ?>
                <div class="alert alert-success">📢 Promoção publicada com sucesso!</div>
            <?php endif; ?>
        <?php endif; ?>

        <div class="card" style="border-left: 5px solid var(--accent);">
            <h2 style="color: var(--accent-dark);">✅ Validar Cupom</h2>
            <form action="../controllers/CupomController.php" method="POST" style="flex-direction: row; flex-wrap: wrap;">
                <input type="text" name="codigo_cupom" placeholder="Código (Ex: a1b2c3...)" style="flex: 2; min-width: 200px;" required>
                <button type="submit" name="validar_uso" style="flex: 1; background: var(--accent);">Validar Agora</button>
            </form>
        </div>

        <div class="card">
            <h2>📢 Nova Promoção</h2>
            <form action="../controllers/CupomController.php" method="POST">
                <input type="text" name="titulo" placeholder="Título (Ex: 50% OFF na Pizza)" required>
                <div style="display:flex; gap:20px; flex-wrap:wrap;">
                     <div style="flex:1"><label>Início</label><input type="date" name="inicio" required></div>
                     <div style="flex:1"><label>Fim</label><input type="date" name="fim" required></div>
                     <div style="flex:1"><label>% Desconto</label><input type="number" name="desconto" required></div>
                     <div style="flex:1"><label>Qtd.</label><input type="number" name="quantidade" required></div>
                </div>
                <button type="submit" name="criar_cupom">Gerar Cupons</button>
            </form>
        </div>

        <div style="display:flex; justify-content:space-between; align-items:center; margin-top:40px; border-bottom: 2px solid #eee; padding-bottom: 10px;">
            <h2>🎫 Meus Cupons</h2>
            <form action="" method="GET" style="flex-direction:row; gap:10px;">
                <select name="filtro" onchange="this.form.submit()" style="padding:10px;">
                    <option value="ativos" <?php echo $filtro=='ativos'?'selected':''; ?>>Ativos</option>
                    <option value="vencidos" <?php echo $filtro=='vencidos'?'selected':''; ?>>Vencidos</option>
                    <option value="utilizados" <?php echo $filtro=='utilizados'?'selected':''; ?>>Já Utilizados</option>
                    <option value="todos" <?php echo $filtro=='todos'?'selected':''; ?>>Todos</option>
                </select>
            </form>
        </div>
        
        <div class="cupom-grid" style="margin-top: 20px;">
            <?php
            $stmt = $pdo->prepare($sql . " LIMIT 20");
            if ($filtro != 'utilizados') {
                $stmt->execute([':cnpj' => $cnpj]);
            } else {
                $stmt->execute([':cnpj' => $cnpj]);
            }
            $cupons = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($cupons) > 0) {
                foreach ($cupons as $cupom) {
                    echo "<div class='cupom-card'>";
                    echo "<div class='cupom-header'><h3>" . $cupom['tit_cupom'] . "</h3>";
                    echo "<span class='cupom-discount'>" . $cupom['per_desc_cupom'] . "% OFF</span></div>";
                    echo "<div class='cupom-body'><div class='cupom-code'>" . $cupom['num_cupom'] . "</div>";
                    
                    if ($filtro == 'utilizados') {
                         echo "<small style='color:green;'>Utilizado</small>";
                    } else {
                         echo "<small>Vence: " . date('d/m/Y', strtotime($cupom['dta_termino_cupom'])) . "</small>";
                    }
                    echo "</div></div>";
                }
            } else {
                echo "<p style='color:var(--gray);'>Nenhum cupom encontrado neste filtro.</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>