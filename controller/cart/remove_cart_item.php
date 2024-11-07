<?php
session_start();

if (isset($_SESSION['email']) && isset($_POST['id_produto'])) {
    include_once('../../includes/config.php');
    $email = $_SESSION['email'];
    $id_produto = $_POST['id_produto'];

    // Busca o id_usuario com base no email
    $sql = "SELECT id_usuario FROM usuarios WHERE email = '$email'";
    $resultado = $conexao->query($sql);
    $usuario = $resultado->fetch_assoc();
    $user_id = $usuario['id_usuario'];

    // Remove o item do carrinho
    $sql = "DELETE FROM carrinho WHERE id_usuario = '$user_id' AND id_produto = '$id_produto'";
    $conexao->query($sql);

    echo json_encode(['success' => true, 'message' => 'Produto removido']);
} else {
    echo json_encode(['success' => false, 'message' => 'Dados inválidos']);
}
?>
