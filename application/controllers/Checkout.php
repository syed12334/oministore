<?php
defined('BASEPATH') OR exit('No direct script access allowed');
ob_start();
include_once('easebuzz-lib/easebuzz_payment_gateway.php');
include_once('easebuzz-lib/payment.php');
class Checkout extends CI_Controller {
	protected $data;
	public function __construct() {
		date_default_timezone_set("Asia/Kolkata");
        parent::__construct();
        $this->load->helper("utility_helper");
        $this->load->model("master_db");
        $this->load->model("home_db");
        $this->load->model("cart_db");
        $this->data["detail"] = "";
        $this->data['session'] = CUSTOMER_SESSION; 
        $this->load->library('form_validation');
        if($this->session->userdata($this->data['session'])) {
            $new = $this->session->userdata($this->data['session']);
            $getWishlist = $this->master_db->getRecords('wishlist',['uid'=>$new['id'],'pid !='=>0],'*');
            $this->data['wishcount'] = $getWishlist;
            $this->data['wallet'] = $this->master_db->getRecords('wallet',['user_id'=>$new['id']],'*');
            $this->data['walletamount'] = $this->master_db->getRecords('wallet_amount',['id !='=>-1],'*');
            $this->data['totalreferrals'] = $this->master_db->getRecords('wallet_total_referrals',['user_id'=>$new['id']],'*');
        }else {
          $this->data['wishcount'] =[];
          $this->data['wallet'] = [];
          $this->data['walletamount'] =[];
          $this->data['totalreferrals'] =[];
        }
        if(isset($_GET['referral_code']) && !empty($_GET['referral_code'])) {
          $ref = icchaDcrypt($_GET['referral_code']);
          $this->session->set_userdata('referral_code',$ref);
        }
        $this->data["category"] = $this->master_db->getRecords(
            "category",
            ["status" => 0],
            "id as cat_id,page_url,cname,ads_image,icons",
            "order_by asc",
            "",
            "",
            "14"
        );
        $this->data["header"] = $this->load->view(
            "includes/header1",
            $this->data,
            true
        );
        $this->data["header1"] = $this->load->view(
            "includes/header1",
            $this->data,
            true
        );
        $this->data["footer"] = $this->load->view(
            "includes/footer1",
            $this->data,
            true
        );
        $this->data["js"] = $this->load->view("jsFile", $this->data, true);
        if (!$this->session->userdata($this->data['session'])) {
            redirect(base_url().'login');
        }
	}
    public function index() {
         if (!$this->session->userdata($this->data['session'])) {
            redirect(base_url().'login');
        }else {
             if($this->session->userdata('pincodes') && $this->session->userdata('pincodes') !='') {
                $pin = $this->session->userdata('pincodes');
                $getPincode = $this->master_db->getRecords('pincodes',['pincode'=>$pin],'*');
                
                if($this->cart->total() >=300) {
                        $this->data['getdelivery'] = $del = 0;
                }else {
                         $this->data['getdelivery'] = $del = 50;
                }
             }else {
                $this->data['getdelivery'] = $del ="";
             }
            //echo "<pre>";print_r($this->session->userdata(CUSTOMER_SESSION));
            $this->data['states'] = $this->master_db->getRecords('states',['status'=>0],'id,name');
            $this->data['cities'] = $this->master_db->getRecords('cities',['status'=>0],'id,cname');
            $this->data['area'] = $this->master_db->getRecords('area',['status'=>0],'id,areaname');
             $this->load->view('checkout',$this->data);
        }
    }
    public function confirmorder() {
        //echo "<pre>";print_r();exit;
        $details = $this->session->userdata($this->data['session']);
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
            $this->form_validation->set_rules('bfname','First Name','trim|required|regex_match[/^([a-zA-Z ])+$/i]|max_length[20]',['required'=>'Billing First name is required','regex_match'=>'Invalid billing first name','max_length'=>'Maximum 20 characters are allowed']);
            $this->form_validation->set_rules('bemail','Billing Email','trim|required|valid_email|regex_match[/^[_A-Za-z0-9-]+(\.[_A-Za-z0-9-]+)*@[A-Za-z0-9-]+(\.[A-Za-z0-9-]+)*(\.[A-Za-z]{2,10})$/]',['required'=>'Billing email is required','valid_email'=>'Enter valid email','regex_match'=>'Invalid email']);
            $this->form_validation->set_rules('bphone','Product Title','trim|required|numeric|exact_length[10]',['numeric'=>'Only numeric values are allowed','exact_length'=>'10 Numbers are allowed','required'=>'Billing phone number is required']);
            $this->form_validation->set_rules('bpincode','Product Code','trim|required|numeric|exact_length[6]',['required'=>'Billing pincode is required','exact_length'=>'Minimum 6 numbers are allowed','numeric'=>'Only numeric values are allowed']);
            $this->form_validation->set_rules('baddress','Billing Address ','trim|required',['required'=>'Please enter billing address']);
            if($this->input->post('shipped') ==1) {
                    $this->form_validation->set_rules('sfname','First Name','trim|required|regex_match[/^([a-zA-Z ])+$/i]|max_length[20]',['required'=>'Shipping First name is required','regex_match'=>'Invalid shipping first name','max_length'=>'Maximum 20 characters are allowed']);
                    $this->form_validation->set_rules('semail','Shipping Email','trim|required|valid_email|regex_match[/^[_A-Za-z0-9-]+(\.[_A-Za-z0-9-]+)*@[A-Za-z0-9-]+(\.[A-Za-z0-9-]+)*(\.[A-Za-z]{2,10})$/]',['required'=>'Shipping email is required','valid_email'=>'Enter valid email','regex_match'=>'Invalid email']);
                    $this->form_validation->set_rules('sphone','Product Title','trim|required|numeric|exact_length[10]',['numeric'=>'Only numeric values are allowed','exact_length'=>'10 Numbers are allowed','required'=>'Shipping phone number is required']);
                    $this->form_validation->set_rules('spincode','Product Code','trim|required|numeric|exact_length[6]',['required'=>'Shipping pincode is required','exact_length'=>'Minimum 6 numbers are allowed','numeric'=>'Only numeric values are allowed']);
                    $this->form_validation->set_rules('saddress','Shipping Address ','trim|required',['required'=>'Please enter shipping address']);
            }
            $this->form_validation->set_rules('pmode','Payment mode','trim|required',['required'=>'Please select payment mode']);
            if($this->form_validation->run() ==TRUE) {
                $bfname = $this->input->post('bfname');
                $blname = $this->input->post('blname');
                $bemail = $this->input->post('bemail');
                $bphone = $this->input->post('bphone');
                $bpincode = $this->input->post('bpincode');
                $bstate = $this->input->post('bstate');
                $bcity = $this->input->post('bcity');
                $baddress = $this->input->post('baddress');
                $shipped = $this->input->post('shipped');
                $pmode = $this->input->post('pmode');
                $sfname = $this->input->post('sfname');
                $slname = $this->input->post('slname');
                $semail = $this->input->post('semail');
                $sphone = $this->input->post('sphone');
                $spincode = $this->input->post('spincode');
                $sstate = $this->input->post('sstate');
                $scity = $this->input->post('scity');
                $sarea = $this->input->post('sarea');
                $saddress = $this->input->post('saddress');
                $orderfrom = $this->input->post('orderfrom');
                $pincodeAmount = "";$taxamt =[];
                if($shipped ==1) {
                         $getSpincode = $this->master_db->sqlExecute('select c.noofdays,amount from pincodes p left join cities c on c.id = p.cid where p.pincode='.$spincode.' and p.status=0 and c.status=0');
                        if(count($getSpincode) ==0) {
                            $array = ['status'=>false,'msg'=>'Shipping is not available for entered pincode '.$spincode.'','csrf_token'=> $this->security->get_csrf_hash()];
                            echo json_encode($array);exit;
                        }else {
                            $pincodeAmount .= $getSpincode[0]->amount;
                        }
                }else {
                        $getBpincode = $this->master_db->sqlExecute('select c.noofdays,amount from pincodes p left join cities c on c.id = p.cid where p.pincode='.$bpincode.' and p.status=0 and c.status=0');
                        if(count($getBpincode) ==0) {
                            $array = ['status'=>false,'msg'=>'Shipping is not available for entered pincode '.$bpincode.'','csrf_token'=> $this->security->get_csrf_hash()];
                            echo json_encode($array);exit;
                        }else {
                            $pincodeAmount .= $getBpincode[0]->amount;
                        }
                }
                if(isset($bfname) && !empty($bfname) && isset($bemail) && !empty($bemail) && isset($bphone) && !empty($bphone) && isset($bpincode) && !empty($bpincode) && isset($bstate) && !empty($bstate) && isset($bcity) && !empty($bcity) && isset($baddress) && !empty($baddress)) {
                    $totalAmount = "";$deliveryamount ="";
                    if($this->cart->total() >=300) {
                        $deliveryamount .=0;
                    }else {
                        $deliveryamount .=50;
                    }
                    $orders['user_id'] = $details['id'];
                    if($this->session->userdata('discount')) {
                        if($this->session->userdata('discount_type') ==0) {
                             $amount = floatval($this->cart->total() + $deliveryamount ) - $this->session->userdata('discount');
                            if($amount >0) {
                                $orders['totalamount'] =  sprintf("%.2f",$amount);
                            }else {
                                $orders['totalamount'] = 0;
                            }
                           $totalAmount .=sprintf("%.1f",  $amount);
                        }else if($this->session->userdata('discount_type') ==1) {
                            $amount = floatval($this->cart->total() + $deliveryamount )* $this->session->userdata('discount') / 100;
                            $tma = $this->cart->total() - $amount +$deliveryamount;
                            
                            if($tma >0) {
                                $orders['totalamount'] =  sprintf("%.1f",$tma);
                            }else {
                                $orders['totalamount'] = 0;
                            }
                           
                           $totalAmount .=sprintf("%.1f",  $amount);
                        }else {
                            $amount = floatval($this->cart->total() + $deliveryamount ) - $this->session->userdata('discount');
                            if($amount >0) {
                                $orders['totalamount'] =  sprintf("%.2f",$amount);
                            }else {
                                $orders['totalamount'] = 0;
                            }
                           $totalAmount .=sprintf("%.1f",  $amount);
                        }
                        $orders['discount'] = $this->session->userdata('discount');
                        $orders['discount_type'] = $this->session->userdata('discount_type');
                    }else {
                        $orders['totalamount'] =  sprintf("%.1f", $this->cart->total() + $deliveryamount);
                        $totalAmount .=sprintf("%.1f",$this->cart->total() + $deliveryamount);
                    }
                    $orders['delivery_charges'] = $deliveryamount;
                    $orders['subtotal'] = $this->cart->total();
                    $orders['pmode'] = $pmode;
                    $orders['order_date'] = date('Y-m-d H:i:s');
                    $orders['datewise'] = date('Y-m-d');
                    $orders['lead_from'] = "Online";
                    $oid = $this->master_db->insertRecord('orders',$orders);
                    if($oid >0) {
                        $orderNo = $this->cart_db->generateOrderNo($oid);
                        $db = array('orderid' => $orderNo);
                        $this->master_db->updateRecord('orders',$db,['oid'=>$oid]);
                         $getNeworders = $this->master_db->getRecords('orders',['oid'=>$oid],'*');
                         if(is_array($this->cart->contents()) && !empty($this->cart->contents())) {
                            foreach ($this->cart->contents() as $key => $cart) {
                                $oproducts['oid'] = $oid;
                                $oproducts['qty'] = $cart['qty'];
                                $oproducts['name'] = $cart['name'];
                                $oproducts['pcode'] = $cart['pcode'];
                                $oproducts['price'] = $cart['price'];
                                $oproducts['image'] = $cart['image'];
                                $oproducts['purl'] = $cart['purl'];
                                $oproducts['pid'] = $cart['pid'];
                                $oproducts['psid'] = $cart['id'];
                                $oproducts['stock'] = $cart['stock'];
                                $this->master_db->insertRecord('order_products',$oproducts);
                            }
                        }
                        $bills['oid'] = $oid;
                        $bills['bfname'] = $bfname;
                        $bills['bemail'] = $bemail;
                        $bills['bphone'] = $bphone;
                        $bills['bpincode'] = $bpincode;
                        $bills['bstate'] = $bstate;
                        $bills['bcity'] = $bcity;
                        $bills['baddress'] = $baddress;
                        if($shipped ==1) {
                            $bills['sfname'] = $sfname;
                            $bills['semail'] = $semail;
                            $bills['sphone'] = $sphone;
                            $bills['spincode'] = $spincode;
                            $bills['sstate'] = $sstate;
                            $bills['scity'] = $scity;
                            $bills['saddress'] = $saddress;
                        }
                        $this->master_db->insertRecord('order_bills',$bills);
                        if($pmode ==1) {
                            if($getNeworders[0]->totalamount >0) {
                            $transactionId =  sprintf("%06d", mt_rand(1, 999999));
                            $payment['oid'] = $oid;
                            $payment['transaction_id'] = $transactionId;
                            $payment['pstatus'] = 'pending';
                            $payment['status'] = 3;
                            $this->master_db->insertRecord('payment_log',$payment);
                            $txnid = 'SWARNA'.rand(123,100);
                           $razor_amt = floatval($getNeworders[0]->totalamount);
                           //$razor_amt = floatval(50);
                                $newamt = "2.0";
                                $newqs = sprintf("%.2f", $razor_amt);
                                $payUrl = PaymentUrl;
                                $clientid = "00".$oid;
                                $params =array(
                                        "Login" => LOGIN,
                                        "Password" => Password,
                                        "url"=>$payUrl,
                                        "ProductId" => ProductId,
                                        "Amount" => $newqs,
                                        "TransactionCurrency" => TransactionCurrency,
                                        "TransactionAmount" => "50.00",
                                        "ReturnUrl" =>base_url('orderpayment'),
                                        "ClientCode" => $clientid,
                                        "TransactionId" => $transactionId,
                                        "udf1" => $bfname,
                                        "CustomerEmailId" => $bemail,
                                        "CustomerMobile" => $bphone, 
                                        "udf2" => $baddress, 
                                        "CustomerAccount" =>$oid,
                                        "ReqHashKey" => ReqHashKey,
                                        "mode" => MODE,  
                                        "RequestEncypritonKey" =>RequestEncypritonKey,
                                        "Salt" => Salt,
                                        "ResponseDecryptionKey" =>ResponseDecryptionKey,
                                        "ResponseSalt" =>ResponseSalt,
                                        "udf3" =>$orderNo,
                                        "udf4" =>$oid,
                                        "udf5" =>"",
                                        "mmp_txn"=>rand(234555,366363)
                                    );
                              $this->load->library("AtompayRequest",$params); 
                              $atomid =  $this->atompayrequest->payNow();
                             $new = base_url().'checkout/paymentlink?atomid='.icchaEncrypt($atomid).'&amount='.icchaEncrypt($newqs).'&email='.icchaEncrypt($bemail).'&phone='.icchaEncrypt($bphone).'&oid='.icchaEncrypt($oid);                                  
                             $array = ['status'=>true,'msg'=>'Processing please wait...','url'=>$new,'csrf_token'=> $this->security->get_csrf_hash()];
                         }else {
                            $array = ['status'=>true,'msg'=>'Processing please wait...','url'=>base_url().'checkout/paymentSuccess/'.icchaEncrypt($getNeworders[0]->oid),'csrf_token'=> $this->security->get_csrf_hash()];
                         }
                        }
                        else if($pmode ==6) {
                            if($getNeworders[0]->totalamount >0) {
                                  $txn_id   = substr(hash('sha512', mt_rand() . microtime()), 0, 20);
                            $txn_id   = strtoupper($txn_id);
                            $payment['oid'] = $oid;
                            $payment['transaction_id'] = $txn_id;
                            $payment['pstatus'] = 'pending';
                            $payment['status'] = 3;
                            $this->master_db->insertRecord('payment_log',$payment);
                            $txnid = 'SWARNA'.rand(123,100);
                           //$razor_amt = floatval($getNeworders[0]->totalamount);
                         $razor_amt = floatval(2);
                             $easebuzzObj = new Easebuzz(MERCHANT_KEY, SALT, ENV);
                                    $txnid = 'SWARNA'.rand(1,100);
                                    $newamt = "2.0";
                                    $newqs = sprintf("%.2f", $razor_amt);
                                    $postData = array( 
                                        "txnid" => $txn_id, 
                                        "amount" => $newqs, 
                                        "firstname" =>$bfname, 
                                        "email" => $bemail, 
                                        "phone" => $bphone, 
                                        "productinfo" => "For test", 
                                        "surl" =>base_url().'ordereasebuzz', 
                                        "furl" => base_url().'ordereasebuzz',
                                        "udf1" =>$orderNo,
                                        "udf2" =>$oid
                                    );
                                   // echo "<pre>";print_r($postData);exit;
                                   $new = $easebuzzObj->initiatePaymentAPI($postData); 
                                  
                             $array = ['status'=>true,'msg'=>'Processing please wait...','url'=>$new,'csrf_token'=> $this->security->get_csrf_hash()];
                         }else {
                            $array = ['status'=>true,'msg'=>'Processing please wait...','url'=>base_url().'checkout/paymentSuccess/'.icchaEncrypt($getNeworders[0]->oid),'csrf_token'=> $this->security->get_csrf_hash()];
                         }
                            //$this->master_db->insertRecord('payment_log',$payment);
                        }
                        else if($pmode ==2) {
                            $this->master_db->updateRecord('orders',['status'=>5],['oid'=>$oid]);
                            $payment['oid'] = $oid;
                            $payment['pstatus'] = 'COD';
                            $payment['status'] = 2;
                            $this->master_db->insertRecord('payment_log',$payment);
                             $array = ['status'=>true,'msg'=>'Order placed','url'=>base_url().'checkout/orderProcess/'.$orderNo.'','csrf_token'=> $this->security->get_csrf_hash()];
                        }
                        else if($pmode ==3) {
                            $getWallet = $this->master_db->getRecords('wallet',['user_id'=>$details['id']],'*');
                            if(count($getWallet) >0) {
                                if($getWallet[0]->amount >0) {
                                    if($getNeworders[0]->totalamount > $getWallet[0]->amount) {
                                       // $differenceAmt = $getNeworders[0]->totalamount - $getWallet[0]->amount;
                                       $differenceAmt = 1;
                                         $newqs = sprintf("%.1f", $differenceAmt);
                                          $transactionId =  sprintf("%06d", mt_rand(1, 999999));
                                        $payment['oid'] = $oid;
                                        $payment['transaction_id'] = $transactionId;
                                        $payment['pstatus'] = 'wallet';
                                        $payment['status'] = 8;
                                        $this->master_db->insertRecord('payment_log',$payment);
                                         $payUrl = PaymentUrl;
                                         $clientid = "00".$oid;
                                        $params =array(
                                            "Login" => LOGIN,
                                            "Password" => Password,
                                            "url"=>$payUrl,
                                            "ProductId" => ProductId,
                                            "Amount" => $newqs,
                                            "TransactionCurrency" => TransactionCurrency,
                                            "TransactionAmount" => $newqs,
                                            "ReturnUrl" =>base_url('orderpayment'),
                                            "ClientCode" => $clientid,
                                            "TransactionId" => $transactionId,
                                            "udf1" => $bfname,
                                            "CustomerEmailId" => $bemail,
                                            "CustomerMobile" => $bphone, 
                                            "udf2" => $baddress, 
                                            "CustomerAccount" =>$oid,
                                            "ReqHashKey" => ReqHashKey,
                                            "mode" => MODE,  
                                            "RequestEncypritonKey" =>RequestEncypritonKey,
                                            "Salt" => Salt,
                                            "ResponseDecryptionKey" =>ResponseDecryptionKey,
                                            "ResponseSalt" =>ResponseSalt,
                                            "udf3" =>$orderNo,
                                            "udf4" =>$oid,
                                            "udf5" =>"",
                                            "mmp_txn"=>rand(234555,366363)
                                         );
                                          $this->load->library("AtompayRequest",$params); 
                                          $atomid =  $this->atompayrequest->payNow();
                                         $new = base_url().'checkout/paymentlink?atomid='.icchaEncrypt($atomid).'&amount='.icchaEncrypt($newqs).'&email='.icchaEncrypt($bemail).'&phone='.icchaEncrypt($bphone).'&oid='.icchaEncrypt($oid);
                                         $array = ['status'=>true,'msg'=>'Processing please wait...','url'=>$new,'csrf_token'=> $this->security->get_csrf_hash()];
                                    }else {
                                        $this->master_db->updateRecord('orders',['status'=>5],['oid'=>$oid]);
                                        $payment['oid'] = $oid;
                                        $payment['pstatus'] = 'Wallet';
                                        $payment['status'] = 1;
                                        $this->master_db->insertRecord('payment_log',$payment);
                                        $array = ['status'=>true,'msg'=>'Amount Has Been Debited From Wallet','url'=>base_url().'checkout/orderProcess/'.$orderNo.'','csrf_token'=> $this->security->get_csrf_hash()];
                                    }
                                }else {
                                     $array = ['status'=>false,'msg'=>'Wallet amount is 0','csrf_token'=> $this->security->get_csrf_hash()];
                                }
                            }else {
                                $array = ['status'=>false,'msg'=>'No wallet amount','csrf_token'=> $this->security->get_csrf_hash()];
                            }
                        }
                    }
                }else {
                    $array = ['status'=>false,'msg'=>'Required fields are missing','csrf_token'=> $this->security->get_csrf_hash()];
                }
            }else {
                 $array = array(
                'formerror'   => false,
                'bfname_error' => form_error('bfname'),
                'bemail_error' => form_error('bemail'),
                'bphone_error' => form_error('bphone'),
                'bpincode_error' => form_error('bpincode'),
                'bstate_error' => form_error('bstate'),
                'bcity_error' => form_error('bcity'),
                'barea_error' => form_error('barea'),
                'baddress_error' => form_error('baddress'),
                'sfname_error' => form_error('sfname'),
                'semail_error' => form_error('semail'),
                'sphone_error' => form_error('sphone'),
                'spincode_error' => form_error('spincode'),
                'sstate_error' => form_error('sstate'),
                'scity_error' => form_error('scity'),
                'sarea_error' => form_error('sarea'),
                'pmode_error' => form_error('pmode'),
                'saddress_error' => form_error('saddress'),
                'csrf_token'=> $this->security->get_csrf_hash()
               );
            }
           echo json_encode($array);
       } 
    }
    public function orderProcess() {
         $this->load->library('Mail');
        $oid = $this->uri->segment(3);
        if(isset($oid) && !empty($oid)) {
           $getOrders =  $this->master_db->getRecords('orders',['orderid'=>$oid],'totalamount,pmode,oid,orderid');
            $pmode = $getOrders[0]->pmode;
            if($pmode ==1) {
                $this->data['orders'] = $getOrders;
                $this->data['payment'] = $this->master_db->getRecords('payment_log',['oid'=>$getOrders[0]->oid],'*');
                $this->load->view('payment',$this->data);
            }
            else if($pmode ==2) {
              if($this->session->userdata('referral_code')) {
                $new = $this->session->userdata($this->data['session']);
                $wid = $this->session->userdata('referral_code');
                $getSelfwallet = $this->master_db->getRecords('wallet',['wallet_id'=>$wid],'*');
                if(count($getSelfwallet) >0) {
                    $getWallet = $this->master_db->getRecords('wallet',['wallet_id'=>$wid,'user_id'=>$new['id']],'*');
                    if(count($getWallet) >0) {
                    }else {
                          $getwamount = $this->master_db->getRecords('wallet_amount',['id !='=>-1],'*');
                        if(count($getwamount) >0 && $getwamount[0]->amount >0) {
                            $wd['amount'] = $getSelfwallet[0]->amount + $getwamount[0]->amount;
                            $this->master_db->updateRecord('wallet',$wd,['wallet_id'=>$wid]);
                        }  
                        $refcount['wallet_id'] = $wid;
                        $refcount['user_id'] = $getSelfwallet[0]->user_id;
                        $refcount['referral_user_id'] = $new['id'];
                        $refcount['created_at'] = date('Y-m-d H:i:s');
                        $this->master_db->insertRecord('wallet_total_referrals',$refcount);     
                    }
                }
             }
                $this->master_db->updateRecord('orders',['status'=>5],['oid'=>$oid]);
                    $this->data['orders'] = $orders = $this->master_db->getRecords('orders',['orderid'=>$oid],'*');
             $getStockdetails = $this->master_db->sqlExecute('select op.qty,ps.stock,ps.pro_size as psid  from order_products op left join product_size ps on ps.pro_size = op.psid where op.oid='.$orders[0]->oid.'');
                   if(count($getStockdetails) >0) {
                        foreach ($getStockdetails as $key => $value) {
                            $stock = $value->stock - $value->qty;
                            $up['stock'] = $stock;
                            $this->master_db->updateRecord('product_size',$up,['pro_size'=>$value->psid]);
                        }
                   }
                    $this->data['orderproducts'] = $this->master_db->getRecords('order_products',['oid'=>$orders[0]->oid],'*');
                    $this->data['payment'] = $this->master_db->getRecords('payment_log',['oid'=>$orders[0]->oid],'*');
                    $this->data['billing'] = $this->master_db->sqlExecute('select * from order_bills where oid='.$orders[0]->oid.'');
                     $this->data['order_bill'] = $order_bills =  $this->master_db->getRecords('order_bills',['oid'=>$orders[0]->oid],'*');
                    $social_links = $this->master_db->getRecords('sociallinks',['status'=>0],'*');
                    $this->data['social'] = $social_links;
                    $html = $this->load->view('order_success',$this->data,true);
                 //   $this->mail->sendMail($order_bills[0]->bemail,$html,'Your Order Summary');
                    $this->load->view('order_summary',$this->data); 
                    $this->session->unset_userdata('discount');
                    $this->session->unset_userdata('pamount');
                   // $this->session->unset_userdata('referral_code');
                    $this->cart->destroy();
            }
            else if($pmode ==3) {
                $new = $this->session->userdata($this->data['session']);
                $this->master_db->updateRecord('orders',['status'=>5],['oid'=>$oid]);
                    $this->data['orders'] = $orders = $this->master_db->getRecords('orders',['orderid'=>$oid],'*');
                            $getStockdetails = $this->master_db->sqlExecute('select op.qty,ps.stock,ps.pro_size as psid  from order_products op left join product_size ps on ps.pro_size = op.psid where op.oid='.$orders[0]->oid.'');
                               if(count($getStockdetails) >0) {
                                    foreach ($getStockdetails as $key => $value) {
                                        $stock = $value->stock - $value->qty;
                                        $up['stock'] = $stock;
                                        $this->master_db->updateRecord('product_size',$up,['pro_size'=>$value->psid]);
                                    }
                               }
                               if($this->session->userdata('referral_code')) {
                                    $new = $this->session->userdata($this->data['session']);
                                    $wid = $this->session->userdata('referral_code');
                                    $getSelfwallet = $this->master_db->getRecords('wallet',['wallet_id'=>$wid],'*');
                                    if(count($getSelfwallet) >0) {
                                        $getWallet = $this->master_db->getRecords('wallet',['wallet_id'=>$wid,'user_id'=>$new['id']],'*');
                                        if(count($getWallet) >0) {
                                            
                                        }else {
                                              $getwamount = $this->master_db->getRecords('wallet_amount',['id !='=>-1],'*');
                                            if(count($getwamount) >0 && $getwamount[0]->amount >0) {
                                                $wd['amount'] = $getSelfwallet[0]->amount + $getwamount[0]->amount;
                                                $this->master_db->updateRecord('wallet',$wd,['wallet_id'=>$wid]);
                                            }  
                                            $refcount['wallet_id'] = $wid;
                                            $refcount['user_id'] = $getSelfwallet[0]->user_id;
                                            $refcount['referral_user_id'] = $new['id'];
                                            $refcount['created_at'] = date('Y-m-d H:i:s');
                                            $this->master_db->insertRecord('wallet_total_referrals',$refcount);     
                                        }
                                    }
                                 }
                   $getWallet = $this->master_db->getRecords('wallet',['user_id'=>$orders[0]->user_id],'*');
                   if(count($getWallet) >0) {
                        if($getWallet[0]->amount >0) {
                            $totalAmt = $orders[0]->totalamount;
                            $wupate['amount'] = $getWallet[0]->amount - $totalAmt;
                            $this->master_db->updateRecord('wallet',$wupate,['user_id'=>$orders[0]->user_id]);
                        }
                   }

                    $this->data['orderproducts'] = $this->master_db->getRecords('order_products',['oid'=>$orders[0]->oid],'*');
                    $this->data['payment'] = $this->master_db->getRecords('payment_log',['oid'=>$orders[0]->oid],'*');
                    $this->data['billing'] = $this->master_db->sqlExecute('select * from order_bills where oid='.$orders[0]->oid.'');
                     $this->data['order_bill'] = $order_bills =  $this->master_db->getRecords('order_bills',['oid'=>$orders[0]->oid],'*');
                    $social_links = $this->master_db->getRecords('sociallinks',['status'=>0],'*');
                    $this->data['social'] = $social_links;
                    $html = $this->load->view('order_success',$this->data,true);
                    $this->mail->send_sparkpost_attach($html,[$order_bills[0]->bemail],'Your Order Summary');
                    $this->load->view('order_summary',$this->data); 
                    $this->session->unset_userdata('discount');
                    $this->session->unset_userdata('pamount');
                    $this->session->unset_userdata('referral_code');
                    $this->cart->destroy();
            }
        }
    }
    public function paymentResponse() {
        if($_SERVER['REQUEST_METHOD']=='POST'){
             $this->session->unset_userdata('discount');
                             $this->session->unset_userdata('pamount');
                             $this->cart->destroy(); 
             $this->load->library('Mail');
                $val = 0;
                $payid = $this->input->post('paymentID');
                $orderID = $this->input->post('orderID');
                $signature = $this->input->post('signature');
                $oid = $this->input->post('oid');
                if ($payid == "" || $orderID == "" || $signature == "")
                {
                    echo '<div class="alert alert-danger alert-dismissable"><button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>Registration un-successful. Payment Details not found</div>';
                }
                else{
                    $generated_signature = hash_hmac('sha256',  $orderID . '|' . $payid, TEST_MERCHANT_SECRET);
                    if($generated_signature == $signature){
                        $db =['pay_id'=>$payid,'hash'=>$signature,'status'=>1];
                         $update = $this->master_db->updateRecord('payment_log',$db,['oid'=>$oid]);
                         if($update >0) {
                            $this->data['orders'] = $this->master_db->getRecords('orders',['oid'=>$oid],'*');
                            $this->data['orderproducts'] = $this->master_db->getRecords('order_products',['oid'=>$oid],'*');
                            $this->data['order_bill'] = $order_bills =  $this->master_db->getRecords('order_bills',['oid'=>$oid],'*');
                            $this->data['payment'] = $this->master_db->getRecords('payment_log',['oid'=>$oid],'*');
                            $this->data['billing'] = $this->master_db->sqlExecute('select * from order_bills where oid='.$oid.'');
                            $social_links = $this->master_db->getRecords('sociallinks',['status'=>0],'*');
                            //echo $this->db->last_query();exit;
                            $this->data['social'] = $social_links;
                            $html = $this->load->view('order_success',$this->data,true);
                            //$this->mail->sendMail($order_bills[0]->bemail,$html,'Your Order Summary');
                             $this->load->view('order_summary',$this->data); 
                             $this->session->unset_userdata('discount');
                             $this->session->unset_userdata('pamount');
                             $this->session->unset_userdata('referral_code');
                             $this->cart->destroy();                       
                        }
                    }
                    else{
                        $db =['status'=>-1];
                         $update = $this->master_db->updateRecord('payment_log',$db,['oid'=>$oid]);
                         if($update >0) {
                            echo "Payment un-successful";
                         }
                    }
                }
        }
    }
    public function fetchPaymentStatus() {
         $api = new Api(TEST_MERCHANT_KEY, TEST_MERCHANT_SECRET);
         $orderID = "order_KlQJymQ0fFrmnb";
         $order  = $api->order->fetch($orderID)->payments();
        $orderDetails = $order->toArray();
        echo "<pre>";print_r($orderDetails);
    }
    public function paymentPayload() {
        $encdata = $_POST['encData'];
        $data['data'] = $encdata;
        $data['merchId'] = LOGIN;
        $data['ResponseDecryptionKey'] = ResponseDecryptionKey;
        $this->load->library('AtompayResponse',$data);
        $arrayofdata = $this->atompayresponse->decryptResponseIntoArray($encdata);
        $statusCode =  $arrayofdata['responseDetails']['statusCode'];
        $foid =  $arrayofdata['payDetails']['custAccNo'];
        $oid =$arrayofdata['extras']['udf4'];
        $order = $this->master_db->getRecords("orders", array("oid"=>$oid), "*");
        $this->load->library('mail');
        if(is_array($arrayofdata) && !empty($arrayofdata)) {
            $pay_array = json_encode($arrayofdata);
            $payid = $arrayofdata['payModeSpecificData']['bankDetails']['bankTxnId'];
            $signature = $arrayofdata['payDetails']['signature'];
            if($statusCode =="OTS0000") {
                  if($this->session->userdata($this->data['session'])) {
                    $new = $this->session->userdata($this->data['session']);
                    $getWallet = $this->master_db->getRecords('wallet',['user_id'=>$new['id']],'*');
                    if(count($getWallet) >0) {
                        if($getWallet[0]->amount >0) {
                            if($order[0]->totalamount > $getWallet[0]->amount) {
                                $differenceAmt = $order[0]->totalamount - $getWallet[0]->amount;
                                $this->master_db->updateRecord('wallet',['amount'=>0],['user_id'=>$new['id']]);
                                    $this->master_db->updateRecord('orders',['wallet_amt'=>$differenceAmt],['oid'=>$order[0]->oid]);
                            }
                        }
                    }
                 }
                 if($this->session->userdata('referral_code')) {
                    $new = $this->session->userdata($this->data['session']);
                    $wid = $this->session->userdata('referral_code');
                    $getSelfwallet = $this->master_db->getRecords('wallet',['wallet_id'=>$wid],'*');
                    if(count($getSelfwallet) >0) {
                        $getWallet = $this->master_db->getRecords('wallet',['wallet_id'=>$wid,'user_id'=>$new['id']],'*');
                        if(count($getWallet) >0) {
                        }else {
                            $getwamount = $this->master_db->getRecords('wallet_amount',['id !='=>-1],'*');
                            if(count($getwamount) >0 && $getwamount[0]->amount >0) {
                                $wd['amount'] = $getSelfwallet[0]->amount + $getwamount[0]->amount;
                                $this->master_db->updateRecord('wallet',$wd,['wallet_id'=>$wid]);
                            }  
                            $refcount['wallet_id'] = $wid;
                            $refcount['user_id'] = $getSelfwallet[0]->user_id;
                            $refcount['referral_user_id'] = $new['id'];
                            $refcount['created_at'] = date('Y-m-d H:i:s');
                            $this->master_db->insertRecord('wallet_total_referrals',$refcount);     
                        }
                    }
                  }
                  $payme['pay_array'] = $pay_array;
                  $payme['pay_id'] = $payid;
                  $payme['signature'] = $signature;
                  $payme['endata'] = $encdata;
                  $payme['pstatus'] = "success";
                  $payme['status'] = 1;
                   $this->master_db->updateRecord('payment_log',$payme,['oid'=>$oid]);
                    $ord['status'] = 5;
                    $this->master_db->updateRecord('orders',$ord,['oid'=>$oid]);
                    if($this->session->userdata('couponcodeid')) {
                        $vids = $this->session->userdata('couponcodeid');
                        $getVouchers = $this->master_db->getRecords('vouchers',['id'=>$vids],'*');
                        if(count($getVouchers) >0) {
                            if($getVouchers[0]->type ==2) {
                                $this->master_db->updateRecord('vouchers',['individual_limit_count'=>1],['id'=>$vids]);
                            }
                            if($getVouchers[0]->usage_limit >0) {
                                $vuser['coupon_id'] = $getVouchers[0]->id;
                                $vuser['user_id'] = $order[0]->user_id;
                                $this->master_db->insertRecord('vouchers_users',$vuser);
                            }
                        }
                    }
                    $getStockdetails = $this->master_db->sqlExecute('select op.qty,ps.stock,ps.pro_size as psid  from order_products op left join product_size ps on ps.pro_size = op.psid where op.oid='.$oid.'');
                       if(count($getStockdetails) >0) {
                            foreach ($getStockdetails as $key => $value) {
                                if($value->stock >0) {
                                     $stock = $value->stock - $value->qty;
                                    $up['stock'] = $stock;
                                    $this->master_db->updateRecord('product_size',$up,['pro_size'=>$value->psid]);
                                }
                            }
                       }
                    $this->data['orders'] = $orders = $this->master_db->getRecords('orders',['oid'=>$oid],'*');
                    //echo $this->db->last_query();exit;
                    $this->data['orderproducts'] = $this->master_db->getRecords('order_products',['oid'=>$oid],'*');
                    $this->data['payment'] = $this->master_db->getRecords('payment_log',['oid'=>$oid],'*');
                    $this->data['billing'] = $this->master_db->sqlExecute('select * from order_bills where oid='.$oid.'');
                     $this->data['order_bill'] = $order_bills =  $this->master_db->getRecords('order_bills',['oid'=>$oid],'*');
                     $this->data['payment'] = $this->master_db->getRecords('payment_log',['oid'=>$oid],'*');
                    $social_links = $this->master_db->getRecords('sociallinks',['status'=>0],'*');
                    $this->data['social'] = $social_links;
                     $html = $this->load->view('order_success',$this->data,true);
                    // $this->mail->sendMail($order_bills[0]->bemail,$html,'Your Order Summary');
                    $this->load->view('order_payment_summary',$this->data); 
                    $this->session->unset_userdata('discount');
                    $this->session->unset_userdata('pamount');
                    $this->session->unset_userdata('referral_code');
                    $this->cart->destroy();
            }
            else if($statusCode =="OTS0101") {
               $ord['status'] = 4;
                $this->master_db->updateRecord('orders',$ord,['oid'=>$foid]);
                $this->data['orders'] = $orders = $this->master_db->getRecords('orders',['oid'=>$foid],'*');
                $payme['pay_array'] = $pay_array;
                $payme['endata'] = $encdata;
                $payme['pstatus'] = "cancelled";
                $payme['status'] = 4;
                $payid = $this->master_db->updateRecord('payment_log',$payme,['oid'=>$foid]);
                $this->load->view('order_cancel',$this->data);
            }
            else if($statusCode =="OTS0600") {
                $ord['status'] = -1;
                $this->master_db->updateRecord('orders',$ord,['oid'=>$oid]);
                $this->data['orders'] = $orders = $this->master_db->getRecords('orders',['oid'=>$oid],'*');
                $payme['pay_array'] = $pay_array;
                $payme['endata'] = $encdata;
                $payme['pstatus'] = "failure";
                $payme['status'] = -1;
                $payid = $this->master_db->updateRecord('payment_log',$payme,['oid'=>$oid]);
                $this->load->view('order_cancel',$this->data);
            }
            else if($statusCode =="OTS0551" || $statusCode =="OTS0201" || $statusCode =="OTS0401" || $statusCode =="OTS0451" || $statusCode =="OTS0501" || $statusCode =="OTS0301" || $statusCode =="OTS0351" || $statusCode =="OTS0951") {
                 $ord['status'] = 1;
                $this->master_db->updateRecord('orders',$ord,['oid'=>$foid]);
                $this->data['orders'] = $orders = $this->master_db->getRecords('orders',['oid'=>$foid],'*');
                $payme['pay_array'] = $pay_array;
                $payme['endata'] = $encdata;
                $payme['pstatus'] = "pending";
                $payme['status'] = 3;
                $payid = $this->master_db->updateRecord('payment_log',$payme,['oid'=>$foid]);
                $this->load->view('order_cancel',$this->data);
            }
        } 
    }
    public function order_failure() {
        echo "<h2>Payment has been failed</h2>";
    }
    public function paymentSuccess() {
         $this->load->library('mail');
        $id = icchaDcrypt($this->uri->segment(3));
          $this->data['orders'] = $orders = $this->master_db->getRecords('orders',['oid'=>$id],'*');
                    if($this->session->userdata('couponcodeid')) {
                        $vids = $this->session->userdata('couponcodeid');
                        $getVouchers = $this->master_db->getRecords('vouchers',['id'=>$vids],'*');
                        if(count($getVouchers) >0) {
                            if($getVouchers[0]->type ==2) {
                                $this->master_db->updateRecord('vouchers',['individual_limit_count'=>1],['id'=>$vids]);
                            }
                            if($getVouchers[0]->usage_limit >0) {
                                $vuser['coupon_id'] = $getVouchers[0]->id;
                                $vuser['user_id'] = $orders[0]->user_id;
                                $this->master_db->insertRecord('vouchers_users',$vuser);
                            }
                        }
                    }
                    $this->master_db->updateRecord('orders',['status'=>5],['oid'=>$id]);
                    $getStockdetails = $this->master_db->sqlExecute('select op.qty,ps.stock,ps.pro_size as psid  from order_products op left join product_size ps on ps.pro_size = op.psid where op.oid='.$id.'');
                   if(count($getStockdetails) >0) {
                        foreach ($getStockdetails as $key => $value) {
                            if($value->stock >0) {
                                 $stock = $value->stock - $value->qty;
                                $up['stock'] = $stock;
                                $this->master_db->updateRecord('product_size',$up,['pro_size'=>$value->psid]);
                            }
                        }
                   }
                    $this->data['orders'] = $orders = $this->master_db->getRecords('orders',['oid'=>$id],'*');
                
                    $this->data['orderproducts'] = $this->master_db->getRecords('order_products',['oid'=>$id],'*');
                       
                     
                    $this->data['billing'] = $this->master_db->sqlExecute('select * from order_bills where oid='.$id.'');
                     $this->data['order_bill'] = $order_bills =  $this->master_db->getRecords('order_bills',['oid'=>$id],'*');
                     $this->data['payment'] = $this->master_db->getRecords('payment_log',['oid'=>$id],'*');
                    // echo $this->db->last_query();exit;
                    $social_links = $this->master_db->getRecords('sociallinks',['status'=>0],'*');
                    $this->data['social'] = $social_links;
                     $html = $this->load->view('order_success',$this->data,true);
                  // $this->mail->sendMail($order_bills[0]->bemail,$html,'Your Order Summary');
                    $this->load->view('order_payment_summary',$this->data); 
                   $this->session->unset_userdata('discount');
                    $this->session->unset_userdata('pamount');
                  $this->cart->destroy();
        
    }
    public function wallet() {
        if($this->session->userdata($this->data['session'])) {
            $new = $this->session->userdata($this->data['session']);
            $this->data['phone'] = $new['phone'];
            $this->data['wallet'] = $this->master_db->getRecords('wallet',['user_id'=>$new['id']],'*');
            $this->load->view('wallet',$this->data);
        }
        
    }

        public function paymentlink() {

            if(isset($_GET['atomid']) && isset($_GET['amount']) && isset($_GET['email']) && isset($_GET['phone'])) {
               $this->data['atomTokenId'] = icchaDcrypt($_GET['atomid']);
              $this->data['amount'] =1;
              $this->data['oid'] =icchaDcrypt($_GET['oid']);
              $this->data['email'] =icchaDcrypt($_GET['email']);
              $this->data['phone'] =icchaDcrypt($_GET['phone']);
              $this->load->view('paymentview',$this->data); 
            }
        }

        public function orderCancelled() {
            $oid = $this->input->post('oid');
            $ord['status'] = 4;
            $this->master_db->updateRecord('orders',$ord,['oid'=>$oid]);
            $this->data['orders'] = $orders = $this->master_db->getRecords('orders',['oid'=>$oid],'*');
            $payme['pstatus'] = "cancelled";
            $payme['status'] = 4;
            $payid = $this->master_db->updateRecord('payment_log',$payme,['oid'=>$oid]);
            echo json_encode(['status'=>true,'msg'=>'Order cancelled successfully']);
        }

