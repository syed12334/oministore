<?php
    //echo "<pre>";print_r($wishlist);exit;
?>
<?= $header;?>
<style type="text/css">
    .btn {
        padding: 10px!important
    }
    .table-responsive {
    display: block;
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}
.product.product-cart .btn-close i {
    margin-top: -14px!important;
    margin-left: -4px!important;
}
.order-list {
    border: 1px solid #efe9cf;
    margin-bottom: 30px;
    border-radius: 5px;
    background-color: #fff;
    float: left;
    width: 100%;
}
.top-order, .bottom-order {
    padding: 12px 25px 12px 25px;
    background: #488624 !important;
    float: left;
    width: 100%;
    color: #fff;
}
.pull-left {
    float: left!important;
}
.pull-right {
    float: right!important;
}

.shop-tracking-status {
    float: left;
    width: 96%;
    padding: 35px;
}

.shop-tracking-status .order-status {
    margin-top: 21px;
    position: relative;
    margin-bottom: 36px;
}
.shop-tracking-status .order-status-timeline {
    height: 5px;
    border-radius: 7px;
    background: #e5e5e5;
}

.shop-tracking-status .image-order-status {
    border: 1px solid #ddd;
    padding: 7px;
    background-color: #fdfdfd;
    position: absolute;
    margin-top: -32px;
}
.shop-tracking-status .image-order-status-new {
    left: 0;
}
.shop-tracking-status .image-order-status {
    border-radius: 150px;
    border: 1px solid #ddd;
    padding: 7px;
    background-color: #fdfdfd;
    position: absolute;
    margin-top: -32px;
}

.shop-tracking-status .image-order-status-active {
    left: 29%;
}

.shop-tracking-status .image-order-status-intransit {
    left: 63%;
}

.shop-tracking-status .image-order-status-delivered {
    left: 97%;
}

.bottom-order {
    background: #fff;
    border-top: 1px dashed #ccc;
    padding: 20px 20px 12px 20px;
    color: #fff;
}

.shop-tracking-status .image-order-status .icon {
    height: 40px;
    width: 40px;
    background-size: contain;
    background-position: no-repeat;
}

.service-details img {
    border-radius: 0px;
    max-width: 100%;
}


.shop-tracking-status .image-order-status .status {
    position: absolute;
    color: #777;
    width: 94px;
    bottom: -26px;
    left: -38%;
    text-align: center;
    font-size: 12px;
    font-weight: 600;
}

.service-sidebar {
    margin-bottom: 50px;
}

.myaccount .service-sidebar__links {
    margin-bottom: 30px;
    background-color: #36648b;
    border-radius: 5px;
}

.service-sidebar__links ul {
    margin: 0;
    padding: 0;
    list-style: none;
    padding-left: 20px;
    padding-right: 20px;
}

.service-sidebar__links ul li {
    display: block;
}

.myaccount .service-sidebar__links ul li a {
    color: #fff;
    padding-left: 15px;
    padding-right: 15px;
}

.myaccount .service-sidebar__links ul li a i {
    margin-right: 10px;
}


.service-sidebar__links ul li a {
    display: block;
    font-size: 16px;
    color: #69af07;
    line-height: 50px;
    font-weight: 500;
    -webkit-transition: all .5s ease;
    transition: all .5s ease;
    padding-left: 20px;
    padding-right: 20px;
    border-radius: 5px;
}

.myaccount .service-sidebar__links ul li a.active, .myaccount .service-sidebar__links ul li a.active:hover, .myaccount .service-sidebar__links ul li a:hover {
    color: #36648b;
    background-color: #fff
}

.myaccount .service-sidebar__links ul li a:hover {
    background-color: rgba(255, 255, 255, .3);
    color: #fff
}

.btn-info {
    color: #fff;
    background-color: #ee7500!important;
    border-color: #ee7500!important;
}

@media (min-width: 992px)
.service-sidebar__links ul li a {
    line-height: 55px;
    font-size: 18px;
    padding-left: 40px;
    padding-right: 40px;
}
}
.active {
    background: #fff!important;
    color:#000!important;
}
.shop-tracking-status .order-status-timeline .order-status-timeline-completion.c2 {
    width: 33%;
}
.shop-tracking-status .order-status-timeline .order-status-timeline-completion.c3 {
    width: 66%;
}
.shop-tracking-status .order-status-timeline .order-status-timeline-completion.c4 {
    width: 99%;
}
.shop-tracking-status .order-status-timeline .order-status-timeline-completion {
    height: 5px;
    margin: 1px;
    border-radius: 7px;
    background: #488624 !important;
    width: 0;
}

