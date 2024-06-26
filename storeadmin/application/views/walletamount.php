<?php //echo "<pre>";print_r($property);exit;?>
<?= $header;?>
<style type="text/css">
  #title-error {
    color:red;
  }
</style>
<div class="page has-sidebar-left bg-light height-full">
 <div class="container-fluid my-3">
        <div class="row">
            <div class="col-md-12">
                    <div class="card my-3 no-b">
                    <div class="card-body">
                  <div class="card-title">Wallet Amount Setting</div>
                           
                            <?php
                              if($this->session->flashdata('message')) {
                                echo $this->session->flashdata('message');
                              }
                            ?>    
                               <form action="<?= base_url().'master/walletsave';?>" method="post" style="margin-top:40px">
<input type="hidden" class="csrf_token" name="<?= $this->security->get_csrf_token_name();?>" value="<?= $this->security->get_csrf_hash();?>">
                         
                        <div class="row">
                          <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                             <div class="form-group">
                               <label>Amount</label>
                               <input type="number" name="amount" placeholder="Enter Amount" class="form-control" value="<?php if(!empty($walletamount[0]->amount)) { echo $walletamount[0]->amount;}?>">
                           </div>
                          </div>
                          <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
                             
                          </div>
                          <div class="clearfix"></div>
                        </div>
                             <button class="btn btn-primary" type="submit" id="submit">Submit</button>
                       </form>
                      
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $footer;?>

