<script src="<?php echo base_url('js/jquery.printElement.js');?>" type="text/javascript"> </script>
<script src="http://labelwriter.com/software/dls/sdk/js/DYMO.Label.Framework.latest.js" type="text/javascript" charset="UTF-8"> </script>
<script type="text/javascript">

function updateOrderStatus(orderID, varthis)
{
	var status = $(varthis).val();
	var dataString = "orderID="+orderID+"&status="+status;
	$.ajax({
		type: "POST",
		url: '<?php echo base_url('index.php/adminorders/updateOrderStaus'); ?>',
		data: dataString,
		success: function (data) {
		}
	});
}


function getOrderDetails(orderID,onfleet)
{
	$("#myModal .modal-body").html('');
  	var dataString = "orderID="+orderID+"&onfleet="+onfleet;
	$.ajax({
		type: "POST",
		url: '<?php echo base_url('index.php/adminorders/getOrderDetails'); ?>',
		data: dataString,
		success: function (data) {
			$("#myModal .modal-body").html(data);
		}
	});
}

function PrintShippingLabel(orderID)
{
	
	//if(confirm("Are you sure you want to print large shipping label # 1 ?"))
	//{
		var patientName = $("#patientName-"+orderID).val();
		var doctorName = $("#doctorName-"+orderID).val();
		var patientDetails = $("#patientDetails-"+orderID).val();
		var phone = $("#phone-"+orderID).val();
		var userName = $("#userName-"+orderID).val();
		var created = $("#created-"+orderID).val();
		var orderType = $("#orderType-"+orderID).val();
		var prescription1_str = $("#prescription1_str-"+orderID).val();
		var prescription2_str = $("#prescription2_str-"+orderID).val();
		var total_str = $("#total_str-"+orderID).val();
		
	
		//$.get('http://bayfrontorganics.com/labels/order-sample-label.label', function(labelXml)
		$.get('<?php echo base_url('labels/MMCC_Complianace_New.label');?>', function(labelXml)
		{
			var label = dymo.label.framework.openLabelXml(labelXml);  
			console.log(label);
			
			label.setObjectText("DATE-TIME", "619 552-1246");
			//label.setObjectText("DATE-TIME", created);
			label.setObjectText("Patient_name", patientName);
			//var myURI = label.framework.loadImageAsPngBase64("<?php echo base_url('labels/label_logo.png'); ?>");
			//label.setObjectText("logo",myURI);
			//label.setObjectText("ADDRESS", ''+orderType+'\n'+patientName+'\n'+patientDetails+'\n'+phone+'\nMedical ID:'+userName+'\Doctor Name:'+doctorName+'');
			label.setObjectText("Prescription-1", prescription1_str+'\n Total Donation : $'+total_str);
			//label.setObjectText("Prescription-2", prescription2_str);
			var printers = dymo.label.framework.getPrinters();
			if (printers.length == 0)
			{
				alert( "No DYMO printers are installed. Install DYMO printers.");
				return;
			}
	
			var printerName = "";
			for (var i = 0; i < printers.length; ++i)
			{
				var printer = printers[i];
				if (printer.printerType == "LabelWriterPrinter")
				{
					printerName = printer.name;
					break;
				}
			}
			
			<?php if($deviceType == 'computer'){ ?>		
				
			if (printerName == "")
			{
				  alert("No LabelWriter printers found. Install LabelWriter printer");
				  return false;
				  }
			 console.log("1");
			var params = dymo.label.framework.createLabelWriterPrintParamsXml({twinTurboRoll: dymo.label.framework.TwinTurboRoll.Right});
			console.log(params);
			label.print(printerName,params);
			
			<?php }else{ ?>
			console.log("2");
			if (!label)
				return;
				console.log("3");
			var pngData = label.render();		
			var labelImage = document.getElementById('labelAddressImage');
			labelImage.src = "data:image/png;base64," + pngData;
			$('img#labelAddressImage').printElement();
			<?php } ?> 
		});
	//}
}

