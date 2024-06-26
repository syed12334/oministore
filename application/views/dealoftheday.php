<?php //echo "<pre>";print_r($this->cart->contents());exit; ?>
<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">

    <title>Swarnagowri - Dealoftheday </title>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <meta name="keywords" content="Swarnagowri, Flour & Atta, Non Veg Masala, Veg Masala, Masala & Spices, Coffee Powder, Mineral Water" />
    <meta name="description" content="Swarnagowri">
    <meta name="author" content="Swarnagowri">

  <link rel="preload" href="<?= base_url();?>assets/vendor/fontawesome-free/webfonts/fa-regular-400.woff2" as="font" type="font/woff2"
        crossorigin="anonymous">
    <link rel="preload" href="<?= base_url();?>assets/vendor/fontawesome-free/webfonts/fa-solid-900.woff2" as="font" type="font/woff2"
        crossorigin="anonymous">
    <link rel="preload" href="<?= base_url();?>assets/vendor/fontawesome-free/webfonts/fa-brands-400.woff2" as="font" type="font/woff2"
            crossorigin="anonymous">
    <link rel="preload" href="<?= base_url();?>assets/fonts/wolmart87d5.woff?png09e" as="font" type="font/woff" crossorigin="anonymous">

    <!-- Vendor CSS -->
    <link rel="stylesheet" type="text/css" href="<?= base_url();?>assets/vendor/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url();?>assets/vendor/animate/animate.min.css">

    <!-- Plugin CSS -->
    <link rel="stylesheet" type="text/css" href="<?= base_url();?>assets/vendor/magnific-popup/magnific-popup.min.css">
    <link rel="stylesheet" href="<?= base_url();?>assets/vendor/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url();?>assets/vendor/photoswipe/photoswipe.min.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url();?>assets/vendor/photoswipe/default-skin/default-skin.min.css">

    <!-- Default CSS -->
<!--     <link rel="stylesheet" type="text/css" href="<?= base_url();?>assets/css/demo1.min.css">
 -->    <link rel="stylesheet" type="text/css" href="<?= base_url();?>assets/css/style.min.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url();?>assets/css/demo1.min.css">
    <style>
        .btm-br{
            border-bottom: 1px solid #ccc;
            margin-right: 25px;
        }
        .wcolors {
            color:#e358e1 !important;
        }
            input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

input[type=number] {
  -moz-appearance: textfield;
}
.modal {
  display: none; 
  position: fixed; 
  z-index: 100000; 
  padding-top: 100px; 
  left: 0;
  top: 0;
  width: 100%; 
  height: 100%;
  overflow: auto; 
  background-color: rgb(0,0,0); 
  background-color: rgba(0,0,0,0.4);
}


.modal-content {
  background-color: #fefefe;
  margin: auto;
  padding: 20px;
  border: 1px solid #888;
  width: 400px;
}


.close {
  color: #aaaaaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: #000;
  text-decoration: none;
  cursor: pointer;
}
    </style>
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-V7J8NNTDN4"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-V7J8NNTDN4');
</script>
</head>

