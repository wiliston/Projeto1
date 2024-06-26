<?php
// Incluir arquivo de configuração do banco de dados
include '../conexao/config.php';
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit();
}

// Obter o ID do usuário da sessão
$usuario_id = $_SESSION['user_id'];

// Excluir conta
$sqlDelete = "DELETE FROM usuario WHERE id = ?";
$stmt = $conn->prepare($sqlDelete);
$stmt->bind_param("i", $usuario_id);

if ($stmt->execute()) {
    // Remover a sessão e redirecionar para a página de login
    session_destroy();
    header("Location: ../login/login.php");
    exit();
} else {
    echo "Erro ao excluir a conta: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
