<?= $header1;?>
<style type="text/css">
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
.mfp-bg {
    display: none!important;
}
input[type=number] {
  -moz-appearance: textfield;
}
.newsletter-popup {
      display: none!important
    }
</style>
<div id="processing"></div>
  <main class="main login-page">
           
            <!-- Start of Page Header -->
            <div class="page-header">
                <div class="container">
                    <h1 class="page-title mb-0">Login Your Account </h1>
                </div>
            </div>
            <!-- End of Page Header -->
            
            <!-- End of Breadcrumb -->
            <div class="page-content">
                <div class="container">
                    <div class="login-popup">
                        <div class="tab tab-nav-boxed tab-nav-center tab-nav-underline">
                            <ul class="nav nav-tabs text-uppercase" role="tablist">
                                <li class="nav-item ">
                                    <a href="#sign-in" class="nav-link <?php if(@$_GET['reg'] !='register') {echo 'active in';}?>">Login </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#sign-up" class="nav-link <?php if(@$_GET['reg'] =='register') {echo 'active';} ?>">Register</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane <?php if(@$_GET['reg'] !='register') {echo 'active in';}?>" id="sign-in">
                                	<form id="login" method="post" enctype="multipart/form-data">
                                		 <input type="hidden" class="csrf_token" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
	                                    <div class="form-group" style="margin-bottom: 0px">
	                                        <label>Enter Mobile Number / Email </label>
	                                        <input type="text" class="form-control" name="email" id="email" placeholder="Please Enter Mobile Number / Email " autocomplete="off">
	                                    </div>
	                                    <span id="emails_error" class="text-danger"></span>
	                                    
	                                    
	                                    <button type="submit" class="btn btn-dark" style="margin-top: 10px;float: left">Continue</button>
                                        <a href="<?= base_url().'guest';?>" class="btn btn-danger" style="width:60%;float: right;margin-top:10px;font-size: 13px">Checkout as Guest</a>
                                	</form>
                                </div>
                                <div class="tab-pane <?php if(@$_GET['reg'] =='register') {echo 'active in';}else {echo '';}?>" id="sign-up">
                                	<form id="register" method="post" enctype="multipart/form-data">
                                		  <input type="hidden" class="csrf_token" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
	                                	<div class="form-group">
	                                        <label>Name</label>
	                                        <input type="text" class="form-control" name="name" id="name" placeholder="Eg: Your Name" autocomplete="off">
	                                        <span id="name_error" class="text-danger"></span>
	                                    </div>
	                                    <div class="form-group">
	                                        <label>Email Address</label>
	                                        <input type="email" class="form-control" name="emailid" id="emailid" placeholder="Eg: Your Email" autocomplete="off">
	                                        <span id="email_error" class="text-danger" ></span>
	                                    </div>
	                                    <div class="form-group mb-5">
	                                        <label>Phone Number</label>
	                                        <input type="number" class="form-control" id="phone" name="phone" maxlength="10" min="0" minlength="10" onkeypress="if(this.value.length==10) return false;" placeholder="Eg:9999999999" autocomplete="off">
	                                        <span id="phone_error" class="text-danger"></span>
	                                    </div>
	                               <!--      <div class="form-group mb-5">
	                                        <label>Password</label>
	                                        <input type="password" class="form-control" name="password" id="password" placeholder="Enter password" maxlength="12" autocomplete="off">
	                                        <span id="password_error" class="text-danger"></span>
	                                    </div>
	                                    <div class="form-group mb-5">
	                                        <label>Confirem Password</label>
	                                        <input type="password" class="form-control" name="cpassword" id="cpassword" placeholder="Enter confirm password" maxlength="12" autocomplete="off">
	                                        <span id="cpassword_error" class="text-danger"></span>
	                                    </div> -->
	                                    <button type="submit" class="btn btn-primary">Submit</button>
                                	</form>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <input type="hidden" class="csrf_token" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
<?= $footer;?>
<?= $js;?>
