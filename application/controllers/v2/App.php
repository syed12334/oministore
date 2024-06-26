<?php 
if(!defined('BASEPATH')) exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST,DELETE,UPDATE");
header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
header('Content-type: application/json; charset=UTF-8');
class App extends CI_Controller { 
	public function __construct() {
		parent::__construct();	
			$this->load->helper('utility_helper');
			$this->load->model('home_db');
			$this->load->model('master_db');
			$this->load->model('cart_db');
			date_default_timezone_set('Asia/Kolkata');
	}
		public function deliverylogin() {
			 $bods = file_get_contents('php://input');
			$datas = json_decode($bods, true);
			$phone = @$datas['phone'];
			$fcm_id = @$datas['fcm_id'];
		  if(!empty($phone)) {
		 	if(is_numeric($phone) && preg_match('/^[0-9]{10}+$/', $phone)) {
		 		$this->load->library('SMS');
			$getFranchise = $this->master_db->getRecords('franchises',['contact_no'=>$phone,'status !='=>2],'franchise_id,franchise_name,name_of_person as person_name,contact_no as franchise_contact_no,whatsapp_no as franchise_whatsapp_no,address as franchise_address');
		 		if(count($getFranchise) >0) {
		 			$otp =rand(1234,9999);
		 			if(!empty($fcm_id) && $fcm_id !="") {
		 				$update = $this->master_db->updateRecord('franchises',['fcm_id'=>$fcm_id],['franchise_id'=>$getFranchise[0]->franchise_id]);
		 			}
		 			if($phone ==9999999999) {
		 			}else {
		 				$message = "Dear User, Your OTP for www.swarnagowri.com login verification is {#var#}. Regards, Team SWARNAGOWRI.";
		            	$message = str_replace('{#var#}',$otp,$message);
		            	$this->sms->sendSmsToUser($message,$phone);
		            	  $update = $this->master_db->updateRecord('franchises',['otp'=>$otp],['franchise_id'=>$getFranchise[0]->franchise_id]);
		 			}
		            $data['franchise_id'] = $getFranchise[0]->franchise_id;
		            if(!empty($getFranchise[0]->franchise_name) && $getFranchise[0]->franchise_name !=null) {
		            	$data['franchise_name'] = $getFranchise[0]->franchise_name;
		            }else {
		            	$data['franchise_name'] = "";
		            }
		             if(!empty($getFranchise[0]->name_of_person) && $getFranchise[0]->name_of_person !=null) {
		            	$data['person_name'] = $getFranchise[0]->name_of_person;
		            }else {
		            	$data['person_name'] = "";
		            }
		             if(!empty($getFranchise[0]->contact_no) && $getFranchise[0]->contact_no !=null) {
		            	$data['franchise_contact_no'] = $getFranchise[0]->contact_no;
		            }else {
		            	$data['franchise_contact_no'] = "";
		            }
		             if(!empty($getFranchise[0]->whatsapp_no) && $getFranchise[0]->whatsapp_no !=null) {
		            	$data['franchise_whatsapp_no'] = $getFranchise[0]->franchise_name;
		            }else {
		            	$data['franchise_whatsapp_no'] = "";
		            }
		             if(!empty($getFranchise[0]->address) && $getFranchise[0]->address !=null) {
		            	$data['franchise_address'] = $getFranchise[0]->address;
		            }else {
		            	$data['franchise_address'] = "";
		            }
		          
		            $result = array('status'=>'success','msg'=>'OTP sent successfully','data'=>$data);
		 		}else {
		 			$result = array('status'=>'failure','msg'=>'Franchise not exists try another');
		 		}
		 	}else {
		 		$result = array('status'=>'failure','msg'=>'Please enter 10 digit mobile number');
		 	}
		 }else {
		 	$result = array('status'=>'failure','msg'=>'Required fields are missing.');
		 }
		 echo json_encode($result);
		}
		public function deliveryverifyotp() {
			$result = array('status'=>'failure','msg'=>'Required fields are missing.');
		 	$bod = file_get_contents('php://input');
			$data = json_decode($bod, true);
			$user_id = @$data['user_id'];
			$otp = @$data['otp'];
		 if(!empty($user_id) && !empty($otp)) {
		 	$getFranchise = $this->master_db->getRecords('franchises',['franchise_id '=>$user_id],'franchise_id,franchise_name,name_of_person as person_name,contact_no as franchise_contact_no,whatsapp_no as franchise_whatsapp_no,address as franchise_address');
		 	if(count($getFranchise) >0) {
		 		$getotp = $this->master_db->getRecords('franchises',['otp'=>$otp],'*');
		 		if(count($getotp) >0) {
		 			$data['franchise_id'] = $getFranchise[0]->franchise_id;
		 			 if(!empty($getFranchise[0]->franchise_name) && $getFranchise[0]->franchise_name !=null) {
		            	$data['franchise_name'] = $getFranchise[0]->franchise_name;
		            }else {
		            	$data['franchise_name'] = "";
		            }
		             if(!empty($getFranchise[0]->name_of_person) && $getFranchise[0]->name_of_person !=null) {
		            	$data['person_name'] = $getFranchise[0]->name_of_person;
		            }else {
		            	$data['person_name'] = "";
		            }
		             if(!empty($getFranchise[0]->contact_no) && $getFranchise[0]->contact_no !=null) {
		            	$data['franchise_contact_no'] = $getFranchise[0]->contact_no;
		            }else {
		            	$data['franchise_contact_no'] = "";
		            }
		             if(!empty($getFranchise[0]->whatsapp_no) && $getFranchise[0]->whatsapp_no !=null) {
		            	$data['franchise_whatsapp_no'] = $getFranchise[0]->franchise_name;
		            }else {
		            	$data['franchise_whatsapp_no'] = "";
		            }
		             if(!empty($getFranchise[0]->address) && $getFranchise[0]->address !=null) {
		            	$data['franchise_address'] = $getFranchise[0]->address;
		            }else {
		            	$data['franchise_address'] = "";
		            }
		 			$result = array('status'=>'success','msg'=>'Your otp verified successfully','data'=>$data);
		 		}else {
		 			$result = array('status'=>'failure','msg'=>"OTP Not matching try another");
		 		}
		 	}else {
		 		$result = array('status'=>'failure','msg'=>'User id not exists try another');
		 	}
		 }
		 echo json_encode($result);
		}
		public function deliverytodayorders() {
			$result = array('status'=>'failure','msg'=>'Required fields are missing.');
		 	$bod = file_get_contents('php://input');
			$data = json_decode($bod, true);
			$user_id = @$data['user_id'];
			$condition ="";
			if(!empty($user_id)) {
				$getFranchise = $this->master_db->getRecords('franchises',['franchise_id '=>$user_id,'status'=>0],'*');
				$getPincodes = $this->master_db->sqlExecute('select af.pincode_id,af.franchise_id,af.oid from assign_franchise  af left join franchises_pincodes fp on fp.franchise_id = af.franchise_id where af.franchise_id ='.$user_id.' group by af.oid order by af.id desc');
				$pins=[];$oid =[];$franchise_id =[];
				if(is_array($getPincodes) && !empty($getPincodes[0]) ) {
					foreach ($getPincodes as $key => $value) {
						$pins[] = $value->pincode_id;
						$oid[] = $value->oid;
					}
				}
				$condition ="";
				if(is_array($getPincodes) && !empty($getPincodes[0])) {
					foreach ($getPincodes as $key => $value) {
						$pins[] = $value->pincode_id;
						$oid[] = $value->oid;
						$franchise_id[] = $value->franchise_id;
					}
					if(is_array($pins) && !empty($pins[0]) && is_array($oid) && !empty($oid[0]) && is_array($franchise_id) && !empty($franchise_id[0])) {
						$pinids = implode(",", $pins);
						$oids = implode(",", $oid);
						$franchise_id = implode(",", $franchise_id);
						$condition .=" and af.pincode_id in (".$pinids.") and af.oid in (".$oids.") and af.franchise_id in (".$franchise_id.")";
					}
					if(count($getFranchise) >0) {
					$getOrders = $this->master_db->sqlExecute('select o.oid as id,o.orderid,DATE_FORMAT(o.order_date,"%d-%m-%Y") as ordered_date,o.totalamount as total_amount,u.name as customer_name,o.pmode,o.status,p.pay_id from orders o left join users u on u.u_id = o.user_id left join payment_log p on p.oid = o.oid left join assign_franchise af on af.oid=o.oid where o.status not in (-1,4,1,3) and  af.assigned_date="'.date('Y-m-d').'" '.$condition.' group by o.orderid order by o.oid desc');
					//echo $this->db->last_query();exit;
					if(count($getOrders) >0) {
						foreach ($getOrders as $key => $value) {
							$oid = $value->id;
							if(!empty($value->uname) && $value->uname !=NULL){
								$value->uname =$value->uname;
							}else {
								$value->uname = "";
							}
							$getItems = $this->master_db->sqlExecute('select ob.name,ob.pcode,ob.image,ob.qty,ob.stock,ob.price,s.sname from order_products ob left join product_size ps on ps.pro_size = ob.psid left join sizes s on s.s_id = ps.sid where ob.oid='.$oid.'');
							if ($r->status == 1) {
				                $r->status = "In Progress";
				            } else if ($r->status == 2){
				                $r->status = "Ready to Ship";
				            }
				            else if ($r->status == 3){
				                $r->status = "Delivered";
				            }
				            else if ($r->status == 4){
				                $r->status = "Order Cancelled";
				            }
				            else if ($r->status == 5){
				                $r->status = "Order Confirmed";
				            }
				            else if ($r->status == -1){
				                $r->status = "Order Failed";
				            }
				            if($r->pmode ==1) {
				                $r->pmode="Online";
				            }else if($r->pmode ==2) {
				                $r->pmode="COD";
				            }
						}
						$result = array('status'=>'success','data'=>$getOrders);
					}else {
						$result = array('status'=>'failure','msg'=>'Orders not found');
					}

				}else {
					$result = array('status'=>'failure','msg'=>'User id not exists try another');
				}
			  }else {
			  	$result = array('status'=>'failure','msg'=>'No data found');
			  }
			}
		   echo json_encode($result);
		}
		public function deliverypendingorders() {
			$result = array('status'=>'failure','msg'=>'Required fields are missing.');
		 	$bod = file_get_contents('php://input');
			$data = json_decode($bod, true);
			$user_id = @$data['user_id'];
			if(!empty($user_id)) {
				$condition ="";
				$getFranchise = $this->master_db->getRecords('franchises',['franchise_id '=>$user_id,'status'=>0],'*');
					$getPincodes = $this->master_db->sqlExecute('select af.pincode_id,af.franchise_id,af.oid from franchises_pincodes fp left join assign_franchise af on af.franchise_id = fp.franchise_id where af.franchise_id ='.$user_id.' group by af.pincode_id');
				$pins=[];$oid =[];$franchise_id =[];
				if(is_array($getPincodes) && !empty($getPincodes[0]) ) {
					foreach ($getPincodes as $key => $value) {
						$pins[] = $value->pincode_id;
						$oid[] = $value->oid;
						$franchise_id[] = $value->franchise_id;
					}
					if(is_array($pins) && !empty($pins[0]) && is_array($oid) && !empty($oid[0]) && is_array($franchise_id) && !empty($franchise_id[0])) {
						$pinids = implode(",", $pins);
						$oids = implode(",", $oid);
						$franchise_id = implode(",", $franchise_id);
						$condition .=" and af.pincode_id in (".$pinids.") and af.oid in (".$oids.") and af.franchise_id in (".$franchise_id.")";
					}
					if(count($getFranchise) >0) {
						$getOrders = $this->master_db->sqlExecute('select o.oid as id,o.orderid,DATE_FORMAT(o.order_date,"%d-%m-%Y") as ordered_date,o.totalamount as total_amount,u.name as customer_name,o.pmode,o.status,p.pay_id from orders o left join users u on u.u_id = o.user_id left join payment_log p on p.oid = o.oid left join assign_franchise af on af.oid=o.oid where af.assigned_date <="'.date('Y-m-d').'" and  o.status in (5,2) '.$condition.' group by o.orderid order by o.oid desc');
						//echo $this->db->last_query();exit;
						if(count($getOrders) >0) {
							foreach ($getOrders as $key => $value) {
								$oid = $value->id;
								$getItems = $this->master_db->sqlExecute('select ob.name,ob.pcode,ob.image,ob.qty,ob.stock,ob.price,s.sname from order_products ob left join product_size ps on ps.pro_size = ob.psid left join sizes s on s.s_id = ps.sid where ob.oid='.$oid.'');
								if(!empty($value->uname) && $value->uname !=NULL){
									$value->uname =$value->uname;
								}else {
									$value->uname = "";
								}
								if ($r->status == 1) {
					                $r->status = "In Progress";
					            } else if ($r->status == 2){
					                $r->status = "Ready to Ship";
					            }
					            else if ($r->status == 3){
					                $r->status = "Delivered";
					            }
					            else if ($r->status == 4){
					                $r->status = "Order Cancelled";
					            }
					            else if ($r->status == 5){
					                $r->status = "Order Confirmed";
					            }
					            else if ($r->status == -1){
					                $r->status = "Order Failed";
					            }
					            if($r->pmode ==1) {
					                $r->pmode="Online";
					            }else if($r->pmode ==2) {
					                $r->pmode="COD";
					            }
							}
							$result = array('status'=>'success','data'=>$getOrders);
						}else {
							$result = array('status'=>'failure','msg'=>'Orders not found');
						}

					}else {
						$result = array('status'=>'failure','msg'=>'User id not exists try another');
					}
				}else {
					$result = array('status'=>'failure','msg'=>'No data found');
				}
				
			}
			echo json_encode($result);
		}
		public function deliverychangeorderstatus() {
		  $result = array('status'=>'failure','msg'=>'Required fields are missing.');
			$orders = file_get_contents('php://input');
			$order = json_decode($orders, true);
			$user_id = @$order['user_id'];
			$order_id = @$order['order_id'];
			$status = @$order['status'];
			$otp = @$order['otp'];
			if(!empty($user_id) && !empty($order_id) && !empty($status) && !empty($otp)) {
				$getFranchise = $this->master_db->getRecords('franchises',['franchise_id'=>$user_id,'status !='=>2],'*');
				if(count($getFranchise) >0) {
					$getOrders = $this->master_db->getRecords('orders',['oid'=>$order_id],'user_id');
					if(count($getOrders) >0) {
						$getOtp = $this->master_db->getRecords('users',['u_id'=>$getOrders[0]->user_id,'otp'=>$otp],'*');
						if(count($getOtp) >0) {
							$update['delivered_date'] = date('Y-m-d');
							$update['status'] = $status;
							$this->master_db->updateRecord('orders',$update,['oid'=>$order_id]);
							$result = array('status'=>'success','msg'=>'Order status updated successfully');
						}else {
							$result = array('status'=>'failure','msg'=>'Otp not exists try another');
						}
					}else {
						$result = array('status'=>'failure','msg'=>'Order id not exists try another');
					}
				}else {
					$result = array('status'=>'failure','msg'=>'User id not exists try another');
				}
			}
			echo json_encode($result);
		}
		public function deliveryOrderdetails() {
		  $orders = file_get_contents('php://input');
		  $order = json_decode($orders, true);
		  $order_id = @$order['order_id'];
		  if(!empty($order_id)) {
		  		$getOrders = $this->master_db->sqlExecute('select o.oid, o.orderid,o.totalamount,o.pmode,DATE_FORMAT(o.order_date,"%d-%m-%Y") as orderdate,ob.bfname as customer_name,ob.bemail as customer_email,ob.bphone as customer_phone,ob.baddress as customer_address,o.status as order_status from orders o left join order_bills ob on ob.oid = o.oid where o.oid ='.$order_id.'');
		  		//echo $this->db->last_query();exit;
		  		if(count($getOrders) >0) {
		  			$oid = $getOrders[0]->oid;
					$getItems = $this->master_db->sqlExecute('select ob.name,ob.price,ob.qty,ob.image,s.sname from order_products ob left join product_size ps on ps.pro_size = ob.psid left join sizes s on s.s_id = ps.sid where ob.oid='.$oid.'');
					$getOrders[0]->product_items = $getItems;
						$result['status'] = "success";
						$result['data'] = $getOrders;
		  		}else {
		  			$result['status'] = "failure";
		  			$result['msg'] = "Order id not exists try another";
		  		}
		   }else {
		   		$result = array('status'=>'failure','msg'=>'Required fields are missing.');
		   }
		   echo json_encode($result);
		}
		public function deliveryprofile() {
		  $orders = file_get_contents('php://input');
		  $order = json_decode($orders, true);
		  $user_id = @$order['user_id'];
		  if(!empty($user_id)) {
		  	$getFranchise = $this->master_db->getRecords('franchises',['franchise_id'=>$user_id],'*');
		  	if(count($getFranchise) >0) {
		  		$arr['name'] = $getFranchise[0]->name_of_person;
		  		$arr['franchise_name'] = $getFranchise[0]->franchise_name;
		  		$arr['phone'] = $getFranchise[0]->contact_no;
		  		$arr['whatsappno'] = $getFranchise[0]->whatsapp_no;
		  		$arr['address'] = $getFranchise[0]->address;
		  		$arr['area'] = $getFranchise[0]->area;
		  		$arr['subarea'] = $getFranchise[0]->subarea;
		  		$arr['street'] = $getFranchise[0]->street;
		  		$arr['gated_community'] = $getFranchise[0]->gated_community;
		  		$result['status'] = "success";
		  		$result['data'] = $arr;
		  	}else {
		  		$result['status'] ="failure";
		  		$result['msg'] ="User id not exists try another";
		  	}
		  }else {
		  	$result['status'] ="failure";
		  	$result['msg'] ="Required fields missing";
		  }
		  echo json_encode($result);
		}
		public function deliveredorder() {
			$result = array('status'=>'failure','msg'=>'Required fields are missing.');
		 	$bod = file_get_contents('php://input');
			$data = json_decode($bod, true);
			$user_id = @$data['user_id'];
			$day = @$data['day'];
			$month = @$data['month'];
			if(!empty($user_id)) {
				$condition ="";$groupby ="";
				if(!empty($day) && $day !="") {
					$dates = date("Y-m-d",strtotime("-".$day." day"));
					$condition .= " and o.delivered_date = '".$dates."'";
				}
				if(!empty($month) && $month !="") {
					$m =[];$y =[];
					$ex = explode(",", $month);
					if(is_array($ex) && !empty($ex)) {
						foreach ($ex as $key => $value) {
							$m[] = date('m',strtotime($value));
							$y[] = date('Y',strtotime($value));
						}
						$m = implode(",", $m);
						$y = implode(",", array_unique($y));
					}
					$condition .=" and month(o.delivered_date) in (".$m.") and year(o.delivered_date) in (".$y.")";
				}
				$getFranchise = $this->master_db->getRecords('franchises',['franchise_id '=>$user_id,'status'=>0],'*');
					$getPincodes = $this->master_db->sqlExecute('select af.pincode_id,af.franchise_id,af.oid from franchises_pincodes fp left join assign_franchise af on af.franchise_id = fp.franchise_id where af.franchise_id ='.$user_id.' group by af.pincode_id');
					//echo $this->db->last_query();exit;
				$pins=[];$oid =[];$franchise_id =[];
				if(is_array($getPincodes) && !empty($getPincodes[0]) ) {
					foreach ($getPincodes as $key => $value) {
						$pins[] = $value->pincode_id;
						$oid[] = $value->oid;
						$franchise_id[] = $value->franchise_id;
					}
					if(is_array($pins) && !empty($pins[0]) && is_array($oid) && !empty($oid[0]) && is_array($franchise_id) && !empty($franchise_id[0])) {
						$pinids = implode(",", $pins);
						$oids = implode(",", $oid);
						$franchise_id = implode(",", $franchise_id);
						$condition .=" and af.franchise_id in (".$franchise_id.")";
					}
					if(count($getFranchise) >0) {
						$getOrders = $this->master_db->sqlExecute('select o.oid as id,o.orderid,DATE_FORMAT(o.order_date,"%d-%m-%Y") as ordered_date,o.totalamount as total_amount,u.name as customer_name,o.pmode,o.status,p.pay_id,o.delivered_date from orders o left join users u on u.u_id = o.user_id left join payment_log p on p.oid = o.oid left join assign_franchise af on af.oid=o.oid where o.status =3 '.$condition.' group by o.orderid order by o.oid desc');
						//echo $this->db->last_query();exit;
						if(count($getOrders) >0) {
							foreach ($getOrders as $key => $value) {
								$oid = $value->id;
								$getItems = $this->master_db->sqlExecute('select ob.name,ob.pcode,ob.image,ob.qty,ob.stock,ob.price,s.sname from order_products ob left join product_size ps on ps.pro_size = ob.psid left join sizes s on s.s_id = ps.sid where ob.oid='.$oid.'');
								if(!empty($value->uname) && $value->uname !=NULL){
									$value->uname =$value->uname;
								}else {
									$value->uname = "";
								}
								if ($r->status == 1) {
					                $r->status = "In Progress";
					            } else if ($r->status == 2){
					                $r->status = "Ready to Ship";
					            }
					            else if ($r->status == 3){
					                $r->status = "Delivered";
					            }
					            else if ($r->status == 4){
					                $r->status = "Order Cancelled";
					            }
					            else if ($r->status == 5){
					                $r->status = "Order Confirmed";
					            }
					            else if ($r->status == -1){
					                $r->status = "Order Failed";
					            }
					            if($r->pmode ==1) {
					                $r->pmode="Online";
					            }else if($r->pmode ==2) {
					                $r->pmode="COD";
					            }
							}
							$result = array('status'=>'success','data'=>$getOrders);
						}else {
							$result = array('status'=>'failure','msg'=>'Orders not found');
						}

					}else {
						$result = array('status'=>'failure','msg'=>'User id not exists try another');
					}
				}else {
					$result = array('status'=>'failure','msg'=>'No data found');
				}
				
			}
			echo json_encode($result);
		}
		public function orderscount() {
		      $bod = file_get_contents('php://input');
		      $data = json_decode($bod, true);
		      $user_id = @$data['user_id'];
		      $condition ="";$condition1 = "";
		      if(!empty($user_id)) {
		        $getFranchise = $this->master_db->getRecords('franchises',['franchise_id '=>$user_id,'status'=>0],'*');
		        $todayCount ="";$pendingCount="";
		        /*** Today ****/
		        $getPincodes = $this->master_db->sqlExecute('select af.pincode_id,af.franchise_id,af.oid from assign_franchise  af left join franchises_pincodes fp on fp.franchise_id = af.franchise_id where af.franchise_id ='.$user_id.' group by af.oid order by af.id desc');
		        /*** Pending ****/
		        $getPincodes1 = $this->master_db->sqlExecute('select af.pincode_id,af.franchise_id,af.oid from franchises_pincodes fp left join assign_franchise af on af.franchise_id = fp.franchise_id where af.franchise_id ='.$user_id.' group by af.pincode_id');
		       
		        $pins=[];$oid =[];$franchise_id =[];$pins1=[];$oid1 =[];$franchise_id1 =[];
		        if(is_array($getPincodes) && !empty($getPincodes[0]) ) {
		          foreach ($getPincodes as $key => $value) {
		            $pins[] = $value->pincode_id;
		            $oid[] = $value->oid;
		          }
		        }
		        /*** Today ****/
		        if(is_array($getPincodes) && !empty($getPincodes[0])) {
		          foreach ($getPincodes as $key => $value) {
		            $pins[] = $value->pincode_id;
		            $oid[] = $value->oid;
		            $franchise_id[] = $value->franchise_id;
		          }
		          if(is_array($pins) && !empty($pins[0]) && is_array($oid) && !empty($oid[0]) && is_array($franchise_id) && !empty($franchise_id[0])) {
		            $pinids = implode(",", array_unique($pins));
		            $oids = implode(",", array_unique($oid));
		            $franchise_id = implode(",", array_unique($franchise_id));
		            $condition .=" and af.pincode_id in (".$pinids.") and af.oid in (".$oids.") and af.franchise_id in (".$franchise_id.")";
		          }
		          if(count($getFranchise) >0) {
		            $getOrders = $this->master_db->sqlExecute('select o.oid as id,o.orderid,DATE_FORMAT(o.order_date,"%d-%m-%Y") as ordered_date,o.totalamount as total_amount,u.name as customer_name,o.pmode,o.status,p.pay_id from orders o left join users u on u.u_id = o.user_id left join payment_log p on p.oid = o.oid left join assign_franchise af on af.oid=o.oid where o.status not in (-1,4,1,3) and af.assigned_date="'.date('Y-m-d').'" '.$condition.' group by o.orderid order by o.oid desc');
		            if(count($getOrders) >0) {
		              $todayCount .=count($getOrders);
		            }else {
		              $todayCount .=0;
		            }
		          }else {
		            $result = array('status'=>'failure','msg'=>'User id not exists try another');
		          }
		        }
		          /*** Pending ****/
		          if(is_array($getPincodes1) && !empty($getPincodes1[0])) {
		            foreach ($getPincodes1 as $key => $pending) {
		            $pins1[] = $pending->pincode_id;
		            $oid1[] = $pending->oid;
		            $franchise_id1[] = $pending->franchise_id;
		            }
		            if(is_array($pins1) && !empty($pins1[0]) && is_array($oid1) && !empty($oid1[0]) && is_array($franchise_id1) && !empty($franchise_id1[0])) {
		              $pinids = implode(",", array_unique($pins1));
		              $oids = implode(",", array_unique($oid1));
		              $franchise_id = implode(",", array_unique($franchise_id1));
		              $condition1 .=" and af.pincode_id in (".$pinids.") and af.oid in (".$oids.") and af.franchise_id in (".$franchise_id.")";
		            }
		            if(count($getFranchise) >0) {
		            $getOrders1 = $this->master_db->sqlExecute('select o.oid as id,o.orderid,DATE_FORMAT(o.order_date,"%d-%m-%Y") as ordered_date,o.totalamount as total_amount,u.name as customer_name,o.pmode,o.status,p.pay_id from orders o left join users u on u.u_id = o.user_id left join payment_log p on p.oid = o.oid left join assign_franchise af on af.oid=o.oid where af.assigned_date <="'.date('Y-m-d').'" and  o.status in (5,2) '.$condition1.' group by o.orderid order by o.oid desc');
		            //echo $this->db->last_query();exit;

		              if(count($getOrders1) >0) {
		                $pendingCount .=count($getOrders1);
		              }else {
		                $pendingCount .=0;
		              }
		            }else {
		              $result['status'] = "failure";
		              $result['msg'] = "User id not exists try another";
		            }
		          } 
		          $result['status'] ="success";
		          $result['pendingcount'] = $pendingCount;
		          $result['todaycount'] =$todayCount;
		      }else {
		        $result['status'] = "failure";
		        $result['msg'] = "Required fields missing";
		      }
		      echo json_encode($result);
    	}
		public function franchisenotificationlogs() {
			if($_SERVER["REQUEST_METHOD"] == "POST") {
		   	 $bods = file_get_contents('php://input');
			 $datas = json_decode($bods, true);
			 $user_id = @$datas['user_id'];
			 if(!empty($user_id)) {
			 	$getFranchise = $this->master_db->getRecords('franchises',['franchise_id'=>$user_id,'status'=>0],'*');
			 	if(count($getFranchise) >0) {
			 		$getLogs = $this->master_db->sqlExecute('select f.id,f.message as msg,DATE_FORMAT(f.created_at,"%d-%m-%Y") as date,DATE_FORMAT(f.created_at,"%h:%i:%s %p") as time from franchise_notification_logs f left join orders o on o.oid=f.order_id where f.franchise_id ='.$user_id.'');
			 		if(count($getLogs) >0) {
			 			$result['status'] = "success";
			 			$result['msg'] =$getLogs;
			 		}else {
			 			$result['status'] = 'failure';
			 			$result['msg'] = 'No notification found';
			 		}
			 	}else {
			 		$result['status'] = "failure";
			 		$result['msg'] = "User not exists try another";
			 	}
			 }else { 
			 	$result['status'] ="failure";
			 	$result['msg'] ="Required fields missing";
			 }
			}else {
				$result['status'] = "failure";
				$result['msg'] = "Invalid request";
			}
			echo json_encode($result);
		}
		public function spotredemption() {
		  if($_SERVER["REQUEST_METHOD"] == "POST") {
		   	 $bods = file_get_contents('php://input');
			 $datas = json_decode($bods, true);
			 $cust_phone = @$datas['cust_phone'];
			 $voucher = @$datas['voucher'];
			 $user_id = @$datas['user_id'];
			 if(!empty($user_id) && !empty($voucher) && !empty($cust_phone)) {
			 	$getUser = $this->master_db->getRecords('franchises',['franchise_id'=>$user_id,'status'=>0],'*');
			 	//echo $this->db->last_query();exit;
			 	if(count($getUser) >0) {
			 		$getCoupon = $this->master_db->getRecords('vouchers',['title'=>$voucher,'type'=>2,'individual_limit_count'=>0],'*');
			 		if(count($getCoupon) >0) {
			 			$getV = $this->master_db->getRecords('users',['phone'=>$cust_phone,'status'=>0],'*');
			 			if(count($getV) >0) {
			 				$db['voucher_id'] = $getCoupon[0]->id;
				 			$db['voucher'] = $voucher;
				 			$db['vphone'] = $cust_phone;
				 			$in = $this->master_db->insertRecord('voucher_spot_redemption',$db);
				 			if($in >0) {
				 				$up['individual_limit_count'] =1;
				 				$this->master_db->updateRecord('vouchers',$up,['id'=>$getCoupon[0]->id]);
				 				$res['status'] = "success";
				 				$res['msg'] = "Congratulations, your gift voucher has been successfully redeemed Rs.".$getCoupon[0]->discount;
				 			}else {
				 				$res['status'] = "failure";
				 				$res['msg'] = "Error in saving";
				 			}
			 			}else {
			 				$res['status'] = "failure";
			 				$res['msg'] = "Phone number not exists try another";
			 			}
			 		}else {
			 			$res['status'] = "failure";
			 			$res['msg'] = "Voucher already used try another";
			 		}
			 	}else {
			 		$res['status'] = "failure";
			 		$res['msg'] = "User id not exists try another";
			 	}
			 }else {
			 	$res['status'] = "failure";
			 	$res['msg'] = "Required fields is missing";
			 }
		  }else {
		  	$res['status'] ="failure";
		  	$res['msg'] ="Invalid request";
		  }
		  echo json_encode($res);
		}
	}
?>