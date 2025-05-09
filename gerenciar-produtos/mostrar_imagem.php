<?php
include $_SERVER['DOCUMENT_ROOT'].'/projeto/conexao/conexao.php';

if (!isset($_GET['id'])) {
    die("ID da imagem não fornecido.");
}

$id = $_GET['id'];

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
