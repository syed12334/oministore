<?php
	if(count($products) >0) {
		foreach ($products as $key => $prod) {
             $pid = $prod->pid;
                                        $mrp = $prod->mrp;
                                    $sell = $prod->price;
                                    $disc = $this->home_db->discount($mrp, $sell);
            $getSizes = $this->master_db->sqlExecute('select ps.pro_size as psid,s.sname from product_size ps left join sizes s on s.s_id = ps.sid where ps.pid ='.$pid.'');
            $psid = $prod->psid;
$getProds = $this->master_db->getRecords('product_size',['pro_size'=>$psid,'stock !='=>0],'*');
			?>
			 <div class="product-wrap">
<input type="hidden" name="sizes" id="sizesnew<?= $prod->pid;?>" value="<?= $prod->psid;?>">
<input type="hidden" name="pincodestatuscheck" id="pincodestatuscheck" value="<?php if($this->session->userdata('pincodes') && $this->session->userdata('pincodes') !='') {echo 1;}else {echo '';} ?>">

                                <div class="product text-center">
                                    <figure class="product-media">
                                        <a href="<?= base_url().'products/'.$prod->page_url;?>">
                                            <img src="<?= base_url().$prod->image;?>" alt="Product"
                                                width="300" height="338" />
                                            <img src="<?= base_url().$prod->image;?>" alt="Product"
                                                width="300" height="338" />
                                        </a>
                                        <div class="product-action-vertical">
                                      
                                                <?php
                                                if(count($getProds) >0) {
                                                    if (!empty($disc) && $disc != 0) {
                                                    ?>
                                                     <a href="javascript:void(0)" id="discounttag<?= $prod->pid;?>" class="btn-product-icon"
                                                     ><?php echo round($disc); ?>% off</a>
                                                       <a href="<?= base_url().'products/'.$prod->page_url;?>" class="btn-product-icon btn-wishlist w-icon-heart"
                                                title="Add to wishlist" id="wishlist" data-pid="<?= encode($prod->pid);?>"></a>
                                                        <?php
                                                    } 
                                                }else {
                                                     ?>
                                                     <a href="javascript:void(0)" id="discounttag<?= $prod->pid;?>" class="btn-product-icon" style="display: none;"
                                                     ></a>
                                                       <a href="<?= base_url().'products/'.$prod->page_url;?>" class="btn-product-icon btn-wishlist w-icon-heart wishlist<?= $prod->pid;?>"
                                                title="Add to wishlist" id="wishlist" data-pid="<?= encode($prod->pid);?>" style="display: none"></a>
                                                        <?php
                                                }
                                                
                                                ?>
                                          

                                                
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
                                    <?php
                                        if(count($getProds) >0) {
                                            ?>
                                            <div class="product-price" style="margin-top: 10px" id="productpricelist<?= $prod->pid;?>">
                                            <?php
                                                if(!empty($prod->mrp)) {
                                                    ?>
                                                    <del class="old-price" id="sellmrp<?= $prod->pid;?>" style="margin-right: 2px">MRP <i class="fas fa-rupee-sign" style="margin-right: 3px"></i> <?php echo  $prod->mrp;?></del>
                                                    <?php
                                                }
                                            ?>
                                            <ins class="new-price" id="sellprice<?= $prod->pid;?>">Offer <i class="fas fa-rupee-sign" style="margin-right: 1px;margin-left:3px;"></i> <?php echo  number_format($prod->price);?></ins>
                                            
                                              
                                        </div>
                                            <?php
                                        }else {
                                            ?>
                                            <p id="hidearrivalstext<?= $prod->pid;?>" style="color:red; margin-top: 8px;
    font-size: 16px;">Out of Stock</p>
                                            <div class="product-price" style="margin-top: 10px" id="productpricelist<?= $prod->pid;?>">
                                            
                                                    <del class="old-price" id="sellmrp<?= $prod->pid;?>" style="margin-right: 2px"></del>
                                               
                                            <ins class="new-price" id="sellprice<?= $prod->pid;?>"> </ins>
                                            
                                              
                                        </div>
                                            <?php
                                        }
                                    ?>
                                       
                                        
                                        <div class="text-danger" id="outofstockmsg<?= $prod->pid;?>"></div>

                                         <?php
                                        if(count($getProds) >0) {
                                            ?>
                                    <button class="btn btn-primary btn-carts" type="button" id="hideBtn<?= $prod->pid;?>" data-pid="<?= encode($prod->pid);?>" data-psid="<?= encode($prod->psid);?>" style="width:84%; border-radius:16px;" data-wen="<?= $prod->pid;?>">  
                                            <span>Add to </span>
                                            <i class="w-icon-cart" style="margin-left:5px;"></i>
                                        </button> 
                                        <?php
                                    }
                                    else {
                                           ?>
                                    <button class="btn btn-primary btn-carts" type="button" id="hideBtn<?= $prod->pid;?>" data-pid="<?= encode($prod->pid);?>" data-psid="<?= encode($prod->psid);?>" style="width:84%; border-radius:16px;display: none" data-wen="<?= $prod->pid;?>">  
                                            <span>Add to </span>
                                            <i class="w-icon-cart" style="margin-left:5px;"></i>
                                        </button> 
                                        <?php
                                    }
                                    ?>
                                    </div>
                                </div>
                            </div>
			<?php
		}
	}else {
        echo "<h4>No data found</h4>";
    }
?>