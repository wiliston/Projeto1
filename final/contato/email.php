<!-- Apenas funciona em um server real-->

<?php
include '../conexao/config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login/login.php");
    exit();
} 

if(isset($_POST['email']) && !empty($_POST['email'])){ 

    $nome = addcslashes($_POST['nome']);
    $email = addcslashes($_POST['email']);
    $mensagem = addcslashes($_POST['mensagem']);

    $para = "colecionadorcarros39@gmail.com";
    $subject = "Mensagem - Projeto programador";

    $corpo =    "Nome: ".$nome. "\n".
                "Email: ". $email. "\n".
                "Mensagem: ". $mensagem;

    $header = "From: conerk411@gmail.com". "\n\r".
                "Reply-To: $email" . "\e\n".
                "X-Mailer:PHP/".phpversion();
    
    if(mail($to, $subject, $corpo, $header)){

        echo ('Email enviado com sucesso!');
    }
    else{
        echo("O email não pode ser acessado");
    }
}
?>