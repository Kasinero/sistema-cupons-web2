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

if (!isset($_SESSION['usuario']) || $_SESSION['tipo'] != 'associado') {
    header("Location: login.php");
    exit;
}

$filtroCarteira = isset($_GET['f_carteira']) ? $_GET['f_carteira'] : 'ativos';
$buscaCategoria = isset($_GET['f_categoria']) ? $_GET['f_categoria'] : '';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Área do Associado - Cupons Leila</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <div class="nav">
            <div class="brand-logo">Cupons <span>Leila</span></div>
            <div class="user-info">
                <span>Olá, <strong><?php echo $_SESSION['usuario']['nom_associado']; ?></strong></span>
                <a href="logout.php" class="logout">Sair</a>
            </div>
        </div>

        <?php if(isset($_GET['msg']) && $_GET['msg'] == 'reservado'): ?>
            <div class="alert alert-success">🎉 <strong>Parabéns!</strong> Cupom reservado com sucesso.</div>
        <?php endif; ?>

        <div style="background: #fff; padding: 30px; border-radius: 16px; box-shadow: var(--shadow); border: 2px solid var(--accent); margin-bottom: 40px;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
                <h2 style="color: var(--accent-dark); margin:0;">🎒 Minha Carteira</h2>
                <form action="" method="GET">
                    <?php if($buscaCategoria) echo "<input type='hidden' name='f_categoria' value='$buscaCategoria'>"; ?>
                    <select name="f_carteira" onchange="this.form.submit()" style="padding:8px;">
                        <option value="ativos" <?php echo $filtroCarteira=='ativos'?'selected':''; ?>>Ativos (Para Usar)</option>
                        <option value="usados" <?php echo $filtroCarteira=='usados'?'selected':''; ?>>Já Usados</option>
                    </select>
                </form>
            </div>
            
            <div class="cupom-grid">
                <?php
                $cpf = $_SESSION['usuario']['cpf_associado'];
                // Adicionei o endereço aqui para ajudar o usuário
                $sqlMeu = "SELECT c.tit_cupom, c.num_cupom, com.nom_fantasia_comercio, com.end_comercio, com.bai_comercio, c.per_desc_cupom, c.dta_termino_cupom, r.dta_uso_cupom_associado 
                           FROM cupom_associado r
                           JOIN cupom c ON r.num_cupom = c.num_cupom
                           JOIN comercio com ON c.cnpj_comercio = com.cnpj_comercio
                           WHERE r.cpf_associado = :cpf";
                
                if ($filtroCarteira == 'ativos') {
                    $sqlMeu .= " AND r.dta_uso_cupom_associado IS NULL AND c.dta_termino_cupom >= CURDATE()";
                } else {
                    $sqlMeu .= " AND r.dta_uso_cupom_associado IS NOT NULL";
                }
                
                // Ordenação conforme RF007 (Data inicio DESC)
                $sqlMeu .= " ORDER BY c.dta_inicio_cupom DESC";

                $stmtMeu = $pdo->prepare($sqlMeu);
                $stmtMeu->execute([':cpf' => $cpf]);
                $meus = $stmtMeu->fetchAll(PDO::FETCH_ASSOC);

                if(count($meus) > 0) {
                    foreach ($meus as $meu) {
                        echo "<div class='cupom-card' style='border: 2px solid var(--accent);'>";
                        echo "<div class='cupom-header' style='background:var(--accent);'>";
                        echo "<h3>" . $meu['tit_cupom'] . "</h3>";
                        echo "<div style='font-size:0.9rem; opacity:0.9'>" . $meu['nom_fantasia_comercio'] . "</div></div>";
                        echo "<div class='cupom-body'>";
                        echo "<small>Código:</small><div class='cupom-code'>" . $meu['num_cupom'] . "</div>";
                        echo "<p style='font-size:0.8rem; color:var(--gray); margin: 5px 0;'>📍 " . $meu['end_comercio'] . ", " . $meu['bai_comercio'] . "</p>";
                        
                        if ($meu['dta_uso_cupom_associado']) {
                            echo "<strong style='color:gray'>JÁ UTILIZADO</strong>";
                        } else {
                            echo "<small style='color:red'>Vence: " . date('d/m', strtotime($meu['dta_termino_cupom'])) . "</small>";
                        }
                        echo "</div></div>";
                    }
                } else {
                    echo "<p style='color:var(--gray)'>Nenhum cupom neste filtro.</p>";
                }
                ?>
            </div>
        </div>

        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 20px;">
            <h2 style="margin:0;">🔥 Ofertas Disponíveis</h2>
            <form action="" method="GET" style="display:flex; gap:10px;">
                 <?php if($filtroCarteira) echo "<input type='hidden' name='f_carteira' value='$filtroCarteira'>"; ?>
                 <select name="f_categoria" style="padding:10px; width:200px;">
                    <option value="">Todas as Categorias</option>
                    <option value="Alimentação" <?php echo $buscaCategoria=='Alimentação'?'selected':''; ?>>Alimentação</option>
                    <option value="Vestuário" <?php echo $buscaCategoria=='Vestuário'?'selected':''; ?>>Vestuário</option>
                    <option value="Eletrônicos" <?php echo $buscaCategoria=='Eletrônicos'?'selected':''; ?>>Eletrônicos</option>
                    <option value="Serviços" <?php echo $buscaCategoria=='Serviços'?'selected':''; ?>>Serviços</option>
                    <option value="Diversos" <?php echo $buscaCategoria=='Diversos'?'selected':''; ?>>Diversos</option>
                </select>
                <button type="submit" style="padding:10px 20px;">Filtrar</button>
            </form>
        </div>

        <div class="cupom-grid">
            <?php
            $sql = "SELECT c.*, com.nom_fantasia_comercio, cat.nom_categoria as categoria_nome 
                    FROM cupom c 
                    JOIN comercio com ON c.cnpj_comercio = com.cnpj_comercio
                    JOIN categoria cat ON com.id_categoria = cat.id_categoria
                    WHERE c.num_cupom NOT IN (SELECT num_cupom FROM cupom_associado)
                    AND c.dta_termino_cupom >= CURDATE()";
            
            if (!empty($buscaCategoria)) {
                $sql .= " AND cat.nom_categoria = :cat";
            }
            
            // Ordenação conforme RF007 (Data inicio DESC)
            $sql .= " ORDER BY c.dta_inicio_cupom DESC LIMIT 20";
            
            $stmt = $pdo->prepare($sql);
            if (!empty($buscaCategoria)) {
                $stmt->execute([':cat' => $buscaCategoria]);
            } else {
                $stmt->execute();
            }
            
            $disponiveis = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($disponiveis) > 0) {
                foreach ($disponiveis as $cupom) {
                    echo "<div class='cupom-card'>";
                    echo "<div class='cupom-header'><h3>" . $cupom['tit_cupom'] . "</h3>";
                    echo "<span class='cupom-discount'>" . $cupom['per_desc_cupom'] . "% OFF</span></div>";
                    echo "<div class='cupom-body' style='text-align:left'>";
                    echo "<p style='margin:0; color:var(--primary); font-size:0.8rem; font-weight:bold;'>" . strtoupper($cupom['categoria_nome']) . "</p>";
                    echo "<p style='margin:5px 0 10px 0; color:var(--gray);'><strong>" . $cupom['nom_fantasia_comercio'] . "</strong></p>";
                    echo "<form action='../controllers/CupomController.php' method='POST'>";
                    echo "<input type='hidden' name='num_cupom' value='" . $cupom['num_cupom'] . "'>";
                    echo "<button type='submit' name='reservar_cupom' style='width:100%'>Reservar</button>";
                    echo "</form></div></div>";
                }
            } else {
                echo "<div class='card' style='grid-column: span 3; text-align:center;'>Nenhuma oferta encontrada nesta categoria.</div>";
            }
            ?>
        </div>
    </div>
</body>
</html>