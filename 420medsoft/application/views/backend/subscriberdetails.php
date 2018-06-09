<div class="memberlogin-wps col-md-12 products_page">
  <h2>Subscriber List
    <?php /*?><a class="category_add" href="<?php echo base_url('index.php/admin/addemployee'); ?>">Add Employee</a><?php */?>
  </h2>
  <div class="col-md-12"> </div>
  <?php if(!$subscriberDetails){ ?>
  <div>No Subscribers found.</div>
  <?php }else{  ?>
  <div class="col-md-12">
  <form method="post">
    <table class="table table-hover table-striped table_hd1">
      <thead class="table_heading">
        <tr>
          <th class="cell1">Name:</th>
          <th class="cell1"> </th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><input class="text_input2" type="text" name="userName" value="" size="10" ></td>
          <td width="14%"><input type="hidden" name="recordPerPage" value="<?php echo isset($recordsperpage) ? $recordsperpage : 10; ?>">
            <input type="submit" name="search" class="btn btn-success"></td>
        </tr>
      </tbody>
    </table>
  </form>
  <form name="view_order" method="post" action="">
    <div class="col-md-10"></div>
    <div class="col-md-2 show_class">
      <select name="recordPerPage" onchange="view_order.submit()" class="text_input4 cellform">
        <option value="10" <?php echo ($recordsperpage == 10) ? ' selected="selected"' : ''; ?>>10 Records</option>
        <option value="25" <?php echo ($recordsperpage == 25) ? ' selected="selected"' : ''; ?>>25 Records</option>
        <option value="50" <?php echo ($recordsperpage == 50) ? ' selected="selected"' : ''; ?>>50 Records</option>
        <option value="100" <?php echo ($recordsperpage == 100) ? ' selected="selected"' : ''; ?>>100 Records</option>
        <option value="250" <?php echo ($recordsperpage == 250) ? ' selected="selected"' : ''; ?>>250 Records</option>
        <option value="500" <?php echo ($recordsperpage == 500) ? ' selected="selected"' : ''; ?>>500 Records</option>
        <option value="1000" <?php echo ($recordsperpage == 1000) ? ' selected="selected"' : ''; ?>>1000 Records</option>
      </select>
    </div>
  </form>
  <img src="" id="showQR" alt="">
  <h5 style="color:red"><a href="<?php echo base_url('index.php/backend/delsubscriberupdate/'); ?>">Add New</a></h5>
  <table class="table table-hover table-striped table_hd1">
    <thead class="table_heading">
      <tr>
        <th>S No</th>
        <th>Name</th>
        <th>Phone No</th>
        <th>Status</th>
        <th width="10%">Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php $sno = 0; foreach($subscriberDetails as $subscriber){ ?>
      <tr>
        <td class="alignright1"><?php echo ++$sno; ?></td>
        <td><?php echo $subscriber['username']; ?></td>
        <td><?php echo $subscriber['PhoneNumber']; ?></td>
        <th id="categoryActive-<?php echo $subscriber['SubscriberID']; ?>"><?php echo $subscriber['status'] ? '<a data-toggle="tooltip" data-placement="top" title="Click here to inactivate" class="btn btn-sm btn-success" onclick="activate(\''.$subscriber['SubscriberID'].'\', \'0\')">Active</a>' : '<a data-toggle="tooltip" data-placement="top" title="Click here to activate" class="btn btn-sm btn-danger" onclick="activate(\''.$subscriber['SubscriberID'].'\', \'1\')">Inactive</a>'; ?>
        </th>
        <td class="textbutton">
		<a href="<?php echo base_url('index.php/backend/delsubscriberupdate/'.$subscriber['SubscriberID']); ?>" class="glyphicon glyphicon-refresh view_button" data-toggle="tooltip" data-placement="top" title="Update"></a>
        <a class="glyphicon glyphicon-eye-open view_button" onClick="getOrderDetails('<?php echo $subscriber['SubscriberID'];?>')" data-toggle="modal" data-target="#myModal" title="View"></a>
        <a onclick="return confirm('Are you sure. Do you want to delete?');" href="<?php echo base_url('index.php/backend/delsubscriber/'.$subscriber['SubscriberID']); ?>" class="glyphicon glyphicon-trash view_button" data-toggle="tooltip" data-placement="top" title="Delete"></a>
        
        <a class="glyphicons glyphicons-small-payments" onClick="openCreditCardPopup('<?php echo $subscriber['SubscriberID'];?>')" data-toggle="modal" data-target="#responsive" title="Payment">Pay</a>
        
        </td>
      </tr>
    </tbody>
    <?php } ?>
  </table>
  <div class="pagination">&nbsp;&nbsp;<?php echo $paginationLinks; ?></div>
  <?php } ?>
  </div>
</div>


<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Subscriber Details</h4>
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


