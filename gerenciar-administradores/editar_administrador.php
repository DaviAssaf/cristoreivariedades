<?php
include '../conexao/conexao.php';
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $query = "SELECT * FROM administradores WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0) {
        $admin = $result->fetch_assoc();
    } else {
        echo "Administrador não encontrado.";
        exit;
    }
} else {
    echo "ID não fornecido.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $nome = $_POST['nome'];
    $data_de_nascimento = $_POST['data_de_nascimento'];
    $email = $_POST['email'];
    $contato = $_POST['contato'];
    $status = $_POST['status'];

    $updateQuery = "UPDATE administradores SET nome = ?, data_de_nascimento = ?, email = ?, contato = ?, status = ? WHERE id = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("ssssi", $nome, $data_de_nascimento, $email, $contato, $status, $id);

    if ($updateStmt->execute()) {
        header("Location: administradores.php");
        exit;
    } else {
        echo "Erro ao atualizar administrador.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Administrador</title>
    <link rel="stylesheet" type="text/css" href="administradores.css">
</head>
<body>
    <div id="header">
        <h1>Editar Administrador</h1>
    </div>

    <div class="table-section">
        <form action="" method="POST">
            <input type="hidden" name="id" value="<?php echo $admin['id']; ?>">

            <label for="nome">Nome:</label>
            <input type="text" name="nome" value="<?php echo $admin['nome']; ?>" required>

            <label for="data_de_nascimento">Data de Nascimento:</label>
            <input type="date" name="data_de_nascimento" value="<?php echo $admin['data_de_nascimento']; ?>" required>

            <label for="email">Email:</label>
            <input type="email" name="email" value="<?php echo $admin['email']; ?>" required>

            <label for="contato">Contato:</label>
            <input type="text" name="contato" value="<?php echo $admin['contato']; ?>" required>
            
            <label for="status">Status:</label>
            <select name="status" required>
                <option value="Ativa" <?php if ($admin['status'] == 'Ativa') echo 'selected'; ?>>Ativa</option>
                <option value="Inativa" <?php if ($admin['status'] == 'Inativa') echo 'selected'; ?>>Inativa</option>
            </select>

            <button type="submit">Atualizar</button>
        </form>

        <div class="back-button">
            <button onclick="window.location.href='administradores.php';">Voltar</button>
        </div>
    </div>
</body>
</html>