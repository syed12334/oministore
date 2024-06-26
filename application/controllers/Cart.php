<?php
defined("BASEPATH") or exit("No direct script access allowed");
class Cart extends CI_Controller
{
    protected $data;
    public function __construct()
    {
        date_default_timezone_set("Asia/Kolkata");
        parent::__construct();
        $this->load->helper("utility_helper");
        $this->load->model("master_db");
        $this->load->model("home_db");
        $this->load->model("cart_db");
        $this->data['session'] = CUSTOMER_SESSION; 
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
           $this->data['totalreferrals'] = [];
        }
        if(isset($_GET['referral_code']) && !empty($_GET['referral_code'])) {
          $ref = icchaDcrypt($_GET['referral_code']);
          $this->session->set_userdata('referral_code',$ref);
        }
        $this->data["detail"] = "";
        $this->data["category"] = $this->master_db->getRecords(
            "category",
            ["status" => 0],
            "id as cat_id,page_url,cname,ads_image,icons",
            "order_by asc",
            "",
            "",
            "14"
        );
        $this->load->library("encryption");
        $this->data["header"] = $this->load->view(
            "includes/header",
            $this->data,
            true
        );
        $this->data["header1"] = $this->load->view(
            "includes/header1",
            $this->data,
            true
        );
        $this->data["footer"] = $this->load->view(
            "includes/footer",
            $this->data,
            true
        );
        $this->data["js"] = $this->load->view("jsFile", $this->data, true);
    }
    public function addtocart()
    {
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
            //echo "<pre>";print_r($_POST);exit;
                $id = decode($_POST["id"]);
                $newsize = "";
                $psid = decode($_POST["psid"]);
                $sizes = $this->input->post('sizes');
                if(!empty($sizes)) {
                    $newsize .=$sizes;
                }else {
                    $newsize .=$psid;
                }
                $product = $this->cart_db->getCartproducts($newsize);
               // echo $this->db->last_query();exit;
                $db = [
                    "id" =>$newsize,
                    "qty" => 1,
                    "image" => base_url() . $product[0]->image,
                    "price" => $product[0]->price,
                    "discount" => $product[0]->discount,
                    "pcode" => $product[0]->pcode,
                    "name" => $product[0]->ptitle,
                    "purl" => $product[0]->page_url,
                    "stock" => $product[0]->stock,
                    "pid" => $product[0]->pid,
                    "sizeid" => $product[0]->sid,
                    "cid" => $product[0]->coid,
                    "sid"  =>$sizes,
                    "taxamount" =>$product[0]->tax,
                    "sname"=>$product[0]->sname,
                    "plink" => base_url() . "products/" . $product[0]->page_url . "",
                    "dmsg" => ""
                    
                ];
                if ($this->cart->insert($db)) {
                    $subtotal = [];
                       $totaldiff = "";
                    if($this->cart->total() >=299) {
                        $totaldiff .=" Shpping Free";
                    }else {
                         $ams = 300;
                        $tt = $this->cart->total();
                        $totr = $ams - $tt;
                        $totaldiff .=" Add Rs.".$totr."  for Free Delivery";
                    }
                    $tax =[];
                    foreach ($this->cart->contents() as $cart) {
                        $subtotal[] = $cart['subtotal'];
                        $tax[] = $cart['price'] * $cart['qty'] * $cart['taxamount'] / 100;
                    }
                    $this->data['tax'] =array_sum($tax); 
                    $html = $this->load->view("cartitems", $this->data, true);
                    $result = [
                        "status" => true,
                        "msg" => $html,
                        "count" => count($this->cart->contents()),
                        'subtotal'=>array_sum($subtotal),
                        'totaldiff'=>$totaldiff
                    ];
                } else {
                    $result = [
                        "status" => false,
                        "msg" => "Error in adding",
                    ];
                }
        }else {
             $result = [
                        "status" => true,
                        "msg" => 'Invalid request',
                    ];
        }
        echo json_encode($result);
    }
    public function removeCartitem()
    {
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){

                $rowid = $this->input->post("id");
                $pre = $this->input->post("pre");
                //echo "<pre>";print_r($_POST);exit;
                $dat = [
                    "rowid" => $rowid,
                    "qty" => "0",
                ];
                if ($this->cart->update($dat)) {
                     $subtotal = [];
                      $totaldiff = "";
                    if($this->cart->total() >=299) {
                        $totaldiff .=" Shpping Free";
                    }else {
                         $ams = 300;
                        $tt = $this->cart->total();
                        $totr = $ams - $tt;
                        $totaldiff .=" Add Rs.".$totr."  for Free Delivery";
                    }
                    $tax =[];
                    foreach ($this->cart->contents() as $cart) {
                        $subtotal[] = $cart['subtotal'];
                        $tax[] = $cart['price'] * $cart['qty'] * $cart['taxamount'] / 100;
                    }
                    $this->data['tax'] =array_sum($tax);
                    if($pre ==1) {

                        $html = $this->load->view("cartitems", $this->data, true);
                        $result = [
                        "status" => true,
                        "msg" => $html,
                        "count" => count($this->cart->contents()),
                        'subtotal'=>array_sum($subtotal),
                        'totaldiff'=>$totaldiff
                        ];
                    }
                    else if($pre ==2) {
                        $html = $this->load->view("cartitemsdel", $this->data, true);
                        $result = [
                        "status" => true,
                        "msg" => $html,
                        "count" => count($this->cart->contents()),
                        'subtotal'=>array_sum($subtotal),
                        'totaldiff'=>$totaldiff
                        ];
                    }
                    
                } else {
                    $result = [
                        "status" => false,
                        "msg" => "Error in removing items",
                    ];
                }
                echo json_encode($result);
        }else {
            $result = [
                        "status" => false,
                        "msg" => "Invalid request",
                    ];
        }
        
    }
      public function updateCartitem()
    {
    //echo "<pre>";print_r($_POST);exit;
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){

                $rowid = $this->input->post("id");
                $pre = $this->input->post("pre");
                $qtystatus = $this->input->post("qtystatus");
                $qtys ="";
                if($qtystatus ==1) {
                    $qtys .= $this->input->post("qtys")+1;
                }else if($qtystatus ==-1) {
                    $cartqty = $this->input->post("qtys")-1;
                    if($cartqty ==0) {
                        $qtys .= 1;
                    }else {
                        $qtys .= $this->input->post("qtys")-1;
                    }
                }
                
                //echo "<pre>";print_r($_POST);exit;
                $dat = [
                    "rowid" => $rowid,
                    "qty" => $qtys,
                ];
                if ($this->cart->update($dat)) {
                     $totaldiff = "";
                    if($this->cart->total() >=299) {
                        $totaldiff .=" Shipping Free";
                    }else {
                         $ams = 300;
                        $tt = $this->cart->total();
                        $totr = $ams - $tt;
                        $totaldiff .=" Add Rs.".$totr."  for Free Delivery";
                    }
                     $subtotal = [];$tax =[];
                    foreach ($this->cart->contents() as $cart) {
                        $subtotal[] = $cart['subtotal'];
                        $tax[] = $cart['price'] * $cart['qty'] * $cart['taxamount'] / 100;
                    }
                    $this->data['tax'] =array_sum($tax);
                    if($pre ==1) {
                        
                        $html = $this->load->view("cartitems", $this->data, true);
                        $result = [
                        "status" => true,
                        "msg" => $html,
                        "count" => count($this->cart->contents()),
                        'subtotal'=>array_sum($subtotal),
                        'totaldiff'=>$totaldiff
                        ];
                    }
                    // else if($pre ==2) {
                    //     $html = $this->load->view("cartitemsdel", $this->data, true);
                    //     $result = [
                    //     "status" => true,
                    //     "msg" => $html,
                    //     "count" => count($this->cart->contents()),
                    //     'subtotal'=>array_sum($subtotal)
                    //     ];
                    // }
                    
                } else {
                    $result = [
                        "status" => false,
                        "msg" => "Error in removing items",
                    ];
                }
                echo json_encode($result);
        }else {
            $result = [
                        "status" => false,
                        "msg" => "Invalid request",
                    ];
        }
        
    }
    public function updateCart()
    {
        $html ="";
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
            $rowid = $this->input->post("rowid");
            $qty = $this->input->post("qty");
            $msg = "";
            $chaneqty = 0;
            $tax = [];
            $ars = [];
            foreach ($this->cart->contents() as $items) {
                if ($items["rowid"] == $rowid) {
                    $dat = [
                        "rowid" => $rowid,
                        "qty" => $qty,
                    ];
                    $this->cart->update($dat);
                }
            }
            $totaldiff = "";
                    if($this->cart->total() >=299) {
                        $totaldiff .=" Shpping Free";
                    }else {
                         $ams = 300;
                        $tt = $this->cart->total();
                        $totr = $ams - $tt;
                        $totaldiff .=" Add Rs.".$totr."  for Free Delivery";
                    }
            $html .= $this->load->view('cartitemsdel',$this->data,true);
        }else {
            $html .="Invalid request";
        }
       echo json_encode(['status'=>true,'msg'=>$html,'totaldiff'=>$totaldiff]);
    }
    public function cartDetails() {
        $requesturi = $_SERVER['REQUEST_URI'];
        $this->session->set_userdata('urlredirect',$requesturi);
        $this->load->view('cart',$this->data);
    }
    public function couponList() {
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
             $details = $this->session->userdata($this->data['session']);
             $userid = @$details['id'];
             if($this->session->userdata($this->data['session'])) {
                 $this->load->library('form_validation');
                $this->form_validation->set_rules('couponcode','Coupon Code','trim|required|regex_match[/^([A-Za-z0-9])+$/i]|max_length[10]',['required'=>'Coupon Code is required','regex_match'=>'Only characters and numbers are allowed and spaces are not allowed','max_length'=>'Only 10 digits are allowed']);
                 if($this->form_validation->run() ==TRUE) {
                    $couponcode = trim(html_escape($this->input->post('couponcode')));
                    $getCoupon = $this->master_db->getRecords('vouchers',['title'=>$couponcode,'status'=>0],'id,to_date,discount,type,usage_limit as limit,min_amount as minamount');
                   // echo $this->db->last_query();exit;
                    if(count($getCoupon) >0) {
                        $getVouchetstatus = $this->master_db->getRecords('vouchers_users',['user_id'=>$userid,'coupon_id'=>$getCoupon[0]->id],'*');
                         $totaldiff = "";
                        if($this->cart->total() >=299) {
                            $totaldiff .=" Shpping Free";
                        }else {
                                $ams = 300;
                            $tt = $this->cart->total();
                            $totr = $ams - $tt;
                            $totaldiff .=" Add Rs.".$totr."  for Free Delivery";
                        }
                        $delivercha ="";$totalAmount ="";
                        if($this->cart->total() >=300) {
                            $delivercha .= 0;
                        }else {
                            $delivercha .= 50;
                        }
                        $totalPrice =  @$this->cart->total()  +  $delivercha;
                        if($getCoupon[0]->type ==2) {
                            $getVouchercountstatus = $this->master_db->getRecords('vouchers',['individual_limit_count'=>1,'title'=>$couponcode],'*');
                           // echo $this->db->last_query();exit;
                            if(count($getVouchercountstatus) >0) {
                                 $resuls = array(
                                             'status'   => false,
                                             'msg'      =>'Vouchers already used',
                                        );
                                echo json_encode($resuls);exit;
                            }else {
                                if($totalPrice >= $getCoupon[0]->minamount) {
                                     $totalPrices =  @$this->cart->total()  +  $delivercha;
                                $totalDiscountss = $totalPrices  - $getCoupon[0]->discount;
                                $total = "";
                                if($totalDiscountss >0) {
                                    $total .=$totalDiscountss;
                                }else {
                                    $total .=0;
                                }
                                $this->session->set_userdata('couponcodeid',$getCoupon[0]->id);
                                if($this->session->userdata('pamount')) {
                                    $totalAmount .= '<span id="totalAmount">'. sprintf("%.2f",$total ).'<div id="pincodeAm"></div></span>';
                                }else {
                                    $totalAmount .= '<span id="totalAmount">'. sprintf("%.2f",$total).'<div id="pincodeAm"></div></span>';
                                }
                                    $date = date('Y-m-d');
                                    if($date  ==  $getCoupon[0]->to_date) {
                                    $this->session->set_userdata('discount',@$getCoupon[0]->discount);
                                    $this->session->set_userdata('discount_type',@$getCoupon[0]->type);
                                    $resul = array(
                                            'status'   => true,
                                            'msg'      =>'Voucher code matched',
                                            'discount' => "-<i class='fas fa-rupee-sign'></i> ".$getCoupon[0]->discount,
                                            'total'    =>$totalAmount ,
                                            'totaldiff'=>$totaldiff
                                    );
                                    echo json_encode($resul);exit;
                                    }
                                    else if($date  >  $getCoupon[0]->to_date) {
                                    $resul = array(
                                            'status'   => false,
                                            'msg'      =>'Voucher code is expired',
                                    );
                                    echo json_encode($resul);exit;
                                    }else {
                                        $this->session->set_userdata('discount',@$getCoupon[0]->discount);
                                    $this->session->set_userdata('discount_type',@$getCoupon[0]->type);
                                    $resul = array(
                                            'status'   => true,
                                            'msg'      =>'Voucher code matched',
                                            'discount' => "-<i class='fas fa-rupee-sign'></i> ".$getCoupon[0]->discount,
                                            'total'    =>$totalAmount ,
                                            'totaldiff'=>$totaldiff
                                    );
                                    echo json_encode($resul);exit;
                                 } 
                                }
                                else if($getCoupon[0]->minamount ==0) {

                                     $totalPrices =  @$this->cart->total()  +  $delivercha;
                                $totalDiscountss = $totalPrices  - $getCoupon[0]->discount;
                                $total = "";
                                if($totalDiscountss >0) {
                                    $total .=$totalDiscountss;
                                }else {
                                    $total .=0;
                                }
                                $this->session->set_userdata('couponcodeid',$getCoupon[0]->id);
                                if($this->session->userdata('pamount')) {
                                    $totalAmount .= '<span id="totalAmount">'. sprintf("%.2f",$total ).'<div id="pincodeAm"></div></span>';
                                }else {
                                    $totalAmount .= '<span id="totalAmount">'. sprintf("%.2f",$total).'<div id="pincodeAm"></div></span>';
                                }
                                    $date = date('Y-m-d');
                                    if($date  ==  $getCoupon[0]->to_date) {
                                    $this->session->set_userdata('discount',@$getCoupon[0]->discount);
                                    $this->session->set_userdata('discount_type',@$getCoupon[0]->type);
                                    $resul = array(
                                            'status'   => true,
                                            'msg'      =>'Voucher code matched',
                                            'discount' => "-<i class='fas fa-rupee-sign'></i> ".$getCoupon[0]->discount,
                                            'total'    =>$totalAmount ,
                                            'totaldiff'=>$totaldiff
                                    );
                                    echo json_encode($resul);exit;
                                    }
                                    else if($date  >  $getCoupon[0]->to_date) {
                                    $resul = array(
                                            'status'   => false,
                                            'msg'      =>'Voucher code is expired',
                                    );
                                    echo json_encode($resul);exit;
                                    }else {
                                        $this->session->set_userdata('discount',@$getCoupon[0]->discount);
                                    $this->session->set_userdata('discount_type',@$getCoupon[0]->type);
                                    $resul = array(
                                            'status'   => true,
                                            'msg'      =>'Voucher code matched',
                                            'discount' => "-<i class='fas fa-rupee-sign'></i> ".$getCoupon[0]->discount,
                                            'total'    =>$totalAmount ,
                                            'totaldiff'=>$totaldiff
                                    );
                                    echo json_encode($resul);exit;
                                 } 
                                }
                                else {
                                    $tote = $getCoupon[0]->minamount - $totalPrice;
                                     $resul = array(
                                                     'status'   => false,
                                                     'msg'      =>'Buy Products worth Rs.'.$tote."/- and Get voucher applied <a href='".base_url()."' class='btn btn-dark btn-rounded' style='padding:5px;'>Shop Now</a>",
                                                );
                                     echo json_encode($resul);exit;
                                }
                            }
                        }else {
                            if(count($getVouchetstatus) > $getCoupon[0]->limit) {
                             $resul = array(
                                             'status'   => false,
                                             'msg'      =>'Voucher already used',
                                        );
                        }
                        else if($getCoupon[0]->limit == count($getVouchetstatus) ) {
                             $resul = array(
                                             'status'   => false,
                                             'msg'      =>'Voucher already used',
                                        );
                        }
                        else {
                            
                                            if($totalPrice >= $getCoupon[0]->minamount) {
                                                 if($getCoupon[0]->type ==0) {
                                                    $totalPrices =  @$this->cart->total()  +  $delivercha;
                                                    $totalDiscountss = $totalPrices  - $getCoupon[0]->discount;
                                                     $total = "";
                                                    if($totalDiscountss >0) {
                                                        $total .=$totalDiscountss;
                                                    }else {
                                                        $total .=0;
                                                    }
                                                    $this->session->set_userdata('couponcodeid',$getCoupon[0]->id);
                                                    if($this->session->userdata('pamount')) {
                                                        $totalAmount .= '<span id="totalAmount">'. sprintf("%.2f",$total ).'<div id="pincodeAm"></div></span>';
                                                    }else {
                                                        $totalAmount .= '<span id="totalAmount">'. sprintf("%.2f",$total).'<div id="pincodeAm"></div></span>';
                                                    }
                                                
                                                     $date = date('Y-m-d');
                                                     if($date  ==  $getCoupon[0]->to_date) {
                                                        $this->session->set_userdata('discount',@$getCoupon[0]->discount);
                                                        $this->session->set_userdata('discount_type',@$getCoupon[0]->type);
                                                        $resul = array(
                                                             'status'   => true,
                                                             'msg'      =>'Voucher code matched',
                                                             'discount' => "-<i class='fas fa-rupee-sign'></i> ".$getCoupon[0]->discount,
                                                             'total'    =>$totalAmount ,
                                                             'totaldiff'=>$totaldiff
                                                        );
                                                     }
                                                    else if($date  >  $getCoupon[0]->to_date) {
                                                        $resul = array(
                                                             'status'   => false,
                                                             'msg'      =>'Voucher code is expired',
                                                        );
                                                    }else {
                                                         $this->session->set_userdata('discount',@$getCoupon[0]->discount);
                                                        $this->session->set_userdata('discount_type',@$getCoupon[0]->type);
                                                        $resul = array(
                                                             'status'   => true,
                                                             'msg'      =>'Voucher code matched',
                                                             'discount' => "-<i class='fas fa-rupee-sign'></i> ".$getCoupon[0]->discount,
                                                             'total'    =>$totalAmount ,
                                                             'totaldiff'=>$totaldiff
                                                        );
                                                    }
                                                }else {
                                                     $totalPricess =  @$this->cart->total()  +  $delivercha;
                                                    $totalDiscounts = $totalPricess  * $getCoupon[0]->discount  / 100;
                                                    $newprice= $totalPricess - $totalDiscounts;
                                                    $total = "";
                                                    $totaldissc= "";
                                                    if($getCoupon[0]->discount ==100) {
                                                         $totaldissc .=$this->cart->total()  +  $delivercha;
                                                    }else {
                                                        $totaldissc .=$newprice;
                                                    }
                                                    if($newprice >0 ) {
                                                        $total .=$newprice;
                                                    }else {
                                                        $total .=0;
                                                    }
                                                    if($this->session->userdata('pamount')) {
                                                        $totalAmount .= '<span id="totalAmount">'. sprintf("%.2f",$total ).'<div id="pincodeAm"></div></span>';
                                                    }else {
                                                        $totalAmount .= '<span id="totalAmount">'. sprintf("%.2f",$total).'<div id="pincodeAm"></div></span>';
                                                    }
                                                     $dates = date('Y-m-d');
                                                     if($dates  ==  $getCoupon[0]->to_date) {
                                                         $this->session->set_userdata('discount',@$getCoupon[0]->discount);
                                                        $this->session->set_userdata('discount_type',@$getCoupon[0]->type);
                                                        $resul = array(
                                                             'status'   => true,
                                                             'msg'      =>'Voucher code matched',
                                                             'discount' => "-<i class='fas fa-rupee-sign'></i> ".$totaldissc,
                                                             'total'    =>"<b><i class='fas fa-rupee-sign'></i> ".$totalAmount."</b>",
                                                             'totaldiff'=>$totaldiff
                                                        );
                                                     }
                                                    else if($dates  > $getCoupon[0]->to_date) {
                                                        $resul = array(
                                                             'status'   => false,
                                                             'msg'      =>'Voucher code is expired',
                                                        );
                                                    }else {
                                                         $this->session->set_userdata('discount',@$getCoupon[0]->discount);
                                                        $this->session->set_userdata('discount_type',@$getCoupon[0]->type);
                                                        $resul = array(
                                                             'status'   => true,
                                                             'msg'      =>'Voucher code matched',
                                                             'discount' =>"-<i class='fas fa-rupee-sign'></i> ".$totaldissc,
                                                             'total'    =>"<b><i class='fas fa-rupee-sign'></i> ".$totalAmount."</b>",
                                                             'totaldiff'=>$totaldiff
                                                        );
                                                    }
                                                }
                                            }
                                            else if($getCoupon[0]->minamount ==0) {
                                                 if($getCoupon[0]->type ==0) {
                                                    $totalPrices =  @$this->cart->total()  +  $delivercha;
                                                    $totalDiscountss = $totalPrices  - $getCoupon[0]->discount;
                                                    $total ="";
                                                    if($totalDiscountss >0) {
                                                        $total .= $totalDiscountss;
                                                    }else {
                                                        $total .= 0;
                                                    }

                                                    $this->session->set_userdata('couponcodeid',$getCoupon[0]->id);
                                                    if($this->session->userdata('pamount')) {
                                                        $totalAmount .= '<span id="totalAmount">'. sprintf("%.2f",$total ).'<div id="pincodeAm"></div></span>';
                                                    }else {
                                                        $totalAmount .= '<span id="totalAmount">'. sprintf("%.2f",$total).'<div id="pincodeAm"></div></span>';
                                                    }
                                                
                                                     $date = date('Y-m-d');
                                                     if($date  ==  $getCoupon[0]->to_date) {
                                                        $this->session->set_userdata('discount',@$getCoupon[0]->discount);
                                                        $this->session->set_userdata('discount_type',@$getCoupon[0]->type);
                                                        $resul = array(
                                                             'status'   => true,
                                                             'msg'      =>'Voucher code matched',
                                                             'discount' => "-<i class='fas fa-rupee-sign'></i> ".$getCoupon[0]->discount,
                                                             'total'    =>$totalAmount ,
                                                             'totaldiff'=>$totaldiff
                                                        );
                                                     }
                                                    else if($date  >  $getCoupon[0]->to_date) {
                                                        $resul = array(
                                                             'status'   => false,
                                                             'msg'      =>'Voucher code is expired',
                                                        );
                                                    }else {
                                                         $this->session->set_userdata('discount',@$getCoupon[0]->discount);
                                                        $this->session->set_userdata('discount_type',@$getCoupon[0]->type);
                                                        $resul = array(
                                                             'status'   => true,
                                                             'msg'      =>'Voucher applied',
                                                             'discount' => "-<i class='fas fa-rupee-sign'></i> ".$getCoupon[0]->discount,
                                                             'total'    =>$totalAmount ,
                                                             'totaldiff'=>$totaldiff
                                                        );
                                                    }
                                                }else {
                                                    $totalPricess =  @$this->cart->total()  +  $delivercha;
                                                    $totalDiscounts = $totalPricess  * $getCoupon[0]->discount  / 100;
                                                    $total ="";
                                                    $newamt = $totalPricess - $totalDiscounts;
                                                    if($newamt >0) {
                                                        $total .= $newamt;
                                                    }else {
                                                        $total .= 0;
                                                    }
                                                    if($this->session->userdata('pamount')) {
                                                        $totalAmount .= '<span id="totalAmount">'. sprintf("%.2f",$total ).'<div id="pincodeAm"></div></span>';
                                                    }else {
                                                        $totalAmount .= '<span id="totalAmount">'. sprintf("%.2f",$total).'<div id="pincodeAm"></div></span>';
                                                    }
                                                     $dates = date('Y-m-d');
                                                     if($date  ==  $getCoupon[0]->to_date) {
                                                         $this->session->set_userdata('discount',@$getCoupon[0]->discount);
                                                        $this->session->set_userdata('discount_type',@$getCoupon[0]->type);
                                                        $resul = array(
                                                             'status'   => true,
                                                             'msg'      =>'Voucher code matched',
                                                             'discount' => "-<i class='fas fa-rupee-sign'></i> ".$totalDiscounts,
                                                             'total'    =>$totalAmount ,
                                                             'totaldiff'=>$totaldiff
                                                        );
                                                     }
                                                    else if($dates  > $getCoupon[0]->to_date) {
                                                        $resul = array(
                                                             'status'   => false,
                                                             'msg'      =>'Voucher code is expired',
                                                        );
                                                    }else {
                                                         $this->session->set_userdata('discount',@$getCoupon[0]->discount);
                                                        $this->session->set_userdata('discount_type',@$getCoupon[0]->type);
                                                        $resul = array(
                                                             'status'   => true,
                                                             'msg'      =>'Voucher code matched',
                                                             'discount' => $totalDiscounts."%",
                                                             'total'    =>$totalAmount ,
                                                             'totaldiff'=>$totaldiff
                                                        );
                                                    }
                                                }
                                            }
                                            else {
                                                $mindds = $getCoupon[0]->minamount - $totalPrice;
                                                $resul = array(
                                                     'status'   => false,
                                                 'msg'      =>'Buy Products worth Rs.'.$mindds."/- and Get voucher applied <a href='".base_url()."' class='btn btn-dark btn-rounded' style='padding:5px;'>Shop Now</a>",
                                                );
                                            }
                                }
                        }
                        
                    }else {
                        $resul = array(
                             'status'   => false,
                             'msg'      =>'Voucher does not exists try another',
                        );
                    }
                 }else {
                    $resul = array(
                             'formerror'   => false,
                            'coupon_error' => form_error('couponcode'),
                        );
                 }
             }else {
                 $resul = array(
                           'status'   => false,
                            'msg'      =>'Registration is mandatory to avail gift vourchers',
                                        );
             }
        }else {
                 $resul = array(
                             'status'   => false,
                             'msg'      =>'Invalid request',
                        );
        }
        echo json_encode($resul);
    }
    public function wishlist() {
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
            //echo "<pre>";print_r($_POST);exit;
            $details = $this->session->userdata($this->data['session']);
           $pid = decode($this->input->post('pid'));
            if (!$this->session->userdata($this->data['session'])) {
                $result = ['status'=>-1,'url'=>base_url().'login'];
            }else {
                $details = $this->session->userdata($this->data['session']);
                $getWishlist = $this->master_db->getRecords('wishlist',['pid'=>$pid,'uid'=>$details['id']],'*');
                if(count($getWishlist) >0) {
                    $result = ['status'=>false,'msg'=>'Product already added to wishlist'];
                }else {
                    $db['uid'] = $details['id'];
                    $db['pid'] = $pid;
                    $db['created_at'] = date('Y-m-d H:i:s');
                    $in = $this->master_db->insertRecord('wishlist',$db);
                    if($in >0) {
                        $result = ['status'=>true,'msg'=>'Added to wishlist'];
                    }else {
                        $result = ['status'=>false];
                    } 
                }
            }
            echo json_encode($result);
        }
    }
    public function cancelOrder() {
        $this->load->library('Mail');
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
            $new = $this->session->userdata($this->data['session']);
            $oid = icchaDcrypt($this->input->post('oid'));
            $status =4;
            $db['status'] = $status;
            $this->data['orders'] = $orders =  $this->master_db->getRecords('orders',['oid'=>$oid],'orderid,subtotal,user_id');
            $bills = $this->master_db->getRecords('order_bills',['oid'=>$oid],'*');
            //echo $this->db->last_query();exit;
            $html = $this->load->view('porder_cancel',$this->data,true);
            //$this->mail->sendMail($bills[0]->bemail,$html,'Order Cancellation');
            $update = $this->master_db->updateRecord('orders',$db,['oid'=>$oid],'*');
            if($update >0) {
                $getPay = $this->master_db->sqlExecute('select o.totalamount,o.user_id from orders o left join payment_log p on p.oid = o.oid where o.oid='.$oid.' and o.status =4 and p.status =1');
                if(count($getPay) >0) {
                    $getWallet = $this->master_db->getRecords('wallet',['user_id'=>$new['id']],'*');
                    if(count($getWallet) >0) {
                        $updates['amount'] = $getWallet[0]->amount + $getPay[0]->totalamount;
                        $this->master_db->updateRecord('wallet',$updates,['user_id'=>$new['id']]);
                        $worders['wallet_id'] = $getWallet[0]->wallet_id;
                        $worders['order_id'] = $oid;
                        $worders['amount'] =  $getPay[0]->totalamount;
                        $this->master_db->insertRecord('wallet_orders',$worders);
                    }else {
                        $or['user_id'] = $new['id'];
                        $or['referral_code'] = random_strings(8);
                        $or['amount'] = $getPay[0]->totalamount;
                        $in = $this->master_db->insertRecord('wallet',$or);
                        if($in >0) {
                            $worder['wallet_id'] = $in;
                            $worder['order_id'] = $oid;
                            $worder['amount'] =  $getPay[0]->totalamount;
                            $this->master_db->insertRecord('wallet_orders',$worder);
                        }
                    }
                }
                $result = ['status'=>true,'msg'=>'Your order has been cancelled successfully'];
            }else {
                $result = ['status'=>false,'msg'=>'Error in cancelling order'];
            }
            echo json_encode($result);
        }
    }
   public function redeemtowallet() {
           if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
             $details = $this->session->userdata($this->data['session']);
             $userid = $details['id'];
             if($this->session->userdata($this->data['session'])) {
                $this->load->library('form_validation');
                $this->form_validation->set_rules('couponcode','Coupon Code','trim|required|regex_match[/^([A-Za-z0-9])+$/i]|max_length[10]',['required'=>'Coupon Code is required','regex_match'=>'Only characters and numbers are allowed and spaces are not allowed','max_length'=>'Only 10 digits are allowed']);
                 if($this->form_validation->run() ==TRUE) {
                    $date = date('Y-m-d');
                    $couponcode = trim(html_escape($this->input->post('couponcode')));
                    $getCoupon = $this->master_db->getRecords('vouchers',['title'=>$couponcode,'status'=>0],'id,to_date,discount,type,usage_limit as limit,min_amount as minamount');
                    if(count($getCoupon) >0) {
                        $getVouchetstatus = $this->master_db->getRecords('vouchers_users',['user_id'=>$userid,'coupon_id'=>$getCoupon[0]->id],'*');
                         $totaldiff = "";
                        if($this->cart->total() >=299) {
                            $totaldiff .=" Shpping Free";
                        }else {
                            $ams = 300;
                            $tt = $this->cart->total();
                            $totr = $ams - $tt;
                            $totaldiff .=" Add Rs.".$totr."  for Free Delivery";
                        }
                        $delivercha ="";$totalAmount ="";
                        if($this->cart->total() >=300) {
                            $delivercha .= 0;
                        }else {
                            $delivercha .= 50;
                        }
                        $totalPrice =  @$this->cart->total()  +  $delivercha;
                        if($getCoupon[0]->type ==2) {
                            $getVouchercountstatus = $this->master_db->getRecords('vouchers',['individual_limit_count'=>1,'title'=>$couponcode],'*');
                            if(count($getVouchercountstatus) >0) {
                                $resuls['status'] =false;
                                $resuls['msg'] ='Voucher already used';
                                echo json_encode($resuls);exit;
                            }
                            else {
                                if($totalPrice >= $getCoupon[0]->minamount) {
                                    if($date  ==  $getCoupon[0]->to_date) {
                                        $this->master_db->updateRecord('vouchers',['individual_limit_count'=>1],['id'=>$getCoupon[0]->id]);
                                       $getWallet = $this->master_db->getRecords('wallet',['user_id'=>$details['id']],'*');
                                        if(count($getWallet) >0) {
                                             $wamount = $getWallet[0]->amount;
                                             $w['amount'] = $wamount +$getCoupon[0]->discount;
                                            $this->master_db->updateRecord('wallet',$w,['user_id'=>$details['id']]);
                                        }else {
                                            $ws['user_id'] =$details['id'];
                                            $ws['referral_code'] =random_strings(8);
                                            $ws['amount'] =$getCoupon[0]->discount;
                                            $this->master_db->insertRecord('wallet',$ws);
                                        }
                                        $resul['status'] =true;
                                        $resul['msg'] ='Voucher redeemed successfully';
                                    }
                                    else if($date  >  $getCoupon[0]->to_date) {
                                        $resul['status'] =false;
                                        $resul['msg'] ='Voucher code is expired';
                                        echo json_encode($resul);exit;
                                    }
                                    else {
                                        $this->master_db->updateRecord('vouchers',['individual_limit_count'=>1],['id'=>$getCoupon[0]->id]);
                                        $getWallet = $this->master_db->getRecords('wallet',['user_id'=>$details['id']],'*');
                                        if(count($getWallet) >0) {
                                             $wamount = $getWallet[0]->amount;
                                             $w['amount'] = $wamount +$getCoupon[0]->discount;
                                            $this->master_db->updateRecord('wallet',$w,['user_id'=>$details['id']]);
                                        }else {
                                            $ws['user_id'] =$details['id'];
                                            $ws['referral_code'] =random_strings(8);
                                            $ws['amount'] =$getCoupon[0]->discount;
                                            $this->master_db->insertRecord('wallet',$ws);
                                        }
                                        $resul['status'] =true;
                                        $resul['msg'] ='Voucher redeemed successfully';
                                    }
                                }
                                else if($getCoupon[0]->minamount ==0) {
                                    if($date  ==  $getCoupon[0]->to_date) {
                                         $this->master_db->updateRecord('vouchers',['individual_limit_count'=>1],['id'=>$getCoupon[0]->id]);
                                        $getWallet = $this->master_db->getRecords('wallet',['user_id'=>$details['id']],'*');
                                        if(count($getWallet) >0) {
                                             $wamount = $getWallet[0]->amount;
                                             $w['amount'] = $wamount +$getCoupon[0]->discount;
                                            $this->master_db->updateRecord('wallet',$w,['user_id'=>$details['id']]);
                                        }else {
                                            $ws['user_id'] =$details['id'];
                                            $ws['referral_code'] =random_strings(8);
                                            $ws['amount'] =$getCoupon[0]->discount;
                                            $this->master_db->insertRecord('wallet',$ws);
                                        }
                                         $resul['status'] =true;
                                        $resul['msg'] ='Voucher redeemed successfully';
                                    }
                                    else if($date  >  $getCoupon[0]->to_date) {
                                        $resul['status'] =false;
                                        $resul['msg'] ='Voucher code is expired';
                                        echo json_encode($resul);exit;
                                    }
                                    else {
                                        $this->master_db->updateRecord('vouchers',['individual_limit_count'=>1],['id'=>$getCoupon[0]->id]);
                                        $getWallet = $this->master_db->getRecords('wallet',['user_id'=>$details['id']],'*');
                                        if(count($getWallet) >0) {
                                             $wamount = $getWallet[0]->amount;
                                             $w['amount'] = $wamount +$getCoupon[0]->discount;
                                            $this->master_db->updateRecord('wallet',$w,['user_id'=>$details['id']]);
                                        }else {
                                            $ws['user_id'] =$details['id'];
                                            $ws['referral_code'] =random_strings(8);
                                            $ws['amount'] =$getCoupon[0]->discount;
                                            $this->master_db->insertRecord('wallet',$ws);
                                        }
                                         $resul['status'] =true;
                                        $resul['msg'] ='Voucher redeemed successfully';
                                    }
                                }
                                else {
                                    $tote = $getCoupon[0]->minamount - $totalPrice;
                                    $resul['status'] =false;
                                    $resul['msg'] ='Buy Products worth Rs.'.$tote."/- and Get voucher applied <a href='".base_url()."' class='btn btn-dark btn-rounded' style='padding:5px;'>Shop Now</a>";
                                     echo json_encode($resul);exit;
                                }
                            }
                        }else {
                            if(count($getVouchetstatus) > $getCoupon[0]->limit) {
                             $resul['status'] =false;
                             $resul['msg'] ='Voucher already used';
                        }
                        else if($getCoupon[0]->limit == count($getVouchetstatus)) {
                             $resul['status'] =false;
                             $resul['msg'] ='Voucher already used';
                        }
                        else {
                            if($totalPrice >= $getCoupon[0]->minamount) {
                                if($getCoupon[0]->type ==0) {
                                    if($date  ==  $getCoupon[0]->to_date) {
                                        $getWallet = $this->master_db->getRecords('wallet',['user_id'=>$details['id']],'*');
                                        if(count($getWallet) >0) {
                                             $wamount = $getWallet[0]->amount;
                                             $w['amount'] = $wamount +$getCoupon[0]->discount;
                                            $this->master_db->updateRecord('wallet',$w,['user_id'=>$details['id']]);
                                        }else {
                                            $ws['user_id'] =$details['id'];
                                            $ws['referral_code'] =random_strings(8);
                                            $ws['amount'] =$getCoupon[0]->discount;
                                            $this->master_db->insertRecord('wallet',$ws);
                                        }
                                         if($getCoupon[0]->limit >0) {
                                            $vuser['coupon_id'] = $getCoupon[0]->id;
                                            $vuser['user_id'] = $details['id'];
                                            $this->master_db->insertRecord('vouchers_users',$vuser);
                                        }
                                         $resul['status'] =true;
                                        $resul['msg'] ='Voucher redeemed successfully';
                                    }
                                    else if($date  >  $getCoupon[0]->to_date) {
                                        $resul['status'] =false;
                                        $resul['msg'] ='Voucher code is expired';
                                    }
                                    else {
                                       $getWallet = $this->master_db->getRecords('wallet',['user_id'=>$details['id']],'*');
                                        if(count($getWallet) >0) {
                                             $wamount = $getWallet[0]->amount;
                                             $w['amount'] = $wamount +$getCoupon[0]->discount;
                                            $this->master_db->updateRecord('wallet',$w,['user_id'=>$details['id']]);
                                        }else {
                                            $ws['user_id'] =$details['id'];
                                            $ws['referral_code'] =random_strings(8);
                                            $ws['amount'] =$getCoupon[0]->discount;
                                            $this->master_db->insertRecord('wallet',$ws);
                                        }
                                         if($getCoupon[0]->limit >0) {
                                            $vuser['coupon_id'] = $getCoupon[0]->id;
                                            $vuser['user_id'] = $details['id'];
                                            $this->master_db->insertRecord('vouchers_users',$vuser);
                                        }
                                         $resul['status'] =true;
                                        $resul['msg'] ='Voucher redeemed successfully';
                                    }
                                }else {
                                    if($date  ==  $getCoupon[0]->to_date) {
                                        $totals = "";
                                        $totalDiscounts = $totalPrice  * $getCoupon[0]->discount  / 100;
                                        if($getCoupon[0]->discount ==100) {
                                            $totals .=$totalPrice;
                                        }else {
                                            $totals .=$totalDiscounts;
                                        }
                                        $getWallet = $this->master_db->getRecords('wallet',['user_id'=>$details['id']],'*');
                                        if(count($getWallet) >0) {
                                             $wamount = $getWallet[0]->amount;
                                             $w['amount'] = $wamount + $totals;
                                            $this->master_db->updateRecord('wallet',$w,['user_id'=>$details['id']]);
                                        }else {
                                            $ws['user_id'] =$details['id'];
                                            $ws['referral_code'] =random_strings(8);
                                            $ws['amount'] =$totals;
                                            $this->master_db->insertRecord('wallet',$ws);
                                        }
                                         if($getCoupon[0]->limit >0) {
                                            $vuser['coupon_id'] = $getCoupon[0]->id;
                                            $vuser['user_id'] = $details['id'];
                                            $this->master_db->insertRecord('vouchers_users',$vuser);
                                        }
                                        $resul['status'] =true;
                                        $resul['msg'] ='Voucher redeemed successfully';
                                    }
                                    else if($date  >  $getCoupon[0]->to_date) {
                                        $resul['status'] =false;
                                        $resul['msg'] ='Voucher code is expired';
                                    }
                                    else {
                                         $totals = "";
                                        $totalDiscounts = $totalPrice  * $getCoupon[0]->discount  / 100;
                                        if($getCoupon[0]->discount ==100) {
                                            $totals .=$totalPrice;
                                        }else {
                                            $totals .=$totalDiscounts;
                                        }
                                        $getWallet = $this->master_db->getRecords('wallet',['user_id'=>$details['id']],'*');
                                        if(count($getWallet) >0) {
                                             $wamount = $getWallet[0]->amount;
                                             $w['amount'] = $wamount + $totals;
                                            $this->master_db->updateRecord('wallet',$w,['user_id'=>$details['id']]);
                                        }else {
                                            $ws['user_id'] =$details['id'];
                                            $ws['referral_code'] =random_strings(8);
                                            $ws['amount'] =$totals;
                                            $this->master_db->insertRecord('wallet',$ws);
                                        }
                                         if($getCoupon[0]->limit >0) {
                                            $vuser['coupon_id'] = $getCoupon[0]->id;
                                            $vuser['user_id'] = $details['id'];
                                            $this->master_db->insertRecord('vouchers_users',$vuser);
                                        }
                                        $resul['status'] =true;
                                        $resul['msg'] ='Voucher redeemed successfully';
                                    }
                                }
                            }
                            else if($getCoupon[0]->minamount ==0) {
                                if($getCoupon[0]->type ==0) {
                                    if($date  ==  $getCoupon[0]->to_date) {
                                        $getWallet = $this->master_db->getRecords('wallet',['user_id'=>$details['id']],'*');
                                        if(count($getWallet) >0) {
                                             $wamount = $getWallet[0]->amount;
                                             $w['amount'] = $wamount +$getCoupon[0]->discount;
                                            $this->master_db->updateRecord('wallet',$w,['user_id'=>$details['id']]);
                                        }else {
                                            $ws['user_id'] =$details['id'];
                                            $ws['referral_code'] =random_strings(8);
                                            $ws['amount'] =$getCoupon[0]->discount;
                                            $this->master_db->insertRecord('wallet',$ws);
                                        }
                                         if($getCoupon[0]->limit >0) {
                                            $vuser['coupon_id'] = $getCoupon[0]->id;
                                            $vuser['user_id'] = $details['id'];
                                            $this->master_db->insertRecord('vouchers_users',$vuser);
                                        }
                                         $resul['status'] =true;
                                        $resul['msg'] ='Voucher redeemed successfully';
                                        $resul['status'] =true;
                                        $resul['msg'] ='Voucher redeemed successfully';
                                       
                                    }
                                    else if($date  >  $getCoupon[0]->to_date) {
                                        $resul['status'] =false;
                                        $resul['msg'] ='Voucher code is expired';
                                    }
                                    else {
                                        $getWallet = $this->master_db->getRecords('wallet',['user_id'=>$details['id']],'*');
                                        if(count($getWallet) >0) {
                                             $wamount = $getWallet[0]->amount;
                                             $w['amount'] = $wamount +$getCoupon[0]->discount;
                                            $this->master_db->updateRecord('wallet',$w,['user_id'=>$details['id']]);
                                        }else {
                                            $ws['user_id'] =$details['id'];
                                            $ws['referral_code'] =random_strings(8);
                                            $ws['amount'] =$getCoupon[0]->discount;
                                            $this->master_db->insertRecord('wallet',$ws);
                                        }
                                         if($getCoupon[0]->limit >0) {
                                            $vuser['coupon_id'] = $getCoupon[0]->id;
                                            $vuser['user_id'] = $details['id'];
                                            $this->master_db->insertRecord('vouchers_users',$vuser);
                                        }
                                         $resul['status'] =true;
                                        $resul['msg'] ='Voucher redeemed successfully';
                                        $resul['status'] =true;
                                        $resul['msg'] ='Voucher redeemed successfully';
                                        
                                    }
                                }else {
                                    if($date  ==  $getCoupon[0]->to_date) {
                                         $totals = "";
                                        $totalDiscounts = $totalPrice  * $getCoupon[0]->discount  / 100;
                                        if($getCoupon[0]->discount ==100) {
                                            $totals .=$totalPrice;
                                        }else {
                                            $totals .=$totalDiscounts;
                                        }
                                        $getWallet = $this->master_db->getRecords('wallet',['user_id'=>$details['id']],'*');
                                        if(count($getWallet) >0) {
                                             $wamount = $getWallet[0]->amount;
                                             $w['amount'] = $wamount + $totals;
                                            $this->master_db->updateRecord('wallet',$w,['user_id'=>$details['id']]);
                                        }else {
                                            $ws['user_id'] =$details['id'];
                                            $ws['referral_code'] =random_strings(8);
                                            $ws['amount'] =$totals;
                                            $this->master_db->insertRecord('wallet',$ws);
                                        }
                                         if($getCoupon[0]->limit >0) {
                                            $vuser['coupon_id'] = $getCoupon[0]->id;
                                            $vuser['user_id'] = $details['id'];
                                            $this->master_db->insertRecord('vouchers_users',$vuser);
                                        }
                                        $resul['status'] =true;
                                        $resul['msg'] ='Voucher redeemed successfully';
                                    }
                                    else if($date  >  $getCoupon[0]->to_date) {
                                        $resul['status'] =false;
                                        $resul['msg'] ='Voucher code is expired';
                                    }
                                    else {
                                         $totals = "";
                                        $totalDiscounts = $totalPrice  * $getCoupon[0]->discount  / 100;
                                        if($getCoupon[0]->discount ==100) {
                                            $totals .=$totalPrice;
                                        }else {
                                            $totals .=$totalDiscounts;
                                        }
                                        $getWallet = $this->master_db->getRecords('wallet',['user_id'=>$details['id']],'*');
                                        if(count($getWallet) >0) {
                                             $wamount = $getWallet[0]->amount;
                                             $w['amount'] = $wamount + $totals;
                                            $this->master_db->updateRecord('wallet',$w,['user_id'=>$details['id']]);
                                        }else {
                                            $ws['user_id'] =$details['id'];
                                            $ws['referral_code'] =random_strings(8);
                                            $ws['amount'] =$totals;
                                            $this->master_db->insertRecord('wallet',$ws);
                                        }
                                         if($getCoupon[0]->limit >0) {
                                            $vuser['coupon_id'] = $getCoupon[0]->id;
                                            $vuser['user_id'] = $details['id'];
                                            $this->master_db->insertRecord('vouchers_users',$vuser);
                                        }
                                        $resul['status'] =true;
                                        $resul['msg'] ='Voucher redeemed successfully';
                                    }
                                }
                            }else {
                                    $tote = $getCoupon[0]->minamount - $totalPrice;
                                    $resul['status'] =false;
                                    $resul['msg'] ='Buy Products worth Rs.'.$tote."/- and Get voucher applied <a href='".base_url()."' class='btn btn-dark btn-rounded' style='padding:5px;'>Shop Now</a>";
                                     echo json_encode($resul);exit;
                            }
                            }
                        }
                        
                    }else {
                        $resul['status'] =false;
                        $resul['msg'] ="Voucher does not exists try another";
                    }
                 }else {
                    $resul['formerror']= false;
                    $resul['coupon_error']= form_error('couponcode');
                 }
             }else {
                  $resul['status'] =false;
                  $resul['msg'] ="Registration is mandatory to avail gift vourchers";
             }
         }else {
            $resul['status'] =false;
            $resul['msg'] ="Invalid request";
         }
         echo json_encode($resul);
     }
}
