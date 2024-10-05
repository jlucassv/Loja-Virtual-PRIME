
<?php
// Conexão com o banco de dados
    $dbHost = 'Localhost';
    $dbUsername = 'root';
    $dbPassword = '';
    $dbName = 'primedb';

    $conexao = new mysqli($dbHost,$dbUsername,$dbPassword, $dbName);

// Query para selecionar todos os produtos
$sql = "SELECT * FROM produtos";
$result = mysqli_query($conexao, $sql);

// Array para armazenar os produtos
$products = array();

// Verifica se existem produtos
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $products[] = $row;
    }
}

// Retorna os produtos em formato JSON
echo json_encode($products);
?>