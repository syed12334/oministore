<?php //echo "<pre>";print_r($property);exit;?>
<?= $header;?>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/themes/base/jquery-ui.min.css">
<style type="text/css">
  #pincode-error {
    color:red;
  }
  #cname-error {
    color:red;
  }
  #processing {
    position: fixed;
    z-index:9999999;
    width: 24%;
    padding: 10px;
    right:20px;
    top:70px;
   
  }
  input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
input[type=number] {
  -moz-appearance: textfield;
}
</style>
<div id="processing"></div>
<div class="page has-sidebar-left bg-light height-full">
 <div class="container-fluid my-3">
        <div class="row">
            <div class="col-md-12">
                    <div class="card my-3 no-b">
                    <div class="card-body">
                            <div class="card-title">Add Coupon</div>
                            <?php
                              if(@$this->session->flashdata('message')) {
                                echo $this->session->flashdata('message');
                              }
                            ?>
                            <a href="<?= base_url().'coupons';?>" class="btn btn-primary" style="float: right;margin-top: -25px">Back</a>
                              <form id="coupons" method="post" style="margin-top:40px" enctype="multipart/form-data">
                                  <input type="hidden" class="csrf_token" name="<?= $this->security->get_csrf_token_name();?>" value="<?= $this->security->get_csrf_hash();?>">
                                  <input type="hidden" name="cid" value="">
                                  <div class="row">
                                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                       <div class="form-group">
                                          <label>Coupon Type<span style="color:red">*</span></label>
                                              <select name="type" id="type" class="form-control" >
                                                <option value="">Select Type</option>
                                                <option value="0">Flat Discount - Public</option>
                                                <option value="1">Percentage Discount - Public</option>
                                                 <option value="2">Individual Discount</option>
                                              </select>
                                              <span id="type_error" class="text-danger"></span>
                                       </div>
                                  </div>
                                  <div class="col-xs-12 col-sm-4 col-lg-4 col-md-4">
                                      <div class="form-group">
                                        <label>Coupon Code<span style="color:red">*</span></label>
                                        <input type="text" name="ccode" id="ccode" class="form-control"  placeholder="Eg:SS123">
                                        <span id="ccode_error" class="text-danger"></span>
                                      </div>
                                 </div>
                                  <div class="col-xs-12 col-sm-4 col-lg-4 col-md-4">
                                      <div class="form-group">
                                        <label>From Date<span style="color:red">*</span></label>
                                        <input type="text" name="fdate" id="fdate" class="form-control"  placeholder="Select From Date" autocomplete="off">
                                        <span id="fdate_error" class="text-danger"></span>
                                      </div>
                                 </div>
                                 <div class="clearfix"></div>
                                  <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                      <div class="form-group">
                                        <label>To Date<span style="color:red">*</span></label>
                                        <input type="text" name="tdate" id="tdate" class="form-control"  placeholder="Select To Date" autocomplete="off">
                                        <span id="tdate_error" class="text-danger"></span>
                                      </div>
                                  </div>
                                  <div class="col-xs-12 col-sm-4 col-lg-4 col-md-4">
                                      <div class="form-group">
                                        <label>Discount<span style="color:red">*(%)</span></label>
                                        <input type="number" name="discount" id="discount" class="form-control"  placeholder="Eg: 10" placeholder="Eg:10">
                                        <span id="dicount_error" class="text-danger"></span>
                                      </div>
                                 </div>
                                  <div class="col-xs-12 col-sm-4 col-lg-4 col-md-4">
                                     <div class="form-group" id="usagediv">
                                        <label>Usage/user<span style="color:red">*</span></label>
                                        <input type="number" name="usage" id="usage" class="form-control"  placeholder="Eg: 10" placeholder="Eg:10">
                                        <span id="usage_error" class="text-danger"></span>
                                      </div>
                                 </div>
                                 <div class="clearfix"></div>
                                  <div class="col-xs-12 col-sm-4 col-lg-4 col-md-4" id="minvaldiv">
                                     <div class="form-group">
                                        <label>On Min Value<span style="color:red">*</span></label>
                                        <input type="number" name="minval" id="minval" class="form-control"  placeholder="Eg: 10" placeholder="Eg:10">
                                        <span id="minval_error" class="text-danger"></span>
                                      </div>
                                 </div>
                                 <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                    <div class="form-group">
                                        <label>Prefix</label>
                                        <input type="text" name="prefix" id="prefix" class="form-control" placeholder="Eg:SW,GU">
                                      </div>
                                 </div>
                                  <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                    <div class="form-group">
                                        <label>No of Vouchers</label>
                                        <input type="number" name="noofvoucher" id="noofvoucher" class="form-control" placeholder="Eg:100">
                                      </div>
                                 </div>
                                 <div class="clearfix"></div>
                                 <div class="col-xs-12 col-md-8 col-lg-8 col-sm-8"></div>
                                 <div class="col-xs-12 col-md-4 col-lg-4 col-sm-4" id="appendVouchers"></div>
                                  <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                      <center> <button class="btn btn-primary" type="submit" id="submit">Submit</button></center>
                                  </div>
                                  <input type='hidden' name='vouchercount[]' value=''/>
                                <div class="clearfix"></div>
                          </div>
                       </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $footer;?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"></script>

