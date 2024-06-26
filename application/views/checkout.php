<?php //echo "<pre>";print_r($this->cart->contents()); exit; ?>
<?= $header;?>
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
  .newsletter-popup {
      display: none!important
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


</style>
 <!-- Start of Main -->
        <main class="main checkout">
        
        <!-- Start of Page Header -->
            <div class="page-header">
                <div class="container">
                    <h1 class="page-title mb-0">Checkout </h1>
                </div>
            </div>
            <!-- End of Page Header -->
            
            <!-- Start of Breadcrumb -->
            <nav class="breadcrumb-nav">
                <div class="container">
                    <ul class="breadcrumb shop-breadcrumb bb-no">
                        <li class="passed"><a href="<?= base_url().'cart'; ?>">Shopping Cart</a></li>
                        <li class="active"><a href="<?= base_url().'checkout'; ?>">Checkout</a></li>
                        <li><a href="<?= base_url().'checkout'; ?>">Order Complete</a></li>
                    </ul>
                </div>
            </nav>
            <!-- End of Breadcrumb -->


            <!-- Start of PageContent -->
            <div class="page-content">
                <div class="container">


                    <div id="processing"></div>

                    <form id="checkout" method="post" enctype="multipart/form-data">
                        <input type="hidden" class="csrf_token" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />
                        <input type="hidden" name="orderfrom" value="1">
                        <div class="row mb-9">
                            <div class="col-lg-7 pr-lg-4 mb-4">
                                <h3 class="title billing-title text-uppercase ls-10 pt-1 pb-3 mb-0">
                                    Billing Details
                                </h3>
                                <div class="row gutter-sm">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="form-group">
                                            <label>Name <font style="color:red">*</font></label>
                                            <input type="text" class="form-control form-control-md" name="bfname"
                                                id="bfname" placeholder="Eg:John" value="<?php if($this->session->userdata(CUSTOMER_SESSION)) {echo $this->session->userdata(CUSTOMER_SESSION)['name'];}else {echo '';}?>">
                                                <span id="bfname_error" class="text-danger"></span>
                                        </div>
                                    </div>
                                   
                                    <div class="clearfix"></div>
                                     <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                        <div class="form-group">
                                            <label>Email <font style="color:red">*</font></label>
                                            <input type="email" class="form-control form-control-md" name="bemail"
                                                id="bemail" placeholder="Eg:johndoe@example.com" value="<?php if($this->session->userdata(CUSTOMER_SESSION)) {echo $this->session->userdata(CUSTOMER_SESSION)['email'];}else {echo '';}?>">
                                                 <span id="email_error" class="text-danger"></span>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                        <div class="form-group">
                                            <label>Mobile Number <font style="color:red">*</font></label>
                                            <input type="number" class="form-control form-control-md" name="bphone"
                                                id="bphone" placeholder="Eg:9988776655" maxlength="10" minlength="10" pattern="/^-?\d+\.?\d*$/" onkeypress="if(this.value.length==10) return false;" value="<?php if($this->session->userdata(CUSTOMER_SESSION)) {echo $this->session->userdata(CUSTOMER_SESSION)['phone'];}else {echo '';}?>">
                                                 <span id="bphone_error" class="text-danger"></span>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                     <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                        <div class="form-group">
                                            <label>Pincode <font style="color:red">*</font></label>
                                            <input type="number" class="form-control form-control-md" name="bpincode" id="bpincode" placeholder="Eg:560043" min="0" maxlength="6" minlength="6" pattern="/^-?\d+\.?\d*$/" onkeypress="if(this.value.length==6) return false;"   value="<?php if($this->session->userdata('pincodes')) {echo $this->session->userdata('pincodes');}else {echo '';} ?>">
                                                 <span id="bpincode_error" class="text-danger"></span>
                                                 <span id="pincode_error" class="text-danger"></span>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                        <div class="form-group">
                                            <label>State <font style="color:red">*</font></label>
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
                                            <label>City <font style="color:red">*</font></label>
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
                                      
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="form-group">
                                            <label>Address <font style="color:red">*</font></label>
                                            <textarea cols="4" rows="4" class="form-control" name="baddress" id="baddress" placeholder="Address"></textarea>
                                        </div>
                                        <span id="baddress_error" class="text-danger" style="color:red"></span>
                                    </div>
                                     <div class="clearfix"></div>
                                </div>
                              
                                <div class="form-group pb-2">
                                    <input type="checkbox" 
                                        name="shipped" class="shipped" id="shipped" value="1">
                                     <label for="shipped" style="cursor: pointer">Ship to a Different Address?</label> 
                                </div>
                                <div id="shipped-container">
                                   <div class="row gutter-sm">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="form-group">
                                            <label>Shipping Name <font style="color:red">*</font></label>
                                            <input type="text" class="form-control form-control-md" name="sfname"
                                                id="sfname" placeholder="Name">
                                                <span id="sfname_error" class="text-danger"></span>
                                        </div>
                                    </div>
                                    
                                    <div class="clearfix"></div>
                                     <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                        <div class="form-group">
                                            <label>Shipping Email <font style="color:red">*</font></label>
                                            <input type="email" class="form-control form-control-md" name="semail"
                                                id="semail" placeholder="Email">
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
                                            <label>Shipping Address <font style="color:red">*</font></label>
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
                                                        <b>Product </b>  <b class="float-end">Price</b>
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
                                                                    <td class="product-name"><a href="<?= base_url().'products/'.$cart['purl'];?>"><img src="<?= $cart['image'];?>" style="width:100px; float:left; margin-right:15px;"/> <div style="display: block; margin-top:25px;"><?= ucfirst($cart['name']);?> </div> <div style="float: left;">QTY <i
                                                                            class="fas fa-times"></i> <span
                                                                            class="product-quantity"><?= $cart['qty'];?></span></div></a> </td>
                                                                    <td class="product-total"><i class="fas fa-rupee-sign"></i> <?= number_format($cart['subtotal']);?></td>
                                                                </tr>
                                                            <?php
                                                        }
                                                    }
                                                }
                                                  
                                                ?>
                                                
                                             

                                                 <tr class="order-total">
                                                    <td colspan="2">If you have a Voucher code, please
                                                        apply it below.</td>
                                                </tr>
                                                <tr>
                                                    <td class="input-wrapper-inline" colspan="2">
                                                        <input type="text" name="couponcode"
                                                            class="form-control-md mr-1 mb-2"
                                                            placeholder="Voucher code" id="couponcode">
                                                        <button type="button" class="btn button btn-rounded btn-coupon mb-2"
                                                            value="Apply coupon" id="checkoutCoupon">Apply
                                                            Voucher</button>
                                                    </td>
                                                </tr>
                                                  <tr>
                                                    <td class="input-wrapper-inline" colspan="2">
                                                        
                                                              <button type="button" class="btn button btn-rounded btn-coupon mb-2"
                                                            value="Apply coupon" id="redeemToWallet">Redeem to Wallet</button>
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
                                                        <b> <?php if(is_array($this->cart->contents()) && !empty($this->cart->contents())) { echo '<i class="fas fa-rupee-sign"></i> '. number_format(array_sum($subtotal),2); } ?></b>
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
                                                        <input type="radio" name="pmode" id="pmode" value="6">
                                                        <label for="pmode">Easebuzz</label>
                                                    </div>

                                                       <div class="card-header">
                                                        <input type="radio" name="pmode" id="pmode" value="1">
                                                        <label for="pmode">Atom Pay</label>
                                                    </div>
                                                  <div class="card-header">
                                                        <input type="radio" name="pmode" id="pmodes" value="2">
                                                        <label for="pmodes">COD </label>
                                                    </div> 
                                                    <?php
                                                      if(count($wallet) >0) {
                                                        if($wallet[0]->amount >0) {
                                                          ?>
                                                          <div class="card-header">
                                                        <input type="radio" name="pmode" id="wallet" value="3">
                                                        <label for="wallet">Wallet </label>
                                                    </div> 
                                                          <?php
                                                        }
                                                      }
                                                    ?>

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