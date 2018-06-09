<!DOCTYPE html>

<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?=TITLE;?></title>

<link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url("images/favicon.ico");?>">

<link href="<?php echo base_url("css/backend.css");?>" rel="stylesheet">
<link href="<?php echo base_url("css/style-color-backend.css");?>" rel="stylesheet">

<!-- Bootstrap -->

<link href="<?php echo base_url("css/bootstrap.min.css");?>" rel="stylesheet">
<link href="<?php echo base_url("css/bootstrap.css");?>" rel="stylesheet">
<link href="<?php echo base_url("css/style-color.css");?>" rel="stylesheet">

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->

<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->

<!--[if lt IE 9]>

      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>

      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

    <![endif]-->

</head>

<body>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) --> 

<script src="<?php echo base_url("js/jquery.min.js");?>"></script> 

<!-- Include all compiled plugins (below), or include individual files as needed --> 

<script src="<?php echo base_url("js/bootstrap.min.js");?>"></script>

<script src="<?php echo base_url("js/common.js");?>"></script>
<!--<script src="http://www.finy.se/finyapp/js/jquery.validate.js"></script>-->
<script type="text/javascript">
$(document).ready(function(){
//$("form.validate-form").validate({ errorPlacement: function(error, element) {}, errorClass: "validate-error" , validClass: "validate-success"});
	$("input[type='text'], input[type='password']").each(function(){
		$(this).attr("autocapitalize", "off");
	});
});
</script>
<style>
label.error{
	background:none;
	padding:0px;
	border:none;
}
</style>
<div class="header-wps">
  <div class="container">
    <nav class="navbar navbar-inverse" role="navigation">
      <div class="container-fluid"> 
        
        <!-- Brand and toggle get grouped for better mobile display -->
   <?php //echo "<pre>"; print_r($session);?>
        
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#menubar"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
          <a class="navbar-brand logo-wps" href="<?php echo base_url('index.php/admin/dashboard'); ?>"><img src="<?php echo base_url("images/logo.png");?>"></a> </div>
        
        <!-- Collect the nav links, forms, and other content for toggling -->
        
        
         <?php if((isset($session['ADMIN_ID']) && !empty($session['ADMIN_ID'])) ){ ?>
<?php $controller = $this->router->fetch_class(); ?>
<?php $method = $this->router->fetch_method(); ?>
  <div class="collapse navbar-collapse" id="menubar">

          <ul class="nav navbar-nav top_menubar">
      
      <li class="<?php if($controller == 'admin' && $method == 'dashboard'){ ?> active<?php } ?>"><a href="<?php echo base_url('index.php/admin/dashboard'); ?>" class="list-group-item1 list-group-item-success1"><b class="glyphicon glyphicon-play"></b>Dashboard</a></li>
      
      <?php /*?><a href="#demo2" class="list-group-item1 list-group-item-success1" data-toggle="collapse" data-parent="#MainMenu"><b class="glyphicon glyphicon-play"></b>Orders</a>
      
      <div class="collapse<?php if($controller == 'adminorders'){ ?> i
	  n<?php } ?>" id="demo2">
      <a href="<?php echo base_url('index.php/adminorders/orders'); ?>" class="list-group-item2<?php if($controller == 'adminorders' && ($method == 'orders')){ ?> active<?php } ?>"><b class="glyphicon glyphicon-play"></b>View Current Orders</a>
      <a href="<?php echo base_url('index.php/adminorders/createorder'); ?>" class="list-group-item2<?php if($controller == 'adminorders' && ($method == 'createorder')){ ?> active<?php } ?>"><b class="glyphicon glyphicon-play"></b>Create New Order</a>
      </div><?php */?>
      <li class="<?php if($controller == 'adminorders' && ($method == 'orders')){ ?> active<?php } ?>"><a href="<?php echo base_url('index.php/adminorders/orders'); ?>" class="list-group-item1 list-group-item-success1"><b class="glyphicon glyphicon-play"></b>Orders List</a></li>
      
      <li class="<?php if($controller == 'adminorders' && ($method == 'orderqueue' || $method == 'addorderqueue')){ ?> active<?php } ?>"><a href="<?php echo base_url('index.php/adminorders/orderqueue'); ?>" class="list-group-item1 list-group-item-success1"><b class="glyphicon glyphicon-play"></b>Orders Queue List</a></li>
      
      <li class="<?php if($controller == 'adminorders' && ($method == 'counterdetails')){ ?> active<?php } ?>"><a href="<?php echo base_url('index.php/adminorders/counterdetails'); ?>" class="list-group-item1 list-group-item-success1"><b class="glyphicon glyphicon-play"></b>Counters</a></li>

      <li class="<?php if($controller == 'adminorders' && ($method == 'createorder')){ ?> active<?php } ?>"><a href="<?php echo base_url('index.php/adminorders/createorder'); ?>" class="list-group-item1 list-group-item-success1"><b class="glyphicon glyphicon-play"></b>Create New Order</a></li>
      
      <?php if($session['LOGIN_TYPE'] == 'EMPLOYEE'){?>
      <li class="<?php if($controller == 'adminpatients'){ ?> active<?php } ?>"><a href="<?php echo base_url('index.php/adminpatients/addpatient'); ?>" class="list-group-item1 list-group-item-success1"><b class="glyphicon glyphicon-play"></b>Add Patient</a></li>
      <?php }?>
      <?php if($session['LOGIN_TYPE'] == 'PARTNER'){?>
      <li class="<?php if($controller == 'admin' && ($method == 'employeedetails' || $method == 'addemployee')){ ?> active<?php } ?>"><a href="<?php echo base_url('index.php/admin/employeedetails'); ?>" class="list-group-item1 list-group-item-success1"><b class="glyphicon glyphicon-play"></b>Employee List</a></li>
      <?php }?>
    <?php if($session['LOGIN_TYPE'] != 'EMPLOYEE'){ ?>
    <?php if($session['LOGIN_TYPE'] == 'ADMIN'){ ?>
    <li class="<?php if($controller == 'admin' && ($method == 'partnerdetails' || $method == 'addpartner')){ ?> active<?php } ?>"><a href="<?php echo base_url('index.php/admin/partnerdetails'); ?>" class="list-group-item1 list-group-item-success1"><b class="glyphicon glyphicon-play"></b>Partner List</a></li>
      
      <li class="<?php if($controller == 'admin' && ($method == 'employeedetails' || $method == 'addemployee')){ ?> active<?php } ?>"><a href="<?php echo base_url('index.php/admin/employeedetails'); ?>" class="list-group-item1 list-group-item-success1"><b class="glyphicon glyphicon-play"></b>Employee List</a></li>
      <?php }?>
             <?php if($session['LOGIN_TYPE']!='EMPLOYEE'){?>
      <li class="<?php if($controller == 'adminpatients'){ ?> active<?php } ?>"><a href="<?php echo base_url('index.php/adminpatients/patients'); ?>" class="list-group-item1 list-group-item-success1"><b class="glyphicon glyphicon-play"></b>Patient List</a></li>
    <?php } else { ?>
      <li class="<?php if($controller == 'adminpatients'){ ?> active<?php } ?>"><a href="<?php echo base_url('index.php/adminpatients/addpatient'); ?>" class="list-group-item1 list-group-item-success1"><b class="glyphicon glyphicon-play"></b>Add Patient</a></li>
      <?php } ?>

      
      
      <li class="<?php if($controller == 'admincategories'){ ?> active<?php } ?>"><a href="#demo3" class="list-group-item1 list-group-item-success1" data-toggle="collapse" data-parent="#MainMenu"><b class="glyphicon glyphicon-play"></b>Categories</a>
      
      <div class="collapse<?php if($controller == 'admincategories'){ ?> in<?php } ?>" id="demo3">
      <a href="<?php echo base_url('index.php/admincategories/maincategories'); ?>" class="list-group-item2<?php if($controller == 'admincategories' && ($method == 'maincategories' || $method == 'addcategory')){ ?> active<?php } ?>"><b class="glyphicon glyphicon-play"></b>Categories</a>
      <a href="<?php echo base_url('index.php/admincategories/subcategories'); ?>" class="list-group-item2<?php if($controller == 'admincategories' && ($method == 'subcategories'|| $method == 'addsubcategory')){ ?> active<?php } ?>"><b class="glyphicon glyphicon-play"></b>Products</a>
      </div>
      </li>
      <?php if($session['LOGIN_TYPE'] != 'EMPLOYEE'){ ?>
      <li class="<?php if($controller == 'adminproducts'){ ?> active<?php } ?>"><a href="<?php echo base_url('index.php/adminproducts/products'); ?>" class="list-group-item1 list-group-item-success1"><b class="glyphicon glyphicon-play"></b>Product Pricing</a></li>
      <?php } ?>
       <li class="<?php if($controller == 'admininventory'){ ?> active<?php } ?>"><a href="<?php echo base_url('index.php/admininventory/inventorydetails'); ?>" class="list-group-item1 list-group-item-success1"><b class="glyphicon glyphicon-play"></b>Inventory</a></li>
       <li class="<?php if($controller == 'adminempinventory'){ ?> active<?php } ?>"><a href="<?php echo base_url('index.php/adminempinventory/inventoryempdetails'); ?>" class="list-group-item1 list-group-item-success1"><b class="glyphicon glyphicon-play"></b>Employee Inventory</a></li>
       <li class="<?php if($controller == 'admin' && $method == 'ipdetails'){ ?> active<?php } ?>"><a href="<?php echo base_url('index.php/admin/ipdetails'); ?>" class="list-group-item1 list-group-item-success1"><b class="glyphicon glyphicon-play"></b>Settings</a></li>
       <?php } ?>
       <?php if($session['LOGIN_TYPE'] != 'EMPLOYEE'){ ?>
        <li class="<?php if($controller == 'admin' && $method == 'getitemcode'){ ?> active<?php } ?>"><a href="<?php echo base_url('index.php/admin/getitemcode'); ?>" class="list-group-item1 list-group-item-success1"><b class="glyphicon glyphicon-play"></b>Item QBCODE</a></li>
        <?php } ?>
       <?php if($session['LOGIN_TYPE']=='ADMIN'){?>
       <li class="<?php if($controller == 'admin' && ($method == 'bannerdetails' || $method == 'addbanner')){ ?> active<?php } ?>"><a href="<?php echo base_url('index.php/admin/bannerdetails'); ?>" class="list-group-item1 list-group-item-success1"><b class="glyphicon glyphicon-play"></b>Banner</a></li>
       <li class="<?php if($controller == 'admin' && $method == 'indexText'){ ?> active<?php } ?>"><a href="<?php echo base_url('index.php/admin/indexText'); ?>" class="list-group-item1 list-group-item-success1"><b class="glyphicon glyphicon-play"></b>Home Content</a></li>
       <?php }?>
       <li class="<?php if($controller == 'admin' && $method == 'changepwd'){ ?> active<?php } ?>"><a href="<?php echo base_url('index.php/admin/changepwd'); ?>" class="list-group-item1 list-group-item-success1"><b class="glyphicon glyphicon-play"></b>Change Password</a></li>
      </ul>
  </div>
  <?php } ?>
  
 

            
        
        <!-- /.navbar-collapse --> 
        <?php if((isset($session['ADMIN_ID']) && !empty($session['ADMIN_ID']))){ ?>
        <div class="admin-welcome-msg"> Welcome! <span><?php echo isset($session['ADMIN_NAME']) ? $session['ADMIN_NAME'] : ''; ?></span> <a class="btn btn-warning" href="<?php echo base_url('index.php/admin/logout'); ?>">logout</a></div>
        <?php } ?>
      </div>
      
      <!-- /.container-fluid --> 
      
    </nav>
  </div>
