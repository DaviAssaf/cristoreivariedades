<?php
session_start();

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'administrador') {
    header("Location: ../pagina.principal.php?error=acesso_negado");
    exit();
}

include '../conexao/conexao.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "SELECT * FROM estoque WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        die("Produto não encontrado.");
    }

    $produto = $result->fetch_assoc();
} else {
    die("ID do produto não especificado.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco_original = floatval($_POST['preco_original']);
    $preco_atual = floatval($_POST['preco_atual']);
    $quantidade = intval($_POST['quantidade']);
    $categoria = $_POST['categoria'];
    $fornecedor = $_POST['fornecedor'];
    $estado = $_POST['estado'];

    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $imagemContent = file_get_contents($_FILES['imagem']['tmp_name']);
        $query = "UPDATE estoque SET nome = ?, imagem = ?, descricao = ?, preco_original = ?, preco_atual = ?, quantidade = ?, categoria = ?, fornecedor = ?, estado = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('sbssddissi', $nome, $imagemContent, $descricao, $preco_original, $preco_atual, $quantidade, $categoria, $fornecedor, $estado, $id);
        $stmt->send_long_data(1, $imagemContent);
    } else {
        $query = "UPDATE estoque SET nome = ?, descricao = ?, preco_original = ?, preco_atual = ?, quantidade = ?, categoria = ?, fornecedor = ?, estado = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ssddissi', $nome, $descricao, $preco_original, $preco_atual, $quantidade, $categoria, $fornecedor, $estado, $id);
    }

    if ($stmt->execute()) {
        header("Location: produtos.php");
        exit();
    } else {
        die("Erro ao atualizar o banco de dados: " . htmlspecialchars($stmt->error));
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Produto</title>
    <link rel="stylesheet" href="editar_produto.css">
</head>
<body>
    <h1>Editar Produto</h1>
    <form action="editar_imagem.php?id=<?php echo $produto['id']; ?>" method="POST" enctype="multipart/form-data">
        <label for="nome">Nome:</label>
        <input type="text" name="nome" value="<?php echo htmlspecialchars($produto['nome']); ?>" required>

        <label for="descricao">Descrição:</label>
        <textarea name="descricao" required><?php echo htmlspecialchars($produto['descricao']); ?></textarea>

        <label for="preco_original">Preço Original:</label>
        <input type="number" step="0.01" name="preco_original" value="<?php echo $produto['preco_original']; ?>" required>

        <label for="preco_atual">Preço Atual:</label>
        <input type="number" step="0.01" name="preco_atual" value="<?php echo $produto['preco_atual']; ?>" required>

        <label for="quantidade">Quantidade:</label>
        <input type="number" name="quantidade" value="<?php echo $produto['quantidade']; ?>" required>

        <label for="categoria">Categoria:</label>
        <select name="categoria" required>
            <option value="Material Escolar" <?php if ($produto['categoria'] === 'Material Escolar') echo 'selected'; ?>>Material Escolar</option>
            <option value="Material de Escritório" <?php if ($produto['categoria'] === 'Material de Escritório') echo 'selected'; ?>>Material de Escritório</option>
        </select>

        <label for="fornecedor">Fornecedor:</label>
        <input type="text" name="fornecedor" value="<?php echo htmlspecialchars($produto['fornecedor']); ?>" required>

        <label for="estado">Estado:</label>
        <input type="text" name="estado" value="<?php echo htmlspecialchars($produto['estado']); ?>" required>

        <label for="imagem">Nova Imagem:</label>
        <input type="file" name="imagem">

        <button type="submit">Salvar Alterações</button>
    </form>
    <button class="back-button" onclick="window.location.href='produtos.php';">Voltar</button></body>
</html>
