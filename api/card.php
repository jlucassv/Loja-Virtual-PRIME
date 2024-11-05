<?php 

 $card = true;
 require_once './preference.php';

if(isset($body->token)){

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.mercadopago.com/v1/payments',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
        "description": "Payment for product",
        "installments": '.$body->installments.',
        "payer": {
        "email": "'.$body->payer->email.'",
        "identification": {
            "type": "'.$body->payer->identification->type.'",
            "number": "'.$body->payer->identification->number.'"
        }
        },
        "issuer_id": "'.$body->issuer_id.'",
        "payment_method_id": "'.$body->payment_method_id.'",
        "token": "'.$body->token.'",
        "transaction_amount": '.$body->transaction_amount.'
      }',
        CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json',
        'Authorization: Bearer '.$accesstoken
        ),
    ));
    
    $response = curl_exec($curl);
    curl_close($curl);

    echo $response;

    die;

}



?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

     <link rel="stylesheet" href="../css/style.css">

    <title>Cartão</title>

    <script src="https://sdk.mercadopago.com/js/v2"></script>


</head>
<body>
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
   <input type="hidden" id="valor_payment" value="<?= $amount; ?>" >
   <input type="hidden" id="preference_id" value="<?= $preference_id; ?>">
    
    
    <div class="card-page">
        <div id="statusScreenBrick_container"></div>
         <div id="paymentBrick_container"></div>
    </div>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="../js/card.js"></script>

</body>
</html>