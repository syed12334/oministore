<?= $header;?>
<style type="text/css">
    input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
.highlightrow {
  background-color: #ff7d004f!important;
    color: #000!important
}
.highlightcommon {
  background-color:#f5f8fa;
}
input[type=number] {
  -moz-appearance: textfield;
}
 .form-group.has-error .help-block {
    color: red;
}
.modal-lg {
  width: 1400px!important;
  max-width: 1400px!important
}
#customerAddress {
  text-align: center;
  margin-bottom: 10px;
}
</style>
<div class="page has-sidebar-left bg-light height-full">
 <div class="container-fluid my-3">
        <div class="row">
            <div class="col-md-12">
                    <div class="card my-3 no-b">
                    <div class="card-body">

                        <div class="card-title">Orders</div>
                          <?php 
                            if($this->session->flashdata('message')) {
                                echo $this->session->flashdata('message');
                            }
                        ?>
                         
                       
                      <form id="ordersFilter" method="post" style="margin-bottom: 30px">
                         <input type="hidden" class="csrf_token" name="<?= $this->security->get_csrf_token_name();?>" value="<?= $this->security->get_csrf_hash();?>">
                          <div class="row">
                            <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                              <div class="form-group">
                                <label>Order Status</label>
                                <select name="ostatus" class="form-control">
                                    <option value="">Select Order Status</option>
                                    <option value="1">In Progress</option>
                                    <option value="2">Shipped</option>
                                    <option value="3">Delivered</option>
                                    <option value="4">Cancelled</option>
                                    <option value="5">Order Confirmed</option>
                                </select>
                              </div>
                            </div>
                            <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                              <div class="form-group">
                            <label>Payment Status</label>
                             <select name="pstatus" class="form-control">
                                    <option value="">Select Payment Status</option>
                                    <option value="1">Success</option>
                                    <option value="3">Pending</option>
                                    <option value="-1">Failed</option>
                                </select>
                          </div>
                            </div>

                            <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                              <div class="form-group">
                            <label>Payment Mode</label>
                             <select name="paymode" class="form-control">
                                    <option value="">Select Payment Mode</option>
                                    <option value="1">Online</option>
                                    <option value="2">COD</option>
                                </select>
                          </div>
                            </div>
                            <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                              <div class="form-group">
                                <label>From Date</label>
                                <input type="date" name="fdate" class="form-control" placeholder="">
                              </div>
                            </div>
                         
                            <div class="clearfix"></div>
                              <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                              <div class="form-group">
                            <label>To Date</label>
                            <input type="date" name="tdate" class="form-control" placeholder="">
                          </div>
                            </div>
                           
                            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                             <button type="button" class="btn btn-primary" onclick="reinitialsedata();" style="margin-top: 28px;">Search</button><button class="btn btn-default" type="button" style="margin-top: 28px;margin-left: 7px" onclick="exportorders();"><i class="fas fa-download" style="margin-right: 5px"></i> Export Orders</button>
                             <a href="<?= base_url().'orders';?>" class="btn btn-danger" style="margin-top: 28px;margin-left: 7px">Reset</a>
                            </div>
                            <div class="col-xs-12 col-sm-5 col-md-5 col-lg-5"></div>
                            <div class="clearfix"></div>
                            
                          </div>
                        </form>
                        <div class="table-responsive">
                            <table id="category_table" class="table display table-bordered table-striped no-wrap" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>Sl. No.</th>
                                        <th>Action</th>
                                        <th>Orderid</th>
                                        <th>Franchise Name</th>
                                        
                                        <th>Orderdate</th>
                                        <th>Order Status</th>
                                        <th>Ordered By</th>
                                        <th>Payment Mode</th>
                                        <th>User OTP</th>
                                         <th style="width: 100px!important">Comments</th>
                                        <th>Payment ID</th>
                                        <th>Payment Status</th>
                                        <th>Total Amount</th>
                                       
                   
                                                                            
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                          </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $footer;?>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/notify/0.4.2/notify.min.js"></script>
<?php $csrf = array('name' => $this->security->get_csrf_token_name(),'hash' => $this->security->get_csrf_hash()); ?>
<input type="hidden" class="csrf_token" name="<?= $this->security->get_csrf_token_name();?>" value="<?= $this->security->get_csrf_hash();?>">
<script type="text/javascript">
 

                var dataTable;
    function initialiseData(){
        $("#category_table").dataTable().fnDestroy();
        dataTable = $('#category_table').DataTable( {
            "processing":true,  
            "serverSide":true,  
            "searching": true, 
            "ajax":{  
                  url:"<?=base_url().'master/getOrderslist'?>",  
                  type:"POST",
                  data: function(d){
                     d.form = $("#ordersFilter").serializeArray();
                     <?php echo "d.".$csrf['name'];?> = $(".csrf_token").val();
                  },
                  error: function(){  
                        $(".tbl-error").html("");
                        $("#tbl").append('<tbody class="tbl-error"><tr class="row><th colspan="11">No data found in the server</th></tr></tbody>');
                  }
             },          
             columnDefs:[  
                  {  
                       "targets":[0],  
                       "orderable":false,  
                  },  
            ], 
            "drawCallback": function (response) {               
                var res = response.json;
                $(".csrf_token").val(res.csrf_token);
                
            },
      "rowCallback": function( row, data ) {
          if (data[4] !="Ready to Ship" && data[4] !="Delivered" && data[9] =="success") {
              $(row).addClass('highlightrow');
            } else {
              $(row).addClass('highlightcommon');
            }
          }
        });
    }


    $(document).ready(function() {
        initialiseData();
    });
              function updateStatus(id){
                     $("#orderidss").val(id);
                     $("#productModal").modal('show');

            }
            function updateStatusdelivered(id){
                     $("#orderids").val(id);
                     $("#delproductModal").modal('show');

            }
  $(document).ready(function() {
            $("#productForm").on("submit",function(e) {
            e.preventDefault();
              var formdata =new FormData(this);
              $.ajax({
                  url :"<?= base_url().'master/updateShipping';?>",
                  method :"post",
                  dataType:"json",
                  data :formdata,
                  contentType: false,
                    cache: false,
                    processData:false,
                    success:function(data) {
                    if(data.formerror ==false) {
                      $(".csrf_token").val(data.csrf_token);
                      if(data.sdate_error !='') {
                        $("#sdate_error").html(data.sdate_error);
                      }else {
                        $("#sdate_error").html('');
                      }
                      $("#brandSubmit").prop('disabled',false);
                    }
                    else if(data.status ==false) {
                      $(".csrf_token").val(data.csrf_token);
                      $("#processing").html('<div class="alert alert-danger alert-dismissible" style="font-size: 15px!important"><i class="fas fa-ban"></i> '+data.msg+'</div>').show();
                      $("#brandSubmit").prop('disabled',false);
                    }
                    else if(data.status ==true) {
                       $(".csrf_token").val(data.csrf_token);
                       $("#file_error").html('');
                       $("#processing").html('<div class="alert alert-success alert-dismissible"><i class="fas fa-circle-check"></i> '+data.msg+'</div>');
                        setTimeout(function() {window.location.href="<?= base_url().'orders';?>";}, 1000);

                    }
                  }
              });
          });


              $("#commentForm").on("submit",function(e) {
            e.preventDefault();
              var formdata =new FormData(this);
              $.ajax({
                  url :"<?= base_url().'master/updateComment';?>",
                  method :"post",
                  dataType:"json",
                  data :formdata,
                  contentType: false,
                    cache: false,
                    processData:false,
                    success:function(data) {
                    if(data.formerror ==false) {
                      $(".csrf_token").val(data.csrf_token);
                      if(data.comments_error !='') {
                        $("#comments_error").html(data.comments_error);
                      }else {
                        $("#comments_error").html('');
                      }
                    }
                    else if(data.status ==false) {
                      $(".csrf_token").val(data.csrf_token);
                      $("#processing1").html('<div class="alert alert-danger alert-dismissible" style="font-size: 15px!important"><i class="fas fa-ban"></i> '+data.msg+'</div>').show();
                    }
                    else if(data.status ==true) {
                      $("#commentForm")[0].reset();
                       $(".csrf_token").val(data.csrf_token);
                       $("#comment").html('');
                       $("#comments_error").html('');
                       $("#processing1").html('<div class="alert alert-success alert-dismissible"><i class="fas fa-circle-check"></i> '+data.msg+'</div>');
                        dataTable.ajax.reload( null, false ); 
                        
                    }
                  }
              });
          });

              $("#deliveryproductForm").on("submit",function(e) {
            e.preventDefault();
              var formdata =new FormData(this);
              $.ajax({
                  url :"<?= base_url().'master/updateDelivered';?>",
                  method :"post",
                  dataType:"json",
                  data :formdata,
                  contentType: false,
                    cache: false,
                    processData:false,
                    success:function(data) {
                    if(data.formerror ==false) {
                      $(".csrf_token").val(data.csrf_token);
                      if(data.ddate_error !='') {
                        $("#ddate_error").html(data.ddate_error);
                      }else {
                        $("#ddate_error").html('');
                      }
                      $("#delSubmit").prop('disabled',false);
                    }
                    else if(data.status ==false) {
                      $(".csrf_token").val(data.csrf_token);
                      $("#deliveryProcess").html('<div class="alert alert-danger alert-dismissible" style="font-size: 15px!important"><i class="fas fa-ban"></i> '+data.msg+'</div>').show();
                      $("#delSubmit").prop('disabled',false);
                    }
                    else if(data.status ==true) {
                       $(".csrf_token").val(data.csrf_token);
                       $("#ddate_error").html('');
                       $("#deliveryProcess").html('<div class="alert alert-success alert-dismissible"><i class="fas fa-circle-check"></i> '+data.msg+'</div>');
                        setTimeout(function() {window.location.href="<?= base_url().'orders';?>";}, 1000);
                        dataTable.ajax.reload( null, false ); 
                    }
                  }
              });
          });


  $('#pincodeForm').bootstrapValidator({
    excluded: [':disabled', ':hidden', ':not(:visible)'],
    fields: {
      'pincode[]': {
          validators: {
            notEmpty: {
              message: "Please select pincode."
           }
          }
        },
        'franchise[]': {
          validators: {
            notEmpty: {
              message: "Please select franchise."
           }
          }
        },
        'type': {
          validators: {
            notEmpty: {
              message: "Please select type."
           }
          }
        }


    }
  }).on('success.form.bv', function(e) {
    e.preventDefault();
    var formData = new FormData($(this)[0]);
    $.ajax({
      url: '<?= base_url().'master/assignFranchise';?>',
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
               if(res.pincodes_error !='') {
                  $("#pincodes_error").html(res.pincodes_error);
                }else {
                  $("#pincodes_error").html('');
                }
                if(res.franchise_error !='') {
                  $("#franchise_error").html(res.franchise_error);
                }else {
                  $("#franchise_error").html('');
                }
              }
              else if(res.status ==false) {
               $.notify(res.msg, "error");
              }       
      else if(res.status==true)
      {
        $("#pincodes_error").html('');
        $("#franchise_error").html('');
        $.notify(res.msg, "success");
        $("#pincodeView").modal('hide');
      }
      },
      error: function(jqXHR, textStatus, errorThrown) {
      $("#btn_submit").prop("disabled",false);
      var msg = "Something went wrong !";
      switch (jqXHR.status) {
        case 403: msg = "Token error ! Refresh and try again";break;
        default : msg = textStatus+" - "+errorThrown;break;
      } 
      }
    });
  }); 

        });
  
       function exportorders(){
               var dt = $('#ordersFilter').serialize();
               document.location.href="<?= base_url().'reports/exportorders';?>?"+dt;
          }
          

  function generatePdf(oid) {
    if(confirm('Are are sure you want to generate pdf')) {
      $.ajax({
      url :"<?= base_url().'Reports/generatePDF';?>",
      method:"post",
      data:{
        oid:oid
      },
      dataType:"json",
      success:function(res) {
        if(res.status ==true) {
          dataTable.ajax.reload( null, false ); 
        }
      }
    });
    }
    
  }
   function regeneratePdf(oid) {
    if(confirm('Are are sure you want to regenerate pdf')) {
      $.ajax({
      url :"<?= base_url().'Reports/generatePDF';?>",
      method:"post",
      data:{
        oid:oid
      },
      dataType:"json",
      success:function(res) {
        if(res.status ==true) {
          dataTable.ajax.reload( null, false ); 
        }
      }
    });
    }
    
  }
  function sendMailtouser(oid) {
    $.ajax({
      url :"<?= base_url().'Reports/sendMailtouser';?>",
      method:"post",
      data:{
        oid:oid
      },
      dataType:"json",
      success:function(res) {
         if(res.status ==true) {
          alert('Invoice sent successfully');
          dataTable.ajax.reload( null, false ); 
        }
      }
    });
  }
  function cancelOrder(oid) {
    if(confirm('Are you sure you want to cancel ordere')) {
      $.ajax({
          url :"<?= base_url().'Master/cancelOrders';?>",
          method:"post",
          data :{
            oid:oid
          },
          dataType:"json",
          success:function(data) {
            if(data.status ==true) {
              alert(data.msg);
              dataTable.ajax.reload( null, false ); 
            }else {
               dataTable.ajax.reload( null, false ); 
            }
          }
      });
    }
  }

  function assignFranchise(id) {
      $("#pincodeid").val(id);
      $("#pincodeView").modal('show');
      $.ajax({
        url:"<?= base_url().'master/getCustomeraddress';?>",
        method:"post",
        data:{
          id:id
        },
        dataType:"json",
        success:function(data) {
          if(data.status ==true) {
            $("#customerAddress").html(data.address);
          }else {
            $("#customerAddress").html('');
          }
        }
      });
  }
     function commentView(id) {
    $("#commentView").modal('show');
    $("#commentorder").val(id);
  }

   function reinitialsedata(){
                var dt = $("#category_table").DataTable();
                dt.ajax.reload(null, false);
            }


            function getFranchise() {
              var filter = [];

              $('#pincode').each(function(){
                  filter.push($(this).val());
              });
                $.ajax({
                  url:"<?= base_url().'Master/getFranchisepincodes';?>",
                  method:"post",
                  cache:false,
                  data:{
                    pincodes :filter
                  },
                  dataType:"html",
                  success:function(response) {
                    $("#franchise").html(response);
                    dt.ajax.reload(null, false);
                  }
                });
            }
