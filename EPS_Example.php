<?php
  
  require_once('./EPS_Payment.php');

  try{

    $EpsPayment = new EPS();
    $payload = [
        "totalAmount" => 10,
        "ipAddress" => "37.111.218.149",
        
        "successUrl" => "successUrl",
        "failUrl" => "failUrl",
        "cancelUrl" => "cancelUrl",

        "customerName" => "Jone De",
        "customerEmail" => "JoneDe@gmail.com",
        "customerAddress" => "Looking up an address",
        "customerAddress2" => "Looking up an address",
        "customerCity" => "Dhaka",
        "customerState" => "Dhaka",
        "customerPostcode" => "1000",
        "customerCountry" => "Bangladesh",
        "customerPhone"=> "01700000000",

        "shipmentName"=> "shipmentName",
        "shipmentAddress"=> "Looking up an address",
        "shipmentAddress2"=> "Looking up an address",
        "shipmentCity"=> "Dhaka",
        "shipmentState"=> "Dhaka",
        "shipmentPostcode"=> "1000",
        "shipmentCountry"=> "Bangladesh",

        "valueA"=> "customer_id",
        "valueB"=> "local_transaction_id",
        "valueC"=> "order_id-123",


        "shippingMethod"=> "Home Delivery",
        "noOfItem"=> "2",
        "productName"=> "product name 1, product name 2",
        "productProfile"=> "product profile 1, product profile 2",
        "productCategory"=> "product Category 1, product Category 2",
    ];

    $payment_initiate_response = $EpsPayment->CreatePayment($payload);
    print_r($payment_initiate_response);

    if($payment_initiate_response->RedirectGatewayURL){

        //redirect RedirectGatewayURL on browser..
    }

   }
    catch (exception $e) {
        print_r($e);
    }


?>