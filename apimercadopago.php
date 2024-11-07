<?php

function gerarLinkPagamento() {
    // Token de acesso da API do Mercado Pago
    $access_token = "TEST-2596423661439279-110405-445e8f92f230457c373d9183f0c71475-327334435";

    // Dados do pagamento
    $payment_data = [
        "items" => [
            [
                "id" => "1",
                "title" => "Camisa",
                "quantity" => 1,
                "currency_id" => "BRL",
                "unit_price" => 259.99
            ]
        ],
        "back_urls" => [
            "success" => "http://localhost:8000/pages/home.php",
            "failure" => "http://localhost:8000/pages/home.php",
            "pending" => "http://localhost:8000/pages/home.php"
        ],
        "auto_return" => "all"
    ];

    // Configuração da requisição cURL
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api.mercadopago.com/checkout/preferences",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer $access_token",
            "Content-Type: application/json"
        ],
        CURLOPT_POSTFIELDS => json_encode($payment_data),
        CURLOPT_SSL_VERIFYPEER => false, // Desativa a verificação SSL
        CURLOPT_SSL_VERIFYHOST => false  // Desativa a verificação do host
    ]);
    
    // Executa a requisição e obtém a resposta
    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "Erro: " . $err;
        return null;
    } else {
        $response_data = json_decode($response, true);
        return $response_data["init_point"] ?? null; // Retorna o link de pagamento
    }
}

// link
$link_pagamento = gerarLinkPagamento();