</script>
<div class="modal fade" id="commentView">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Comments</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            <div id="processing1"></div>
          <form id="commentForm" method="post" enctype="multipart/form-data">
            <input type="hidden" class="csrf_token" name="<?= $this->security->get_csrf_token_name();?>" value="<?= $this->security->get_csrf_hash();?>">
            <input type="hidden" name="orderid" id="commentorder">
              <div class="form-group">
                  <label>Comment<font style="color:red"> * </font></label>
                  <textarea cols="4" rows="4" class="form-control" name="comment" id="comment" style="width:100%;" placeholder="Enter Comment"></textarea>
                  <span id="comments_error" class="text-danger"></span>
              </div>
              <button type="submit" id="brandSubmit" class="btn btn-primary">Submit</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="pincodeView">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Assign Franchise</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            <div id="processing1"></div>
            <div id="customerAddress"></div>
          <form id="pincodeForm" method="post" enctype="multipart/form-data">
            <input type="hidden" class="csrf_token" name="<?= $this->security->get_csrf_token_name();?>" value="<?= $this->security->get_csrf_hash();?>">
            <input type="hidden" name="oid" id="pincodeid">
              <div class="form-group">
                <label>Select type <font style="color:red">*</font></label>
                <select name="type" class="form-control" id="type">
                  <option value="4" selected>Franchise</option>
                 <!--  <option value="1">Super Stockiest</option>
                  <option value="2">Stockiest</option>
                  <option value="3">Distributor</option>
                  <option value="5">Sub Franchise</option> -->
                </select>
              </div> 
              <div class="form-group">
                  <label>Select Customer Pincodes<font style="color:red"> * </font></label>
                  <select name="pincode[]" id="pincode"  class="form-control" onchange="getFranchise()" multiple>
                    <option value=""> Select pincode</option>
                      <?php
                      if(count($pincodes)>0) {
                        foreach ($pincodes as $key => $pin) {
                          ?>
                            <option value="<?= $pin->id;?>"><?= $pin->pincode;?></option>
                          <?php
                        }
                       }
                  ?>
                  </select>
                  <span id="pincodes_error" class="text-danger"></span>
              </div>
              <div class="form-group">
                <label>Franchise Name <span style="color:red">*</span></label>
                  <select name="franchise[]" id="franchise"  class="form-control" multiple="">
                    <option value=""> Select Franchise</option>
                   
                  </select>
                  <span id="franchise_error" class="text-danger"></span>
              </div>
              <button type="submit" id="brandSubmit" class="btn btn-primary">Submit</button>
          </form>
        </div>
      </div>
    </div>
  </div>


