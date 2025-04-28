<?php
// Conectar ao banco de dados (usando PDO para PostgreSQL)
try {
    $pdo = new PDO('pgsql:host=localhost;dbname=seu_banco', 'usuario', 'senha');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erro ao conectar ao banco de dados: " . $e->getMessage();
    exit;
}

// Verificar se 'id' ou 'slug' foi passado na URL
if (isset($_GET['id'])) {
    $categoria_id = $_GET['id'];
    $sqlCategoria = "SELECT * FROM categorias WHERE id = :id";
} elseif (isset($_GET['slug'])) {
    $slug = $_GET['slug'];
    $sqlCategoria = "SELECT * FROM categorias WHERE slug = :slug";
} else {
    echo "Categoria não especificada!";
    exit;
}

// Preparar e executar a consulta para pegar os dados da categoria
$stmt = $pdo->prepare($sqlCategoria);
if (isset($categoria_id)) {
    $stmt->bindParam(':id', $categoria_id, PDO::PARAM_INT);
} elseif (isset($slug)) {
    $stmt->bindParam(':slug', $slug, PDO::PARAM_STR);
}
$stmt->execute();

$categoria = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$categoria) {
    echo "Categoria não encontrada!";
    exit;
}

// Consultar todos os itens dessa categoria
$sqlItens = "SELECT * FROM itens WHERE categoria_id = :categoria_id";
$stmtItens = $pdo->prepare($sqlItens);
$stmtItens->bindParam(':categoria_id', $categoria['id'], PDO::PARAM_INT);
$stmtItens->execute();

$itens = $stmtItens->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categoria: <?php echo htmlspecialchars($categoria['nome']); ?></title>
</head>
<body>

<h1>Categoria: <?php echo htmlspecialchars($categoria['nome']); ?></h1>
<p><?php echo nl2br(htmlspecialchars($categoria['descricao'])); ?></p>

<h2>Itens dessa categoria:</h2>

<?php if (count($itens) > 0): ?>
    <ul>
        <?php foreach ($itens as $item): ?>
            <li>
                <a href="item.php?id=<?php echo $item['id']; ?>">
                    <?php echo htmlspecialchars($item['nome']); ?>
                </a> - R$ <?php echo number_format($item['preco'], 2, ',', '.'); ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Não há itens disponíveis nesta categoria.</p>
<?php endif; ?>

</body>
</html>