</div>
<div class="main-content martp60">
<div class="container">
<?php $success = $this->session->flashdata('success') ?>
<?php $error = $this->session->flashdata('error') ?>
<?php if(!empty($success)){ ?>
<div class="success">
  <?=$success;?>
</div>
<?php } ?>
<?php if(!empty($error)){ ?>
<div class="error">
  <?=$error;?>
</div>
<?php } ?>

<?php $controller = $this->router->fetch_class(); ?>
<?php $method = $this->router->fetch_method(); ?>
<?php if($controller == 'adminorders' && ($method == 'createorder')){ ?>

<div class="col-md-2 col-sm-3 backend_style" id="sidebar-floatdiv" style="position: relative;"  >
<?php }else{ ?>
<div class="col-md-2 col-sm-3 csm-respom-mr0 backend_style" id="sidebar-floatdiv" style="position: relative;"  >
</div>
<div class="col-md-2 col-sm-3 backend_style"  id="floatDiv" style="position: absolute;top:60px; width: auto; z-index:1000;">
<?php } ?>

 <?php if((isset($session['ADMIN_ID']) && !empty($session['ADMIN_ID']))){ ?>
  <div id="MainMenu"> 
     <div class="list-group panel panel_group">
   
      
      <a href="<?php echo base_url('index.php/admin/dashboard'); ?>" class="list-group-item1 list-group-item-success1<?php if($controller == 'admin' && $method == 'dashboard'){ ?> active<?php } ?>"><b class="glyphicon glyphicon-play"></b>Dashboard</a>
      
      <?php /*?><a href="#demo2" class="list-group-item1 list-group-item-success1" data-toggle="collapse" data-parent="#MainMenu"><b class="glyphicon glyphicon-play"></b>Orders</a>
      
      <div class="collapse<?php if($controller == 'adminorders'){ ?> in<?php } ?>" id="demo2">
      <a href="<?php echo base_url('index.php/adminorders/orders'); ?>" class="list-group-item2<?php if($controller == 'adminorders' && ($method == 'orders')){ ?> active<?php } ?>"><b class="glyphicon glyphicon-play"></b>View Current Orders</a>
      <a href="<?php echo base_url('index.php/adminorders/createorder'); ?>" class="list-group-item2<?php if($controller == 'adminorders' && ($method == 'createorder')){ ?> active<?php } ?>"><b class="glyphicon glyphicon-play"></b>Create New Order</a>
      </div><?php */?>
      
      <?php if(!($controller == 'adminorders' && ($method == 'createorder'))){ ?>
      
      <a href="<?php echo base_url('index.php/adminorders/orders'); ?>" class="list-group-item1 list-group-item-success1<?php if($controller == 'adminorders' && ($method == 'orders')){ ?> active<?php } ?>"><b class="glyphicon glyphicon-play"></b>Orders List</a>
      
      <a href="<?php echo base_url('index.php/adminorders/orderqueue'); ?>" class="list-group-item1 list-group-item-success1<?php if($controller == 'adminorders' && ($method == 'orderqueue' || $method == 'addorderqueue')){  ?> active<?php } ?>"><b class="glyphicon glyphicon-play"></b>Orders Queue List</a>
      
      <a href="<?php echo base_url('index.php/adminorders/counterdetails'); ?>" class="list-group-item1 list-group-item-success1<?php if($controller == 'adminorders' && ($method == 'counterdetails')){  ?> active<?php } ?>"><b class="glyphicon glyphicon-play"></b>Counters</a>
      
      <a href="<?php echo base_url('index.php/adminorders/createorder'); ?>" class="list-group-item1 list-group-item-success1<?php if($controller == 'adminorders' && ($method == 'createorder')){ ?> active<?php } ?>"><b class="glyphicon glyphicon-play"></b>Create New Order</a>
      
      <?php if($session['LOGIN_TYPE'] == 'EMPLOYEE'){?>
      <a href="<?php echo base_url('index.php/adminpatients/addpatient'); ?>" class="list-group-item1 list-group-item-success1<?php if($controller == 'adminpatients' ){ ?> active<?php } ?>"><b class="glyphicon glyphicon-play"></b>Add Patient</a>
      <?php }?>
      <?php if($session['LOGIN_TYPE'] == 'PARTNER'){?>
      <a href="<?php echo base_url('index.php/admin/employeedetails'); ?>" class="list-group-item1 list-group-item-success1<?php if($controller == 'admin' && ($method == 'employeedetails' || $method == 'addemployee')){ ?> active<?php } ?>"><b class="glyphicon glyphicon-play"></b>Employee List</a>
      <?php }?>
	  <?php if(($session['LOGIN_TYPE'] != 'EMPLOYEE') ){ ?>
      <?php if(($session['LOGIN_TYPE'] == 'ADMIN') ){ ?>
      <a href="<?php echo base_url('index.php/admin/partnerdetails'); ?>" class="list-group-item1 list-group-item-success1<?php if($controller == 'admin' && ($method == 'partnerdetails' || $method == 'addpartner')){ ?> active<?php } ?>"><b class="glyphicon glyphicon-play"></b>Partner List</a>
      
      <a href="<?php echo base_url('index.php/admin/employeedetails'); ?>" class="list-group-item1 list-group-item-success1<?php if($controller == 'admin' && ($method == 'employeedetails' || $method == 'addemployee')){ ?> active<?php } ?>"><b class="glyphicon glyphicon-play"></b>Employee List</a>
      <?php } ?>
      
      
             <?php if($session['LOGIN_TYPE']!='EMPLOYEE'){?>
      <a href="<?php echo base_url('index.php/adminpatients/patients'); ?>" class="list-group-item1 list-group-item-success1<?php if($controller == 'adminpatients'){ ?> active<?php } ?>"><b class="glyphicon glyphicon-play"></b>Patient List</a>
    <?php } else { ?>
      <a href="<?php echo base_url('index.php/adminpatients/addpatient'); ?>" class="list-group-item1 list-group-item-success1<?php if($controller == 'adminpatients'){ ?> active<?php } ?>"><b class="glyphicon glyphicon-play"></b>Add Patient</a>
      <?php } ?>
      
      
      <a href="#demo4" class="list-group-item1 list-group-item-success1" data-toggle="collapse" data-parent="#MainMenu"><b class="glyphicon glyphicon-play"></b>Categories</a>
      
      <div class="collapse<?php if($controller == 'admincategories'){ ?> in<?php } ?>" id="demo4">
      <a href="<?php echo base_url('index.php/admincategories/maincategories'); ?>" class="list-group-item2<?php if($controller == 'admincategories' && ($method == 'maincategories' || $method == 'addcategory')){ ?> active<?php } ?>"><b class="glyphicon glyphicon-play"></b>Categories</a>
      <a href="<?php echo base_url('index.php/admincategories/subcategories'); ?>" class="list-group-item2<?php if($controller == 'admincategories' && ($method == 'subcategories'|| $method == 'addsubcategory')){ ?> active<?php } ?>"><b class="glyphicon glyphicon-play"></b>Products</a>
      </div>
      <?php if($session['LOGIN_TYPE'] != 'EMPLOYEE'){ ?>
      <a href="<?php echo base_url('index.php/adminproducts/products'); ?>" class="list-group-item1 list-group-item-success1<?php if($controller == 'adminproducts'){ ?> active<?php } ?>"><b class="glyphicon glyphicon-play"></b>Product Pricing</a>
      <?php } ?>
      <a href="<?php echo base_url('index.php/admininventory/inventorydetails'); ?>" class="list-group-item1 list-group-item-success1<?php if($controller == 'admininventory'){ ?> active<?php } ?>"><b class="glyphicon glyphicon-play"></b>Inventory</a>
      <a href="<?php echo base_url('index.php/adminempinventory/inventoryempdetails'); ?>" class="list-group-item1 list-group-item-success1<?php if($controller == 'adminempinventory'){ ?> active<?php } ?>"><b class="glyphicon glyphicon-play"></b>Employee Inventory</a>
  <?php }if($session['LOGIN_TYPE']=='ADMIN'){?>
  	<a href="<?php echo base_url('index.php/admin/ipdetails'); ?>" class="list-group-item1 list-group-item-success1<?php if($controller == 'admin' && $method == 'ipdetails'){ ?> active<?php } ?>"><b class="glyphicon glyphicon-play"></b>Settings</a>
    
    <a href="<?php echo base_url('index.php/admin/getitemcode'); ?>" class="list-group-item1 list-group-item-success1<?php if($controller == 'admin' && $method == 'getitemcode'){ ?> active<?php } ?>"><b class="glyphicon glyphicon-play"></b>Item QBCODE</a>
    
    
    <a href="<?php echo base_url('index.php/admin/bannerdetails'); ?>" class="list-group-item1 list-group-item-success1<?php if($controller == 'admin' && ($method == 'bannerdetails' || $method == 'addbanner')){ ?> active<?php } ?>"><b class="glyphicon glyphicon-play"></b>Banner</a>
  	<a href="<?php echo base_url('index.php/admin/indexText'); ?>" class="list-group-item1 list-group-item-success1<?php if($controller == 'admin' && $method == 'indexText'){ ?> active<?php } ?>"><b class="glyphicon glyphicon-play"></b>Home Content</a>
    <?php }?>
    <a href="<?php echo base_url('index.php/admin/changepwd'); ?>" class="list-group-item1 list-group-item-success1<?php if($controller == 'admin' && $method == 'changepwd'){ ?> active<?php } ?>"><b class="glyphicon glyphicon-play"></b>Change Password</a>
    
    <?php } ?>
  </div>
  </div>
  <?php } ?> 



