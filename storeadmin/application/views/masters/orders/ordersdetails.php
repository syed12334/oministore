<?= $header;?>
<style type="text/css">
  table th {
    font-size: 14px!important;font-weight: bold!important;
  }
</style>
<?php
    if(is_array($order_details) && !empty($order_details)) {
      ?>
          <div class="page has-sidebar-left bg-light height-full">
             <div class="container-fluid my-3">
                    <div class="row">
                        <div class="col-md-6">
                          <div class="card-title">Orders Details</div>
                            
                                      <?php 
                                        if($this->session->flashdata('message')) {
                                            echo $this->session->flashdata('message');
                                        }
                                    ?>
                                        <table id="category_table" class="table display table-bordered table-striped no-wrap" style="width:100%;">
                                            <thead>
                                                <tr>
                                                    <th style="border-bottom:2px solid #e1e8ee!important;font-size:14px!important">Order Id</th>
                                                    <td><?= $order_details[0]->orderid;?></td>
                                                </tr>
                                                 <tr>
                                                    <th style="border-bottom:2px solid #e1e8ee!important;font-size:14px!important">Order Date </th>
                                                    <td><?= date('d-m-Y h:i:s A', strtotime($order_details[0]->order_date))?></td>
                                                </tr>
                                                 <tr>
                                                    <th style="border-bottom:2px solid #e1e8ee!important;font-size:14px!important">Total Amount Paid  </th>
                                                    <td><?= number_format($order_details[0]->totalamount);?></td>
                                                </tr>
                                                 <tr>
                                                    <th style="border-bottom:2px solid #e1e8ee!important;font-size:14px!important">Paid Through </th>
                                                    <td><?php if($order_details[0]->pmode ==1) {echo "Online";}else if($order_details[0]->pmode ==2){echo "COD";}else if($order_details[0]->pmode ==3){echo "Wallet";}?></td>
                                                </tr>
                                                <?php
                                                  if($order_details[0]->pmode ==1) {
                                                      ?>
                                                        <tr>
                                                          <th style="border-bottom:2px solid #e1e8ee!important;font-size:14px!important">Payment Id</th>
                                                          <td><?php if(is_array($logs) && !empty($logs)) {echo $logs[0]->pay_id; }?></td>
                                                      </tr>
                                                      <?php
                                                  }
                                                ?>
                                                 
                                                 <tr>
                                                    <th style="border-bottom:2px solid #e1e8ee!important;font-size:14px!important">Ordered By </th>
                                                    <td><?=  $order_details[0]->uname;?></td>
                                                </tr>
                                                 <tr>
                                                    <th style="border-bottom:2px solid #e1e8ee!important;font-size:14px!important">Order Status </th>       
                                                    <td><?php $statustext ="";  if ($order_details[0]->status == 1) {
                                                                      $statustext .= "In Progress";
                                                                  } else if ($order_details[0]->status == 2){
                                                                      $statustext .= "Ready to Ship";
                                                                  }
                                                                  else if ($order_details[0]->status == 3){
                                                                      $statustext .= "Delivered";
                                                                  }
                                                                  else if ($order_details[0]->status == 4){
                                                                      $statustext .= "Cancelled";
                                                                  }
                                                                  else if ($order_details[0]->status == 5){
                                                                      $statustext .= "Order Confirmed";
                                                                  }
                                                                  else if ($order_details[0]->status == -1){
                                                                      $statustext .= "Failed";
                                                                  } 

            echo $statustext;?></td>
                                                </tr>
                                                <?php
                                                        if(!empty($order_details[0]->shipping_date)) {
                                                            ?>
                                                              <tr>

                                                    <th style="border-bottom:2px solid #e1e8ee!important;font-size:14px!important">Shipping Date </th>
                                                    <td><?=  date("d-m-Y",strtotime($order_details[0]->shipping_date));?></td>
                                                </tr>
                                                            <?php
                                                        }
                                                      ?>

                                                      <?php
                                                        if(!empty($order_details[0]->delivered_date)) {
                                                            ?>
                                                              <tr>
                                                    <th style="border-bottom:2px solid #e1e8ee!important;font-size:14px!important">Estimated Delivery Date  </th>
                                                    <td><?=  date("d-m-Y",strtotime($order_details[0]->delivered_date));?></td>
                                                </tr>
                                                            <?php
                                                        }
                                                      ?>
                                                     
                                                     
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                
                        </div>
                         <div class="col-md-3">
                          <h3>Billing Address</h3>
                          <?php
                              if(count($baddress) >0) {
                               if(!empty($baddress[0]->baddress)) {
                                ?>
                                  <p><?= $baddress[0]->bfname." ".$baddress[0]->bphone."<br /> ". $baddress[0]->baddress. " - ".$baddress[0]->bpincode;?></p>
                                <?php
                               }
                              }
                          ?>
                        </div>
                        <div class="col-md-3">

                          <?php
                              if(count($saddress) >0) {
                               if(!empty($saddress[0]->saddress)) {
                                ?>
                                 <h3>Shipping Address</h3>
                                  <p><?= $saddress[0]->sfname." ".$saddress[0]->sphone."<br /> ". $saddress[0]->saddress. " - ".$saddress[0]->spincode;?></p>
                                <?php
                               }
                              }
                          ?>
                         
                        </div>
                       <div class="clearfix"></div>
                       <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-top: 20px">
                        <h3></h3>
                         <table class="table table-bordered">
    <thead>
      <tr>
        <th>Product Image</th>
        <th>Price</th>
        <th>Quantity</th>
        <th>Subtotal</th>
      </tr>
    </thead>
    <tbody>
      <?php
        if(count($order_products) >0) {
          foreach ($order_products as $key => $value) {
            ?>
                <tr>
        <td><img src="<?= $value->image;?>" style="width:100px"> <?= $value->name;?></td>
        <td><?= number_format($value->price,2);?></td>
        <td><?= $value->qty;?></td>
        <td><?= number_format($value->price * $value->qty,2);?></td>
        
      </tr>
            <?php
          }
        }
      ?>
    
      
    </tbody>
  </table>

 
                       </div>
                       <div class="clearfix"></div>
                       <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                         &nbsp;
                       </div>
                       <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                          <table class="table table-bordered">
                            <tr>
                              <th>Subtotal</th>
                              <td><?= "<i class='fas fa-indian-rupee'></i> ".number_format($order_details[0]->subtotal,2);?></td>
                            </tr>

                             <?php
                                $tdiscount ="";
                                    if(!empty($order_details[0]->discount)) {
                                        
                                        ?>  
                                         
                                          <tr>
                              <th>Voucher Applied</th>
                              <td><?= "-  <i class='fas fa-indian-rupee'></i> ".number_format($order_details[0]->discount);?></td>
                            </tr>
                                        <?php
                                    }else {
                                        $tdiscount .=0;
                                    }
                                   // echo $tdiscount;
                                ?>

                            
                            <tr>
                              <th>Delivery Charges</th>
                              <td><?= "<i class='fas fa-indian-rupee'></i> ".number_format($order_details[0]->delivery_charges,2);?></td>
                            </tr>
                            
                             <tr>
                              <th>Total Amount</th>
                              <td><?= "<i class='fas fa-indian-rupee'></i> ".number_format($order_details[0]->totalamount,2);?></td>
                            </tr>
                          </table>
                       </div>
                       <div class="clearfix">
                         
                       </div>
                       <div class="col-lg-12 col-sm-12 col-md-12">
                          <center>
    <a href="<?= base_url().'orders';?>" class="btn btn-primary"><i class="fas fa-arrow-left" style="margin-right: 2px"></i> Back</a>
  </center>
                       </div>
                       <div class="clearfix"></div>
                    </div>
                </div>
            </div>
      <?php
    }else {
      redirect(base_url().'orders');
    }
?>

<?= $footer;?>
<?php $csrf = array('name' => $this->security->get_csrf_token_name(),'hash' => $this->security->get_csrf_hash()); ?>
<input type="hidden" class="csrf_token" name="<?= $this->security->get_csrf_token_name();?>" value="<?= $this->security->get_csrf_hash();?>">
<script type="text/javascript">
    function updateStatus(id){
                       
                     alert(id);   
            }
</script>
