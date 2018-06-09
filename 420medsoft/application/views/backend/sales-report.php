<div class="memberlogin-wps col-md-12 products_page">
  <h2>Sales Reports</h2>
  
  <div class="col-md-12"> <?php echo validation_errors(); ?>
  <div id="message"></div>
 <table style="width:100%">
   <tr>
    <th>Subscriber</th>
    <th>Package</th>
    <th>Amount</th>
    <th>Subscriber status</th>
    <th>Purchased on</th>
   
  </tr>
    <?php  
    $i=1;
    foreach($reports as $report)
    {
	  ?> 
    <tr>
    <td><?php echo $report['name'] ?></td>
    <td><?php echo $report['PackageName'] ?></td>
    <td><?php echo $report['Cost'] ?></td>
    <td><?php echo ($report['status'])?'Active':'In active' ?></td>
    <td><?php echo ($report['date'])?$report['date']:'-' ?></td>
  </tr>
 <?php $i++; } ?>
 
</table> 
</div>
</div>
<script type="text/javascript">
function resetinventory()
{
	if(confirm("Are you sure you want to reset your inventory and sales?"))
	{
		var dataString = 'parentID=2'
		$.ajax({
			type: "POST",
			url: '<?php echo base_url('index.php/admininventory/resetInventory'); ?>',
			data: dataString,
			success: function (data) {
				$("#message").html(data);
			}
		});
	}
}
</script>