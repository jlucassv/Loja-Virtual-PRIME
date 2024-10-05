<?php
  // Enviar os dados obtidos dos inputs para o  banco de dados
   if(isset( $_POST['submit'])){
    // teste para verificar se está passando os dados
    // print_r( $_POST[ 'name' ]);
    
    // print_r( $_POST[ 'address' ]);
    
    // Envio de dados para o BD
    include_once('../includes/config.php');
 
    $nome = $_POST['name'];
    $endereco = $_POST['address'];
    $email = $_POST['emailSignUp'];
    $senha = password_hash($_POST['passwordConfirmation'], PASSWORD_DEFAULT);

    $result = mysqli_query($conexao, "INSERT INTO usuarios(nome, email, endereco, senha) VALUES ('$nome','$email', '$endereco', '$senha')");
   }
   


?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../assets/css/login.css">
  <link rel="icon" href="../assets/img/icons/logoLogin.png" type="image/x-icon">
  <title>PRIME</title>
</head>
<body>
  <div class="content">
    <a href="./home.php">    
      <img id="logoLogin" src="../assets/img/icons/logoLoginSemFundo.png" alt="PRIME">
    </a>
    <p id="login-p">A <strong>PRIME</strong> é sua loja online especializada em oferecer produtos de <strong>tecnologia de ponta</strong>. Encontre soluções inovadoras e eleve sua experiência!</p>
    
    <a href="./home.php">
      <button>Veja nossos produtos!</button>
    </a>
  </div>
  
  <div class="container">
    <div class="buttons">
      <div class="btnHover"></div>
      <button id="signInButton">Login</button>
      <button id="signUpButton">Cadastre-se</button>
    </div>
    
    <form id="signIn" action="../controller/controller.php" method="POST">
      <i class="fa-solid fa-user"></i>
      <input type="email" name="email"  id="emailSignIn" placeholder="Email" required autocomplete="off">
      <i class="fa-solid fa-lock"></i>
      <input type="password" name="password" id="senha" placeholder="Senha" required autocomplete="off">
      <i class="fas fa-eye" id="eyeIcon"></i>
      <input id="submitLogin" name="submitLogin" type="submit" value="Login">
      <div class="linkPassword">
        <a href="#">Esqueceu a senha?</a>      
      </div>
    </form>
   
    <form id="signUp" action="./login.php" method="POST">
        <i class="fas fa-user-circle"></i>
        <input type="text" name="name" id="name" class="inputToValidate" placeholder="Nome" required autocomplete="off" oninput="validarNome()">
        <span class="spans">Este campo deve conter pelo menos 3 caracteres.</span>
        <i class="fas fa-user-circle"></i>
        <input type="text" name="address" id="address" class="inputToValidate" placeholder="Endereço" required autocomplete="off" oninput="validarEndereco()">
        <span class="spans">Este campo deve ser preenchido.</span>
        <i class="fa-solid fa-user"></i>
        <input type="email" name="emailSignUp" id="emailSignUp" class="inputToValidate" placeholder="Email" required autocomplete="off" oninput="validateEmail()">
        <span class="spans">Utilize um email válido.</span>
        <i class="fa-solid fa-lock"></i>
        <input type="text" name="passwordConfirmation" id="passwordConfirmation" class="inputToValidate" placeholder="Senha" required autocomplete="off" oninput="validatePassword()">
        <span class="helpContainer">
        <i><img src="../assets/img/icons/ajuda.png" alt="help" class="helpIcon"></i>
            <ul class="items">
            <li class="item">A senha deve conter :</li>
            <li class="item">Ao menos 8 caracteres</li>
            <li class="item">Ao menos 1 letra maiúscula</li>
            <li class="item">Ao menos 1 caractere especial</li>
            </ul>
        </span>
        <i class="fas fa-unlock"></i>
        <input type="text" name="passwordConfirmation2" id="passwordConfirmation2" class="inputToValidate" placeholder="Confirme sua senha" required autocomplete="off" oninput="validatePasswordConfirmation()">
        <span class="spans">As senhas devem ser iguais.</span>
        
        <div class="checkbox">
            <input type="checkbox" name="terms" id="checkbox2">
            <label for="checkbox2">Aceito todos os Termos</label>
        </div>
        

        <input id="submit" name="submit" type="submit" value="Cadastrar">

    </form>
  </div>
  
  <script src="https://kit.fontawesome.com/7dd30168df.js" crossorigin="anonymous"></script>
  <script src="../assets/js/login.js"></script>
</body>
</html>