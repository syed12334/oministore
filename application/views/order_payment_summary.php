
<!DOCTYPE html>
<html lang="en">



<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">

    <title>Swarnagowri - Order Summary </title>

    <meta name="keywords" content="Marketplace ecommerce responsive HTML5 Template" />
    <meta name="description"
        content="Swarnagowri">
    <meta name="author" content="D-THEMES">

    <!-- Favicon -->


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
    <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-8HMH7TSNL4"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-8HMH7TSNL4');
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
    </style>
</head>

<body>

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
                        <form method="get" action="#"
                            class="header-search hs-expanded hs-round d-none d-md-flex input-wrapper">
                          <!--   <div class="select-box">
                               <select id="category" name="category">
                                    <option value="14">All Categories</option>
                                    <?php
                                        if(count($category) >0) {
                                            foreach ($category as $cat) {
                                                ?>
                                                    <option value="<?= $cat->cat_id; ?>"><?= $cat->cname;?></option>
                                                <?php
                                            }
                                        }
                                    ?>
                                </select>
                            </div> -->
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
                                                    <span class="product-quantity"><?= $cart['qty'];?></span>
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
                                                <input class="form-control" type="number" value="<?= $cart['qty'];?>" id="qtys<?= $cart['rowid']; ?>">
                                                <button class="w-icon-plus" onclick="addpopqty('<?= $cart['rowid']; ?>')"></button>
                                                <button class="w-icon-minus" onclick="reducepopqty('<?= $cart["rowid"];?>')"></button>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                         </div>
                                        <div class="cart-total" style="padding:5px 0px!important;">
                                           <label>Sub Total:</label>
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
                                            <?php
                                          }else {
                                            $delivercha .= 50;
                                            
                                              ?>
                                              <div class="cart-total" style="padding:5px 0px!important;">
                                           <label>Delivery Charges:</label>
                                           <span ><i class="fas fa-rupee-sign" style="margin-right: 5px"></i><?= number_format(50,2);?></span>
                                        </div>
                                            <?php

                                          }
                                        ?>
                                          <div class="cart-total" style="padding: 5px 0px!important">
                                           <label>Total Amount :</label>
                                           <span class="price"><i class="fas fa-rupee-sign" style="margin-right: 5px"></i><?= number_format($this->cart->total()+$delivercha,2);?></span>
                                        </div>
                                        <div class="cart-action" style="position: absolute;bottom: 100px; width: 90%"> 
                                          <a href="<?= base_url();?>" class="btn btn-warning btn-outline-outline btn-rounded">Continue Shopping</a>
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
                                    <li class="active">
                                        <a href="<?= base_url(); ?>">Home</a>
                                    </li>
                                    <li class="">
                                          <a href="<?= base_url().'dealoftheday'; ?>">Deal of The Day </a>
                                    </li>
                                    <li class="">
                                        <a href="<?= base_url().'specialoffers'; ?>">Special Offers</a>
                                    </li>
                                    <!-- <li class="">
                                        <a href="#">Careers</a>
                                    </li> -->
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
    @media print {
         header {
            display: none!important;
         }
         .breadcrumb-nav {
            display: none!important;
         }
         .btn-back {
            display: none!important;
         }
         footer {
            display: none!important;
         }
      }
