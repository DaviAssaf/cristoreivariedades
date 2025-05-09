<?php
session_start();
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'administrador') {
    header("Location: ../pagina.principal/pagina.principal.php");
    exit;
}

include '../conexao/conexao.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $query = "DELETE FROM administradores WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: administradores.php?message=Administrador deletado com sucesso!");
    } else {
        header("Location: administradores.php?message=Erro ao deletar administrador.");
    }
    $stmt->close();
} else {
    header("Location: administradores.php?message=ID do administrador não encontrado.");
}