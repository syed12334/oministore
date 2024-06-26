<?= $header2;?>
<style type="text/css">
    .newsletter-popup {
        display: none!important;
    }
</style>
<main class="main mb-10 pb-1">

           <div class="page-header">
                <div class="container">
                    <h1 class="page-title mb-0">About Us </h1>
                </div>
            </div>
            
            <nav class="breadcrumb-nav">
                <div class="container">
                    <ul class="breadcrumb">
                        <li><a href="<?= base_url();?>">Home</a></li>
                        <li>About Us </li>
                    </ul>
                </div>
            </nav>
            
	<div class="container">
		
        <div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="para">
				<!-- <p>www.swarnagowri.com is a ecommerce platform executed by Homeneeds Cart Pvt Ltd to 
                serve in house brand high quality food products under the Brand Name Swarnagowri at affordable prices. </p> 

				<p>Homeneeds Cart Pvt Ltd owns Swarnagowri.com and Swarnagowri range of food products such as Atta, 
                Masala Products, Coffee and Drinking Water. </p>

				<p>The Company started in 2022 headed by its Directors Mr. Sumith Kantharaj & Santhosh Kumar. To start 
                with we cater to Bangalore region and we are planning to extend further. Company has its own in-house 
                delivery system in place. Company has a vision to reach its products all across Karnataka by 2025 
                through online, offline and retail networks. </p> -->


                <p>IAMPL (Innovative Advanced Media Management Pvt Ltd) is a leading advertising agency in Karnataka with a large number of active clients. The company has a team of highly skilled and experienced professionals who work together to provide the best service to clients to their utmost satisfaction. IAMPL is committed to provide most effective media tools and technologies to its clients and help them compete in the market. </p>

                <p> IAMPL is authorized to purchase products from the principal seller and sell it through our online platform IAMPL STORE.</p> 
                <p>IAMPL has permission from the sellers to market and sell their products through our platform for reasonable price /discounted price.</p> 
                <p>We shall sell the product from the supplier through our online platform and have obtained approval from the actual manufacturer of the product for the lawful resale of their products.</p>
                
                </div>
			</div>
		</div>
        
	</div>
</main>
<?= $footer;?>
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