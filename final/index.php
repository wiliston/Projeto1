<?php
include 'conexao/config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login/login.php");
    exit();
}

$usuario_id = $_SESSION['user_id'];
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : '';

// Modifique a consulta SQL para incluir o filtro de tipo, se aplicável
$sql = "SELECT marca, modelo, preco, foto, id FROM carro WHERE usuario_id = ?";
if ($tipo) {
    $sql .= " AND tipo = ?";
}
$stmt = $conn->prepare($sql);

if ($tipo) {
    $stmt->bind_param("is", $usuario_id, $tipo);
} else {
    $stmt->bind_param("i", $usuario_id);
}

$stmt->execute();
$result = $stmt->get_result();
$itens = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styleHome.css">
    <title>Garagem</title>
</head>

<body>

    <!-- Menu de navegação -->
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="add_carro/index.php">Adicionar Carro</a></li>
            <li><a href="contato/index.php">Contato</a></li>  
            <li><a href="#">Perfil</a>
                <ul class="dropdown">
                    <li><a href="logout/logout.php">Logout</a></li>
                    <li><a href="#" type="button" id="excluir-conta" class="excluir-conta">Excluir</a></li>
                </ul>
            </li>
        </ul>
    </nav>
    
    <!-- Formulário de filtro -->
    <div class="filtro">
        <form method="GET" action="index.php">
            <label for="tipo">Filtrar por Tipo:</label>
            <select name="tipo" id="tipo">
                <option value="">Todos</option>
                <option value="Sedan" <?= $tipo == 'Sedan' ? 'selected' : '' ?>>Conversível</option>
                <option value="SUV" <?= $tipo == 'SUV' ? 'selected' : '' ?>>SUV</option>
                <option value="Picape" <?= $tipo == 'Picape' ? 'selected' : '' ?>>Picape</option>
                <option value="Crossover" <?= $tipo == 'Crossover' ? 'selected' : '' ?>>Crossover</option>
                <option value="Perua" <?= $tipo == 'Perua' ? 'selected' : '' ?>>Perua</option>
                <option value="Minivan" <?= $tipo == 'Minivan' ? 'selected' : '' ?>>Minivan</option>
                <option value="Furgão" <?= $tipo == 'Furgão' ? 'selected' : '' ?>>Furgão</option>
                <option value="Esportivo" <?= $tipo == 'Esportivo' ? 'selected' : '' ?>>Esportivo</option>
            </select>
            <button type="submit">Filtrar</button>
        </form>
    </div>
        
    <main>
        <?php foreach ($itens as $item): ?>
            <!-- Div englobando todos cards para usar o grid -->
            <div class="container">
                <!-- Div referente ao card individualmente -->
                <div class="card">
                    <!-- Imagem do carro -->
                    <div class="imgCard">
                        <img src="imagens/<?= strtolower(htmlspecialchars($item['foto'])) ?>" alt="">
                    </div>
                    <!-- Conteudo de informações sobre cada carro -->
                    <div class="contentCard">
                        <h3><?= htmlspecialchars($item['marca'] . ' ' . $item['modelo']) ?></h3>
                        <h2 class="preco">R$<?= htmlspecialchars($item['preco']) ?></h2>
                        <!-- Link para mais informações-->
                        <a href="ver_carro/index.php?id=<?= $item['id'] ?>" class="ver">Ver mais</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.11.1/dist/sweetalert2.all.min.js"></script>
    <script>
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
                    window.location.href = "excluir/excluir.php";
                }
            });
        }
    </script>
</body>
</html>
