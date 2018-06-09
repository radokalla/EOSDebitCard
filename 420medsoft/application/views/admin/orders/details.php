<script>
function assignTask()
{
	var orderID=$('#orderID').val();
	var merchant=$('#merchant').val();
	var executor=$('#merchant').val();
	var worker=$('#driver option:selected').val();
	var workername=$('#driver option:selected').text();
	var recipient_name=$('#recipient_name').val();
	var recipient_address1=$('#recipient_address1').val();
	var recipient_address2=$('#recipient_address2').val();
	var recipient_phone=$('#recipient_phone').val();
	var recipient_city=$('#recipient_city').val();
	var recipient_zipcode=$('#recipient_zipcode').val();
	var product_note=$('#product_note').val();
	var Total=$('#Total').val();
	var Note=product_note+"Total:"+Total;
	
	 $.ajax({

					type: "POST",	
					url: '<?php echo base_url('index.php/adminorders/taskAssign'); ?>',		
					data: {'orderID':orderID,'merchant':merchant,'executor':executor,'workername':workername,'worker':worker,
							    'city':recipient_city,'postalCode':recipient_zipcode,'country':recipient_city,'street':recipient_address1,'address':recipient_address2, 'name':recipient_name,'phone':recipient_phone,'notes':Note
						  },
						  
			        dataType: "json",
					success: function(data) {
 									if(data==1) { var message =  "Successfully added task to worker" }else {
										 var message =  data;
									}
									alert(message);
									if(data==1)
									$('#myModal').modal('hide');
					}
						});
}
</script>
<div class="memberlogin-wps col-md-12 products_page">
<?php /*?><h2>Order Details</h2><?php */?>
  <div id="msg" style="display:none"></div>
    <?php if($ordersDetails){ ?>
   
     <?php
	 
	if(isset($onfleet['flag']))
	{
		//echo "<pre>";
		//print_r($onfleet['driverdetail']);
		?>
    Driver :<select name="driver" id="driver" class="text_input2">
<?php
foreach($onfleet['driverdetail']['workers'] as $result)
{ ?>
	<option value="<?php echo $result['id']."_". $onfleet['driverdetail']['id'];?>"><?php echo $result['name'];?></option>
<?php } ?>
</select>
    <input type="hidden" name="merchant" id="merchant" value="HseB35jw1tnV00oa3A~KIc2N">
    <input type="hidden" name="orderID" id="orderID" value="<?php echo $ordersDetails['orderID']?>">
    <input type="hidden" name="recipient_name" id="recipient_name" value="<?php echo $ordersDetails['patientDetails']['firstName'] . ' ' . $ordersDetails['patientDetails']['lastName']; ?>">
      <input type="hidden" name="recipient_address1" id="recipient_address1" value="<?php echo $ordersDetails['patientDetails']['address1']?>">
    <input type="hidden" name="recipient_address2" id="recipient_address2" value="<?php echo $ordersDetails['patientDetails']['address2']; ?>">
    
    <input type="hidden" name="recipient_phone" id="recipient_phone" value="<?php echo $this->session->TelephoneNumberFormat($ordersDetails['patientDetails']['phone']); ?>">
    
     <input type="hidden" name="recipient_city" id="recipient_city" value="<?php echo $ordersDetails['patientDetails']['city']; ?>">
     
     <input type="hidden" name="recipient_zipcode" id="recipient_zipcode" value="<?php echo $ordersDetails['patientDetails']['zip']; ?>">
     
     <input type="hidden" name="Total" id="Total" value="<?php echo $currency['symbol']; ?><?php echo $ordersDetails['totalPrice']; ?>">
     
    <?php $pro_name_tot ="";
	foreach($ordersDetails['productDetails'] as $orderID => $productDetails){ 
	$pro_name_tot .=$productDetails['subCategotyName']."_".$currency['symbol'].$productDetails['quantity']*$productDetails['productPrice'];
	//$pro_name_tot .=" ";
	}
	?>
 <input type="hidden" name="product_note" id="product_note" value="<?php echo $pro_name_tot;?>">
 
    <input type="button" value="Assign" onclick="assignTask();" class="btn btn-success"/>
    
    
    
    
    <?php }else {?>
   <table class="table table-hover table-striped table_hd">

     <thead class="table_heading">
        <tr>
          <th>S No</th>
          <th>Categoty Name</th>
          <th>Product Name</th>
          <th>Quantity</th>
          <th>Unit Donations</th>
          <th>Donations</th>
        </tr>
      </thead>
      <tbody>
        <?php $sno = 0; foreach($ordersDetails['productDetails'] as $orderID => $productDetails){ ?>
        <tr>
          <td class="aligncenter"><?php echo ++$sno; ?></td>
          <td><?php echo $productDetails['subCategotyName']; ?></td>
          <td><?php echo $productDetails['productName']; ?></td>
          <td class="aligncenter"><?php echo $productDetails['quantity']; ?></td>
          <td class="alignright"><?php echo $currency['symbol']; ?><?php echo ($productDetails['categotyID'] == 96) ? $productDetails['productPrice']*2 : $productDetails['productPrice']; ?></td>
          <td class="alignright"><?php echo $currency['symbol']; ?><?php echo $productDetails['quantity']*$productDetails['productPrice']; ?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
   
   <table class="table table-hover table-striped table_hd">
      <tr>
        <td>Patient Name</td>
        <td><?php echo $ordersDetails['patientDetails']['firstName'] . ' ' . $ordersDetails['patientDetails']['lastName']; ?></td>
        
      </tr>
      <tr>
        <td>Address</td>
        <td><?php echo $ordersDetails['patientDetails']['address1'] . ' ' . $ordersDetails['patientDetails']['address2']; ?></td>
      </tr>
      <tr>
        <td>City</td>
        <td><?php echo $ordersDetails['patientDetails']['city']; ?></td>
      </tr>
      <tr>
        <td>Zipcode</td>
        <td><?php echo $ordersDetails['patientDetails']['zip']; ?></td>
      </tr>
      <tr>
        <td>Phone number</td>
        <td><?php echo $this->session->TelephoneNumberFormat($ordersDetails['patientDetails']['phone']); ?></td>
      </tr>
      <tr>
        <td>Doctor Name</td>
        <td><?php echo $ordersDetails['patientDetails']['doctorName']; ?></td>
      </tr>
    </table>
    
    
    <table class="pull-right table table-condensed col-md-4 checkout-total">
      <tr>
        <td style="font-size:15px;"><strong>Invoice Number</strong></td>
        <td style="font-size:15px;"><strong><?php echo $ordersDetails['invoiceNumber']; ?></strong></td>
      </tr>
      
      <tr>
        <td style="font-size:15px;"><strong>Status</strong></td>
        <td style="font-size:15px;"><strong><?php echo $ordersStatus[$ordersDetails['status']]; ?></strong></td>
      </tr>
      <tr>
        <td style="font-size:15px;"><strong>Delivery Type</strong></td>
        <td style="font-size:15px;"><strong><?php echo ($ordersDetails['deliveryType'] == 'delivery') ? 'Delivery' : 'Pick-up'; ?></strong></td>
      </tr>
      <tr>
        <td style="font-size:15px;"><strong>Payment Type</strong></td>
        <td style="font-size:15px;"><strong><?php echo ($ordersDetails['paymentType'] == 'cash') ? 'Cash' : 'Credit card'; ?></strong></td>
      </tr>
      <tr>
        <td style="font-size:15px;"><strong>Sales tax</strong></td>
        <td style="font-size:15px;"><strong><?php echo $currency['symbol']; ?><?php echo $ordersDetails['tax_amount']; ?></strong></td>
      </tr>
      <?php if($ordersDetails['deliveryType'] == 'delivery'){ ?>
      <tr>
        <td style="font-size:15px;"><strong>Delivary charge</strong></td>
        <td style="font-size:15px;"><strong><?php echo $currency['symbol']; ?><?php echo $ordersDetails['deliveryCharge']; ?></strong></td>
      </tr>
      <?php } ?>
      <tr>
        <td style="font-size:15px;"><strong>Total</strong></td>
        <td style="font-size:15px;" class="alignright"><strong><?php echo $currency['symbol']; ?><?php echo $ordersDetails['totalPrice']; ?></strong></td>
      </tr>
    </table>
   
   <?php /*?><a href="<?php echo base_url('index.php/adminorders/orders'); ?>"><button type="button" class="btn btn-warning back_buttons"><span class="glyphicon glyphicon-arrow-left"></span> Back</button></a><?php */?>
   <!--<iframe width="580" src="https://onfleet.com/dashboard#/manage" height="500"></iframe>-->
    <?php }}else{ ?>
    <div> No Orders found. </div>
    <?php } ?>
  </div>
