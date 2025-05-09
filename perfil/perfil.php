<?php
session_start();
include '../conexao/conexao.php';

$admin = isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'administrador';
$cliente = isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'cliente';
$logado = $admin || $cliente;

if (!$logado) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['senha_atual']) && isset($_POST['senha'])) {
    $senha_atual = $_POST['senha_atual'];
    $nova_senha = $_POST['senha'];

    if ($nova_senha) {
        $tabela = $cliente ? "clientes" : "administradores";
        $query_senha = "SELECT senha FROM $tabela WHERE id = ?";
        $stmt = $conn->prepare($query_senha);
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user_data = $result->fetch_assoc();

        if (password_verify($senha_atual, $user_data['senha'])) {
            $nova_senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
            $query_update_senha = "UPDATE $tabela SET senha = ? WHERE id = ?";
            $stmt_update = $conn->prepare($query_update_senha);
            $stmt_update->bind_param('si', $nova_senha_hash, $user_id);
            if ($stmt_update->execute()) {
                echo "Senha atualizada com sucesso!";
            } else {
                echo "Erro ao atualizar a senha!";
            }
        } else {
            echo "A senha atual está incorreta!";
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['foto_perfil'])) {
    if ($_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
        $imagemContent = file_get_contents($_FILES['foto_perfil']['tmp_name']);
        $query = $cliente ? "UPDATE clientes SET foto_perfil = ? WHERE id = ?" : "UPDATE administradores SET foto_perfil = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('bi', $imagemContent, $user_id);
        $stmt->send_long_data(0, $imagemContent);
        if ($stmt->execute()) {
            echo "Foto de perfil atualizada com sucesso!";
        } else {
            die("Erro ao atualizar foto de perfil: " . htmlspecialchars($stmt->error));
        }
    } else {
        echo "Erro no upload da imagem: " . htmlspecialchars($_FILES['foto_perfil']['error']);
    }
}

$query = $cliente ? "SELECT nome, email, foto_perfil, telefone, cep, endereco FROM clientes WHERE id = ?" : "SELECT nome, email, foto_perfil, contato AS telefone FROM administradores WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil do Usuário</title>
    <link rel="stylesheet" href="perfil.css">
</head>

<body>
    <div class="profile-container">
        <h1>Perfil do Usuário</h1>

        <div class="profile-pic-section">
            <form action="perfil.php" method="POST" enctype="multipart/form-data">
                <label for="profile-pic-input">
                    <?php
                    if (!empty($user['foto_perfil'])) {
                        echo '<img id="profile-pic" src="data:image/jpeg;base64,' . base64_encode($user['foto_perfil']) . '" alt="Foto de Perfil">';
                    } else {
                        echo '<img id="profile-pic" src="../pagina.principal/imagens-produtos/material-escolar/profile-icon.jpg" alt="Foto de Perfil">';
                    }
                    ?>
                </label>
                <input type="file" name="foto_perfil" id="profile-pic-input" accept="image/*">
                <button type="submit">Atualizar Foto de Perfil</button>
            </form>
        </div>

        <div class="personal-info">
            <h3>Informações Pessoais</h3>
            <form action="perfil.php" method="POST">
                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($user['nome'] ?? ''); ?>" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>

                <label for="telefone">Telefone:</label>
                <input type="text" id="telefone" name="telefone" value="<?php echo htmlspecialchars($user['telefone'] ?? ''); ?>" required>

                <?php if ($cliente): ?>
                    <label for="cep">CEP:</label>
                    <input type="text" id="cep" name="cep" value="<?php echo htmlspecialchars($user['cep'] ?? ''); ?>" required>

                    <label for="endereco">Endereço:</label>
                    <input type="text" id="endereco" name="endereco" value="<?php echo htmlspecialchars($user['endereco'] ?? ''); ?>" required>
                <?php endif; ?>

                <label for="senha_atual">Senha Atual:</label>
                <input type="password" id="senha_atual" name="senha_atual" required>

                <label for="senha">Nova Senha (opcional):</label>
                <input type="password" id="senha" name="senha">

                <button type="submit">Salvar Alterações</button>
            </form>
        </div>
    </div>

    <button class="back-button" onclick="window.location.href='../pagina.principal/pagina.principal.php'">Voltar</button>

    <script type="text/javascript" src="perfil.js"></script>
</body>
</html>