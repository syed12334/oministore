<?php
	//echo "<pre>";print_r($products);exit;
?>
<?= $header3;?>
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

</style>

    <main class="main">
    
     <!-- Start of Page Header -->
            <div class="page-header">
                <div class="container">
                    <h1 class="page-title mb-0">Shop </h1>
                </div>
            </div>
            <!-- End of Page Header -->
            
            <!-- Start of Breadcrumb -->
            <nav class="breadcrumb-nav">
                <div class="container">
                    <ul class="breadcrumb bb-no">
                        <!-- <li><a href="<?= base_url();?>">Home</a></li>
                        <li>Shop</li> -->
                    </ul>
                </div>
            </nav>
            <!-- End of Breadcrumb -->

            <!-- Start of Page Content -->
            <div class="page-content">
                <div class="container">



                    <!-- Start of Shop Content -->
                    <div class="shop-content row gutter-lg mb-10">
              
                        <!-- End of Shop Sidebar -->

                        <!-- Start of Shop Main Content -->
                        <div class="main-content" style="width: 100%!important;max-width: 100%!important;">
                            <div class="container">
                               
                            </div>
                           
                            <div class="product-wrapper row cols-md-4 cols-sm-2 cols-2">
                              <?php
  if(count($products) >0) {
    foreach ($products as $key => $prod) {
             $pid = $prod->pid;
                                        $mrp = $prod->mrp;
                                    $sell = $prod->price;
                                    $disc = $this->home_db->discount($mrp, $sell);
            $getSizes = $this->master_db->sqlExecute('select ps.pro_size as psid,s.sname from product_size ps left join sizes s on s.s_id = ps.sid where ps.pid ='.$pid.'');
      ?>
       <div class="product-wrap">
<input type="hidden" name="sizes" id="sizesnew<?= $prod->pid;?>" value="<?= $prod->psid;?>">
<input type="hidden" name="pincodestatuscheck" id="pincodestatuscheck" value="<?php if($this->session->userdata('pincodes') && $this->session->userdata('pincodes') !='') {echo 1;}else {echo '';} ?>">

                                <div class="product text-center">
                                    <figure class="product-media">
                                        <a href="<?= base_url().'rasapakaproview/'.$prod->page_url;?>">
                                            <img src="<?= base_url().$prod->image;?>" alt="Product"
                                                width="300" height="338" />
                                            <img src="<?= base_url().$prod->image;?>" alt="Product"
                                                width="300" height="338" />
                                        </a>
                                        <div class="product-action-vertical">
                                      
                                                <?php

                                                if (!empty($disc) && $disc != 0) {
                                                    ?>
                                                     <a href="javascript:void(0)" id="discounttag<?= $prod->pid;?>" class="btn-product-icon"
                                                 ><?php echo round($disc); ?>% off</a>
                                                    <?php
                                                }
                                                ?>
                                         <!--    <a href="<?= base_url().'rasapakaproview/'.$prod->page_url;?>" class="btn-product-icon btn-wishlist w-icon-heart"
                                                title="Add to wishlist" id="wishlist" data-pid="<?= encode($prod->pid);?>"></a> -->

                                                
                                        </div>
                                    </figure>
                                    <div class="product-details">
                                        <h4 class="product-name"><a href="#"><?php echo ucfirst($prod->title);?></a></h4>

                                        <div class="flex-wrap d-flex align-items-center product-variations">
                                        <?php
                                            if(is_array($getSizes) && !empty($getSizes)) {
                                                foreach ($getSizes as $key => $size) {
                                                   ?>
                                                       <a href="javascript:void(0)" onclick="listsize(<?= $prod->pid;?>,<?= $size->psid;?>)" class="sizelist size newsizeof<?= $size->psid;?> <?php if($size->psid == $prod->psid) {echo 'newactive';}?>" id="sizef<?= $size->psid;?>" style="width:50px;"><?= $size->sname; ?></a>
                                                   
                                                 
                                                   <?php
                                                }
                                            }
                                        ?>
                                    </div>
                                       
                                        <div class="product-price" style="margin-top: 10px">
                                            <?php
                                                if(!empty($prod->mrp)) {
                                                    ?>
                                                    <del class="old-price" id="sellmrp<?= $prod->pid;?>" style="margin-right: 2px">MRP <i class="fas fa-rupee-sign" style="margin-right: 3px"></i> <?php echo  $prod->mrp;?></del>
                                                    <?php
                                                }
                                            ?>
                                            <ins class="new-price" id="sellprice<?= $prod->pid;?>">Offer <i class="fas fa-rupee-sign" style="margin-right: 1px;margin-left:3px;"></i> <?php echo  number_format($prod->price);?></ins>
                                            
                                              
                                        </div>
                                    <button class="btn btn-primary btn-cartss" type="button" data-pid="<?= encode($prod->pid);?>" data-psid="<?= encode($prod->psid);?>" style="width:84%; border-radius:16px;" data-wen="<?= $prod->pid;?>">  
                                            <span>Add to </span>
                                            <i class="w-icon-cart" style="margin-left:5px;"></i>
                                        </button> 
                                    </div>
                                </div>
                            </div>
      <?php
    }
  }else {
        echo "<h4>No data found</h4>";
    }
?>
                              
                            </div>

                            <div class="toolbox toolbox-pagination justify-content-end">
                             <span id="newpage"></span>
                            </div>
                        </div>
                        <!-- End of Shop Main Content -->
                    </div>
                    <!-- End of Shop Content -->
                </div>
            </div>
            <!-- End of Page Content -->
        </main>
<?= $footer1;?>
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