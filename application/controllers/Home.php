<?php
defined("BASEPATH") or exit("No direct script access allowed");

class Home extends CI_Controller
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
          $this->data['walletamount'] = [];
           $this->data['totalreferrals'] = [];
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
            "includes/header",
            $this->data,
            true
        );
        $this->data["header1"] = $this->load->view(
            "includes/header1",
            $this->data,
            true
        );
         $this->data["header2"] = $this->load->view(
            "includes/header2",
            $this->data,
            true
        );
          $this->data["header3"] = $this->load->view(
            "includes/header3",
            $this->data,
            true
        );
        $this->data["footer"] = $this->load->view(
            "includes/footer",
            $this->data,
            true
        );
         $this->data["footer1"] = $this->load->view(
            "includes/footer2",
            $this->data,
            true
        );
        $this->data["js"] = $this->load->view("jsFile", $this->data, true);
        $this->data["js1"] = $this->load->view("jsFile1", $this->data, true);
    }
    public function index()
    {
       $requesturi = $_SERVER['REQUEST_URI'];
        $this->session->set_userdata('urlredirect',$requesturi);
        $this->data["slider"] = $this->master_db->getRecords(
            "slider_img",
            ["status" => 0,'type'=>1],
            "image,stitle as title,stagline as tagline,sliderlink as link",
            "id desc"
        );
        //echo $this->db->last_query();exit;
        $this->data["ads1"] = $this->master_db->getRecords(
            "ads",
            ["status" => 0],
            "ad_banner,ad_name as title,ad_link as link,id,bstatus",
            "id desc",
            "",
            "",
            "2"
        );
        $this->data["ads2"] = $this->master_db->getRecords(
            "ads",
            ["status" => 0],
            "ad_banner,ad_name as title,ad_link as link,id",
            "id asc",
            "",
            "",
            "2"
        );
        $this->data["ads3"] = $this->master_db->getRecords(
            "ads",
            ["status" => 0, "below" => 0],
            "ad_banner,ad_name as title,ad_link as link,id",
            "id asc",
            "",
            "",
            "1"
        );
        //echo $this->db->last_query();exit;
        $this->data["categoryimg"] = $this->master_db->getRecords(
            "category",
            ["status" => 0, "image !=" => ""],
            "image,page_url,cname as title,id as cat_id",
            "orderno asc"
        );
         $query = "select p.id as pid,p.featuredimg as image,p.ptitle as title,ps.selling_price as price,ps.stock,p.page_url,c.page_url as cpage_url,ps.mrp,ps.sid,ps.pro_size as psid,ps.stock from products p left join product_size ps on ps.pid=p.id left join category c on c.id=p.cat_id where p.status =0 group by ps.pid order by p.orderno asc";

        $this->data["newarrivals"] = $this->master_db->sqlExecute($query);
        //echo $this->db->last_query();exit;
        $this->data["best"] = $this->home_db->getBestproducts();
        $this->data["feature"] = $this->home_db->getFeatureproducts();
        $this->data["category"] = $this->master_db->getRecords(
            "category",
            ["status" => 0, "showcategory" => 0],
            "id as cat_id,page_url,cname,ads_image,icons",
            "orderno asc",
            "",
            "",
            "6"
        );
        $this->data["brand"] = $this->master_db->getRecords(
            "brand",
            ["status" => 0],
            "brand_img",
            "id desc",
            "",
            "",
            "12"
        );
        $this->load->view("index", $this->data);
    }
    public function category()
    {
        $this->load->view("maincategory", $this->data);
    }
    public function products()
    {
        $page_url = icchaDcrypt($this->uri->segment(3));
       $this->data['filters'] = $this->home_db->getProductsize($page_url);
       $this->data['pfilters'] = $this->master_db->getRecords('pricefilters',['id !='=>-1],'*','order_by asc');
      // echo $this->db->last_query();exit;
        $this->load->view("products", $this->data);
    }
    public function productsFilters() {
       $id = icchaDcrypt($this->input->post('id'));
         $limit = $this->input->post('limit');
        $start = $this->input->post('start');
        $price = $this->input->post('price');
       $this->data['products'] = $products =  $this->master_db->sqlExecute('select p.id as pid,p.featuredimg as image,p.ptitle as title,ps.selling_price as price,p.page_url,s.page_url as spage_url,p.page_url,ss.page_url as sspage_url,ps.mrp,ps.pro_size as psid from products p left join product_size ps on p.id = ps.pid left join subcategory s on s.id = p.subcat_id left join subsubcategory ss on ss.id = p.sub_sub_id left join brand b on b.id = p.brand_id where p.cat_id = '.$id.' and p.status =0 group by ps.pid,p.ptitle order by p.orderno limit '.$start.','.$limit.'  ');
            //echo $this->db->last_query();exit;
            $html =  $this->load->view('productsfilterslist',$this->data,true);
            $output = ['status'=>true,'data'=>$html];
       
       // exit;
        echo json_encode($output);
    }
    public function checkpincode() {
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
            $this->form_validation->set_rules('cpincode','Pincode','trim|required|numeric|exact_length[6]',['exact_length'=>'Six numbers are allowed','numeric'=>'Only numbers are allowed','required'=>'Pincode is required']);
             if($this->form_validation->run() ==TRUE) {
                 $cpincode = trim(preg_replace('!\s+!', ' ',html_escape($this->input->post('cpincode', true))));
                 $count = $this->master_db->sqlExecute('select c.noofdays,amount from pincodes p left join cities c on c.id = p.cid where p.pincode='.$cpincode.'');
                // echo $this->db->last_query();exit;
                 if(count($count) >0) {
                    $subtotal =[];
                    $this->session->set_userdata('pincode',$cpincode);
                    $date = date('d-m-Y',strtotime('+'.$count[0]->noofdays.' days'));
                    $array = array(
                        'status'   => true,
                        'msg' => '<b style="font-size:15px;"><i class="fas fa-shuttle-van" style="margin-right:5px"></i>Within 24hrs</b><br />',
                        'value' =>set_value($cpincode),
                        'amount' =>$count[0]->amount
                     );
                    $this->session->set_userdata('pincodes',$cpincode);
                 }else {
                    $array = array(
                      'status'   => false,
                      'msg' => 'Your offer is getting ready soon. Please stay with us',
                    );
                 }
             }
             else {
                $array = array(
                    'formerror'   => false,
                    'pincode_error' => form_error('cpincode'),
               );
             }
         }else {
             $array = array(
                        'status'   => false,
                        'msg' => 'Invalid request',
                     );
         }
         echo json_encode($array);
    }
      public function pincodeCheck() {
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
                 $cpincode = trim(preg_replace('!\s+!', ' ',html_escape($this->input->post('cpincode', true))));
                 $count = $this->master_db->sqlExecute('select c.noofdays,amount from pincodes p left join cities c on c.id = p.cid where p.pincode='.$cpincode.'');
                 if(count($count) >0) {
                  $urls = "https://api.postalpincode.in/pincode/".$cpincode;
                    $da = file_get_contents($urls);
                    $dec = json_decode($da,true);

                    $st = $dec[0]['Status'];
                    $diss ="";$citys="";$states ="";
                    if($st =='Success') {
                      $dis = $dec[0]['PostOffice'][0]['District'];
                      $city = $dec[0]['PostOffice'][0]['Division'];
                      $state = $dec[0]['PostOffice'][0]['State'];
                      //echo $state;exit;
                      $getstate = $this->master_db->getRecords('states',['name'=>$state],'id');
                      $getcity = $this->master_db->getRecords('cities',['cname'=>$dis],'id');
                      $getarea = $this->master_db->getRecords('area',['areaname'=>$city],'id');
                     // echo $this->db->last_query();exit;
                      $diss .=$getarea[0]->id;
                      $citys .=$getcity[0]->id;
                      $states .=$getstate[0]->id;
                    }

                    
                    //echo "<pre>";print_r($dec);exit;
                    $totalAmount ="";$delam ="";
                    $deliveryamount ="";
                    if($this->cart->total() >=299) {
                        $deliveryamount .="Free Delivery";
                        $delam .=0;
                    }else {
                        $deliveryamount .='<i class="fas fa-rupee-sign"></i> 50';
                        $delam .=50;
                    }
                    if($this->session->userdata('discount')) {
                        $amount = floatval($this->cart->total() +$delam)   * $this->session->userdata('discount') / 100;
                        $totalAmount .= '<span id="totalAmount">'. sprintf("%.2f",$this->cart->total() +$delam - $amount).'<div id="pincodeAm"></div></span>';
                    }else {
                        $totalAmount .= '<span id="totalAmount">'. sprintf("%.2f",$delam + $this->cart->total()).'<div id="pincodeAm"></div></span>';
                    }
                    //echo $totalAmount;exit;
                    $this->session->set_userdata('pamount',$delam);
                    $date = date('D m, y',strtotime('+'.$count[0]->noofdays.' days'));
                    $array = array(
                        'status'   => true,
                        'msg' => '<b>Shipping is available to entered pincode '.$cpincode.'</b>',
                        'value' =>set_value($cpincode),
                        'amount' =>''.$deliveryamount,
                        'totalamount'=>$totalAmount,
                        'dis'   =>$diss,
                        'city'=>$citys,
                        'state' =>$states
                     );
                 }else {
                    $array = array(
                    'status'   => false,
                    'msg' => '<font style="color:red;font-size:14px;font-weight:bold">Unfortunately we do not ship to your pincode</font>',
               );
            }
         }else {
             $array = array(
                        'status'   => false,
                        'msg' => 'Invalid request',
                     );
         }
         echo json_encode($array);
    }
      public function reviews() {
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
                 $this->form_validation->set_rules('reviews','Reviews','trim|required|regex_match[/^([A-Za-z0-9 ])+$/i]',['regex_match'=>'Invalid reviews','numeric'=>'Only numbers are allowed','required'=>'Review is required']);
                 if($this->form_validation->run() ==TRUE) {
                     $reviews = trim(preg_replace('!\s+!', ' ',html_escape($this->input->post('reviews', true))));
                     $pid = trim(preg_replace('!\s+!', ' ',html_escape($this->input->post('pid', true))));
                     $ratings = trim(preg_replace('!\s+!', ' ',html_escape($this->input->post('ratings', true))));
                     if($this->session->userdata(CUSTOMER_SESSION)) {
                      $getReview = $this->master_db->getRecords('reviews',['pid'=>decode($pid),'user_id'=>$this->session->userdata(CUSTOMER_SESSION)['id']],'*');
                      if(count($getReview) >0) {
                          $array = array(
                                'status'   => false,
                                'msg' => '<font style="color:red;font-size:14px;font-weight:bold">Review already submitted to products try another product </font>',
                                'csrf_token'=> $this->security->get_csrf_hash()
                            );
                          echo json_encode($array);exit;
                      } else {
                          $db['pid'] = decode($pid);
                        $db['review_descp'] = $reviews;
                        $db['rating'] = $ratings;
                        $db['user_id'] = $this->session->userdata(CUSTOMER_SESSION)['id'];
                        $db['created_at'] = date('Y-m-d H:i:s');
                        $insert = $this->master_db->insertRecord('reviews',$db);
                        if($insert >0) {
                            $array = array(
                                'status'   => true,
                                'csrf_token'=> $this->security->get_csrf_hash(),
                                'msg' => 'Review saved successfully'
                             );
                         }else {
                            $array = array(
                                'status'   => false,
                                'msg' => '<font style="color:red;font-size:14px;font-weight:bold">Unfortunately we do not ship to your pincode</font>',
                                'csrf_token'=> $this->security->get_csrf_hash()
                            );
                        }
                      }
                        
                     }else {
                            $array = array(
                                'status'   => -1,
                                'url' => base_url().'login',
                                'csrf_token'=> $this->security->get_csrf_hash()
                            );
                     }
                 }
                 else {
                    $array = array(
                        'formerror'   => false,
                        'rating_error' => form_error('reviews'),
                        'csrf_token'=> $this->security->get_csrf_hash()
                   );
                 }
             }else {
                     $array = array(
                                'status'   => -1,
                                'msg' => 'Invalid request',
                                'csrf_token'=> $this->security->get_csrf_hash()
                            );
             }
         echo json_encode($array);
    }
    public function state() {
        //echo "<pre>";print_r($_POST);
        $id = trim($this->input->post('id'));
        $sid = trim($this->input->post('sid'));
        if(!empty($id)) {
            $getCity = $this->master_db->getRecords('cities',['sid'=>$id],'id,cname');
             $html ="";$naming ="";
            
             $html .="<option value=''>Select City</otpion>";
            if(count($getCity) >0) {
                foreach ($getCity as  $value) {
                    $html .="<option value='".$value->id."'>".$value->cname."</option>";
                } 
                $array = ['status'=>true,'msg'=>$html];  
            }else {
                $array = ['status'=>false,'msg'=>$html];
            }
        }else {
            $array = ['status'=>false,'msg'=>'Required fields are missing'];
        }
        echo json_encode($array);
    }
    public function city() {
        $id = trim($this->input->post('id'));
        $sid = trim($this->input->post('sid'));
        if(!empty($id)) {
            $getCity = $this->master_db->getRecords('area',['cid'=>$id],'id,areaname as cname');
             $html ="";
            
             $html .="<option value=''>Select Area</otpion>";
            if(count($getCity) >0) {
                foreach ($getCity as  $value) {
                    $html .="<option value='".$value->id."'>".$value->cname."</option>";
                } 
                $array = ['status'=>true,'msg'=>$html];  
            }else {
                $array = ['status'=>false,'msg'=>$html];
            }
        }else {
            $array = ['status'=>false,'msg'=>'Required fields are missing'];
        }
        echo json_encode($array);
    }
     public function sizechangelist() {
      $sid = $this->input->post('sid');
      $this->session->set_userdata('propsid',$sid);
      $getPrice = $this->master_db->getRecords('product_size',['pro_size'=>$sid,'stock !='=>0],'selling_price,mrp');
      //echo $this->db->last_query();exit;
      if(count($getPrice) >0) {
         $mrps = "";
            if(!empty($getPrice[0]->mrp)) {
                $mrps .=$getPrice[0]->mrp - $getPrice[0]->selling_price;
            }
        $res = ['status'=>true,'price'=>'<i class="fas fa-rupee-sign"></i> '.$getPrice[0]->selling_price,'mrp'=>'<i class="fas fa-rupee-sign"></i> '.$getPrice[0]->mrp,'saved'=>'<i class="fas fa-rupee-sign"></i> '.$mrps];
      }else {
        $res =['status'=>false,'msg'=>'Out of stock'];
      }
      echo json_encode($res);
    }
    public function getPrice() {
         $limit = $this->input->post('limit');
        $start = $this->input->post('start');
        $price = $this->input->post('price');
        $order = $this->input->post('order');
        $id = icchaDcrypt($this->input->post('id'));
       // echo "<pre>";print_r($_POST);exit;
        $lastid =[];$firstid =[];
        $pricefilts = "";
        $order_by ="";
        if($order ==1) {
                    $order_by .=" order by CAST(ps.selling_price AS DECIMAL(10,2))";
                }else if($order ==2) {
                    $order_by .=" order by CAST(ps.selling_price AS DECIMAL(10,2))";
                }
                else if($order ==3) {
                    $order_by .=" order by p.id desc";
                }
                else {
                    $order_by .=" order by p.id desc";
                }

          if(is_array($price) && !empty($price)) {
             $pricecom = implode(",", $price);
              if($pricecom ==1) {
              $pricefilts .=" and ps.selling_price between 50 and 100";
            }
            else if($pricecom ==2) {
              $pricefilts .=" and ps.selling_price between 100 and 300";
            }
            else if($pricecom ==3) {
              $pricefilts .=" and ps.selling_price between 300 and 500";
            }
            else if($pricecom ==4) {
              $pricefilts .=" and ps.selling_price between 500 and 1000";
            }
            else if($pricecom ==5) {
              $pricefilts .=" and ps.selling_price >=1000";
            }
            else if($pricecom ==6) {
              $pricefilts .=" and ps.selling_price between 1 and 50";
            }
            else if($pricecom =="1,2") {
                $pricefilts .=" and ps.selling_price between 50 and 300";
            }
            else if($pricecom =="1,2,3") {
                $pricefilts .=" and ps.selling_price between 50 and 500";
            }
            else if($pricecom =="1,2,3,4") {
                $pricefilts .=" and ps.selling_price between 50 and 1000";
            }
            else if($pricecom =="1,2,3,4,6") {
                $pricefilts .=" and ps.selling_price between 1 and 1000";
            }
            // else if($pricecom =="1,2,3,4,5") {
            //     $pricefilts .=" and ps.selling_price >=1000";
            // }
            else if($pricecom =="2,1") {
                $pricefilts .=" and ps.selling_price between 50 and 300";
            }
            else if($pricecom =="2,3") {
                $pricefilts .=" and ps.selling_price between 300 and 500";
            }
            else if($pricecom =="2,4") {
                $pricefilts .=" and ps.selling_price between 300 and 1000";
            }
            else if($pricecom =="2,5") {
                $pricefilts .=" and ps.selling_price between 300 and 1000";
            }
            else if($pricecom =="2,6") {
                $pricefilts .=" and ps.selling_price between 1 and 300";
            }
             else if($pricecom =="2,1,3") {
                $pricefilts .=" and ps.selling_price between 50 and 500";
            }
            else if($pricecom =="2,1,4") {
                $pricefilts .=" and ps.selling_price between 50 and 1000";
            }
             else if($pricecom =="2,1,5") {
                $pricefilts .=" and ps.selling_price between 50 and 1500";
            }
            else if($pricecom =="2,1,6") {
                $pricefilts .=" and ps.selling_price between 1 and 300";
            }
          }else {
            $pricefilts .="";
          }
            

            
       $qu = 'select p.id as pid,p.featuredimg as image,p.ptitle as title,ps.selling_price as price,p.page_url,s.page_url as spage_url,p.page_url,ss.page_url as sspage_url,ps.mrp,ps.pro_size as psid from products p left join product_size ps on p.id = ps.pid left join subcategory s on s.id = p.subcat_id left join subsubcategory ss on ss.id = p.sub_sub_id left join brand b on b.id = p.brand_id where p.cat_id = '.$id.' and p.status =0 '.$pricefilts.'  group by ps.pid,p.ptitle '.$order_by.' limit '.$start.','.$limit.'  ';
       
     $this->data['products'] = $this->master_db->sqlExecute($qu);
     //echo $this->db->last_query();exit;
    $html =  $this->load->view('productsfilterslist',$this->data,true);
    echo $html;
  }

    public function specialoffers() {
      $this->load->view('specialoffers',$this->data);
    }
    public function dealoftheday() {
      $this->load->view('dealoftheday',$this->data);
    }
    public function contact() {
      $this->load->view('contact',$this->data);
    }
    public function terms() {
      $this->data['term'] = $this->master_db->getRecords('terms',['id '=>1],'*');
      $this->load->view('terms',$this->data);
    }
     public function privacy() {
      $this->data['privacypolicy'] = $this->master_db->getRecords('privacypolicy',['id '=>1],'*');
      $this->load->view('privacy',$this->data);
    }
    public function getProdsizerate() {
      $pid = $this->input->post('pid');
      $psid = $this->input->post('psid');
      $this->session->set_userdata('propsid',$psid);
      $getprice = $this->master_db->getRecords('product_size',['pro_size'=>$psid,'pid'=>$pid,'stock !='=>0],'mrp,selling_price');
      //echo $this->db->last_query();exit;
      if(count($getprice) >0) {
                                    $mrp = $getprice[0]->mrp;
                                    $sell = $getprice[0]->selling_price;
                                    //$disc = $this->home_db->discount($mrp, $sell);
                                    $disc = $mrp - $sell;
        echo json_encode(['status'=>true,'price'=>'<i class="fas fa-rupee-sign" style="margin:0px 1px;"></i> '.$getprice[0]->selling_price,'mrp'=>' <i class="fas fa-rupee-sign" style="margin:0px 3px;"></i> '.$getprice[0]->mrp,'discount'=>'Saved <i class="fas fa-rupee-sign" style="margin:0px 3px;"></i>'.round($disc)]);
      }else {
         echo json_encode(['status'=>false,'msg'=>'Out of Stock']);
      }
    }
     public function getProdsizeratecategorywise() {
     // echo "<pre>";print_r($_POST);exit;
      $pid = $this->input->post('pid');
      $psid = $this->input->post('psid');
      $this->session->set_userdata('propsid',$psid);
      $getprice = $this->master_db->getRecords('product_size',['pro_size'=>$psid,'pid'=>$pid,'stock !='=>0],'mrp,selling_price');
      //echo $this->db->last_query();exit;
      if(count($getprice) >0) {
         $mrp = $getprice[0]->mrp;
                                    $sell = $getprice[0]->selling_price;
                                    // $disc = $this->home_db->discount($mrp, $sell);
                                    $disc = $mrp - $sell;
        echo json_encode(['status'=>true,'price'=>' <i class="fas fa-rupee-sign" style="margin:0px 1px;"></i> '.$getprice[0]->selling_price,'mrp'=>' <i class="fas fa-rupee-sign" style="margin:0px 3px;"></i> '.$getprice[0]->mrp,'discount'=>'Saved <i class="fas fa-rupee-sign" style="margin:0px 1px;"></i> '.round($disc)]);
      }
      else {
         echo json_encode(['status'=>false,'msg'=>'Out of Stock']);
      }
    }
    public function disclaimer() {
        $this->data['shippingpolicy'] = $this->master_db->getRecords('shippingpolicy',['id '=>1],'*');
        $this->load->view('disclaimer',$this->data);
    }
    public function deliverypolicy() {
        $this->data['returnpolicy'] = $this->master_db->getRecords('returnpolicy',['id '=>1],'*');
        $this->load->view('deliverypolicy',$this->data);
    }
   

     public function subscribetochannel() {
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
                //echo "<pre>";print_r($_POST);exit;
                $this->load->library('form_validation');
                 $this->form_validation->set_rules('email','Email','required|valid_email|regex_match[/^[_A-Za-z0-9-]+(\.[_A-Za-z0-9-]+)*@[A-Za-z0-9-]+(\.[A-Za-z0-9-]+)*(\.[A-Za-z]{2,10})$/]',['required'=>'Email is required','valid_email'=>'Enter valid email','regex_match'=>'Invalid email']);
                 if($this->form_validation->run() ==TRUE) {
                    $email = trim(html_escape($this->input->post('email')));
                    $getemail = $this->master_db->getRecords('subscribe',['email'=>$email],'*');
                   
                    if(count($getemail) >0) {
                        $resul = array(
                             'status'   => false,
                             'msg'      =>'Email already subscribed',
                        );
                    }else {
                        $db['email'] = $email;
                        $db['created_at'] =date('Y-m-d H:i:s');
                        $this->master_db->insertRecord('subscribe',$db);
                           $resul = array(
                                 'status'   => true,
                                 'msg'      =>'Email subscribed successfully',
                                 
                            );
                    }
                 }else {
                    $resul = array(
                             'formerror'   => false,
                            'email_error' => form_error('email'),
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
    public function about() {
      $this->load->view('about',$this->data);
    }
  public function autocomplete() {
      $searchTerm=$this->input->get('term');
      $searchData   =[];
      $getData = $this->master_db->sqlExecute("select p.ptitle,p.id from category c left join products p on p.cat_id = c.id where p.ptitle like '%".$searchTerm."%' ");
      //echo $this->db->last_query();exit;
      if(count($getData) >0) {
          foreach($getData as $val) {
              $data['id'] = $val->id;
              $data['value'] = $val->ptitle;
              array_push($searchData,$data);
          }
      }
      echo json_encode($searchData);
  }

  public function searchResults() {
    $search = $this->input->get('search');
    $qu = "select p.id as pid,p.featuredimg as image,p.ptitle as title,ps.selling_price as price,ps.stock,p.page_url,c.page_url as cpage_url,ps.mrp,ps.sid,ps.pro_size as psid from products p left join product_size ps on ps.pid=p.id left join category c on c.id=p.cat_id where p.ptitle like '%".$search."%' and p.status =0  group by ps.pid order by p.orderno";
    $this->data['products'] = $this->master_db-> sqlExecute($qu);
    //echo $this->db->last_query();
    $this->load->view('search',$this->data);
  }
  public function getSearchsort() {
      
        $order = $this->input->post('order');
        $title = $this->input->post('title');
       // echo "<pre>";print_r($_POST);exit;
        $lastid =[];$firstid =[];
        $pricefilts = "";
        $order_by ="";
        if($order ==1) {
                    $order_by .=" order by ps.selling_price desc";
                }else if($order ==2) {
                    $order_by .=" order by ps.selling_price asc";
                }
                else if($order ==3) {
                    $order_by .=" order by p.id desc";
                }
                else {
                    $order_by .=" order by p.id desc";
                }

            

            
       $qu = 'select p.id as pid,p.featuredimg as image,p.ptitle as title,ps.selling_price as price,p.page_url,s.page_url as spage_url,p.page_url,ss.page_url as sspage_url,ps.mrp,ps.pro_size as psid from products p left join product_size ps on p.id = ps.pid left join subcategory s on s.id = p.subcat_id left join subsubcategory ss on ss.id = p.sub_sub_id left join brand b on b.id = p.brand_id  where p.ptitle like "%'.$title.'%" and p.status =0 '.$pricefilts.'  group by ps.pid,p.ptitle '.$order_by.' ';
       
     $this->data['products'] = $this->master_db->sqlExecute($qu);
     //echo $this->db->last_query();exit;
    $html =  $this->load->view('productsfilterslist',$this->data,true);
    echo $html;
  }
   public function contestsave() {
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
                //echo "<pre>";print_r($_POST);exit;
                $this->load->library('form_validation');
                 $this->form_validation->set_rules('name','Name','required',['required'=>'Name is required','regex_match'=>'Invalid name']);
                 $this->form_validation->set_rules('phone','Mobile Number','required|numeric|exact_length[10]',['required'=>'Mobile Number is required','exact_length'=>'Mobile Number should be 10 digit']);
                 if($this->form_validation->run() ==TRUE) {
                    $name = trim(html_escape($this->input->post('name')));
                    $phone = trim(html_escape($this->input->post('phone')));
                    $getemail = $this->master_db->getRecords('contest',['mobileno'=>$phone],'*');
                   
                    if(count($getemail) >0) {
                        $resul = array(
                             'status'   => false,
                             'msg'      =>'Mobile number already exists try another',
                        );
                    }else {
                        $db['name'] = $name;
                        $db['mobileno'] = $phone;
                        $db['created_at'] =date('Y-m-d H:i:s');
                        $this->master_db->insertRecord('contest',$db);
                           $resul = array(
                                 'status'   => true,
                                 'msg'      =>'Contest saved successfully',
                                 
                            );
                    }
                 }else {
                    $resul = array(
                             'formerror'   => false,
                            'name_error' => form_error('name'),
                            'phone_error' => form_error('phone')
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
    public  function cartnew() {
      $this->load->view('cart_new',$this->data);
    }
 	public function checkhomepicode() {
   // echo "<pre>";print_r($_POST);exit;
         if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
                 $cpincode = trim(preg_replace('!\s+!', ' ',html_escape($this->input->post('cpincodes', true))));
                 $count = $this->master_db->sqlExecute('select c.noofdays,amount from pincodes p left join cities c on c.id = p.cid where p.pincode='.$cpincode.'');
                // echo $this->db->last_query();exit;
                 if(count($count) >0) {
                    $subtotal =[];
                   // $this->session->set_userdata('pincode',$cpincode);
                    $date = date('d-m-Y',strtotime('+'.$count[0]->noofdays.' days'));
                    //$this->session->set_userdata('pincodes',$cpincode);
                      $id = decode($_POST["pid"]);
                $psid =$_POST["psid"];
              
                $product = $this->cart_db->getCartproducts($psid);
                //echo $this->db->last_query();exit;
                $db = [
                    "id" =>$psid,
                    "qty" => 1,
                    "image" => base_url().$product[0]->image,
                    "price" => $product[0]->price,
                    "discount" => $product[0]->discount,
                    "pcode" => $product[0]->pcode,
                    "name" => $product[0]->ptitle,
                    "purl" => $product[0]->page_url,
                    "stock" => $product[0]->stock,
                    "pid" => $product[0]->pid,
                    "sizeid" => $product[0]->sid,
                    "cid" => $product[0]->coid,
                    "sid"  =>$psid,
                    "taxamount" =>$product[0]->tax,
                    "sname"=>$product[0]->sname,
                    "plink" => base_url() . "products/" . $product[0]->page_url . "",
                    "dmsg" => "",
                ];
                if ($this->cart->insert($db)) {
                    $subtotal = [];
                       $totaldiff = "";

                       $tax =[];
                    foreach ($this->cart->contents() as $cart) {
                        $subtotal[] = $cart['subtotal'] * $cart['qty'];
                    }
                    if($this->cart->total() >=299) {
                        $totaldiff .=" Shpping Free";
                    }else {
                         $ams = 300;
                        $tt = $this->cart->total();
                        $totr = $ams - $tt;
                        $totaldiff .=" Add Rs.".$totr."  for Free Delivery";
                    }
                    
                    $html = $this->load->view("cartitems", $this->data, true);
                    $array = [
                        "status" => true,
                        "msg" => $html,
                        "count" => count($this->cart->contents()),
                        'subtotal'=>array_sum($subtotal),
                        'totaldiff'=>$totaldiff
                    ];
                } else {
                    $array = [
                        "status" => false,
                        "msg" => "Error in adding",
                    ];
                }
                 }else {
                    $array = array(
                    'status'   => false,
                    'msg' => 'We will deliver soon to your area” <button class="btn btn-primary btn-sm" onclick="notifyme()">Notify Me</button>',
               );
                 }
            
         }else {
             $array = array(
                        'status'   => false,
                        'msg' => 'Invalid request',
                     );
         }
         echo json_encode($array);
    }

      public function checkspecialredirects() {
          $details = $this->session->userdata($this->data['session']);
         if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
                 $cpincode = trim(preg_replace('!\s+!', ' ',html_escape($this->input->post('cpincodes', true))));
                 $count = $this->master_db->sqlExecute('select c.noofdays,amount from pincodes p left join cities c on c.id = p.cid where p.pincode='.$cpincode.' and site_type=2');
                 $sessiondata ="";
                 if($this->session->userdata($this->data['session'])) {
                  $getOtp = $this->master_db->getRecords('users',['u_id'=>$details['id']],'u_id as id,name,email,phone');
                    $sessiondata .="&login=".icchaEncrypt($getOtp[0]->id);
                 }else {
                    $sessiondata .="";
                 }
                // echo $this->db->last_query();exit;
                 if(count($count) >0) {
                    $getSpecial = $this->master_db->getRecords('pincodes',['pincode'=>$cpincode,'site_type'=>2],'*');
                    //echo $this->db->last_query();exit;
                    if(count($getSpecial) >0) {
                      $arrays['status'] = 'success';
                      $arrays['url'] = "https://www.swarnagowri.com/specialoffers?pin=".$cpincode.$sessiondata;
                     // echo "<pre>";print_r($arrays);exit;
                      echo json_encode($arrays);exit;
                    }
                   $array = ["status" => true];
                   echo json_encode($array);exit;
                 }else {
                    $array = array(
                      'status'   => false,
                      'msg' => 'Your offer is getting ready soon. Please stay with us',
                  );
                }
         }else {
             $array = array(
                        'status'   => false,
                        'msg' => 'Invalid request',
                     );
         }
         echo json_encode($array);
    }
    public function demoindex()
    {
       $requesturi = $_SERVER['REQUEST_URI'];
        $this->session->set_userdata('urlredirect',$requesturi);
        $this->data["slider"] = $this->master_db->getRecords(
            "slider_img",
            ["status" => 0],
            "image,stitle as title,stagline as tagline,sliderlink as link",
            "id desc"
        );
        $this->data["ads1"] = $this->master_db->getRecords(
            "ads",
            ["status" => 0, "above" => 0],
            "ad_banner,ad_name as title,ad_link as link,id",
            "id desc",
            "",
            "",
            "2"
        );
        $this->data["ads2"] = $this->master_db->getRecords(
            "ads",
            ["status" => 0, "below" => 0],
            "ad_banner,ad_name as title,ad_link as link,id",
            "id asc",
            "",
            "",
            "2"
        );
        $this->data["ads3"] = $this->master_db->getRecords(
            "ads",
            ["status" => 0, "below" => 0],
            "ad_banner,ad_name as title,ad_link as link,id",
            "id asc",
            "",
            "",
            "1"
        );
        //echo $this->db->last_query();exit;
        $this->data["categoryimg"] = $this->master_db->getRecords(
            "category",
            ["status" => 0, "image !=" => ""],
            "image,page_url,cname as title,id as cat_id",
            "order_by asc"
        );
         $query = "select p.id as pid,p.featuredimg as image,p.ptitle as title,ps.selling_price as price,ps.stock,p.page_url,c.page_url as cpage_url,ps.mrp,ps.sid,ps.pro_size as psid from products p left join product_size ps on ps.pid=p.id left join category c on c.id=p.cat_id where p.newarrivals =0 and p.status =0 group by ps.pid order by CAST(ps.selling_price AS DECIMAL(10,2)) limit 10";

        $this->data["newarrivals"] = $this->master_db->sqlExecute($query);
        //echo $this->db->last_query();exit;
        $this->data["best"] = $this->home_db->getBestproducts();
        $this->data["feature"] = $this->home_db->getFeatureproducts();
        $this->data["category"] = $this->master_db->getRecords(
            "category",
            ["status" => 0, "showcategory" => 0],
            "id as cat_id,page_url,cname,ads_image,icons",
            "order_by asc",
            "",
            "",
            "6"
        );
        $this->data["brand"] = $this->master_db->getRecords(
            "brand",
            ["status" => 0],
            "brand_img",
            "id desc",
            "",
            "",
            "12"
        );
        $this->load->view("index1", $this->data);
    }
  
    public function phpinfo() {
      echo phpinfo();
    }
    public function rasapaka() {
       $this->data['products'] = $products =  $this->master_db->sqlExecute('select p.id as pid,p.featuredimg as image,p.ptitle as title,ps.selling_price as price,p.page_url,s.page_url as spage_url,p.page_url,ss.page_url as sspage_url,ps.mrp,ps.pro_size as psid from products p left join product_size ps on p.id = ps.pid left join subcategory s on s.id = p.subcat_id left join subsubcategory ss on ss.id = p.sub_sub_id left join brand b on b.id = p.brand_id where p.status =0 group by ps.pid,p.ptitle order by ps.selling_price desc  ');
      // echo $this->db->last_query();exit;
      $this->load->view('rasapaka',$this->data);
    }
    public function smslink() {
      $this->load->library('SMS');
      $message = "Dear User, Your OTP for https://www.swarnagowri.com/ verification is 1260. Regards, Team SWARNAGOWRI. Powered by Homeneeds Cart Pvt Ltd.";
      $phone = 9986571768;
      $this->sms->sendSmsToUser($message,$phone);
    }
    public function savenotification() {
       if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
              $this->load->library('form_validation');
                 $this->form_validation->set_rules('phoneno','Name','required|numeric|exact_length[10]',['required'=>'Phone number is required','numeric'=>'Only numeric value is allowed','exact_length'=>'Exactly 10 numbers are allowed']);
                 $this->form_validation->set_rules('emailid','Emailid','required|valid_email|regex_match[/^[_A-Za-z0-9-]+(\.[_A-Za-z0-9-]+)*@[A-Za-z0-9-]+(\.[A-Za-z0-9-]+)*(\.[A-Za-z]{2,10})$/]',['required'=>'Email is required','valid_email'=>'Enter valid email','regex_match'=>'Invalid email']);
                 if($this->form_validation->run() ==TRUE) {
                 $phoneno = trim(preg_replace('!\s+!', ' ',html_escape($this->input->post('phoneno', true))));
                 $emailid = trim(preg_replace('!\s+!', ' ',html_escape($this->input->post('emailid', true))));
                 $db['phone_number'] = $phoneno;
                 $db['emailid'] = $emailid;
                 $db['created_at'] = date('Y-m-d H:i:s');
                 $in = $this->master_db->insertRecord('notification',$db);
                 if($in>0) {
                    $array = ["status" => true,"msg" => 'Thank you for submitting, We will notify you once the product is available on your area'];
                 }else {
                  $array = ["status" => false,"msg" => 'Error in submitting'];
                 }
               }else {
                   $array = array(
                             'formerror'   => false,
                            'phoneno_error' => form_error('phoneno'),
                            'emailid_error' => form_error('emailid')
                        );
               }
         }else {
             $array = array(
                        'status'   => false,
                        'msg' => 'Invalid request',
                     );
         }
         echo json_encode($array);
    }
    public function webhooks() {
        $getfile = file_get_contents("php://input");
        $encode = json_encode($getfile);
        $decode = json_decode($encode,true);
        $content = parse_str($getfile,$queryString);
        $pay_array = json_encode($queryString);
        $encData = $queryString['encData'];
        $data['data'] = $encData;
        $data['merchId'] = LOGIN;
        $data['ResponseDecryptionKey'] = ResponseDecryptionKey;
        $this->load->library('AtompayResponse',$data); 
        $arrayofdata = $this->atompayresponse->decryptResponseIntoArray($encData);
        $json = json_encode($arrayofdata);
        $date = date('d-m-Y h:i:s').".json";
         file_put_contents("assets/webhooks/".$date,$json);
         if(is_array($arrayofdata)) {
            $oid =$arrayofdata['extras']['udf4'];
            $pay_array = json_encode($arrayofdata);
            $statusCode =  $arrayofdata['responseDetails']['statusCode'];
            $payid = $arrayofdata['payModeSpecificData']['bankDetails']['bankTxnId'];
            $foid =  $arrayofdata['payDetails']['custAccNo'];
            $signature = $arrayofdata['payDetails']['signature'];
            if($statusCode =="OTS0000") {
                  $payme['pay_array'] = $pay_array;
                  $payme['pay_id'] = $payid;
                  $payme['signature'] = $signature;
                  $payme['endata'] = $encData;
                  $payme['pstatus'] = "success";
                  $payme['status'] = 1;
                   $this->master_db->updateRecord('payment_log',$payme,['oid'=>$oid]);
                    $ord['status'] = 5;
                    $this->master_db->updateRecord('orders',$ord,['oid'=>$oid]);
            }
            else if($statusCode =="OTS0101") {
                echo "cancel";
            }
            else if($statusCode =="OTS0600") {
                $ord['status'] = -1;
                $this->master_db->updateRecord('orders',$ord,['oid'=>$foid]);
                $payme['pay_array'] = $pay_array;
                $payme['endata'] = $encData;
                $payme['pstatus'] = "failure";
                $payme['status'] = -1;
                $payid = $this->master_db->updateRecord('payment_log',$payme,['oid'=>$foid]);
            }
            else if($statusCode =="OTS0551") {
                echo "pending";
            }
            else if($statusCode =="OTS0201" || $statusCode =="OTS0401" || $statusCode =="OTS0451" || $statusCode =="OTS0501" || $statusCode =="OTS0301" || $statusCode =="OTS0351" || $statusCode =="OTS0951") {

            }
        } 
    }
    public function discount_type() {
      echo $this->session->userdata('discount_type');
    }

     
}
?>
