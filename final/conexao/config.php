<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "colecionadordb";

// Criação da conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificação da conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

