<?php //echo "<pre>";print_r($this->cart->contents()); exit; ?>

<!DOCTYPE html>
<html lang="en">



<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">

    <title>Guest Order </title>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <meta name="keywords" content="Swarnagowri, Flour & Atta, Non Veg Masala, Veg Masala, Masala & Spices, Coffee Powder, Mineral Water" />
   <meta name="description" content="Swarnagowri">
    <meta name="author" content="Swarnagowri">

    <!-- Favicon -->
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-TMV78QMB7E"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-TMV78QMB7E');
</script>

    <!-- WebFont.js -->
    <script>
        WebFontConfig = {
            google: { families: ['Poppins:400,500,600,700'] }
        };
        (function (d) {
            var wf = d.createElement('script'), s = d.scripts[0];
            wf.src = '<?= base_url();?>assets/js/webfont.js';
            wf.async = true;
            s.parentNode.insertBefore(wf, s);
        })(document);
    </script>
<link rel="stylesheet" type="text/css" href="<?= base_url();?>assets/vendor/fontawesome-free/css/all.min.css">
    <link rel="preload" href="<?= base_url();?>assets/vendor/fontawesome-free/webfonts/fa-regular-400.woff2" as="font" type="font/woff2"
        crossorigin="anonymous">
    <link rel="preload" href="<?= base_url();?>assets/vendor/fontawesome-free/webfonts/fa-solid-900.woff2" as="font" type="font/woff2"
        crossorigin="anonymous">
    <link rel="preload" href="<?= base_url();?>assets/vendor/fontawesome-free/webfonts/fa-brands-400.woff2" as="font" type="font/woff2"
        crossorigin="anonymous">
    <link rel="preload" href="<?= base_url();?>assets/fonts/wolmart87d5.woff?png09e" as="font" type="font/woff" crossorigin="anonymous">

    <!-- Vendor CSS -->
    

    <!-- Plugin CSS -->
    <link rel="stylesheet" type="text/css" href="<?= base_url();?>assets/vendor/magnific-popup/magnific-popup.min.css">

    <!-- Default CSS -->
    <link rel="stylesheet" type="text/css" href="<?= base_url();?>assets/css/style.min.css">
       <link rel="stylesheet" type="text/css" href="<?= base_url();?>assets/css/demo1.min.css">
    <style type="text/css">
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

.cart-dropdown .products {
    height: 57vh!important;
}

.dropdown-box .msg_de {
    position: initial;
    border-radius: 5px;
    text-align: center;
    margin-bottom: 20px;
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

.dropdown-box .msg_de p {
    font-size: 11px;
}
.msg_de p {
    margin: 0;
}

.dropdown-box .msg_de #savingamount2 {
    margin: 0;
    padding: 7px;
}
.msg_de span {
    margin-left: 35px;
    background-color: #599d28;
    padding: 7px 19px;
    color: #fff;
}
    </style>

    <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-XC42SRQQ0L"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-XC42SRQQ0L');
</script>
</head>

<body>
    <div class="page-wrapper">

        <!-- Start of Header -->
        <header class="header">
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
                        <a href="<?= base_url();?>" class="mobile-menu-toggle  w-icon-hamburger" aria-label="menu-toggle">
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
                                           <span class="price"><i class="fas fa-rupee-sign" style="margin-right: 5px"></i><?= number_format($this->cart->total() ,2);?></span>
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
                                                        $getSubcategory = $this->master_db->getRecords('subcategory',['status'=>0,'cat_id'=>$catid],'sname,id as subid,page_url','sname asc');
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
                                    <li class="">
                                        <a href="<?= base_url().'dealoftheday'; ?>">Deal of The Day </a>
                                    </li>
                                    <li class="active">
                                        <a href="javascript:void(0)">Special Offers</a>
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
<style type="text/css">
    #shipped-container {
        display: none;
    }
    #discount {
        display: none;
    }
    .checkout .form-control {
        margin-bottom: 10px!important
    }
    label {
        cursor: pointer!important
    }
    #charges {
        display: none;
    }
  
   #pincode_error {
    display: none!important;
  }
</style>
<style type="text/css">
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

input[type=number] {
  -moz-appearance: textfield;
}

