<?php
        $sociallinks = $this->master_db->getRecords('sociallinks',['id !='=>-1],'*');
?>
        <!-- Start of Footer -->
        <footer class="footer appear-animate" data-animation-options="{
            'name': 'fadeIn'
        }" style="padding-bottom: 0px!important">
            <div class="footer-newsletter bg-primary pt-6 pb-6">
                <div class="container">
                    <div class="row justify-content-center align-items-center">
                        <div class="col-xl-5 col-lg-6">
                            <div class="icon-box icon-box-side text-white">
                                <div class="icon-box-icon d-inline-flex">
                                    <i class="w-icon-envelop3"></i>
                                </div>
                                <div class="icon-box-content">
                                    <h4 class="icon-box-title text-white text-uppercase mb-0">Subscribe To Our
                                        Newsletter</h4>
                                    <p class="text-white">Get all the latest information on Events, Sales and Offers.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-7 col-lg-6 col-md-9 mt-4 mt-lg-0 ">
                            <div id="subscriberror"></div>
                            <form method="post" id="subscribetochannel"
                                class="input-wrapper input-wrapper-inline input-wrapper-rounded">
                                <input type="hidden" class="csrf_token" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                                <input type="email" class="form-control mr-2 bg-white" name="email" id="email"
                                    placeholder="Your E-mail Address" />
                                    
                                <button class="btn btn-dark btn-rounded" type="submit">Subscribe<i
                                        class="w-icon-long-arrow-right"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="footer-top">
                    <div class="row">
                        <div class="col-lg-4 col-sm-6">
                            <div class="widget widget-about">
                                <a href="index.html" class="logo-footer">
                                    <img src="<?= base_url();?>assets/images/logo_footer.png" alt="logo-footer"  />
                                </a>
                                <div class="widget-body">
                                    <p class="widget-about-title">Got Question? Call us 24/7</p>
                                    <a href="tel:9902002111" class="widget-about-call">+91 99020 02111</a>
                                     

                                    <div class="social-icons social-icons-colored">
                                        <?php
                                            if(is_array($sociallinks) && !empty($sociallinks)) {
                                                if(!empty($sociallinks[0]->facebook)) {
                                                    ?>
                                                     <a href="<?= $sociallinks[0]->facebook?>" class="social-icon social-facebook w-icon-facebook" target="_blank"></a>
                                                    <?php
                                                }
                                                if(!empty($sociallinks[0]->twitter)) {
                                                    ?>
                                                     <a href="<?= $sociallinks[0]->twitter?>" class="social-icon" target="_blank"><img src="<?= base_url();?>assets/images/x-logo.svg"/></a>
                                                    <?php
                                                }
                                                if(!empty($sociallinks[0]->instagram)) {
                                                    ?>
                                                     <a href="<?= $sociallinks[0]->instagram?>" class="social-icon social-instagram w-icon-instagram" target="_blank"></a>
                                                    <?php
                                                }
                                                
                                                if(!empty($sociallinks[0]->youtube)) {
                                                    ?>
                                                     <a href="<?= $sociallinks[0]->youtube;?>" class="social-icon social-youtube w-icon-youtube" target="_blank"></a>
                                                    <?php
                                                }

                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="widget">
                                <h3 class="widget-title">Company</h3>
                                <ul class="widget-body">
                                     <li><a href="<?= base_url().'about'; ?>">About Us</a></li>
                                    <li><a href="<?= base_url().'contact'; ?>">Contact Us</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="widget">
                                <h4 class="widget-title">My Account</h4>
                                <ul class="widget-body">
                                   <li><a href="<?= base_url().'my-account';?>">Track My Order</a></li>

                                 <li><a href="<?= base_url().'my-account?rel=wishlist'; ?>">My Wishlist</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="widget">
                                <h4 class="widget-title">Customer Service</h4>
                                <ul class="widget-body">
                                    <li><a href="<?= base_url().'deliverypolicy'; ?>">Delivery and Return Policy</a></li>
                                    <li><a href="<?= base_url().'disclaimer'; ?>">Refund Policy</a></li>
                                    <li><a href="<?= base_url().'terms'; ?>">Term and Conditions</a></li>
                                    <li><a href="<?= base_url().'privacy'; ?>">Privacy Policy</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="footer-bottom">
                    <div class="footer-left">
                        <p class="copyright">Copyright © 2023 IAMPL Store. All Rights Reserved.

</p>
                    </div>
                    <div class="footer-right">
                        <span class="payment-label mr-lg-8">We're using safe payment for</span>
                        <figure class="payment">
                            <img src="<?= base_url();?>assets/images/payment.png" alt="payment" width="159" height="25" />
                        </figure>
                    </div>
                </div>
            </div>
        </footer>
        <!-- End of Footer -->
    </div>

     <!-- Start of Newsletter popup -->
  <!--   <div class="newsletter-popup mfp-hide">
        <div class="newsletter-content">
            <h2 class="ls-25">Swarnagowri Food Contest </h2>
            <p class="text-light ls-10">Register Your Name and Mobile number </p>
            <form action="#" method="post" id="subscribe" class="input-wrapper input-wrapper-inline input-wrapper-round">
            <div class="row">
            <div class="col-md-12">
            <div class="form-group">
                <input type="name" class="form-control email font-size-md" name="email" id="email"
                    placeholder="Your Name"> </div>
                   
                    <div class="form-group">
                    <input type="Phone" class="form-control email font-size-md" name="email" id="email"
                    placeholder="Your Phone"> </div></div>
                    
                    <span id="subemail_error" class="text-danger"></span>
                     <div class="col-md-12">
                <button class="btn btn-dark" type="submit">SUBMIT</button></div>
                
                </div>
            </form>
            <div class="form-checkbox d-flex align-items-center">
                <input type="checkbox" class="custom-checkbox" id="hide-newsletter-popup" name="hide-newsletter-popup"
                    required="">
              
            </div>
        </div>
    </div> -->
    <!-- End of Newsletter popup -->
    <!-- End of Page Wrapper -->

    <!-- Start of Sticky Footer -->
    <!-- <div class="sticky-footer sticky-content fix-bottom">
        <a href="index.html" class="sticky-link active">
            <i class="w-icon-home"></i>
            <p>Home</p>
        </a>
        <a href="shop-banner-sidebar.html" class="sticky-link">
            <i class="w-icon-category"></i>
            <p>Shop</p>
        </a>
        <a href="my-account.html" class="sticky-link">
            <i class="w-icon-account"></i>
            <p>Account</p>
        </a>
        <div class="cart-dropdown dir-up">
            <a href="cart.html" class="sticky-link">
                <i class="w-icon-cart"></i>
                <p>Cart</p>
            </a>
            <div class="dropdown-box">
                <div class="products">
                    <div class="product product-cart">
                        <div class="product-detail">
                            <h3 class="product-name">
                                <a href="product-default.html">Beige knitted elas<br>tic
                                    runner shoes</a>
                            </h3>
                            <div class="price-box">
                                <span class="product-quantity">1</span>
                                <span class="product-price">$25.68</span>
                            </div>
                        </div>
                        <figure class="product-media">
                            <a href="#">
                                <img src="assets/images/cart/product-1.jpg" alt="product" height="84" width="94" />
                            </a>
                        </figure>
                        <button class="btn btn-link btn-close" aria-label="button">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <div class="product product-cart">
                        <div class="product-detail">
                            <h3 class="product-name">
                                <a href="https://www.portotheme.com/html/wolmart/product.html">Blue utility pina<br>fore
                                    denim dress</a>
                            </h3>
                            <div class="price-box">
                                <span class="product-quantity">1</span>
                                <span class="product-price">$32.99</span>
                            </div>
                        </div>
                        <figure class="product-media">
                            <a href="#">
                                <img src="assets/images/cart/product-2.jpg" alt="product" width="84" height="94" />
                            </a>
                        </figure>
                        <button class="btn btn-link btn-close" aria-label="button">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                <div class="cart-total">
                    <label>Subtotal:</label>
                    <span class="price">$58.67</span>
                </div>

                <div class="cart-action">
                    <a href="cart.html" class="btn btn-dark btn-outline btn-rounded">View Cart</a>
                    <a href="checkout.html" class="btn btn-dark  btn-rounded">Checkout</a>
                </div>
            </div>
        </div>

        <div class="header-search hs-toggle dir-up">
            <a href="#" class="search-toggle sticky-link">
                <i class="w-icon-search"></i>
                <p>Search</p>
            </a>
            <form action="#" class="input-wrapper">
                <input type="text" class="form-control" name="search" autocomplete="off" placeholder="Search"
                    required />
                <button class="btn btn-search" type="submit">
                    <i class="w-icon-search"></i>
                </button>
            </form>
        </div>
    </div> -->

    <!-- Start of Scroll Top -->
    <a id="scroll-top" class="scroll-top" href="#top" title="Top" role="button"> <i class="w-icon-angle-up"></i> <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 70 70"> <circle id="progress-indicator" fill="transparent" stroke="#000000" stroke-miterlimit="10" cx="35" cy="35" r="34" style="stroke-dasharray: 16.4198, 400;"></circle> </svg> </a>
    <!-- End of Scroll Top -->

    <!-- Start of Mobile Menu -->
  
    <!-- End of Mobile Menu -->

  <div class="msg_de">
      <div class="container">  <p>
            Buy Products worth Rs.300/- and Get Free Delivery  <span id="savingamount2"> <?php if($this->cart->total() >=299) {echo "Shipping free";}else { $amntlimti =300;echo "Add Rs. ".$amntlimti - $this->cart->total().' for Free Delivery';}?> </span>
        </p>
        </div>
    </div>

  
    <!-- End of Quick view -->

    <!-- Plugin JS File -->
    <script src="<?= base_url();?>assets/vendor/jquery/jquery.min.js"></script>
     <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script src="<?= base_url();?>assets/vendor/sticky/sticky.min.js"></script>
    <script src="<?= base_url();?>assets/vendor/jquery.plugin/jquery.plugin.min.js"></script>
    <script src="<?= base_url();?>assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
<!--     <script src="<?= base_url();?>assets/vendor/magnific-popup/jquery.magnific-popup.min.js"></script>
 -->    <script src="<?= base_url();?>assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="<?= base_url();?>assets/vendor/zoom/jquery.zoom.js"></script>
    <script src="<?= base_url();?>assets/vendor/photoswipe/photoswipe.min.js"></script>
    <script src="<?= base_url();?>assets/vendor/photoswipe/photoswipe-ui-default.min.js"></script>


    <!-- Main JS File -->
    <script src="<?= base_url();?>assets/js/main.min.js"></script>
     <script>
  $( function() {
   
    $( "#search" ).autocomplete({
      source: "<?= base_url().'Home/autocomplete';?>"
    });
  } );
  $(document).ready(function() {
              $(document).on('click',"#ui-id-1 .ui-menu-item",function(e) {
                var text = $(this).children().text();
                window.location.href="<?= base_url().'searchresults?search=';?>"+text;
            });
  });
  </script>
</body>


<!-- Mirrored from portotheme.com/html/wolmart/product-without-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 31 May 2022 08:12:24 GMT -->
</html>