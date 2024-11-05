<?php  

    $config = require_once '../config.php';
    require_once '../class/Conn.class.php';
    require_once '../class/Payment.class.php';
    require_once '../class/User.class.php';

    $accesstoken = $config['accesstoken'];

    $body   = json_decode(file_get_contents('php://input'));

    if(isset($body->data->id)){

        $id      = $body->data->id;
       
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://api.mercadopago.com/v1/payments/'.$id,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: Bearer '.$accesstoken
          ),
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);

        $payment = json_decode($response);

        if(isset($payment->id)){

            $payment_class             = new Payment();
            $payment_class->payment_id = $payment->external_reference;
            $payment_data              = $payment_class->get();

            if($payment_data){

                if($payment->status == "approved"){
                    // add balance user
                    $user = new User($payment_data->user_id);
                    $addBalance = $user->addBalance((float)$payment_data->valor);

                }

                $payment_class->setStatusPayment($payment->status);

            }


        }


    }