.ab{
   margin-top:25px; 
}

</style>
 <!-- Start of Main -->
        <main class="main checkout">
        
        <!-- Start of Page Header -->
            <div class="page-header">
                <div class="container">
                    <h1 class="page-title mb-0">Guest </h1>
                </div>
            </div>
            <!-- End of Page Header -->
            
            <!-- Start of Breadcrumb -->
            <nav class="breadcrumb-nav">
                <div class="container">
                    <ul class="breadcrumb shop-breadcrumb bb-no">
                        <li class="passed"><a href="<?= base_url().'cart'; ?>">Shopping Cart</a></li>
                        <li><a href="<?= base_url().'guest'; ?>">Order Complete</a></li>
                    </ul>
                </div>
            </nav>
            <!-- End of Breadcrumb -->


            <!-- Start of PageContent -->
            <div class="page-content">
                <div class="container">


                    <div id="processing"></div>

                    <form id="guestcheckout" method="post" enctype="multipart/form-data">
                        <input type="hidden" class="csrf_token" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                        <div class="row mb-9">
                            <div class="col-lg-7 pr-lg-4 mb-4">
                                <h3 class="title billing-title text-uppercase ls-10 pt-1 pb-3 mb-0">
                                    Billing Details
                                </h3>
                                <div class="row gutter-sm">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="form-group">
                                            <label>Billing Name <font style="color:red">*</font></label>
                                            <input type="text" class="form-control form-control-md" name="bfname"
                                                id="bfname" placeholder="Eg:John">
                                                <span id="bfname_error" class="text-danger"></span>
                                        </div>
                                    </div>
                                   
                                    <div class="clearfix"></div>
                                     <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                        <div class="form-group">
                                            <label>Billing Email <font style="color:red">*</font></label>
                                            <input type="email" class="form-control form-control-md" name="bemail"
                                                id="bemail" placeholder="Eg:johndoe@example.com" >
                                                 <span id="email_error" class="text-danger"></span>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                        <div class="form-group">
                                            <label>Billing Mobile Number <font style="color:red">*</font></label>
                                            <input type="number" class="form-control form-control-md" name="bphone"
                                                id="bphone" placeholder="Eg:9988776655" maxlength="10" minlength="10" pattern="/^-?\d+\.?\d*$/" onkeypress="if(this.value.length==10) return false;">
                                                 <span id="bphone_error" class="text-danger"></span>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                     <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                        <div class="form-group">
                                            <label>Billing Pincode <font style="color:red">*</font></label>
                                            <input type="number" class="form-control form-control-md" name="bpincode" id="bpincode" placeholder="Eg:560043" min="0" maxlength="6" minlength="6" pattern="/^-?\d+\.?\d*$/" onkeypress="if(this.value.length==6) return false;"  onkeyup="return pincodeCheck(this.value,1)" >
                                                 <span id="bpincode_error" class="text-danger"></span>
                                                 <span id="pincode_error" class="text-danger"></span>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                        <div class="form-group">
                                            <label>Billing State <font style="color:red">*</font></label>
                                           <select name="bstate" id="bstate" class="form-control form-control-md" onchange="return state(this.value)">
                                               <option value="">Select State</option>
                                               <?php
                                                    if(count($states) >0) {
                                                        foreach ($states as $state) {
                                                            ?>
                                                            <option value="<?= $state->id;?>"><?= $state->name;?></option>
                                                            <?php
                                                        }
                                                    }
                                               ?>
                                           </select>
                                                 <span id="bstate_error" class="text-danger"></span>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                        <div class="form-group">
                                            <label>Billing City <font style="color:red">*</font></label>
                                           <select name="bcity" id="bcity" class="form-control form-control-md" onchange="return city(this.value)">
                                               <option value="">Select City</option>
                                               <?php
                                                    if(count($cities) >0) {
                                                        foreach ($cities as $city) {
                                                            ?>
                                                            <option value="<?= $city->id;?>"><?= $city->cname;?></option>
                                                            <?php
                                                        }
                                                    }
                                               ?>
                                           </select>
                                                 <span id="bcity_error" class="text-danger"></span>
                                        </div>
                                    </div>
                                     <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                       <!--  <div class="form-group">
                                            <label>Billing Area <font style="color:red">*</font></label>
                                           <select name="barea" id="barea" class="form-control form-control-md">
                                               <option value="">Select Area</option>
                                               <?php
                                                    if(count($area) >0) {
                                                        foreach ($area as $are) {
                                                            ?>
                                                            <option value="<?= $are->id;?>"><?= $are->areaname;?></option>
                                                            <?php
                                                        }
                                                    }
                                               ?>
                                           </select>
                                                 <span id="barea_error" class="text-danger"></span>
                                        </div> -->
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="form-group">
                                            <label>Billing Street Address <font style="color:red">*</font></label>
                                            <textarea cols="4" rows="4" class="form-control" name="baddress" id="baddress" placeholder="Address"></textarea>
                                        </div>
                                        <span id="baddress_error" class="text-danger" style="color:red"></span>
                                    </div>
                                     <div class="clearfix"></div>
                                </div>
                              
                                <div class="form-group pb-2">
                                    <input type="checkbox" 
                                        name="shipped" class="shipped" id="shipped" value="1">
                                     <label for="shipped" style="cursor: pointer">Ship to a different address?</label> 
                                </div>
                                <div id="shipped-container">
                                   <div class="row gutter-sm">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="form-group">
                                            <label>Shipping Name <font style="color:red">*</font></label>
                                            <input type="text" class="form-control form-control-md" name="sfname"
                                                id="sfname" placeholder="Eg:John">
                                                <span id="sfname_error" class="text-danger"></span>
                                        </div>
                                    </div>
                                    
                                    <div class="clearfix"></div>
                                     <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                        <div class="form-group">
                                            <label>Shipping Email <font style="color:red">*</font></label>
                                            <input type="email" class="form-control form-control-md" name="semail"
                                                id="semail" placeholder="Eg:johndoe@example.com">
                                                 <span id="semail_error" class="text-danger"></span>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                        <div class="form-group">
                                            <label>Shipping Mobile Number <font style="color:red">*</font></label>
                                            <input type="number" class="form-control form-control-md" name="sphone"
                                                id="sphone" placeholder="Eg:9988776655" maxlength="10" minlength="10" pattern="/^-?\d+\.?\d*$/" onkeypress="if(this.value.length==10) return false;">
                                                 <span id="sphone_error" class="text-danger"></span>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                     <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                        <div class="form-group">
                                            <label>Shipping Pincode <font style="color:red">*</font></label>
                                            <input type="number" class="form-control form-control-md" name="spincode" id="spincode" placeholder="Eg:560043" maxlength="6" minlength="6" pattern="/^-?\d+\.?\d*$/" onkeypress="if(this.value.length==6) return false;" onkeyup="return pincodeCheck(this.value,2)">
                                                 <span id="spincode_error" class="text-danger"></span>

                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                        <div class="form-group">
                                            <label>Shipping State <font style="color:red">*</font></label>
                                           <select name="sstate" id="sstate" class="form-control form-control-md" onchange="return shippingstate(this.value)">
                                               <option value="">Select State</option>
                                               <?php
                                                    if(count($states) >0) {
                                                        foreach ($states as $state) {
                                                            ?>
                                                            <option value="<?= $state->id;?>"><?= $state->name;?></option>
                                                            <?php
                                                        }
                                                    }
                                               ?>
                                           </select>
                                                 <span id="sstate_error" class="text-danger"></span>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                        <div class="form-group">
                                            <label>Shipping City <font style="color:red">*</font></label>
                                           <select name="scity" id="scity" class="form-control form-control-md" onchange="return shippingcity(this.value)">
                                               <option value="">Select City</option>
                                               <?php
                                                    if(count($cities) >0) {
                                                        foreach ($cities as $city) {
                                                            ?>
                                                            <option value="<?= $city->id;?>"><?= $city->cname;?></option>
                                                            <?php
                                                        }
                                                    }
                                               ?>
                                           </select>
                                                 <span id="scity_error" class="text-danger"></span>
                                        </div>
                                    </div>
                                     <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                        
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="form-group">
                                            <label>Shipping Street Address <font style="color:red">*</font></label>
                                            <textarea cols="4" rows="4" class="form-control" name="saddress" id="saddress" placeholder="Address"></textarea>
                                            <span id="saddress_error" class="text-danger"></span>
                                        </div>
                                    </div>
                                     <div class="clearfix"></div>
                                </div>
                                </div>

                               
                            </div>
                            <div class="col-lg-5 mb-4 sticky-sidebar-wrapper">
                                <div class="order-summary-wrapper sticky-sidebar">
                                    <h3 class="title text-uppercase ls-10">Your Order</h3>
                                    <div class="order-summary">
                                        <table class="order-table">
                                            <thead>
                                                <tr>
                                                    <th colspan="2">
                                                        <b>Product</b>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                if(is_array($this->cart->contents()) && !empty($this->cart->contents())) {
                                                          if(count($this->cart->contents()) >0) {
                                                        $subtotal =[];
                                                        foreach ($this->cart->contents() as $cart) {
                                                            $subtotal[] = $cart['subtotal'];
                                                           
                                                            ?>
                                                                <tr class="bb-no">
                                                                    <td class="product-name"><a href="<?= base_url().'products/'.$cart['purl'];?>"><img src="<?= $cart['image'];?>" style="width:100px;"/><?= ucfirst($cart['name']);?></a> <i
                                                                            class="fas fa-times"></i> <span
                                                                            class="product-quantity"><?= $cart['qty'];?></span></td>
                                                                    <td class="product-total"><i class="fas fa-rupee-sign"></i> <?= number_format($cart['subtotal']);?></td>
                                                                </tr>
                                                            <?php
                                                        }
                                                    }
                                                }
                                                  
                                                ?>
                                                
                                             

                                                 <tr class="order-total">
                                                    <td style="text-align: left;">If you have a coupon code, please
                                                        apply it below.</td>
                                                </tr>
                                                <tr>
                                                    <td class="input-wrapper-inline">
                                                        <input type="text" name="couponcode"
                                                            class="form-control-md mr-1 mb-2"
                                                            placeholder="Coupon code" id="couponcode">
                                                        <button type="button" class="btn button btn-rounded btn-coupon mb-2"
                                                            value="Apply coupon" id="checkoutCoupon">Apply
                                                            Coupon</button>
                                                            <br />
                                                     
                                                    </td>
                                                </tr>
                                                 <tr>
                                                    <td colspan="2">
                                                          <div id="coupon_error" class="text-left"></div>
                                                    </td>
                                                </tr>
                                                <?php
