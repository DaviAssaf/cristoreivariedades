<?php
session_start();

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'administrador') {
    header("Location: ../pagina.principal.php?error=acesso_negado");
    exit();
}

include '../conexao/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['imagem'])) {
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco_original = floatval($_POST['preco_original']);
    $preco_atual = floatval($_POST['preco_atual']);
    $quantidade = intval($_POST['quantidade']);
    $categoria = $_POST['categoria'];
    $fornecedor = $_POST['fornecedor'];
    $estado = $_POST['estado'];

    if ($_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $imagemContent = file_get_contents($_FILES['imagem']['tmp_name']);

        $query = "INSERT INTO estoque (nome, imagem, descricao, preco_original, preco_atual, quantidade, categoria, fornecedor, estado) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);

        if ($stmt === false) {
            die("Erro ao preparar consulta: " . htmlspecialchars($conn->error));
        }

        $stmt->bind_param('sbssddiss', $nome, $imagemContent, $descricao, $preco_original, $preco_atual, $quantidade, $categoria, $fornecedor, $estado);

        $stmt->send_long_data(1, $imagemContent);

        if ($stmt->execute()) {
            header("Location: produtos.php");
            exit();
        } else {
            die("Erro ao inserir no banco de dados: " . htmlspecialchars($stmt->error));
        }
    } else {
        die("Erro no upload da imagem: " . htmlspecialchars($_FILES['imagem']['error']));
    }
}

$conn->close();
