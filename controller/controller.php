<?php
session_start();
include_once('../includes/config.php');
if(!empty($_POST['email']) && !empty($_POST['password'])){
    
    $email = $_POST['email'];
    $senha = $_POST['password'];

    // Verificar se esse parâmetros existem no BD
    $sql = "SELECT * FROM usuarios WHERE email = '$email'"; 
    $resultado = $conexao->query($sql);
    $usuario = $resultado->fetch_assoc();

    if(password_verify($senha, $usuario['senha'])){
        // Usuário autenticado com sucesso
        $_SESSION['email'] = $email;
        $_SESSION['user_id'] = $usuario['id'];  // Salvar o ID do usuário na sessão
        $_SESSION['password'] = $senha;

        echo "<script>                    
                window.location.href = '../pages/home.php';
            </script>";
    } else {
        // Não há usuário com essas credenciais
        unset($_SESSION['email']);
        unset($_SESSION['password']);
        unset($_SESSION['user_id']);  // Remover o ID em caso de falha no login

        echo "<script>    
                alert('Usuário ou senha incorretos');
                window.location.href = '../pages/login.php';
            </script>";
    }
}
?>
