<?php
	//echo "<pre>";print_r($products);exit;
?>
<?= $header1;?>
<style type="text/css">
    label {
        cursor:pointer!important;
    }
    ul li {
      line-height: 20px!important;
    }
	
	.btn, input {
    margin-right: 8px;
  }

.widget-body ul li {
    line-height: 28px!important;
}
 .newactive {
            border-color: #f18800!important;
    color: #f18800;
    }
    .newsletter-popup {
      display: none!important
    }

    .mfp-bg {
    display: none!important;
}

</style>

    <main class="main">
    
     <!-- Start of Page Header -->
            <div class="page-header">
                <div class="container">
                    <h1 class="page-title mb-0">Shop </h1>
                </div>
            </div>
            <!-- End of Page Header -->
            
            <!-- Start of Breadcrumb -->
            <nav class="breadcrumb-nav">
                <div class="container">
                    <ul class="breadcrumb bb-no">
                        <li><a href="<?= base_url();?>">Home</a></li>
                        <li>Shop</li>
                    </ul>
                </div>
            </nav>
            <!-- End of Breadcrumb -->

            <!-- Start of Page Content -->
            <div class="page-content">
                <div class="container">



                    <!-- Start of Shop Content -->
                    <div class="shop-content row gutter-lg mb-10">
                <aside class="sidebar shop-sidebar sticky-sidebar-wrapper sidebar-fixed">
                            <div class="sidebar-overlay"></div>
                            <a class="sidebar-close" href="#"><i class="close-icon"></i></a>

                            <div class="sidebar-content scrollable">
                                <div class="sticky-sidebar">
                                    


                                    <div class="widget widget-collapsible">
                                        <h3 class="widget-title"><label>Price</label></h3>
                                        <div class="widget-body">
                                            <ul class="filter-items search-ul">
                                                <?php
                                                    if(count($pfilters) >0) {
                                                        foreach ($pfilters as $key => $value) {
                                                            ?>
                                                            <li><input type="checkbox" value="<?= $value->id;?>" name="price[]" class="checkedbox getPrice" /><?= $value->price_range;?></li>
                                                            <?php
                                                        }
                                                    }
                                                ?>
                                                
                                        
                                            </ul>
                                           
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </aside>  
                        <!-- End of Shop Sidebar -->

                        <!-- Start of Shop Main Content -->
                        <div class="main-content" style="width: 100%!important;max-width: 100%!important;">
                            <div class="container">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <select style="width:20%;float: right;margin-bottom: 20px" class="form-control" id="sortData" >
                                            <option value="">Select Option</option>
                                            <option value="1">Low to High</option>
                                            <option value="2">High to Low</option>
                                            <option value="3">Newest First</option>
                                        </select>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                           
                            <div class="product-wrapper row cols-md-4 cols-sm-2 cols-2" id="products">
                              
                              
                            </div>

                            <div class="toolbox toolbox-pagination justify-content-end">
                             <span id="newpage"></span>
                            </div>
                        </div>
                        <!-- End of Shop Main Content -->
                    </div>
                    <!-- End of Shop Content -->
                </div>
            </div>
            <!-- End of Page Content -->
        </main>
<?= $footer;?>
<?= $js;?>
<div id="myModal" class="modal">
<div class="modal-content">
    <span class="close">&times;</span>
    <form id="checkhomepin" method="post" enctype="multipart/form-data">
         <input type="hidden" class="csrf_token" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
        <input type="hidden" name="pid" id="pincodedata" />
        <input type="hidden" name="psid" id="psid" />
        <input type="hidden" name="sid" id="sid" />
        <div class="input-group">
        <input type="number" name="cpincodes" id="cpincode"  maxlength="6" minlength="6" pattern="/^-?\d+\.?\d*$/" onkeypress="if(this.value.length==6) return false;" class="form-control" placeholder="Enter Pin Code" required>
        
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
    <div id="pincode_error"></div>
     <div id="notificationme"></div>
<form method="post" id="notifymes" class="mt-4">
    <input type="hidden" class="csrf_token" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
    <div class="form-group mb-3">
<input type="number" placeholder="Enter Phone Number" class="form-control" id="phoneno" name="phoneno" maxlength="10" minlength="10" pattern="/^-?\d+\.?\d*$/" onkeypress="if(this.value.length==10) return false;"/>
<span class="text-danger" id="phoneno_error"></span>
</div>
 <div class="form-group mb-3">
