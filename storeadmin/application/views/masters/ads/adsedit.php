<?php //echo "<pre>";print_r($category);exit;?>
<?= $header;?>
<style type="text/css">
  #pincode-error {
    color:red;
  }
  #image-error {
    color:red;
  }
</style>
<div class="page has-sidebar-left bg-light height-full">
 <div class="container-fluid my-3">
        <div class="row">
            <div class="col-md-12">
                    <div class="card my-3 no-b">
                    <div class="card-body">
                            <div class="card-title">Edit Ads</div>
                            <?php
                              if(@$this->session->flashdata('message')) {
                                echo $this->session->flashdata('message');
                              }
                            ?>

                          <?php
                            if(count($ads)>0) {
                              ?>
                                 <div id="processing"></div>
                              <form action="<?= base_url().'master/adssave';?>" id="ads" method="post" style="margin-top:40px" enctype="multipart/form-data">
                                  <input type="hidden" class="csrf_token" name="<?= $this->security->get_csrf_token_name();?>" value="<?= $this->security->get_csrf_hash();?>">
                                  <input type="hidden" name="cid" value="<?= $ads[0]->id;?>">
                                  <div class="row">
                                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                       <div class="form-group">
                                          <label>Ads Name</label>
                                              <input type="text" name="cname" id="cname" class="form-control" placeholder="Enter Ads Name"  value="<?= $ads[0]->ad_name;?>">
                                       </div>
                                       <span id="ads_error" class="text-danger"></span>
                                  </div>
                                    <div class="col-xs-12 col-sm-4 col-lg-4 col-md-4">
                                      <div class="form-group">
                                        <label>Ad Link</label>
                                        <input type="text" name="adlink" id="adlink" class="form-control"  value="<?= $ads[0]->ad_link;?>" placeholder="Eg:https://www.youtube.com/watch?v=OAfBdcrqkQcee">
                                      </div>
                                      <span id="link_error" class="text-danger"></span>
                                 </div>
                                  <div class="col-xs-12 col-sm-4 col-lg-4 col-md-4">
                                      <div class="form-group">
                                        <label>Ads Image<span style="color:red">*</span><span class="text-danger" style="margin-left: 10px">(Imagesize should be 610px * 200px)</span></label>
                                        <input type="file" name="image" id="image" class="form-control"  >
                                        <?php
                                          if(!empty($ads[0]->ad_banner)) {
                                            ?>
                                            <img src="<?= app_url().$ads[0]->ad_banner; ?>" style="width:300px">
                                            <?php
                                          }
                                        ?>
                                      </div>
                                 </div>
                                 <div class="clearfix"></div>
                                  <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                      <center> <button class="btn btn-primary" type="submit" id="submit">Submit</button></center>
                                  </div>
                                <div class="clearfix"></div>
                          </div>
                       </form>
                              <?php
                            }else {
                              redirect(base_url().'ads');
                            }
                          ?>
                              
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $footer;?>
<script>

$(document).ready(function() {
      $("#ads").on("submit",function(e) {
    e.preventDefault();
      var formdata =new FormData(this);
      $.ajax({
          url :"<?= base_url().'master/adssave';?>",
          method :"post",
          dataType:"json",
          data :formdata,
          contentType: false,
            cache: false,
            processData:false,
            success:function(data) {
            if(data.formerror ==false) {
              $(".csrf_token").val(data.csrf_token);
              if(data.ads_error !='') {
                $("#ads_error").html(data.ads_error);
              }else {
                $("#ads_error").html('');
              }
              if(data.link_error !='') {
                $("#link_error").html(data.link_error);
              }else {
                $("#link_error").html('');
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
               $("#ads_error").html('');
               $("#link_error").html('');
               $("#processing").html('<div class="alert alert-success alert-dismissible"><i class="fas fa-circle-check"></i> '+data.msg+'</div>');
                setTimeout(function() {window.location.href="<?= base_url().'ads';?>";}, 1000);
            }
          }
      });
  });
});
</script>