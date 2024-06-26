<?php
    //echo "<pre>";print_r($categoryimg);exit;
?>

<?= $header;?>
<style type="text/css">
    .sizelist {
    border: 1px solid #ccc;
    padding: 3px 4px;
    border-radius: 3px;
    margin-right: 5px;
    margin-bottom: 10px;
    cursor: pointer;
    color:#484848;
    }
    .newactive {
            border-color: #f18800;
    color: #f18800;
    }
	

</style>

         
<!-- Start of Main-->
<input type="hidden" class="csrf_token" name="<?= $this->security->get_csrf_token_name();?>" value="<?= $this->security->get_csrf_hash();?>">
        <main class="main">
            <section class="intro-section">

                <div class="swiper-container swiper-theme nav-inner pg-inner swiper-nav-lg animation-slider pg-xxl-hide nav-xxl-show nav-hide"
                    data-swiper-options="{
                    'slidesPerView': 1,
                    'autoplay': {
                        'delay': 2000,
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

                                    <?php /*?><a href="<?php if(!empty($slide->link)) { echo $slide->link;}else {echo '#';}?>"
                                        class="btn btn-dark btn-outline btn-rounded btn-icon-right slide-animate"
                                        data-animation-options="{
                                    'name': 'fadeInRightShorter',
                                    'duration': '1s',
                                    'delay': '.8s'
                                }">SHOP NOW<i class="w-icon-long-arrow-right"></i></a><?php */?>

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
                                <p class="text-default">Buy Products Above Rs.300/- <br> Get Free Delivery</p>
                            </div>
                        </div>
                        <div class="swiper-slide icon-box icon-box-side icon-box-primary">
                            <span class="icon-box-icon icon-payment">
                                <i class="w-icon-return"></i>
                            </span>
                            <div class="icon-box-content">
                                <h4 class="icon-box-title font-weight-bold mb-1">Free Returns</h4>
                                <p class="text-default">Within 24hrs or at the time of delivery</p>
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
                                    width="610" height="160" style="background-color: #ecedec;" />
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

            <section class="category-section top-category bg-grey pt-10 pb-10 appear-animate">
                <div class="container pb-2">
                    <h2 class="title justify-content-center pt-1 ls-normal mb-5">Top Categories</h2>
                    <div class="swiper">
                        <div class="swiper-container swiper-theme pg-show" data-swiper-options="{
                            'spaceBetween': 20,
                            'slidesPerView': 2,
                            'breakpoints': {
                                '576': {
                                    'slidesPerView': 3
                                },
                                '768': {
                                    'slidesPerView': 5
                                },
                                '992': {
                                    'slidesPerView': 6
                                }
                            }
                        }">
                            <div class="swiper-wrapper row cols-lg-6 cols-md-5 cols-sm-3 cols-2">
                                <?php 
                                    if(count($categoryimg) >0) {
                                        foreach ($categoryimg as $catimg) {
                                            ?>
                                             <div
                                    class="swiper-slide category category-classic category-absolute overlay-zoom br-xs">
                                    <a href="<?= base_url().'home/products/'.icchaEncrypt($catimg->cat_id);?>" class="category-media">
                                        <img src="<?= base_url().$catimg->image;?>" alt="Category"
                                            width="130" height="130">
                                    </a>
                                    <div class="category-content">
                                        <h4 class="category-name"><?= ucfirst($catimg->title);?></h4>
                                        <!-- <a href="#" class="btn btn-primary btn-link btn-underline">Shop
                                            Now</a> -->
                                    </div>
                                </div>
                                            <?php
                                        }
                                    }
                                ?>
                               
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- End of .category-section top-category -->

            <div class="container">
                <h2 class="title justify-content-center ls-normal mb-4 mt-10 pt-1 appear-animate">Popular Products
                </h2>
                <div class="tab tab-nav-boxed tab-nav-outline appear-animate">
                    <ul class="nav nav-tabs justify-content-center" role="tablist">
                        <li class="nav-item mr-2 mb-2">
                            <a class="nav-link active br-sm font-size-md ls-normal" href="#tab1-1">New arrivals</a>
                        </li>
                      <!--   <li class="nav-item mr-2 mb-2">
                            <a class="nav-link br-sm font-size-md ls-normal" href="#tab1-2">Best seller</a>
                        </li> -->
                       <!--  <li class="nav-item mr-2 mb-2">
                            <a class="nav-link br-sm font-size-md ls-normal" href="#tab1-3">most popular</a>
                        </li> -->
                       <!--  <li class="nav-item mr-0 mb-2">
                            <a class="nav-link br-sm font-size-md ls-normal" href="#tab1-4">Featured</a>
                        </li> -->
                    </ul>
                </div>
                <!-- End of Tab -->
                <div class="tab-content product-wrapper appear-animate">
                    <div class="tab-pane active pt-4" id="tab1-1">
                            <div class="row cols-xl-5 cols-md-4 cols-sm-3 cols-2">
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
<input type="hidden" name="pincodestatuscheck" id="pincodestatuscheck" value="<?php if($this->session->userdata('pincodes') && $this->session->userdata('pincodes') !='') {echo 1;}else {echo '';} ?>">

                                <div class="product text-center">
                                    <figure class="product-media">
                                        <a href="<?= base_url().'products/'.$arrive->page_url;?>">
                                            <img src="<?= base_url().$arrive->image;?>" alt="Product"
                                                width="300" height="338" />
                                            <img src="<?= base_url().$arrive->image;?>" alt="Product"
                                                width="300" height="338" />
                                        </a>
                                        <div class="product-action-vertical">
                                            <!-- <a href="#" data-pid="<?= encode($arrive->pid);?>" class="btn-product-icon btn-cart w-icon-cart"
                                                title="Add to cart" data-id="1" id="cartBack<?= encode($arrive->pid);?>"></a> -->
                                                <?php

                                                if (!empty($disc) && $disc != 0) {
                                                    ?>
                                                     <a href="javascript:void(0)" id="discounttag<?= $arrive->pid;?>" class="btn-product-icon"
                                                 ><?php echo round($disc); ?>% off</a>
                                                    <?php
                                                }
                                                ?>
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
                                       
                                        <div class="product-price" style="margin-top: 10px">
                                            <ins class="new-price" id="sellprice<?= $arrive->pid;?>"><i class="fas fa-rupee-sign" style="margin-right: 2px"></i> <?php echo  number_format($arrive->price);?></ins>
                                            <?php
                                                if(!empty($arrive->mrp)) {
                                                    ?>
                                                    <del class="old-price" id="sellmrp<?= $arrive->pid;?>">MRP <i class="fas fa-rupee-sign" style="margin-right: 5px; margin-left: 5px;"></i> <?php echo  $arrive->mrp;?></del>
                                                    <?php
                                                }
                                            ?>
                                              
                                        </div>
                                        <button class="btn btn-primary btn-carts" type="button" data-toggle = "modal" data-target = "#exampleModal" data-pid="<?= encode($arrive->pid);?>" data-psid="<?= encode($arrive->psid);?>" style="width:84%; border-radius:16px;" data-wen="<?= $arrive->pid;?>"> 
                                            
                                            <span>Add to </span>
                                            <i class="w-icon-cart" style="margin-left:5px;"></i>
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
                    <!-- End of Tab Pane -->
                    <div class="tab-pane pt-4" id="tab1-2">
                        <div class="row cols-xl-5 cols-md-4 cols-sm-3 cols-2">
                            <?php
                            if(count($best) >0) {
                                foreach ($best as $bes) {
                                    $mrp = $bes->mrp;
                                    $sell = $bes->price;
                                    $disc = $this->home_db->discount($mrp, $sell);
                                    ?>
                                
                            <div class="product-wrap">
                                <div class="product text-center">
                                    <figure class="product-media">
                                        <a href="<?= base_url().'products/'.$bes->page_url;?>">
                                            <img src="<?= base_url().$bes->image;?>" alt="Product"
                                                width="300" height="338" />
                                            <img src="<?= base_url().$bes->image;?>" alt="Product"
                                                width="300" height="338" />
                                        </a>
                                        <div class="product-action-vertical">
                                            <!-- <a href="#" data-pid="<?= encode($bes->pid);?>" class="btn-product-icon btn-cart w-icon-cart" data-id="1"
                                                title="Add to cart"></a> -->
                                            <a href="<?= base_url().'products/'.$bes->page_url;?>" class="btn-product-icon btn-wishlist w-icon-heart"
                                                title="Add to wishlist" id="wishlist" data-pid="<?= encode($bes->pid);?>"></a>

                                                 <?php

                                                if (!empty($disc) && $disc != 0) {
                                                    ?>
                                                     <a href="javascript:void(0)" id="discounttag<?= $bes->pid;?>" class="btn-product-icon"
                                                 ><?php echo round($disc); ?>% off</a>
                                                    <?php
                                                }
                                                ?>


                                        </div>
                                    </figure>
                                    <div class="product-details">
                                        <h4 class="product-name"><a href="#"><?php echo ucfirst($bes->title);?></a></h4>
                                        <div class="ratings-container">
                                            <div class="ratings-full">
                                                <span class="ratings" style="width: 60%;"></span>
                                                <span class="tooltiptext tooltip-top"></span>
                                            </div>
                                        </div>
                                        <div class="product-price">
                                            <ins class="new-price " id="sellprices<?= $bes->pid;?>"><i class="fas fa-rupee-sign" style="margin-right: 2px"></i> <?php echo  number_format($bes->price);?></ins>
                                             <?php
                                                if(!empty($bes->mrp)) {
                                                    ?>
                                                    <del class="old-price" id="sellmrps<?= $bes->pid;?>">MRP <i class="fas fa-rupee-sign" style="margin-right: 5px"></i> <?php echo  $bes->mrp;?></del>
                                                    <?php
                                                }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                       
                                    <?php
                                }
                            }
                        ?>
                        </div>
                    </div>
                   
                    <!-- End of Tab Pane -->
                    <div class="tab-pane pt-4" id="tab1-4">
                        <div class="row cols-xl-5 cols-md-4 cols-sm-3 cols-2">
                            <?php
                            if(count($feature) >0) {
                                foreach ($feature as $feat) {
                                    $mrp = $feat->mrp;
                                    $sell = $feat->price;
                                    $disc = $this->home_db->discount($mrp, $sell);
                                    ?>
                                
                            <div class="product-wrap">
                                <div class="product text-center">
                                    <figure class="product-media">
                                        <a href="<?= base_url().'products/'.$feat->page_url;?>">
                                            <img src="<?= base_url().$feat->image;?>" alt="Product"
                                                width="300" height="338" />
                                            <img src="<?= base_url().$feat->image;?>" alt="Product"
                                                width="300" height="338" />
                                        </a>
                                        <div class="product-action-vertical">
                                           <!--  <a href="#" data-id="1" data-pid="<?= encode($feat->pid);?>" class="btn-product-icon btn-cart w-icon-cart"
                                                title="Add to cart" id="cartBack<?= encode($feat->pid);?>"></a> -->
                                                 <?php

                                                if (!empty($disc) && $disc != 0) {
                                                    ?>
                                                     <a href="javascript:void(0)" id="discounttag<?= $bes->pid;?>" class="btn-product-icon"
                                                 ><?php echo round($disc); ?>% off</a>
                                                    <?php
                                                }
                                                ?>
                                            <a href="#" class="btn-product-icon btn-wishlist w-icon-heart"
                                                title="Add to wishlist" id="wishlist" data-pid="<?= encode($feat->pid);?>"></a>

                                               


                                        </div>
                                    </figure>
                                    <div class="product-details">
                                        <h4 class="product-name"><a href="<?= base_url().'products/'.$feat->page_url;?>"><?php echo ucfirst($feat->title);?></a></h4>
                                      
                                        <div class="product-price">
                                            <ins class="new-price"><i class="fas fa-rupee-sign" style="margin-right: 2px"></i> <?php echo  number_format($feat->price);?></ins>
                                              <?php
                                                if(!empty($feat->mrp)) {
                                                    ?>
                                                    <del class="old-price"><i class="fas fa-rupee-sign" style="margin-right: 5px"></i> <?php echo  $feat->mrp;?></del>
                                                    <?php
                                                }
                                            ?>
                                        </div>
                                        
                                        
                                        
                                    </div>
                                </div>
                            </div>
                       
                                    <?php
                                }
                            }
                        ?>
                        </div>
                    </div>
                    <!-- End of Tab Pane -->
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
                     <?php

                if(count($category) >0) {
                  foreach ($category as $cat) {
                    $id = $cat->cat_id;
                    $newq = "select p.id as pid,p.featuredimg as image,p.ptitle as title,ps.selling_price as price,ps.stock,p.page_url,c.page_url as cpage_url,ps.mrp,ps.sid,ps.pro_size as psid from products p left join product_size ps on ps.pid=p.id left join category c on c.id=p.cat_id where p.cat_id =".$id." and p.status =0 group by ps.pid order by CAST(ps.selling_price AS DECIMAL(10,2)) limit 8 ";
                    $getProducts = $this->master_db->sqlExecute($newq);
                    //echo $this->db->last_query();
                    $getProducts1 = $this->home_db->getCategorywiseproducts($id,'asc','8');
                   
                    ?>
                    <div class="product-wrapper-1 appear-animate mb-5">
                    <div class="title-link-wrapper pb-1 mb-4">
                        <h2 class="title ls-normal mb-0"><?= ucfirst($cat->cname);?></h2>
                        <a href="<?= base_url().'home/products/'.icchaEncrypt($cat->cat_id);?>" class="font-size-normal font-weight-bold ls-25 mb-0">More
                            Products<i class="w-icon-long-arrow-right"></i></a>
                    </div>
                    <div class="row">
                       <?php
                            if(!empty($cat->ads_image)) {
                                ?>
                                <div class="col-lg-3 col-sm-4 mb-4">
                            <div class="banner h-100 br-sm" style="background-image: url(<?= base_url().$cat->ads_image;?>); 
                                background-color: #ebeced;">
                            </div>
                        </div>
                        <!-- End of Banner -->
                        <div class="col-lg-9 col-sm-8">

                            <div class="swiper-container swiper-theme" data-swiper-options="{
                                'spaceBetween': 20,
                                'slidesPerView': 2,
                                'breakpoints': {
                                    '992': {
                                        'slidesPerView': 3
                                    },
                                    '1200': {
                                        'slidesPerView': 4
                                    }
                                }
                            }">

                                <div class="swiper-wrapper row cols-xl-4 cols-lg-3 cols-2">
                                       <?php
                                if(count($getProducts) >0) {
                                    foreach ($getProducts as $products) {
                                        $pid = $products->pid;
                                        $mrp = $products->mrp;
                                    $sell = $products->price;
                                    $disc = $this->home_db->discount($mrp, $sell);
                                    $getSizes = $this->master_db->sqlExecute('select ps.pro_size as psid,s.sname from product_size ps left join sizes s on s.s_id = ps.sid where ps.pid ='.$pid.'');
                                        ?>
                                        <input type="hidden" name="sizes" id="sizesnew<?= $products->pid;?>" value="<?= $products->psid;?>">
<input type="hidden" name="pincodestatuscheck" id="pincodestatuscheck" value="<?php if($this->session->userdata('pincodes') && $this->session->userdata('pincodes') !='') {echo 1;}else {echo '';} ?>">
                                         <div class="swiper-slide product-col">
                                        <div class="product-wrap product text-center">
                                            <figure class="product-media">
                                                <a href="<?= base_url().'products/'.$products->page_url;?>">
                                                    <img src="<?= base_url().$products->image;?>" alt="Product"
                                                        width="216" height="243" />
                                                </a>
                                                <div class="product-action-vertical">
                                                    <!-- <a href="#" data-id="1" data-pid="<?= encode($products->pid);?>" class="btn-product-icon btn-cart w-icon-cart"
                                                        title="Add to cart"></a> -->
                                                           <?php

                                                if (!empty($disc) && $disc != 0) {
                                                    ?>
                                                     <a href="javascript:void(0)" id="discounttag1<?= $products->pid;?>" class="btn-product-icon"
                                                 ><?php echo round($disc); ?>% off</a>
                                                    <?php
                                                }
                                                ?>
                                                    <a href="#" class="btn-product-icon btn-wishlist w-icon-heart"
                                                        title="Add to wishlist" id="wishlist" data-pid="<?= encode($products->pid);?>"></a>
                                                   
                                                 
                                                </div>
                                            </figure>
                                            <div class="product-details">
                                                <h4 class="product-name"><a href="<?= base_url().'products/'.$products->page_url;?>"><?= ucfirst($products->title);?></a>
                                                </h4>
                                                 <div class="flex-wrap d-flex align-items-center product-variations">
                                                <?php
                                            if(is_array($getSizes) && !empty($getSizes)) {
                                                foreach ($getSizes as $key => $size) {
                                                   ?>
                                                       <a href="javascript:void(0)" onclick="listsizeses(<?= $products->pid;?>,<?= $size->psid;?>)" class="sizelist size newsizeof<?= $size->psid;?> <?php if($size->psid == $products->psid) {echo 'newactive';}?>" id="sizef<?= $size->psid;?>" style="width:50px;"><?= $size->sname; ?></a>
                                                   
                                                 
                                                   <?php
                                                }
                                            }
                                        ?>
                                    </div>
                                               
                                                <div class="product-price">
                                                    <ins class="new-price sellprices<?= $products->pid;?>" id="sellpricesss<?= $products->pid;?>"><i class="fas fa-rupee-sign" style="margin-right: 2px"></i> <?= number_format($products->price)?></ins>
                                                     <?php
                                                if(!empty($products->mrp)) {
                                                    ?>
                                                    <del class="old-price sellmrps" id="sellmrpsss<?= $products->pid;?>">MRP <i class="fas fa-rupee-sign" style="margin-right: 5px"></i> <?php echo  $products->mrp;?></del>
                                                    <?php
                                                }
                                            ?>
                                                </div>

                                                <button class="btn btn-primary btn-carts" type="button" data-pid="<?= encode($products->pid);?>" data-psid="<?= encode($products->psid);?>" style="width:84%; border-radius:16px;" data-wen="<?= $products->pid;?>"> 
                                            
                                            <span>Add to </span>
                                            <i class="w-icon-cart" style="margin-left:5px;"></i>
                                        </button> 
                                            </div>
                                        </div>
                                    </div>
                                        <?php
                                    }
                                }
                             ?>

                                </div>
                                <div class="swiper-pagination"></div>
                            </div>
                             <div class="swiper-container swiper-theme" data-swiper-options="{
                                'spaceBetween': 20,
                                'slidesPerView': 2,
                                'breakpoints': {
                                    '992': {
                                        'slidesPerView': 3
                                    },
                                    '1200': {
                                        'slidesPerView': 4
                                    }
                                }
                            }">

                           
                                <div class="swiper-pagination"></div>
                            </div>
                        </div>
                                <?php
                            }else {
                                 ?>
                                
                        <!-- End of Banner -->
                        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                            <div class="swiper-container swiper-theme" data-swiper-options="{
                                'spaceBetween': 20,
                                'slidesPerView': 2,
                                'breakpoints': {
                                    '992': {
                                        'slidesPerView': 3
                                    },
                                    '1200': {
                                        'slidesPerView': 4
                                    }
                                }
                            }">

                                <div class="swiper-wrapper row cols-xl-4 cols-lg-3 cols-2">
                                       <?php
                                if(count($getProducts) >0) {
                                    foreach ($getProducts as $products) {
                                         $pid = $products->pid;
                                         $mrp = $products->mrp;
                                    $sell = $products->price;
                                    $disc = $this->home_db->discount($mrp, $sell);
                                    $getSizes = $this->master_db->sqlExecute('select ps.pro_size as psid,s.sname from product_size ps left join sizes s on s.s_id = ps.sid where ps.pid ='.$pid.'');
                                        ?>
                                         <div class="swiper-slide product-col">
                                        <div class="product-wrap product text-center">
                                            <figure class="product-media">
                                                <a href="<?= base_url().'products/'.$products->page_url;?>">
                                                    <img src="<?= base_url().$products->image;?>" alt="Product"
                                                        width="216" height="243" />
                                                </a>
                                                <div class="product-action-vertical">
                                                    <!-- <a href="#" data-id="1" data-pid="<?= encode($products->pid);?>" class="btn-product-icon btn-cart w-icon-cart"
                                                        title="Add to cart"></a> -->
                                                                  <?php

                                                if (!empty($disc) && $disc != 0) {
                                                    ?>
                                                     <a href="javascript:void(0)" id="discounttag1<?= $products->pid;?>" class="btn-product-icon"
                                                 ><?php echo round($disc); ?>% off</a>
                                                    <?php
                                                }
                                                ?>
                                                    <a href="#" class="btn-product-icon btn-wishlist w-icon-heart"
                                                        title="Add to wishlist" id="wishlist" data-pid="<?= encode($products->pid);?>"></a>
                                                
                                                  
                                                </div>
                                            </figure>
                                            <div class="product-details">
                                                <h4 class="product-name"><a href="<?= base_url().'products/'.$products->page_url;?>"><?= ucfirst($products->title);?></a>
                                                </h4>
                                                 <?php
                                            if(is_array($getSizes) && !empty($getSizes)) {
                                                foreach ($getSizes as $key => $size) {
                                                   ?>
                                                       <a href="javascript:void(0)" onclick="listsize(<?= $products->pid;?>,<?= $size->psid;?>)" class="sizelist newsizeof<?= $size->psid;?> <?php if($size->psid == $products->psid) {echo 'newactive';}?>" id="sizef<?= $size->psid;?>"><?= $size->sname; ?></a>
                                                   
                                                 
                                                   <?php
                                                }
                                            }
                                        ?>
                                                
                                                <div class="product-price">
                                                    <ins class="new-price"><i class="fas fa-rupee-sign" style="margin-right: 5px"></i> <?= number_format($products->price)?></ins>
                                                </div>

                                                  <button class="btn btn-primary btn-carts" type="button" data-pid="<?= encode($products->pid);?>" data-psid="<?= encode($products->psid);?>" style="width:84%; border-radius:16px;" data-wen="<?= $products->pid;?>"> 
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
                                <div class="swiper-pagination"></div>
                            </div>
                             <div class="swiper-container swiper-theme" data-swiper-options="{
                                'spaceBetween': 20,
                                'slidesPerView': 2,
                                'breakpoints': {
                                    '992': {
                                        'slidesPerView': 3
                                    },
                                    '1200': {
                                        'slidesPerView': 4
                                    }
                                }
                            }">

            
                                <div class="swiper-pagination"></div>
                            </div>
                        </div>
                                <?php
                            }
                       ?>
                        
                    </div>
                </div>
                    <?php
                  }
                }
                ?>
               
                <!-- End of Product Wrapper 1 -->
              
                <!-- End of Banner Fashion -->


              
                <!-- End of Brands Wrapper -->


                <!-- End of Reviewed Producs -->
            </div>
            <!--End of Catainer -->
        </main>
        <!-- End of Main -->
