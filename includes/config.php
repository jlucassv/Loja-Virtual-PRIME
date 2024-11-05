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
    return [
        'accesstoken' => 'TEST-2596423661439279-110405-445e8f92f230457c373d9183f0c71475-327334435',
        'url_notification_sdk' => 'https://95d60c603db969.lhr.life/curso/sdk/notification.php',
        'url_notification_api' => 'https://95d60c603db969.lhr.life/curso/api/notification.php'
    ];
?>