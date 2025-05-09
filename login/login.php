<?php
include "../conexao/conexao.php";
session_start();

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $login = $_POST['username'];
    $senha = $_POST['password'];

    $query = "SELECT * FROM clientes WHERE email = ?";
    $stmt = $conn->prepare($query);

    if ($stmt == false){
        die ("Erro ao preparar a consulta: ". $conn->error);
    }
    
    $stmt->bind_param("s", $login);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0){
        $usuario = $result->fetch_assoc();
        if (password_verify($senha, $usuario['senha'])) {
            $_SESSION['user_id'] = $usuario['id'];
            $_SESSION['user_type'] = 'cliente'; 
            header("Location: ../pagina.principal/pagina.principal.php");
            exit();
        } else {
            echo "Email ou senha incorretos.";
        }
    } else {
        $queryAdmin = "SELECT * FROM administradores WHERE email = ?";
        $stmtAdmin = $conn->prepare($queryAdmin);

        if ($stmtAdmin == false){
            die("Erro ao realizar o login. Tente novamente mais tarde.");
        }

        $stmtAdmin->bind_param("s", $login);
        $stmtAdmin->execute();
        $resultAdmin = $stmtAdmin->get_result();

        if ($resultAdmin->num_rows>0){
            $admin = $resultAdmin->fetch_assoc();
            if (password_verify($senha, $admin['senha'])) {
                $_SESSION['user_id'] = $admin['id'];
                $_SESSION['user_type'] = 'administrador';
                header("Location: ../pagina.principal/pagina.principal.php");
                exit();
            } else {
                echo "Email ou senha incorretos.";
            }
        } else {
            echo "Email não encontrado";
        }
        $stmtAdmin->close();
    }
    
    $stmt->close();
    $conn->close();
}