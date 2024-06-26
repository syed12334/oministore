<?php
    //echo "<pre>";print_r($categoryimg);exit;
?>
<?= $header;?>
<!-- Start of Main-->
<input type="hidden" class="csrf_token" name="<?= $this->security->get_csrf_token_name();?>" value="<?= $this->security->get_csrf_hash();?>">
        <main class="main">
            <section class="intro-section">

                <div class="swiper-container swiper-theme nav-inner pg-inner swiper-nav-lg animation-slider pg-xxl-hide nav-xxl-show nav-hide"
                    data-swiper-options="{
                    'slidesPerView': 1,
                    'autoplay': {
                        'delay': 8000,
                        'disableOnInteraction': false
                    }
                }">
                    <div class="swiper-wrapper">
                         <?php
                    if(count($slider)) {
                        foreach ($slider as $slide) {
                            ?>
                                <div class="swiper-slide banner banner-fixed intro-slide intro-slide1"
                            style="background-image: url(<?= base_url().$slide->image;?>); background-color: #ebeef2;">
                            <div class="container">
                                <div class="banner-content y-50 text-right">
                                    <h5 class="banner-subtitle font-weight-normal text-default ls-50 lh-1 mb-2 slide-animate"
                                        data-animation-options="{
                                    'name': 'fadeInRightShorter',
                                    'duration': '1s',
                                    'delay': '.2s'
                                }" style="font-weight: bold!important">
                                       <?= $slide->title;?>
                                    </h5>
                                    
                                    <p class="font-weight-normal text-default slide-animate" data-animation-options="{
                                    'name': 'fadeInRightShorter',
                                    'duration': '1s',
                                    'delay': '.6s'
                                }">
                                        <?= $slide->tagline;?>
                                    </p>
                                    <?php
                                        if(!empty($slide->link)) {
                                            ?>
                                            <a href="<?php if(!empty($slide->link)) { echo $slide->link;}else {echo '#';}?>"
                                        class="btn btn-dark btn-outline btn-rounded btn-icon-right slide-animate"
                                        data-animation-options="{
                                    'name': 'fadeInRightShorter',
                                    'duration': '1s',
                                    'delay': '.8s'
                                }">SHOP NOW<i class="w-icon-long-arrow-right"></i></a>
                                            <?php
                                        }
                                    ?>
                                    

                                </div>
                                <!-- End of .banner-content -->
                            </div>
                            <!-- End of .container -->
                        </div>
                            <?php
                        }
                    }
                ?>
                        
                        <!-- End of .intro-slide1 -->

                    </div>
                    <div class="swiper-pagination"></div>
                    <button class="swiper-button-next"></button>
                    <button class="swiper-button-prev"></button>
                </div>
                <!-- End of .swiper-container -->
            </section>
            <!-- End of .intro-section -->

            <div class="container">
                <div class="swiper-container appear-animate icon-box-wrapper br-sm mt-6 mb-6" data-swiper-options="{
                    'slidesPerView': 1,
                    'loop': false,
                    'breakpoints': {
                        '576': {
                            'slidesPerView': 2
                        },
                        '768': {
                            'slidesPerView': 3
                        },
                        '1200': {
                            'slidesPerView': 4
                        }
                    }
                }">
                    <div class="swiper-wrapper row cols-md-4 cols-sm-3 cols-1">
                        <div class="swiper-slide icon-box icon-box-side icon-box-primary">
                            <span class="icon-box-icon icon-shipping">
                                <i class="w-icon-truck"></i>
                            </span>
                            <div class="icon-box-content">
                                <h4 class="icon-box-title font-weight-bold mb-1">Free Shipping </h4>
                                <p class="text-default">Buy Products Above <i class="fas fa-rupee-sign"></i>300/-</p>
                            </div>
                        </div>
                        <div class="swiper-slide icon-box icon-box-side icon-box-primary">
                            <span class="icon-box-icon icon-payment">
                                <i class="w-icon-return"></i>
                            </span>
                            <div class="icon-box-content">
                                <h4 class="icon-box-title font-weight-bold mb-1">Free Returns</h4>
                                <p class="text-default">Returns are free within 2 days</p>
                            </div>
                        </div>

                        <div class="swiper-slide icon-box icon-box-side icon-box-primary">
                            <span class="icon-box-icon icon-payment">
                                <i class="w-icon-money"></i>
                            </span>
                            <div class="icon-box-content">
                                <h4 class="icon-box-title font-weight-bold mb-1">100% Payment Secure</h4>
                                <p class="text-default">Your payment are safe with us.</p>
                            </div>
                        </div>

                        <div class="swiper-slide icon-box icon-box-side icon-box-primary">
                            <span class="icon-box-icon icon-payment">
                                <i class="w-icon-hotline"></i>
                            </span>
                            <div class="icon-box-content">
                                <h4 class="icon-box-title font-weight-bold mb-1">Support 24/7</h4>
                                <p class="text-default">Contact us 24 hours a day</p>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- End of Iocn Box Wrapper -->


                <div class="row category-banner-wrapper appear-animate pt-6 pb-8">
                    <?php 
                        if(count($ads1) >0) {
                            foreach ($ads1 as $ads) {
                                ?>
                                    <div class="col-md-6 mb-4">
                        <div class="banner banner-fixed br-xs">
                            <figure>
                                <a href="<?php if(!empty($ads->link)) {echo  $ads->link;}else {echo '#';}?>">
                                <img src="<?= base_url().$ads->ad_banner;?>" alt="Category Banner"
                                     style="background-color: #ecedec;" />
                                </a>
                            </figure>
                          
                        </div>
                    </div>
                                <?php
                            }
                        }
                    ?>
                    
                   
                </div>
                <!-- End of Category Banner Wrapper -->
            </div>

      

            <div class="container">
                <h2 class="title justify-content-center ls-normal mb-4 mt-10 pt-1 appear-animate" style="font-size: 2.6rem!important">Our Products
                </h2>
              
                <!-- End of Tab -->
                <div class="tab-content product-wrapper appear-animate">
                    <div class="tab-pane active pt-4" id="tab1-1">
                            <div class="row cols-xl-4 cols-md-4 cols-sm-4 cols-2">
                        <?php
                            if(count($newarrivals) >0) {
                                foreach ($newarrivals as $arrive) {
                                    $mrp = $arrive->mrp;
                                    $sell = $arrive->price;
                                    $pid = $arrive->pid;
                                    $getSizes = $this->master_db->sqlExecute('select ps.pro_size as psid,s.sname from product_size ps left join sizes s on s.s_id = ps.sid where ps.pid ='.$pid.'');
                                    $disc = $this->home_db->discount($mrp, $sell);
                                    ?>
                            <div class="product-wrap">
                                <input type="hidden" name="sizes" id="sizesnew<?= $arrive->pid;?>" value="<?= $arrive->psid;?>">
                                <div class="product text-center">
                                    <figure class="product-media">
                                        <a href="<?= base_url().'products/'.$arrive->page_url;?>">
                                            <img src="<?= base_url().$arrive->image;?>" alt="Product"
                                                 />
                                            <img src="<?= base_url().$arrive->image;?>" alt="Product"
                                                 />
                                        </a>
                                        <div class="product-action-vertical">
                                            <!-- <a href="#" data-pid="<?= encode($arrive->pid);?>" class="btn-product-icon btn-cart w-icon-cart"
                                                title="Add to cart" data-id="1" id="cartBack<?= encode($arrive->pid);?>"></a> -->
                                                
                                                 
                                            <a href="<?= base_url().'products/'.$arrive->page_url;?>" class="btn-product-icon btn-wishlist w-icon-heart"
                                                title="Add to wishlist" id="wishlist" data-pid="<?= encode($arrive->pid);?>"></a>
                                        </div>
                                    </figure>
                                    <div class="product-details">
                                        <h4 class="product-name"><a href="#"><?php echo ucfirst($arrive->title);?></a></h4>

                                        <div class="flex-wrap d-flex align-items-center product-variations">
                                        <?php
                                            if(is_array($getSizes) && !empty($getSizes)) {
                                                foreach ($getSizes as $key => $size) {
                                                   ?>
                                                       <a href="javascript:void(0)" onclick="listsize(<?= $arrive->pid;?>,<?= $size->psid;?>)" class="sizelist size newsizeof<?= $size->psid;?> <?php if($size->psid == $arrive->psid) {echo 'newactive';}?>" id="sizef<?= $size->psid;?>" style="width:50px;"><?= $size->sname; ?></a>
                                                   
                                                 
                                                   <?php
                                                }
                                            }
                                        ?>
                                    </div>
                                       
                                        <div class="product-price">
                                            <ins class="new-price" id="sellprice<?= $arrive->pid;?>"><i class="fas fa-rupee-sign" style="margin-right: 5px"></i><?php echo  number_format($arrive->price);?></ins>
                                             <?php
                                                if(!empty($arrive->mrp)) {
                                                    ?>
                                                    <del class="old-price" id="sellmrp<?= $arrive->pid;?>"><i class="fas fa-rupee-sign" style="margin-right: 5px"></i> <?php echo  $arrive->mrp;?></del>
                                                    <?php
                                                }
                                            ?>
                                            <?php 
                                                    if(!empty($arrive->mrp) && $arrive->mrp !="") {
                                                        ?>
                                                        <span class="savedamt" id="saveamt<?= $arrive->pid;?>"
                                                 >Saved <i class="fas fa-rupee-sign" style="margin-right: 5px;margin-left: 5px;"></i><?php echo $arrive->mrp - $arrive->price;?></span>
                                                        <?php
                                                    }
                                                ?>
                                        </div>

                                         <button class="btn btn-primary btn-carts" type="button" data-pid="<?= encode($arrive->pid);?>" data-psid="<?= encode($arrive->psid);?>" style="width:100%" data-wen="<?= $arrive->pid;?>"> 
                                            <i class="w-icon-cart"></i>
                                            <span>Add to Cart</span>
                                        </button> 

                                    </div>
                                </div>
                            </div>
                       
                                    <?php
                                }
                            }
                        ?>
                         </div>
                    </div>
                
                </div>
                <!-- End of Tab Content -->

                <div class="row category-cosmetic-lifestyle appear-animate mb-5">
                      <?php 
                        if(count($ads2) >0) {
                            foreach ($ads2 as $ads1) {
                                ?>
                             
                      <div class="col-md-6 mb-4">
                        <div class="banner banner-fixed category-banner-1 br-xs">
                            <figure>
                                <a href="<?php if(!empty($ads1->link)) {echo  $ads1->link;}else {echo '#';}?>">
                                <img src="<?= base_url().$ads1->ad_banner;?>" alt="Category Banner"
                                    width="610" height="200" style="background-color: #3B4B48;" />
                                </a>
                            </figure>
                         
                        </div>
                    </div>
                                <?php
                            }
                        }
                    ?>
                  
                </div>
                <!-- End of Category Cosmetic Lifestyle -->
              


                <!-- End of Reviewed Producs -->
            </div>
            <!--End of Catainer -->
        </main>
        <!-- End of Main -->
<?= $footer;?>
<?= $js;?>
<script type="text/javascript">
   function listsize(pid,psid) {
       $("#sizesnew"+pid).val(psid);
       $.ajax({
            url:"<?= base_url().'Home/getProdsizerate';?>",
            method:"post",
            data :{
                pid:pid,
                psid:psid
            },
            dataType:"json",
            success:function(data) {
                if(data.status ==true) {
                    $("#sellprice"+pid).html(data.price);
                    $("#sellmrp"+pid).html(data.mrp);
                    $("#saveamt"+pid).html(data.discount);
                }else {

                }
            }
       });
   }
    function listsizeses(pid,psid) {
       $("#sizesnew"+pid).val(psid);
       $.ajax({
            url:"<?= base_url().'Home/getProdsizeratecategorywise';?>",
            method:"post",
            data :{
                pid:pid,
                psid:psid
            },
            dataType:"json",
            success:function(data) {
                if(data.status ==true) {
                    $("#sellpricesss"+pid).html(data.price);
                    $("#sellmrpsss"+pid).html(data.mrp);
                    $("#saveamt"+pid).html(data.discount);
                }else {

                }
            }
       });
   }

</script>