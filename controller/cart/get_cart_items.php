<?php
session_start();
include_once('../../includes/config.php');

if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    // Busca o id_usuario com base no e-mail na sessão
    $sql = "SELECT id_usuario FROM usuarios WHERE email = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $usuario = $resultado->fetch_assoc();

    if ($usuario) {
        $user_id = $usuario['id_usuario'];
        
        // Busca os itens do carrinho para o usuário específico
        $query = "SELECT c.quantidade, p.nome, p.preco, p.imagem, p.id_produto
                  FROM carrinho c
                  JOIN produtos p ON c.id_produto = p.id_produto
                  WHERE c.id_usuario = ?";
        $stmt = $conexao->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $cartItems = [];
        while ($row = $result->fetch_assoc()) {
            $cartItems[] = $row;
        }

        echo json_encode($cartItems);
    } else {
        echo json_encode([]); // Retorna um array vazio se o usuário não for encontrado
    }
} else {
    echo json_encode([]); // Retorna um array vazio se o e-mail não estiver na sessão
}
?>