<div class="modal fade" id="productModal">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Shipping Date</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div id="processing"></div>
          <form id="productForm" method="post" enctype="multipart/form-data">
            <input type="hidden" class="csrf_token" name="<?= $this->security->get_csrf_token_name();?>" value="<?= $this->security->get_csrf_hash();?>">
            <input type="hidden" name="orderid" id="orderidss">
              <div class="form-group">
                  <label>Shipping Date<font style="color:red"> * </font></label>
                  <input type="date" name="sdate" id="sdate" class="form-control">
                  <span id="sdate_error" class="text-danger"></span>
              </div>
              <button type="submit" id="brandSubmit" class="btn btn-primary">Submit</button>
           
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="delproductModal">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Delivered Date</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div id="deliveryProcess"></div>
          <form id="deliveryproductForm" method="post" enctype="multipart/form-data">
            <input type="hidden" class="csrf_token" name="<?= $this->security->get_csrf_token_name();?>" value="<?= $this->security->get_csrf_hash();?>">
            <input type="hidden" name="orderid" id="orderids">
              <div class="form-group">
                  <label>Delivery Date<font style="color:red"> * </font></label>
                  <input type="date" name="ddate" id="ddate" class="form-control">
                  <span id="ddate_error" class="text-danger"></span>
              </div>
              <button type="submit" id="delSubmit" class="btn btn-primary">Submit</button>
           
          </form>
        </div>
      </div>
    </div>
  </div>