<?= $header;?>
<style type="text/css">
  #name-error {
    color:red;
  }
    #location-error {
    color:red;
  }
    #msg-error {
    color:red;
  }
    #vmonths-error {
    color:red;
  }
  .form-group.has-error .help-block {
    color: red;
}
</style>
<div class="page has-sidebar-left bg-light height-full">
 <div class="container-fluid my-3">
        <div class="row">
         
                    <div class="card my-3 no-b" style="width:100%">
                    <div class="card-body">
                          
                               <form id="franchises" method="post" style="margin-top:40px" enctype="multipart/form-data">
                                  <input type="hidden" class="csrf_token" name="<?= $this->security->get_csrf_token_name();?>" value="<?= $this->security->get_csrf_hash();?>">
                                <div class="row">
                                  <input type="hidden" name="fid" id="fid" value="0">
                                      <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                           <div class="form-group">
                            <label>Franchise Name <span style="color:red">*</span></label>
                               <input type="text" name="fname" id="fname" class="form-control" placeholder="Enter Franchise Name"  >
                               <span id="fname_error" class="text-danger"></span>
                           </div>
                         </div>
<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
         <div class="form-group">
                            <label>Person Name </label>
                               <input type="text" name="pname" id="pname" class="form-control" placeholder="Enter Person Name"  >
                               <span id="pname_error" class="text-danger"></span>
                           </div>                
</div>
<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
         <div class="form-group">
                            <label>Contact Number </label>
                               <input type="number" name="cno" id="cno" class="form-control" placeholder="Enter Contact Number"  maxlength="10" min="0" minlength="10" onkeypress="if(this.value.length==10) return false;">
                               <span id="cno_error" class="text-danger"></span>
                           </div>                
</div>
<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
         <div class="form-group">
                            <label>Whatsapp Number </label>
                               <input type="number" name="wno" id="wno" class="form-control" placeholder="Enter Whatsapp Number"  maxlength="10" min="0" minlength="10" onkeypress="if(this.value.length==10) return false;">
                               <span id="wno_error" class="text-danger"></span>
                           </div>                
</div>
<div class="clearfix"></div>

<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
          <div class="form-group">
                            <label>Pincode <span style="color:red">*</span></label>
                            <select name="pincodes[]" id="pincodes" class="form-control" multiple>
                              <option value="">Select Pincode</option>
                              <?php
                                if(count($pincodes) >0) {
                                  foreach ($pincodes as $key => $value) {
                                    ?>
                                    <option value="<?= $value->id;?>"><?= $value->pincode;?></option>
                                    <?php
                                  }
                                }
                              ?>
                            </select>
                            <span id="pincodes_error" class="text-danger"></span>
                           </div>                  
</div>
<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
  <div class="form-group">
    <label>Area</label>
    <input type="text" name="area" id="area" class="form-control" placeholder="Enter Area">
  </div>
</div>
<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
  <div class="form-group">
    <label>Subarea/Block/Phase</label>
    <input type="text" name="subarea" id="subarea" class="form-control" placeholder="Enter Subarea/Block/Phase">
  </div>
</div>
<div class="clearfix"></div>
<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
  <div class="form-group">
    <label>Street/Road</label>
    <input type="text" name="street" id="street" class="form-control" placeholder="Enter Street/Road">
  </div>
</div>
<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
  <div class="form-group">
    <label>Gated Community/Institution</label>
    <input type="text" name="gatedcommunity" id="gatedcommunity" class="form-control" placeholder="Enter Gated Community/Institution">
  </div>
</div>
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
   <div class="form-group">
                            <label>Address <span style="color:red">*</span></label>
                             <textarea cols="2" rows="2" class="form-control" id="address" name="address" ></textarea>
                             <span id="address_error" class="text-danger"></span>
                           </div>
</div>
<div class="clearfix"></div>

              
<div class="clearfix"></div>
                           <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <center>
                             <button class="btn btn-primary" type="submit" id="submit">Submit</button>
                           </center>
                           </div>
                           </div>
                       </form>
                        
                    </div>
                </div>
          
        </div>
    </div>
</div>
<?= $footer;?>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/notify/0.4.2/notify.min.js"></script>
<script>

$(document).ready(function() {
  $('#franchises').bootstrapValidator({
    excluded: [':disabled', ':hidden', ':not(:visible)'],
    fields: {
      'fname': {
        validators: {
          notEmpty: {
            message: "Please enter franchise name."
          },
          stringLength: {
            max: 80,
            message: 'Maximum 80 characters are allowed'
          },regexp: {
              regexp: /^[A-Za-z ]+((\s)?([A-Za-z ])+)*$/,
              message: 'It can only consist of alphabeticals'
          }
        }
      },
      'address': {
        validators: {
          notEmpty: {
            message: "Please enter address."
          },
          stringLength: {
            max: 300,
            message: 'Maximum 300 characters are allowed'
          }
        }
      },
      'pincodes[]': {
          validators: {
            notEmpty: {
              message: "Please select pincode."
           }
          }
        },
        'cno': {
          validators: {
            notEmpty: {
            message: "Please enter contact number."
          },
          regexp: {
            regexp: /^\d{10}$/,
            message: 'The phone number must contain 10 digits'
          }
         }
        }
    }
  }).on('success.form.bv', function(e) {
    e.preventDefault();
    $("#dv-valerrors").html("");
    var formData = new FormData($(this)[0]);
    $.ajax({
      url: '<?= base_url().'master/saveFranchise';?>',
      data: formData,
      processData: false,
      contentType: false,
      type: 'POST',
      dataType:"json",
      beforeSend: function() {
      },
      success: function(res){
      $(".csrf_token").val(res.csrf_tkn); 
      if(res.formerror ==false) {
                if(res.fname_error !='') {
                  $("#fname_error").html(res.fname_error);
                }else {
                  $("#fname_error").html('');
                }
                 if(res.pincodes_error !='') {
                  $("#pincodes_error").html(res.pincodes_error);
                }else {
                  $("#pincodes_error").html('');
                }
                 if(res.address_error !='') {
                  $("#address_error").html(res.address_error);
                }else {
                  $("#address_error").html('');
                }
              }
              else if(res.status ==false) {
               $.notify(res.msg, "error");
              }       
      else if(res.status==true)
      {
        $("#fname_error").html('');
        $("#pname_error").html('');
         $("#cno_error").html('');
        $("#wno_error").html('');
        $("#pincodes_error").html('');
        $("#address_error").html('');
        $.notify(res.msg, "success");
        setTimeout(function(){ 
          window.location.href = "<?= base_url().'franchises';?>";
        }, 2000);
      }
      },
      error: function(jqXHR, textStatus, errorThrown) {
      $("#btn_submit").prop("disabled",false);
      var msg = "Something went wrong !";
      switch (jqXHR.status) {
        case 403: msg = "Token error ! Refresh and try again";break;
        default : msg = textStatus+" - "+errorThrown;break;
      } 
      $(".lbl_status").ErrMsg(msg);
      }
    });
  }); 
}); 
</script>