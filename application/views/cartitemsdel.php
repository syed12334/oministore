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
                                                <button type="submit" class="btn btn-close" onclick="removeCart('<?= $cart["rowid"];?>','2')"><i
                                                        class="fas fa-times"></i></button>
                                            </div>
                                        </td>
                                        <td class="product-name">
                                            <a href="<?= $cart['plink'];?>">
                                                <?= ucfirst($cart['name']); ?>
                                            </a>
                                        </td>
                                        <td class="product-price"><span class="amount"><i class="fas fa-rupee-sign" style="margin-right: 3px"></i><?=$cart['price'];?></span></td>
                                        <td class="product-quantity">
                                            <div class="input-group">
                                                <input type="number" class="quantity form-control" id="qty<?= $cart['rowid']; ?>" value="<?= $cart['qty'];?>" readonly/>
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
                                        <td colspan="5" style="text-align:right; padding-right:133px;"><span>Subtotal : <i class="fas fa-rupee-sign"></i> <?= floatval($this->cart->total());?>  </span></th>
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
                                                <td colspan="5" style="text-align:right; padding-right:133px;"><span>Total Amount : <i class="fas fa-rupee-sign"></i> <span id="totalAmount"><?php $amount = floatval($this->cart->total() + $delamount) * $this->session->userdata('discount') / 100; 
                                                echo sprintf("%.2f", $this->cart->total() -$amount +$delamount);?></span> </span></td>
                                            </tr>
                                         
                                   
                                            </table>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                            		<div class="cart-action mb-6">
		                                <a href="index.html" class="btn btn-rounded btn-default btn-icon-left mr-auto"><i
		                                        class="w-icon-long-arrow-left"></i> Continue Shopping</a>
		                                <a href="<?php if($this->session->userdata(CUSTOMER_SESSION)) {echo base_url().'checkout';}else {echo base_url().'login';}?>" class="btn btn-dark btn-rounded">Checkout <i
		                                        class="w-icon-long-arrow-right"></i></a>
		                            </div>
		                           
									<?php
								}else {
									?>
<div class="col-lg-12 pr-lg-4 mb-6"><div class="message-error"><img src="<?= base_url().'assets/images/cart.png';?>" alt="" class="img-fluid"><h4>Your Cart is empty</h4><a href="<?= base_url();?>" class="btn btn-dark">SHOP More</a></div></div>
									<?php
								}
							?>