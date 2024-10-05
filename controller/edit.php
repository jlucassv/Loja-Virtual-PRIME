<?php
    include_once('../includes/config.php');

    if(!empty($_GET['id_produto']))
    {
        $id = $_GET['id_produto']; // pegar o Id do produto
        $sqlSelect = "SELECT * FROM produtos WHERE id_produto=$id";
        $result = $conexao->query($sqlSelect);
        if($result->num_rows > 0)
        {
            while($product_data = mysqli_fetch_assoc($result))
            {
                $nome = $product_data['nome'];
                $descricao = $product_data['descricao'];
                $preco = $product_data['preco'];
                $estoque = $product_data['quantidade_estoque'];
                $imagemProduto = $product_data['imagem'];
            }
        }
        else
        {
            header('Location: ../pages/addProdutos.php');
        }
    }
    else
    {
        header('Location: ../pages/addProdutos.php');
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
                <h1>Editar Produto </h1>
            </div>
            <div class="card">
                <div class="card-body">
                        
                    <form id="addProductForm" enctype="multipart/form-data" action="./save.php" method="POST" autocomplete="off">
                        <label for="nome">Nome do Produto:</label><br>
                        <input class="form-control" type="text" id="nomeProduto" name="nomeProduto" value="<?php echo $nome ?>"><br>
                    
                        <label for="descricao">Descrição:</label><br>
                        <input class="form-control" type="text" id="descricao" name="descricao" value="<?php echo $descricao ?>"><br>
                    
                        <label for="preco">Preço:</label><br>
                        <input class="form-control" type="text" id="preco" name="preco" value="<?php echo $preco ?>">
                        <br>
                        <label for="estoque">Estoque:</label><br>
                        <input class="form-control" type="number" id="estoque" name="estoque" value="<?php echo $estoque ?>">
                        <br>
                        <div class="form-group">
                            <label for="exampleFormControlFile1">Escolha uma imagem para o Produto.</label>
                            <input type="file" class="form-control-file" id="exampleFormControlFile1" name="imagemProduto"> 
                        </div>
                        <input type="hidden" name="id" value="<?php echo $id ?>">
                        <br> 
                        <input class="btn btn-primary" id="submit" name="save" type="submit" value="Salvar" onclick="salvarProduto()">
                        
                    </form>
                </div>
              </div>
        </section>
       
    </main>
    
    <footer></footer>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="../assets/js/produtos.js"></script>
</body>
</html>