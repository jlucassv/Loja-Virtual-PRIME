<?php
// conexao com o banco de dados.

    $dbHost = 'Localhost';
    $dbUsername = 'root';
    $dbPassword = '';
    $dbName = 'primedb';

    $conexao = new mysqli($dbHost,$dbUsername,$dbPassword, $dbName);

    // teste de conexao com o banco de dados
    // if($conexao->connect_errno){
    //     echo "Erro";
    // } else {
    //     echo "Conexão efetuada!";
    // }
    
?>