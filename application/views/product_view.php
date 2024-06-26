<?php
$rate = $this->home_db->getratings($products[0]->pid);

if($this->session->userdata('propsid')) {
    $pdid=$this->session->userdata('propsid');
    $getProdsize = $this->master_db->getRecords('product_size',['pid'=>$products[0]->pid,'pro_size'=>$pdid],'*');
}else {
    $getProdsize = $this->master_db->getRecords('product_size',['pid'=>$products[0]->pid,'pro_size'=>$products[0]->psid],'*');
}
$outofstockprod = $this->master_db->getRecords('product_size',['pro_size'=>$products[0]->psid,'stock !='=>0],'*');
?>
<?= $header1;?>
<style type="text/css">
    <style>
        .btm-br{
            border-bottom: 1px solid #ccc;
            margin-right: 25px;
        }
         .availability {
            margin-top: 20px;
        }
        .availability input {
    display: inline-block;
    vertical-align: middle;
    margin-left: 0px;
    padding: 8px;
}
.fix-bottom {
    display: none!important
}

.free-dev {
    background-color: #0989b0;
    padding: 10px;
    border-radius: 5px;
    color: #fff;
    display: inline;
}


p {
    margin-bottom: 0px!important;
}
/* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
}
#pincodeStatus {
    display: none
}
#checkpincode {
    margin-bottom: 10px;
}
    </style>

 <main class="main mb-10 pb-1">
 
  <!-- Start of Page Header -->
            <div class="page-header">
                <div class="container">
                    <h1 class="page-title mb-0">Shop </h1>
                </div>
            </div>
            <!-- End of Page Header -->
            
            <!-- Start of Breadcrumb -->
            <nav class="breadcrumb-nav container">
                <ul class="breadcrumb bb-no">
                    <li><a href="<?= base_url();?>">Home</a></li>
                </ul>
               
            </nav>
            <!-- End of Breadcrumb -->

            <!-- Start of Page Content -->
            <div class="page-content">
                <div class="container">
                    <div class="product product-single row">
                        <div class="col-md-6 mb-6">
                            <div class="product-gallery product-gallery-sticky product-gallery-vertical">
                                <div class="swiper-container product-single-swiper swiper-theme nav-inner" data-swiper-options="{
                                    'navigation': {
                                        'nextEl': '.swiper-button-next',
                                        'prevEl': '.swiper-button-prev'
                                    }
                                }">
                                    <div class="swiper-wrapper row cols-1 gutter-no">
                                       <?php 
                                            if(count($getImages) >0) {
                                                foreach ($getImages as $img) {
                                                    ?>
                                                    <div class="swiper-slide">
                                                        <figure class="product-image">
                                                            <img src="<?= base_url().$img->p_image;?>"
                                                                data-zoom-image="<?= base_url().$img->p_image;?>"
                                                                alt="Swarnagowri" width="800" height="900">
                                                        </figure>
                                                    </div>
                                                    <?php
                                                }
                                            }
                                        ?>
                                    </div>
                                    <button class="swiper-button-next"></button>
                                    <button class="swiper-button-prev"></button>
                                    <a href="#" class="product-gallery-btn product-image-full"><i class="w-icon-zoom"></i></a>
                                </div>
                                <div class="product-thumbs-wrap swiper-container" data-swiper-options="{
                                    'navigation': {
                                        'nextEl': '.swiper-button-next',
                                        'prevEl': '.swiper-button-prev'
                                    },
                                    'breakpoints': {
                                        '992': {
                                            'direction': 'vertical',
                                            'slidesPerView': 'auto'
                                        }
                                    }
                                }">
                                    <div class="product-thumbs swiper-wrapper row cols-lg-1 cols-4 gutter-sm">
                                        <?php 
                                            if(count($getImages) >0) {
                                                foreach ($getImages as $img) {
                                                    ?>
                                                    <div class="product-thumb swiper-slide">
                                                        <img src="<?= base_url().$img->p_image;?>" alt="Product Thumb" width="800" height="900">
                                                    </div>
                                                    <?php
                                                }
                                            }
                                        ?>

                                    </div>
                                    <button class="swiper-button-prev"></button>
                                    <button class="swiper-button-next"></button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4 mb-md-6">
                            <div class="product-details">
                                <h1 class="product-title"><?= ucfirst($products[0]->title);?></h1>
                                
                              <?php /*  <div class="product-bm-wrapper">
                                         
                                    <?php
                                        if(!empty($products[0]->brand_id) && $products[0]->brand_id !="") {
                                            $getBrand = $this->master_db->getRecords('brand',['id'=>$products[0]->brand_id],'name');
                                           
                                            ?>
                                           <figure class="brand">
                                               <?= $getBrand[0]->name;?>
                                            </figure>
                                            <?php
                                        }
                                    ?>
                                </div> */?>

                                <!--<hr class="product-divider">