</style>
<div id="processing"></div>
 <main class="main">
            <!-- Start of Page Header -->
            <div class="page-header">
                <div class="container">
                    <h1 class="page-title mb-0">My Account</h1>
                </div>
            </div>
            <!-- End of Page Header -->

            <!-- Start of Breadcrumb -->
            <nav class="breadcrumb-nav">
                <div class="container">
                    <ul class="breadcrumb">
                        <li><a href="<?= base_url();?>">Home</a></li>
                        <li>My account</li>
                    </ul>
                </div>
            </nav>
            <!-- End of Breadcrumb -->

            <!-- Start of PageContent -->
            <div class="page-content pt-2">
                <div class="container">
                    <div class="tab tab-vertical">
                        

                        <div class="tab-content mb-6">



                            <div class="tab-pane <?php if(@$_GET['rel'] == 'wishlist') {echo 'active in';}else {echo '';}?>" id="wishlist">
                                <div class="icon-box icon-box-side icon-box-light">
                                    <span class="icon-box-icon icon-account mr-2">
                                        <i class="w-icon-heart"></i>
                                    </span>
                                    <div class="icon-box-content">
                                        <h4 class="icon-box-title mb-0 ls-normal">Cart Page </h4>
                                    </div>
                                </div>
                                <?php
                                    if(count($wishlist) >0) {
                                        ?>
                                             <table class="shop-table wishlist-table">
                                    <thead>
                                        <tr>
                                            <th class="product-name"><span>Product</span></th>
                                            <th></th>
                                            <th class="product-price"><span>Price</span></th>
                                            <th class="product-stock-status"><span>Stock Status</span></th>
                                            <th class="wishlist-action">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            
                                                foreach ($wishlist as $wish) {
                                                    ?>
                                                    <tr id="removeTable<?= icchaEncrypt($wish->wid);?>">
                                                        <td class="product-thumbnail">
                                                            <div class="p-relative">
                                                                <a href="<?= base_url().'products/'.$wish->ppage_url;?>">
                                                                    <figure>
                                                                        <img src="<?= base_url().$wish->image;?>" alt="product"
                                                                            width="300" height="338">
                                                                    </figure>
                                                                </a>
                                                                <button type="submit" id="removeWishlist" class="btn btn-close" data-pids="<?= icchaEncrypt($wish->wid);?>"><i class="fas fa-times" style="position: relative;
    top: -5px;
    right: 4px;"></i></button>
                                                            </div>
                                                        </td>
                                                        <td class="product-name">
                                                            <a href="<?= base_url().'products/'.$wish->ppage_url;?>">
                                                                <?= ucfirst($wish->title);?>
                                                            </a>
                                                        </td>
                                                        <td class="product-price">
                                                            <ins class="new-price"><i class="fas fa-rupee-sign"></i> <?= number_format($wish->price,2);?></ins>
                                                        </td>
                                                        <td class="product-stock-status">
                                                            <?php
                                                                if(!empty($wish->stock) && $wish->stock !=0) {
                                                                    ?>
                                                                    <span class="wishlist-in-stock">In Stock</span>
                                                                    <?php
                                                                }else {
                                                                    ?>
                                                                    <span class="wishlist-in-stock" style="color:red!important">Out of Stock</span>
                                                                    <?php
                                                                }
                                                            ?>
                                                            
                                                        </td>
                                                        <td class="wishlist-action">
                                                            <div class="d-lg-flex">

                                                                <button type="button"
                                                                    class="btn btn-dark btn-rounded btn-sm ml-lg-2 btn-cartbtn" data-pid="<?= encode($wish->pid);?>" id="cartBack<?= encode($wish->pid);?>" data-psid="<?= encode($wish->psid);?>">Add to
                                                                    cart</button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                          
                                        ?>
                                       
                                    </tbody>
                                </table>
                                        <?php
                                    }else {
                                        echo "No wishlist found";
                                    }
                                    ?>
                               

                            </div>

                        </div>




                        
                    </div>
                </div>
            </div>
            <!-- End of PageContent -->
        </main>
<?= $footer;?>
<?= $js;?>