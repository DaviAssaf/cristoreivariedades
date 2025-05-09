<?php
session_start();

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'administrador') {
    header("Location: ../pagina.principal.php?error=acesso_negado");
    exit();
}

include '../conexao/conexao.php';

$allowedTables = ['administradores', 'clientes', 'entregas', 'estoque', 'fornecedores', 'historico_venda'];

function exibirTabela($nomeTabela) {
    global $conn, $allowedTables;

    if (!in_array($nomeTabela, $allowedTables)) {
        echo "<p>Tabela não permitida.</p>";
        return;
    }

    $nomeTabela = preg_replace('/[^a-zA-Z0-9_]/', '', $nomeTabela); 

    $tableExistsQuery = "SHOW TABLES LIKE '$nomeTabela'";
    $stmt = $conn->prepare($tableExistsQuery);
    $stmt->execute();
    $tableExistsResult = $stmt->get_result();

    if ($tableExistsResult->num_rows == 0) {
        echo "<p>A tabela '". htmlspecialchars($nomeTabela) ."' não existe.</p>";
        return;
    }

    $query = "SELECT * FROM `$nomeTabela`";
    $stmt = $conn->prepare($query);

    if ($stmt === false) {
        die("Erro na preparação da consulta: " . htmlspecialchars($conn->error));
    }

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $tabelaId = 'tabela_' . $nomeTabela;

        echo "<h3>Tabela: " . htmlspecialchars($nomeTabela) . "</h3>";
        echo "<button class='toggle-button' onclick=\"toggleTabela('$tabelaId')\">Minimizar</button>";
        echo "<div id='$tabelaId' style='display: block;'>";
        echo "<table border='1'><tr>";

        $campos = $result->fetch_fields();
        foreach ($campos as $campo) {
            echo "<th>" . htmlspecialchars($campo->name) . "</th>";
        }
        echo "</tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            foreach ($row as $campo => $valor) {
                if (strpos($campo, 'foto') !== false && !empty($valor)) {
                    $imagemBase64 = 'data:image/jpeg;base64,' . base64_encode($valor);
                    echo "<td><img src='" . htmlspecialchars($imagemBase64) . "' alt='Imagem' style='max-width: 100px;'></td>";
                } elseif ($campo == 'imagem' && $nomeTabela == 'estoque' && !empty($valor)) {
                    $imagemId = $row['id']; // Pega o ID da linha para usar no link
                    echo "<td><a href='?mostrar_imagem=$imagemId'><img src='?mostrar_imagem=$imagemId' alt='Imagem do Estoque' style='max-width: 100px;'></a></td>";
                } else {
                    echo "<td>" . htmlspecialchars($valor ?? '') . "</td>";
                }
            }
            echo "</tr>";
        }
        echo "</table>";
        echo "</div><br>";
    } else {
        echo "<p>Nenhum dado encontrado na tabela " . htmlspecialchars($nomeTabela) . "</p>";
    }
    $stmt->close();
}

if (isset($_GET['mostrar_imagem'])) {
    $id = $_GET['mostrar_imagem'];

    $query = "SELECT imagem FROM estoque WHERE id = ?";
    $stmt = $conn->prepare($query);

    if ($stmt === false) {
        die("Erro ao preparar consulta: " . htmlspecialchars($conn->error));
    }

    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($imagem);
    $stmt->fetch();
    $stmt->close();

    if (empty($imagem)) {
        die("Imagem não encontrada.");
    }

    header("Content-Type: image/jpeg");
    echo $imagem;
    exit(); // Após enviar a imagem, parar a execução do script.
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administração - Tabelas</title>
    <link rel="stylesheet" type="text/css" href="tabelas.css">
    <script type="text/javascript">
        function toggleTabela(tabelaId) {
            const tabela = document.getElementById(tabelaId);
            const botao = tabela.previousElementSibling;

            if (tabela.style.display === "none") {
                tabela.style.display = "block";
                botao.textContent = "Minimizar";
            } else {
                tabela.style.display = "none";
                botao.textContent = "Expandir";
            }
        }
    </script>
</head>
<body>
    <div id="header">
        <h1>Área de Administração - Visualização de Tabelas</h1>
    </div>
    <div>
        <?php 
        foreach ($allowedTables as $tabela) {
            exibirTabela($tabela);
        }
        ?>
    </div>
    
    <div class="back-button">
        <button onclick="window.location.href='../pagina.principal/pagina.principal.php'">SAIR</button>
    </div>
</body>
</html>