<?php
	if(count($this->cart->contents()) >0) {
    $subtotal = [];
    ?>
      <div class="cart-header">
                                    <span>Shopping Cart</span>
                                    <a href="#" class="btn-close">Close<i class="w-icon-long-arrow-right"></i></a>
                                </div>
 <div class="products" style="border-bottom: 0px!important">
    <?php
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
                                                    <img src="<?= $cart['image'];?>" alt="product" height="84" width="94" />
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
       <div class="cart-total" style="padding: 10px 0px!important">
                                           <label>Sub Total:</label>
                                           <span class="price"><i class="fas fa-rupee-sign" style="margin-right: 5px"></i><?= number_format($this->cart->total(),2);?></span>
                                        </div>
                                         <?php
                                        $delivercha ="";
                                          if($this->cart->total() >=300) {
                                            $delivercha .= (int)0;
                                            ?>
                                              <div class="cart-total" style="padding:5px 0px!important;">
                                           <label>Delivery Charges:</label>
                                           <span ><?= "Free Shipping"; ?></span>
                                        </div>
                                      

                                        <div class="cart-total" style="padding: 10px 0px!important">
                                           <label>Total Amount :</label>
                                           <span><i class="fas fa-rupee-sign" style="margin-right: 5px"></i><?= number_format($this->cart->total(),2);?></span>
                                        </div>
                                       
                                            <?php
                                          }else {
                                            $deliverchas= (int)50;
                                              ?>
                                              <div class="cart-total" style="padding:5px 0px!important;">
                                           <label>Delivery Charges:</label>
                                           <span ><i class="fas fa-rupee-sign" style="margin-right: 5px"></i><?= number_format(50,2);?></span>
                                        </div>
                                        
                                        <div class="cart-total" style="padding: 10px 0px!important">
                                           <label>Total Amount :</label>
                                           <span><i class="fas fa-rupee-sign" style="margin-right: 5px"></i><?= number_format($this->cart->total() +$deliverchas,2);?></span>
                                        </div>
                                        
                                            <?php

                                          }
                                        ?>

 
                                          
                                      
                                        <div class="cart-action">
                                          <a href="<?= base_url();?>" class="btn btn-dark btn-outline btn-rounded">SHOP MORE</a>
                                          <a href="<?php if($this->session->userdata(CUSTOMER_SESSION)) {echo base_url().'checkout';}else {echo base_url().'login';}?>" class="btn btn-dark  btn-rounded">Checkout</a>
                                        </div>

    <?php
	}else {
    ?>
                                <div class="cart-header">
                                    <span>Shopping Cart</span>
                                    <a href="#" class="btn-close">Close<i class="w-icon-long-arrow-right"></i></a>
                                </div><br /><br /><br /><center><img src='<?= base_url()."assets/images/cart.png" ?>' style='width:230px' /><p style='font-size:16px;text-align:center;margin-top:10px;font-weight:bold'>Your Cart is empty</p></center>
    <?php
		
	}
?>