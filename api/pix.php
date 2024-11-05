<?php

if (!isset($_GET['vl'])) {
    die('vl nao existe');
} else {
    if ($_GET['vl'] == "" || !is_numeric($_GET['vl'])) {
        die('vl não pode ser vazio, e tem que ser numerico');
    } else {
        if ($_GET['vl'] < 1 && $_GET['vl'] > 100) {
            die('valor deve ser entre 1 e 100');
        }
    }
}

$config = require_once '../config.php';
require_once '../class/Conn.class.php';
require_once '../class/Payment.class.php';

// captura  o valor
$amount = (float) trim($_GET['vl']);

// instancia a classe pagamento
$payment = new Payment(1);

// criação do pagamento
$payCreate = $payment->addPayment($amount);

if ($payCreate) {

    // access token arquivo config.php
    $accesstoken = $config['accesstoken'];


    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.mercadopago.com/v1/payments',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => '{
        "description": "Payment for product",
        "external_reference": "'.$payCreate.'",
        "notification_url": "https://google.com",
        "payer": {
            "email": "test_user_123@testuser.com",
            "identification": {
            "type": "CPF",
            "number": "95749019047"
            }
        },
        "payment_method_id": "pix",
        "transaction_amount": ' . $amount . '
        }',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $accesstoken
        ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);

    $obj = json_decode($response);

    if (isset($obj->id)) {
        if ($obj->id != NULL) {

            $copia_cola = $obj->point_of_interaction->transaction_data->qr_code;
            $img_qrcode = $obj->point_of_interaction->transaction_data->qr_code_base64;
            $link_externo = $obj->point_of_interaction->transaction_data->ticket_url;
            $transaction_amount = $obj->transaction_amount;
            $external_reference = $obj->external_reference;

            echo "<h3>{$transaction_amount} #{$external_reference}</h3> <br />";
            echo "<img src='data:image/png;base64, {$img_qrcode}' width='200' /> <br />";
            echo "<textarea>{$copia_cola}</textarea> <br />";
            echo "<a href='{$link_externo}' target='_blank' >Link externo</a>";

        }
    }

}