<div id="responsive" class="modal fade" tabindex="-1" data-width="760">
 <div class="modal-dialog">
 <div class="modal-content">
  <form id="checkCreditcardDetails" class="form-horizontal">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
      <h4 class="modal-title">Credit card details</h4>
    </div>
    <div class="modal-body">
      <div class="row">
      
      	<div class="main-loader" style="display:none;">
        	<img src="<?php echo base_url('images/input-spinner.gif'); ?>" />  <span>Credit Card is processing</span>
        </div>
        
        <div class="main-creditdetails">
        
        <div class="form-group">
          <div class="col-md-12" id="error-message"> </div>
          <div class="col-md-12" id="success-message"> </div>
        </div>
        
        <input type="hidden" name="SubscriberID" id="SubscriberID" value="" />
        
        <div class="form-group">
          <label for="nameOnCard" class="control-label col-sm-4 control_labeltext">Name as it appears on Card<strong class="star">*</strong> : </label>
          <div class="col-sm-6">
            <input type="text" class="form-control" id="nameOnCard" name="nameOnCard" placeholder="Name as it appears on Card" value="" required="required">
          </div>
        </div>
        <div class="form-group">
          <label for="cardNumber" class="control-label col-sm-4 control_labeltext">Credit Card Number<strong class="star">*</strong> : </label>
          <div class="col-sm-6">
            <input type="text" class="form-control" id="cardNumber" name="cardNumber" value="" placeholder="Credit Card Number" >
          </div>
        </div>
        <div class="form-group">
          <label for="CVVCode" class="control-label col-sm-4 control_labeltext">CVC Code<strong class="star">*</strong> : </label>
          <div class="col-sm-6">
            <input type="text" class="form-control" id="CVVCode" name="CVVCode" value="" placeholder="CVC Code" >
          </div>
        </div>
        <div class="form-group">
          <label for="expiry" class="control-label col-sm-4 control_labeltext">Expiration Month<strong class="star">*</strong> : </label>
          <div class="col-sm-6">
            <select id="expiry_month" name="expiry_month" >
            <?php for($year = 1; $year <= 12; $year++){ ?>
            	<option value="<?php echo $year; ?>"><?php echo $year; ?></option>
                <?php } ?>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label for="expiry" class="control-label col-sm-4 control_labeltext">Expiration Year<strong class="star">*</strong> : </label>
          <div class="col-sm-6">
            <select id="expiry_year" name="expiry_year" >
            <?php for($year = date("Y"); $year <= date("Y")+20; $year++){ ?>
            	<option value="<?php echo $year; ?>"><?php echo $year; ?></option>
                <?php } ?>
            </select>

          </div>
        </div>
        
        <div class="form-group">
          <label for="cardNumber" class="control-label col-sm-4 control_labeltext">Cardholder Billing Street address <strong class="star">*</strong> : </label>
          <div class="col-sm-6">
            <input type="text" class="form-control" id="street_address" name="street_address" value="" placeholder="Cardholder Billing Street address" >
          </div>
        </div>
        
        <div class="form-group">
          <label for="cardNumber" class="control-label col-sm-4 control_labeltext">Cardholder Billing Zip Code<strong class="star">*</strong> : </label>
          <div class="col-sm-6">
            <input type="text" class="form-control" id="zipcode" name="zipcode" value="" placeholder="Cardholder Billing Zip Code" >
          </div>
        </div>
        
       
      </div>
      
      </div>
      
      
    </div>
    <div class="modal-footer">
      <button type="button" data-dismiss="modal" class="btn btn-default">Close</button>
      <button type="button" class="btn blue paynow">Pay</button>
    </div>
  </form>
</div>
</div>
</div>

<script type="text/javascript">

function activate(SubscriberID, status)
{
	var dataString = "SubscriberID="+SubscriberID+"&status="+status;
	$.ajax({
		type: "POST",
		url: '<?php echo base_url('index.php/backend/updateSubscriberStatus'); ?>',
		data: dataString,
		success: function (data) {
			if(data)
			{
				var html = '';
				if(status == 0)
					html = '<a data-toggle="tooltip" data-placement="top" title="Click here to activate" class="btn btn-sm btn-danger" onclick="activate(\''+SubscriberID+'\', \'1\')">Inactive</a>';	
				else 
					html = '<a class="btn btn-sm btn-success" data-toggle="tooltip" data-placement="top" title="Click here to inactivate" onclick="activate(\''+SubscriberID+'\', \'0\')">Active</a>';	
				$("#categoryActive-"+SubscriberID).html(html);
				$("[data-toggle='tooltip']").tooltip();
			}
		}
	});
}


function getOrderDetails(SubscriberID)
{
	$("#myModal .modal-body").html('');
	var dataString = "SubscriberID="+SubscriberID;
	$.ajax({
		type: "POST",
		url: '<?php echo base_url('index.php/backend/getsubscriberDetails'); ?>',
		data: dataString,
		success: function (data) {
			$("#myModal .modal-body").html(data);
		}
	});
}
	
	function openCreditCardPopup(SubscriberID)
	{
		$("#success-message").html('');
		$("#error-message").html('');
		//$("form#checkCreditcardDetails").reset();
		$("form#checkCreditcardDetails #SubscriberID").val(SubscriberID);
	}
	
	$(document).ready(function(){
		
		$("button.paynow").on('click', function(){

			$(".main-creditdetails").hide();
			$(".main-loader").show();

			var dataString = $("form#checkCreditcardDetails").serialize();
			$.ajax({
				type: "POST",
				url: '<?php echo base_url("index.php/backend/checkCreditCardDetails") ?>',
				data: dataString,
				success: function (data) {
					var obj = jQuery.parseJSON( data );
					if(obj.success == true)
					{
						$("#success-message").html(obj.message);
						$("#error-message").html('');
						$(".main-creditdetails").show();
						$(".main-loader").hide();
						//$("form#checkCreditcardDetails").reset();
					}
					else
					{
						$("#success-message").html('');
						$("#error-message").html(obj.message);
						$(".main-creditdetails").show();
						$(".main-loader").hide();
					}
				}
			});

		});
	});
</script>