</script><div class="memberlogin-wps col-md-12 products_page">
  <h2>Orders List</h2>
  <?php /*?><a href="<?php echo base_url('index.php/adminpatients/addpatient'); ?>">Add Order</a><?php */?>
  <?php if($ordersDetails){ ?>
  <form method="post" action="<?php echo base_url('index.php/adminorders/orders'); ?>">
    <table class="table table-hover table-striped table_hd">
      <thead class="table_heading">
        <tr>
        
          <th>Patient Name:</th>
          <th width="10%">Delivery type</th>
          <th width="10%">Status:</th>
          <th>Order By:</th>
          <th>From Date:</th>
           <th>To Date:</th> 
          <th> </th>
        </tr>
      </thead>
      <tbody>
        <tr>
            <td><input class="text_input2" type="text" name="patientName" value="" size="10" placeholder="Patient Name" ></td>
          <td><select class="text_input2" name="deliveryType">
              <option value=""> Select </option>
              <option value="delivery">Delivery</option>
              <option value="pickup">Pick up</option>
              <option value="creditcard">Credit card</option>
              <option value="cash">Cash</option>
            </select></td>
          <td><select class="text_input2" name="orderStatus" >
              <option value=""> Select </option>
              <?php foreach($ordersStatus as $orderStatusId => $orderStaus){ ?>
              <option value="<?php echo $orderStatusId; ?>"><?php echo $orderStaus; ?></option>
              <?php } ?>
            </select></td>
          <td><input class="text_input2" type="text" name="orderBy" value="" size="10" placeholder="Order By" ></td>
          <td colspan="2"><!--<input class="text_input2 datepicker" type="text" value="<?php echo date("Y-m-d")?>" name="from_orderDate" size="8" placeholder="YYYY-MM-DD">-->
          <div class="input-group input-daterange">
    <input type="text" class="form-control" value="<?php echo date("Y-m-d")?>" name="from_orderDate"  size="8">
    <div class="input-group-addon">to</div>
    <input type="text" class="form-control" value="<?php echo date("Y-m-d")?>" name="to_orderDate"  size="8">
</div>
          
          
          
          
          
          </td>
           <!--<td><input class="text_input2 datepicker" type="text" value="<?php echo date("Y-m-d")?>" name="to_orderDate" size="8" placeholder="YYYY-MM-DD"></td>-->
          <td><input type="hidden" name="recordPerPage" value="<?php echo isset($recordsperpage) ? $recordsperpage : 10; ?>">
            <input type="submit" name="search" value="Search" class="btn btn-success"></td>
        </tr>
      </tbody>
    </table>
  </form>
  <form name="view_order" method="post" action="<?php echo base_url('index.php/adminorders/orders'); ?>">
    <div class="col-md-10"></div>
    <div class="col-md-2 show_class">
      <select class="text_input4 cellform" name="recordPerPage" onchange="view_order.submit()">
        <option value="10" <?php echo ($recordsperpage == 10) ? ' selected="selected"' : ''; ?>>10 Records</option>
        <option value="25" <?php echo ($recordsperpage == 25) ? ' selected="selected"' : ''; ?>>25 Records</option>
        <option value="50" <?php echo ($recordsperpage == 50) ? ' selected="selected"' : ''; ?>>50 Records</option>
        <option value="100" <?php echo ($recordsperpage == 100) ? ' selected="selected"' : ''; ?>>100 Records</option>
        <option value="250" <?php echo ($recordsperpage == 250) ? ' selected="selected"' : ''; ?>>250 Records</option>
        <option value="500" <?php echo ($recordsperpage == 500) ? ' selected="selected"' : ''; ?>>500 Records</option>
        <option value="1000" <?php echo ($recordsperpage == 1000) ? ' selected="selected"' : ''; ?>>1000 Records</option>
        <option value="5000" <?php echo ($recordsperpage == 5000) ? ' selected="selected"' : ''; ?>>5000 Records</option>
        <option value="10000" <?php echo ($recordsperpage == 10000) ? ' selected="selected"' : ''; ?>>10000 Records</option>
      </select>
    </div>
  </form>
  
 <?php /*?> <img id="labelAddressImage" src="" /><?php */?>
  
  <table class="table table-hover table-striped table_hd">
    <thead class="table_heading">
      <tr> 
        <!--<th>Order Id</th>-->
        <th>Dymo</th>
        <th>Patient Name</th>
        <th>Sub Total</th>
        <th>Charge</th>
        <th>Sales Tax</th>
        <th>Total</th>
        <th>Order Date</th>
        <th>Order By</th>
        <th width="15%">Delivery type</th>
        <!--   <th>Order Type</th>-->
        <th width="13%">Status</th>
        <th width="8%">Action</th>
      </tr>
    </thead>
    <tbody>
      <?php $label_total=0; $total = 0;  $deliveryCharge = 0;  $tax_amount = 0; $sno = 0; foreach($ordersDetails as $orderID => $orderDetails){ ?>
      <?php //echo "<pre>"; print_r($orderDetails); exit; ?>
      
      <?php 
	  
	  
	$orderType  = $orderDetails['deliveryType'] == 'pickup' ? 'Pick up' : 'Delivery';
	$orderType .= ', ';
	$orderType .= $orderDetails['paymentType'] == 'careditcard' ? 'Credit card' : 'Cash';
	
	
	  $prescription1=array(); $prescription2=array(); $count = 1;
	  foreach($orderDetails['productdetailsconcat'] as $productdetail)
	  {  
		 
		  if($count<=9)
		  {
			  $prescription1[] = $productdetail;
		  }
		  else if($count<=18)
		  {
			  $prescription2[] = $productdetail;
		  }
		  $count++;
	  }
	  
	  $prescription1_str = implode("\n",$prescription1);
	  $prescription2_str = implode("\n",$prescription2);
	  ?>
      
      
      
      <tr> 
        <!--<td class="aligncenter"><?php echo $orderID;?></td>-->
        <td><a class="number_design" onclick="PrintShippingLabel('<?php echo $orderID ?>')" >1</a></td>
        <td><?php echo $orderDetails['patientName'];
		//$date=date('Y-m-d H:i:s', strtotime('-9 hour'));
		$date=date('Y-m-d H:i:s');
 		$printdate= date('l, F d, Y h:i A', strtotime($date));
		//$patient_address = 	 $orderDetails['patient_address']; 
			
			$patient_address = 	 ""; 
			?>
        
        <input type="hidden" id="patientName-<?php echo $orderID; ?>" value="<?php echo $orderDetails['patientName'] ?>" />
        <input type="hidden" id="doctorName-<?php echo $orderID; ?>" value="<?php echo $orderDetails['doctorName'] ?>" />
        <input type="hidden" id="patientDetails-<?php echo $orderID; ?>" value="<?php echo $orderDetails['patientDetails'] ?>" />
        <input type="hidden" id="phone-<?php echo $orderID; ?>" value="<?php echo $this->session->TelephoneNumberFormat($orderDetails['phone']); ?>" />
        <input type="hidden" id="userName-<?php echo $orderID; ?>" value="<?php echo $orderDetails['userName'] ?>" />
        <input type="hidden" id="created-<?php echo $orderID; ?>" value="<?php echo $printdate; ?>" />
        <input type="hidden" id="orderType-<?php echo $orderID; ?>" value="<?php echo $orderType ?>" />
        <input type="hidden" id="prescription1_str-<?php echo $orderID; ?>" value="<?php echo $prescription1_str ?>" />
        <input type="hidden" id="prescription2_str-<?php echo $orderID; ?>" value="<?php echo $prescription2_str ?>" />
        <input type="hidden" id="address_str-<?php echo $orderID; ?>" value="<?php echo $patient_address ?>" />
        </td>
        <td class="alignright"><?php echo $currency['symbol']; ?><?php echo ($orderDetails['totalPrice'] - $orderDetails['deliveryCharge'] - $orderDetails['tax_amount']); ?></td>
        <td class="alignright"><?php echo $currency['symbol']; ?><?php echo $orderDetails['deliveryCharge']; ?></td>
        <td class="alignright"><?php echo $currency['symbol']; ?><?php echo $orderDetails['tax_amount']; ?></td>
        <td class="alignright"><?php echo $currency['symbol']; ?><?php echo $orderDetails['totalPrice']; ?></td>
        <td class="aligncenter"><?php echo date("m/d/Y H:i:s", strtotime($orderDetails['created'])); ?></td>
        <td class="aligncenter"><?php echo ($orderDetails['createdBy']); ?></td>
        <?php /*?> <td class="aligncenter"><?php echo ($orderDetails['createdType']); ?></td>        <?php */?>
        <td><?php echo isset($orderDetails['deliveryType']) && ($orderDetails['deliveryType'] == 'pickup') ? 'Pick up' : 'Delivery'; ?> - <?php echo isset($orderDetails['paymentType']) && ($orderDetails['paymentType'] == 'cash') ? 'Cash' : 'CC-Paid'; ?></td>
        <td><select class="text_input2" onchange="updateOrderStatus('<?php echo $orderID;?>', this)">
            <?php foreach($ordersStatus as $orderStatusId => $orderStaus){ if(($orderDetails['status'] == 5 && $orderStatusId != 5) || ($orderDetails['status'] != 5 && $orderStatusId == 5)){ continue;} ?>
            <option value="<?php echo $orderStatusId; ?>" <?php echo ($orderDetails['status'] == $orderStatusId) ? ' selected="selected"' : ''; ?>><?php echo $orderStaus; ?></option>
            <?php } ?>
          </select></td>
        <td class="aligncenter"><a class="glyphicon glyphicon-file view_button" onClick="getOrderDetails('<?php echo $orderID;?>',0)" data-toggle="modal" data-target="#myModal" <?php /*?>href="<?php echo base_url('index.php/adminorders/details/'.$orderID); ?>"  data-toggle="tooltip" data-placement="top"<?php */?> title="View"></a> &nbsp;&nbsp; <!--<a class="glyphicon glyphicon-pencil view_button" href="<?php //echo base_url('index.php/adminorders/details/'.$orderID); ?>"  data-toggle="tooltip" data-placement="top" title="Edit"></a>-->
          <?php /*?><a class="glyphicon glyphicon-pencil view_button" onClick="getOrderDetails('<?php echo $orderID;?>')" data-toggle="modal" data-target="#myModal"></a><?php */?>
          <?php if($session['LOGIN_TYPE']=='ADMIN'){?>
          <a onclick="return confirm('Are you sure. Do you want to delete?');" class="glyphicon glyphicon-trash view_button" href="<?php echo base_url('index.php/adminorders/deleteorder/'.$orderID); ?>" data-toggle="tooltip" data-placement="top" title="Delete"></a> <?php }?>
          <a class="glyphicon glyphicon glyphicon-user" onClick="getOrderDetails('<?php echo $orderID;?>',1)" data-toggle="modal" data-target="#myModal" title="Assign"><!--<img src="<?php echo base_url('images/onfleet_1.png'); ?>" />--></a> 
          <?php if($orderDetails['status'] == 5){ ?>
          <a class="glyphicon glyphicon-pencil view_button" href="<?php echo base_url('index.php/adminorders/createorder/'.$orderID); ?>" data-toggle="tooltip" data-placement="top" title="Delete"></a>
          <?php }else{ ?>
          <a class="glyphicon glyphicon-pencil view_button" href="<?php echo base_url('index.php/adminorders/setorder/'.$orderID); ?>" data-toggle="tooltip" data-placement="top" title="Delete"></a>
          <?php } ?>
          
         </td>
      </tr>
      <?php 
	  $total += $orderDetails['totalPrice']; $tax_amount += $orderDetails['tax_amount']; $deliveryCharge += $orderDetails['deliveryCharge'];
	 
		$label_total = $orderDetails['totalPrice'];
	 
?>
<input type="hidden" id="total_str-<?php echo $orderID; ?>" value="<?php echo $label_total ?>" />
<?php 
} ?>
      <tr>
        <td colspan="11"></td>
      </tr>
      <tr>
        <td colspan="3">Total Subtotal</td>
        <td colspan="3" class="alignright"><?php echo $currency['symbol']; ?><?php echo $total - $tax_amount - $deliveryCharge;?></td>
        <td colspan="5" class="alignright"></td>
      </tr>
      <tr>
        <td colspan="3">Total Sales Tax</td>
        <td colspan="3" class="alignright"><?php echo $currency['symbol']; ?><?php echo $tax_amount;?></td>
        <td colspan="5" class="alignright"></td>
      </tr>
      <tr>
        <td colspan="3">Total Delivery Charge</td>
        <td colspan="3" class="alignright"><?php echo $currency['symbol']; ?><?php echo $deliveryCharge;?></td>
        <td colspan="5" class="alignright"></td>
      </tr>
      <tr>
        <td colspan="3">Total</td>
        <td colspan="3" class="alignright"><?php echo $currency['symbol']; ?><?php echo $total;?></td>
        <td colspan="5" class="alignright"></td>
      </tr>
    </tbody>
  </table>
  
  <div class="pagination">&nbsp;&nbsp;<?php echo $paginationLinks; ?></div>
  <?php }else{ ?>
  <div> No Orders found. </div>
  <?php } ?>
</div>

<div style="display:none;">
<img src="" id="labelAddressImage" />
</div>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Order Details</h4>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- Include Required Prerequisites -->
 
 
<!-- Include Date Range Picker -->
<script type="text/javascript" src="<?php echo  base_url('js/datepicker/js/bootstrap-datepicker.min.js');?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo  base_url('js/datepicker/css/daterangepicker.css');?>" />
 
 
<script type="text/javascript">
$(function() {
$('.input-daterange input').each(function() {
    $(this).datepicker({format: "yyyy-mm-dd"});
});
    
});
</script>