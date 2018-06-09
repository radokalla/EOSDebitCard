<div class="memberlogin-wps col-md-12 products_page">
  <h2>Sales Reports</h2>
  
  <div class="col-md-12"> <?php echo validation_errors(); ?>
  <div id="message"></div>
 <table style="width:100%">
   <tr>
    <th>DURATION</th>
    <th>NO.PERSONS</th>
    <th>AMOUNT</th>
  </tr>
  <tr>
    <td>Year</td>
    <td><?php echo $reports['noofpersons'] ?></td>
    <td><?php echo $reports['total_sales'] ?></td>
  </tr>

  <tr>
    <td>Month</td>
    <td><?php echo $reports['month']['noofpersons']?></td>
    <td><?php echo $reports['month']['total_sales']?></td>
  </tr>
  <?php  
  $i=1;
  foreach($reports['month']['week'] as $key)
  {
	  ?> 
  <tr>
    <td>week<?php echo $i; ?></td>
    <td><?php echo $key['noofpersons']; ?></td>
    <td><?php echo $key['total_sales']; ?></td>
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