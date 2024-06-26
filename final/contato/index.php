<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Garagem</title>
    <link rel="stylesheet" href="style_contato.css">
</head>
    <body> 
        <nav>
            <ul>
                <li><a href="../index.php">Home</a></li>
                <li><a href="../add_carro/index.php">Adicionar Carro</a></li>
                <li><a href="index.php">Contato</a></li>
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
                    <h2>Fale conosco</h2>
                </section>    

                <!-- action="email.php" Linha a ser adicionada na tag <form> caso estivesse em um servido real-->
                <form  class="form" method="post">

                    <div class="form-content">
                        <label for="">Nome:</label>
                        <input type="text" placeholder="Digite seu nome" name="nome">
                    </div>   

                    <div class="form-content">
                        <label for="">Email:</label>
                        <input type="email" placeholder="Digite seu email para contato" name="email">
                    </div>

                    <div class="form-content-msg">
                        <label for="">Mensagem:</label>
                        <textarea placeholder="Deixe sua mensagem" name="mensagem"></textarea>
                    </div>   
                    
                    <button type="submit">Enviar</button>
                    
                </form>
            </div>
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
                        window.location.href = "../excluir/excluir.php";
                    }
                });
            }
        </script>

    </body>
</html>