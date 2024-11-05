<?php
    // trabalhando session
    // print_r($_REQUEST);
    session_start();
    include_once('../includes/config.php');
    if(!empty($_POST['email']) && !empty($_POST['password'])){
        
        $email = $_POST['email'];
        $senha = $_POST['password'];

        // verificar se esse parametros existem no BD
        $sql = "SELECT * FROM usuarios WHERE email = '$email'"; 
        $resultado = $conexao->query($sql);
        $usuario = $resultado->fetch_assoc();

        if(password_verify($senha, $usuario['senha'])){
             // Usuário autenticado com sucesso
             $_SESSION['email'] = $email;
             $_SESSION['password'] = $senha;
             echo "<script>                    
                     window.location.href = '../pages/home.php';
                 </script>";
        } else {
             // Não há usuário com essas credenciais
             unset($_SESSION['email']);
             unset($_SESSION['password']);            
             echo "<script>    
                     alert('Usuário ou senha incorretos');
                     window.location.href = '../pages/login.php';
                 </script>";
        }
    }
?>