<input type="email" placeholder="Enter Email ID" class="form-control" id="emailid" name="emailid" />
<span class="text-danger" id="emailid_error"></span>
</div>
<input type="submit" class="btn btn-primary btn-sm" value="SEND">
</form>
</div>
</div>

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
<style>
.modal {
  display: none; 
  position: fixed; 
  z-index: 100000; 
  padding-top: 100px; 
  left: 0;
  top: 0;
  width: 100%; 
  height: 100%;
  overflow: auto; 
  background-color: rgb(0,0,0); 
  background-color: rgba(0,0,0,0.4);
}


.modal-content {
  background-color: #fefefe;
  margin: auto;
  padding: 20px;
  border: 1px solid #888;
  width: 400px;
}


.close {
  color: #aaaaaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: #000;
  text-decoration: none;
  cursor: pointer;
}
</style>

<script type="text/javascript">
var modal = document.getElementById("myModal");
    var redirect = document.getElementById("redirectModal");
var span = document.getElementsByClassName("close")[0];
var span1 = document.getElementsByClassName("redirectclose")[0];
span.onclick = function() {
  modal.style.display = "none";
}
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}

span1.onclick = function() {
  redirect.style.display = "none";
}
window.onclick = function(event) {
  if (event.target == modal) {
    redirect.style.display = "none";
  }
}
function redirectSpecialoffers() {
   redirect.style.display = "block";
}
    var limit = 20;
    var start = 0;
    var action = 'inactive';
        function getproductdetails(limit,start) {
            var price = get_filter('getPrice');
            var text = $("#tags").val();
            var id = "<?= $this->uri->segment(3);?>";
            $.ajax({
            url :"<?= base_url().'home/productsFilters';?>",
            method:"post",
            data : {
                id:id,
                price:price,
                limit:limit,
                start:start
            },
            dataType:"json",
            success:function(response) {
             if(response.status ==true){
                   $("#products").append(response.data);
                 if(response.data =='') {
                    action = 'active';
                 }else {
                  action = "inactive";
                 }
             }
            }
          });
        }
        getproductdetails(limit,start);
       $(window).scroll(function(){
        if($(window).scrollTop() + $(window).height() > $(document).height() && action == 'inactive')
        {
         action = 'active';
         start = start + limit;
         setTimeout(function(){
          getproductdetails(limit, start);
         }, 1000);
        }
       }); 


    function get_filter(class_name)
    {
        var filter = [];
        $('.'+class_name+':checked').each(function(){
            filter.push($(this).val());
        });
        return filter;
    }

    

      function filter_data()
    {
    
        var price = get_filter('getPrice');
        var id = "<?= $this->uri->segment(3);?>";
        var type = "diyas";
       
        $.ajax({
            url:"<?= base_url().'home/getPrice';?>",
            method:"POST",
             dataType :"html",
            data:{price:price,limit:limit,
                start:start,id:id},
            success:function(data){
              
                   $("#products").html(data);
                 
             
            }
        });
    }
    $(document).ready(function() {
        $(document).on("change","#sortData",function(e) {
            e.preventDefault();
            var order = $(this).val();
            var id = "<?= $this->uri->segment(3);?>";
            var type = "diyas";
       
        $.ajax({
            url:"<?= base_url().'home/getPrice';?>",
            method:"POST",
             dataType :"html",
            data:{order:order,limit:limit,
                start:start,id:id},
            success:function(data){
                   $("#products").html(data);
            }
        });
        });
    });

    function get_filter(class_name)
    {
        var filter = [];
        $('.'+class_name+':checked').each(function(){
            filter.push($(this).val());
        });
        return filter;
    }
   
 $('.checkedbox').click(function(){
        filter_data();
    });

        function listsize(pid,psid) {
       $("#sizesnew"+pid).val(psid);
       $.ajax({
            url:"<?= base_url().'Home/getProdsizerate';?>",
            method:"post",
            data :{
                pid:pid,
                psid:psid
            },
            dataType:"json",
            success:function(data) {
                if(data.status ==true) {
                    $("#sellprice"+pid).html(data.price);
                    $("#sellmrp"+pid).html(data.mrp);
                    $("#discounttag"+pid).html(data.discount);
                    $("#hideBtn"+pid).show();
                    $("#outofstockmsg"+pid).html('');
                    $("#productpricelist"+pid).show();
                    $("#hidearrivalstext"+pid).hide();
                    $("#discounttag"+pid).show();
                     $(".wishlist"+pid).hide();
                }else if(data.status ==false) {
                    $("#hideBtn"+pid).hide();
                    $("#outofstockmsg"+pid).html('<p style="color:red;font-size:16px">'+data.msg+'</p>');
                    $("#productpricelist"+pid).hide();
                    $("#hidearrivalstext"+pid).hide();
                    $("#discounttag"+pid).hide();
                     $(".wishlist"+pid).hide();
                }
            }
       });
   }
    function listsizeses(pid,psid) {
       $("#sizesnew"+pid).val(psid);
       $.ajax({
            url:"<?= base_url().'Home/getProdsizeratecategorywise';?>",
            method:"post",
            data :{
                pid:pid,
                psid:psid
            },
            dataType:"json",
            success:function(data) {
                if(data.status ==true) {
                    $("#sellpricesss"+pid).html(data.price);
                    $("#sellmrpsss"+pid).html(data.mrp);
                    $("#discounttag1"+pid).html(data.discount);
                    $("#hideNewbtn"+pid).show();
                    $("#outofstockcatmsg"+pid).html('');
                     $("#productprise"+pid).show();
                     $("#hideoutofstocktext"+pid).hide();
                     $("#discounttag1"+pid).show();
                      $(".wishlist1"+pid).show();
                }else if(data.status ==false) {
                    $("#hideNewbtn"+pid).hide();
                    $("#outofstockcatmsg"+pid).html('<p style="color:red;font-size:16px">'+data.msg+'</p>');
                    $("#productprise"+pid).hide();
                    $("#hideoutofstocktext"+pid).hide();
                    $("#discounttag1"+pid).hide();
                    $(".wishlist1"+pid).hide();

                }
            }
       });
   }


   $(document).ready(function() {
        
    $("#checkhomepin").on("submit",function(e) {
   e.preventDefault();
   var pincode = $("#cpincode").val();
var formdata =new FormData(this);
 var modal = document.getElementById("myModal");
        $.ajax({
          url :"<?= base_url().'Home/checkhomepicode';?>",
          method :"post",
          dataType:"json",
          data :formdata,
             contentType: false,
            cache: false,
            processData:false,
            success:function(data) {
            if(data.formerror ==false) {
              if(data.pincode_error !='') {
                $("#pincode_error").html(data.pincode_error).addClass('text-danger');
              s
              }else {
                $("#pincode_error").html('');
              
              }
            }
            else if(data.status ==false) {
              $("#pincode_error").show().html(data.msg);
              
            }
            else if(data.status ==true) {
              $("#pincodestatuscheck").val(1);
             
                $("#pincode_error").html('');

              $("#pincodedata").val(1);
              modal.style.display = "none";
               
               $("#cpincode").removeClass('inputborders');
               $("#checkpincodevalid").val(1);

                 $(".csrf_token").val(data.csrf_token);
                $(".cart-dropdown").addClass('opened');
                $("#appendProducts").html(data.msg);
                $(".cart-count").html(data.count);
                 $("#savingamount").html(data.totaldiff);
                $("#savingamount1").html(data.totaldiff);
                $("#savingamount2").html(data.totaldiff);
                $(".price").html('<i class="fas fa-rupee-sign" style="margin-right: 5px"></i>'+data.subtotal);
              
            }
          }
      });
   
      
  });


       $("#notifymes").on("submit",function(e) {
    e.preventDefault();
      var formdata =new FormData(this);
      $.ajax({
          url :"<?= base_url().'Home/savenotification';?>",
          method :"post",
          dataType:"json",
          data :formdata,
          contentType: false,
            cache: false,
            processData:false,
            success:function(data) {
            if(data.formerror ==false) {
              $(".csrf_token").val(data.csrf_token);
              if(data.phoneno_error !='') {
                $("#phoneno_error").html(data.phoneno_error);
              }else {
                $("#phoneno_error").html('');
              }
              if(data.emailid_error !='') {
                $("#emailid_error").html(data.emailid_error);
              }else {
                $("#emailid_error").html('');
              }
            }
            else if(data.status ==false) {
              $(".csrf_token").val(data.csrf_token);
              $("#notificationme").html('<div class="alert alert-danger alert-dismissible" style="color:red!important;"><i class="fas fa-ban"></i> '+data.msg+'</div>').show().fadeOut(10000);
            }
            else if(data.status ==true) {
              
              $(".csrf_token").val(data.csrf_token);
               $("#phoneno_error").html('');
               $("#emailid_error").html('');
              
                 $("#notificationme").html('<div class="alert alert-success alert-dismissible" style="color:#000!important;">'+data.msg+'</div>').show().fadeOut(20000);
                 $("#notifymes")[0].reset();

                 setTimeout(function() {var modal = document.getElementById("myModal");
              modal.style.display = "none";}, 4400);
            }
          }
      });
  });


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
