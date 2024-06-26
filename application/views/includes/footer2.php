
<style>
.msg_de {
    background: #fceabb;
    background: -moz-linear-gradient(left,rgba(252,234,187,1) 0,rgba(252,205,77,1) 50%,rgba(251,223,147,1) 100%);
    background: -webkit-linear-gradient(left,rgba(252,234,187,1) 0,rgba(252,205,77,1) 50%,rgba(251,223,147,1) 100%);
    background: linear-gradient(to right,rgba(252,234,187,1) 0,rgba(252,205,77,1) 50%,rgba(251,223,147,1) 100%);
    position: fixed;
    z-index: 9;
    left: 0;
    bottom: 0;
    width: 100%;
    text-align: center;
    padding: 4px 0;
    color: #000;
}

.msg_de span {
    margin-left: 35px;
    background-color: #599d28;
    padding: 7px 19px;
    color: #fff;
}
.mfp-bg {
    display: none!important;
}

.msg_de p {
    margin: 0;
}

</style>
        <!-- Start of Footer -->
        <footer class="footer appear-animate" data-animation-options="{
            'name': 'fadeIn'
        }" style="padding-bottom: 0px!important">
          <!--   <div class="footer-newsletter bg-primary pt-6 pb-6">
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
                            <span id="subemail_error" class="text-danger"></span>
                        </div>
                    </div>
                </div>
            </div> -->
            <div class="container">
               
                
                <div class="footer-bottom" style="padding: 30px 0px!important;width: 100%;">
                    <div class="footer-center" style="width: 100%">
                        <p class="copyright" style="padding-bottom: 34px">Copyright © 2023 IAMPL Store. All Rights Reserved.

</p>
                    </div>
                   
                </div>
            </div>
        </footer>
        <!-- End of Footer -->
    </div>
    <!-- End of Page Wrapper -->

    <!-- Start of Sticky Footer -->
    <div class="msg_de">
      <div class="container">  <p>
            Buy Products worth Rs.300/- and Get Free Delivery  <span id="savingamount1"> <?php if($this->cart->total() >=299) {echo "Shipping free";}else { $amntlimti =300;echo "Add Rs.".$amntlimti - $this->cart->total().'/- for Free Delivery';}?> </span>
        </p>
        </div>
    </div>

    <!-- Start of Scroll Top -->
    <a id="scroll-top" class="scroll-top" href="#top" title="Top" role="button"> <i class="w-icon-angle-up"></i> <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 70 70"> <circle id="progress-indicator" fill="transparent" stroke="#000000" stroke-miterlimit="10" cx="35" cy="35" r="34" style="stroke-dasharray: 16.4198, 400;"></circle> </svg> </a>
    <!-- End of Scroll Top -->

  
    </div>
   


    <!-- Start of Quick View -->
   
    <!-- End of Quick view -->

    <!-- Plugin JS File -->
    <script src="<?= base_url();?>assets/vendor/jquery/jquery.min.js"></script>
     <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script src="<?= base_url();?>assets/vendor/sticky/sticky.min.js"></script>
    <script src="<?= base_url();?>assets/vendor/jquery.plugin/jquery.plugin.min.js"></script>
    <script src="<?= base_url();?>assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
    <script src="<?= base_url();?>assets/vendor/magnific-popup/jquery.magnific-popup.min.js"></script>
    <script src="<?= base_url();?>assets/vendor/swiper/swiper-bundle.min.js"></script>
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

                 $(".menu").on('click','li',function(){
            $(".menu li.active").removeClass("active"); 
            $(this).addClass("active"); 
        });
  });

  </script>
</body>



</html>