-->

<?php
        if(count($outofstockprod) >0) {
            ?>
                   <div class="product-price"><ins class="new-price" id="sellprice<?= $products[0]->pid;?>"><i class="fas fa-rupee-sign"></i> <?php if(is_array($getProdsize) && !empty($getProdsize) && count($getProdsize) >0) {echo @$getProdsize[0]->selling_price; }else { echo @$products[0]->price;}?></ins>  <span class="saving-amount"><?php if(is_array($getProdsize) && !empty($getProdsize) && count($getProdsize) >0) { echo "Saved <i class='fas fa-rupee-sign' style='margin-right:3px;'></i>".(int)$getProdsize[0]->mrp - (int)$getProdsize[0]->selling_price; }else {echo "Saved <i class='fas fa-rupee-sign' style='margin-right:3px;'></i>".(int)$products[0]->mrp - (int)$products[0]->price;}?> </span>

                                </div>
                                 <?php
                                                if(!empty($products[0]->mrp)) {
                                                    ?>
                                                    <del class="old-price" style="font-size: 16px!important;" id="sellmrp<?= $products[0]->pid;?>">MRP <i class="fas fa-rupee-sign" style="margin-right: 2px"></i> <?php if(is_array($getProdsize) && !empty($getProdsize[0])) { echo $getProdsize[0]->mrp; }else {echo  $products[0]->mrp; }?></del>
                                                    <?php
                                                }
                                            ?>
            <?php
        }else {
            ?>
                <div class="product-price"><ins class="new-price" id="sellprice<?= $products[0]->pid;?>" style="display: none;"></div>
                                
                                                    <del class="old-price" style="font-size: 16px!important;" id="sellmrp<?= $products[0]->pid;?>" style="display: none"></del>
                                              
            <?php
        }
