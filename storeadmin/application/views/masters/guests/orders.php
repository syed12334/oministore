<?= $header;?>
<style type="text/css">
    input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
input[type=number] {
  -moz-appearance: textfield;
}
</style>
<div class="page has-sidebar-left bg-light height-full">
 <div class="container-fluid my-3">
        <div class="row">
            <div class="col-md-12">
                    <div class="card my-3 no-b">
                    <div class="card-body">

                        <div class="card-title">Guest Orders</div>
                          <?php 
                            if($this->session->flashdata('message')) {
                                echo $this->session->flashdata('message');
                            }
                        ?>
                        
                            <table id="category_table" class="table display table-bordered table-striped no-wrap" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>Sl. No.</th>
                                        <th>Action</th>
                                        <th>Orderid</th>
                                        <th>Orderdate</th>
                                        <th>Order Status</th>
                                        <th>Ordered By</th>
                                        <th>Payment Mode</th>
                                        <th style="width:100px!important">Comments</th>
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
<?= $footer;?>
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
                  url:"<?=base_url().'master/getguestOrderslist'?>",  
                  type:"POST",
                  data: function(d){
                      d.forPost    = 1;
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
                
            }
        } );
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
                  url :"<?= base_url().'master/updateguestShipping';?>",
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
                        setTimeout(function() {window.location.href="<?= base_url().'guestorders';?>";}, 1000);
                    }
                  }
              });
          });



              $("#commentForm").on("submit",function(e) {
            e.preventDefault();
              var formdata =new FormData(this);
              $.ajax({
                  url :"<?= base_url().'master/updateCommentguest';?>",
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
                       $(".csrf_token").val(data.csrf_token);
                       $("#comment").html('');
                       $("#comments_error").html('');
                       $("#processing1").html('<div class="alert alert-success alert-dismissible"><i class="fas fa-circle-check"></i> '+data.msg+'</div>');
                        setTimeout(function() {$("#commentView").modal('hide');$("#processing1").html('');}, 3000);
                        dataTable.ajax.reload( null, false ); 
                        $("#commentForm")[0].reset();
                    }
                  }
              });
          });

              $("#deliveryproductForm").on("submit",function(e) {
            e.preventDefault();
              var formdata =new FormData(this);
              $.ajax({
                  url :"<?= base_url().'master/updateguestDelivered';?>",
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
                      $("#processing").html('<div class="alert alert-danger alert-dismissible" style="font-size: 15px!important"><i class="fas fa-ban"></i> '+data.msg+'</div>').show();
                      $("#delSubmit").prop('disabled',false);
                    }
                    else if(data.status ==true) {
                       $(".csrf_token").val(data.csrf_token);
                       $("#ddate_error").html('');
                       $("#processing").html('<div class="alert alert-success alert-dismissible"><i class="fas fa-circle-check"></i> '+data.msg+'</div>');
                        setTimeout(function() {window.location.href="<?= base_url().'guestorders';?>";}, 1000);
                    }
                  }
              });
          });

        });
  

  function generatePdf(oid) {
    if(confirm('Are are sure you want to generate pdf')) {
      $.ajax({
      url :"<?= base_url().'Reports/guestgeneratePDF';?>",
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
    function commentView(id) {
    $("#commentView").modal('show');
    $("#commentorder").val(id);
  }
   function regeneratePdf(oid) {
    if(confirm('Are are sure you want to regenerate pdf')) {
      $.ajax({
      url :"<?= base_url().'Reports/guestgeneratePDF';?>",
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
      url :"<?= base_url().'Reports/sendMailtouserguest';?>",
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
            <div id="processing"></div>
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