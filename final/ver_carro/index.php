<?php
include '../conexao/config.php';
session_start();

$usuario_id = $_SESSION['user_id'];
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!isset($_SESSION['user_id'])) {
    header("Location: login/login.php");
    exit();
}

// Obtenha os itens da coleção
$sql = "SELECT * FROM carro WHERE id = ? and usuario_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id, $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
$itens = $result->fetch_all(MYSQLI_ASSOC);

// Voltando o mesmo valor da foto antiga
$selectFoto = "SELECT foto from carro where id=$id";
$resultado = $conn->query($selectFoto);
$row = $resultado->fetch_assoc();
$fotoAnt = $row['foto'];

$stmt->close();



//Delete carro
if(isset($_POST['action']) && $_POST['action'] == 'delete'){

    $sqlSelect = "SELECT * FROM carro WHERE id=$id";

    $result = $conn->query($sqlSelect);
    

    if($result->num_rows > 0){

        $sqlDelete = "DELETE FROM carro WHERE id=$id";
        $resultDelete = $conn->query($sqlDelete);

        header("Location: ../index.php");
        exit();
    }
}


// Update Carro
if (isset($_POST['action']) && $_POST['action'] == 'atualizar') {
    $modelo = $_POST['modelo'];
    $ano = $_POST['ano'];
    $marca = $_POST['marca'];
    $data_aquisicao = $_POST['data_aquisicao'];
    $tipo = $_POST['tipo'];
    $combustivel = $_POST['combustivel'];
    $preco = $_POST['preco'];
    $cambio = $_POST['cambio'];

    if(!empty($_FILES['foto']['name'])){
        $foto = $_FILES['foto'];
        $permitido = array('png');
        $extensao = pathinfo($foto['name'], PATHINFO_EXTENSION);

        if(in_array($extensao, $permitido)){
            $pasta = "../imagens/"; 
            $temporario = $foto['tmp_name'];
            $novoNome = uniqid().".$extensao";

            if(move_uploaded_file($temporario, $pasta.$novoNome)){
                
            }else {
                echo "Falha ao mover o arquivo";
            }
        }else{
            echo "Formato inválido";
        }
    }else{

    $novoNome = $fotoAnt;
    
    }

    $sql = "UPDATE carro SET `modelo` = ?, `marca` = ?, `ano` = ?, `tipo` = ?, `combustivel` = ?, `preco` = ?, `cambio` = ?, `data_aquisicao` = ?, `foto` = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssissssssi", $modelo, $marca, $ano, $tipo, $combustivel, $preco, $cambio, $data_aquisicao, $novoNome, $id);            
    if ($stmt->execute()){
        header("Location: ../index.php");
        exit();
    } else {
        echo "Erro: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Garagem</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.11.1/dist/sweetalert2.min.css" rel="stylesheet">
</head>
<body>

    <!-- Menu de navegação -->
    <nav>
        <ul>
            <li><a href="../index.php">Home</a></li>
            <li><a href="../add_carro/index.php">Adicionar Carro</a></li>
            <li><a href="../contato/index.php">Contato</a></li>
            <li>
                <a href="#">Perfil</a>
                <ul class="dropdown">
                    <li><a href="">Logout</a></li>
                    <li><a href="#" type="button" id="excluir-conta" class="excluir-conta">Excluir</a></li>
                </ul>
            </li>
        </ul>
    </nav>

    <?php foreach ($itens as $item): ?>
    <main>
        <div class="container">
            <section class="header">
                <h2>Seu carro</h2>
            </section>    

            <div class="container2">
                <div class="image">
                    <section>
                        <img src="../imagens/<?= strtolower($item['foto']) ?>" alt="imagem do carro especificado">
                    </section>
                </div>
                <form id="carro-form" method="post" enctype="multipart/form-data" autocomplete="off">
                    <input type="hidden" name="action" id="action-input">
                    <div class="content">
                        <div class="form-content">
                            <label>Marca:</label>
                            <input type="text" name="marca" value="<?php echo htmlspecialchars($item['marca']); ?>" placeholder="Digite a marca do carro">
                        </div>
                        <div class="form-content">
                            <label>Modelo:</label>
                            <input type="text" name="modelo" value="<?php echo htmlspecialchars($item['modelo']); ?> "placeholder="Digite a montadora do carro">
                        </div>
                    </div>
                    <div class="content">
                        <div class="form-content">
                            <label>Tipo:</label>
                            <select name="tipo" value="">
                                <option value=""></option>
                                <option value="Sedã" <?php echo ($item['tipo'] == 'Sedã') ? 'selected' : ''; ?>>Sedã</option>
                                <option value="SUV" <?php echo ($item['tipo'] == 'SUV') ? 'selected' : ''; ?>>SUV</option>
                                <option value="Picape" <?php echo ($item['tipo'] == 'Picape') ? 'selected' : ''; ?>>Picape</option>
                                <option value="Hatch" <?php echo ($item['tipo'] == 'Hatch') ? 'selected' : ''; ?>>Hatch</option>
                                <option value="Crossover" <?php echo ($item['tipo'] == 'Crossoverr') ? 'selected' : ''; ?>>Crossover</option>
                                <option value="Perua" <?php echo ($item['tipo'] == 'Perua') ? 'selected' : ''; ?>>Perua</option>
                                <option value="Minivan" <?php echo ($item['tipo'] == 'Minivan') ? 'selected' : ''; ?>>Minivan</option>
                                <option value="Furgão" <?php echo ($item['tipo'] == 'Furgão') ? 'selected' : ''; ?>>Furgão</option>
                                <option value="Esportivo" <?php echo ($item['tipo'] == 'Esportivo') ? 'selected' : ''; ?>>Esportivo</option>
                            </select>
                        </div>
                        <div class="form-content">
                            <label>Ano:</label>
                            <input type="number" name="ano" placeholder="Digite a montadora do carro" max="2030" min="1886" value="<?php echo htmlspecialchars($item['ano']); ?>" >
                        </div>
                    </div>
                    <div class="content">
                        <div class="form-content">
                            <label>Combustível:</label>
                            <select name="combustivel">
                                <option value=""></option>
                                <option value="Flex" <?php echo ($item['combustivel'] == 'Flex') ? 'selected' : ''; ?>>Flex</option>
                                <option value="Álcool" <?php echo ($item['combustivel'] == 'Álcool') ? 'selected' : ''; ?>>Álcool</option>
                                <option value="Gasolina" <?php echo ($item['combustivel'] == 'Gasolina') ? 'selected' : ''; ?>>Gasolina</option>
                                <option value="Elétrico" <?php echo ($item['combustivel'] == 'Elétrico') ? 'selected' : ''; ?>>Elétrico</option>
                                <option value="Diesel" <?php echo ($item['combustivel'] == 'Diesel') ? 'selected' : ''; ?>>Diesel</option>
                            </select>
                        </div>
                        <div class="form-content">
                            <label>Cambio:</label>
                            <select name="cambio">
                                <option value=""></option>
                                <option value="Manual" <?php echo ($item['cambio'] == 'Manual') ? 'selected' : ''; ?>>Manual</option>
                                <option value="Automático" <?php echo ($item['cambio'] == 'Automático') ? 'selected' : ''; ?>>Automático</option>
                                <option value="Semi-automático" <?php echo ($item['cambio'] == 'Semi-automático') ? 'selected' : ''; ?>>Semi-automático</option>
                            </select>
                        </div>
                    </div>

                    <div class="content">
                        <div class="form-content">
                            <label>Preco:</label>
                            <input type="text" name="preco" id="preco" value="<?php echo htmlspecialchars($item['preco']); ?>" placeholder="Digite a montadora do carro">
                        </div>
                        
                        <div class="form-content">
                            <label>Data de Aquisição:</label>
                            <input type="date" name="data_aquisicao" value="<?php echo htmlspecialchars($item['data_aquisicao']); ?>" max="2030-12-31" min="1886-12-31" placeholder="Digite a montadora do carro">
                        </div>
                    </div>
                    <div class="form-content-img">
                        <label for="">Foto do carro:</label>
                        <input type="file" id="inputImagem" name="foto">
                    </div>
                    <div class="content">
                        <button type="button" id="button-atualizar" name="button-atualizar" >Atualizar</button>
                        <button class="excluir" id="button-excluir" name="button-excluir" type="button">Excluir</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <?php endforeach; ?>
    <!-- link de js para o sweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.11.1/dist/sweetalert2.all.min.js"></script>
    <!-- Importar SweetAlert -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script>

        // Mascara de input - Preco
        $(document).ready(function(){
            $('#preco').mask('R$ 000.000.000.000.000,00', {reverse: true});
        });
            
        document.getElementById("button-excluir").addEventListener("click", function(event) {
            event.preventDefault();
            alertExcluir();
        });
        
        // PopUp do excluir carro 
        function alertExcluir() {
            Swal.fire({
                title: "Deseja excluir o carro?",
                text: "Não será possível reverter a ação",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Sim, deletar!",
                cancelButtonText: "Cancelar"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById("action-input").value = "delete";
                    document.getElementById("carro-form").submit();
                }
            });
        }

        // PopUp de atualizar carro 
        document.getElementById("button-atualizar").addEventListener("click", function(event) {
            event.preventDefault();
            alertAtualizar();
        });

        function alertAtualizar() {
            Swal.fire({
                title: "Deseja atualizar o carro?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Sim, Atualizar!",
                cancelButtonText: "Cancelar"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById("action-input").value = "atualizar";
                    document.getElementById("carro-form").submit();
                }
            });
        }

        // PopUp do excluir conta 
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


