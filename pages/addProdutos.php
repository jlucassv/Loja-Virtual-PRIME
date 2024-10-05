<?php
session_start();
//    print_r($_SESSION);
include_once('../includes/config.php');
if (!isset($_SESSION['email']) || $_SESSION['email'] !== 'admin@admin.com' || !isset($_SESSION['password']) || $_SESSION['password'] !== 'Admin123*') {
    // Se a sessão não estiver definida para o administrador, redirecione-o para home.php
    header("Location: home.php");
    exit;
}

  
  if(isset( $_POST['submit'])){
    

    $nomeProduto = $_POST['nomeProduto'];
    $descricaoProduto = $_POST['descricao'];
    $precoProduto = $_POST['preco'];
    $estoqueProduto = $_POST['estoque'];
    
    if(isset($_FILES['imagemProduto'])){
        $arquivo = $_FILES['imagemProduto'];
        $pasta = "../assets/arquivos/";
        $nomeDoArquivo = $arquivo['name'];
        $extensao = strtolower(pathinfo($nomeDoArquivo,  PATHINFO_EXTENSION));
        $path = $pasta . $nomeDoArquivo;
        move_uploaded_file($arquivo['tmp_name'], $path);
    }
   
    $result = mysqli_query($conexao, "INSERT INTO produtos(nome, descricao, preco, quantidade_estoque, imagem) VALUES ('$nomeProduto', '$descricaoProduto', '$precoProduto', '$estoqueProduto', '$path')");

    echo "<script>                    
                    window.location.href = './addProdutos.php';
            </script>";
    exit();
   }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/addProdutos.css">
    <link rel="icon" href="../assets/img/icons/logoLogin.png" type="image/x-icon">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <title>PRIME</title>
</head>
<body>
    <header>
        <nav id="navBar">
            <div class="logoContainer">
                <a href="./home.php"><img id="logo" src="../assets/img/icons/logoLoginSemFundo.png"></a>
            </div>
            <div id="searchContainer">
                <input type="text" name="searchInput" id="searchInput" placeholder="Pesquisar">
            </div>            
            <div class="buttonsContainer">
                <a href="">
                    <img  class="navIcon" src="../assets/img/icons/prodicon.png" alt="iconProd">
                </a>
                <a href="">
                    <img  class="navIcon" src="../assets/img/icons/coracao.png" alt="iconCoracao">
                </a>
                <a href="">
                    <img  class="navIcon" src="../assets/img/icons/carrinho.png" alt="iconCarrinho">
                </a>
            </div>
            <div class="loginContainer">               
                <span>
                    <img class="navIcon" src="../assets/img/icons/perfil.png" alt="perfil">
                </span>
                <span id="spanLogin">
                    <?php echo isset($_SESSION['email']) ? 'Olá, '.$_SESSION['email'] : '<a id="linkLogin" href="./login.php">Faça <strong class="linkLoginStrong">LOGIN</strong> ou <strong class="linkLoginStrong">CADASTRE-SE</strong></a>'; ?>                    
                </span>
                <span>
                    <a href="../controller/logout.php">
                        <img class="navIcon" src="../assets/img/icons/sair.png" alt="logOff">    
                    </a>                
                </span>
            </div>
        </nav>
        <div class="submenuNav">
            <a href="" class=" ">Catálogo</a>  
     
        </div>
    </header>
    <main>
        <section>
            <div class="title">
                <h1>Produtos </h1>
                <h3>Lista de todos os produtos </h3>
            </div>
            <div class="card">
                <div class="card-header">
                  Novo Produto
                </div>
                <div class="card-body">
                        
                    <form id="addProductForm" enctype="multipart/form-data" action="./addProdutos.php" method="POST" autocomplete="off">
                        <label for="nome">Nome do Produto:</label><br>
                        <input class="form-control" type="text" id="nomeProduto" name="nomeProduto"><br>
                    
                        <label for="descricao">Descrição:</label><br>
                        <textarea class="form-control" id="descricao" name="descricao"></textarea><br>
                    
                        <label for="preco">Preço:</label><br>
                        <input class="form-control" type="text" id="preco" name="preco">
                        <br>
                        <label for="estoque">Estoque:</label><br>
                        <input class="form-control" type="number" id="estoque" name="estoque">
                        <br>
                        <div class="form-group">
                            <label for="exampleFormControlFile1">Escolha uma imagem para o Produto.</label>
                            <input type="file" class="form-control-file" id="exampleFormControlFile1" name="imagemProduto">
                        </div>
                        
                        <br> 
                        <input class="btn btn-primary" id="submit" name="submit" type="submit" value="Salvar" onclick="salvarProduto()">
                        
                    </form>
                </div>
              </div>
        </section>
        <section class="tableSection">
            <?php
            include_once('../includes/config.php');
                // Consulta SQL
                $sql = "SELECT * FROM produtos";
                $result = $conexao->query($sql);

                if ($result->num_rows > 0) {
                    // Exibindo os dados da tabela com estilização Bootstrap
                    echo "<div class='table-responsive'>";
                    echo "<table class='table table-striped'>";
                    echo "<thead class='thead-dark'>";
                    echo "<tr><th>ID</th><th>Imagem do Produto</th><th>Nome do Produto</th><th>Descrição</th><th>Preço</th><th>Estoque</th><th>Ações</th></tr>";
                    echo "</thead>";
                    echo "<tbody>";
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                            echo "<td>".$row["id_produto"]."</td>";
                            echo "<td><img width='100px' src='".$row["imagem"]."'></td>";
                            echo "<td>".$row["nome"]."</td>";
                            echo "<td>".$row["descricao"]."</td>";
                            echo "<td>".$row["preco"]."</td>";
                            echo "<td>".$row["quantidade_estoque"]."</td>";
                            echo "<td>
                                    <a href='../controller/edit.php?id_produto=$row[id_produto]' class='btn btn-outline-primary'>
                                        <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-pencil-fill' viewBox='0 0 16 16'>
                                            <path d='M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.5.5 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11z'/>
                                        </svg>
                                    </a>
                                    <a href='../controller/delete.php?id_produto=$row[id_produto]' class='btn btn-outline-danger'>
                                        <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-trash3-fill' viewBox='0 0 16 16'>
                                            <path d='M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5'/>
                                        </svg>
                                    </a></td>";
                        echo "</tr>";
                    }
                    echo "</tbody>";
                    echo "</table>";
                    echo "</div>";
                } else {
                    echo "0 resultados";
                }
                
            ?>
        </section>
    </main>
    
    <footer></footer>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="../assets/js/produtos.js"></script>
</body>
</html>