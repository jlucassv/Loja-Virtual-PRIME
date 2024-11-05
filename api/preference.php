<?php


$config = require_once '../includes/config.php';
require_once '../class/Conn.class.php';
require_once '../class/Payment.class.php';

 // access token arquivo config.php
 $accesstoken = $config['accesstoken'];

$body = json_decode(file_get_contents("php://input"));

// se nao for requisição do formulario do cartao
if (!isset($body->token)) {

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


    // captura  o valor
    $amount = (float) trim($_GET['vl']);

    // instancia a classe pagamento
    $payment = new Payment(1);

    // criação do pagamento
    $payCreate = $payment->addPayment($amount);

    if ($payCreate) {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.mercadopago.com/checkout/preferences',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                "back_urls": {
                    "success": "https://google.com/success",
                    "pending": "https://google.com/pending",
                    "failure": "https://google.com/failure"
                },
                "external_reference": "' . $payCreate . '",
                "notification_url": "https://google.com",
                "auto_return": "approved",
                "items": [
                    {
                    "title": "Dummy Title",
                    "description": "Dummy description",
                    "picture_url": "http://www.myapp.com/myimage.jpg",
                    "category_id": "car_electronics",
                    "quantity": 1,
                    "currency_id": "BRL",
                    "unit_price": ' . $amount . '
                    }
                ],
                "payment_methods": {
                    "excluded_payment_methods": [
                    {"id": "pix"}
                    ],
                    "excluded_payment_types": [
                    {"id": "ticket"}
                    ]
                }
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

                if (isset($card)) {
                    $preference_id = $obj->id;
                } else {

                    $link_externo = $obj->init_point;
                    $external_reference = $obj->external_reference;

                    echo "<h3>{$amount} #{$external_reference}</h3> <br />";
                    echo "<a href='{$link_externo}' target='_blank' >Link externo</a>";

                }
            }
        }
    }

}