public function ordereasebuzz() {
        $this->load->library('mail');
           $easebuzzObj = new Easebuzz(MERCHANT_KEY, SALT, ENV);
        $result = $easebuzzObj->easebuzzResponse($_POST);
       // echo "<pre>";print_r($result);exit;
        $pdecode = json_decode($result,true);
        //echo "<prE>";print_r($pdecode);exit;
         $pay_array = json_encode($pdecode['data']);
            if($pdecode['status'] == '1') {
                $val = 0;
                $payid = $pdecode['data']['easepayid'];
                $signature =$pdecode['data']['hash'];
                $order = $this->master_db->getRecords("orders", array("orderid"=>$pdecode['data']['udf1']), "*");
                if ($payid == "")
                {
                        $this->session->set_flashdata('message','<div class="alert alert-danger alert-dismissable">Payment is pending. Please try again later</div>');
                        redirect( base_url().'checkout/billing_shipping' );
                }
                else{
                        if($pdecode['data']['status'] =='success') {
                                     $dbp = array(
                                    'pay_array'=>$pay_array,
                                    'pay_id'=>$payid,
                                    'signature'=>$signature,
                                     'pstatus'=>'success',
                                    'status'=>1,
                                );
                    $payid = $this->master_db->updateRecord('payment_log',$dbp,['oid'=>$pdecode['data']['udf2']]);
                    $ord['status'] = 5;
                    $this->master_db->updateRecord('orders',$ord,['oid'=>$pdecode['data']['udf2']]);
                    if($this->session->userdata('couponcodeid')) {
                        $vids = $this->session->userdata('couponcodeid');
                        $getVouchers = $this->master_db->getRecords('vouchers',['id'=>$vids],'*');
                        if(count($getVouchers) >0) {
                            if($getVouchers[0]->type ==2) {
                                $this->master_db->updateRecord('vouchers',['individual_limit_count'=>1],['id'=>$vids]);
                            }
                            if($getVouchers[0]->usage_limit >0) {
                                $vuser['coupon_id'] = $getVouchers[0]->id;
                                $vuser['user_id'] = $order[0]->user_id;
                                $this->master_db->insertRecord('vouchers_users',$vuser);
                            }
                        }
                    }
                    $getStockdetails = $this->master_db->sqlExecute('select op.qty,ps.stock,ps.pro_size as psid  from order_products op left join product_size ps on ps.pro_size = op.psid where op.oid='.$pdecode['data']['udf2'].'');
       if(count($getStockdetails) >0) {
            foreach ($getStockdetails as $key => $value) {
                if($value->stock >0) {
                     $stock = $value->stock - $value->qty;
                    $up['stock'] = $stock;
                    $this->master_db->updateRecord('product_size',$up,['pro_size'=>$value->psid]);
                }
            }
       }
                    $this->data['orders'] = $orders = $this->master_db->getRecords('orders',['oid'=>$pdecode['data']['udf2']],'*');
                    //echo $this->db->last_query();exit;
                    $this->data['orderproducts'] = $this->master_db->getRecords('order_products',['oid'=>$pdecode['data']['udf2']],'*');
                    $this->data['payment'] = $this->master_db->getRecords('payment_log',['oid'=>$pdecode['data']['udf2']],'*');
                    $this->data['billing'] = $this->master_db->sqlExecute('select * from order_bills where oid='.$pdecode['data']['udf2'].'');
                     $this->data['order_bill'] = $order_bills =  $this->master_db->getRecords('order_bills',['oid'=>$pdecode['data']['udf2']],'*');
                     $this->data['payment'] = $this->master_db->getRecords('payment_log',['oid'=>$pdecode['data']['udf2']],'*');
                    $social_links = $this->master_db->getRecords('sociallinks',['status'=>0],'*');
                    $this->data['social'] = $social_links;
                     $html = $this->load->view('order_success',$this->data,true);
                    // $this->mail->sendMail($order_bills[0]->bemail,$html,'Your Order Summary');
                      $this->mail->send_sparkpost_attach($html,[$order_bills[0]->bemail],'Your Order Summary');
                    $this->load->view('order_payment_summary',$this->data); 
                    $this->session->unset_userdata('discount');
                    $this->session->unset_userdata('pamount');
                    $this->cart->destroy();
                        }else if($pdecode['data']['status'] =='pending') {
                             $ord['status'] = 1;
                            $this->master_db->updateRecord('orders',$ord,['oid'=>$pdecode['data']['udf2']]);
                             $orders = $this->master_db->getRecords('orders',['oid'=>$pdecode['data']['udf2']],'*');
                              $dbp = array(
                                 'pay_array'=>$pay_array,
                                 'pstatus'=>'pending',
                                'status'=>3
                            );
                               $this->data['orders'] =  $this->master_db->getRecords('orders',['oid'=>$pdecode['data']['udf2']],'*');
                            //print_r($dbp);exit;       
                            $payid = $this->master_db->updateRecord('payment_log',$dbp,['oid'=>$pdecode['data']['udf2']]);
                             $this->load->view('order_cancel',$this->data);
                        }
                        else if($pdecode['data']['status'] =='userCancelled') {
                             $ord['status'] = 4;
                            $this->master_db->updateRecord('orders',$ord,['oid'=>$pdecode['data']['udf2']]);
                             $orders = $this->master_db->getRecords('orders',['oid'=>$pdecode['data']['udf2']],'*');
                              $dbp = array(
                                 'pay_array'=>$pay_array,
                                 'pstatus'=>'cancelled',
                                'status'=>4
                            );
                              $this->data['orders'] =  $this->master_db->getRecords('orders',['oid'=>$pdecode['data']['udf2']],'*');
                            //print_r($dbp);exit;       
                            $payid = $this->master_db->updateRecord('payment_log',$dbp,['oid'=>$pdecode['data']['udf2']]);
                             $this->load->view('order_cancel',$this->data);
                        }
                         else if($pdecode['data']['status'] =='dropped' || $pdecode['data']['status']=="bounced") {
                             $ord['status'] =-1;
                            $this->master_db->updateRecord('orders',$ord,['oid'=>$pdecode['data']['udf2']]);
                             $this->data['orders'] =  $this->master_db->getRecords('orders',['oid'=>$pdecode['data']['udf2']],'*');
                             $orders = $this->master_db->getRecords('orders',['oid'=>$pdecode['data']['udf2']],'*');
                              $dbp = array(
                                 'pay_array'=>$pay_array,
                                 'pstatus'=>'failure',
                                'status'=>-1
                             );
                            //print_r($dbp);exit;       
                            $payid = $this->master_db->updateRecord('payment_log',$dbp,['oid'=>$pdecode['data']['udf2']]);
                             $this->load->view('order_cancel',$this->data);
                        }
                        else if($pdecode['data']['status'] =='failure') {
                            $ord['status'] = -1;
                            $this->master_db->updateRecord('orders',$ord,['oid'=>$pdecode['data']['udf2']]);
                            $this->data['orders'] = $orders = $this->master_db->getRecords('orders',['oid'=>$pdecode['data']['udf2']],'*');
                                $dbp = array(
                                 'pay_array'=>$pay_array,
                                 'pstatus'=>'failure',
                                'status'=>-1
                                
                            );
                            //print_r($dbp);exit;       
                            $payid = $this->master_db->updateRecord('payment_log',$dbp,['oid'=>$pdecode['data']['udf2']]);
                           $this->load->view('order_cancel',$this->data);
                        }
                }
            }

             $this->session->unset_userdata('discount');
                    $this->session->unset_userdata('pamount');
                    $this->session->unset_userdata('discount_type');
                    $this->session->unset_userdata('couponcodeid');
                    $this->cart->destroy();
    }
}
?>