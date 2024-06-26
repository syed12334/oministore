<?php

require_once 'AtomAES.php';

class AtompayResponse {

    private $respHashKey = "KEYRESP123657234";
    private $responseEncryptionKey = "75AEF0FA1B94B3C10D4F5B268F757F11";
    private $salt = "75AEF0FA1B94B3C10D4F5B268F757F11";
    public $data;
    
     public function __construct($config = array()) {
              $this->data = $config['data'];
               $this->merchId = $config['merchId'];
            $this->ResponseDecryptionKey = $config['ResponseDecryptionKey'];
    }

    /**
     * @return string
     */
    public function getRespHashKey()
    {
        return $this->respHashKey;
    }
    
    public function setResponseEncypritonKey($key){
        $this->responseEncryptionKey = $key;
    }
    
    public function setSalt($saltEntered){
        $this->salt = $saltEntered;
    }

    /**
     * @param string $respHashKey
     */
    public function setRespHashKey($respHashKey)
    {
        $this->respHashKey = $respHashKey;
    }

    public function decryptResponseIntoArray(){
    
        $atomenc = new AtomAES();
        $decrypted = $atomenc->decrypt($this->data, $this->ResponseDecryptionKey, $this->ResponseDecryptionKey);
        $jsonData = json_decode($decrypted, true);
     
        return $jsonData['payInstrument'];

    }

    public function validateResponse($responseParams)
    {
        echo "<pre>";print_r($responseParams);exit;
        if(is_array($responseParams) && !empty($responseParams)) {
            $str = $responseParams["mmp_txn"].$responseParams["mer_txn"].$responseParams["f_code"].$responseParams["prod"].$responseParams["discriminator"].$responseParams["amt"].$responseParams["bank_txn"];
                $signature =  hash_hmac("sha512",$str,$this->respHashKey,false);
                if($signature == $responseParams["signature"]){
                    return true;
                } else {
                    return false;
                }
            }else {
                return false;
            }
  
        

    }
}