$delivertext ="";$delamount ="";
     if($this->cart->total() >=300) {
                        $delivertext  .= "Free Delivery";
                        $delamount .=0;
                }else {
                         $delivertext  .= "<i class='fas fa-rupee-sign'></i> 50" ;
                         $delamount .=50;
                }
?>
                                                <tr class="cart-subtotal bb-no">
                                                    <td>
                                                        <b>Sub Total</b>
                                                    </td>
                                                    
                                                    
                                                    <td style="width: 50%">
                                                        <b> <?php if(is_array($this->cart->contents()) && !empty($this->cart->contents())) { echo '<i class="fas fa-rupee-sign"></i> '. array_sum($subtotal); } ?></b>
                                                    </td>
                                                </tr>

                                              

                                             <tr id="discount" class="cart-subtotal bb-no" style="<?php if($this->session->userdata('discount')) {echo 'display: table-row!important';}?>">
                                                    <td><b>Voucher Applied  </b></td>
                                                    <?php
                                                     $totalss = $this->cart->total() + $delamount;
                                                      if($this->session->userdata('discount_type') ==0) {
                                                        ?>
                                                        <td><b id="disamount">-<i class="fas fa-rupee-sign"></i><?= " ".$this->session->userdata('discount');?></b></td>
                                                        <?php
                                                      }else if($this->session->userdata('discount_type') ==1) {
                                                        ?>
                                                        <td ><b id="disamount"><?php if($this->session->userdata('discount') ==100) {
                                                          echo '<i class="fas fa-rupee-sign"></i> -'.$totalss;
                                                        }else {
                                                          echo '<i class="fas fa-rupee-sign"></i> '.$this->session->userdata('discount');
                                                        } ?></b></td>
                                                        <?php
                                                      }
                                                       else if($this->session->userdata('discount_type') ==2) {
                                                        ?>
                                                        <td><b id="disamount">-<i class="fas fa-rupee-sign"></i><?= " ".$this->session->userdata('discount');?></b></td>
                                                        <?php
                                                      }
                                                    ?>
                                                
                                            </tr>

                                          

                                         
                                            
