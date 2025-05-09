<?php 
session_start();
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'administrador') {
    header("Location: ../pagina.principal/pagina.principal.php");
    exit;
}

include '../conexao/conexao.php';

$query = "SELECT * FROM administradores";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administração - Administradores</title>
    <link rel="stylesheet" type="text/css" href="administradores.css">
</head>
<body>
    <div id="header">
        <h1>Área de Administração - Gerenciar Administradores</h1>
    </div>

    <div class="table-section">
        <h2>Cadastrar Novo Administrador</h2>
        <form action="cadastrar_administrador.php" method="POST">
            <label for="nome">Nome:</label>
            <input type="text" name="nome" required>

            <label for="data_de_nascimento">Data de Nascimento:</label>
            <input type="date" name="data_de_nascimento" required>

            <label for="email">Email:</label>
            <input type="email" name="email" required>

            <label for="senha">Senha:</label>
            <input type="password" name="senha" required>

            <label for="contato">Contato:</label>
            <input type="text" name="contato" required>
            
            <label for="status">Status:</label>
            <select name="status" required>
                <option value="Ativa">Ativa</option>
                <option value="Inativa">Inativa</option>
            </select>

            <button type="submit">Cadastrar</button>
        </form>

        <h2>Lista de Administradores</h2>
        <div class="table-container">
            <h3>Administradores Cadastrados</h3>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Data de Nascimento</th>
                    <th>Email</th>
                    <th>Contato</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($admin = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $admin['id']; ?></td>
                            <td><?php echo $admin['nome']; ?></td>
                            <td><?php echo $admin['data_de_nascimento']; ?></td>
                            <td><?php echo $admin['email']; ?></td>
                            <td><?php echo $admin['contato']; ?></td>
                            <td><?php echo $admin['status']; ?></td>
                            <td>
                                <a href="editar_administrador.php?id=<?php echo $admin['id']; ?>">Editar</a>
                                <a href="deletar_administrador.php?id=<?php echo $admin['id']; ?>" onclick="return confirm('Tem certeza que deseja deletar este administrador?');">Deletar</a>
                            </td>
                        </tr>
                    <?php endwhile; ?> 
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="empty-message">Nenhum administrador cadastrado</td>
                    </tr> 
                <?php endif; ?>
            </table>
        </div>
    </div>

    <div class="back-button">
        <button onclick="window.location.href='../pagina.principal/pagina.principal.php';">Voltar</button>
    </div>
</body>
</html>