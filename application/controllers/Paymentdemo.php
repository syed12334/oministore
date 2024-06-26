<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Paymentdemo extends CI_Controller {
  public $data;
   public function __construct()
    {
        date_default_timezone_set("Asia/Kolkata");
        parent::__construct();
        $this->load->helper("utility_helper");
        $this->load->model("master_db");
        $this->load->model("home_db");
        $this->load->model("cart_db");
        
    }
    public function pay()
    {

        
          $transactionId =  sprintf("%06d", mt_rand(1, 999999));
           $payUrl = "https://paynetzuat.atomtech.in/ots/aipay/auth";
          //$payUrl = "https://caller.atomtech.in/ots/aipay/auth";
          $params =array(
                    "Login" => LOGIN,
                    "Password" => Password,
                    "url"=>$payUrl,
                    "ProductId" => ProductId,
                    "Amount" => "50.00",
                    "TransactionCurrency" => TransactionCurrency,
                    "TransactionAmount" => "50.00",
                    "ReturnUrl" => base_url("paymentdemo/confirm"),
                    "ClientCode" => "007",
                    "TransactionId" => $transactionId,
                    "udf1" => "Syed Afroz",
                    "CustomerEmailId" => "developerweb50@gmail.com",
                    "CustomerMobile" => "9986571768", 
                    "udf2" => "Bengaluru North", 
                    "CustomerAccount" => rand(12345,3535533),
                    "ReqHashKey" => ReqHashKey,
                    "mode" => MODE,  
                    "RequestEncypritonKey" =>RequestEncypritonKey,
                    "Salt" => Salt,
                    "ResponseDecryptionKey" =>ResponseDecryptionKey,
                    "ResponseSalt" =>ResponseSalt,
                    "udf3" =>"",
                    "udf4" =>"",
                    "udf5" =>"",
                    "mmp_txn"=>rand(234555,366363)
            );
      $this->load->library("AtompayRequest",$params); 
      $atomid =  $this->atompayrequest->payNow();
      $this->data['atomTokenId'] = $atomid;
      $this->data['transactionId'] = $transactionId;
      $this->data['amount'] ="50.00";
      $this->load->view('demo_pay_page',$this->data);
    }
    
    public function confirm()
    {

// $bodys = file_get_contents('php://input');
// echo $bodys;exit;
        $data['data'] = $_POST['encData'];
         $data['merchId'] = "317156";
         $data['ResponseDecryptionKey'] = "75AEF0FA1B94B3C10D4F5B268F757F11";

        $this->load->library('AtompayResponse',$data); 

        $this->atompayresponse->setRespHashKey("KEYRESP123657234");
        $this->atompayresponse->setResponseEncypritonKey("75AEF0FA1B94B3C10D4F5B268F757F11");
        $this->atompayresponse->setSalt("75AEF0FA1B94B3C10D4F5B268F757F11");
      
       

          $arrayofdata = $this->atompayresponse->decryptResponseIntoArray($_POST['encData']);
           $in['encdata'] = $_POST['encData'];
          $in['payarray'] = json_encode($arrayofdata);
          $this->master_db->insertRecord('ordersdata',$in);
          $this->transactionapi("317156",$_POST['encData']);

          
        
        
     //  if(!empty($arrayofdata)){
        
        
     //  }else{
          
     //       log_message('error', 'Data received is not proper encoded data ! Data received ='.$_POST['encdata']);
     //       echo "Payment failed due to techical error caused during making the payment";
     // }  
        
           
    }
    
    public function transactionapi($merid,$encdata) {
      $curl = curl_init();
      curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://caller.atomtech.in/ots/payment/status?merchId='.$merid.'&encData='.$encdata.'',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
          'Content-Type: application/json'
        ),
      ));
      $response = curl_exec($curl);
      curl_close($curl);
      $content = parse_str($response,$queryString);
        $pay_array = json_encode($queryString);
        $data['data'] = $queryString['encData'];
         $data['merchId'] = "317156";
         $data['ResponseDecryptionKey'] = "75AEF0FA1B94B3C10D4F5B268F757F11";
        $this->load->library('AtompayResponse',$data); 
        $arrayofdata = $this->atompayresponse->decryptResponseIntoArray($queryString['encData']);
        echo "<pre>";print_r($arrayofdata);
    }
}
