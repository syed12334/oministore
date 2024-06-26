<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends CI_Controller {
    protected $data;
      public function __construct() {
        date_default_timezone_set("Asia/Kolkata");
        parent::__construct();
        $this->load->helper('utility_helper');
        $this->load->model('master_db');   
        $this->load->model('home_db'); 
        $this->data['detail']="";
        $this->data['session'] = ADMIN_SESSION;
         $detail = $this->session->userdata($this->data['session']);
        $this->data['id'] = $detail['id']; 
         $this->load->library("excel");
        if (!$this->session->userdata($this->data['session'])) {
            redirect('Login', 'refresh');
        }else{
                $sessionval = $this->session->userdata($this->data['session']);
                $details = $this->home_db->getlogin($sessionval['name']);
                if(count($details)){
                    $this->data['detail']=$details;
                }else{
                    $this->session->set_flashdata('message','<div class="alert alert-danger alert-dismissable"><button class="close" aria-hidden="true" data-dismiss="alert" type="button">&times;</button>Invalid Credentials.</div>');
                    redirect(base_url()."login/logout");
                }
        } 
  }
    public function exportproducts() {
        $query = "select p.id as pid,p.ptitle,p.pcode,p.overview,p.pspec,p.meta_title,p.meta_description,p.meta_keywords,p.tax,p.youtubelink,p.discount,p.length,p.weight,p.breadth,p.height,p.modalno,p.cqa,c.cname as catname,s.sname,ss.ssname,b.name as bname from products p left join category c on c.id =p.cat_id left join subcategory s on s.id = p.subcat_id left join subsubcategory ss on ss.id =p.sub_sub_id left join brand b on b.id= p.brand_id"; 
        $arr = $this->master_db->sqlExecute($query);
        //echo $this->db->last_query();exit;
        $count = 1;
        $table_columns = array("Sl No","Category Name","Subcategory Name","Subsubcategory Name","Brand Name","Product Title","Product Code","Product Description","Product Specification","Product Sizes","Product Price","Product Colors","Inventory","Length","Breadth","Weight","Height","Meta Title","Meta Description","Meta Keywords","Youtube Link","Discount","Tax","Modal Number","Customer Q&A");
        $count = 1;
        $object = new PHPExcel();
        $object->setActiveSheetIndex(0);
        $column = 0;
        foreach($table_columns as $field)
        {
           $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
           $column++;
        }
        $count_row = 2;
        foreach($arr as $r)
        { 
            $pid = $r->pid;
            $sizeQuery = "select s.sname as siname,co.name as coname, ps.selling_price as price,ps.stock from product_size ps left join sizes s on s.s_id = ps.sid left join colors co on co.co_id = ps.coid where ps.pid = ".$pid."";
            $getSize = $this->master_db->sqlExecute($sizeQuery);
            $sizename = [];$colorname =[];$price = [];$stock =[];
            if(count($getSize) >0) {
              foreach ($getSize as $key => $value) {
                  $sizename[] = $value->siname;
                  $colorname[] = $value->coname;
                  $price[] = $value->price;
                  $stock[] = $value->stock;
              }
            }
            $sizecomma = implode(",",$sizename);
            $colorcomma = implode(",",$colorname);
            $pricecomma = implode(",",$price);
            $stockcomma = implode(",",$stock);
           $object->getActiveSheet()->setCellValueByColumnAndRow(0, $count_row, strval($count++));
           $object->getActiveSheet()->setCellValueByColumnAndRow(1, $count_row, $r->catname);
           $object->getActiveSheet()->setCellValueByColumnAndRow(2, $count_row,$r->sname);
           $object->getActiveSheet()->setCellValueByColumnAndRow(3, $count_row, $r->ssname);
           $object->getActiveSheet()->setCellValueByColumnAndRow(4, $count_row, $r->bname);
           $object->getActiveSheet()->setCellValueByColumnAndRow(5, $count_row, $r->ptitle);
           $object->getActiveSheet()->setCellValueByColumnAndRow(6, $count_row, $r->pcode);
           $object->getActiveSheet()->setCellValueByColumnAndRow(7, $count_row, $r->overview);
           $object->getActiveSheet()->setCellValueByColumnAndRow(8, $count_row, $r->pspec);
           $object->getActiveSheet()->setCellValueByColumnAndRow(9, $count_row, $sizecomma);
           $object->getActiveSheet()->setCellValueByColumnAndRow(10, $count_row, $pricecomma);
           $object->getActiveSheet()->setCellValueByColumnAndRow(11, $count_row, $colorcomma);
           $object->getActiveSheet()->setCellValueByColumnAndRow(12, $count_row, $stockcomma);
           $object->getActiveSheet()->setCellValueByColumnAndRow(13, $count_row, $r->length);
           $object->getActiveSheet()->setCellValueByColumnAndRow(14, $count_row, $r->breadth);
           $object->getActiveSheet()->setCellValueByColumnAndRow(15, $count_row, $r->weight);
           $object->getActiveSheet()->setCellValueByColumnAndRow(16, $count_row, $r->height);
           $object->getActiveSheet()->setCellValueByColumnAndRow(17, $count_row, $r->meta_title);
           $object->getActiveSheet()->setCellValueByColumnAndRow(18, $count_row, $r->meta_description);
           $object->getActiveSheet()->setCellValueByColumnAndRow(19, $count_row, $r->meta_keywords);
           $object->getActiveSheet()->setCellValueByColumnAndRow(20, $count_row, "https://www.youtube.com/watch?v=".$r->youtubelink);
           $object->getActiveSheet()->setCellValueByColumnAndRow(21, $count_row, $r->discount);
           $object->getActiveSheet()->setCellValueByColumnAndRow(22, $count_row, $r->tax);
           $object->getActiveSheet()->setCellValueByColumnAndRow(23, $count_row, $r->modalno);
           $object->getActiveSheet()->setCellValueByColumnAndRow(24, $count_row, $r->cqa);
           $count_row++;
        }
      $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
      header('Content-Type: application/vnd.ms-excel');
      header('Content-Disposition: attachment;filename="products.xls"');
      header('Cache-Control: max-age=0');
      ob_end_clean();
      $object_writer->save('php://output');
    }
    public function generatePDF() {
      require_once APPPATH."vendor/autoload.php";
      $oid = $this->input->post('oid');
      $this->data['orders'] = $orders =  $this->master_db->getRecords('orders',['oid'=>$oid],'*');
      
     //echo $this->db->last_query();exit();
      
      if(count($orders) >0) {
        $getInvoiceorder = $this->master_db->getRecords('orders',['invoice_pdf !='=>'','invoice_status'=>1],'oid');

        $this->data['order_products'] = $this->master_db->sqlExecute('select p.modalno,op.qty,op.name,op.price,s.sname,op.pid,p.tax from order_products op left join products p on p.id = op.pid left join product_size ps on ps.pro_size = op.psid left join sizes s on s.s_id = ps.sid  where op.oid='.$oid.'');
         $this->data['order_productstax'] = $this->master_db->sqlExecute('select p.modalno,op.qty,op.name,sum(op.price) as price,s.sname,op.pid,p.tax from order_products op left join products p on p.id = op.pid left join product_size ps on ps.pro_size = op.psid left join sizes s on s.s_id = ps.sid  where op.oid='.$oid.' and p.tax =5');
         $this->data['order_productstax1'] = $this->master_db->sqlExecute('select p.modalno,op.qty,op.name,sum(op.price) as price,s.sname,op.pid,p.tax from order_products op left join products p on p.id = op.pid left join product_size ps on ps.pro_size = op.psid left join sizes s on s.s_id = ps.sid  where op.oid='.$oid.' and p.tax =18');
        // echo $this->db->last_query();exit;
         $this->data['orderid'] = $this->home_db->generateOrderNo($oid);
         if(is_array($orders) && !empty($orders[0]) && $orders[0]->invoice_status =1 && $orders[0]->invoice_pdf !="") {
            $this->data['invoiceid'] = $orders[0]->invoice_id;
         }else {
            $invoiceid = "000".count($getInvoiceorder) +1;
            $this->data['invoiceid'] = $invoiceid ;
            $up['invoice_id'] = $invoiceid;
            $this->master_db->updateRecord('orders',$up,['oid'=>$oid]);
         }
         
      //  echo $orders[0]->totalamount;exit;
       // echo $this->db->last_query();exit;
        $this->data['bills'] = $this->master_db->sqlExecute('select ob.bfname,ob.bemail,ob.bphone,ob.bpincode,a.areaname,s.name as sname,c.cname,ob.baddress,ob.sfname,ob.semail,ob.sphone,ob.spincode from order_bills ob left join states s on s.id=ob.bstate left join cities c on c.id = ob.bcity left join area a on a.id = ob.barea where ob.oid='.$oid.'');
        $this->data['shipping'] = $this->master_db->sqlExecute('select a.areaname,s.name as sname,c.cname,ob.sfname,ob.semail,ob.sphone,ob.spincode,ob.saddress from order_bills ob left join states s on s.id=ob.sstate left join cities c on c.id = ob.scity left join area a on a.id = ob.sarea where ob.oid='.$oid.'');
        $this->data['qrcode'] = $this->master_db->sqlExecute('select * from qrcodeimg where id !=-1 order by id desc');
       // echo $this->db->last_query();exit;
          $buff = $this->load->view('createInvoice',$this->data,true); 
         // echo $buff;exit;
          $base_path = "../assets/invoicepdf/".$orders[0]->orderid.".pdf";
          $path = "assets/invoicepdf/".$orders[0]->orderid.".pdf";
          $this->master_db->updateRecord('orders',['invoice_pdf'=>$path,'invoice_status'=>1],['oid'=>$orders[0]->oid]);
          $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
          $mpdf->WriteHTML($buff);
          $mpdf->Output($base_path, "F");
          echo json_encode(['status'=>true,'msg'=>'PDF Generated Successfull']);
      }
    }
    public function guestgeneratePDF() {
      require_once APPPATH."vendor/autoload.php";
      $oid = $this->input->post('oid');
      $this->data['orders'] = $orders =  $this->master_db->getRecords('guest_orders',['oid'=>$oid],'*');
      
     //echo $this->db->last_query();exit();
      
      if(count($orders) >0) {
        $this->data['order_products'] = $this->master_db->sqlExecute('select p.modalno,op.qty,op.name,op.price,s.sname,op.pid,p.tax from guest_order_products op left join products p on p.id = op.pid left join product_size ps on ps.pro_size = op.psid left join sizes s on s.s_id = ps.sid  where op.oid='.$oid.'');
         $this->data['order_productstax'] = $this->master_db->sqlExecute('select p.modalno,op.qty,op.name,op.price,s.sname,op.pid,p.tax from guest_order_products op left join products p on p.id = op.pid left join product_size ps on ps.pro_size = op.psid left join sizes s on s.s_id = ps.sid  where op.oid='.$oid.' group by p.tax');
         $this->data['orderid'] = $this->home_db->generateGuestNo($oid);
      //  echo $orders[0]->totalamount;exit;
       // echo $this->db->last_query();exit;
        $this->data['bills'] = $this->master_db->sqlExecute('select ob.bfname,ob.bemail,ob.bphone,ob.bpincode,a.areaname,s.name as sname,c.cname,ob.baddress,ob.sfname,ob.semail,ob.sphone,ob.spincode from guest_order_bills ob left join states s on s.id=ob.bstate left join cities c on c.id = ob.bcity left join area a on a.id = ob.barea where ob.oid='.$oid.'');
        $this->data['shipping'] = $this->master_db->sqlExecute('select a.areaname,s.name as sname,c.cname,ob.sfname,ob.semail,ob.sphone,ob.spincode,ob.saddress from guest_order_bills ob left join states s on s.id=ob.sstate left join cities c on c.id = ob.scity left join area a on a.id = ob.sarea where ob.oid='.$oid.'');
       // echo $this->db->last_query();exit;
          $buff = $this->load->view('createInvoice',$this->data,true); 
          //echo $buff;exit;
          $base_path = "../assets/invoiceguestpdf/".$orders[0]->orderid.".pdf";
          $path = "assets/invoiceguestpdf/".$orders[0]->orderid.".pdf";
          $this->master_db->updateRecord('guest_orders',['invoice_pdf'=>$path,'invoice_status'=>1],['oid'=>$orders[0]->oid]);
          $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
          $mpdf->WriteHTML($buff);
          $mpdf->Output($base_path, "F");
          echo json_encode(['status'=>true,'msg'=>'PDF Generated Successfull']);
      }
    }
    public function sendMailtouser() {
      $this->load->library('Mail');
      $oid = $this->input->post('oid');
      $orders = $this->master_db->getRecords('orders',['oid'=>$oid],'*');
      $bills = $this->master_db->getRecords('order_bills',['oid'=>$oid],'*');
     // echo $this->db->last_query();exit;
      if(!empty($orders[0]->invoice_pdf)) {
        $newdata =  "../".$orders[0]->invoice_pdf;
        $invoice = "Invoice";
        $html = $this->load->view('sendmail',$this->data,true);
        $this->mail->send_sparkpost_attach($html,[$bills[0]->bemail],'Customer Invoice',$newdata,$invoice);
        $arr = ['status'=>true];
      }else {
        $arr =['status'=>false];
      }
      echo json_encode($arr);
    }
public function sendMailtouserguest() {
      $this->load->library('Mail');
      $oid = $this->input->post('oid');
      $orders = $this->master_db->getRecords('guest_orders',['oid'=>$oid],'*');
      $bills = $this->master_db->getRecords('guest_order_bills',['oid'=>$oid],'*');
      if(!empty($orders[0]->invoice_pdf)) {
        $newdata =  "../".$orders[0]->invoice_pdf;
        $invoice = "Invoice";
        $html = $this->load->view('sendmail',$this->data,true);
        $this->mail->send_sparkpost_attach($html,[$bills[0]->bemail],'Customer Invoice',$newdata,$invoice);
        $arr = ['status'=>true];
      }else {
        $arr =['status'=>false];
      }
      echo json_encode($arr);
    }

      public function exportorders() {
        //echo "<pre>";print_r($_GET);exit;
        $ostatus = $this->input->get('ostatus');
        $pstatus = $this->input->get('pstatus');
        $paymode = $this->input->get('paymode');
        $fdate = $this->input->get('fdate');
        $tdate = $this->input->get('tdate');
        $filters ="";
        if(!empty($ostatus) && $ostatus !="") {
          $filters .=" and o.status =".$ostatus." ";
        }
        if(!empty($pstatus) && $pstatus !="") {
          $filters .=" and p.status =".$pstatus." ";
        }
        if(!empty($paymode) && $paymode !="") {
          $filters .=" and o.pmode =".$paymode." ";
        }
        if(!empty($fdate) && $fdate !="" && !empty($tdate) && $tdate !="") {
          $filters .=" and o.order_date between '".$fdate."' and '".$tdate."' ";
        }
        $query = "select o.oid as id,o.orderid,o.order_date,o.totalamount,u.name as uname,o.pmode,o.status,o.invoice_status,o.invoice_pdf,p.pay_id,p.pstatus,o.comments,ob.bfname,ob.bemail,ob.bphone,ob.bpincode,ob.baddress,ob.sfname,ob.semail,ob.sphone,ob.spincode,ob.saddress,s.name as state_name,c.cname as city_name,a.areaname from orders o left join order_bills ob on ob.oid =o.oid left join states s on s.id = ob.bstate left join cities c on c.id = ob.bcity left join area a on a.id = ob.barea left join users u on u.u_id = o.user_id left join payment_log p on p.oid = o.oid  where p.status !=6 ".$filters." group by o.orderid order by o.oid desc"; 
        $arr = $this->master_db->sqlExecute($query);
      //  echo $this->db->last_query();exit;
        $count = 1;
        $table_columns = array("Sl No","Order ID","Order Date","Order Total Amount","Ordered By","Payment ID","Product Status","Payment Mode","Billing Name","Billing Email","Billing Phone","Billing State","Billing City","Billing Area","Billing Address","Billing Pincode","Shipping Name","Shipping Email","Shipping Phone","Shipping State","Shipping City","Shipping Area","Shipping Address","Shipping Pincode");
        $count = 1;
        $object = new PHPExcel();
        $object->setActiveSheetIndex(0);
        $column = 0;
        foreach($table_columns as $field)
        {
           $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
           $column++;
        }
        $count_row = 2;
        foreach($arr as $r)
        { 
          $oid = $r->id;
$paystatus = "";
          if($r->pmode ==1) {
                $paystatus .="Online";
            }else if($r->pmode ==2) {
                $paystatus .="COD";
            }
          $getShippingorder = $this->master_db->sqlExecute('select ob.sfname,ob.semail,ob.sphone,ob.spincode,ob.saddress,s.name as state_name,c.cname as city_name,a.areaname from order_bills ob left join states s on s.id = ob.sstate left join cities c on c.id = ob.scity left join area a on a.id = ob.sarea where oid='.$oid.' and s.name !=null');
         
           $object->getActiveSheet()->setCellValueByColumnAndRow(0, $count_row, strval($count++));
           $object->getActiveSheet()->setCellValueByColumnAndRow(1, $count_row, $r->orderid);
           $object->getActiveSheet()->setCellValueByColumnAndRow(2, $count_row,date('d M y', strtotime($r->order_date)));
           $object->getActiveSheet()->setCellValueByColumnAndRow(3, $count_row, $r->totalamount);
           $object->getActiveSheet()->setCellValueByColumnAndRow(4, $count_row,$r->uname);
           $object->getActiveSheet()->setCellValueByColumnAndRow(5, $count_row, $r->pay_id);
           $object->getActiveSheet()->setCellValueByColumnAndRow(6, $count_row, $r->pstatus);
           $object->getActiveSheet()->setCellValueByColumnAndRow(7, $count_row, $paystatus);
           $object->getActiveSheet()->setCellValueByColumnAndRow(8, $count_row, $r->bfname);
           $object->getActiveSheet()->setCellValueByColumnAndRow(9, $count_row, $r->bemail);
           $object->getActiveSheet()->setCellValueByColumnAndRow(10, $count_row, $r->bphone);
           $object->getActiveSheet()->setCellValueByColumnAndRow(11, $count_row, $r->state_name);
           $object->getActiveSheet()->setCellValueByColumnAndRow(12, $count_row, $r->city_name);
           $object->getActiveSheet()->setCellValueByColumnAndRow(13, $count_row, $r->areaname);
           $object->getActiveSheet()->setCellValueByColumnAndRow(14, $count_row, $r->baddress);
           $object->getActiveSheet()->setCellValueByColumnAndRow(15, $count_row, $r->bpincode);
           if(!empty($getShippingorder[0]->sfname) && $getShippingorder[0]->sfname !=NULL && !empty($getShippingorder[0]->semail) && $getShippingorder[0]->semail !=NULL){
              $object->getActiveSheet()->setCellValueByColumnAndRow(16, $count_row, $getShippingorder[0]->sfname);
               $object->getActiveSheet()->setCellValueByColumnAndRow(17, $count_row, $getShippingorder[0]->semail);
               $object->getActiveSheet()->setCellValueByColumnAndRow(18, $count_row,$getShippingorder[0]->sphone);
               $object->getActiveSheet()->setCellValueByColumnAndRow(19, $count_row,$getShippingorder[0]->state_name);
               $object->getActiveSheet()->setCellValueByColumnAndRow(20, $count_row, $getShippingorder[0]->city_name);
               $object->getActiveSheet()->setCellValueByColumnAndRow(21, $count_row, $getShippingorder[0]->areaname);
               $object->getActiveSheet()->setCellValueByColumnAndRow(22, $count_row, $getShippingorder[0]->saddress);
               $object->getActiveSheet()->setCellValueByColumnAndRow(23, $count_row, $getShippingorder[0]->spincode);
           }else {
               $object->getActiveSheet()->setCellValueByColumnAndRow(16, $count_row, $r->bfname);
               $object->getActiveSheet()->setCellValueByColumnAndRow(17, $count_row, $r->bemail);
               $object->getActiveSheet()->setCellValueByColumnAndRow(18, $count_row, $r->bphone);
               $object->getActiveSheet()->setCellValueByColumnAndRow(19, $count_row, $r->state_name);
               $object->getActiveSheet()->setCellValueByColumnAndRow(20, $count_row, $r->city_name);
               $object->getActiveSheet()->setCellValueByColumnAndRow(21, $count_row, $r->areaname);
               $object->getActiveSheet()->setCellValueByColumnAndRow(22, $count_row, $r->baddress);
               $object->getActiveSheet()->setCellValueByColumnAndRow(23, $count_row, $r->bpincode);
           }
           
      
           $count_row++;
        }
      $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
      header('Content-Type: application/vnd.ms-excel');
      header('Content-Disposition: attachment;filename="orders.xls"');
      header('Cache-Control: max-age=0');
      ob_end_clean();
      $object_writer->save('php://output');
    }

    public function exportfailureorders() {
        $query = "select o.oid as id,o.orderid,o.order_date,o.totalamount,u.name as uname,o.pmode,o.status,o.invoice_status,o.invoice_pdf,p.pay_id,p.pstatus,o.comments,ob.bfname,ob.bemail,ob.bphone,ob.bpincode,ob.baddress,ob.sfname,ob.semail,ob.sphone,ob.spincode,ob.saddress,s.name as state_name,c.cname as city_name,a.areaname from orders o left join order_bills ob on ob.oid =o.oid left join states s on s.id = ob.bstate left join cities c on c.id = ob.bcity left join area a on a.id = ob.barea left join users u on u.u_id = o.user_id left join payment_log p on p.oid = o.oid  where p.status=-1 group by o.orderid order by o.oid desc"; 
        $arr = $this->master_db->sqlExecute($query);
       // echo $this->db->last_query();exit;
        $count = 1;
        $table_columns = array("Sl No","Order ID","Order Date","Order Total Amount","Ordered By","Payment ID","Product Status","Billing Name","Billing Email","Billing Phone","Billing State","Billing City","Billing Area","Billing Address","Billing Pincode","Shipping Name","Shipping Email","Shipping Phone","Shipping State","Shipping City","Shipping Area","Shipping Address","Shipping Pincode");
        $count = 1;
        $object = new PHPExcel();
        $object->setActiveSheetIndex(0);
        $column = 0;
        foreach($table_columns as $field)
        {
           $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
           $column++;
        }
        $count_row = 2;
        foreach($arr as $r)
        { 
          $oid = $r->id;
          $getShippingorder = $this->master_db->sqlExecute('select ob.sfname,ob.semail,ob.sphone,ob.spincode,ob.saddress,s.name as state_name,c.cname as city_name,a.areaname from order_bills ob left join states s on s.id = ob.sstate left join cities c on c.id = ob.scity left join area a on a.id = ob.sarea where oid='.$oid.' and s.name !=null');
         
           $object->getActiveSheet()->setCellValueByColumnAndRow(0, $count_row, strval($count++));
           $object->getActiveSheet()->setCellValueByColumnAndRow(1, $count_row, $r->orderid);
           $object->getActiveSheet()->setCellValueByColumnAndRow(2, $count_row,date('d M y', strtotime($r->order_date)));
           $object->getActiveSheet()->setCellValueByColumnAndRow(3, $count_row, $r->totalamount);
           $object->getActiveSheet()->setCellValueByColumnAndRow(4, $count_row,$r->uname);
           $object->getActiveSheet()->setCellValueByColumnAndRow(5, $count_row, $r->pay_id);
           $object->getActiveSheet()->setCellValueByColumnAndRow(6, $count_row, $r->pstatus);
           $object->getActiveSheet()->setCellValueByColumnAndRow(7, $count_row, $r->bfname);
           $object->getActiveSheet()->setCellValueByColumnAndRow(8, $count_row, $r->bemail);
           $object->getActiveSheet()->setCellValueByColumnAndRow(9, $count_row, $r->bphone);
           $object->getActiveSheet()->setCellValueByColumnAndRow(10, $count_row, $r->state_name);
           $object->getActiveSheet()->setCellValueByColumnAndRow(11, $count_row, $r->city_name);
           $object->getActiveSheet()->setCellValueByColumnAndRow(12, $count_row, $r->areaname);
           $object->getActiveSheet()->setCellValueByColumnAndRow(13, $count_row, $r->baddress);
           $object->getActiveSheet()->setCellValueByColumnAndRow(14, $count_row, $r->bpincode);
           if(!empty($getShippingorder[0]->sfname) && $getShippingorder[0]->sfname !=NULL && !empty($getShippingorder[0]->semail) && $getShippingorder[0]->semail !=NULL){
              $object->getActiveSheet()->setCellValueByColumnAndRow(15, $count_row, $getShippingorder[0]->sfname);
               $object->getActiveSheet()->setCellValueByColumnAndRow(16, $count_row, $getShippingorder[0]->semail);
               $object->getActiveSheet()->setCellValueByColumnAndRow(17, $count_row,$getShippingorder[0]->sphone);
               $object->getActiveSheet()->setCellValueByColumnAndRow(18, $count_row,$getShippingorder[0]->state_name);
               $object->getActiveSheet()->setCellValueByColumnAndRow(19, $count_row, $getShippingorder[0]->city_name);
               $object->getActiveSheet()->setCellValueByColumnAndRow(20, $count_row, $getShippingorder[0]->areaname);
               $object->getActiveSheet()->setCellValueByColumnAndRow(21, $count_row, $getShippingorder[0]->saddress);
               $object->getActiveSheet()->setCellValueByColumnAndRow(22, $count_row, $getShippingorder[0]->spincode);
           }else {
               $object->getActiveSheet()->setCellValueByColumnAndRow(15, $count_row, $r->bfname);
               $object->getActiveSheet()->setCellValueByColumnAndRow(16, $count_row, $r->bemail);
               $object->getActiveSheet()->setCellValueByColumnAndRow(17, $count_row, $r->bphone);
               $object->getActiveSheet()->setCellValueByColumnAndRow(18, $count_row, $r->state_name);
               $object->getActiveSheet()->setCellValueByColumnAndRow(19, $count_row, $r->city_name);
               $object->getActiveSheet()->setCellValueByColumnAndRow(20, $count_row, $r->areaname);
               $object->getActiveSheet()->setCellValueByColumnAndRow(21, $count_row, $r->baddress);
               $object->getActiveSheet()->setCellValueByColumnAndRow(22, $count_row, $r->bpincode);
           }
           
      
           $count_row++;
        }
      $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
      header('Content-Type: application/vnd.ms-excel');
      header('Content-Disposition: attachment;filename="orders.xls"');
      header('Cache-Control: max-age=0');
      ob_end_clean();
      $object_writer->save('php://output');
    }

}
