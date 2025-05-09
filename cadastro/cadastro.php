<?php
session_start();
include '../conexao/conexao.php';

if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'cliente') {
    header("Location: ../pagina.principal/pagina.principal.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $data_de_nascimento = $_POST['nascimento'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $cep = $_POST['cep'];
    $endereco = $_POST['endereco'];
    $senha = password_hash($_POST['senha'], PASSWORD_BCRYPT);

    $query = "SELECT * FROM clientes WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        header("Location: ../pagina.principal/pagina.principal.php");
        exit;
    } else {
        $insertQuery = "INSERT INTO clientes (nome, data_de_nascimento, email, senha, telefone, cep, endereco) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->bind_param("sssssss", $nome, $data_de_nascimento, $email, $senha, $telefone, $cep, $endereco);

        if ($insertStmt->execute()) {
            $_SESSION['user_type'] = 'cliente';
            $_SESSION['user_id'] = $insertStmt->insert_id;
            header("Location: ../pagina.principal/pagina.principal.php");
            exit;
        } else {
            echo "Erro ao cadastrar cliente.";
        }
    }
}
