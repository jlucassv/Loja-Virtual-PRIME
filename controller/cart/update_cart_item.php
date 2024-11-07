<?php
session_start();

if (isset($_SESSION['email']) && isset($_POST['id_produto']) && isset($_POST['quantidade'])) {
    include_once('../../includes/config.php');
    $email = $_SESSION['email'];
    $id_produto = $_POST['id_produto'];
    $quantidade = $_POST['quantidade'];

    // Busca o id_usuario com base no email
    $sql = "SELECT id_usuario FROM usuarios WHERE email = '$email'";
    $resultado = $conexao->query($sql);
    $usuario = $resultado->fetch_assoc();
    $user_id = $usuario['id_usuario'];

    // Verifica se a quantidade é maior que 0
    if ($quantidade <= 0) {
        // Remove o item do carrinho se a quantidade for 0 ou menor
        $sql = "DELETE FROM carrinho WHERE id_usuario = '$user_id' AND id_produto = '$id_produto'";
        $conexao->query($sql);
        echo json_encode(['success' => true, 'message' => 'Item removido do carrinho']);
        exit;
    }

    // Atualiza a quantidade no carrinho
    $sql = "UPDATE carrinho SET quantidade = '$quantidade' WHERE id_usuario = '$user_id' AND id_produto = '$id_produto'";
    $conexao->query($sql);

    echo json_encode(['success' => true, 'message' => 'Quantidade atualizada']);
} else {
    echo json_encode(['success' => false, 'message' => 'Dados inválidos']);
}
?>