<?php if((isset($session['ADMIN_ID']) && !empty($session['ADMIN_ID']))){ ?>
<?php $controller = $this->router->fetch_class(); ?>
<?php $method = $this->router->fetch_method(); ?>

<?php if($controller == 'adminorders' && ($method == 'createorder')){ ?>


<div class="col-md-12 cart-sdwps" id="floatDiv" style="position: absolute;top:550px;    left: 0;     width: auto; z-index:1000;">
<h2 class="htlte-org"><i class="fa fa-shopping-cart"></i> Cart</h2><div class="col-md-12 cr-sdcontent"><div id="add_cart_products"> 
 <?php $this->load->view('admin/orders/cart'); ?>
</div></div></div>


<script type="text/javascript">
var checking = 1;
var intex=1;
var minheight="";
function text1(){
	if(document.body.clientWidth>768) {
		if(minheight!="")
			$('#sidebar-floatdiv').css('min-height',minheight);
		minheight=$('#sidebar-floatdiv').css('min-height');
		intex=2;
		var ele=document.getElementById('CartDivmob');
		if(ele!=null)
			ele.setAttribute('id','floatDiv');
		$('#floatDiv').attr('style','position: absolute;top:50px;    left: 0;     width: auto; z-index:1000;');
		$(window).scroll(function () {
		 var minlength=0;
		 var  toppos = ($(document).scrollTop()-minlength);
		 var maxhh=document.getElementById('footer').offsetTop-document.getElementById('floatDiv').offsetHeight - 100;
		 set = ($(document).scrollTop()-minlength)+"px";
		if(toppos <50)
		 {
		  set="50px"; 
		 }
		
		 if(maxhh>toppos)
		  $('#floatDiv').animate({top:set},{duration:1000,queue:false});
		  
		});
		$(document).ready(function(){
		  var width = $('#floatDiv').outerHeight(true) + 253;
		  $('#sidebar-floatdiv').css('min-height', width+'px');
		  $( "#add_cart_products" ).css('display','block');
		  $( "#carth2" ).off('click');
		});
		
	} else {
		$('#sidebar-floatdiv').css('min-height',0);
		if( $( "#add_cart_products" ).css('display')!='none') {
			 $( "#add_cart_products" ).css('display','block');
		} else {
			$('#add_cart_products').hide();
		}
		if(intex==1)
			$('#add_cart_products').hide();
		intex=2;
		
		if($("#floatDiv").length != 0) {
						
			var ele=document.getElementById('floatDiv');
			ele.setAttribute('id','CartDivmob');
			ele.removeAttribute('style');
			ele.style.top='0px';
		}
		if(checking == 1)
		{
			checking++;
			$('#CartDivmob h2').click(function(e) { 
			
				/*if($("#add_cart_products").is(":visible"))
					$('#add_cart_products').hide();
				else
					$( "#add_cart_products" ).css('display','block');*/
					
				 $( "#add_cart_products" ).toggle( "slow", function() { });
			});
		}
	}
}


/*$(window).scroll(function(){
	var minlength=0;
	var  toppos = ($(document).scrollTop()-minlength);
	var maxhh=document.getElementById('footer').offsetTop-document.getElementById('floatDiv').offsetHeight - 100;
	set = ($(document).scrollTop()-minlength)+"px";
	if(toppos <550)
	{
		set="550px"; 
	}

	if(maxhh>toppos)
		$('#floatDiv').animate({top:set},{duration:1000,queue:false});  
});*/
$(document).ready(function(){
  var width = $('#floatDiv').outerHeight(true) + 550;
  $('#sidebar-floatdiv').css('', width+'px');
  
   //$('#CartDivmob h2').on('click', function(e) { 
		
			/*if($("#add_cart_products").is(":visible"))
				$('#add_cart_products').hide();
			else
				$( "#add_cart_products" ).css('display','block');*/
				
			//$( "#add_cart_products" ).toggle( "slow", function() { });
		//});
		
});
$(document).ready(function(e) {
    text1();
});
$( window ).resize(function() {
	text1();
});

</script>



<?php }else{ ?> 


<script type="text/javascript">
var checking = 1;
var intex=1;
var minheight="";
function text1(){
	if(document.body.clientWidth>768) {
		if(minheight!="")
			$('#sidebar-floatdiv').css('min-height',minheight);
		minheight=$('#sidebar-floatdiv').css('min-height');
		intex=2;
		var ele=document.getElementById('CartDivmob');
		if(ele!=null)
			ele.setAttribute('id','floatDiv');
		$('#floatDiv').attr('style','position: absolute;top:60px; width: auto; z-index:1000;');
		$(window).scroll(function () {
		 var minlength=0;
		 var  toppos = ($(document).scrollTop()-minlength);
		 var maxhh=document.getElementById('footer').offsetTop-document.getElementById('floatDiv').offsetHeight - 100;
		 set = ($(document).scrollTop()-minlength)+"px";
		if(toppos <60)
		 {
		  set="60px"; 
		 }
		
		 if(maxhh>toppos)
		  $('#floatDiv').animate({top:set},{duration:1000,queue:false});
		  
		});
		$(document).ready(function(){
		  var width = $('#floatDiv').outerHeight(true) + 253;
		  $('#sidebar-floatdiv').css('min-height', width+'px');
		  $( "#add_cart_products" ).css('display','block');
		  $( "#carth2" ).off('click');
		});
		
	} else {
		$('#sidebar-floatdiv').css('min-height',0);
		if( $( "#add_cart_products" ).css('display')!='none') {
			 $( "#add_cart_products" ).css('display','block');
		} else {
			$('#add_cart_products').hide();
		}
		if(intex==1)
			$('#add_cart_products').hide();
		intex=2;
		
		if($("#floatDiv").length != 0) {
						
			var ele=document.getElementById('floatDiv');
			ele.setAttribute('id','CartDivmob');
			ele.removeAttribute('style');
			ele.style.top='0px';
		}
		if(checking == 1)
		{
			checking++;
			$('#CartDivmob h2').click(function(e) { 
			
				/*if($("#add_cart_products").is(":visible"))
					$('#add_cart_products').hide();
				else
					$( "#add_cart_products" ).css('display','block');*/
					
				 $( "#add_cart_products" ).toggle( "slow", function() { });
			});
		}
	}
}


/*$(window).scroll(function(){
	var minlength=0;
	var  toppos = ($(document).scrollTop()-minlength);
	var maxhh=document.getElementById('footer').offsetTop-document.getElementById('floatDiv').offsetHeight - 100;
	set = ($(document).scrollTop()-minlength)+"px";
	if(toppos <550)
	{
		set="550px"; 
	}

	if(maxhh>toppos)
		$('#floatDiv').animate({top:set},{duration:1000,queue:false});  
});*/
$(document).ready(function(){
  var width = $('#floatDiv').outerHeight(true) + 550;
  $('#sidebar-floatdiv').css('', width+'px');
  
   //$('#CartDivmob h2').on('click', function(e) { 
		
			/*if($("#add_cart_products").is(":visible"))
				$('#add_cart_products').hide();
			else
				$( "#add_cart_products" ).css('display','block');*/
				
			//$( "#add_cart_products" ).toggle( "slow", function() { });
		//});
		
});
$(document).ready(function(e) {
    text1();
});
$( window ).resize(function() {
	text1();
});

</script>

<?php } ?>

<?php } ?> 
<div>

</div>

    
      
</div>

<script>
  // $(function () { $("[data-toggle='tooltip']").tooltip(); });
</script>
<div class="col-md-10 col-sm-9 main_backup">