?>
                             

                                <div class="ratings-container">
                                  
                                    <a href="#" class="rating-reviews">(<?= count($reviews);?> Reviews)</a>
                                </div>

                                <div class="product-short-desc lh-2">
                                 <!--   <ul class="list-type-check list-style-none">
                                        <li>Ultrices eros in cursus turpis massa cursus mattis.</li>
                                        <li>Volutpat ac tincidunt vitae semper quis lectus.</li>
                                        <li>Aliquam id diam maecenas ultricies mi eget mauris.</li>
                                    </ul> -->

                                   <?php
                                        if(!empty($products[0]->pspec)) {
                                            ?>
                                             <h4 class="mb-0 mt-3"> Specifications</h4>
                                             <?= $products[0]->pspec;?>
                                            <?php
                                        }
                                   ?>
                                    


                                </div>

                                <hr class="product-divider">

                               
                                 <div id="size_error" style="clear: both"></div><br />
                                <div class="product-form product-variation-form product-size-swatch">
                                    <label class="mb-1">Size:</label>
                                        <div class="flex-wrap d-flex align-items-center product-variations">

                                           <?php 
                                                if(count($getSize) >0) {
                                                    foreach ($getSize as $size) {
                                                        ?>
                                                        <a href="#" onclick="sizechangelist(<?= $size->psid;?>,<?= $products[0]->pid;?>)" class="size <?php if(is_array($getProdsize) && !empty($getProdsize) && count($getProdsize) >0) {if($getProdsize[0]->sid ==$size->sid) {echo 'active';} }else {if($products[0]->sid ==$size->sid) {echo 'active';}}?>" data-sid="<?= $size->psid;?>"><?= $size->sname;?></a>
                                                        <?php
                                                    }
                                                }
                                            ?>
                                    </div>
                                 </div>
                                
                              
                                <div class="fix-bottom product-sticky-content sticky-content" style="margin-top: 20px">
                                    <div class="product-form container">
                                        <input type="hidden" name="colors" id="colors" value="<?= $products[0]->coid;?>">
                                        <input type="hidden" name="sizes" id="sizesnew" value="<?php if(is_array($getProdsize) && !empty($getProdsize)) {echo $getProdsize[0]->pro_size;}else {echo $products[0]->psid;}?>">
                                        <input type="hidden" name="checkpincodevalid" id="checkpincodevalid" value="<?php if($this->session->userdata('pincodes')) {echo $this->session->userdata('pincodes');}?>">
                                      <!--   <button class="btn btn-primary btn-cartbtn" data-pid="<?= encode($products[0]->pid);?>" id="cartBack<?= encode($products[0]->pid);?>" data-psid="<?= encode($products[0]->psid);?>" style="width:40%">
                                            <i class="w-icon-cart"></i>
                                            <span>Add to Cart</span>
                                        </button> -->
                                        
                                    </div>

                                   
                                </div>
                                
                                
                                <div class="availability p-0">
                                    <div id="checkpin" class="pincode_form">
                                            <label style="font-size: 17px;margin-bottom: 6px">Pincode Check</label>
                                            <br />
                                            <input type="number" maxlength="6" minlength="6" placeholder="Enter Pincode" class="checkpincode form-control" name="cpincode" id="cpincode" maxlength="6" minlength="6" pattern="/^-?\d+\.?\d*$/" onkeypress="if(this.value.length==6) return false;" style="width:50%" value="<?php if($this->session->userdata('pincodes') && $this->session->userdata('pincodes') !='') {echo $this->session->userdata('pincodes');}else {echo '';} ?>">
                                          <br />
                                         <span id="pincode_error" class="text-danger" style="color:red"></span>
                                         <span id="showMsg"></span>
                                         <span style="color:red">(Shipping calculated at checkout)</span>
                                    </div>
                                </div>
                                <br />
                                <div id="producterrormsg" class="text-danger"></div>
                                <?php
        if(count($outofstockprod) >0) {
            ?>
                                  <button class="btn btn-primary btn-cartbtn" id="productviewlistbtn<?= $products[0]->pid;?>" data-pid="<?= encode($products[0]->pid);?>" id="cartBack<?= encode($products[0]->pid);?>" data-psid="<?= encode($products[0]->psid);?>" style="width:50%">
                                            <span>Add to </span>
                                            <i class="w-icon-cart" style="margin-left:5px;"></i>
                                            
                                        </button>

            <?php

        }   else {
 ?>
 <p id="hideoutofstocklistview<?= $products[0]->pid;?>" style="color:red; margin-top: 8px;
    font-size: 16px;">Out of Stock</p>
                                  <button class="btn btn-primary btn-cartbtn" id="productviewlistbtn<?= $products[0]->pid;?>" data-pid="<?= encode($products[0]->pid);?>" id="cartBack<?= encode($products[0]->pid);?>" data-psid="<?= encode($products[0]->psid);?>" style="width:50%;display: none">
                                            <span>Add to </span>
                                            <i class="w-icon-cart" style="margin-left:5px;"></i>
                                            
                                        </button>

            <?php
        }
        ?>

