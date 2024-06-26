<?php
include '../conexao/config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $sql = "SELECT id, nome FROM usuario WHERE email = '$email' AND senha = '$senha'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Sucesso no login
        $row = $result->fetch_assoc();
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['user_name'] = $row['nome'];
        header("Location: ../index.php");
    } else {
        echo "<p class='msg_erro'>Email ou senha incorretos</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <link rel="stylesheet" href="login.css">
    <title>Login</title>
</head>
<body>
        <form method="post">
            <div class="caixaLogin">

                <div class="user-box">
                    <input type="email" id="email" name="email" required>
                    <label>Email</label>
                </div>

                <div class="user-box">
                    <input type="password" id="senha" name="senha" required=>
                    <label>Senha</label>
                </div>

                <br><br><center>
                    <button type="submit">Login</button>
                
                    <a href="../cadastro/cadastro.php">Cadastro</a>
            </div>
        </form>
</body>
</html>