<body>
    <div class="page-wrapper">

        <!-- Start of Header -->
        <header class="header header-border">
            <div class="header-top">
                <div class="container">

                    <div class="header-right">
                        <a href="<?= base_url().'my-account'; ?>" class="d-lg-show">My Account</a>
                          <?php
                            if($this->session->userdata(CUSTOMER_SESSION)) {
                                ?>
                                <a href="<?= base_url().'login';?>" class="d-lg-show  "><i class="w-icon-account"></i><?= ucfirst(substr($this->session->userdata(CUSTOMER_SESSION)['name'],0,4));?></a>
                                <?php
                            }else {
                                ?>
                                    <a href="<?= base_url().'login';?>" class="d-lg-show  "><i class="w-icon-account"></i>Sign In</a>
                        <span class="delimiter d-lg-show">/</span>
                        <a href="<?= base_url().'register?reg=register';?>" class="ml-0 d-lg-show  ">Register</a>
                                <?php
                            }
                        ?>
                    </div>
                </div>
            </div>
            <!-- End of Header Top -->

            <div class="header-middle">
                <div class="container">
                    <div class="header-left mr-md-4">
                        <a href="#" class="mobile-menu-toggle  w-icon-hamburger" aria-label="menu-toggle">
                        </a>
                        <a href="<?= base_url();?>" class="logo ml-lg-0">
                            <img src="<?= base_url();?>assets/images/logo.png" alt="logo"  />
                        </a>
                        <form method="get" action="<?= base_url().'searchresults';?>"
                            class="header-search hs-expanded hs-round d-none d-md-flex input-wrapper">
                          
                            <input type="text" class="form-control" name="search" id="search" placeholder="Search for Products..."
                                required />
                            <button class="btn btn-search" type="submit"><i class="w-icon-search"></i>
                            </button>
                        </form>
                    </div>
                    <div class="header-right ml-4">
                        <div class="header-call d-xs-show d-lg-flex align-items-center">
                            <a href="tel:#" class="w-icon-call"></a>
                            <div class="call-info d-lg-show">
                                <h4 class="chat font-weight-normal font-size-md text-normal ls-normal text-light mb-0">
                                    <a href="tel:#" class="text-capitalize">Got Question? Call us</a>
                                </h4>
                                <a href="tel:+917026027027" class="phone-number font-weight-bolder ls-50">+91 7026 027027</a>
                            </div>
                        </div>

                        <a class="wishlist label-down link d-xs-show" href="<?= base_url().'my-account?rel=wishlist';?>" style="position: relative;">
                            <i class="w-icon-heart"></i>
                            <span class="wishlist-label d-lg-show">Wishlist</span>
                            <span class="cart-wishlist"><?php if($this->session->userdata(CUSTOMER_SESSION)) {echo count($wishcount);}else {echo '0';}?></span>
                        </a>

                         <div class="dropdown cart-dropdown cart-offcanvas mr-0 mr-lg-2">
                            <div class="cart-overlay"></div>
                            <a href="#" class="cart-toggle label-down link">
                                <i class="w-icon-cart">
                                    <span class="cart-count"><?= count($this->cart->contents());?></span>
                                </i>
                                <span class="cart-label">Cart</span>
                            </a>
                            <div class="dropdown-box" id="appendProducts">
                                <div class="cart-header">
                                    <span>Shopping Cart</span>
                                    <a href="#" class="btn-close">Close<i class="w-icon-long-arrow-right"></i></a>
                                </div>

                                <div class="products" style="border-bottom: 0px!important">
                                    <?php
                                    $subtotal = [];
                                    if(count($this->cart->contents()) >0) {
                                        foreach ($this->cart->contents() as $cart) {
                                            $subtotal[] = $cart['subtotal'];
                                            ?>
                                            <div class="product product-cart">
                                               <div class="product-detail">
                                                 <a href="<?= $cart['plink'];?>" class="product-name"><?= ucfirst($cart['name']).'('.$cart["sname"].')'; ?></a>
                                                 <div class="price-box">
                                                    <span class="product-quantity"><?= "Qty: ".$cart['qty'];?></span>
                                                    <span class="product-price"><i class="fas fa-rupee-sign" style="margin-right: 5px"></i><?= $cart['price'];?></span>
                                                 </div>
                                               </div>
                                               <figure class="product-media">
                                                 <a href="<?= $cart['plink'];?>">
                                                    <img src="<?= $cart['image'];?>" alt="product" />
                                                 </a>
                                                </figure>
                                                <button class="btn btn-link btn-close" aria-label="button" onClick="removeCart('<?= $cart['rowid'];?>',1)"><i class="fas fa-times"></i></button>
                                            </div>
                                            <div class="input-group" style="width:40%">
                                                <input class="form-control" type="number" value="<?= $cart['qty'];?>" id="qtys<?= $cart['rowid']; ?>" readonly>
                                                <button class="w-icon-plus" onclick="addpopqty('<?= $cart['rowid']; ?>')"></button>
                                                <button class="w-icon-minus" onclick="reducepopqty('<?= $cart["rowid"];?>')"></button>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                         </div>
                                         
                                          <p style='font-size:14px;text-align:center;margin-top:10px;font-weight:bold; color:#599d28;'>Buy Products worth Rs.300/- and Get Free Delivery  <span id="savingamount2"> <?php if($this->cart->total() >=299) {echo "Shipping free";}else { $amntlimti =300;echo "Add Rs.".$amntlimti - $this->cart->total().'/- for Free Delivery';}?> </span> </p> 
                                         
                                        <div class="cart-total" style="padding:5px 0px!important;">
                                           <label>Subtotal:</label>
                                           <span class="price"><i class="fas fa-rupee-sign" style="margin-right: 5px"></i><?= number_format($this->cart->total());?></span>
                                        </div>
                                         <?php
                                        $delivercha ="";
                                          if($this->cart->total() >=300) {
                                            $delivercha .= 0;
                                            ?>
                                              <div class="cart-total" style="padding:5px 0px!important;">
                                           <label>Delivery Charges:</label>
                                           <span ><?= "Free Shipping"; ?></span>
                                        </div>
                                         <div class="cart-total" style="padding: 5px 0px!important">
                                           <label>Total Amount :</label>
                                           <span class="price"><i class="fas fa-rupee-sign" style="margin-right: 5px"></i><?= number_format($this->cart->total(),2);?></span>
                                        </div>
                                            <?php
                                          }else {
                                            $delivercha .= 50;
                                            
                                              ?>
                                              <div class="cart-total" style="padding:5px 0px!important;">
                                           <label>Delivery Charges:</label>
                                           <span ><i class="fas fa-rupee-sign" style="margin-right: 5px"></i><?= number_format(50,2);?></span>
                                        </div>
                                         <div class="cart-total" style="padding: 5px 0px!important">
                                           <label>Total Amount :</label>
                                           <span class="price"><i class="fas fa-rupee-sign" style="margin-right: 5px"></i><?= number_format($this->cart->total()+50,2);?></span>
                                        </div>
                                            <?php

                                          }
                                        ?>
                                         
                                        <div class="cart-action">
                                          <a href="<?= base_url();?>" class="btn btn-warning btn-outline-outline btn-rounded">Shop More </a>
                                          <a href="<?php if($this->session->userdata(CUSTOMER_SESSION)) {echo base_url().'checkout';}else {echo base_url().'login';}?>" class="btn btn-dark  btn-rounded">Checkout</a>
                                        </div>
                                        <?php
                                    }else {
                                        ?>
                                         <br /><br /><br /><center><img src='<?= base_url()."assets/images/cart.png" ?>' style='width:230px' /><p style='font-size:16px;text-align:center;margin-top:10px;font-weight:bold'>Your Cart is empty</p></center>
                                        <?php
                                         
                                    }
                                ?>
                               
                            </div>
                            <!-- End of Dropdown Box -->
                        </div>

                    </div>
                </div>
            </div>
            <!-- End of Header Middle -->

            <div class="header-bottom sticky-content fix-top sticky-header has-dropdown">
                <div class="container">
                    <div class="inner-wrap">
                        <div class="header-left">
                            <div class="dropdown category-dropdown has-border" data-visible="true">
                                <a href="#" class="category-toggle text-dark" role="button" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="true" data-display="static"
                                    title="Browse Categories">
                                    <i class="w-icon-category"></i>
                                    <span>Browse Categories</span>
                                </a>

                                <div class="dropdown-box">
                                    <ul class="menu vertical-menu category-menu">
                                            <?php
                                                if(count($category) >0) {
                                                    foreach ($category as $cat) {
                                                        $catid = $cat->cat_id;
                                                        
                                                        ?>
                                            <li>
                                                <a href="<?= base_url().'home/products/'.icchaEncrypt($cat->cat_id);?>">
                                                    <i class="<?= $cat->icons; ?>"></i><?= ucfirst($cat->cname);?>
                                                </a>
                                                
                                         </li>
                                                        <?php
                                                    }
                                                }
                                            ?>
                                        <!-- <li>
                                            <a href="#" class="font-weight-bold text-primary text-uppercase ls-25">
                                                View All Categories<i class="w-icon-angle-right"></i>
                                            </a>
                                        </li> -->
                                    </ul>
                                </div>
                            </div>
                            <nav class="main-nav">
                                <ul class="menu active-underline">
                                    <li >
                                        <a href="<?= base_url(); ?>">Home</a>
                                    </li>
                                   <li class="active">
                                          <a href="<?= base_url().'dealoftheday'; ?>" >Deal of The Day </a>
                                    </li>
                                    <li class="">
                                        <a href="javascript:void(0)" onclick="redirectSpecialoffers()">Special Offers</a>
                                    </li>
                                    
                                    <li class="">
                                        <a href="<?= base_url().'contact'; ?>">Contact Us</a>
                                    </li>

                                </ul>
                            </nav>
                        </div>

                    </div>
                </div>
            </div>
        </header>

<style>
.pa{
	background-image:url("./assets/images/deal_of_day.jpg");
	min-height: 400px;
    background-repeat: no-repeat;
    background-size: cover;	
}
</style>
<main class="main mb-10 pb-1">
       
       <div class="page-header">
                <div class="container">
                    <h1 class="page-title mb-0">Deal of the Day </h1>
                </div>
            </div>
            
            <nav class="breadcrumb-nav">
                <div class="container">
                    <ul class="breadcrumb">
                        <li><a href="<?= base_url();?>">Home</a></li>
                        <li>Deal of the Day </li>
                    </ul>
                </div>
            </nav>
	
    <div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 pa">
            <div class="para">
				
                <p> </p>
		
            </div>
        	</div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <h3 style="text-align: center;">Coming Soon</h3>
            </div>
		</div>
	</div>
</main>
<!-- Start of Footer -->

<style>
.input-wrapper-round .form-control {
    border-radius: 0 0 0 0!important;
    margin-top: 10px;
    border-right:1px solid #ccc;
}
.text-danger {
    color:red;
}

.input-wrapper-round .btn {
     border-radius: 0px!important; 
    margin-top: 5px;
}

.text-light {
    color: #fff !important;
}

.newsletter-popup .input-wrapper-inline .form-control {
    color: #fff;
}

.newsletter-popup h2 {
    color: #fff;
}

.newsletter-content{
    position: relative;
    z-index: 999;
    float: left;
    width: 100%;
}


.msg_de {
    background: #fceabb;
    background: -moz-linear-gradient(left,rgba(252,234,187,1) 0,rgba(252,205,77,1) 50%,rgba(251,223,147,1) 100%);
    background: -webkit-linear-gradient(left,rgba(252,234,187,1) 0,rgba(252,205,77,1) 50%,rgba(251,223,147,1) 100%);
    background: linear-gradient(to right,rgba(252,234,187,1) 0,rgba(252,205,77,1) 50%,rgba(251,223,147,1) 100%);
    position: fixed;
    z-index: 9;
    left: 0;
    bottom: 0;
    width: 100%;
    text-align: center;
    padding: 4px 0;
    color: #000;
}

.msg_de span {
    margin-left: 35px;
    background-color: #599d28;
    padding: 7px 19px;
    color: #fff;
}

.msg_de p {
    margin: 0;
}
</style>
        <footer class="footer appear-animate" data-animation-options="{
            'name': 'fadeIn'
        }">
            <div class="footer-newsletter bg-primary">
                <div class="container">
                    <div class="row justify-content-center align-items-center">
                        <div class="col-xl-5 col-lg-6">
                            <div class="icon-box icon-box-side text-white">
                                <div class="icon-box-icon d-inline-flex">
                                    <i class="w-icon-envelop3"></i>
                                </div>
                                <div class="icon-box-content">
                                    <h4 class="icon-box-title text-white text-uppercase font-weight-bold">Subscribe To
                                        Our Newsletter</h4>
                                    <p class="text-white">Get all the latest information on Events, Sales and Offers.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-7 col-lg-6 col-md-9 mt-4 mt-lg-0 ">
                            <div id="subscriberror"></div>
                            <form method="post" id="subscribetochannel"
                                class="input-wrapper input-wrapper-inline input-wrapper-rounded">
                                <input type="hidden" class="csrf_token" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                                <input type="email" class="form-control mr-2 bg-white" name="email" id="email"
                                    placeholder="Your E-mail Address" />
                                    
                                <button class="btn btn-dark btn-rounded" type="submit">Subscribe<i
                                        class="w-icon-long-arrow-right"></i></button>
                            </form>
                            <span id="subemail_error" class="text-danger"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="footer-top">
                    <div class="row">
                        <div class="col-lg-4 col-sm-6">
                            <div class="widget widget-about">
                                <a href="https://www.swarnagowri.com/" class="logo-footer">
                                    <img src="<?= base_url();?>assets/images/logo_footer.png" alt="logo-footer" width="180px" />
                                </a>
                                <div class="widget-body">
                                    <p class="widget-about-title">Got Question? Call us 24/7</p>
                                    <a href="tel:+917026027027" class="widget-about-call">+91 70260 27027</a>
                                     

                                    <div class="social-icons social-icons-colored">
                                        <a href="https://www.facebook.com/swarnagowriofficial" target="_blank" class="social-icon social-facebook w-icon-facebook"></a>
                                        <a href="https://twitter.com/swarnagowri_com" target="_blank" class="social-icon social-twitter w-icon-twitter"></a>
                                        <a href="https://www.instagram.com/swarnagowriofficial/" target="_blank" class="social-icon social-instagram w-icon-instagram"></a>
                                        <a href="https://www.youtube.com/channel/UCzkjrDQW4f3vCQBr7suEljw" target="_blank" class="social-icon social-youtube w-icon-youtube"></a>
                                        <!--<i class="fa-brands fa-linkedin"></i> <a href="https://www.linkedin.com/in/swarna-gowri-bb627627b/" target="_blank" class="social-icon social-linkedin w-icon-linkedin"></a>-->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="widget">
                                <h3 class="widget-title">Company</h3>
                                <ul class="widget-body">
                                    <li><a href="<?= base_url().'about'; ?>">About Us</a></li>
                                    <li><a href="<?= base_url().'contact'; ?>">Contact Us</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="widget">
                                <h4 class="widget-title">My Account</h4>
                                <ul class="widget-body">
                                 <li><a href="<?= base_url().'my-account';?>">Track My Order</a></li>

                                 <li><a href="<?= base_url().'my-account?rel=wishlist'; ?>">My Wishlist</a></li>
                                    
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="widget">
                                <h4 class="widget-title">Customer Service</h4>
                                <ul class="widget-body">
                                   <!--  <li><a href="#">Payment Methods</a></li>
                                    <li><a href="#">Product Returns</a></li> -->
                                    <li><a href="<?= base_url().'deliverypolicy'; ?>">Delivery and Return Policy</a></li>
                                    <li><a href="<?= base_url().'disclaimer'; ?>">Refund Policy</a></li>
                                    <li><a href="<?= base_url().'terms'; ?>">Terms and Conditions</a></li>
                                    <li><a href="<?= base_url().'privacy'; ?>">Privacy Policy</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="footer-bottom">
                    <div class="footer-left">
                        <p class="copyright">Copyright © 2023 HomeNeeds Cart Pvt Ltd. All Rights Reserved.

                        </p>
                    </div>
                    <div class="footer-right">
                        <span class="payment-label mr-lg-8">We're using safe payment for</span>
                        <figure class="payment">
                            <img src="<?= base_url();?>assets/images/payment.png" alt="payment" width="159" height="25" />
                        </figure>
                    </div>
                </div>
            </div>
        </footer>
        <!-- End of Footer -->
    </div>
    <!-- End of Page-wrapper-->

    <!-- Start of Sticky Footer -->
   
    <!-- End of Sticky Footer -->
 <div class="msg_de">
      <div class="container">  <p>
            Buy Products worth Rs.300/- and Get Free Delivery  <span id="savingamount1"> <?php if($this->cart->total() >=299) {echo "Shipping free";}else { $amntlimti =300;echo "Add Rs.".$amntlimti - $this->cart->total().'/- for Free Delivery';}?> </span>
        </p>
        </div>
    </div>
    <!-- Start of Scroll Top -->
    <a id="scroll-top" class="scroll-top" href="#top" title="Top" role="button"> <i class="w-icon-angle-up"></i> <svg
            version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 70 70">
            <circle id="progress-indicator" fill="transparent" stroke="#000000" stroke-miterlimit="10" cx="35" cy="35"
                r="34" style="stroke-dasharray: 16.4198, 400;"></circle>
        </svg> </a>
    <!-- End of Scroll Top -->

    <!-- Start of Mobile Menu -->
    <div class="mobile-menu-wrapper">
        <div class="mobile-menu-overlay"></div>
        <!-- End of .mobile-menu-overlay -->

        <a href="#" class="mobile-menu-close"><i class="close-icon"></i></a>
        <!-- End of .mobile-menu-close -->

        <div class="mobile-menu-container scrollable">
            <form action="#" method="get" class="input-wrapper">
                <input type="text" class="form-control" name="search" autocomplete="off" placeholder="Search"
                    required />
                <button class="btn btn-search" type="submit">
                    <i class="w-icon-search"></i>
                </button>
            </form>
            <!-- End of Search Form -->
            <div class="tab">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a href="#main-menu" class="nav-link active">Main Menu</a>
                    </li>
                    <li class="nav-item">
                        <a href="#categories" class="nav-link">Categories</a>
                    </li>
                </ul>
            </div>
            
                
                <div class="tab-content">
                <div class="tab-pane active" id="main-menu">
                    <ul class="mobile-menu">
                        <li><a href="<?= base_url(); ?>">Home</a></li>
                        
                          <li><a href="<?= base_url().'dealoftheday'; ?>">Deal of The Day </a></li>
                        <li><a href="javascript:void(0)" onclick="redirectSpecialoffers();">Special Offers</a></li>
                        <li><a href="<?= base_url().'contact'; ?>">Contact Us</a></li>
                    </ul>
                </div>
                <div class="tab-pane" id="categories">
                    <ul class="mobile-menu">
                      <?php
                                                if(count($category) >0) {
                                                    foreach ($category as $cat) {
                                                        $catid = $cat->cat_id;
                                                        ?>
                                            <li>
                                                <a href="<?= base_url().'home/products/'.icchaEncrypt($cat->cat_id);?>">
                                                    <i class="<?= $cat->icons; ?>"></i><?= ucfirst($cat->cname);?>
                                                </a>
                                         </li>
                                                        <?php
                                                    }
                                                }
                                            ?>
                      



                    </ul>
                </div>
            </div>
            </div>
        </div>
    </div>
    <!-- End of Mobile Menu -->

    <!-- Start of Newsletter popup -->
 <!--    <div class="newsletter-popup mfp-hide" style="height: 400px;cursor: pointer" >
        <a class="newsletter-content" href="https://www.swarnagowri.com/rasapaka/" target="_blank">
            
                      
            <div class="form-checkbox d-flex align-items-center " >
                <input type="checkbox" class="custom-checkbox" id="hide-newsletter-popup" name="hide-newsletter-popup"
                    required="">
              
            </div>
        </a>
    </div> -->
  
    <!-- End of Newsletter popup -->

    <!-- Start of Quick View -->
    <div class="product product-single product-popup">
        <div class="row gutter-lg">
            <div class="col-md-6 mb-4 mb-md-0">
                <div class="product-gallery product-gallery-sticky">
                    <div class="swiper-container product-single-swiper swiper-theme nav-inner">
                        <div class="swiper-wrapper row cols-1 gutter-no">
                            <div class="swiper-slide">
                                <figure class="product-image">
                                    <img src="assets/images/products/popup/1-440x494.jpg"
                                        data-zoom-image="assets/images/products/popup/1-800x900.jpg"
                                        alt="Water Boil Black Utensil" width="800" height="900">
                                </figure>
                            </div>
                            <div class="swiper-slide">
                                <figure class="product-image">
                                    <img src="assets/images/products/popup/2-440x494.jpg"
                                        data-zoom-image="assets/images/products/popup/2-800x900.jpg"
                                        alt="Water Boil Black Utensil" width="800" height="900">
                                </figure>
                            </div>
                            <div class="swiper-slide">
                                <figure class="product-image">
                                    <img src="assets/images/products/popup/3-440x494.jpg"
                                        data-zoom-image="assets/images/products/popup/3-800x900.jpg"
                                        alt="Water Boil Black Utensil" width="800" height="900">
                                </figure>
                            </div>
                            <div class="swiper-slide">
                                <figure class="product-image">
                                    <img src="assets/images/products/popup/4-440x494.jpg"
                                        data-zoom-image="assets/images/products/popup/4-800x900.jpg"
                                        alt="Water Boil Black Utensil" width="800" height="900">
                                </figure>
                            </div>
                        </div>
                        <button class="swiper-button-next"></button>
                        <button class="swiper-button-prev"></button>
                    </div>
                    <div class="product-thumbs-wrap swiper-container" data-swiper-options="{
                        'navigation': {
                            'nextEl': '.swiper-button-next',
                            'prevEl': '.swiper-button-prev'
                        }
                    }">
                        <div class="product-thumbs swiper-wrapper row cols-4 gutter-sm">
                            <div class="product-thumb swiper-slide">
                                <img src="assets/images/products/popup/1-103x116.jpg" alt="Product Thumb" width="103"
                                    height="116">
                            </div>
                            <div class="product-thumb swiper-slide">
                                <img src="assets/images/products/popup/2-103x116.jpg" alt="Product Thumb" width="103"
                                    height="116">
                            </div>
                            <div class="product-thumb swiper-slide">
                                <img src="assets/images/products/popup/3-103x116.jpg" alt="Product Thumb" width="103"
                                    height="116">
                            </div>
                            <div class="product-thumb swiper-slide">
                                <img src="assets/images/products/popup/4-103x116.jpg" alt="Product Thumb" width="103"
                                    height="116">
                            </div>
                        </div>
                        <button class="swiper-button-next"></button>
                        <button class="swiper-button-prev"></button>
                    </div>
                </div>
            </div>
            <div class="col-md-6 overflow-hidden p-relative">
                <div class="product-details scrollable pl-0">
                    <h2 class="product-title">Electronics Black Wrist Watch</h2>
                    <div class="product-bm-wrapper">
                        <figure class="brand">
                            <img src="assets/images/products/brand/brand-1.jpg" alt="Brand" width="102" height="48" />
                        </figure>
                        <div class="product-meta">
                            <div class="product-categories">
                                Category:
                                <span class="product-category"><a href="#">Electronics</a></span>
                            </div>
                            <div class="product-sku">
                                SKU: <span>MS46891340</span>
                            </div>
                        </div>
                    </div>

                    <hr class="product-divider">

                    <div class="product-price">$40.00</div>

                    <div class="ratings-container">
                        <div class="ratings-full">
                            <span class="ratings" style="width: 80%;"></span>
                            <span class="tooltiptext tooltip-top"></span>
                        </div>
                        <a href="#" class="rating-reviews">(3 Reviews)</a>
                    </div>

                    <div class="product-short-desc">
                        <ul class="list-type-check list-style-none">
                            <li>Ultrices eros in cursus turpis massa cursus mattis.</li>
                            <li>Volutpat ac tincidunt vitae semper quis lectus.</li>
                            <li>Aliquam id diam maecenas ultricies mi eget mauris.</li>
                        </ul>
                    </div>

                    <hr class="product-divider">

                    <div class="product-form product-variation-form product-color-swatch">
                        <label>Color:</label>
                        <div class="d-flex align-items-center product-variations">
                            <a href="#" class="color" style="background-color: #ffcc01"></a>
                            <a href="#" class="color" style="background-color: #ca6d00;"></a>
                            <a href="#" class="color" style="background-color: #1c93cb;"></a>
                            <a href="#" class="color" style="background-color: #ccc;"></a>
                            <a href="#" class="color" style="background-color: #333;"></a>
                        </div>
                    </div>
                    <div class="product-form product-variation-form product-size-swatch">
                        <label class="mb-1">Size:</label>
                        <div class="flex-wrap d-flex align-items-center product-variations">
                            <a href="#" class="size">Small</a>
                            <a href="#" class="size">Medium</a>
                            <a href="#" class="size">Large</a>
                            <a href="#" class="size">Extra Large</a>
                        </div>
                        <a href="#" class="product-variation-clean">Clean All</a>
                    </div>

                    <div class="product-variation-price">
                        <span></span>
                    </div>

                    <div class="product-form">
                        <div class="product-qty-form">
                            <div class="input-group">
                                <input class="quantity form-control" type="number" min="1" max="10000000">
                                <button class="quantity-plus w-icon-plus"></button>
                                <button class="quantity-minus w-icon-minus"></button>
                            </div>
                        </div>
                        <button class="btn btn-primary btn-cart">
                            <i class="w-icon-cart"></i>
                            <span>Add to Cart</span>
                        </button>
                    </div>

                    <div class="social-links-wrapper">
                        <div class="social-links">
                            <div class="social-icons social-no-color border-thin">
                                <a href="https://www.facebook.com/swarnagowriofficial" target="_blank" class="social-icon social-facebook w-icon-facebook"></a>
                                <a href="https://twitter.com/swarnagowri_com" target="_blank" class="social-icon social-twitter w-icon-twitter"></a>
                                <a href="https://www.instagram.com/swarnagowriofficial/" target="_blank" class="social-icon social-pinterest fab fa-pinterest-p"></a>
                                <a href="https://www.youtube.com/channel/UCzkjrDQW4f3vCQBr7suEljw" target="_blank" class="social-icon social-whatsapp fab fa-whatsapp"></a>
                              <!--  <a href="https://www.linkedin.com/in/swarna-gowri-bb627627b/" target="_blank" class="social-icon social-youtube fab fa-linkedin-in"></a>-->
                            </div>
                        </div>
                        <span class="divider d-xs-show"></span>
                        <div class="product-link-wrapper d-flex">
                            <a href="#" class="btn-product-icon btn-wishlist w-icon-heart"><span></span></a>
                            <a href="#"
                                class="btn-product-icon btn-compare btn-icon-left w-icon-compare"><span></span></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End of Quick view -->




     <!-- Plugin JS File -->
     <script src="<?= base_url();?>assets/vendor/jquery/jquery.min.js"></script>
      <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
         <script src="<?= base_url();?>assets/vendor/sticky/sticky.min.js"></script>

     <script src="<?= base_url();?>assets/vendor/jquery.plugin/jquery.plugin.min.js"></script>
     <script src="<?= base_url();?>assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
     <script src="<?= base_url();?>assets/vendor/zoom/jquery.zoom.js"></script>
     <script src="<?= base_url();?>assets/vendor/jquery.countdown/jquery.countdown.min.js"></script>
     <script src="<?= base_url();?>assets/vendor/magnific-popup/jquery.magnific-popup.min.js"></script>
     <script src="<?= base_url();?>assets/vendor/skrollr/skrollr.min.js"></script>
     <!-- Swiper JS -->
     <script src="<?= base_url();?>assets/vendor/swiper/swiper-bundle.min.js"></script>
     <script src="<?=base_url();?>assets/vendor/photoswipe/photoswipe.min.js"></script>
    <script src="<?=base_url();?>assets/vendor/photoswipe/photoswipe-ui-default.min.js"></script>
     <!-- Main JS -->
     <script src="<?= base_url();?>assets/js/main.min.js"></script>
 <script>
  $( function() {
   
    $( "#search" ).autocomplete({
      source: "<?= base_url().'Home/autocomplete';?>"
    });
  } );
  $(document).ready(function() {
              $(document).on('click',"#ui-id-1 .ui-menu-item",function(e) {
                var text = $(this).children().text();
                window.location.href="<?= base_url().'searchresults?search=';?>"+text;
            });

             
  });
