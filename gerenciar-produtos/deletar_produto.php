<?php
session_start();
include '../conexao/conexao.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $stmt = $conn->prepare("DELETE FROM estoque WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>alert('Produto deletado com sucesso!'); window.location.href='produtos.php';</script>";
    } else {
        echo "<script>alert('Erro ao deletar o produto. Tente novamente.'); window.location.href='produtos.php';</script>";
    }

    $stmt->close();
} else {
    echo "<script>alert('ID do produto não fornecido.'); window.location.href='produtos.php';</script>";
}

$conn->close();
