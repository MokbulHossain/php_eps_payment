<?php 

class EPS {

    private $baseUrl = 'https://sandboxpgapi.eps.com.bd';
    private $userName = 'dt_merchant@eps.com.bd';
    private $password = '1234567@9';
    private $deviceTypeId = 4;
    private $hashkey = 'SFNLQHJlY2lwZXdhbGEjYTc3Zi1mOTQ5NWZhY2M2ZTZuZXQ=';
    private $merchent_id = '75BD77AE-59C6-4031-A309-E69E63D7272F';
    private $store_id = '228c769d-3177-43a7-8728-d60f907d8f12';
  
    function GenerateHash($payload,$hashkey){
        $utf8_key = utf8_encode($hashkey);
        $utf8_payload = utf8_encode($payload);
        $data = hash_hmac('sha512', $utf8_payload, $utf8_key,true);
        $hmac = base64_encode($data);
        return $hmac;
    }

    function GetToken() {

        $curl = curl_init();
        $req_body = array( 
            "userName"=>$this->userName, 
            "password"=>$this->password
        );
        $x_hash = $this->GenerateHash($this->userName,$this->hashkey);

        curl_setopt_array($curl, array(
        CURLOPT_URL => $this->baseUrl.'/v1/Auth/GetToken',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>json_encode($req_body),
        CURLOPT_HTTPHEADER => array(
            "x-hash: $x_hash",
            "Content-Type: application/json"
          ),
        ));

        $response = curl_exec($curl);

        if ($response === false) {
            $error = curl_error($curl);
            $info = curl_getinfo($curl);
            die("cURL request failed, error = {$error}; info = " . print_r($info, true));
        }

        $responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        /*
        * 4xx status codes are client errors
        * 5xx status codes are server errors
        */
        if ($responseCode >= 400) {
            die("HTTP Error in gettoken: ". $responseCode);
        }
        curl_close($curl);
        return json_decode($response);
    }

    function CreatePayment($payload = array()) {

        $getToken_response = $this->GetToken();
          if(!isset($getToken_response->token)){
  
              die("Access Denied!");
          }
  
          $curl = curl_init();
          $invoice_id = (string)time() ;
          $req_body = array(
 
                "deviceTypeId"=> $this->deviceTypeId,
				"merchantId" => $this->merchent_id,
                "storeId" => $this->store_id,
                "transactionTypeId" => 1,
                "financialEntityId" => 0,
                "version"=> "1",
                "transactionDate" => date('c'),
                "transitionStatusId" => 0,
                "valueD"=> "",
				"merchantTransactionId" => $invoice_id,
          );

          $req_body = array_merge($req_body, $payload);

          $x_hash = $this->GenerateHash($invoice_id,$this->hashkey);
          $token = $getToken_response->token;
  
          curl_setopt_array($curl, array(
          CURLOPT_URL => $this->baseUrl.'/v1/EPSEngine/InitializeEPS',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>json_encode($req_body),
          CURLOPT_HTTPHEADER => array(
              "x-hash: $x_hash",
              "Authorization: Bearer $token",
              "Content-Type: application/json"
            ),
          ));
  
          $response = curl_exec($curl);
  
          if ($response === false) {
              $error = curl_error($curl);
              $info = curl_getinfo($curl);
              die("cURL request failed, error = {$error}; info = " . print_r($info, true));
          }
  
          $responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
          /*
          * 4xx status codes are client errors
          * 5xx status codes are server errors
          */
          if ($responseCode >= 400) {
              die("HTTP Error in gettoken: ". $responseCode);
          }
          curl_close($curl);
          return json_decode($response);
    }

    //check payment status...
    function CheckPaymentStatus($invoice_id) {

        $getToken_response = $this->GetToken();
          if(!isset($getToken_response->token)){
  
              die("Access Denied!");
          }
  
          $curl = curl_init();

          $x_hash = $this->GenerateHash($invoice_id,$this->hashkey);
          $token = $getToken_response->token;
  
          curl_setopt_array($curl, array(
          CURLOPT_URL => $this->baseUrl.'/v1/EPSEngine/CheckMerchantTransactionStatus?merchantTransactionId='.$invoice_id,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_POSTFIELDS =>json_encode($req_body),
          CURLOPT_HTTPHEADER => array(
              "x-hash: $x_hash",
              "Authorization: Bearer $token",
              "Content-Type: application/json"
            ),
          ));
  
          $response = curl_exec($curl);
  
          if ($response === false) {
              $error = curl_error($curl);
              $info = curl_getinfo($curl);
              die("cURL request failed, error = {$error}; info = " . print_r($info, true));
          }
  
          $responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
          /*
          * 4xx status codes are client errors
          * 5xx status codes are server errors
          */
          if ($responseCode >= 400) {
              die("HTTP Error in gettoken: ". $responseCode);
          }
          curl_close($curl);
          return json_decode($response);
    }
  }

  
?>