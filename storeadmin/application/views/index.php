<?= $header;?>
<style type="text/css">
	#toporders {
		padding: 20px;
		border-radius: 5px;
		box-shadow: 0 0 var(--spacing--xs) var(--color-ui--grey-90);
		-webkit-box-shadow: 0 0 var(--spacing--xs) var(--color-ui--grey-90);
		-moz-box-shadow: 0 0 var(--spacing--xs) var(--color-ui--grey-90);
		-ms-box-shadow: 0 0 var(--spacing--xs) var(--color-ui--grey-90);
		-o-box-shadow: 0 0 var(--spacing--xs) var(--color-ui--grey-90);
		background: #fff;
        display: block;
	}
	.topor {
		background: #f56954!important
	}
	.products {
		background: #00a65a!important
	}
	.reviews {
		background: #00c0ef!important
	}
	.reguser {
		background: #0073b7!important
	}
	#toporders h1 {
		font-weight: bold;
		font-size: 35px;
		color:#fff;
	}
	#toporders h3 {
		font-size: 20px;
		color:#fff;
	}
</style>
<div class="page has-sidebar-left bg-light height-full">
<div class="container-fluid">
        <div class="row my-3">
        	<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3" style="margin-bottom: 20px">
        		<a href="<?= base_url().'orders';?>" id="toporders" class="topor">
        			<h1><?= count($orders);?></h1>
        			<h3>Total Orders</h3>
        		</a>
        	</div>
          <?php
            if($id ==1) {
              ?>
                <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3" style="margin-bottom: 20px">
            <a href="<?= base_url().'products';?>" id="toporders" class="products">
              <h1><?= count($products);?></h1>
              <h3>Top Products</h3>
            </a>
          </div>
          <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3" style="margin-bottom: 20px">
            <a href="<?= base_url().'reviews';?>" id="toporders" class="reviews">
              <h1><?= count($reviews);?></h1>
              <h3>Reviews</h3>
            </a>
          </div>
            <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3" style="margin-bottom: 20px">
            <a href="<?= base_url().'users';?>" id="toporders" class="reguser">
              <h1><?= count($users);?></h1>
              <h3>Registered Users</h3>
            </a>
          </div>
              <?php
            }else {

            }
          ?>
        	
        	<div class="clearfix"></div>
        	<br />
            <!-- bar chart -->
         <!--    <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="card ">
                    <div class="card-header white">
                            <strong> Total Sales</strong>
                        </div>
                    <div class="card-body p-0">
                        <div id="graph_bar" style="width:100%; height:280px;"></div>
                    </div>
                </div>
            </div> -->
            <!-- /bar charts -->

            <!-- bar charts group -->
         <!--    <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="card">

                    <div class="card-header white">
                            <h6> Total Sales(Monthwise)</h6>
                        </div>
                    <div class="card-body p-0">
                        <div id="graph_bar_group" style="width:100%; height:280px;"></div>
                    </div>
                </div>
            </div> -->
            <div class="clearfix"></div>
            <!-- /bar charts group -->
        </div>
        <div class="row my-3">
            <!-- bar charts group -->
          <!--   <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="card ">
                    <div class="card-header white">
                        <h6>Total Order Cancellation</h6>
                    </div>
                    <div class="card-body p-0">
                        <div id="graphx" style="width:100%; height:300px;" ></div>
                    </div>
                </div>
            </div> -->
            <!-- /bar charts group -->

            <!-- pie chart -->
             <!--  <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="card">
                    <div class="card-header white">
                        <h6>Total Reviews</small></h6>
                    </div>
                    <div class="card-body p-0">
                        <div id="graph_area" style="width:100%; height:300px;"></div>
                    </div>
                </div>
            </div> -->
            <!-- /Pie chart -->
        </div>
        
    </div>
     <div class="container-fluid my-3">
        <div class="row">
            <div class="col-md-12">
                    <div class="card my-3 no-b">
                    <div class="card-body">

                        <div class="card-title">Today Orders</div>
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
                  url:"<?=base_url().'master/getTodaylist'?>",  
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
                     $("#orderid").val(id);
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
                        setTimeout(function() {window.location.href="<?= base_url();?>";}, 1000);
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
                       $(".csrf_token").val(data.csrf_token);
                       $("#comment").html('');
                       $("#comments_error").html('');
                       $("#processing1").html('<div class="alert alert-success alert-dismissible"><i class="fas fa-circle-check"></i> '+data.msg+'</div>');
                        setTimeout(function() {$("#commentView").modal('hide');$("#processing1").html('');}, 2000);
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
                        setTimeout(function() {window.location.href="<?= base_url();?>";}, 1000);
                    }
                  }
              });
          });

        });
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
        function commentView(id) {
    $("#commentView").modal('show');
    $("#commentorder").val(id);
  }
</script>
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
            <input type="hidden" name="orderid" id="orderid">
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
   <script type="text/javascript">
    var sessionTimeout = <?php echo $this->config->item('sess_expiration') ?>;
    
    function resetSessionTimeout() {
        <?php echo 'update_session_time()'; ?>;
    }
    
    function setupSessionTimeout() {
        var timeoutHandle;
        
        function countdown() {
            clearTimeout(timeoutHandle);
            timeoutHandle = setTimeout(logout, sessionTimeout * 1000);
        }
        
        function logout() {
            window.location.href = "<?php echo base_url().'Login/logout'; ?>"; // Replace with your logout URL
        }
        
        document.onmousemove = resetSessionTimeout;
        document.onkeydown = resetSessionTimeout;
        
        countdown();
    }
    
    $(document).ready(function() {
        setupSessionTimeout();
    });
</script>