// function redirectPage() {
//     window.location.href="https://www.swarnagowri.com/rasapaka/";
// }

function notifyme() {
    $("#notifymes").toggle();
}

 
  </script>


     
<div id="redirectModal" class="modal">
<div class="modal-content">
    <span class="close redirectclose">&times;</span>
    <form id="checkhomepinspecial" method="post">
         <input type="hidden" class="csrf_token" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
        <input type="hidden" name="pid" id="pincodedata" />
        <input type="hidden" name="psid" id="psid" />
        <input type="hidden" name="sid" id="sid" />
        <div class="input-group">
        <input type="number" name="cpincodes" id="cpincodes"  maxlength="6" minlength="6" pattern="/^-?\d+\.?\d*$/" onkeypress="if(this.value.length==6) return false;" class="form-control" placeholder="Enter Pin Code" required>
        <input type="submit" class="btn btn-primary" value="submit">
        </div>
    </form>
    <?php
        if($this->session->userdata('pincodes')) {
             $cpincode = $this->session->userdata('pincodes');
                 $count = $this->master_db->sqlExecute('select c.noofdays,amount from pincodes p left join cities c on c.id = p.cid where p.pincode='.$cpincode.'');
                 if(count($count) ==0) {
                    ?>
                    <div id="pincode_error" class="text-danger">Shipping is not available to given pincode <?= $cpincode;?></div>
                    <?php
                 }
        }
    ?>
    <div id="pincodes_error"></div>
    <div id="notificationme"></div>

