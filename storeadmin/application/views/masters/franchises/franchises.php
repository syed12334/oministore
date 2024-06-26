<?= $header;?>
<style type="text/css">
  .modal-lg {
    width:1600px!important;
    max-width: 1600px!important
  }
</style>
<div class="page has-sidebar-left bg-light height-full">
 <div class="container-fluid my-3">
        <div class="row">
            <div class="col-md-12">
                    <div class="card my-3 no-b">
                    <div class="card-body">

                        <div class="card-title">Franchise</div>
                          <?php 
                            if($this->session->flashdata('message')) {
                                echo $this->session->flashdata('message');
                            }
                        ?>
                        <a class="btn btn-success" href="<?= base_url().'franchiseadd';?>" style="float: right;margin-bottom: 20px;margin-right: 20px"><i class="fa fa-plus"></i> Add</a>
                        <br /><br /><br />
                            <table id="category_table" class="table display table-bordered table-striped no-wrap" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>Sl. No.</th>
                                        <th style="width:170px!important">Action</th>
                                        <th>Franchise Name</th>
                                        <th>Person Name</th>
                                        <th>Contact Number</th>
                                        <th>Whatsapp Number</th> 
                                       <th>Address</th>                                      
                                                                            
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
                  url:"<?=base_url().'master/getfranchises'?>",  
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
                       "targets":[3,4,5],  
                       "orderable":true,  
                  },  
                  {  
                       "targets":[0,1,2],  
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
                function updateStatus(id,status){
                  var msg = "";
                  if(status ==-1) {
                    msg +="Are you sure you want to deactivate";
                  }
                   else if(status ==0) {
                    msg +="Are you sure you want to activate";
                  }
                  else if(status ==2) {
                    msg +="Are you sure you want to delete";
                  }
                       var postdata = { id : id,status : status,"<?= $this->security->get_csrf_token_name();?>": $(".csrf_token").val() } ;
                        //console.log( postdata );
                        if(confirm(msg)) {
                            $.ajax({                        
                            url: "<?=base_url().'master/setfranchiseStatus'?>",
                            type: "post",
                            data:  postdata ,
                            dataType : 'json',
                            success: function (response) {
                                $(".csrf_token").val(response.csrf_token);
                                if(response.status == 1){
                                    //reinitialsedata();
                                    $(".csrf_token").val(response.csrf_token);
                                    dataTable.ajax.reload( null, false ); 
                                }else{
                                    $(".csrf_token").val(response.csrf_token);
                                    dataTable.ajax.reload( null, false );
                                }
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                               console.log();
                            }
                        });
                        }
                        
            }
            function viewPincodes(id) {
              $("#fid").val(id);
              $.ajax({
                url :"<?= base_url().'master/fetchFranchisepincode';?>",
                method:"post",
                data:{
                  id:id
                },
                dataType:"json",
                success:function(res) {
                  if(res.status ==true) {
                    $("#addPincodes").html(res.data);
                    $("#pincodeModal").modal('show');
                  }
                }
              });
            }

            function viewFranchisedetails(id)  {
                  $("#viewFranchiselist").modal('show');
                  $.ajax({
                url :"<?= base_url().'master/viewFranchiselistview';?>",
                method:"post",
                data:{
                  id:id
                },
                dataType:"json",
                success:function(res) {
                  if(res.status ==true) {
                    $("#listfranchise").html(res.data);
                  }
                }
              });
            }
</script>

<div class="modal fade" id="pincodeModal">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Pincodes</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div id="processing"></div>
                <button onclick="addMorepincodes()" class="btn btn-primary" style="float: right;margin-bottom: 10px;cursor: pointer!important;z-index:9999999999999!important">Add Pincodes</button>
          <form id="categoryForm" method="post" enctype="multipart/form-data">
            <input type="hidden" class="csrf_token" name="<?= $this->security->get_csrf_token_name();?>" value="<?= $this->security->get_csrf_hash();?>">

            <input type="hidden" name="fid" id="fid">
              <div id="addPincodes"></div>

              <button type="submit" id="brandSubmit" class="btn btn-primary">Submit</button>
             
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="viewFranchiselist">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Franchise List</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div id="listfranchise">
      
            </div>
                
        </div>
      </div>
    </div>
  </div>