<script>

  $("#tdate").datepicker({
        dateFormat :"dd-mm-yy",
        changeMonth:true,
        changeYear :true
    });
    $("#fdate").datepicker({
        dateFormat :"dd-mm-yy",
        changeMonth:true,
        changeYear :true
    });
    $('#fdate').change(function() {
startDate = $(this).datepicker('getDate');
$("#tdate").datepicker("option", "minDate", startDate );
});
$('#tdate').change(function() {
endDate = $(this).datepicker('getDate');
$("#fdate").datepicker("option", "maxDate", endDate );
});

  $(document).ready(function() {
    $(document).on("keyup change","#noofvoucher",function(e) {
      e.preventDefault();
      var prefix = $("#prefix").val();
      var vouc = $(this).val();
      if(prefix !="" && vouc !="") {
        $.ajax({
          url :"<?= base_url().'master/getVouchercountlist';?>",
          method:"post",
          data:{
            prefix:prefix,
            vouc :vouc
          },
          dataType:"json",
          cache:false,
          success:function(response) {
            if(response.status ==true) {
              $("#appendVouchers").html(response.voucher);
            }
          }
        });
      }
    });
    $(document).on("change","#type",function(e) {
      e.preventDefault();
      var type = $(this).val();
      if(type ==2) {
        $("#usagediv").hide();
      }else {
        $("#usagediv").show();
      }
    });
      $("#coupons").on("submit",function(e) {
    e.preventDefault();
      var formdata =new FormData(this);
      $.ajax({
          url :"<?= base_url().'master/couponsave';?>",
          method :"post",
          dataType:"json",
          data :formdata,
          contentType: false,
            cache: false,
            processData:false,
            success:function(data) {
            if(data.formerror ==false) {
              $(".csrf_token").val(data.csrf_token);
              if(data.type_error !='') {
                $("#type_error").html(data.type_error);
              }else {
                $("#type_error").html('');
              }
              if(data.ccode_error !='') {
                $("#ccode_error").html(data.ccode_error);
              }else {
                $("#ccode_error").html('');
              }
              if(data.fdate_error !='') {
                $("#fdate_error").html(data.fdate_error);
              }else {
                $("#fdate_error").html('');
              }
              if(data.tdate_error !='') {
                $("#tdate_error").html(data.tdate_error);
              }else {
                $("#tdate_error").html('');
              }
              if(data.dicount_error !='') {
                $("#dicount_error").html(data.dicount_error);
              }else {
                $("#dicount_error").html('');
              }
               if(data.minval_error !='') {
                $("#minval_error").html(data.minval_error);
              }else {
                $("#minval_error").html('');
              }
               if(data.usage_error !='') {
                $("#usage_error").html(data.usage_error);
              }else {
                $("#usage_error").html('');
              }
              $("#prosubmit").prop('disabled',false);
            }
            else if(data.status ==false) {
              $(".csrf_token").val(data.csrf_token);
              $("#processing").html('<div class="alert alert-danger alert-dismissible"><i class="fas fa-ban"></i> '+data.msg+'</div>').show();
              $("#prosubmit").prop('disabled',false);
            }
            else if(data.status ==true) {
               $(".csrf_token").val(data.csrf_token);
               $("#type_error").html('');
               $("#ccode_error").html('');
               $("#fdate_error").html('');
               $("#tdate_error").html('');
               $("#dicount_error").html('');
               $("#processing").html('<div class="alert alert-success alert-dismissible"><i class="fas fa-circle-check"></i> '+data.msg+'</div>');
                setTimeout(function() {window.location.href="<?= base_url().'coupons';?>";}, 1000);
            }
          }
      });
  });
});
  function removevouchers(id) {
    $("#remove"+id).fadeOut(500, function () { $("#remove"+id).remove(); });
  }
</script>