<br><br>
                                        <p class="free-dev">Buy Products Above Rs.300/- Get Free Delivery</p>
                                        <br><br>
                            <!--     <div class="social-links-wrapper">
                                    <div class="social-links">
                                        <div class="social-icons social-no-color border-thin">
                                            <a href="#" class="social-icon social-facebook w-icon-facebook"></a>
                                            <a href="#" class="social-icon social-twitter w-icon-twitter"></a>
                                            <a href="#" class="social-icon social-pinterest fab fa-pinterest-p"></a>
                                            <a href="#" class="social-icon social-whatsapp fab fa-whatsapp"></a>
                                            <a href="#" class="social-icon social-youtube fab fa-linkedin-in"></a>
                                        </div>
                                    </div>
                                    <span class="divider d-xs-show"></span>
                                    <div class="product-link-wrapper d-flex">
                                        <a href="#" class="btn-product-icon btn-wishlist w-icon-heart" id="wishlist" data-pid="<?= encode($products[0]->pid);?>"><span></span></a>
                                       
                                    </div>
                                </div> -->
                            </div>
                        </div>
                    </div>
                    <div class="tab tab-nav-boxed tab-nav-underline product-tabs mt-3">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a href="#product-tab-description" class="nav-link active">Description</a>
                            </li>
                            <li class="nav-item">
                                <a href="#product-tab-reviews" class="nav-link">Reviews (<?= count($reviews);?>)</a>
                            </li>
                            
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="product-tab-description">
                                <div class="row mb-4">
                                    <div class="col-md-6 mb-5">
                                       <?php 
                                        if(!empty($products[0]->pdesc)) {
                                            echo $products[0]->pdesc;
                                        }
                                       ?>
                                    </div>
                                    <div class="col-md-6 mb-5">
                                        <div class="banner banner-video product-video br-xs">
                                            <?php
                                            if(!empty($products[0]->youtubelink)) {
                                                ?>
                                                <iframe width="100%" height="315" src="https://www.youtube.com/embed/<?= $products[0]->youtubelink; ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                                <?php
                                            }
                                           ?>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="tab-pane" id="product-tab-reviews">
                                <div class="row mb-4">
                                    <div class="col-xl-4 col-lg-5 mb-4">
                                        <div class="ratings-wrapper">
                                            <div class="avg-rating-container">
                                                <h4 class="avg-mark font-weight-bolder ls-50"><?= ceil($rate);?></h4>
                                                <div class="avg-rating">
                                                    <p class="text-dark mb-1">Average Rating</p>
                                                    <div class="ratings-container">
                                                        <a href="#" class="rating-reviews">(<?= count($reviews);?> Reviews)</a>
                                                    </div>
                                                </div>
                                            </div>
                                           
                                        </div>
                                    </div>
                                    <div class="col-xl-8 col-lg-7 mb-4">
                                        <div class="review-form-wrapper">
                                            <div id="processing"></div>
                                            <h3 class="title tab-pane-title font-weight-bold mb-1">Submit Your Review
                                            </h3>
                                           
                                            <form id="review" method="POST" enctype="multipart/form-data">
                                                <input type="hidden" class="csrf_token" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                                                <input type="hidden" name="pid" value="<?= encode($products[0]->pid);?>">
                                                <div class="rating-form">
                                                    <label for="rating">Your Rating Of This Product :</label>
                                                    <span class="rating-stars">
                                                        <a class="star-1" rel="1" href="#">1</a>
                                                        <a class="star-2" rel="2" href="#">2</a>
                                                        <a class="star-3" rel="3" href="#">3</a>
                                                        <a class="star-4" rel="4" href="#">4</a>
                                                        <a class="star-5" rel="5" href="#">5</a>
                                                    </span>
                                                   <input type="hidden" name="ratings" id="ratings">
                                                </div>
                                                <textarea cols="30" rows="6" placeholder="Write Your Review Here..."
                                                    class="form-control" name="reviews" id="reviews"></textarea>
                                                    <span id="rating_error" class="text-danger" style="color:red"></span>
                                              <br />
                                                <button type="submit" class="btn btn-dark">Submit Review</button>
                                            </form>
                                            <div style="margin-top: 20px">
                                            <?php
                                                if(count($reviews) >0) {
                                                    foreach ($reviews as $key => $value) {
                                                      ?>
                                                        <p><strong> - <?= ucfirst($value->name);?></strong></p>
                                                        <p><span >Reviewed On : <?= date('d-m-Y',strtotime($value->created_at));?></span></p>
                                                         <p<?php /*?> style="float: left;margin-right: 60px;"<?php */?>><?= $value->review_descp;?></p>
                                                         
                                                         <?php
                                                            if($value->rating ==1) {
                                                                ?>
                                                                <i class="fas fa-star"></i>
                                                                <?php
                                                            }
                                                            else if($value->rating ==2) {
                                                                ?>
                                   <i class="fas fa-star"></i>
                                   <i class="fas fa-star"></i>
                                                                <?php
                                                            }
                                                              else if($value->rating ==3) {
                                                                ?>
                                                     <i class="fas fa-star"></i>
                                                     <i class="fas fa-star"></i>
                                                     <i class="fas fa-star"></i>
                                                                <?php
                                                            }
                                                             else if($value->rating ==4) {
                                                                ?>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                                                <?php
                                                            }
                                                             else if($value->rating ==5) {
                                                                ?>
                                                             <i class="fas fa-star"></i>
                                                             <i class="fas fa-star"></i>
                                                             <i class="fas fa-star"></i>
                                                             <i class="fas fa-star"></i>
                                                             <i class="fas fa-star"></i>
                                                                <?php
                                                            }
                                                         ?>
                                                      <?php  
                                                    }
                                                }
                                            ?>
                                           </div>
                                        </div>
                                    </div>
                                </div>

                               
                            </div>

                             <div class="tab-pane " id="pbrochure">
                                <div class="row mb-4">
                                    <div class="col-md-12 col-xs-12 col-lg-12 mb-5">
                                     
                                    </div>
                                   
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    
                    <section class="related-product-section">
                        <div class="title-link-wrapper mb-4">
                            <h4 class="title">Related Products</h4>
                         
                        </div>
                        <div class="swiper-container swiper-theme" data-swiper-options="{
                            'spaceBetween': 20,
                            'slidesPerView': 2,
                            'breakpoints': {
                                '576': {
                                    'slidesPerView': 3
                                },
                                '768': {
                                    'slidesPerView': 4
                                },
                                '992': {
                                    'slidesPerView': 4
                                }
                            }
                        }">
                            <div class="swiper-wrapper row cols-lg-3 cols-md-3 cols-sm-3 cols-2">
                                <?php
                                    if(count($related) >0) {
                                        foreach ($related as $value) {
                                            $mrp = $value->mrp;
                                    $sell = $value->price;
                                    $pid = $value->pid;
                                    $getSizes = $this->master_db->sqlExecute('select ps.pro_size as psid,s.sname from product_size ps left join sizes s on s.s_id = ps.sid where ps.pid ='.$pid.'');
                                    $disc = $this->home_db->discount($mrp, $sell);
                                              ?>
                                         <div class="swiper-slide product">
                                            <figure class="product-media">
                                                <a href="<?= base_url().'products/'.$value->page_url;?>">
                                                    <img src="<?= base_url().$value->image;?>" alt="Product"
                                                        width="300" height="338" />
                                                </a>
                                                <div class="product-action-vertical">
                                                
                                                
                                                    <a href="#" class="btn-product-icon btn-wishlist w-icon-heart"
                                                        title="Add to wishlist" id="wishlist" data-pid="<?= encode($value->pid);?>"></a>
                                                    
                                                </div>
                                                
                                            </figure>
                                            <div class="product-details align-items-center ">
                                                <h4 class="product-name" style="width:100%;text-align: center!important;"><a href="#"><?= ucfirst($value->title);?></a></h4>
                                         

                                        <div class="flex-wrap d-flex align-items-center product-variations">
                                                <?php
                                            if(is_array($getSizes) && !empty($getSizes)) {
                                                foreach ($getSizes as $key => $size) {
                                                   ?>
                                                       <a href="javascript:void(0)" onclick="listsizeses(<?= $value->pid;?>,<?= $size->psid;?>)" class="sizelist size newsizeof<?= $size->psid;?> <?php if($size->psid == $value->psid) {echo 'newactive';}?>" id="sizef<?= $size->psid;?>" style="width:50px;"><?= $size->sname; ?></a>
                                                   
                                                 
                                                   <?php
                                                }
                                            }
                                        ?>
                                    </div>
                                              <!--   <div class="product-pa-wrapper">
                                                    <div class="product-price" id="sellprice<?= $value->pid;?>"><i class="fas fa-rupee-sign"></i> <?= number_format($value->price);?></div>
                                                     <?php
                                                if(!empty($value->mrp)) {
                                                    ?>
                                                    <del class="old-price" id="sellmrp<?= $value->pid;?>"><i class="fas fa-rupee-sign" style="margin-right: 5px"></i> <?php echo  $value->mrp;?></del>
                                                    <?php
                                                }
                                            ?>
                                                </div> -->
                                                   <div class="product-price" style="text-align: center!important;align-items: center!important;">
                                                    <ins class="new-price sellprices<?= $value->pid;?>" id="sellpricesss<?= $value->pid;?>" style="margin-left:10px"> <i class="fas fa-rupee-sign" style="margin-right: 2px"></i> <?= number_format($value->price);?> </ins>
                                                     <?php
                                                if(!empty($value->mrp)) {
                                                    ?>
                                                    <del class="old-price sellmrps" id="sellmrpsss<?= $value->pid;?>"> <i class="fas fa-rupee-sign" style="margin-right: 5px"></i> <?php echo  $value->mrp;?></del>
                                                    <?php
                                                }
                                            ?>
                                              

                                            

                                              <?php 
                                                    if(!empty($value->mrp) && $value->mrp !="") {
                                                        ?>
                                                        <span class="savedamt" id="saveamt<?= $value->pid;?>"
                                                 >Saved <i class="fas fa-rupee-sign" style="margin-right: 5px;margin-left: 5px;"></i><?php echo $value->mrp - $value->price;?></span>
                                                        <?php
                                                    }
                                                ?>
                                                </div>
                                            </div>
                                             <button class="btn btn-primary btn-carts" type="button" data-pid="<?= encode($value->pid);?>" data-psid="<?= encode($value->psid);?>" style="width:100%" data-wen="<?= $value->pid;?>"> 
                                            <i class="w-icon-cart"></i>
                                            <span>Add to Cart</span>
                                        </button> 
                                        </div>
                                        <?php
                                        }
                                    }
                                ?>
                             
                            </div>
                        </div>
                    </section>
                </div>
            </div>
            <!-- End of Page Content -->
        </main>
        <!-- End of Main -->
        <?php echo $footer; ?>
        <?php echo $js; ?>
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
    

           function sizechangelist(sid,pid) {
            $.ajax({
                url :"<?= base_url().'Home/sizechangelist';?>",
                method:"post",
                data :{
                    sid:sid
                },
                dataType:"json",
                success:function(data) {
                    if(data.status ==true) {
                        $(".new-price").html(data.price);
                        $(".old-price").html(data.mrp);
                        $(".saving-amount").html(data.saved);
                        $(".product-price").show();
                        $(".new-price").show();
                        $(".btn-cartbtn").show();
                        $("#producterrormsg").html('');
                        $("#hideoutofstocklistview"+pid).hide();
                    }else if(data.status ==false) {
                        $(".product-price").hide();
                        $(".new-price").hide();
                        $(".btn-cartbtn").hide();
                        $("#producterrormsg").html('<p>'+data.msg+'</p>');
                        $("del").hide();
                        $("#hideoutofstocklistview"+pid).hide();
                    }
                }
            });
           }
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
                    $("#hideBtn"+pid).show();
                    $("#outofstockmsg"+pid).html('');
                    $("#productpricelist"+pid).show();
                }else if(data.status ==false) {
                    $("#hideBtn"+pid).hide();
                    $("#outofstockmsg"+pid).html('<p style="color:red">'+data.msg+'</p>');
                    $("#productpricelist"+pid).hide();
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
                    $("#hideNewbtn"+pid).show();
                    $("#outofstockcatmsg"+pid).html('');
                     $("#productprise"+pid).show();
                }else if(data.status ==false) {
                    $("#hideNewbtn"+pid).hide();
                    $("#outofstockcatmsg"+pid).html('<p style="color:red">'+data.msg+'</p>');
                    $("#productprise"+pid).hide();
                }
            }
       });
   }
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


