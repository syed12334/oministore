<?php //echo "<pre>";print_r($this->cart->contents());exit; ?>
<?= $header1;?>
<style type="text/css">
    #discount {
        display: none;
    }
    p {
        margin-bottom: 0px!important
    }
</style>
        <main class="main cart">
            <!-- Start of Breadcrumb -->
            <nav class="breadcrumb-nav">
                <div class="container">
                    <ul class="breadcrumb shop-breadcrumb bb-no">
                        <li class="active"><a href="<?= base_url().'cart';?>" style="text-transform: uppercase;">Shopping Cart</a></li>
                       
                    </ul>
                </div>
            </nav>
            <!-- End of Breadcrumb -->
<input type="hidden" class="csrf_token" name="<?= $this->security->get_csrf_token_name();?>" value="<?= $this->security->get_csrf_hash();?>">
            <!-- Start of PageContent -->
            <div class="page-content">
                <div class="container">
                    <div class="row gutter-lg mb-10">
                        <div class="col-lg-12 pr-lg-4 mb-6 products" id="appendProducts">
                            <?php
                                if(count($this->cart->contents()) >0) {
                                    ?>
                                     <table class="shop-table cart-table">
                                        <thead>
                                            <tr>
                                                <th class="product-name"><span>Product</span></th>
                                                <th></th>
                                                <th class="product-price"><span>Price</span></th>
                                                <th class="product-quantity"><span>Quantity</span></th>
                                                <th class="product-subtotal"><span>Subtotal</span></th>
                                            </tr>
                                        </thead>
                                      <tbody>
                                    <?php
                                    foreach ($this->cart->contents() as $cart) {
                                        ?>
                                      <tr>
                                        <td class="product-thumbnail">
                                            <div class="p-relative">
                                                <a href="<?= $cart['plink'];?>">
                                                    <figure>
                                                        <img src="<?= $cart['image'];?>" alt="product" width="300"
                                                            height="338">
                                                    </figure>
                                                </a>
                                                <button type="submit" class="btn btn-close" onclick="removeCart('<?= $cart["rowid"];?>',2)"><i
                                                        class="fas fa-times"></i></button>
                                            </div>
                                        </td>
                                        <td class="product-name">
                                            <a href="<?= $cart['plink'];?>">
                                                <?= ucfirst($cart['name']); ?>
                                            </a>
                                            <span><?= "(".$cart['sname'].")";?></span>

                                        </td>
                                        <td class="product-price"><span class="amount"><i class="fas fa-rupee-sign" style="margin-right: 3px"></i><?=$cart['price'];?></span></td>
                                        <td class="product-quantity">
                                            <div class="input-group">
                                                <input type="number" class="form-control" id="qty<?= $cart['rowid']; ?>" value="<?= $cart['qty'];?>" readonly/>
                                                <button class="w-icon-plus" onclick="addqty('<?= $cart['rowid']; ?>')"></button>
                                                <button class="w-icon-minus" onclick="reduceqty('<?= $cart["rowid"];?>')"></button>
                                            </div>
                                        </td>
                                        <td class="product-subtotal">
                                            <span class="amount"><i class="fas fa-rupee-sign" style="margin-right: 3px"></i><?=number_format($cart['subtotal']);?></span>
                                        </td>
                                     </tr>
                                        <?php
                                    }
                                    ?>
                                    
                                        </tbody>
                                    </table>
                                    <div class="row">
                                        <div class="col-lg-4 col-md-4 col-sm-4">
                                            <div id="processing"></div>
                                             <form id="couponsubmit" style="margin:40px 0px" method="post" enctype="multipart/form-data">
                                        <h5 class="title coupon-title font-weight-bold text-uppercase">Coupon Discount</h5>
                                        <input type="text" class="form-control mb-4" placeholder="Enter coupon code here..."
                                     style="width: 100%;margin-bottom: 0px!important" id="couponcode" name="couponcode" />
                                     <span id="coupon_error" class="text-danger" style="color:red;"></span>
                                        <button class="btn btn-dark btn-outline btn-rounded" style="margin-top: 20px">Apply Coupon</button>
                                    </form>
                                        </div>
                                        <div class="col-xl-4 col-md-4 col-sm-4"></div>
                                        <div class="col-lg-4 col-md-4 col-sm-4">
                                            <table class="shop-table cart-table">
                                                 <tr>
                                        <td colspan="5" style="text-align:right; padding-right:133px;"><span>Subtotal : <i class="fas fa-rupee-sign"></i> <?= sprintf("%.2f",$this->cart->total());?>  </span></th>
                                    </tr>
                                    
                                    
                                    
                                             <tr id="discount" style="<?php if($this->session->userdata('discount')) {echo 'display: block!important;float: right';}?>">
                                                <td colspan="5" style="text-align:right; padding-right:133px;"><span id="disamount">Discount :  <?= $this->session->userdata('discount')."%";?></span></td>
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
      


                                                    <tr>

                                                    <td colspan="5" style="text-align:right; padding-right:133px;">Delivery Charges :  <?= $delivertext; ?></td>
                                            </tr>
                                            <tr>
                                                <td colspan="5" style="text-align:right; padding-right:133px;"><span>Total Amount : <i class="fas fa-rupee-sign"></i> <span id="totalAmount"><?php $amount = floatval( $this->cart->total() + $delamount) * $this->session->userdata('discount') / 100; 
                                                echo sprintf("%.2f", $this->cart->total() -$amount +$delamount );?></span> </span></td>
                                            </tr>
                                        
                                  
                                   
                                            </table>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    
                                    <div class="cart-action mb-6">
                                        <a href="<?= base_url();?>" class="btn btn-rounded btn-default btn-icon-left mr-auto"><i
                                                class="w-icon-long-arrow-left"></i> Continue Shopping</a>
                                        <a href="<?php if($this->session->userdata(CUSTOMER_SESSION)) {echo base_url().'checkout';}else {echo base_url().'login';}?>" class="btn btn-dark btn-rounded">Checkout <i
                                                class="w-icon-long-arrow-right"></i></a>
                                    </div>
                                   
                                    <?php
                                }else {
                                    ?>
                                    <div class="col-lg-12 pr-lg-4 mb-6">
                                        <div class="message-error">
                                            <img src="<?= base_url().'assets/images/cart.png';?>" alt="" class="img-fluid">
                                            <h4>Your Cart is empty</h4>
                                            <a href="<?= base_url();?>" class="btn btn-dark">SHOP More</a>
                                        </div>
                                    </div>
                                    <?php
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of PageContent -->
        </main>
        <!-- End of Main -->

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
       <?= $footer;?>
       <?= $js;?>


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