</div>
</div>

</body>



</html>
<?php echo $js; ?>
<script type="text/javascript">
     var redirect = document.getElementById("redirectModal");
var span1 = document.getElementsByClassName("redirectclose")[0];


span1.onclick = function() {
  redirect.style.display = "none";
}
window.onclick = function(event) {
  if (event.target == redirect) {
    redirect.style.display = "none";
  }
}
   function redirectSpecialoffers() {
    redirect.style.display = "block";
   }
   $(document).ready(function() {
        $("#checkhomepinspecial").on("submit",function(e) {
    e.preventDefault();
    var pincode = $("#cpincode").val();
    var formdata =new FormData(this);
    var modal = document.getElementById("redirectModal");
        $.ajax({
          url :"<?= base_url().'Home/checkspecialredirects';?>",
          method :"post",
          dataType:"json",
          data :formdata,
             contentType: false,
            cache: false,
            processData:false,
            success:function(data) {
              $(".csrf_token").val(data.csrf_token);
            if(data.formerror ==false) {
              if(data.pincode_error !='') {
                $("#pincodes_error").html(data.pincode_error).addClass('text-danger');
              }else {
                $("#pincodes_error").html('');
              }
            }
            else if(data.status ==false) {
              $("#pincodes_error").show().html(data.msg);
            }
            else if(data.status ==true) {
              window.location.href="https://www.swarnagowri.com/specialoffers/";
            }
          }
      });
   
      
  });
   });
</script>