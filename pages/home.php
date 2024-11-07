<?php
session_start();
require_once '../apimercadopago.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/home.css">
    <link rel="icon" href="../assets/img/icons/logoLogin.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <title>PRIME</title>
</head>
<body>
    <div id="sucessMsgProduct">
        Produto adicionado no carrinho!
    </div>
    <div id="failedMsgProduct">
        O carrinho está vazio!
    </div>
    <header>
        <nav id="navBar">
            <div class="logoContainer">
                <a href="./home.php"><img id="logo" src="../assets/img/icons/logoLoginSemFundo.png"></a>
            </div>
            <div id="searchContainer">
                 <form id="searchForm" method="GET" autocomplete="off">
                    <input type="text" name="searchInput" id="searchInput" placeholder="Pesquisar">
                 </form>
                 <div id="suggestions" class="suggestions-box"></div> <!-- Caixa para as sugestões -->                   
            </div> 
            <div class="buttonsContainer">
                <?php
                // Verifique se a sessão do administrador está ativa
                if (isset($_SESSION['email']) && $_SESSION['email'] === 'admin@admin.com' && isset($_SESSION['password']) && $_SESSION['password'] === 'Admin123*') {
                    // Se a sessão estiver definida para o administrador, exiba o ícone
                    echo '<a href="./addProdutos.php">';
                    echo '<img class="navIcon" src="../assets/img/icons/prodicon.png" alt="iconCoracao">';
                    echo '</a>';
                }
                ?>
                <a href="">
                    <img  class="navIcon" src="../assets/img/icons/coracao.png" alt="iconCoracao">
                </a>
                
                
                <span class="cartIconDiv">
                    <img  id="cartIcon" class="navIcon" src="../assets/img/icons/carrinho.png" alt="iconCarrinho">
                </span>
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
            <a href="" class="submenuItems">Catálogo</a>  
     
        </div>
    </header>
    
    <main>
        <section>
            <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                    <img class="d-block w-100" src="../assets/img/img1.jpg" alt="Primeiro Slide">
                    </div>
                    <div class="carousel-item">
                    <img class="d-block w-100" src="../assets/img/img2.jpg"" alt="Segundo Slide">
                    </div>
                    <div class="carousel-item">
                    <img class="d-block w-100" src="../assets/img/img3.jpg" alt="Terceiro Slide">
                    </div>
                </div>
                <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Anterior</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Próximo</span>
                </a>
            </div>
        </section>
    </main>
    <div class="promotionDiv">
        <p>PRIMEIRA COMPRA? UTILIZE O CUPOM <strong>APP15</strong>!</p>
    </div>
    <div class="titleProductSection" id="titleOffer">
            <h1>Ofertas da semana</h1>
    </div>
    <section class="offersSection">
        <div class="offers">
            <div class="img-principal">
                <img src="../assets/img/offerImgPrincipal2.jpg" alt="">
            </div>
            <div class="img-secundaria">
                <img src="../assets/img/offerImgSecundaria1.jpg" alt="">
            </div>
            <div class="img-secundaria">
                <img src="../assets/img/offerImgSecundaria2.jpg" alt="">
            </div>
        </div>
    </section>
    <section>
        <div class="titleProductSection">
            <h1>Todos os Produtos</h1>
        </div>
        <div id="products" class="card-container"></div>
        <div id="cartMainDiv">
            <div class="cartHeader">
                <i id="cartCloseButton" class="bi bi-x-lg"></i>
                <h1>Carrinho</h1>
            </div>
            <div id="cartContainer"></div>
            <div class="totalDiv" >
                <div id="cartTotal"></div>
                <button type="button" id="cartBuyButton" class="btn btn-outline-primary" onclick="window.location.href='<?php echo $link_pagamento; ?>'">
                    Finalizar Pedido
                </button>
                    
            </div>
        </div>
    </section>
    <footer>
    </footer>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="../assets/js/home.js"></script>
    <script src="../assets/js/searchProducts.js"></script>
</body>
</html>