<?php
	//echo "<pre>";print_r($products);exit;
?>
<?= $header1;?>
<style type="text/css">
    label {
        cursor:pointer!important;
    }
    ul li {
      line-height: 20px!important;
    }
	
	.btn, input {
    margin-right: 8px;
}

.widget-body ul li {
    line-height: 28px!important;
}
 .newactive {
            border-color: #f18800!important;
    color: #f18800;
    }
    .newsletter-popup {
      display: none!important
    }


</style>

    <main class="main">
            <!-- Start of Breadcrumb -->
            <nav class="breadcrumb-nav">
                <div class="container">
                    <ul class="breadcrumb bb-no">
                        <li><a href="<?= base_url();?>">Home</a></li>
                        <li>Wallet</li>
                    </ul>
                </div>
            </nav>
            <!-- End of Breadcrumb -->
            <div class="">
                <div class="row text-center">
                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4"></div>
                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4" style="margin-bottom: 60px">
                        <?php
                            if(count($wallet) >0) {
                                ?>
                                 <div class="wallet_b">
                                        <img src="<?= base_url();?>/assets/images/wallet.png" alt="">
                                        <h3><i class="fas fa-rupee-sign"></i> <?= $wallet[0]->amount;?></h3>
                                        <h5>Wallet Balance</h5>
                            </div>
                                <?php
                            }
                        ?>
                        
                            <span class="total_ref">Total Referrals - <?= count($totalreferrals); ?></span>
                        
                    </div>
                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4"></div>
                    <div class="clearfix"></div>
                      <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2"></div>
                    <div class="wallet-bg-green">

                    <div class="wallet-matter">
                       
                            <p>You can ask your friends to register using your referral code and earn wallet amount (Rs.<?php if(count($walletamount) >0) {echo $walletamount[0]->amount;}?>) when they make there first order</p>

                            <div class="wallet-wrapper">
                            <h6>Share your Referral Code</h6>
                            <h4><?php if(count($wallet) >0) {
                                echo $wallet[0]->referral_code;
                            } ?></h4>
                            </div>
                            <?php
                                if(count($wallet) >0) {
                                    ?>
                                    <a class="icons_s"> <i class="fa fa-share-alt"></i></a>
                                    <a class="icons_s" href="https://www.facebook.com/sharer.php?u=https://www.iampl.store/omini_store?referral_code=<?= icchaEncrypt($wallet[0]->wallet_id);?>&t=test" target="_blank"> <i class="fab fa-facebook"></i></a>
                                    <a class="icons_s" href="https://api.whatsapp.com/send/?phone=<?= $phone;?>&text=https://www.iampl.store/omini_store?referral_code=<?= icchaEncrypt($wallet[0]->wallet_id);?>&app_absent=0" target="_blank"><i class="fab fa-whatsapp"></i></a>
                                    <a class="icons_s" href="https://www.iampl.store/omini_store?referral_code=<?= icchaEncrypt($wallet[0]->wallet_id);?>" target="_blank"><i class="fa fa-envelope"></i></a>
                                    <?php
                                }
                            ?>
                            
                            </div>
                    </div>
                     <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2"></div>
                    <div class="clearfix"></div>
                </div>
            </div>
            
    
        </main>
<?= $footer;?>
<?= $js;?>




