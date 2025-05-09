<?php
session_start();

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'administrador') {
    header("Location: ../pagina.principal.php?error=acesso_negado");
    exit();
}

include $_SERVER['DOCUMENT_ROOT'].'/projeto/conexao/conexao.php';

function exibirProdutos() {
    global $conn;
    $query = "SELECT * FROM estoque";
    $stmt = $conn->prepare($query);

    if ($stmt === false) {
        die("Erro ao preparar consulta: " . htmlspecialchars($conn->error));
    }

    $stmt->execute();
    $result = $stmt->get_result();

    echo "<div class='container'>";

    while ($row = $result->fetch_assoc()) {
        $nome = htmlspecialchars($row['nome']);
        $id = $row['id'];
        $categoriaRaw = htmlspecialchars($row['categoria']);
        $estado = htmlspecialchars($row['estado']);
        
        if ($categoriaRaw === "Material Escolar") {
            $categoria = "material-escolar";
        } elseif ($categoriaRaw === "Material de Escritório") {
            $categoria = "material-escritorio";
        } else {
            $categoria = "";
        }

        $imagem = "/projeto/pagina.principal/imagens-produtos/$categoria/" . htmlspecialchars($row['imagem']);

        echo "<div class='prateleira'>";
        echo "<img src='mostrar_imagem.php?id=$id' alt='$nome' class='produto'>"; 
        echo "<p class='descricao produto'>$nome</p>";
        echo "<p class='estado'>$estado</p>";
        echo "<p class='preço'>Preço Original: R$" . htmlspecialchars($row['preco_original']) . "</p>";
        echo "<p class='preço'>Preço Atual: R$" . htmlspecialchars($row['preco_atual']) . "</p>";
        echo "<button onclick=\"window.location.href='editar_produto.php?id=$id'\" class='edit-button'>EDITAR</button>";
        echo "<button class='delete-button' onclick=\"confirmDelete($id)\">DELETAR</button>";
        echo "</div>";
    }

    echo "<div class='prateleira'>";
    echo "<button class='botao-adicionar' onclick=\"window.location.href='../gerenciar-produtos/adicionar_produto.html'\">Adicionar Produto</button>";
    echo "</div>";
    
    echo "</div>";

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador de Produtos</title>
    <link rel="stylesheet" href="produtos.css">
    <script>
        function confirmDelete(productId) {
            if (confirm("Você tem certeza que deseja deletar este produto?")) {
                window.location.href = `deletar_produto.php?id=${productId}`;
            }
        }
    </script>
</head>
<body>
    <div id="header">
        <h1>Área de Administração - Gerenciador de Produtos</h1>
        <button class="botao-principal" onclick="window.location.href='../pagina.principal/pagina.principal.php'">Página Principal</button>    </div>
    <div class="produtos">
        <?php exibirProdutos(); ?>
    </div>
</body>
</html>