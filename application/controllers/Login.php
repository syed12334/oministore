<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Login extends CI_Controller {
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
          $this->data['totalreferrals'] =[];
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
            "includes/header1",
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
	}
	public function register() {
        if (!$this->session->userdata($this->data['session'])) {
            $this->load->view('login',$this->data);
        }else {
            redirect(base_url());
        }
    }
    public function registerSave() {
    	$this->load->library('SMS');
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			     $this->form_validation->set_rules('name','Name','trim|required|regex_match[/^([A-Za-z ])+$/i]|max_length[50]',['max_length'=>'Only 50 characters are allowed','regex_match'=>'Invalid name','required'=>'Name is required']);
		        $this->form_validation->set_rules('emailid','Email','trim|required|valid_email|regex_match[/^[_A-Za-zA-Z0-9-]+(\.[_A-Za-z0-9-]+)*@[A-Za-z0-9-]+(\.[a-z0-9-]+)*(\.[A-Za-z]{2,10})$/]',['valid_email'=>'Enter valid email','required'=>'Email is required','regex_match'=>'Invalid email']);
		        $this->form_validation->set_rules('phone','Phone','trim|required|numeric|exact_length[10]',['numeric'=>'Only numeric values are allowed','exact_length'=>'Phone number should be 10 digits','required'=>'Phone number is required']);
		        //$this->form_validation->set_rules('password','Password','trim|required',['maxlength'=>'Only 12 characters are allowed','required'=>'Password is required']);
		        //$this->form_validation->set_rules('cpassword','Password','trim|required|matches[password]',['matches'=>'Confirm password should match password','required'=>'Confirm password is required']);
		         if($this->form_validation->run() ==TRUE) {
		            $name = trim(preg_replace('!\s+!', ' ',html_escape($this->input->post('name', true))));
		            $email = trim(preg_replace('!\s+!', ' ',html_escape($this->input->post('emailid', true))));
		            $phone = trim(preg_replace('!\s+!', ' ',html_escape($this->input->post('phone', true))));
		           // $password = trim(preg_replace('!\s+!', ' ',html_escape($this->input->post('password', true))));
		            //$cpassword = trim(preg_replace('!\s+!', ' ',html_escape($this->input->post('cpassword', true))));
		            if(isset($name) && !empty($name) && isset($email) && !empty($email) && isset($phone) && !empty($phone) ) {
		                $getUsers = $this->master_db->getRecords('users',['email'=>$email,'phone'=>$phone],'*');
		                if(count($getUsers) >0) {
		                    $array = ['status'=>false,'msg'=>'Email or password already exists try another','csrf_token'=> $this->security->get_csrf_hash()];
		                }else {
		                    $otp = rand(1234,9988);
		                    $db['name'] = $name;
		                    $db['email'] = $email;
		                    $db['phone'] = $phone;
		                   // $db['password'] = password_hash($password,PASSWORD_BCRYPT);
		                    $db['otp'] = $otp;
		                    $db['created_at'] = date('Y-m-d H:i:s');
		                    $in = $this->master_db->insertRecord('users',$db);
		                    if($in >0) {
		                //$this->getSms($otp,$email,'user');
		                $message = "Dear User, Your OTP for www.swarnagowri.com login verification is {#var#}. Regards, Team SWARNAGOWRI.";
		                //echo $message;exit;
		                 $message = str_replace('{#var#}',$otp,$message);
		                // echo $message;exit;
		              $this->sms->sendSmsToUser($message,$phone);
		                        $this->session->set_userdata('emailid',$email);
		                        $this->load->library('Mail');
		                        $this->data['otp'] = $otp;
		                        $html = $this->load->view('otpemail',$this->data,true);
		                          $this->mail->send_sparkpost_attach($html,[$email],'OTP Confirmation');
		                        $array = ['status'=>true,'msg'=>'Account created successfully','csrf_token'=> $this->security->get_csrf_hash()];
		                    }else {
		                        $array = ['status'=>false,'msg'=>'Error in creating account','csrf_token'=> $this->security->get_csrf_hash()];
		                    }
		                }
		            }else {
		                $array = ['status'=>false,'msg'=>'Required fields are missing','csrf_token'=> $this->security->get_csrf_hash()];
		            }
		         }else {
		             $array = array(
			            'formerror'   => false,
			            'name_error' => form_error('name'),
			            'email_error' => form_error('emailid'),
			            'phone_error' => form_error('phone'),
			            //'password_error' => form_error('password'),
			            //'cpassword_error' => form_error('cpassword'),
			            'csrf_token'=> $this->security->get_csrf_hash()
		           );
		         }
			}else {
				$array = ['status'=>false,'msg'=>'Invalid request','csrf_token'=> $this->security->get_csrf_hash()];
			}
         echo json_encode($array);
    }
    public function otp() {
        if($this->session->userdata('emailid')) {
            $this->load->view('loginotp',$this->data);
        }else {
            redirect(base_url().'login');
        }
    	
    }
    public function verifyotp() {
    	//echo "<pre>";print_r($_POST);exit();
    	if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
        $this->form_validation->set_rules('otpval','Phone','trim|required|numeric',['numeric'=>'Only numeric values are allowed','required'=>'OTP is required']);
         if($this->form_validation->run() ==TRUE) {
         	 $otp = trim(preg_replace('!\s+!', ' ',html_escape($this->input->post('otpval', true))));
         	 $email = $this->session->userdata('emailid');
         	 if(is_numeric($email)) {
         	 	$getOtp = $this->master_db->getRecords('users',['otp'=>$otp,'phone'=>$email],'u_id as id,name,email,phone');
		            if(count($getOtp) >0) {
		            	$savesession =array('id'=>$getOtp[0]->id,'email'=>$getOtp[0]->email,'phone'=>$getOtp[0]->phone,'name'=>$getOtp[0]->name);
	                $this->session->set_userdata(CUSTOMER_SESSION, $savesession);
		                     $array = array(
		                        'status'   => true,
		                        'msg'       => 'OTP verified successfully',
		                        'csrf_token'=> $this->security->get_csrf_hash()
		                    );
		            }else {
		                $array = array(
		                    'status'   => false,
		                    'msg'       => 'Invalid OTP',
		                    'csrf_token'=> $this->security->get_csrf_hash()
		                );
		            }
         	 }else if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
         	 	$getOtp = $this->master_db->getRecords('users',['otp'=>$otp,'email'=>$email],'u_id as id,name,email,phone');
	         	if(count($getOtp) >0) {
	                $savesession =array('id'=>$getOtp[0]->id,'email'=>$getOtp[0]->email,'phone'=>$getOtp[0]->phone,'name'=>$getOtp[0]->name);
	                $this->session->set_userdata(CUSTOMER_SESSION, $savesession);  
	         		$array = ['status'=>true,'msg'=>'OTP verified successfully','csrf_token'=> $this->security->get_csrf_hash()];
	         	}else {
	         		 $array = ['status'=>false,'msg'=>'Invalid OTP','csrf_token'=> $this->security->get_csrf_hash()];
	         	}
         	 }else {
         	 	 $array = array(
                        'status'   => false,
                        'msg'       => 'Invalid email or phone number',
                        'csrf_token'=> $this->security->get_csrf_hash()
                    );
                    echo json_encode($array);exit;
         	 }

         	
        }else {
        	 $array = array(
	            'formerror'   => false,
	            'otp_error' => form_error('otpval'),
	            'csrf_token'=> $this->security->get_csrf_hash()
           );
        }
      }else {
      		$array = ['status'=>false,'msg'=>'Invalid Request','csrf_token'=> $this->security->get_csrf_hash()];
      }
      echo json_encode($array);
    }
    public function loginsave() {
    	if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
		       $this->form_validation->set_rules('email','Email','trim|required',['required'=>'Email is required','regex_match'=>'Invalid email']);
		       if($this->form_validation->run() ==TRUE) {
		            $email = $this->input->post('email');
		             $valid ="";
                if(is_numeric($email) && preg_match('/^[0-9]{10}+$/', $email)) {
                	$getEmail = $this->master_db->sqlExecute('select * from users where phone='.$email.'');
		            if(count($getEmail) >0) {
		            	$this->load->library('SMS');
		            	$this->session->set_userdata('emailid',$email);
		                //$otp = rand(1234,9876);
		                $otp =rand(1234,9999);
		                //$this->getSms($otp,$email,'user');
		                $message = "Dear User, Your OTP for www.swarnagowri.com login verification is {#var#}. Regards, Team SWARNAGOWRI.";
		                //echo $message;exit;
		                 $message = str_replace('{#var#}',$otp,$message);
		                // echo $message;exit;
		              $this->sms->sendSmsToUser($message,$email);

		                $update = $this->master_db->updateRecord('users',['otp'=>$otp],['u_id'=>$getEmail[0]->u_id]);
		                     $array = array(
		                        'status'   => true,
		                        'msg'       => 'OTP Sent Successfully',
		                        'csrf_token'=> $this->security->get_csrf_hash()
		                    );
		            }else {
		                $array = array(
		                    'status'   => false,
		                    'msg'       => 'Phone number not exists try another',
		                    'csrf_token'=> $this->security->get_csrf_hash()
		                );
		            }
                }elseif(filter_var($email, FILTER_VALIDATE_EMAIL)) {
                 	$getEmail = $this->master_db->sqlExecute('select * from users where email="'.$email.'"');
		            if(count($getEmail) >0) {
		                $otp = rand(1234,9876);
		                $this->load->library('Mail');
		                $this->data['otp'] = $otp;
		                $html = $this->load->view('otpemail',$this->data,true);
		                 $this->mail->send_sparkpost_attach($html,[$getEmail[0]->email],'OTP Confirmation');
		                $update = $this->master_db->updateRecord('users',['otp'=>$otp],['u_id'=>$getEmail[0]->u_id]);
		              $this->session->set_userdata('emailid',$email);
		                     $array = array(
		                        'status'   => true,
		                        'msg'       => 'OTP Sent Successfully',
		                        'csrf_token'=> $this->security->get_csrf_hash()
		                    );
		            }else {
		                $array = array(
		                    'status'   => false,
		                    'msg'       => 'Email not exists try another',
		                    'csrf_token'=> $this->security->get_csrf_hash()
		                );
		            }
                }else {
                 $array = array(
                        'status'   => false,
                        'msg'       => 'Invalid email or phone number',
                        'csrf_token'=> $this->security->get_csrf_hash()
                    );
                    echo json_encode($array);exit;
                }
		            
		        }else {
		            $array = array(
		                'formerror'   => false,
		                'email_error' => form_error('email'),
		                'csrf_token'=> $this->security->get_csrf_hash()
		           );
		        }
    	}else {
        	  $array = array(
                    'status'   => false,
                    'msg'       => 'Invalid request',
                    'csrf_token'=> $this->security->get_csrf_hash()
                );
    	}
    	echo json_encode($array);
    }
    public function resendOtp() {
    	if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
		        $email =  $this->session->userdata('emailid');
		        	$this->load->library('SMS');
		        if(!empty($email) && $email !="") {
		                if(is_numeric($email)) {
                	$getEmail = $this->master_db->sqlExecute('select * from users where phone='.$email.'');
			            if(count($getEmail) >0) {
			               // $otp = rand(1234,9876);
			            	 $otp =rand(1234,9999);
		                //$this->getSms($otp,$email,'user');
		                $message = "Dear User, Your OTP for www.swarnagowri.com login verification is ".$otp.". Regards, Team SWARNAGOWRI.";
		                $this->sms->sendSmsToUser($message,$email);
			                $update = $this->master_db->updateRecord('users',['otp'=>$otp],['u_id'=>$getEmail[0]->u_id]);
			                     $result = array(
			                        'status'   => true,
			                        'msg'       => 'OTP Sent Successfully',
			                        'csrf_token'=> $this->security->get_csrf_hash()
			                    );
			            }else {
			                $result = array(
			                    'status'   => false,
			                    'msg'       => 'Phone number not exists try another',
			                    'csrf_token'=> $this->security->get_csrf_hash()
			                );
			            }
	                }elseif(filter_var($email, FILTER_VALIDATE_EMAIL)) {
	                 	$getEmail = $this->master_db->sqlExecute('select * from users where email="'.$email.'"');
			            if(count($getEmail) >0) {
			                $otp = rand(1234,9876);
			                $this->load->library('Mail');
			                $this->data['otp'] = $otp;
			                $html = $this->load->view('otpemail',$this->data,true);
			                 $this->mail->send_sparkpost_attach($html,[$email],'OTP Confirmation');
			                $update = $this->master_db->updateRecord('users',['otp'=>$otp],['u_id'=>$getEmail[0]->u_id]);
			              $this->session->set_userdata('emailid',$email);
			                     $result = array(
			                        'status'   => true,
			                        'msg'       => 'OTP Sent Successfully',
			                        'csrf_token'=> $this->security->get_csrf_hash()
			                    );
			            }else {
			                $result = array(
			                    'status'   => false,
			                    'msg'       => 'Email not exists try another',
			                    'csrf_token'=> $this->security->get_csrf_hash()
			                );
			            }
	                }else {
	                 $result = array(
	                        'status'   => false,
	                        'msg'       => 'Invalid email or phone number',
	                        'csrf_token'=> $this->security->get_csrf_hash()
	                    );
	                    echo json_encode($array);exit;
	                }
		        }else {
		            $result = ['status'=>false,'msg'=>'Please enter emailid','csrf_token'=> $this->security->get_csrf_hash()];
		        }
    	}else {
    		$result = ['status'=>false,'msg'=>'Invalid request','csrf_token'=> $this->security->get_csrf_hash()];
    	}
            echo json_encode($result);

    }
    public function logout() {
        $this->session->unset_userdata(CUSTOMER_SESSION);
        redirect(base_url().'login');
    }
    public function updateProfile() {
    	if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			     $this->form_validation->set_rules('name','Name','trim|required|regex_match[/^([A-Za-z ])+$/i]|max_length[50]',['max_length'=>'Only 50 characters are allowed','regex_match'=>'Invalid name','required'=>'Name is required']);
		        $this->form_validation->set_rules('email','Email','trim|required|valid_email|regex_match[/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,10})$/]',['valid_email'=>'Enter valid email','required'=>'Email is required','regex_match'=>'Invalid email']);
		        $this->form_validation->set_rules('phone','Phone','trim|required|numeric|exact_length[10]',['numeric'=>'Only numeric values are allowed','exact_length'=>'Phone number should be 10 digits','required'=>'Phone number is required']);
		         if($this->form_validation->run() ==TRUE) {
		            $name = trim(preg_replace('!\s+!', ' ',html_escape($this->input->post('name', true))));
		            $email = trim(preg_replace('!\s+!', ' ',html_escape($this->input->post('email', true))));
		            $phone = trim(preg_replace('!\s+!', ' ',html_escape($this->input->post('phone', true))));
		            $uid = trim(preg_replace('!\s+!', ' ',html_escape($this->input->post('uid', true))));
		           
		            if(isset($name) && !empty($name) && isset($email) && !empty($email) && isset($phone) && !empty($phone) ) {
		                    $db['name'] = $name;
		                    $db['email'] = $email;
		                    $db['phone'] = $phone;
		                    $db['modified_at'] = date('Y-m-d H:i:s');
		                    $in = $this->master_db->updateRecord('users',$db,['u_id'=>$uid]);
		                    if($in >0) {
		                        $array = ['status'=>true,'msg'=>'Profile updated successfully','csrf_token'=> $this->security->get_csrf_hash()];
		                    }else {
		                        $array = ['status'=>false,'msg'=>'Error in updating profile','csrf_token'=> $this->security->get_csrf_hash()];
		                    }
		               
		            }else {
		                $array = ['status'=>false,'msg'=>'Required fields are missing','csrf_token'=> $this->security->get_csrf_hash()];
		            }
		         }else {
		             $array = array(
			            'formerror'   => false,
			            'name_error' => form_error('name'),
			            'email_error' => form_error('email'),
			            'phone_error' => form_error('phone'),
			            'csrf_token'=> $this->security->get_csrf_hash()
		           );
		         }
		         echo json_encode($array);
			}
    }
     public function getSms($rand,$mobile,$uname) {
              $ar = array('listsms'=>array(array('sms'=>'Dear '.$uname.' Your OTP for login is '.$rand.' Regards, Team Miracle Drinks.','mobiles'=>$mobile,'senderid'=>'MIRCOL','clientSMSID'=>'1947692308','accountusagetypeid'=>'1','entityid'=>'1701159256675943303', 'tempid'=>'1707167592051531633')),'password'=>'63d06f365dXX','user'=>'Miracolo');
              $var = json_encode($ar);
              $url = "http://mobicomm.dove-sms.com//REST/sendsms";
              $ch_session = curl_init();
              curl_setopt($ch_session, CURLOPT_RETURNTRANSFER, 1);
              curl_setopt($ch_session, CURLOPT_POST, 1);
              curl_setopt($ch_session, CURLOPT_POSTFIELDS, $var);
              curl_setopt($ch_session, CURLOPT_HTTPHEADER, array(
              'Content-Type: application/json'
              ));
              curl_setopt($ch_session, CURLOPT_URL, $url);
              $result_url = curl_exec($ch_session);
              if( $fp=fopen('smsmessagesResponse.txt','a+') ){  
                    fwrite($fp, $result_url . "\t" . date ("l dS of F Y h:i:s A")."\n".$result_url."\n" );
                    fclose($fp);
                }
        }
}