<?php
$delivertext ="";$delamount ="";
     if($this->cart->total() >=300) {
                        $delivertext  .= "Free Delivery";
                        $delamount .=0;
                }else {
                         $delivertext  .= "<i class='fas fa-rupee-sign'></i> 50" ;
                         $delamount .=50;
                }
?>
      


                                                    <tr class="cart-subtotal bb-no">
                                                    <td><b>Delivery Charges </b></td>
                                                <td ><b id="delcharges"><?= $delivertext; ?></b></td>
                                            </tr>

                                            </tbody>
                                            <tfoot>

                                               <tr class="order-total">
                                                    
                                                    <td><b>Total</b> </td>
                                                  <td id="totalamtsd">
                                                        <b> <?php 
                                                        $totals = $this->cart->total() + $delamount;
                          if(is_array($this->cart->contents()) && !empty($this->cart->contents())) {
                              if($this->session->userdata('discount')) {
                                if($this->session->userdata('discount_type') ==0) {
                                  $newamu = floatval( $this->cart->total() + $delamount) - $this->session->userdata('discount');
                                  echo '<i class="fas fa-rupee-sign"></i> '.$newamu;

                              }else if($this->session->userdata('discount_type') ==1) {
                                  $newamus = floatval($this->cart->total() + $delamount) * $this->session->userdata('discount') / 100;
                                  
                                    echo '<i class="fas fa-rupee-sign"></i> '.$totals - $newamus;
                                                                   
                              }else if($this->session->userdata('discount_type') ==2) {
                                $newamu = floatval( $this->cart->total() + $delamount) - $this->session->userdata('discount');
                                  echo '<i class="fas fa-rupee-sign"></i> '.$newamu;
                              }
                            }else {
                                echo '<i class="fas fa-rupee-sign"></i><span id="totalAmount">'. sprintf("%.2f", $this->cart->total() + $delamount) .'<div id="pincodeAm"></div></span>';
                            }
                                                          
                              }  ?></b>
                                                    </td>
                                                </tr>



                                            </tfoot>
                                        </table>

                                        <div class="payment-methods" id="payment_method">
                                            <h4 class="title font-weight-bold ls-25 pb-0 mb-1">Payment Methods</h4>
                                            <div class="accordion payment-accordion">

                                                <div class="card p-relative">
                                                    <span id="pmode_error" class="text-danger"></span>
                                                   <div class="card-header">
                                                        <input type="radio" name="pmode" id="pmode" value="1">
                                                        <label for="pmode">Pay Online</label>
                                                    </div>
                                             <div class="card-header">
                                                        <input type="radio" name="pmode" id="pmodes" value="2">
                                                        <label for="pmodes">COD</label>
                                                    </div>  
                                                    <div id="paypal" class="card-body collapsed">
                                                    </div>
                                                    
                                                     <div class="card p-relative ab">
                                                    <span id="pmode_error" class="text-danger"></span>
                                                   <div class="card-header">
                                                        
                                                    </div>
                                                    
                                                   <!-- <p> <span id="terms_error" class="text-danger"></span> <input type="checkbox" name="terms" id="terms" value="1">
                                                        <a href="https://www.swarnagowri.com/rasapaka/home/terms">
                                                        Terms & Conditions </a> </p>
                                                     <div class="card-header">
                                                        <input type="radio" name="pmode" id="pmodes" value="2">
                                                        <label for="pmodes">COD</label>
                                                    </div> -->

                                                    <div id="paypal" class="card-body collapsed">
                                                    </div>
                                                    
                                                    
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group place-order pt-6">
                                            <input type="submit" id="checksubmit"  class="btn btn-dark btn-block btn-rounded" value="Place
                                                Order">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- End of PageContent -->
        </main>
<?= $footer;?>
<?= $js;?>