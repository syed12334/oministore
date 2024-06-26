
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <title>Omini Store </title>
      <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <meta name="keywords" content="Swarnagowri"/>
    <meta name="description" content="Omini Store">
    <meta name="author" content="Swarnagowri">
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

input[type=number] {
  -moz-appearance: textfield;
}
    </style>

    <!-- Favicon -->


    <!-- WebFont.js -->
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

    <!-- Vendor CSS -->
    <link rel="stylesheet" type="text/css" href="<?= asset_url();?>vendor/fontawesome-free/css/all.min.css">

    <!-- Plugins CSS -->
    <!-- <link rel="stylesheet" href="assets/vendor/swiper/swiper-bundle.min.css"> -->
    <link rel="stylesheet" type="text/css" href="<?= asset_url();?>/vendor/animate/animate.min.css">
    <link rel="stylesheet" type="text/css" href="<?= asset_url();?>/vendor/magnific-popup/magnific-popup.min.css">
    <!-- Link Swiper's CSS -->
    <link rel="stylesheet" href="<?= asset_url();?>/vendor/swiper/swiper-bundle.min.css">

    <!-- Default CSS -->
    <link rel="stylesheet" type="text/css" href="<?= asset_url();?>/css/demo1.min.css">

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
    <input type="hidden" class="csrf_token" name="<?= $this->security->get_csrf_token_name();?>" value="<?= $this->security->get_csrf_hash();?>">
    <div class="page-wrapper">
        <!-- Start of Header -->
        <header class="header">
        
            <!-- End of Header Top -->

            <div class="header-middle" style="padding: 10px!important">
                <div class="container">
                    <div class="header-left mr-md-4">
                        <a href="<?= base_url();?>" class="mobile-menu-toggle  w-icon-hamburger" aria-label="menu-toggle">
                        </a>
                        <a href="javascript:void(0)" class="logo ml-lg-0">
                            <img src="<?= base_url();?>assets/images/logo.png" alt="logo"  />
                        </a>
                      
                    </div>
                    <div class="header-right ml-4">
                       
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
            <!-- End of Header Middle -->

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