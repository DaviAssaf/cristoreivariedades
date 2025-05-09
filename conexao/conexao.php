<?php
$host = "localhost";
$usuario = "root";
$senha = "";
$dbname = "cristorei";

$conn = new mysqli($host, $usuario, $senha, $dbname);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

$conn->set_charset("utf8");
