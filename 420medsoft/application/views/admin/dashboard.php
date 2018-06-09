<div class="memberlogin-wps col-md-12 products_page">
<h2>Dashboard<a class="category_add" target="_blank" href="<?php echo base_url('index.php/main/announcement'); ?>">Patient queue</a></h2>
  <p class="dashbaord_heading">
  Welcome 420 Medsoft <?php if($session['LOGIN_TYPE']=='ADMIN'){?>Master<?php }else if($session['LOGIN_TYPE']=='EMPLOYEE'){?>Employee<?php }else{?>Partner<?php }?>
  </p>
  <p class="date_dashboard">Today Date: <strong><?php 
 // echo date('l, F d, Y h:i A');echo "<br>";
//$date=date('Y-m-d H:i:s', strtotime('-8 hour'));
$date=date('Y-m-d H:i:s');
 echo date('l, F d, Y h:i A', strtotime($date)); 
  ?></strong></p>
  <?php //if($session['LOGIN_TYPE']=='ADMIN'){?>
  <p class="date_dashboard">New Patients: <strong>
  <a href="<?php echo base_url('index.php/adminpatients/patients1/1'); ?>"><?php echo $unreadPatients[0]['count(patientID)'];?></a>
  </strong></p>
  <?php //}?>
  <h3 class="order_dashboard">Order Status:</h3>
<div class="table_width item-wps"><?php $sno = 0; foreach($ordersStatus as $orderStatusId => $orderStaus){ ?>
    
 
 <div class="col-md-4 number_designss" onclick="gotoOrders('<?php echo $orderStatusId; ?>');" style="cursor:pointer;">     <div class="number_dashboard">  <?php echo $orderStaus; ?> </div>
       <div class="number_dashboard1">  <a class="number_designns" ><?php echo isset($ordersDetails[$orderStatusId]) ? $ordersDetails[$orderStatusId] : 0; ?></a></div></div>
    <?php } ?></div>
</div>
<div style="height:20px; width:100%;"></div>
<div style="display:none;">
<form method="post" id="admin_orders_form" action="<?php echo base_url('index.php/adminorders/orders');?>">
<input type="hidden" id="orderStatus" name="orderStatus" value="" />
</form>
</div>
<script type="text/javascript">
function gotoOrders(orderID)
{
	$("#orderStatus").val(orderID);
	$("#admin_orders_form").submit();
}
</script>