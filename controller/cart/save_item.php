<?php
session_start();
include_once('../../includes/config.php');

$data = json_decode(file_get_contents("php://input"), true);
$id_produto = $data['id_produto'];

// Verifica se o usuário está logado
if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    $sql = "SELECT id_usuario FROM usuarios WHERE email = '$email'";
    $resultado = $conexao->query($sql);
    $usuario = $resultado->fetch_assoc();
    $user_id = $usuario['id_usuario'];

    // Verifica se o produto já está no carrinho
    $sql_check = "SELECT id_produto, quantidade FROM carrinho WHERE id_usuario = '$user_id' AND id_produto = '$id_produto'";
    $resultado_check = $conexao->query($sql_check);
    
    if ($resultado_check->num_rows > 0) {
        // Se o produto já estiver no carrinho, aumenta a quantidade
        $produto = $resultado_check->fetch_assoc();
        $nova_quantidade = $produto['quantidade'] + 1;
        $sql_update = "UPDATE carrinho SET quantidade = '$nova_quantidade' WHERE id_usuario = '$user_id' AND id_produto = '$id_produto'";
        $conexao->query($sql_update);
    } else {
        // Se o produto não estiver no carrinho, insere um novo item
        $sql_insert = "INSERT INTO carrinho (id_usuario, id_produto, quantidade) VALUES ('$user_id', '$id_produto', 1)";
        $conexao->query($sql_insert);
    }
    
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["error" => "Usuário não autenticado."]);
}
?>
