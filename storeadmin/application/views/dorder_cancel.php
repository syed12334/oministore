
<!DOCTYPE html>
<html lang="en">



<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">

    <title>Swarnagowri </title>

    <meta name="keywords" content="Marketplace ecommerce responsive HTML5 Template" />
    <meta name="description"
        content="Swarnagowri">
    <meta name="author" content="D-THEMES">

    <!-- Favicon -->


    <!-- WebFont.js -->
    <script>
        WebFontConfig = {
            google: { families: ['Poppins:400,500,600,700'] }
        };
        (function (d) {
            var wf = d.createElement('script'), s = d.scripts[0];
            wf.src = '<?= base_url();?>assets/js/webfont.js';
            wf.async = true;
            s.parentNode.insertBefore(wf, s);
        })(document);
    </script>
<link rel="stylesheet" type="text/css" href="<?= base_url();?>assets/vendor/fontawesome-free/css/all.min.css">
    <link rel="preload" href="<?= base_url();?>assets/vendor/fontawesome-free/webfonts/fa-regular-400.woff2" as="font" type="font/woff2"
        crossorigin="anonymous">
    <link rel="preload" href="<?= base_url();?>assets/vendor/fontawesome-free/webfonts/fa-solid-900.woff2" as="font" type="font/woff2"
        crossorigin="anonymous">
    <link rel="preload" href="<?= base_url();?>assets/vendor/fontawesome-free/webfonts/fa-brands-400.woff2" as="font" type="font/woff2"
        crossorigin="anonymous">
    <link rel="preload" href="<?= base_url();?>assets/fonts/wolmart87d5.woff?png09e" as="font" type="font/woff" crossorigin="anonymous">

    <!-- Vendor CSS -->
    

    <!-- Plugin CSS -->
    <link rel="stylesheet" type="text/css" href="<?= base_url();?>assets/vendor/magnific-popup/magnific-popup.min.css">

    <!-- Default CSS -->
    <link rel="stylesheet" type="text/css" href="<?= base_url();?>assets/css/style.min.css">
    <style type="text/css">
        .wcolors {
            color:#e358e1 !important;
        }
            input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

input[type=number] {
  -moz-appearance: textfield;
}

.order-failure {
    padding: 3.6rem 1.5rem;
    border: 2px solid #f40e14;
    font-size: 2.4rem;
    background-color: #f40e14;
    color: #fff!important;
}

    </style>
</head>

<body>
<div class="container">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <center>
                <img src="https://www.swarnagowri.com/assets/images/logo.png" style="width:250px;margin-bottom: 50px">
            </center>
        </div>
        <div class="clearfix"></div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-bottom: 30px">
            
           <p style="margin-bottom: 20px;">Your order <?= $orders[0]->orderid;?> has been delivered. Thank you for ordering with Swarnagowri.</p>

        </div>
        <div class="clearfix"></div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <p style="margin-bottom: 50px">Should you require any assistance, please call us on 7026 027027 or write to us - homeneedscart@gmail.com.</p>
            <p><strong>Happy shopping !Warm Regards,</strong>
</p>
<p>Team Swarnagowri</p>

        </div>

        <div class="clearfix"></div>
    </div>
</div>

        
    <!-- End of PhotoSwipe -->

    <!-- Start of Quick View -->
   
    <!-- End of Quick view -->

    <!-- Plugin JS File -->
    <script src="<?= base_url();?>assets/vendor/jquery/jquery.min.js"></script>
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
</body>



</html>