</style>
 <main class="main order">
 
  <!-- Start of Page Header -->
            <div class="page-header">
                <div class="container">
                    <h1 class="page-title mb-0">Order Summary </h1>
                </div>
            </div>
            <!-- End of Page Header -->
            
            <!-- Start of Breadcrumb -->
            <nav class="breadcrumb-nav">
                <div class="container">
                    <ul class="breadcrumb shop-breadcrumb bb-no mt-0 mb-0">
                        <!-- <li class="passed"><a href="<?= base_url().'cart';?>">Shopping Cart</a></li>
                        <li class="passed"><a href="<?= base_url().'checkout';?>">Checkout</a></li> -->
                        <li class="active"><a href="">Order Complete</a></li>
                    </ul>
                </div>
            </nav>
            <!-- End of Breadcrumb -->

            <!-- Start of PageContent -->
            <div class="page-content mb-10 pb-2">
                <div class="container">
                    <div class="order-success text-center font-weight-bolder text-dark pt-1 pb-1">
                        <i class="fas fa-check"></i>
                        Thank you. Your order has been received.
                        <br />
                       <!-- <span style="font-size: 16px!important;">(Within 24hrs or at the time of delivery)</span>-->
                    </div>
                    <!-- End of Order Success -->
                   
                            <ul class="order-view list-style-none mt-2 mb-2">
                                 <?php 
                        if(count($orders) >0) {
                            ?>
                                <li>
                                    <label>Order number</label>
                                    <strong><?= $orders[0]->orderid;?></strong>
                                </li>
                                <li>
                                    <label>Status</label>
                                    <strong>Order Placed</strong>
                                </li>
                                <li>
                                    <label>Date</label>
                                    <strong><?= date('d-m-Y',strtotime($orders[0]->order_date));?></strong>
                                </li>
                                <li>
                                    <label>Total</label>
                                    <strong><i class="fas fa-rupee-sign"></i> <?= sprintf("%.2f",$orders[0]->totalamount);?></strong>
                                </li>
                                <?php
                                    if(count($payment) >0) {
                                    ?>
                                    <li>
                                    <label>Payment ID</label>
                                    <strong><?= $payment[0]->pay_id;?></strong>
                                </li>
                                    <?php
                                }

                                ?>
                                    <?php
                        }
                    ?>  
                    <?php
                        if(count($payment) >0) {
                            ?>
                            <li>
                                    <label>Payment method</label>
                                    <strong><?php if($payment[0]->status ==1) {echo 'Online';}else if($payment[0]->status ==2) {echo 'COD';}?></strong>
                                </li>
                            <?php
                        }
                    ?>
                                
                            </ul>
                        
                    
                    <!-- End of Order View -->

                    <div class="order-details-wrapper mb-5">
                        <h4 class="title text-uppercase ls-25 mb-5">Order Details</h4>
                        <table class="order-table">
                            <thead>
                                <tr>
                                    <th class="text-dark">Product</th>
                                    <th style="text-align: right;color:#333 !important">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                if(count($orderproducts)) {
                                    foreach ($orderproducts as $key => $value) {
                                        ?>
                                            <tr>
                                                <td>
                                                    <a href="<?= base_url().'products/'.$value->purl;?>"><img src="<?= $value->image;?>" style="width:100px"><?= $value->name;?></a>&nbsp;<strong>x <?= $value->qty;?></strong></a>
                                                </td>
                                                <td><i class="fas fa-rupee-sign"></i> <?= number_format($value->price,2);?></td>
                                            </tr>
                                        <?php
                                    }
                                }
                            ?>
                            
                              
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Sub Total:</th>
                                    <td><i class="fas fa-rupee-sign"></i> <?= number_format($orders[0]->subtotal,2);?></td>
                                </tr>
                             
                                <?php
                                $tdiscount ="";
                                    if(!empty($orders[0]->discount)) {
                                        $tdiscount .= $orders[0]->totalamount + $orders[0]->delivery_charges * $orders[0]->discount /100;
                                        ?>  
                                         <tr>
                                            <th>Voucher Applied:</th>
                                            <td>- <i class="fas fa-rupee-sign"></i> <?= $orders[0]->discount;?></td>
                                        </tr>
                                        <?php
                                    }else {
                                        $tdiscount .=0;
                                    }
                                   // echo $tdiscount;
                                ?>

                                <?php
                                    if(!empty($orders[0]->delivery_charges) && $orders[0]->delivery_charges !=0) {
                                        ?>  
                                         <tr>
                                            <th>Delivery Charges:</th>
                                            <td><i class="fas fa-rupee-sign"></i> <?= number_format($orders[0]->delivery_charges,2);?></td>
                                        </tr>
                                        <?php
                                    }
                                ?>
                               
                                <tr class="total">
                                    <th class="border-no">Total:</th>
                                    <td class="border-no"><i class="fas fa-rupee-sign"></i> <?= number_format($orders[0]->totalamount,2);?></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!-- End of Order Details -->



                    <div id="account-addresses">
                        <div class="row">
                            <div class="col-sm-6 mb-8">
                                <div class="ecommerce-address billing-address">
                                    <h4 class="title title-underline ls-25 font-weight-bold">Billing Address</h4>
                                    <address class="mb-4">
                                        <table class="address-table">

                                            <tbody>
                                                <?php 
                                                    if(count($billing) >0) {
                                                        $billingstate = $this->master_db->sqlExecute('select s.name as sname,c.cname,a.areaname from order_bills ob left join states s on s.id = ob.bstate left join cities c on c.id = ob.bcity left join area a on a.id = ob.barea where ob.oid='.$orders[0]->oid.'');
                                                        ?>
                                                        <tr>
                                                    <td><?= $billing[0]->bfname;?></td>
                                                </tr>
                                                <tr>
                                                    <td><?= $billing[0]->bemail;?></td>
                                                </tr>
                                                 <tr>
                                                    <td><?= $billing[0]->baddress;?></td>
                                                </tr>
                                                <tr>
                                                    <td><?= $billingstate[0]->areaname;?></td>
                                                </tr>
                                                  <tr>
                                                    <td><?= $billingstate[0]->cname;?></td>
                                                </tr>
                                                <tr>
                                                    <td><?= $billingstate[0]->sname;?></td>
                                                </tr>
                                                <tr>
                                                    <td><?= $billing[0]->bpincode;?></td>
                                                </tr>
                                                        <?php
                                                    }
                                                ?>
                                                
                                            </tbody>
                                        </table>
                                    </address>
                                </div>
                            </div>
                            <div class="col-sm-6 mb-8">
                                <div class="ecommerce-address shipping-address">
                                    <h4 class="title title-underline ls-25 font-weight-bold">Shipping Address</h4>
                                    <address class="mb-4">
                                        <table class="address-table">
                                            <tbody>
                                                <?php 
                                                    if(count($billing) >0) {
                                                        $sstate = $this->master_db->sqlExecute('select s.name as sname,c.cname,a.areaname from order_bills ob left join states s on s.id = ob.sstate left join cities c on c.id = ob.scity left join area a on a.id = ob.sarea where ob.oid='.$orders[0]->oid.'');
                                                       // echo $this->db->last_query();

                                                        if(count($sstate) >0 && $sstate[0]->sname !=NULL) {
                                                            ?>
                                                        <tr>
                                                    <td><?= $billing[0]->sfname;?></td>
                                                </tr>
                                                <tr>
                                                    <td><?= $billing[0]->semail;?></td>
                                                </tr>
                                                <tr>
                                                    <td><?= $billing[0]->saddress;?></td>
                                                </tr>
                                                 <tr>
                                                    <td><?= $sstate[0]->areaname;?></td>
                                                </tr>
                                                 <tr>
                                                    <td><?= $sstate[0]->cname;?></td>
                                                </tr>
                                                 <tr>
                                                    <td><?= $sstate[0]->sname;?></td>
                                                </tr>
                                                <tr>
                                                    <td><?php if(!empty($billing[0]->spincode) && $billing[0]->spincode !=0) { echo $billing[0]->spincode; }?></td>
                                                </tr>
                                                        <?php
                                                        }else {
                                                            $billingstate = $this->master_db->sqlExecute('select s.name as sname,c.cname,a.areaname from order_bills ob left join states s on s.id = ob.bstate left join cities c on c.id = ob.bcity left join area a on a.id = ob.barea where ob.oid='.$orders[0]->oid.''); 

                                                            ?>
                                                        <tr>
                                                    <td><?= $billing[0]->bfname;?></td>
                                                </tr>
                                                <tr>
                                                    <td><?= $billing[0]->bemail;?></td>
                                                </tr>
                                                 <tr>
                                                    <td><?= $billing[0]->baddress;?></td>
                                                </tr>
                                                <tr>
                                                    <td><?= $billingstate[0]->areaname;?></td>
                                                </tr>
                                                  <tr>
                                                    <td><?= $billingstate[0]->cname;?></td>
                                                </tr>
                                                <tr>
                                                    <td><?= $billingstate[0]->sname;?></td>
                                                </tr>
                                                <tr>
                                                    <td><?= $billing[0]->bpincode;?></td>
                                                </tr>
                                                        <?php
                                                        }
                                                        
                                                    }
                                                ?>
                                            </tbody>
                                        </table>
                                    </address>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End of Account Address -->
                    <div class="row">
                        <div class="col-md-6"><a href="<?= base_url();?>"
                                class="btn btn-dark btn-rounded btn-icon-left btn-back mt-6"><i
                                    class="w-icon-long-arrow-left"></i>Shop More</a></div>
                      <div class="col-md-6 text-right "><a href="#"
                                class="btn btn-default btn-rounded btn-icon-right btn-back mt-6" onclick="window.print()">Print</a></div> 
                              <!-- <div class="col-md-6 text-right"><a href="<?= base_url().'order-view/'.icchaEncrypt($orders[0]->oid);?>"
                                class="btn btn-dark btn-rounded btn-icon-left btn-back mt-6"><i
                                    class="w-icon-long-arrow-left"></i>Confirm Order</a></div> -->


                    </div>
                </div>
            </div>
            <!-- End of PageContent -->
        </main>

        <!-- Start of Footer -->
        <footer class="footer appear-animate" data-animation-options="{
            'name': 'fadeIn'
        }" style="padding-bottom: 0px!important">
            <div class="footer-newsletter bg-primary pt-6 pb-6">
                <div class="container">
                    <div class="row justify-content-center align-items-center">
                        <div class="col-xl-5 col-lg-6">
                            <div class="icon-box icon-box-side text-white">
                                <div class="icon-box-icon d-inline-flex">
                                    <i class="w-icon-envelop3"></i>
                                </div>
                                <div class="icon-box-content">
                                    <h4 class="icon-box-title text-white text-uppercase mb-0">Subscribe To Our
                                        Newsletter</h4>
                                    <p class="text-white">Get all the latest information on Events, Sales and Offers.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-7 col-lg-6 col-md-9 mt-4 mt-lg-0 ">
                            <form action="#" method="get"
                                class="input-wrapper input-wrapper-inline input-wrapper-rounded">
                                <input type="email" class="form-control mr-2 bg-white" name="email" id="email"
                                    placeholder="Your E-mail Address" />
                                <button class="btn btn-dark btn-rounded" type="submit">Subscribe<i
                                        class="w-icon-long-arrow-right"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="footer-top">
                    <div class="row">
                        <div class="col-lg-4 col-sm-6">
                            <div class="widget widget-about">
                                <a href="index.html" class="logo-footer">
                                    <img src="<?= base_url();?>assets/images/logo_footer.png" alt="logo-footer" width="180px" />
                                </a>
                                <div class="widget-body">
                                    <p class="widget-about-title">Got Question? Call us 24/7</p>
                                    <a href="tel:+917026027027" class="widget-about-call">+91 70260 27027</a>
                                    

                                    <div class="social-icons social-icons-colored">
                                        <a href="https://www.facebook.com/swarnagowriofficial" target="_blank" class="social-icon social-facebook w-icon-facebook"></a>
                                        <a href="https://twitter.com/Swarngowri" target="_blank" class="social-icon social-twitter w-icon-twitter"></a>
                                        <a href="https://www.instagram.com/swarnagowriofficial/" target="_blank" class="social-icon social-instagram w-icon-instagram"></a>
                                        <a href="https://www.youtube.com/channel/UCzkjrDQW4f3vCQBr7suEljw" target="_blank" class="social-icon social-youtube w-icon-youtube"></a>
                                        <a href="https://www.linkedin.com/in/swarna-gowri-bb627627b/" target="_blank" class="social-icon social-pinterest w-icon-pinterest"></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="widget">
                                <h3 class="widget-title">Company</h3>
                                <ul class="widget-body">
                                    <li><a href="<?= base_url().'about'; ?>">About Us</a></li>
                                    <!-- <li><a href="#">Team Member</a></li>
                                    <li><a href="#">Career</a></li> -->
                                    <li><a href="<?= base_url().'contact'; ?>">Contact Us</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="widget">
                                <h4 class="widget-title">My Account</h4>
                                <ul class="widget-body">
                                    <li><a href="#">Track My Order</a></li>
                                     <li><a href="<?= base_url().'my-account'; ?>">My Wishlist</a></li>
                                    
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="widget">
                                <h4 class="widget-title">Customer Service</h4>
                                <ul class="widget-body">
                                  <li><a href="<?= base_url().'deliverypolicy'; ?>">Delivery and Return Policy</a></li>
                                    <li><a href="<?= base_url().'disclaimer'; ?>">Refund Policy</a></li>
                                    <li><a href="<?= base_url().'terms'; ?>">Term and Conditions</a></li>
                                    <li><a href="<?= base_url().'privacy'; ?>">Privacy Policy</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="footer-bottom">
                    <div class="footer-left">
                        <p class="copyright">Copyright © 2023 Swarnagowri. All Rights Reserved.

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


    <!-- Start of Sticky Footer -->
   

    <!-- Start of Scroll Top -->
    <a id="scroll-top" class="scroll-top" href="#top" title="Top" role="button"> <i class="w-icon-angle-up"></i> <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 70 70"> <circle id="progress-indicator" fill="transparent" stroke="#000000" stroke-miterlimit="10" cx="35" cy="35" r="34" style="stroke-dasharray: 16.4198, 400;"></circle> </svg> </a>
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
                        <li>  <a href="<?= base_url().'dealoftheday'; ?>">Deal of The Day </a></li>
                        <li><a href="<?= base_url().'specialoffers'; ?>">Special Offers</a></li>
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
   
    <div class="newsletter-popup mfp-hide">
        <div class="newsletter-content">
            <h4 class="text-uppercase font-weight-normal ls-25">Get Up to<span class="text-primary">25% Off</span></h4>
            <h2 class="ls-25">Sign up to About Swarnagowri </h2>
            <p class="text-light ls-10">Subscribe to the About Swarnagowri newsletter to
                receive updates on special offers.</p>
            <form action="#" method="get" class="input-wrapper input-wrapper-inline input-wrapper-round">
                <input type="email" class="form-control email font-size-md" name="email" id="email2"
                    placeholder="Your email address" required>
                <button class="btn btn-dark" type="submit">SUBMIT</button>
            </form>
            <div class="form-checkbox d-flex align-items-center">
                <input type="checkbox" class="custom-checkbox" id="hide-newsletter-popup" name="hide-newsletter-popup"
                    required="">
                <label for="hide-newsletter-popup" class="font-size-sm text-light">Don't show this popup again.</label>
            </div>
        </div>
    </div>
    <!-- End of Mobile Menu -->

    <!-- Root element of PhotoSwipe. Must have class pswp -->
    <div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">

        <!-- Background of PhotoSwipe. It's a separate element as animating opacity is faster than rgba(). -->
        <div class="pswp__bg"></div>

        <!-- Slides wrapper with overflow:hidden. -->
        <div class="pswp__scroll-wrap">

            <!-- Container that holds slides.
            PhotoSwipe keeps only 3 of them in the DOM to save memory.
            Don't modify these 3 pswp__item elements, data is added later on. -->
            <div class="pswp__container">
                <div class="pswp__item"></div>
                <div class="pswp__item"></div>
                <div class="pswp__item"></div>
            </div>

            <!-- Default (PhotoSwipeUI_Default) interface on top of sliding area. Can be changed. -->
            <div class="pswp__ui pswp__ui--hidden">

                <div class="pswp__top-bar">

                    <!--  Controls are self-explanatory. Order can be changed. -->

                    <div class="pswp__counter"></div>

                    <button class="pswp__button pswp__button--close" aria-label="Close (Esc)"></button>
                    <button class="pswp__button pswp__button--zoom" aria-label="Zoom in/out"></button>

                    <div class="pswp__preloader">
                        <div class="loading-spin"></div>
                    </div>
                </div>

                <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                    <div class="pswp__share-tooltip"></div>
                </div>

                <button class="pswp__button--arrow--left" aria-label="Previous (arrow left)"></button>
                <button class="pswp__button--arrow--right" aria-label="Next (arrow right)"></button>

                <div class="pswp__caption">
                    <div class="pswp__caption__center"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- End of PhotoSwipe -->

    <!-- Start of Quick View -->
   
    <!-- End of Quick view -->

    <!-- Plugin JS File -->
    <script src="<?= base_url();?>assets/vendor/jquery/jquery.min.js"></script>
    <script src="<?= base_url();?>assets/vendor/sticky/sticky.min.js"></script>
    <script src="<?= base_url();?>assets/vendor/jquery.plugin/jquery.plugin.min.js"></script>
    <script src="<?= base_url();?>assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
    <script src="<?= base_url();?>assets/vendor/magnific-popup/jquery.magnific-popup.min.js"></script>
    <script src="<?= base_url();?>assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="<?= base_url();?>assets/vendor/zoom/jquery.zoom.js"></script>
    <script src="<?= base_url();?>assets/vendor/photoswipe/photoswipe.min.js"></script>
    <script src="<?= base_url();?>assets/vendor/photoswipe/photoswipe-ui-default.min.js"></script>


    <!-- Main JS File -->
    <script src="<?= base_url();?>assets/js/main.min.js"></script>
</body>



</html>
