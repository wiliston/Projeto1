<?php
include '../conexao/config.php';
session_start();

$usuario_id = $_SESSION['user_id'];

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_usuario = $_SESSION['user_id'];;
    $marca = $_POST['marca'];
    $modelo = $_POST['modelo'];
    $ano = $_POST['ano'];
    $combustivel = $_POST['combustivel'];
    $cambio = $_POST['cambio'];
    $data_aquisicao = $_POST['data_aquisicao'];
    $preco = $_POST['preco'];
    $tipo = $_POST['tipo'];

    // Verifica se um arquivo de imagem foi enviado
    if(isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $foto = $_FILES['foto'];

            $permitido = array('png');
            $extensao = pathinfo($foto['name'], PATHINFO_EXTENSION);

            if(in_array($extensao, $permitido)){
                $pasta = "../imagens/";
                $temporario = $foto['tmp_name'];
                $novoNome = uniqid().".$extensao";

                if(move_uploaded_file($temporario, $pasta.$novoNome)){
                    $sql = "INSERT INTO carro (usuario_id, marca, modelo, tipo, ano, combustivel, cambio, data_aquisicao, preco, foto) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("isssisssss", $usuario_id, $marca, $modelo, $tipo, $ano, $combustivel, $cambio, $data_aquisicao, $preco, $novoNome);
                if ($stmt->execute()) {
                    header("Location: ../index.php?user_id=$usuario_id");
                    exit();
                } else {
                    echo "Erro: " . $stmt->error;
                }
                $stmt->close();
            } else {
                echo "Falha ao mover o arquivo";
            }
        } else {
            echo "Formato inválido";
        }
    } else {
        echo "Nenhum arquivo de imagem enviado";
        }
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">    
    <link rel="stylesheet" href="style_carro.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.11.1/dist/sweetalert2.min.css" rel="stylesheet">
    <title>Garagem</title>
</head>
    <body>
        <nav>
            <ul>
                <li><a href="../index.php">Home</a></li>
                <li><a href="index.php">Adicionar Carro</a></li>
                <li><a href="../contato/index.php">Contato</a></li>
                <li>
                    <a href="#">Perfil</a>
                    <ul class="dropdown">
                        <li><a href="../logout/logout.php">Logout</a></li>
                        <li><a href="#" type="button" id="excluir-conta" class="excluir-conta">Excluir</a></li>
                    </ul>
                </li>
            </ul>
        </nav>

        <main>
            <div class="container">
                <section class="header">
                    <h2>Adicione seu carro</h2>
                </section>    

                <form class="form" method="post" enctype="multipart/form-data" autocomplete="off">
                    <div class="form-content">
                        <label for="">Marca:</label>
                        <input type="text" name="marca" placeholder="Digite a montadora do carro">
                    </div>    

                    <div class="form-content">
                        <label for="">Modelo:</label>
                        <input type="text" name="modelo" placeholder="Digite o modelo">
                    </div>

                    <div class="form-content">
                        <label for="">Ano:</label>
                        <input type="number" name="ano" placeholder="Digite o ano do carro" max="2030" min="1886">
                    </div>

                    <div class="form-content">
                        <label for="">Combustível:</label>
                        <select name="combustivel">
                            <option value=""></option>
                            <option value="Flex">Flex</option>
                            <option value="Álcool">Álcool</option>
                            <option value="Gasolina">Gasolina</option>
                            <option value="Diesel">Diesel</option>
                            <option value="Elétrico">Elétrico</option>
                        </select>
                    </div>
                    <div class="form-content">
                        <label for="">Cambio:</label>
                        <select name="cambio">
                            <option value=""></option>
                            <option value="Manual">Manual</option>
                            <option value="Automático">Automático</option>
                            <option value="Semi-automático">Semi-automático</option>
                        </select>
                    </div>

                    <div class="form-content">
                        <label for="">Data de aquisição:</label>
                        <input type="date" name="data_aquisicao" max="2030-12-31" min="1886-12-31"required>
                    </div> 

                    <div class="form-content">
                        <label for="">Preço:</label>
                        <div class="input-group">
                            <span class="input-group-addon">R$</span>
                            <input type="text" name="preco" id="preco">
                        </div>
                    </div> 

                    <div class="form-content">
                        <label for="">Tipo:</label>
                        <select name="tipo">
                            <option value=""></option>
                            <option value="Sedã">Sedã</option>
                            <option value="SUV">SUV</option>
                            <option value="Picape">Picape</option>
                            <option value="Hatch">Hatch</option>
                            <option value="Crossover">Crossover</option>
                            <option value="Perua">Perua</option>
                            <option value="Minivan">Minivan</option>
                            <option value="Furgão">Furgão</option>
                            <option value="Esportivo">Esportivo</option>
                        </select>
                    </div>
                    
                    <div class="form-content">
                        <label for="">Foto do carro:</label>
                        <input type="file" id="inputImagem" name="foto" placeholder="R$" required>
                    </div>   
                    
                    <button type="submit">Cadastrar</button>
                </form>
            </div>
        </main> 

        <!-- Importar SweeAlert -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.11.1/dist/sweetalert2.all.min.js"></script>

        <!-- Importar JQuery -->
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

        <script>
            
            // Mascara de input - Preco
            $(document).ready(function(){
                $('#preco').mask('R$ 000.000.000.000.000,00', {reverse: true});
            });

            // Funcao excluir conta
            document.getElementById("excluir-conta").addEventListener("click", function(event) {
            event.preventDefault();
            alertExcluirConta();
            });

            function alertExcluirConta() {
                Swal.fire({
                    title: "Deseja excluir sua conta?",
                    text: "Não será possível reverter a ação",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Sim, deletar!",
                    cancelButtonText: "Cancelar"
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "../excluir/excluir.php";
                    }
                });
            }
        </script>
    </body>
</html>