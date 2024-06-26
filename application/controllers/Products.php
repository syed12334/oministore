<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Products extends CI_Controller {
    protected $data;
    public function __construct() {
        date_default_timezone_set("Asia/Kolkata");
        parent::__construct();
        $this->load->helper("utility_helper");
        $this->load->model("master_db");
        $this->load->model("home_db");
        $this->data["detail"] = "";
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
          $this->data['walletamount'] = [];
           $this->data['totalreferrals'] = [];
        }
        if(isset($_GET['referral_code']) && !empty($_GET['referral_code'])) {
          $ref = icchaDcrypt($_GET['referral_code']);
          $this->session->set_userdata('referral_code',$ref);
        }
        $this->load->library('form_validation');
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
            "includes/footer1",
            $this->data,
            true
        );
         $this->data["footer1"] = $this->load->view(
            "includes/footer2",
            $this->data,
            true
        );
        $this->data["js"] = $this->load->view("jsFile", $this->data, true);
    }
    public function index() {
      $requesturi = $_SERVER['REQUEST_URI'];
        $this->session->set_userdata('urlredirect',$requesturi);
        //echo $res;exit;
        $purl =  $this->uri->segment(2);
        $this->data['products'] = $products = $this->home_db->getProductviewpage($purl);
        //echo $this->db->last_query();exit();
        $this->data['getImages'] = $this->master_db->getRecords('product_images',['pid'=>$products[0]->pid],'*');
        $this->data['getSize'] = $this->master_db->sqlExecute('select ps.selling_price, ps.stock,s.sname,c.name as cname,c.co_id as cid,s.s_id as sid,c.ccode,ps.pro_size  as psid,ps.mrp from product_size ps left join sizes s on s.s_id = ps.sid left join colors c on c.co_id = ps.coid where ps.pid ='.$products[0]->pid.'');
        $this->data['related'] = $this->home_db->getRelatedprod($products[0]->pid,$products[0]->subcat_id);
        //echo $this->db->last_query();exit;
        $this->data['reviews'] = $this->master_db->sqlExecute('select r.review_descp,u.name,r.rating,r.created_at,r.pid,r.status from reviews r left join users u on u.u_id = r.user_id where r.pid='.$products[0]->pid.'');
        $this->load->view('product_view',$this->data);
    }
    public function rasapakaproview() {
        $requesturi = $_SERVER['REQUEST_URI'];
        $this->session->set_userdata('urlredirect',$requesturi);
        //echo $res;exit;
        $purl =  $this->uri->segment(2);
        $this->data['products'] = $products = $this->home_db->getProductviewpage($purl);
        //echo $this->db->last_query();exit();
        $this->data['getImages'] = $this->master_db->getRecords('product_images',['pid'=>$products[0]->pid],'*');
        $this->data['getSize'] = $this->master_db->sqlExecute('select ps.selling_price, ps.stock,s.sname,c.name as cname,c.co_id as cid,s.s_id as sid,c.ccode,ps.pro_size  as psid,ps.mrp from product_size ps left join sizes s on s.s_id = ps.sid left join colors c on c.co_id = ps.coid where ps.pid ='.$products[0]->pid.'');
        $this->data['related'] = $this->home_db->getRelatedprod($products[0]->pid,$products[0]->subcat_id);
        //echo $this->db->last_query();exit;
        $this->data['reviews'] = $this->master_db->sqlExecute('select r.review_descp,u.name,r.rating,r.created_at,r.pid,r.status from reviews r left join users u on u.u_id = r.user_id where r.pid='.$products[0]->pid.'');
        $this->load->view('rasapaka_view',$this->data);
    }
 }
?>
