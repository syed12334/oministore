<?php 
if(!defined('BASEPATH')) exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST,DELETE,UPDATE");
header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
header('Content-type: application/json; charset=UTF-8');
class App extends CI_Controller { 
	public function __construct() {
		parent::__construct();	
		//$this->load->model('master_db');
		   $this->load->helper('utility_helper');
		$this->load->model('home_db');
		$this->load->model('master_db');
		$this->load->model('cart_db');
		
		date_default_timezone_set('Asia/Kolkata');
	}
	public function register() {
		$this->load->library('SMS');
		$this->load->library('Mail');
		 	$result = array('status'=>'failure','msg'=>'Required fields are missing.');
		 	$bodys = file_get_contents('php://input');
			$data = json_decode($bodys, true);
			$name = trim(preg_replace('!\s+!', '',$data['name']));
			$phone = trim(preg_replace('!\s+!', '',$data['phone']));
			$email = trim(preg_replace('!\s+!', '',$data['email']));
			$pincode = trim(preg_replace('!\s+!', '',$data['pincode']));
			//echo "<pre>";print_r($data);exit;
			if(!empty($name) && !empty($email) && !empty($phone) && !empty($pincode)) {
		 	 $users = $this->master_db->getRecords('users',['email'=>$email,'phone'=>$phone],'*');
		 	if(count($users) >0) {
		 		$result = array('status'=>'failure','msg'=>'Email or phone number already exists try another.');
		 	}else {
		 		  $otp = rand(1234,9988);
		                    $db['name'] = $name;
		                    $db['email'] = $email;
		                    $db['phone'] = $phone;
		                   // $db['password'] = password_hash($password,PASSWORD_BCRYPT);
		                    $db['otp'] = $otp;
		                    $db['pincode'] = $pincode;
		                    $db['created_at'] = date('Y-m-d H:i:s');
		                    $in = $this->master_db->insertRecord('users',$db);
		                    if($in >0) {
				                //$this->getSms($otp,$email,'user');
				                $message = "Dear User, Your OTP for www.swarnagowri.com login verification is {#var#}. Regards, Team SWARNAGOWRI.";
				                 $message = str_replace('{#var#}',$otp,$message);
				              	$this->sms->sendSmsToUser($message,$phone);
								$this->data['otp'] = $otp;
				        		$html = $this->load->view('otpemail',$this->data,true);
				        		$this->mail->send_sparkpost_attach($html,[$email],'OTP Confirmation');
				 				$userdata = ['user_id'=>$in,'email'=>$email,'phone'=>$phone];
				 				$result = array('status'=>'success','data'=>$userdata);
		 					}
			}
		 
	}else {
		$result = array('status'=>'failure','msg'=>'Required fields is missing');
	}

	echo json_encode($result);
}
	public function login() {
		 $bods = file_get_contents('php://input');
			$datas = json_decode($bods, true);
			$email = $datas['email'];
			$fcm = $datas['fcm_id'];
			//echo "<pre>";print_r($datas);exit;
		 if(!empty($email)) {
		 	if(is_numeric($email) && preg_match('/^[0-9]{10}+$/', $email)) {
		 		if(!empty($fcm) && $fcm !="") {
		 			$this->master_db->updateRecord('users',['fcm_id'=>$fcm],['phone'=>$email]);
		 		}
		 		$this->load->library('SMS');
		 		$getEmail = $this->master_db->sqlExecute('select * from users where phone='.$email.' and status =0');
		 		if(count($getEmail) >0) {
		 			$otp =rand(1234,9999);
		 			if($getEmail[0]->u_id ==61) {
		 				
		 			}else {
		 				$message = "Dear User, Your OTP for www.swarnagowri.com login verification is {#var#}. Regards, Team SWARNAGOWRI.";
			            $message = str_replace('{#var#}',$otp,$message);
			            $this->sms->sendSmsToUser($message,$email);
			            $update = $this->master_db->updateRecord('users',['otp'=>$otp],['u_id'=>$getEmail[0]->u_id]);
		 			} 
		            $data = ['user_id'=>$getEmail[0]->u_id,'user_name'=>$getEmail[0]->name,'user_email'=>$getEmail[0]->email,'user_phone'=>$getEmail[0]->phone,'user_pincode'=>$getEmail[0]->pincode];
		            $result = array('status'=>'success','msg'=>'OTP sent successfully','data'=>$data);
		 		}else {
		 			$result = array('status'=>'failure','msg'=>'User not exists');
		 		}
		 	}else if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
		 		if(!empty($fcm) && $fcm !="") {
		 			$this->master_db->updateRecord('users',['fcm_id'=>$fcm],['email'=>$email]);
		 		}
		 		$getEmail = $this->master_db->getRecords('users',['status'=>0,'email'=>$email],'*');
		 		if(count($getEmail) >0) {
		                $otp = rand(1234,9876);
		                $this->load->library('Mail');
		                $this->data['otp'] = $otp;
		                if($getEmail[0]->u_id ==61) {
		 				}else {
		 				  $html = $this->load->view('otpemail',$this->data,true);
		                  $this->mail->send_sparkpost_attach($html,[$email],'OTP Confirmation');
		                  $update = $this->master_db->updateRecord('users',['otp'=>$otp],['u_id'=>$getEmail[0]->u_id]);
		 				}
		                $data = ['user_id'=>$getEmail[0]->u_id,'user_name'=>$getEmail[0]->name,'user_email'=>$getEmail[0]->email,'user_phone'=>$getEmail[0]->phone,'user_pincode'=>$getEmail[0]->pincode];
		               $result = array('status'=>'success','msg'=>'OTP sent successfully','data'=>$data);
		            }else {
		               $result = array('status'=>'failure','msg'=>'User not exists');
		            }
		 	}else {
		 		$result = array('status'=>'failure','msg'=>'User not exists');
		 	}
		 }else {
		 			$result = array('status'=>'failure','msg'=>'Required fields are missing.');

		 }
		 echo json_encode($result);
	}
	public function verifyotp() {
		$result = array('status'=>'failure','msg'=>'Required fields are missing.');
		 $bod = file_get_contents('php://input');
			$data = json_decode($bod, true);
			$user_id = trim(preg_replace('!\s+!', '',$data['user_id']));
			$otp = trim(preg_replace('!\s+!', '',$data['otp']));
		 if(!empty($user_id) && !empty($otp)) {
		 	$count = $this->master_db->getRecords('users',['u_id'=>$user_id],'u_id,email,phone,name,pincode');
		 	if(count($count) >0) {
		 		$getotp = $this->master_db->getRecords('users',['u_id'=>$user_id,'otp'=>$otp],'*');
		 		$getFranchise = $this->master_db->getRecords('order_otp',['user_id'=>$user_id,'otp'=>$otp],'*');
		 		//echo $this->db->last_query();exit;
		 		if(count($getotp) >0) {
		 				$data = ['user_id'=>$count[0]->u_id,'username'=>$count[0]->name,'email'=>$count[0]->email,'phone'=>$count[0]->phone,'pincode'=>$count[0]->pincode];
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
	public function homepage() {
		$result = array('status'=>'failure','msg'=>'Required fields are missing.');
		$bod = file_get_contents('php://input');
		$data = json_decode($bod, true);
		$user_id = @$data['user_id'];
		$slider = $this->master_db->getRecords("slider_img",["status" => 0,'type'=>1],"id as slider_id,image,stitle as title,stagline as tagline,sliderlink as link","id desc");
		$ad1 = $this->master_db->getRecords("ads",["status" => 0, "above" => 0],"ad_banner,ad_name as title,ad_link as link,id","id desc","","","2");
		$topcat = $this->master_db->getRecords("category",["status" => 0, "image !=" => ""],"image,page_url,cname as title,id as cat_id","orderno asc");
		$query = "select p.id as pid,p.featuredimg as image,p.ptitle as title,ps.selling_price as price,ps.stock,p.page_url,c.page_url as cpage_url,ps.mrp,ps.sid,ps.pro_size as psid,ps.stock,s.sname,p.tax from products p left join product_size ps on ps.pid=p.id left join category c on c.id=p.cat_id left join sizes s on s.s_id =ps.sid left join product_site_type pst on pst.pid = p.id where p.newarrivals =0 and p.status =0 and pst.type =1 group by ps.pid order by p.orderno asc limit 10";
        $newarrivals = $this->master_db->sqlExecute($query);
        //echo "<pre>";print_r($newarrivals);exit;
        $popular = $this->master_db->getRecords("category",["status" => 0, "showcategory" => 0],"id as cat_id,page_url,cname,ads_image,icons","orderno asc","","","6");
		if(count($slider) >0) {
			foreach ($slider as $key => $slide) {
				$slide->image = base_url().$slide->image;
			}
		}
		if(count($ad1) >0) {
			foreach ($ad1 as $key => $ad) {
				$ad->ad_banner = base_url().$ad->ad_banner;
			}
		}
		if(count($topcat) >0) {
			foreach ($topcat as $key => $top) {
				$top->image = base_url().$top->image;
			}
		}
		if(count($newarrivals) >0) {
			foreach ($newarrivals as $key => $arrivals) {
				$pid = $arrivals->pid;
				$getProductsize = $this->master_db->sqlExecute('select s.s_id as sid,s.sname,ps.selling_price,ps.mrp,ps.stock,ps.pro_size as psid from product_size ps left join sizes s on s.s_id = ps.sid where ps.pid='.$pid.'');
				if(!empty($user_id)) {
					$getWishlist = $this->master_db->getRecords('wishlist',['uid'=>$user_id,'pid'=>$pid],'pid');
					if(count($getWishlist) >0 ) {
						$arrivals->is_wishlist = 1;
					}else {
						$arrivals->is_wishlist = 0;
					}
				}else {
					$arrivals->is_wishlist = 0;
				}
				if(count($getProductsize) >0) {
					foreach ($getProductsize as $key => $size) {
						$mrp = $size->mrp;
		                $sell = $size->selling_price;
						$disc = $this->home_db->discount($mrp, $sell);
						if (!empty($disc) && $disc != 0) {
							$size->discount = round($disc)."% off";
						}else {
							$size->discount = "";
						}
					}
				}
				$mrp = $arrivals->mrp;
                $sell = $arrivals->price;
				$arrivals->image = base_url().$arrivals->image;
				$disc = $this->home_db->discount($mrp, $sell);
				if (!empty($disc) && $disc != 0) {
					$arrivals->discount = round($disc)."% off";
				}else {
					$arrivals->discount = "";
				}
				$arrivals->product_sizes = $getProductsize;
			}
		}
		if(count($popular) >0) {
			foreach($popular as $key => $pop) {
				$pop->ads_image = base_url().$pop->ads_image;
                $id = $pop->cat_id;
                 $newq = "select p.id as pid,p.featuredimg as image,p.ptitle as title,ps.selling_price as price,ps.stock,p.page_url,c.page_url as cpage_url,ps.mrp,ps.sid,ps.pro_size as psid,s.sname,p.tax from products p left join product_size ps on ps.pid=p.id left join category c on c.id=p.cat_id left join sizes s on s.s_id =ps.sid left join product_site_type pst on pst.pid = p.id where p.cat_id =".$id." and p.status =0 and pst.type =1 group by ps.pid order by p.orderno asc limit 8 ";
                    $getProducts = $this->master_db->sqlExecute($newq);
                    if(count($getProducts) >0) {
                    	foreach ($getProducts as $key => $produ) {
                    		 $mrp = $produ->mrp;
			                $sell = $produ->price;
			                $pid = $produ->pid;
			                $getProductsize = $this->master_db->sqlExecute('select s.s_id as sid,s.sname,ps.selling_price,ps.mrp,ps.stock,ps.pro_size as psid from product_size ps left join sizes s on s.s_id = ps.sid where ps.pid='.$pid.'');
			                if(count($getProductsize) >0) {
								foreach ($getProductsize as $key => $size) {
									$mrp = $size->mrp;
					                $sell = $size->selling_price;
									$disc = $this->home_db->discount($mrp, $sell);
									if (!empty($disc) && $disc != 0) {
										$size->discount = round($disc)."% off";
									}else {
										$size->discount = "";
									}
								}
							}
							if(!empty($user_id)) {
								$getWishlist = $this->master_db->getRecords('wishlist',['uid'=>$user_id,'pid'=>$pid],'pid');
								if(count($getWishlist) >0 ) {
									$produ->is_wishlist =1;
								}else {
									$produ->is_wishlist =0;
								}
							}else {
								$produ->is_wishlist = 0;
							}
							$produ->image = base_url().$produ->image;
							$disc = $this->home_db->discount($mrp, $sell);
							if (!empty($disc) && $disc != 0) {
								$produ->discount = round($disc)."% off";
							}else {
								$produ->discount = "";
							}
							$produ->product_sizes = $getProductsize;
                    	}
                    }
                $pop->products = $getProducts;
			}
		}
		$result = ['status'=>'success','slider'=>$slider,'adbanner1' =>$ad1,'top_categories'=>$topcat,'newarrivals'=>$newarrivals,'category_wise'=>$popular];
		echo json_encode($result);
	}
	public function wishlist() {
		$result = array('status'=>'failure','msg'=>'Required fields are missing.');
		$bod = file_get_contents('php://input');
		$data = json_decode($bod, true);
		$user_id = @$data['user_id'];
		if(!empty($user_id)) {
			$getUser = $this->master_db->getRecords('users',['u_id'=>$user_id],'*');
			if(count($getUser) >0) {
				$getProducts =$this->master_db->sqlExecute('select p.id as pid,p.featuredimg as image,p.ptitle as title,ps.selling_price as price,ps.stock,p.page_url,c.page_url as cpage_url,ps.mrp,ps.sid,ps.pro_size as psid,ps.stock,p.tax from wishlist w left join products p on p.id=w.pid left join product_size ps on ps.pid=p.id left join category c on c.id=p.cat_id left join product_site_type pst on pst.pid = p.id where w.uid ='.$user_id.' and w.pid !=0 and pst.type =1 group by ps.pid order by w.id desc');
				if(count($getProducts) >0) {
					foreach ($getProducts as $key => $value) {
						$value->image =base_url().$value->image;
					}
				}
				$result = ['status'=>'success','data'=>$getProducts];
			}else {
				$result = ['status'=>'failure','msg'=>'User id not exists try another'];
			}
		}
        echo json_encode($result);
	}
	public function addwishlist() {
		$result = array('status'=>'failure','msg'=>'Required fields are missing.');
		$bod = file_get_contents('php://input');
		$data = json_decode($bod, true);
		$user_id = @$data['user_id'];
		$pid = @$data['pid'];
		if(!empty($user_id)) {
			$getUser = $this->master_db->getRecords('users',['u_id'=>$user_id],'*');
			if(count($getUser) >0) {
				$getProducts = $this->master_db->getRecords('products',['id'=>$pid],'id');
				if(count($getProducts) >0) {
					$wish = $this->master_db->getRecords('wishlist',['uid'=>$user_id,'pid'=>$pid],'*');
					if(count($wish) >0) {
						$result = ['status'=>'failure','msg'=>'Product already added to wishlist'];
					}else {
						$db['uid'] = $user_id;
						$db['pid'] = $pid;
						$db['created_at'] = date('Y-m-d H:i:s');
						$in = $this->master_db->insertRecord('wishlist',$db);
						if($in >0) {
							$result = ['status'=>'success','msg'=>'Added to wishlist'];
						}else {
							$result = ['status'=>'failure','msg'=>'Error in adding to wishlist'];
						}
					}
				}else {
					$result = ['status'=>'failure','msg'=>'Product id not exists try another'];
				}
			}else {
				$result = ['status'=>'failure','msg'=>'User id not exists try another'];
			}
		}
        echo json_encode($result);
	}
	public function removeWishlist() {
		$result = array('status'=>'failure','msg'=>'Required fields are missing.');
		$bod = file_get_contents('php://input');
		$data = json_decode($bod, true);
		$user_id = @$data['user_id'];
		$pid = @$data['pid'];
		if(!empty($user_id)) {
			$getUser = $this->master_db->getRecords('users',['u_id'=>$user_id],'*');
			if(count($getUser) >0) {
				$wish = $this->master_db->getRecords('wishlist',['uid'=>$user_id,'pid'=>$pid],'*');
				if(count($wish) >0) {
					$del = $this->master_db->deleterecord('wishlist',['uid'=>$user_id,'pid'=>$pid]);
					$result = ['status'=>'success','msg'=>'Product removed from wishlist'];
				}else {
					$result = ['status'=>'failure','msg'=>'Product not exists in wishlist'];
				}
			}else {
				$result = ['status'=>'failure','msg'=>'User id not exists try another'];
			}
		}
        echo json_encode($result);
	}
	public function pincodecheck() {
		$result = array('status'=>'failure','msg'=>'Required fields are missing.');
		$bod = file_get_contents('php://input');
		$data = json_decode($bod, true);
		$pincode = @$data['pincode'];
		if(!empty($pincode)) {
			$getPincode = $this->master_db->getRecords('pincodes',['pincode'=>$pincode],'*');
			if(count($getPincode) >0) {
				$result = ['status'=>'success','msg'=>'Within 24hrs'];
			}else {
				$result = ['status'=>'failure','msg'=>'Pincode not exists try another'];
			}
		}
        echo json_encode($result);
	}
	public function productlist() {
		$result = array('status'=>'failure','msg'=>'Required fields are missing.');
		$bod = file_get_contents('php://input');
		$data = json_decode($bod, true);
		$cat_id = @$data['cat_id'];
		$sort = @$data['sort'];
		$filters = @$data['filters'];
		if(!empty($cat_id)) {
			 $order_by ="";$pricefilts="";
			 if(!empty($sort)) {
			 	if($sort ==1) {
                    $order_by .=" order by CAST(ps.selling_price AS DECIMAL(10,2))";
                }else if($sort ==2) {
                    $order_by .=" order by CAST(ps.selling_price AS DECIMAL(10,2)) desc";
                }
                else if($sort ==3) {
                    $order_by .=" order by p.id desc";
                }
                else {
                    $order_by .=" order by p.orderno asc";
                }
			 }
			 if(!empty($filters)) {
			 	 	if($filters ==1) {
		              $pricefilts .=" and ps.selling_price between 50 and 100";
		            }
		            else if($filters ==2) {
		              $pricefilts .=" and ps.selling_price between 100 and 300";
		            }
		            else if($filters ==3) {
		              $pricefilts .=" and ps.selling_price between 300 and 500";
		            }
		            else if($filters ==4) {
		              $pricefilts .=" and ps.selling_price between 500 and 1000";
		            }
		            else if($filters ==5) {
		              $pricefilts .=" and ps.selling_price >=1000";
		            }
		            else if($filters ==6) {
		              $pricefilts .=" and ps.selling_price between 1 and 50";
		            }
		            else if($filters =="1,2") {
		                $pricefilts .=" and ps.selling_price between 50 and 300";
		            }
		            else if($filters =="1,2,3") {
		                $pricefilts .=" and ps.selling_price between 50 and 500";
		            }
		            else if($filters =="1,2,3,4") {
		                $pricefilts .=" and ps.selling_price between 50 and 1000";
		            }
		            else if($filters =="1,2,3,4,6") {
		                $pricefilts .=" and ps.selling_price between 1 and 1000";
		            }
		            else if($filters =="2,1") {
		                $pricefilts .=" and ps.selling_price between 50 and 300";
		            }
		            else if($filters =="2,3") {
		                $pricefilts .=" and ps.selling_price between 300 and 500";
		            }
		            else if($filters =="2,4") {
		                $pricefilts .=" and ps.selling_price between 300 and 1000";
		            }
		            else if($pricecom =="2,5") {
		                $pricefilts .=" and ps.selling_price between 300 and 1000";
		            }
		            else if($filters =="2,6") {
		                $pricefilts .=" and ps.selling_price between 1 and 300";
		            }
		             else if($filters =="2,1,3") {
		                $pricefilts .=" and ps.selling_price between 50 and 500";
		            }
		            else if($filters =="2,1,4") {
		                $pricefilts .=" and ps.selling_price between 50 and 1000";
		            }
		             else if($filters =="2,1,5") {
		                $pricefilts .=" and ps.selling_price between 50 and 1500";
		            }
		            else if($filters =="2,1,6") {
		                $pricefilts .=" and ps.selling_price between 1 and 300";
		            }
			 }
		   $query = "select p.id as pid,p.featuredimg as image,p.ptitle as title,ps.selling_price as price,ps.stock,p.page_url,c.page_url as cpage_url,ps.mrp,ps.sid,ps.pro_size as psid,ps.stock,s.sname,p.tax from products p left join product_size ps on ps.pid=p.id left join category c on c.id=p.cat_id left join sizes s on s.s_id =ps.sid left join product_site_type pst on pst.pid = p.id where p.cat_id =".$cat_id." ".$pricefilts."  and p.status =0 and pst.type =1 group by ps.pid ".$order_by."";
           $getDetails = $this->master_db->sqlExecute($query);
          // echo $this->db->last_query();exit;
           $filters = $this->master_db->getRecords('pricefilters',['id !='=>-1],'id,price_range','order_by asc');
           if(count($getDetails) >0) {
           		foreach ($getDetails as $key => $arrivals) {
				$pid = $arrivals->pid;
				$getProductsize = $this->master_db->sqlExecute('select s.s_id as sid,s.sname,ps.selling_price,ps.mrp,ps.stock,ps.pro_size as psid from product_size ps left join sizes s on s.s_id = ps.sid where ps.pid='.$pid.'');
				if(!empty($user_id)) {
					$getWishlist = $this->master_db->getRecords('wishlist',['uid'=>$user_id,'pid'=>$pid],'pid');
					if(count($getWishlist) >0 ) {
						$arrivals->is_wishlist = 1;
					}else {
						$arrivals->is_wishlist = 0;
					}
				}else {
					$arrivals->is_wishlist = 0;
				}
				if(count($getProductsize) >0) {
					foreach ($getProductsize as $key => $size) {
						$mrp = $size->mrp;
		                $sell = $size->selling_price;
						$disc = $this->home_db->discount($mrp, $sell);
						if (!empty($disc) && $disc != 0) {
							$size->discount = round($disc)."% off";
						}else {
							$size->discount = "";
						}
					}
				}
				$mrp = $arrivals->mrp;
                $sell = $arrivals->price;
				$arrivals->image = base_url().$arrivals->image;
				$disc = $this->home_db->discount($mrp, $sell);
				if (!empty($disc) && $disc != 0) {
					$arrivals->discount = round($disc)."% off";
				}else {
					$arrivals->discount = "";
				}
				$arrivals->product_sizes = $getProductsize;
				$result = ['status'=>'success','productData'=>$getDetails];
			}
           }else {
           		$result = array('status'=>'failure','msg'=>'No data found');
           }
		}
		echo json_encode($result);
	}
	public function filters() {
		$filters = $this->master_db->getRecords('pricefilters',['id !='=>-1],'id,price_range','order_by asc');
		if(count($filters) >0) {
			echo json_encode(['status'=>'success','filters'=>$filters]);
		}else {
			echo json_encode(['status'=>'failure','msg'=>'No data found']);
		}
	}
	public function sort() {
		$sort = [['1'=>'Low to High','2'=>'High to Low','3'=>'Newest First']];
		echo json_encode(['status'=>'success','sort_list'=>$sort]);
	}
	public function productdetails() {
		$result = array('status'=>'failure','msg'=>'Required fields are missing.');
		$bod = file_get_contents('php://input');
		$data = json_decode($bod, true);
		$pid = @$data['pid'];
		$user_id = @$data['user_id'];
		if(!empty($pid)) {
			$getPid = $this->master_db->getRecords('products',['id'=>$pid,'status'=>0],'id');
			if(count($getPid) >0) {
				$query = "select p.id as pid,p.subcat_id as subcat_id,p.ptitle as title,ps.selling_price as price,ps.stock,p.page_url,c.page_url as cpage_url,ps.mrp,ps.sid,ps.pro_size as psid,ps.stock,s.sname,p.overview as description,p.pspec as specification,p.youtubelink,p.tax from products p left join product_size ps on ps.pid=p.id left join category c on c.id=p.cat_id left join sizes s on s.s_id =ps.sid left join product_site_type pst on pst.pid = p.id where p.id =".$pid." and p.status =0 group by ps.pid order by p.orderno asc";
           		$getDetails = $this->master_db->sqlExecute($query);
           		if(count($getDetails) >0) {
           			$wishlist ="";
           			if(!empty($user_id) && $user_id !=0) {
           				$getWishlist = $this->master_db->getRecords('wishlist',['uid'=>$user_id,'pid'=>$pid],'pid');
           				if(count($getWishlist) >0) {
           					$wishlist .=1;
           				}else {
           					$wishlist .=0;
           				}
           			}else {
           				$wishlist .=0;
           			}
           			$getDetails[0]->is_wishlist = $wishlist;
           			$rate = $this->home_db->getratings($pid);
           			$rel = "select p.id as pid,p.featuredimg as image,p.ptitle as title,ps.selling_price as price,ps.stock,p.page_url,c.page_url as cpage_url,ps.mrp,ps.sid,ps.pro_size as psid,ps.stock,s.sname,p.tax from products p left join product_size ps on ps.pid=p.id left join category c on c.id=p.cat_id left join sizes s on s.s_id =ps.sid left join product_site_type pst on pst.pid = p.id where p.id !=".$pid." and p.subcat_id=".$getDetails[0]->subcat_id." and p.status =0  group by ps.pid order by p.orderno asc";
           			$getRelated = $this->master_db->sqlExecute($rel);
           			$getProductsize = $this->master_db->sqlExecute('select s.s_id as sid,s.sname,ps.selling_price,ps.mrp,ps.stock,ps.pro_size as psid from product_size ps left join sizes s on s.s_id = ps.sid where ps.pid='.$pid.'');
           			if(count($getProductsize) >0) {
							foreach ($getProductsize as $key => $size) {
								$mrp = $size->mrp;
				                $sell = $size->selling_price;
								$disc = $this->home_db->discount($mrp, $sell);
								if (!empty($disc) && $disc != 0) {
									$size->discount = round($disc)."% off";
								}else {
									$size->discount = "";
								}
							}
						}
           			$prodimg = $this->master_db->getRecords('product_images',['pid'=>$pid],'id,p_image as image');
           			$reviews  =$this->master_db->sqlExecute('select r.review_descp,u.name,r.rating,r.created_at,r.pid,r.status from reviews r left join users u on u.u_id = r.user_id where r.pid='.$getDetails[0]->pid.' and r.status =0');
           			if(!empty($getDetails[0]->youtubelink) && $getDetails[0]->youtubelink !="") {
           				$getDetails[0]->youtubelink = "https://www.youtube.com/watch?v=".$getDetails[0]->youtubelink;
           			}else {
           				$getDetails[0]->youtubelink ="";
           			}
           			if(count($prodimg) >0) {
           				foreach ($prodimg as $key => $value) {
           					$value->image = base_url().$value->image;
           				}
           			}
           			if(!empty($getDetails[0]->mrp) && $getDetails[0]->mrp !="" && $getDetails[0]->mrp > $getDetails[0]->price) {
           				$getDetails[0]->savedamt = $getDetails[0]->mrp - $getDetails[0]->price;
           			}
           			if(count($getRelated) >0) {
           				foreach ($getRelated as $key => $related) {
           					$rpid = $related->pid;
           					$getRelatedsize = $this->master_db->sqlExecute('select s.s_id as sid,s.sname,ps.selling_price,ps.mrp,ps.stock,ps.pro_size as psid from product_size ps left join sizes s on s.s_id = ps.sid where ps.pid='.$rpid.'');
           					if(count($getRelatedsize) >0) {
								foreach ($getRelatedsize as $key => $size) {
									$mrp = $size->mrp;
					                $sell = $size->selling_price;
									$disc = $this->home_db->discount($mrp, $sell);
									if (!empty($disc) && $disc != 0) {
										$size->discount = round($disc)."% off";
									}else {
										$size->discount = "";
									}
								}
							}
           					$mrp = $related->mrp;
			                $sell = $related->price;
							$related->image = base_url().$related->image;
							$disc = $this->home_db->discount($mrp, $sell);
							if (!empty($disc) && $disc != 0) {
								$related->discount = round($disc)."% off";
							}else {
								$related->discount = "";
							}
							$related->product_sizes = $getRelatedsize;
           				}
           			}
           			$getDetails[0]->rating = $rate;
           			$getDetails[0]->product_size = $getProductsize;
           			$getDetails[0]->product_images = $prodimg;
           			$getDetails[0]->related_products = $getRelated;
           			$getDetails[0]->reviews = $reviews;

           		}
				$result = ['status'=>'success','productdetails'=>$getDetails];
			}else {
				$result = ['status'=>'failure','msg'=>'Product id not exists try another'];
			}
		}
		echo json_encode($result);
	}
	public function specialproductdetails() {
		$result = array('status'=>'failure','msg'=>'Required fields are missing.');
		$bod = file_get_contents('php://input');
		$data = json_decode($bod, true);
		$pid = @$data['pid'];
		$user_id = @$data['user_id'];
		if(!empty($pid)) {
			$getPid = $this->master_db->getRecords('products',['id'=>$pid,'status'=>0],'id');
			if(count($getPid) >0) {
				$query = "select p.id as pid,p.subcat_id as subcat_id,p.ptitle as title,ps.selling_price as price,ps.stock,p.page_url,c.page_url as cpage_url,ps.mrp,ps.sid,ps.pro_size as psid,ps.stock,s.sname,p.overview as description,p.pspec as specification,p.youtubelink,p.tax from products p left join product_size ps on ps.pid=p.id left join category c on c.id=p.cat_id left join sizes s on s.s_id =ps.sid left join product_site_type pst on pst.pid = p.id where p.id =".$pid." and p.status =0 and pst.type =2 group by ps.pid order by p.orderno asc";
           		$getDetails = $this->master_db->sqlExecute($query);
           		if(count($getDetails) >0) {
           			$wishlist ="";
           			if(!empty($user_id) && $user_id !=0) {
           				$getWishlist = $this->master_db->getRecords('wishlist',['uid'=>$user_id,'pid'=>$pid],'pid');
           				if(count($getWishlist) >0) {
           					$wishlist .=1;
           				}else {
           					$wishlist .=0;
           				}
           			}else {
           				$wishlist .=0;
           			}
           			$getDetails[0]->is_wishlist = $wishlist;
           			$rate = $this->home_db->getratings($pid);
           			$rel = "select p.id as pid,p.featuredimg as image,p.ptitle as title,ps.selling_price as price,ps.stock,p.page_url,c.page_url as cpage_url,ps.mrp,ps.sid,ps.pro_size as psid,ps.stock,s.sname,p.tax from products p left join product_size ps on ps.pid=p.id left join category c on c.id=p.cat_id left join sizes s on s.s_id =ps.sid left join product_site_type pst on pst.pid = p.id where p.id !=".$pid." and p.subcat_id=".$getDetails[0]->subcat_id." and p.status =0 and pst.type =2  group by ps.pid order by p.orderno asc";
           			$getRelated = $this->master_db->sqlExecute($rel);
           			$getProductsize = $this->master_db->sqlExecute('select s.s_id as sid,s.sname,ps.selling_price,ps.mrp,ps.stock,ps.pro_size as psid from product_size ps left join sizes s on s.s_id = ps.sid where ps.pid='.$pid.'');
           			if(count($getProductsize) >0) {
							foreach ($getProductsize as $key => $size) {
								$mrp = $size->mrp;
				                $sell = $size->selling_price;
								$disc = $this->home_db->discount($mrp, $sell);
								if (!empty($disc) && $disc != 0) {
									$size->discount = round($disc)."% off";
								}else {
									$size->discount = "";
								}
							}
						}
           			$prodimg = $this->master_db->getRecords('product_images',['pid'=>$pid],'id,p_image as image');
           			$reviews  =$this->master_db->sqlExecute('select r.review_descp,u.name,r.rating,r.created_at,r.pid,r.status from reviews r left join users u on u.u_id = r.user_id where r.pid='.$getDetails[0]->pid.' and r.status =0');
           			if(!empty($getDetails[0]->youtubelink) && $getDetails[0]->youtubelink !="") {
           				$getDetails[0]->youtubelink = "https://www.youtube.com/watch?v=".$getDetails[0]->youtubelink;
           			}else {
           				$getDetails[0]->youtubelink ="";
           			}
           			if(count($prodimg) >0) {
           				foreach ($prodimg as $key => $value) {
           					$value->image = base_url().$value->image;
           				}
           			}
           			if(!empty($getDetails[0]->mrp) && $getDetails[0]->mrp !="" && $getDetails[0]->mrp > $getDetails[0]->price) {
           				$getDetails[0]->savedamt = $getDetails[0]->mrp - $getDetails[0]->price;
           			}
           			if(count($getRelated) >0) {
           				foreach ($getRelated as $key => $related) {
           					$rpid = $related->pid;
           					$getRelatedsize = $this->master_db->sqlExecute('select s.s_id as sid,s.sname,ps.selling_price,ps.mrp,ps.stock,ps.pro_size as psid from product_size ps left join sizes s on s.s_id = ps.sid where ps.pid='.$rpid.'');
           					if(count($getRelatedsize) >0) {
								foreach ($getRelatedsize as $key => $size) {
									$mrp = $size->mrp;
					                $sell = $size->selling_price;
									$disc = $this->home_db->discount($mrp, $sell);
									if (!empty($disc) && $disc != 0) {
										$size->discount = round($disc)."% off";
									}else {
										$size->discount = "";
									}
								}
							}
           					$mrp = $related->mrp;
			                $sell = $related->price;
							$related->image = base_url().$related->image;
							$disc = $this->home_db->discount($mrp, $sell);
							if (!empty($disc) && $disc != 0) {
								$related->discount = round($disc)."% off";
							}else {
								$related->discount = "";
							}
							$related->product_sizes = $getRelatedsize;
           				}
           			}
           			$getDetails[0]->rating = $rate;
           			$getDetails[0]->product_size = $getProductsize;
           			$getDetails[0]->product_images = $prodimg;
           			$getDetails[0]->related_products = $getRelated;
           			$getDetails[0]->reviews = $reviews;

           		}
				$result = ['status'=>'success','productdetails'=>$getDetails];
			}else {
				$result = ['status'=>'failure','msg'=>'Product id not exists try another'];
			}
		}
		echo json_encode($result);
	}
	public function writereviews() {
		$result = array('status'=>'failure','msg'=>'Required fields are missing.');
		$bod = file_get_contents('php://input');
		$data = json_decode($bod, true);
		$pid = @$data['pid'];
		$user_id = @$data['user_id'];
		$comment = @$data['comment'];
		$star = @$data['star'];
		if(!empty($user_id) && !empty($pid) && !empty($comment)) {
			$getUser = $this->master_db->getRecords('users',['u_id'=>$user_id],'*');
			if(count($getUser) >0) {
				$getProduct = $this->master_db->getRecords('products',['id'=>$pid],'id');
				if(count($getProduct) >0) {
					$getReview = $this->master_db->getRecords('reviews',['user_id'=>$user_id,'pid'=>$pid],'*');
					if(count($getReview) >0) {
						$result = ['status'=>'failure','msg'=>'Review already exists try another'];
					}else {
						$db['pid'] = $pid;
                        $db['review_descp'] = $comment;
                        if(!empty($star)) {
                        	$db['rating'] = $star;
                        }
                        $db['user_id'] = $user_id;
                        $db['created_at'] = date('Y-m-d H:i:s');
                        $insert = $this->master_db->insertRecord('reviews',$db);
                        if($insert >0) {
                        	$result = ['status'=>'success','msg'=>'Review saved successfully'];
                        }else {
                        	$result = ['status'=>'failure','msg'=>'Error in saving review'];
                        }
					}
				}else {
					$result = ['status'=>'failure','msg'=>'Product id not exists try another'];
				}
			}else {
				$result = ['status'=>'failure','msg'=>'User id not exists try another'];
			}
		}
		echo json_encode($result);
	}
	public function state() {
		$getState = $this->master_db->getRecords('states',['status'=>0],'id,name');
		if(count($getState) >0) {
			echo json_encode(['status'=>'success','data'=>$getState]);
		}else {
			echo json_encode(['status'=>'failure','msg'=>'No states found']);
		}
	}
	public function city() {
		$result = array('status'=>'failure','msg'=>'Required fields are missing.');
		$bod = file_get_contents('php://input');
		$data = json_decode($bod, true);
		$state_id = @$data['state_id'];
		if(!empty($state_id)) {
			$getState = $this->master_db->getRecords('states',['id'=>$state_id,'status'=>0],'id,name');
			if(count($getState) >0) {
				$getCity = $this->master_db->getRecords('cities',['sid'=>$state_id],'id as city_id,cname as city_name');
				if(count($getCity) >0) {
					$result = ['status'=>'success','data'=>$getCity];
				}else {
					$result = ['status'=>'failure','msg'=>'No cities found'];
				}
			}else {
				$result = ['status'=>'failure','msg'=>'No states found'];
			}
		}
		echo json_encode($result);
	}
	public function area() {
		$result = array('status'=>'failure','msg'=>'Required fields are missing.');
		$bod = file_get_contents('php://input');
		$data = json_decode($bod, true);
		$city_id = @$data['city_id'];
		if(!empty($city_id)) {
			$getCity = $this->master_db->getRecords('cities',['id'=>$city_id,'status'=>0],'id');
			if(count($getCity) >0) {
				$getArea = $this->master_db->getRecords('area',['cid'=>$city_id],'id as area_id,areaname as areaname');
				if(count($getCity) >0) {
					$result = ['status'=>'success','data'=>$getArea];
				}else {
					$result = ['status'=>'failure','msg'=>'No area found'];
				}
			}else {
				$result = ['status'=>'failure','msg'=>'No cities found'];
			}
		}
		echo json_encode($result);
	}
	public function orders() {
		$result = array('status'=>'failure','msg'=>'Required fields are missing.');
		$bod = file_get_contents('php://input');
		$data = json_decode($bod, true);
		$user_id = @$data['user_id'];
		if(!empty($user_id)) {
			$users = $this->master_db->getRecords('users',['u_id'=>$user_id,'status'=>0],'*');
			if(count($users) >0) {
				$getOrder = $this->master_db->sqlExecute('select oid,orderid,totalamount,status,CASE WHEN pmode = 1 THEN "Online"
					WHEN pmode = 2 THEN "COD" WHEN pmode =3 THEN "Voucher"
					ELSE "No payment mode"
					END AS payment_mode,invoice_pdf,DATE_FORMAT(order_date,"%d-%m-%Y") as ordered_date,DATE_FORMAT(shipping_date,"%d-%m-%Y") as shipping_date,DATE_FORMAT(delivered_date,"%d-%m-%Y") as delivered_date from orders where user_id='.$user_id.' order by oid desc');
				if(count($getOrder) >0) {
					if(count($getOrder) >0) {
						foreach ($getOrder as $key => $value) {
							if(!empty($value->invoice_pdf)) {
								$value->invoice_pdf = base_url().$value->invoice_pdf;
							}else {
								$value->invoice_pdf = "";
							}
						}
					}
					$result = ['status'=>'success','data'=>$getOrder];
				}else {
					$result = ['status'=>'failure','msg'=>'No orders found'];
				}
			}else {
				$result = ['status'=>'failure','msg'=>'User not exists try another'];
			}
		}
		echo json_encode($result);
	}
	public function orderdetails() {
		$result = array('status'=>'failure','msg'=>'Required fields are missing.');
		$bod = file_get_contents('php://input');
		$data = json_decode($bod, true);
		$user_id = @$data['user_id'];
		$order_id = @$data['order_id'];
		if(!empty($user_id) && !empty($order_id)) {
			$users = $this->master_db->getRecords('users',['u_id'=>$user_id,'status'=>0],'*');
			if(count($users) >0) {
				$getOrder = $this->master_db->sqlExecute('select oid,orderid,totalamount,status,CASE WHEN pmode = 1 THEN "Online"
					WHEN pmode = 2 THEN "COD" WHEN pmode =3 THEN "Voucher"
					ELSE "No payment mode"
					END AS payment_mode,DATE_FORMAT(order_date,"%d-%m-%Y") as ordered_date,delivery_charges,taxamount,shipping_date,delivered_date from orders where oid='.$order_id.'');
				if(count($getOrder) >0) {
					if(!empty($getOrder[0]->shipping_date)) {
						$getOrder[0]->shipping_date = date('d-m-Y',strtotime($getOrder[0]->shipping_date));
					}else {
						$getOrder[0]->shipping_date ="";
					}
					if(!empty($getOrder[0]->delivered_date)) {
						$getOrder[0]->delivered_date = date('d-m-Y',strtotime($getOrder[0]->delivered_date));
					}else {
						$getOrder[0]->delivered_date ="";
					}
					$orderProd = $this->master_db->sqlExecute('select op.name as product_title, op.pcode,op.price,op.image,op.qty,s.sname from order_products op left join product_size ps on ps.pro_size = op.psid left join sizes s on s.s_id = ps.sid where op.oid='.$order_id.'');
					
					$orderBills = $this->master_db->sqlExecute('select ob.bfname,ob.bemail,ob.bphone,ob.baddress,s.name as state_name,c.cname as city_name,a.areaname from order_bills ob left join states s on s.id=ob.bstate left join cities c on c.id = ob.bcity left join area a on a.id = ob.barea  where ob.oid='.$order_id.'');
					$orderShips = $this->master_db->sqlExecute('select ob.sfname,ob.semail,ob.sphone,ob.saddress,s.name as state_name,c.cname as city_name,a.areaname from order_bills ob left join states s on s.id=ob.sstate left join cities c on c.id = ob.scity left join area a on a.id = ob.sarea  where ob.oid='.$order_id.'');
					if(!empty($orderShips[0]->state_name) && $orderShips[0]->state_name !=null) {
						$orderShips[0]->state_name = $orderShips[0]->state_name;
					}else {
						$orderShips[0]->state_name = "";
					}
					if(!empty($orderShips[0]->city_name) && $orderShips[0]->city_name !=null) {
						$orderShips[0]->city_name = $orderShips[0]->city_name;
					}else {
						$orderShips[0]->city_name = "";
					}
					if(!empty($orderShips[0]->areaname) && $orderShips[0]->areaname !=null) {
						$orderShips[0]->areaname = $orderShips[0]->areaname;
					}else {
						$orderShips[0]->areaname = "";
					}
					$getOrder[0]->orderbilling_addr = $orderBills;
					$getOrder[0]->ordershipping_addr = $orderShips;
					$getOrder[0]->order_products = $orderProd;
					$result = ['status'=>'success','data'=>$getOrder];
				}else {
					$result = ['status'=>'failure','msg'=>'No orders found'];
				}
			}else {
				$result = ['status'=>'failure','msg'=>'User not exists try another'];
			}
		}
		echo json_encode($result);
	}
	public function cancelorder() {
		$result = array('status'=>'failure','msg'=>'Required fields are missing.');
		$bod = file_get_contents('php://input');
		$data = json_decode($bod, true);
		$user_id = @$data['user_id'];
		$order_id = @$data['order_id'];
		if(!empty($user_id) && !empty($order_id)) {
			$users = $this->master_db->getRecords('users',['u_id'=>$user_id,'status'=>0],'*');
			if(count($users) >0) {
				$getOrder = $this->master_db->getRecords('orders',['oid'=>$order_id],'oid');
				if(count($getOrder) >0) {
					$getPayment = $this->master_db->sqlExecute('select o.oid,o.totalamount from orders o left join payment_log p on p.oid =o.oid where p.status =1 and o.oid ='.$order_id.'');
					if(count($getPayment) >0) {
						$or['order_id'] = $getPayment[0]->oid;
                        $or['user_id'] = $user_id;
                        $or['amount'] = $getPayment[0]->totalamount;
                        $this->master_db->insertRecord('referrel_points',$or);
					}
					$this->master_db->updateRecord('orders',['status'=>4],['oid'=>$order_id]);
					$result = ['status'=>'success','msg'=>'Order cancelled successfully'];
				}else {
					$result = ['status'=>'failure','msg'=>'Order id not found try another'];
				}
			}else {
				$result = ['status'=>'failure','msg'=>'User not exists try another'];
			}
		}
		echo json_encode($result);
	}
	public function myaccount() {
		$result = array('status'=>'failure','msg'=>'Required fields are missing.');
		$bod = file_get_contents('php://input');
		$data = json_decode($bod, true);
		$user_id = trim(preg_replace('!\s+!', '',$data['user_id']));
		if(!empty($user_id)) {
			$count = $this->master_db->getRecords('users',['u_id'=>$user_id],'u_id as user_id,email,phone,name,pincode');
			if(count($count) >0) {
				$result = ['status'=>'success','data'=>$count];
			}else {
				$result = ['status'=>'failure','msg'=>'User id not exists try another'];
			}
		}
		echo json_encode($result);
	}
	public function updateprofile() {
		$result = array('status'=>'failure','msg'=>'Required fields are missing.');
		$bod = file_get_contents('php://input');
		$data = json_decode($bod, true);
		$user_id = @$data['user_id'];
		$name = @$data['name'];
		$phone = @$data['phone'];
		$email = @$data['email'];
		$pincode = @$data['pincode'];
		if(!empty($user_id)) {
			$count = $this->master_db->getRecords('users',['u_id'=>$user_id],'u_id as user_id,email,phone,name');
			if(count($count) >0) {
				if(!empty($name)) {
					$db['name'] = $name;
				}
				if(!empty($email)) {
					$db['email'] = $email;
				}
				if(!empty($phone)) {
					$db['phone'] = $phone;
				}
				if(!empty($pincode)) {
					$db['pincode'] = $pincode;
				}
				$this->master_db->updateRecord('users',$db,['u_id'=>$user_id]);
				$result = ['status'=>'success','msg'=>'Profile updated successfully'];
			}else {
				$result = ['status'=>'failure','msg'=>'User id not exists try another'];
			}
		}
		echo json_encode($result);
	}
	public function search() {
		$result = array('status'=>'failure','msg'=>'Required fields are missing.');
		$bod = file_get_contents('php://input');
		$data = json_decode($bod, true);
		$term = @$data['term'];
		if(!empty($term)) {
			$getData = $this->master_db->sqlExecute("select p.id,p.ptitle as title from category c left join products p on p.cat_id = c.id where p.ptitle like '%".$term."%' ");
			if(count($getData) >0) {
				$result = ['status'=>'success','data'=>$getData];
			}else {
				$result = ['status'=>'failure','msg'=>'No results found'];
			}
		}
		echo json_encode($result);
	}
	public function searchresults() {
		$bodys = file_get_contents('php://input');
		$datas = json_decode($bodys, true);
		$term = @$datas['searchterm'];
		if(!empty($term)) {
			$qu = "select p.id as pid,p.featuredimg as image,p.ptitle as title,ps.selling_price as price,ps.stock,p.page_url,c.page_url as cpage_url,ps.mrp,ps.sid,ps.pro_size as psid,p.tax,p.discount,s.sname,ps.stock from products p left join product_size ps on ps.pid=p.id left join category c on c.id=p.cat_id left join sizes s on s.s_id =ps.sid left join product_site_type pst on pst.pid = p.id where p.ptitle like '%".$term."%' and p.status =0 and pst.type =1  group by ps.pid order by p.orderno";
    		$products = $this->master_db->sqlExecute($qu);
    		if(count($products) >0) {
    			foreach ($products as $key => $value) {
    				$pid = $value->pid;
    				$getProductsize = $this->master_db->sqlExecute('select s.s_id as sid,s.sname,ps.selling_price,ps.mrp,ps.stock,ps.pro_size as psid from product_size ps left join sizes s on s.s_id = ps.sid where ps.pid='.$pid.'');
    				if(!empty($value->image)) {
    					$value->image = base_url().$value->image;
    				}else {
    					$value->image = "";
    				}
    				$mrp = $value->mrp;
	                $sell = $value->price;
					$disc = $this->home_db->discount($mrp, $sell);
					if (!empty($disc) && $disc != 0) {
						$value->discount = round($disc)."% off";
					}else {
						$value->discount = "";
					}
					$value->product_size = $getProductsize;
    			}
    			$result = ['status'=>'success','data'=>$products];
    		}else {
    			$result = ['status'=>'failure','msg'=>'No search results found'];
    		}
		}else {
			$result = array('status'=>'failure','msg'=>'Required fields are missing.');
		}
		echo json_encode($result);
	}
	public function resendotp() {
		 $bods = file_get_contents('php://input');
			$datas = json_decode($bods, true);
			$email = $datas['email'];
			//echo "<pre>";print_r($datas);exit;
		 if(!empty($email)) {
		 	if(is_numeric($email) && preg_match('/^[0-9]{10}+$/', $email)) {
		 		$this->load->library('SMS');
		 		$getEmail = $this->master_db->sqlExecute('select * from users where phone='.$email.'');
		 		if(count($getEmail) >0) {
		 			$otp =rand(1234,9999);
		            $message = "Dear User, Your OTP for www.swarnagowri.com login verification is {#var#}. Regards, Team SWARNAGOWRI.";
		            $message = str_replace('{#var#}',$otp,$message);
		            $this->sms->sendSmsToUser($message,$email);
		            $update = $this->master_db->updateRecord('users',['otp'=>$otp],['u_id'=>$getEmail[0]->u_id]);
		            $data = ['user_id'=>$getEmail[0]->u_id,'user_name'=>$getEmail[0]->name,'user_email'=>$getEmail[0]->email,'user_phone'=>$getEmail[0]->phone];
		            $result = array('status'=>'success','msg'=>'OTP sent successfully','data'=>$data);
		 		}else {
		 			$result = array('status'=>'failure','msg'=>'User not exists');
		 		}
		 	}else if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
		 		$getEmail = $this->master_db->getRecords('users',['email'=>$email],'*');
		 		if(count($getEmail) >0) {
		                $otp = rand(1234,9876);
		                $this->load->library('Mail');
		                $this->data['otp'] = $otp;
		                $html = $this->load->view('otpemail',$this->data,true);
		                $this->mail->sendMail($email,$html,'OTP Confirmation');
		                $update = $this->master_db->updateRecord('users',['otp'=>$otp],['u_id'=>$getEmail[0]->u_id]);
		                $data = ['user_id'=>$getEmail[0]->u_id,'user_name'=>$getEmail[0]->name,'user_email'=>$getEmail[0]->email,'user_phone'=>$getEmail[0]->phone];
		               $result = array('status'=>'success','msg'=>'OTP sent successfully','data'=>$data);
		            }else {
		               $result = array('status'=>'failure','msg'=>'User not exists');
		            }
		 	}else {
		 		$result = array('status'=>'failure','msg'=>'User not exists');
		 	}
		 }else {
		 		$result = array('status'=>'failure','msg'=>'Required fields are missing.');
		 }
		 echo json_encode($result);
		}
	public function confirmOrders() {
			$result = array('status'=>'failure','msg'=>'Required fields are missing.');
			$confirm = file_get_contents('php://input');
			$confirmOrders = json_decode($confirm, true);
			$user_id = @$confirmOrders['user_id'];
			$bname = @$confirmOrders['bname'];
			$total_amt = @$confirmOrders['total_amt'];
			$state_id = @$confirmOrders['state_id'];
			$city_id = @$confirmOrders['city_id'];
			$saved_amt = @$confirmOrders['saved_amt'];
			$scity = @$confirmOrders['scity'];
			$sstate = @$confirmOrders['sstate'];
			$sarea = @$confirmOrders['sarea'];
			$bstate = @$confirmOrders['bstate'];
			$bcity = @$confirmOrders['bcity'];
			$barea = @$confirmOrders['barea'];
			$bzip = @$confirmOrders['bzip'];
			$szip = @$confirmOrders['szip'];
			$bname = @$confirmOrders['bname'];
			$baddress = @$confirmOrders['baddress'];
			$saddress = @$confirmOrders['saddress'];
			$bemail = @$confirmOrders['bemail'];
			$semail = @$confirmOrders['semail'];
			$sphone = @$confirmOrders['sphone'];
			$bphone = @$confirmOrders['bphone'];
			$delivery_charges = @$confirmOrders['delivery_charge'];
			$payment_type = @$confirmOrders['payment_type'];
			$coupon_code = @$confirmOrders['coupon_code'];
			$coupon_percentage = @$confirmOrders['coupon_percentage'];
			$coupon_amount = @$confirmOrders['coupon_amount'];
			$total_gst_amount = @$confirmOrders['total_gst_amount'];
			$sub_total_amt = @$confirmOrders['sub_total_amt'];
			$sname = @$confirmOrders['sname'];
			$product =@$confirmOrders['products'];
			 $this->load->library('mail');
			if(!empty($user_id) && !empty($state_id) && !empty($city_id) && !empty($bzip) && !empty($baddress) && !empty($bphone) && !empty($payment_type)  &&  !empty($total_amt) && !empty($bemail) && !empty($bname) && is_array($product)) {
				$count = $this->master_db->getRecords('users',['u_id'=>$user_id],'*');
				if(count($count) >0) {
					$orders['user_id'] = $user_id;
						$orders['totalamount'] = $total_amt;
						if(!empty($coupon_amount)) {
							$orders['discount'] = $coupon_amount;
						}
						$orders['delivery_charges'] = $delivery_charges;
						if(!empty($total_gst_amount)) {
							$orders['taxamount'] = $total_gst_amount;
						}	
						$orders['subtotal'] = $sub_total_amt;
						$orders['pmode'] = $payment_type;
						$orders['order_date'] =date('Y-m-d H:i:s');
						$orders['datewise'] =date('Y-m-d');
						$orders['status'] = 1;
						$oid = $this->master_db->insertRecord('orders',$orders);
						if($oid >0) {
							 $orderNo = $this->cart_db->generateOrderNo($oid);
                         	 $db = array('orderid' => $orderNo);
                         	 $this->master_db->updateRecord('orders',$db,['oid'=>$oid]);
                         	 $bills['oid'] = $oid;
                         	 $bills['bfname'] = $bname;
                         	 $bills['bemail'] = $bemail;
                         	 $bills['bphone'] = $bphone;
                         	 $bills['bpincode'] = $bzip;
                         	 $bills['bstate'] = $bstate;
                         	 $bills['bcity'] = $bcity;
                         	 $bills['baddress'] = $baddress;
                         	 $bills['sfname'] = $sname;
                         	 $bills['semail'] = $semail;
                         	 $bills['sphone'] = $sphone;
                         	 $bills['spincode'] = $szip;
                         	 $bills['sstate'] = $sstate;
                         	 $bills['scity'] = $scity;
                         	 $this->master_db->insertRecord('order_bills',$bills);
                         	 if(is_array($product) && !empty($product)) {
                         	 	foreach ($product as $prod) {
                         	 		$oprod['oid'] = $oid;
                         	 		$oprod['qty'] = $prod['qty'];
                         	 		$oprod['name'] = $prod['name'];
                         	 		$oprod['price'] = $prod['unit_price'];
                         	 		$oprod['image'] = $prod['image_path'];
                         	 		$oprod['pid'] = $prod['pid'];
                         	 		$oprod['psid'] = $prod['psid'];
                         	 		$this->master_db->insertRecord('order_products',$oprod);
                         	 	}
                         	 }
                         	 $getrecords =$this->master_db->getRecords('orders',['oid'=>$oid],'*');
                         	if($payment_type ==1) {
                         		$txn   = substr(hash('sha512', mt_rand() . microtime()), 0, 40);
                         		$custAcc = rand(123456788888,999999999393);
								$paid_amount['payment_type'] = "Online";
								$paid_amount['order_id'] = $getrecords[0]->orderid;
								$paid_amount['merchantId'] = LOGIN;
								$paid_amount['password'] =Password;
								$paid_amount['prodid'] =ProductId;
								$paid_amount['txncurr'] =TransactionCurrency;
								$paid_amount['custacc'] =$custAcc;
								$paid_amount['amt'] =$getrecords[0]->totalamount;
								$paid_amount['txnid'] =$txn;
								$paid_amount['signature_request'] =ReqHashKey;
								$paid_amount['signature_response'] =ResHashKey;
								$paid_amount['enc_request'] =RequestEncypritonKey;
								$paid_amount['salt_request'] =Salt;
								$paid_amount['salt_response'] =ResponseSalt;
								$paid_amount['enc_response'] =ResponseDecryptionKey;
								$paid_amount['isLive'] =isLive;
								$paid_amount['custFirstName'] =$bname;
								$paid_amount['customerEmailID'] =$bemail;
								$paid_amount['customerMobileNo'] =$bphone;
								$paid_amount['udf1'] =$oid;
								$paid_amount['udf2'] =$bname;
								$paid_amount['udf3'] =$baddress;
								$paid_amount['udf4'] ="";
								$paid_amount['udf5'] ="";
								$paid_amount['callback_url'] = base_url().'v1/atomResponse';
								$payment['oid'] = $oid;
								$payment['transaction_id'] = $txn;
								$paid_amount['pstatus'] = "pending";
								$paid_amount['status'] = 3;
                            	$this->master_db->insertRecord('payment_log',$payment);			
								$result = ['status'=>'success','payment_response'=>$paid_amount];
							}
							else if($payment_type ==2){
								$payment['oid'] = $oid;
								$paid_amount['pstatus'] = "COD";
								$paid_amount['status'] = 2;
								$paid_amount['paid_amount'] = $total_amt;
								$paid_amount['order_id'] = $getrecords[0]->orderid;
								$this->master_db->updateRecord('orders',['status'=>5],['oid'=>$oid]);
                            	$this->master_db->insertRecord('payment_log',$payment);
                            	$this->data['orders'] = $orders = $this->master_db->getRecords('orders',['oid'=>$oid],'*');
			                    $this->data['orderproducts'] = $this->master_db->getRecords('order_products',['oid'=>$oid],'*');
			                    $this->data['payment'] = $this->master_db->getRecords('payment_log',['oid'=>$oid],'*');
			                    $this->data['billing'] = $this->master_db->sqlExecute('select * from order_bills where oid='.$oid.'');
			                     $this->data['order_bill'] = $order_bills =  $this->master_db->getRecords('order_bills',['oid'=>$oid],'*');
			                      $social_links = $this->master_db->getRecords('sociallinks',['status'=>0],'*');
			                    $this->data['social'] = $social_links;
			                     $html = $this->load->view('order_success',$this->data,true);
			                     $this->mail->sendMail($order_bills[0]->bemail,$html,'Your Order Summary');
                            	$result = ['status'=>'success','payment_response'=>$paid_amount];
							}
							else if($payment_type ==3){
								$this->master_db->updateRecord('orders',['status'=>5],['oid'=>$oid]);
								$paid_amount['pstatus'] = "Voucher";
								$paid_amount['paid_amount'] = $total_amt;
								$paid_amount['payment_mode'] = $payment_type;
								$paid_amount['order_id'] = $getrecords[0]->orderid;
								$paid_amount['type'] = "voucher";
								$payment['oid'] = $oid;
                            	$payment['status'] = 7;
                            	$this->master_db->insertRecord('payment_log',$payment);
                            	$this->data['orders'] = $orders = $this->master_db->getRecords('orders',['oid'=>$oid],'*');
			                    $this->data['orderproducts'] = $this->master_db->getRecords('order_products',['oid'=>$oid],'*');
			                    $this->data['payment'] = $this->master_db->getRecords('guest_payment_log',['oid'=>$oid],'*');
			                    $this->data['billing'] = $this->master_db->sqlExecute('select * from order_bills where oid='.$oid.'');
			                     $this->data['order_bill'] = $order_bills =  $this->master_db->getRecords('order_bills',['oid'=>$oid],'*');
			                      $social_links = $this->master_db->getRecords('sociallinks',['status'=>0],'*');
			                    $this->data['social'] = $social_links;
			                     $html = $this->load->view('order_success',$this->data,true);
			                     $this->mail->sendMail($order_bills[0]->bemail,$html,'Your Order Summary');
                            	$result = ['status'=>'success','payment_response'=>$paid_amount];
							}
							else if($payment_type ==4){
								 $getWallet = $this->master_db->getRecords('wallet',['user_id'=>$user_id],'*');
								 if(count($getWallet) >0) {
								 	 if($getWallet[0]->amount >0) {
								 	 	if($getrecords[0]->totalamount > $getWallet[0]->amount) {
								 	 		$differenceAmt = $getrecords[0]->totalamount - $getWallet[0]->amount;
								 	 		$txn   = substr(hash('sha512', mt_rand() . microtime()), 0, 40);
			                         		$custAcc = rand(123456788888,999999999393);
											$paid_amount['payment_type'] = "Online";
											$paid_amount['order_id'] = $getrecords[0]->orderid;
											$paid_amount['merchantId'] = LOGIN;
											$paid_amount['password'] =Password;
											$paid_amount['prodid'] =ProductId;
											$paid_amount['txncurr'] =TransactionCurrency;
											$paid_amount['custacc'] =$custAcc;
											$paid_amount['amt'] =$differenceAmt;
											$paid_amount['txnid'] =$txn;
											$paid_amount['signature_request'] =ReqHashKey;
											$paid_amount['signature_response'] =ResHashKey;
											$paid_amount['enc_request'] =RequestEncypritonKey;
											$paid_amount['salt_request'] =Salt;
											$paid_amount['salt_response'] =ResponseSalt;
											$paid_amount['enc_response'] =ResponseDecryptionKey;
											$paid_amount['isLive'] =isLive;
											$paid_amount['custFirstName'] =$bname;
											$paid_amount['customerEmailID'] =$bemail;
											$paid_amount['customerMobileNo'] =$bphone;
											$paid_amount['udf1'] =$oid;
											$paid_amount['udf2'] =$bname;
											$paid_amount['udf3'] =$baddress;
											$paid_amount['udf4'] ="";
											$paid_amount['udf5'] ="";
											$paid_amount['callback_url'] = base_url().'v1/atomResponse';
											 $result = ['status'=>'success','payment_response'=>$paid_amount];
											 $payment['oid'] = $oid;
											 $payment['transaction_id'] = $txn;
											$paid_amount['pstatus'] = "pending";
											$paid_amount['status'] = 3;
			                            	$this->master_db->insertRecord('payment_log',$payment);
								 	 	}else {
								 	 		$this->master_db->updateRecord('orders',['status'=>5],['oid'=>$oid]);
											$paid_amount['pstatus'] = "Wallet";
											$paid_amount['paid_amount'] = $total_amt;
											$paid_amount['payment_mode'] = $payment_type;
											$paid_amount['order_id'] = $getrecords[0]->orderid;
											$paid_amount['type'] = "wallet";
											$payment['oid'] = $oid;
			                            	$payment['status'] = 8;
			                            	$this->master_db->insertRecord('payment_log',$payment);
			                            	$this->data['orders'] = $orders = $this->master_db->getRecords('orders',['oid'=>$oid],'*');
						                    $this->data['orderproducts'] = $this->master_db->getRecords('order_products',['oid'=>$oid],'*');
						                    $this->data['payment'] = $this->master_db->getRecords('guest_payment_log',['oid'=>$oid],'*');
						                    $this->data['billing'] = $this->master_db->sqlExecute('select * from order_bills where oid='.$oid.'');
						                     $this->data['order_bill'] = $order_bills =  $this->master_db->getRecords('order_bills',['oid'=>$oid],'*');
						                      $social_links = $this->master_db->getRecords('sociallinks',['status'=>0],'*');
						                    $this->data['social'] = $social_links;
						                     $html = $this->load->view('order_success',$this->data,true);
						                     $this->mail->sendMail($order_bills[0]->bemail,$html,'Your Order Summary');
			                            	$result = ['status'=>'success','payment_response'=>$paid_amount];
								 	 	}
								 	 }
								 }
							}
						}
				}else {
					$result = ['status'=>'failure','msg'=>'User not exists try another'];
				}
			}
			echo json_encode($result);
	}
	public function easebuzzResponse() {
			$this->load->library('mail');
			$result = array('status'=>'failure','msg'=>'Required fields are missing.');
			$easebuzz = file_get_contents('php://input');
			$razorresponse = json_decode($easebuzz, true);
			$payment_id = $razorresponse['payment_id'];
			$status = $razorresponse['status'];
			$hash = $razorresponse['hash'];
			$txn_id = $razorresponse['txn_id'];
			$oid = $razorresponse['oid'];
			$pay_array = $razorresponse['pay_array'];
			if(!empty($payment_id) && !empty($status) && !empty($hash) && !empty($oid) && !empty($pay_array)) {
					if($status == "success") {
						  $dbp = array(
						  	'pay_array'=>$pay_array,
							'pay_id'=>$payment_id,
							'signature'=>$hash,
							'pstatus'=>'success',
							'status' => 1,
					);
					$this->master_db->updateRecord('payment_log',$dbp,['oid'=>$oid]);
					$this->data['orders'] = $orders = $this->master_db->getRecords('orders',['oid'=>$oid],'*');
                    $this->data['orderproducts'] = $this->master_db->getRecords('order_products',['oid'=>$oid],'*');
                    $this->data['payment'] = $this->master_db->getRecords('payment_log',['oid'=>$oid],'*');
                    $this->data['billing'] = $this->master_db->sqlExecute('select * from order_bills where oid='.$oid.'');
                     $this->data['order_bill'] = $order_bills =  $this->master_db->getRecords('order_bills',['oid'=>$oid],'*');
                     $this->data['payment'] = $this->master_db->getRecords('payment_log',['oid'=>$oid],'*');
                      $social_links = $this->master_db->getRecords('sociallinks',['status'=>0],'*');
                    $this->data['social'] = $social_links;
                     $html = $this->load->view('order_success',$this->data,true);
                     $this->mail->sendMail($order_bills[0]->bemail,$html,'Your Order Summary');
					$result = ['status'=>'success','msg'=>'Payment success'];
				}
				else if($status =="pending") {
					 $dbp = array(
					 	'pay_array'=>$pay_array,
						'pstatus'=>'pending',
						'status' => -1,
					 );
					$this->master_db->updateRecord('payment_log',$dbp,['oid'=>$oid]);
					$result = ['status'=>'failure','msg'=>'Payment pending'];
				}
				else if($status =="failure") {
					  $dbp = array(
						'pay_array'=>$pay_array,
						'pstatus'=>'failure',
						'status' => -1,
					);
					$this->master_db->updateRecord('payment_log',$dbp,['oid'=>$oid]);
					$result = ['status'=>'failure','msg'=>'Payment failure'];
				}
				else if($status == "userCancelled") {
					$dbp = array(
						'pay_array'=>$pay_array,
						'pstatus'=>'cancelled',
						'status' => 4,
					);
					$this->master_db->updateRecord('payment_log',$dbp,['oid'=>$oid]);
					$result = ['status'=>'failure','msg'=>'Payment cancelled by customer'];
				}
				else if($status=="dropped") {
					$dbp = array(
						'pay_array'=>$pay_array,
						'pstatus'=>'dropped',
						'status' => 5,
					);
					$this->master_db->updateRecord('payment_log',$dbp,['oid'=>$oid]);
					$result = ['status'=>'failure','msg'=>'Payment cancelled by customer'];
				}
				else if($status=="bounced") {
					$dbp = array(
						'pay_array'=>$pay_array,
						'pstatus'=>'bounced',
						'status' => 6,
					);
					$this->master_db->updateRecord('payment_log',$dbp,['oid'=>$oid]);
					$result = ['status'=>'failure','msg'=>'Payment cancelled by customer'];
				}
			}
			echo json_encode($result);
	}
	public function success() {

	}
	public function failures() {

	}
	public function couponcode() {
			$couponlist = file_get_contents('php://input');
			$coupon = json_decode($couponlist, true);
			$coupon_code = @$coupon['coupon_code'];
			$user_id = @$coupon['user_id'];
			$total_amount = @$coupon['total_amount'];
			if(!empty($coupon_code) && !empty($user_id) && !empty($total_amount)) {
				$date = date('Y-m-d');
				$getUser = $this->master_db->getRecords('users',['u_id'=>$user_id],'*');
				if(count($getUser) >0) {
					$getCoupon = $this->master_db->sqlExecute('select id,title as coupon_code,discount,to_date,usage_limit,min_amount,if(type =0,"flat","percentage") as type from vouchers where title ="'.$coupon_code.'"');
					if(count($getCoupon) >0) {
						if($getCoupon[0]->type ==0) {
							$getCoupon[0]->type = "flat";
						}
						else if($getCoupon[0]->type ==1) {
							$getCoupon[0]->type = "percentage";
						}
						else if($getCoupon[0]->type ==2) {
							$getCoupon[0]->type = "individual";
						}
						$getVouchetstatus = $this->master_db->getRecords('vouchers_users',['user_id'=>$user_id,'coupon_id'=>$getCoupon[0]->id],'*');
						 if($getCoupon[0]->type ==2) {
						 	 $getVouchercountstatus = $this->master_db->getRecords('vouchers',['individual_limit_count'=>1,'title'=>$getCoupon[0]->title],'*');
						 	 if(count($getVouchercountstatus) >0) {
						 	 	$results['status'] = "failure";
						 	 	$results['msg'] = "Vouchers already used";
						 	 	echo json_encode($results);
						 	 }else {
						 	 	if($total_amount >= $getCoupon[0]->min_amount) {
						 	 		$results['status'] = "success";
								 	$results['data'] = $getCoupon;
								 	echo json_encode($results);
						 	 	}
						 	 	else if($getCoupon[0]->min_amount ==0) {
						 	 		$results['status'] = "success";
								 	$results['data'] = $getCoupon;
								 	echo json_encode($results);
						 	 	}
						 	 	else {
						 	 		$results['status'] = "failure";
									$results['msg'] = "Coupon minimum amount should be Rs.".$getCoupon[0]->min_amount." to access coupon";
									echo json_encode($results);
						 	 	}
						 	 }
						 }
						//echo $this->db->last_query();exit;
						if(count($getVouchetstatus) > $getCoupon[0]->usage_limit) {
							$result['status']="failure";
							$result['msg'] = "Coupon limit exceeded";
						}else if($getCoupon[0]->usage_limit == count($getVouchetstatus)) {
							$result['status']="failure";
							$result['msg'] = "Coupon limit exceeded";
						}else {
							if($total_amount >= $getCoupon[0]->min_amount) {
								if($date  ==  $getCoupon[0]->to_date) {
								 	$result['status'] = "success";
								 	$result['discount'] = "-".$getCoupon[0]->discount;
								 	$result['total'] = $total;
								}
								else if($date  >  $getCoupon[0]->to_date) {
									$result['status'] = "failure";
									$result['msg'] = "Coupon code is expired";
								}else {
									$result['status'] = "success";
								 	$result['data'] = $getCoupon;
								}
							}else if($getCoupon[0]->min_amount ==0) {
								if($date  ==  $getCoupon[0]->to_date) {
								 	$result['status'] = "success";
								 	$result['discount'] = "-".$getCoupon[0]->discount;
								 	$result['total'] = $total;
								}
								else if($date  >  $getCoupon[0]->to_date) {
									$result['status'] = "failure";
									$result['msg'] = "Coupon code is expired";
								}else {
									$result['status'] = "success";
								 	$result['data'] = $getCoupon;
								}
							}else {
								$result['status'] = "failure";
								$result['msg'] = "Coupon minimum amount should be Rs.".$getCoupon[0]->min_amount." to access coupon";
							}
						}
					}else {
						$result['status'] = "failure";
						$result['msg'] = "Coupon code not exists try another";
					}
				}else {
					$result['status'] = "failure";
					$result['msg'] = "User id not exists try another";
				}
			}
			echo json_encode($result);
		}
		public function guestorder() {
			$result = array('status'=>'failure','msg'=>'Required fields are missing.');
			$confirm = file_get_contents('php://input');
			$confirmOrders = json_decode($confirm, true);
			$bname = @$confirmOrders['bname'];
			$total_amt = @$confirmOrders['total_amt'];
			$state_id = @$confirmOrders['state_id'];
			$city_id = @$confirmOrders['city_id'];
			$saved_amt = @$confirmOrders['saved_amt'];
			$scity = @$confirmOrders['scity'];
			$sstate = @$confirmOrders['sstate'];
			$sarea = @$confirmOrders['sarea'];
			$bstate = @$confirmOrders['bstate'];
			$bcity = @$confirmOrders['bcity'];
			// $barea = @$confirmOrders['barea'];
			$bzip = @$confirmOrders['bzip'];
			$szip = @$confirmOrders['szip'];
			$bname = @$confirmOrders['bname'];
			$baddress = @$confirmOrders['baddress'];
			$saddress = @$confirmOrders['saddress'];
			$bemail = @$confirmOrders['bemail'];
			$semail = @$confirmOrders['semail'];
			$sphone = @$confirmOrders['sphone'];
			$bphone = @$confirmOrders['bphone'];
			$delivery_charges = @$confirmOrders['delivery_charge'];
			$payment_type = @$confirmOrders['payment_type'];
			$coupon_code = @$confirmOrders['coupon_code'];
			$coupon_percentage = @$confirmOrders['coupon_percentage'];
			$coupon_amount = @$confirmOrders['coupon_amount'];
			$total_gst_amount = @$confirmOrders['total_gst_amount'];
			$sub_total_amt = @$confirmOrders['sub_total_amt'];
			$sname = @$confirmOrders['sname'];
			$product =@$confirmOrders['products'];
			 $this->load->library('mail');
			if(!empty($state_id) && !empty($city_id) && !empty($bzip) && !empty($baddress) && !empty($bphone) && !empty($payment_type) &&  !empty($total_amt) && !empty($bemail) && !empty($bname) && is_array($product)) {
						$orders['totalamount'] = $total_amt;
						if(!empty($coupon_amount)) {
							$orders['discount'] = $coupon_amount;
						}
						$orders['delivery_charges'] = $delivery_charges;
						if(!empty($total_gst_amount)) {
							$orders['taxamount'] = $total_gst_amount;
						}	
						$orders['subtotal'] = $sub_total_amt;
						$orders['pmode'] = $payment_type;
						$orders['order_date'] =date('Y-m-d H:i:s');
						$orders['datewise'] =date('Y-m-d');
						$orders['status'] = 1;
						$oid = $this->master_db->insertRecord('orders',$orders);
						if($oid >0) {
							 $orderNo = $this->cart_db->ggenerateOrderNo($oid);
                         	 $db = array('orderid' => $orderNo);
                         	 $this->master_db->updateRecord('orders',$db,['oid'=>$oid]);
                         	 $bills['oid'] = $oid;
                         	 $bills['bfname'] = $bname;
                         	 $bills['bemail'] = $bemail;
                         	 $bills['bphone'] = $bphone;
                         	 $bills['bpincode'] = $bzip;
                         	 $bills['bstate'] = $bstate;
                         	 $bills['bcity'] = $bcity;
                         	 $bills['baddress'] = $baddress;
                         	 $bills['sfname'] = $sname;
                         	 $bills['semail'] = $semail;
                         	 $bills['sphone'] = $sphone;
                         	 $bills['spincode'] = $szip;
                         	 $bills['sstate'] = $sstate;
                         	 $bills['scity'] = $scity;
                         	 $this->master_db->insertRecord('order_bills',$bills);
                         	 if(is_array($product) && !empty($product)) {
                         	 	foreach ($product as $prod) {
                         	 		$oprod['oid'] = $oid;
                         	 		$oprod['qty'] = $prod['qty'];
                         	 		$oprod['name'] = $prod['name'];
                         	 		$oprod['price'] = $prod['unit_price'];
                         	 		$oprod['image'] = $prod['image_path'];
                         	 		$oprod['pid'] = $prod['pid'];
                         	 		$oprod['psid'] = $prod['psid'];
                         	 		$this->master_db->insertRecord('order_products',$oprod);
                         	 	}
                         	 }
                         	 $getrecords =$this->master_db->getRecords('orders',['oid'=>$oid],'*');
                         	if($payment_type ==1) {
								$paid_amount['paid_amount'] = $getrecords[0]->totalamount;
								$paid_amount['pay_mode'] = "production";
								$paid_amount['payment_type'] = "Online";
								$paid_amount['order_id'] = $getrecords[0]->orderid;
								$paid_amount['easebuzz_merchantkey'] = "ZHVIAFPUZD";
								$paid_amount['easebuzz_saltkey'] = "LS3NNIA9OZ";
								$paid_amount['currency_type'] = 'INR';
								$paid_amount['easebuzz_callback'] = base_url().'v1/easebuzzResponse';
								$txn   = substr(hash('sha512', mt_rand() . microtime()), 0, 40);		
								$payment['oid'] = $oid;
								$payment['transaction_id'] = $txn;
								$paid_amount['pstatus'] = "pending";
								$paid_amount['status'] = 3;
                            	$this->master_db->insertRecord('payment_log',$payment);
                            	$paid_amount['payment_type'] = "Online";
								$paid_amount['order_id'] = $getrecords[0]->orderid;
								$paid_amount['merchantId'] = LOGIN;
								$paid_amount['password'] =Password;
								$paid_amount['prodid'] =ProductId;
								$paid_amount['txncurr'] =TransactionCurrency;
								$paid_amount['custacc'] =$custAcc;
								$paid_amount['amt'] =$getrecords[0]->totalamount;
								$paid_amount['txnid'] =$txn;
								$paid_amount['signature_request'] =ReqHashKey;
								$paid_amount['signature_response'] =ResHashKey;
								$paid_amount['enc_request'] =RequestEncypritonKey;
								$paid_amount['salt_request'] =Salt;
								$paid_amount['salt_response'] =ResponseSalt;
								$paid_amount['enc_response'] =ResponseDecryptionKey;
								$paid_amount['isLive'] =isLive;
								$paid_amount['custFirstName'] =$bname;
								$paid_amount['customerEmailID'] =$bemail;
								$paid_amount['customerMobileNo'] =$bphone;
								$paid_amount['udf1'] =$bname;
								$paid_amount['udf2'] =$baddress;
								$paid_amount['udf3'] ="";
								$paid_amount['udf4'] ="";
								$paid_amount['udf5'] ="";
								$paid_amount['callback_url'] = base_url().'v1/atomResponse';
								 $result = ['status'=>'success','payment_response'=>$paid_amount];

							}
							else if($payment_type ==2){
								$paid_amount['paid_amount'] = $total_amt;
								$paid_amount['payment_mode'] = $payment_type;
								$paid_amount['order_id'] = $getrecords[0]->orderid;
								$payment['oid'] = $oid;
                            	$payment['status'] = 2;
                            	$this->master_db->updateRecord('orders',['status'=>5],['oid'=>$oid]);
                            	$this->master_db->insertRecord('payment_log',$payment);
                            	$this->data['orders'] = $orders = $this->master_db->getRecords('orders',['oid'=>$oid],'*');
			                    $this->data['orderproducts'] = $this->master_db->getRecords('order_products',['oid'=>$oid],'*');
			                    $this->data['payment'] = $this->master_db->getRecords('guest_payment_log',['oid'=>$oid],'*');
			                    $this->data['billing'] = $this->master_db->sqlExecute('select * from order_bills where oid='.$oid.'');
			                     $this->data['order_bill'] = $order_bills =  $this->master_db->getRecords('order_bills',['oid'=>$oid],'*');
			                      $social_links = $this->master_db->getRecords('sociallinks',['status'=>0],'*');
			                    $this->data['social'] = $social_links;
			                     $html = $this->load->view('guestorder_success',$this->data,true);
			                     $this->mail->sendMail($order_bills[0]->bemail,$html,'Your Order Summary');
                            	$result = ['status'=>'success','payment_response'=>$paid_amount];
							}
							else if($payment_type ==3){
								$paid_amount['paid_amount'] = $total_amt;
								$paid_amount['payment_mode'] = $payment_type;
								$paid_amount['order_id'] = $getrecords[0]->orderid;
								$paid_amount['type'] = "voucher";
								$payment['oid'] = $oid;
                            	$payment['status'] = 7;
                            	$this->master_db->insertRecord('payment_log',$payment);
                            	$this->data['orders'] = $orders = $this->master_db->getRecords('orders',['oid'=>$oid],'*');
			                    $this->data['orderproducts'] = $this->master_db->getRecords('order_products',['oid'=>$oid],'*');
			                    $this->data['payment'] = $this->master_db->getRecords('guest_payment_log',['oid'=>$oid],'*');
			                    $this->data['billing'] = $this->master_db->sqlExecute('select * from order_bills where oid='.$oid.'');
			                     $this->data['order_bill'] = $order_bills =  $this->master_db->getRecords('order_bills',['oid'=>$oid],'*');
			                      $social_links = $this->master_db->getRecords('sociallinks',['status'=>0],'*');
			                    $this->data['social'] = $social_links;
			                     $html = $this->load->view('guestorder_success',$this->data,true);
			                     $this->mail->sendMail($order_bills[0]->bemail,$html,'Your Order Summary');
                            	$result = ['status'=>'success','payment_response'=>$paid_amount];
							}
						}
			}
			echo json_encode($result);
		}
		public function guesteasebuzzResponse() {
			$this->load->library('mail');
			$result = array('status'=>'failure','msg'=>'Required fields are missing.');
			$easebuzz = file_get_contents('php://input');
			$razorresponse = json_decode($easebuzz, true);
			$payment_id = $razorresponse['payment_id'];
			$status = $razorresponse['status'];
			$hash = $razorresponse['hash'];
			$txn_id = $razorresponse['txn_id'];
			$oid = $razorresponse['oid'];
			$pay_array = $razorresponse['pay_array'];
			if(!empty($payment_id) && !empty($status) && !empty($hash) && !empty($oid) && !empty($pay_array)) {
					if($status == "success") {
						  $dbp = array(
						  	'pay_array'=>$pay_array,
							'pay_id'=>$payment_id,
							'signature'=>$hash,
							'pstatus'=>'success',
							'status' => 1,
					);
					$this->master_db->insertRecord('guest_payment_log',$dbp);
					$this->data['orders'] = $orders = $this->master_db->getRecords('guest_orders',['oid'=>$oid],'*');
                    $this->data['orderproducts'] = $this->master_db->getRecords('guest_order_products',['oid'=>$oid],'*');
                    $this->data['payment'] = $this->master_db->getRecords('guest_payment_log',['oid'=>$oid],'*');
                    $this->data['billing'] = $this->master_db->sqlExecute('select * from guest_order_bills where oid='.$oid.'');
                     $this->data['order_bill'] = $order_bills =  $this->master_db->getRecords('guest_order_bills',['oid'=>$oid],'*');
                     $this->data['payment'] = $this->master_db->getRecords('guest_payment_log',['oid'=>$oid],'*');
                      $social_links = $this->master_db->getRecords('sociallinks',['status'=>0],'*');
                    $this->data['social'] = $social_links;
                     $html = $this->load->view('guestorder_success',$this->data,true);
                     $this->mail->sendMail($order_bills[0]->bemail,$html,'Your Order Summary');
					$result = ['status'=>'success','msg'=>'Payment success'];
				}
				else if($status =="pending") {
					 $dbp = array(
					 	'pay_array'=>$pay_array,
						'pstatus'=>'pending',
						'status' => -1,
				);
				$this->master_db->insertRecord('guest_payment_log',$dbp);
				$result = ['status'=>'failure','msg'=>'Payment pending'];
				}
				else if($status =="failure") {
					  $dbp = array(
						'pay_array'=>$pay_array,
						'pstatus'=>'failure',
						'status' => -1,
					);
					$this->master_db->insertRecord('guest_payment_log',$dbp);
					$result = ['status'=>'failure','msg'=>'Payment failure'];
				}
				else if($status == "userCancelled") {
					$dbp = array(
						'pay_array'=>$pay_array,
						'pstatus'=>'cancelled',
						'status' => -1,
					);
					$this->master_db->insertRecord('guest_payment_log',$dbp);
					$result = ['status'=>'failure','msg'=>'Payment cancelled by customer'];
				}
			}
			echo json_encode($result);
		}
		/****** Delivery Boy App *****/
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
				//echo $this->db->last_query();exit;
				$pins=[];$oid =[];$franchise_id =[];
				if(is_array($getPincodes) && !empty($getPincodes[0]) ) {
					foreach ($getPincodes as $key => $value) {
						$pins[] = $value->pincode_id;
						$oid[] = $value->oid;
					}
				}
				//echo "<prE>";print_r($oid);exit;
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
					//echo $this->db->last_query();exit;
				$pins=[];$oid =[];$franchise_id =[];
				if(is_array($getPincodes) && !empty($getPincodes[0]) ) {
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
						$getOrders = $this->master_db->sqlExecute('select o.oid as id,o.orderid,DATE_FORMAT(o.order_date,"%d-%m-%Y") as ordered_date,o.totalamount as total_amount,u.name as customer_name,o.pmode,o.status,p.pay_id from orders o left join users u on u.u_id = o.user_id left join payment_log p on p.oid = o.oid left join assign_franchise af on af.oid=o.oid where af.assigned_date < "'.date('Y-m-d').'" and  o.status in (5,2) '.$condition.' order by o.oid desc');
						
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
						$getFranchise = $this->master_db->getRecords('order_otp',['order_id'=>$order_id,'otp'=>$otp],'*');
						if(count($getFranchise) >0) {
							$update['delivered_date'] = date('Y-m-d');
							$update['status'] = $status;
							$this->master_db->updateRecord('orders',$update,['oid'=>$order_id]);
							$result = array('status'=>'success','msg'=>'Order status updated successfully');
							echo json_encode($result);exit;
						}
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
		            $getOrders1 = $this->master_db->sqlExecute('select o.oid as id,o.orderid,DATE_FORMAT(o.order_date,"%d-%m-%Y") as ordered_date,o.totalamount as total_amount,u.name as customer_name,o.pmode,o.status,p.pay_id from orders o left join users u on u.u_id = o.user_id left join payment_log p on p.oid = o.oid left join assign_franchise af on af.oid=o.oid where af.assigned_date <"'.date('Y-m-d').'" and  o.status in (5,2) '.$condition1.' group by o.orderid order by o.oid desc');
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
		public function specialoffers() {
		   if($_SERVER["REQUEST_METHOD"] == "POST") {
		   	 $bods = file_get_contents('php://input');
			 $datas = json_decode($bods, true);
			 $pincode = @$datas['pincode'];
			 if(!empty($pincode)) {
			 	$getPincode = $this->master_db->getRecords('pincodes',['pincode'=>$pincode,'site_type'=>2],'*');
			 	//echo $this->db->last_query();exit;
			 	if(count($getPincode) >0) {
			 		$getFranchise = $this->master_db->sqlExecute('select f.franchise_name,f.name_of_person as person_name,f.contact_no,f.whatsapp_no,f.address,f.area,f.subarea,f.street,f.gated_community from franchises f left join franchises_pincodes af on af.franchise_id =f.franchise_id where af.pincode_id ='.$getPincode[0]->id.' group by af.pincode_id');
			 		 $query = "select p.id as pid,p.featuredimg as image,p.ptitle as title,ps.selling_price as price,ps.stock,p.page_url,c.page_url as cpage_url,ps.mrp,ps.sid,ps.pro_size as psid,ps.stock,s.sname,p.tax from products p left join product_size ps on ps.pid=p.id left join category c on c.id=p.cat_id left join sizes s on s.s_id =ps.sid left join product_site_type pst on pst.pid = p.id where p.status =0 and pst.type =2  group by ps.pid order by p.orderno asc ";
			           $getDetails = $this->master_db->sqlExecute($query);
			           $slider = $this->master_db->getRecords("slider_img",["status" => 0,'type'=>2],"image,stitle as title,stagline as tagline,sliderlink as link","id desc");
			        //  echo $this->db->last_query();exit;
			           $filters = $this->master_db->getRecords('pricefilters',['id !='=>-1],'id,price_range','order_by asc');
			           if(count($getDetails) >0) {
			           		foreach ($getDetails as $key => $arrivals) {
							$pid = $arrivals->pid;
							$getProductsize = $this->master_db->sqlExecute('select s.s_id as sid,s.sname,ps.selling_price,ps.mrp,ps.stock,ps.pro_size as psid from product_size ps left join sizes s on s.s_id = ps.sid where ps.pid='.$pid.'');
							if(!empty($user_id)) {
								$getWishlist = $this->master_db->getRecords('wishlist',['uid'=>$user_id,'pid'=>$pid],'pid');
								if(count($getWishlist) >0 ) {
									$arrivals->is_wishlist = 1;
								}else {
									$arrivals->is_wishlist = 0;
								}
							}else {
								$arrivals->is_wishlist = 0;
							}
							if(count($getProductsize) >0) {
								foreach ($getProductsize as $key => $size) {
									$mrp = $size->mrp;
					                $sell = $size->selling_price;
									$disc = $this->home_db->discount($mrp, $sell);
									if (!empty($disc) && $disc != 0) {
										$size->discount = round($disc)."% off";
									}else {
										$size->discount = "";
									}
								}
							}
							$mrp = $arrivals->mrp;
			                $sell = $arrivals->price;
							$arrivals->image = base_url().$arrivals->image;
							$disc = $this->home_db->discount($mrp, $sell);
							if (!empty($disc) && $disc != 0) {
								$arrivals->discount = round($disc)."% off";
							}else {
								$arrivals->discount = "";
							}
							$arrivals->product_sizes = $getProductsize;
						}
						$res['status'] = 'success';
						$res['banners'] = $slider;
						$res['productData'] = $getDetails;
						if(count($getFranchise) >0) {
							$res['franchise_data'] = $getFranchise;
						}else {
							$res['franchise_data'] = "No franchise found";
						}
			           }else {
			           		$res['status'] = 'failure';
			           		$res['msg'] = 'No data found';
			           }
			 	}else {
				 	$res['status'] = "failure";
				 	$res['msg'] = "Pincode not exists try another";
			 	}
			 }else {
			 	$res['status'] = "failure";
		   	    $res['msg'] = "Required fields is missing";
			 }
		   }else {
		   	  $res['status'] = "failure";
		   	  $res['msg'] = "Invalid request";
		   }
		  echo json_encode($res);
		}
		public function qrcode() {
		   if($_SERVER["REQUEST_METHOD"] == "GET") {
		   		$getQr = $this->master_db->getRecords('qrcodeimg',['status'=>0],'id,qrcodeimg as qr_img');
		   		if(count($getQr) >0) {
		   			foreach ($getQr as $key => $value) {
		   				$value->qr_img = base_url().$value->qr_img;
		   			}
		   			$res['status'] ="success";
		   			$res['data'] =$getQr;
		   		}else {
		   			$res['status'] ="failure";
		   			$res['msg'] ="No data found";
		   		}
		    }else {
		    	$res['status'] ="failure";
		   		$res['msg'] ="Invalid request";
			}
			echo json_encode($res);
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
		public function wallet() {
			if($_SERVER["REQUEST_METHOD"] == "POST") {
				 $bods = file_get_contents('php://input');
				$datas = json_decode($bods, true);
				$user_id = $datas['user_id'];
				if(!empty($user_id)) {
					$users = $this->master_db->getRecords('users',['u_id'=>$user_id],'*');
					if(count($users) >0) {
						$wallet = $this->master_db->getRecords('wallet',['user_id'=>$user_id],'wallet_id,amount');
						if(count($wallet) >0) {
							$res['status'] ="success";
							$res['data'] =$wallet;
						}else {
							$res['status'] ="failure";
							$res['msg'] ="No data found";
						}
					}else {
						$res['status'] ="failure";
						$res['msg'] ="User not exists try another";
					}
				}else {
					$res['status'] ="failure";
					$res['msg'] ="Required field missing";
				}
			}else {
				$res['status'] ="failure";
				$res['msg'] ="Invalid request";
			}
			echo json_encode($res);
		}
		public function version() {
			if($_SERVER["REQUEST_METHOD"] == "GET") {
				$version = $this->master_db->getRecords('appversion',['id'=>1],'android as android_version,ios as ios_version,DATE_FORMAT(added_date,"%d-%m-%Y %h:%i:%s") as added_date');
				if(count($version) >0) {
					$res['status'] = "success";
					$res['data'] = $version;
				}else {
					$res['status'] = "failure";
					$res['msg'] = "No data found";
				}
			}else {
				$res['status'] ="failure";
				$res['msg'] ="Invalid request";
			}
		   echo json_encode($res);
		}
		public function atomResponse() {
			
		}
	}
?>