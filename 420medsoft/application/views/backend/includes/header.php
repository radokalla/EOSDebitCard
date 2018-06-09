<!DOCTYPE html>

<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>420MedSoft</title>

<link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url("images/favicon.ico");?>">

<link href="<?php echo base_url("css/backend.css");?>" rel="stylesheet">

<!-- Bootstrap -->

<link href="<?php echo base_url("css/bootstrap.min.css");?>" rel="stylesheet">
<link href="<?php echo base_url("css/bootstrap.css");?>" rel="stylesheet">
<!--<link href="<?php //echo base_url("css/colorpicker.css");?>" rel="stylesheet">-->

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
<script src="<?php echo base_url("js/jscolor.js");?>"></script>
<!--<script src="<?php echo base_url("js/colorpicker.js");?>"></script>-->
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
        
        
         <?php if((isset($session['BACKEND_ADMIN_ID']) && !empty($session['BACKEND_ADMIN_ID'])) ){ ?>
<?php $controller = $this->router->fetch_class(); ?>
<?php $method = $this->router->fetch_method(); ?>
  <div class="collapse navbar-collapse" id="menubar">

          <ul class="nav navbar-nav top_menubar">
      
      <li class="<?php if($controller == 'backend' && $method == 'dashboard'){ ?> active<?php } ?>"><a href="<?php echo base_url('index.php/backend/dashboard'); ?>" class="list-group-item1 list-group-item-success1"><b class="glyphicon glyphicon-play"></b>Dashboard</a></li>
      
      <li class="<?php if($controller == 'backend' && $method == 'subscriberdetails'){ ?> active<?php } ?>"><a href="<?php echo base_url('index.php/backend/subscriberdetails'); ?>" class="list-group-item1 list-group-item-success1"><b class="glyphicon glyphicon-play"></b>Subscribers</a></li>
      
      <li class="<?php if($controller == 'backend' && $method == 'packagedetails'){ ?> active<?php } ?>"><a href="<?php echo base_url('index.php/backend/packagedetails'); ?>" class="list-group-item1 list-group-item-success1"><b class="glyphicon glyphicon-play"></b>Package</a></li>
     
      </ul>
  </div>
  <?php } ?>
  
 

            
        
        <!-- /.navbar-collapse --> 
        <?php if((isset($session['BACKEND_ADMIN_ID']) && !empty($session['BACKEND_ADMIN_ID']))){ ?>
        <div class="admin-welcome-msg"> Welcome! <span><?php echo isset($session['ADMIN_NAME']) ? $session['ADMIN_NAME'] : ''; ?></span> <a class="btn btn-warning" href="<?php echo base_url('index.php/backend/logout'); ?>">logout</a></div>
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

<div class="col-md-2 backend_style" id="sidebar-floatdiv" style="position: relative;">
 <?php if((isset($session['BACKEND_ADMIN_ID']) && !empty($session['BACKEND_ADMIN_ID']))){ ?>
<?php $controller = $this->router->fetch_class(); ?>
<?php $method = $this->router->fetch_method(); ?>
  <div id="MainMenu"> 
     <div class="list-group panel panel_group">
   
      
      <a href="<?php echo base_url('index.php/backend/dashboard'); ?>" class="list-group-item1 list-group-item-success1<?php if($controller == 'backend' && $method == 'dashboard'){ ?> active<?php } ?>"><b class="glyphicon glyphicon-play"></b>Dashboard</a>
      
      <a href="<?php echo base_url('index.php/backend/subscriberdetails'); ?>" class="list-group-item1 list-group-item-success1<?php if($controller == 'backend' && ($method == 'subscriberdetails' || $method == 'subscriberdetails1')){ ?> active<?php } ?>"><b class="glyphicon glyphicon-play"></b>Subscribers</a>
      
      <a href="<?php echo base_url('index.php/backend/packagedetails'); ?>" class="list-group-item1 list-group-item-success1<?php if($controller == 'backend' && $method == 'packagedetails'){ ?> active<?php } ?>"><b class="glyphicon glyphicon-play"></b>Package</a>
<?php /*?>	  <a href="<?php echo base_url('index.php/backend/indexText'); ?>" class="list-group-item1 list-group-item-success1<?php if($controller == 'backend' && $method == 'indexText'){ ?> active<?php } ?>"><b class="glyphicon glyphicon-play"></b>Home Content</a><?php */?>
<?php /*?>	  <a href="<?php echo base_url('index.php/backend/chlogo'); ?>" class="list-group-item1 list-group-item-success1<?php if($controller == 'backend' && $method == 'chlogo'){ ?> active<?php } ?>"><b class="glyphicon glyphicon-play"></b>Header Logo</a>
	  <a href="<?php echo base_url('index.php/backend/footerlogo'); ?>" class="list-group-item1 list-group-item-success1<?php if($controller == 'backend' && $method == 'chlogo'){ ?> active<?php } ?>"><b class="glyphicon glyphicon-play"></b>Footer Logo</a><?php */?>
        <a href="<?php echo base_url('index.php/backend/salesreport'); ?>" class="list-group-item1 list-group-item-success1<?php if($controller == 'backend' && $method == 'salesreport'){ ?> active<?php } ?>"><b class="glyphicon glyphicon-play"></b>Sales Report</a>
    
  </div>
  </div>
  <?php } ?> 



<?php if((isset($session['BACKEND_ADMIN_ID']) && !empty($session['BACKEND_ADMIN_ID']))){ ?>
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
		$('#floatDiv').attr('style','position: absolute;top:255px;    left: 0;     width: auto; z-index:1000;');
		$(window).scroll(function () {
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
<div class="col-md-10 main_backup">
