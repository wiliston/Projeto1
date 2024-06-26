
<?php
include '../conexao/config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Verifica se o email já está em uso
    $stmt = $conn->prepare("SELECT id FROM usuario WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        echo "Este email já está em uso. Por favor, escolha outro.";
        echo "<a href='register.php'>Voltar</a>";
        $stmt->close();
        exit();
    }
    $stmt->close();

    // Verifica se o nome de usuário já está em uso
    $stmt = $conn->prepare("SELECT id FROM usuario WHERE nome = ?");
    $stmt->bind_param("s", $nome);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        echo "Este nome de usuário já está em uso. Por favor, escolha outro.";
        echo "<a href='register.php'>Voltar</a>";
        $stmt->close();
        exit();
    }
    $stmt->close();

    // Insere o novo usuário no banco de dados

    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $stmt = $conn->prepare("INSERT INTO usuario (nome, email, senha) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nome, $email, $senha);
    

    if ($stmt->execute()) {

        // Obter o ID do usuário recém-criado
        $usuario_id = $stmt->insert_id;

        $stmt->close();

        echo "Cadastro realizado com sucesso! Você já pode fazer login.";
        header("Location: ../login/login.php");
        exit();

    } 

    else {
        echo "Erro: " . $stmt->error;
    }

    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styleCadastro.css">
    <title>Cadastrar</title>
</head>
<body>
    <form method="post" autocomplete="off">
        <div class="caixaMenu">

            <div class="useCaixa">
                <input type="text" name="nome" required="">
                <label>Nome</label>
            </div>

            <div class="useCaixa">
                <input type="email" name="email" required="">
                <label>Email</label>               
            </div>

            <div class="useCaixa">
                <input type="password" name="senha" maxlength="30" required="">
                <label>Senha</label>
            </div>

            <!-- lembrar de tirar o br br e colocar um padding e margin no botão -->
            <br><br><center>
                <button id="btn-cadastrar" type="submit">Cadastrar</button>


                <!-- <a class="cadastrar" href="insert.php">Cadastrar</a> -->
                <a href="../login/login.php">Fazer login</a>
        </div>
    </form>
</body>
</html>
