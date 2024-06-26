<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <title>Omini Store </title>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <meta name="keywords" content="ICCHHA" />
    <meta name="description"
        content="Omini Store">
    <meta name="author" content="D-THEMES">
    <style>
        .minipopup-area {
            display: none !important;
        }
        .wcolors {
            color:#e358e1 !important;
        }
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
          -webkit-appearance: none;
          margin: 0;
        }
        .category-dropdown {
            display: none!important;
        }

        input[type=number] {
          -moz-appearance: textfield;
        }
    </style>
    <script>
        WebFontConfig = {
            google: { families: ['Poppins:400,500,600,700,800'] }
        };
        (function (d) {
            var wf = d.createElement('script'), s = d.scripts[0];
            wf.src = '<?= asset_url();?>/js/webfont.js';
            wf.async = true;
            s.parentNode.insertBefore(wf, s);
        })(document);
    </script>
    <link rel="preload" href="<?= asset_url();?>/vendor/fontawesome-free/webfonts/fa-regular-400.woff2" as="font" type="font/woff2"
        crossorigin="anonymous">
    <link rel="preload" href="<?= asset_url();?>/vendor/fontawesome-free/webfonts/fa-solid-900.woff2" as="font" type="font/woff2"
        crossorigin="anonymous">
    <link rel="preload" href="<?= asset_url();?>/vendor/fontawesome-free/webfonts/fa-brands-400.woff2" as="font" type="font/woff2"
        crossorigin="anonymous">
    <link rel="preload" href="<?= asset_url();?>/fonts/wolmart87d5.woff?png09e" as="font" type="font/woff" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="<?= asset_url();?>vendor/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="<?= asset_url();?>/vendor/animate/animate.min.css">
    <link rel="stylesheet" type="text/css" href="<?= asset_url();?>/vendor/magnific-popup/magnific-popup.min.css">
    <link rel="stylesheet" href="<?= asset_url();?>/vendor/swiper/swiper-bundle.min.css">

    <link rel="stylesheet" type="text/css" href="<?= asset_url();?>/css/demo1.min.css">
</head>

<body class="home">
    <input type="hidden" class="csrf_token" name="<?= $this->security->get_csrf_token_name();?>" value="<?= $this->security->get_csrf_hash();?>">
    <div class="page-wrapper">
      
        <header class="header">
            <div class="header-top">
                <div class="container">

                    <div class="header-right">
                        <a href="https://www.iampl.store/" class="main_s">Go to Main Site</a>
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
                        <?php 
                          if($this->session->userdata(CUSTOMER_SESSION)) {
                              if(count($wallet) >0) {
                                ?>
                                   <a href="<?= base_url().'wallet'; ?>" class="d-lg-show">Wallet</a>
                                 <?php
                               
                              }
                          }
                        ?>
                        
                    </div>
                </div>
            </div>
            

            <div class="header-middle">
                <div class="container">
                    <div class="header-left mr-md-4">
                        <a href="<?= base_url();?>" class="mobile-menu-toggle  w-icon-hamburger" aria-label="menu-toggle">
                        </a>
                        <a href="<?= base_url();?>" class="logo ml-lg-0">
                            <img src="<?= base_url();?>assets/images/logo.png" alt="logo"  />
                        </a>
                        <form method="get" action="<?= base_url().'searchresults';?>"
                            class="header-search hs-expanded hs-round d-none d-md-flex input-wrapper">
                     
                            <input type="text" class="form-control" name="search" id="search" placeholder="Search in..."
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
                                <a href="tel:+919902002111" class="phone-number font-weight-bolder ls-50">+91 99020 02111</a>
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
                                                <button class="btn btn-link btn-close" aria-label="button" onClick="removeCart('<?= $cart['rowid'];?>',1)" style="display: block!important;"><i class="fas fa-times"></i></button>
                                            </div>
                                            <div class="input-group" style="width:40%">
                                                <input class="form-control" type="number" value="<?= $cart['qty'];?>" id="qtys<?= $cart['rowid']; ?>">
                                                <button class="w-icon-plus" onclick="addpopqty('<?= $cart['rowid']; ?>')"></button>
                                                <button class="w-icon-minus" onclick="reducepopqty('<?= $cart["rowid"];?>')"></button>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                         </div>
                                         
                                        
                                       
                                         <div class="msg_de">
                <p>
                    Buy Products worth Rs.300/- and Get Free Delivery  <span id="savingamount"> <?php if($this->cart->total() >=299) {echo "Shipping free";}else { $amntlimti =300;echo "Add Rs. ".$amntlimti - $this->cart->total().' for Free Delivery';}?> </span>
                </p> 
            </div>
                                       
                                         <div class="cart-total" style="padding:5px 0px!important;">

                                        
                                        
                                           <label>Sub Total:</label>
                                           <span class="price"><i class="fas fa-rupee-sign" style="margin-right: 5px"></i><?= number_format($this->cart->total());?></span>
                                        </div>
                                         <?php
                                          if($this->cart->total() >=300) {
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
                                           
                                              ?>
                                              <div class="cart-total" style="padding:5px 0px!important;">
                                           <label>Delivery Charges:</label>
                                           <span ><i class="fas fa-rupee-sign" style="margin-right: 5px"></i><?= 50;?></span>
                                        </div>
                                        <div class="cart-total" style="padding: 5px 0px!important">
                                           <label>Total Amount :</label>
                                           <span class="price"><i class="fas fa-rupee-sign" style="margin-right: 5px"></i><?= number_format($this->cart->total()+(int)50,2);?></span>
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
                           
                        </div>
                    </div>
                </div>
            </div>
          

                     <div class="header-bottom sticky-content fix-top sticky-header has-dropdown">
                <div class="container">
                    <div class="inner-wrap">
                        <div class="header-left">
                           
                                <ul class="menu active-underline">
                                    <li class="active">
                                        <a href="<?= base_url(); ?>">Home</a>
                                    </li>
                                   <li class="">
                                          <a href="<?= base_url().'dealoftheday'; ?>">Deal of The Day </a>
                                    </li>
                                  
                                   
                                    <li class="">
                                        <a href="<?= base_url().'contact'; ?>">Contact Us</a>
                                    </li>

                                </ul>
                        </div>

                    </div>
                </div>
            </div>
        </header>