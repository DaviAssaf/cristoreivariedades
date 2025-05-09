<?php
session_start();
include '../conexao/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $data_de_nascimento = $_POST['data_de_nascimento'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_BCRYPT);
    $contato = $_POST['contato'];
    $status = $_POST['status'];

    $query = "SELECT * FROM administradores WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        header("Location: administradores.php?erro=email_existente");
        exit;
    } else {
        $insertQuery = "INSERT INTO administradores (nome, data_de_nascimento, email, senha, contato, status) VALUES (?, ?, ?, ?, ?, ?)";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->bind_param("ssssss", $nome, $data_de_nascimento, $email, $senha, $contato, $status);

        if ($insertStmt->execute()) {
            header("Location: administradores.php?sucesso=cadastro_realizado");
            exit;
        } else {
            header("Location: administradores.php?erro=cadastro_falhou");
            exit;
        }
    }
}