<?= $footer;?>

<style>
.modal {
  display: none; 
  position: fixed; 
  z-index: 100000; 
  padding-top: 100px; 
  left: 0;
  top: 0;
  width: 100%; 
  height: 100%;
  overflow: auto; 
  background-color: rgb(0,0,0); 
  background-color: rgba(0,0,0,0.4);
}


.modal-content {
  background-color: #fefefe;
  margin: auto;
  padding: 20px;
  border: 1px solid #888;
  width: 400px;
}


.close {
  color: #aaaaaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: #000;
  text-decoration: none;
  cursor: pointer;
}
</style>

<div id="myModal" class="modal">
<div class="modal-content">
    <span class="close">&times;</span>
    <form id="checkhomepin" method="post">
         <input type="hidden" class="csrf_token" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
        <input type="hidden" name="pid" id="pincodedata" />
        <input type="hidden" name="psid" id="psid" />
        <input type="hidden" name="sid" id="sid" />
        <div class="input-group">
        <input type="number" name="cpincodes" id="cpincode"  maxlength="6" minlength="6" pattern="/^-?\d+\.?\d*$/" onkeypress="if(this.value.length==6) return false;" class="form-control" placeholder="Enter Pin Code" required>
        
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
    <div id="pincode_error"></div>
</div>
</div>

<?= $js1;?>

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
                    $("#discounttag"+pid).html(data.discount);
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
                    $("#discounttag1"+pid).html(data.discount);
                }else {

                }
            }
       });
   }

</script>

<script type="text/javascript">
var modal = document.getElementById("myModal");
var span = document.getElementsByClassName("close")[0];
span.onclick = function() {
  modal.style.display = "none";
}
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}


</script>
