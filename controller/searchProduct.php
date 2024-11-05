<?php
// Conexão com o banco de dados
$dbHost = 'Localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'primedb';

$conexao = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

// Verifica a conexão
if ($conexao->connect_error) {
    die("Conexão falhou: " . $conexao->connect_error);
}

// Obtém a consulta da URL
$query = isset($_GET['query']) ? $_GET['query'] : '';

// Prepara a consulta SQL para buscar produtos
$sql = "SELECT * FROM produtos WHERE MATCH (nome, descricao) AGAINST(? IN NATURAL LANGUAGE MODE)";
$stmt = $conexao->prepare($sql);

$stmt->bind_param('s', $query);
$stmt->execute();
$result = $stmt->get_result();

// Array para armazenar os produtos
$products = array();

// Verifica se existem produtos
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

// Retorna os produtos em formato JSON
echo json_encode($products);

// Fecha a conexão
$stmt->close();
$conexao->close();
?>
