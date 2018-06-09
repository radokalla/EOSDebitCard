
<div class="memberlogin-wps col-md-12 products_page">
  <h2>Partners<a class="category_add" href="<?php echo base_url('index.php/admin/addpartner'); ?>">Add Partner</a></h2>
  <div class="col-md-12"> </div>
  <?php if(!$employeeDetails){ ?>
  <div>No Partners found. Please add.</div>
  <?php }else{ ?>

  <form method="post">
    <table class="table table-hover table-striped table_hd1">
<thead class="table_heading">
        <tr>
          <th class="cell1">Username:</th>
           <th class="cell1"> </th>
          
        </tr>
      </thead>
       <tbody>
       <tr>
       <td> <input class="text_input2" type="text" name="userName" value="" size="10" ></td>
         
                <td width="14%">   <input type="hidden" name="recordPerPage" value="<?php echo isset($recordsperpage) ? $recordsperpage : 10; ?>">
            <input type="submit" name="search" class="btn btn-success"></td>
       </tr>
       </tbody>
    </table>
  </form>
  
  <form name="view_order" method="post" action=""><div class="col-md-10"></div><div class="col-md-2 show_class"> 
  <select name="recordPerPage" onchange="view_order.submit()" class="text_input4 cellform">
    <option value="10" <?php echo ($recordsperpage == 10) ? ' selected="selected"' : ''; ?>>10 Records</option>
    <option value="25" <?php echo ($recordsperpage == 25) ? ' selected="selected"' : ''; ?>>25 Records</option>
    <option value="50" <?php echo ($recordsperpage == 50) ? ' selected="selected"' : ''; ?>>50 Records</option>
    <option value="100" <?php echo ($recordsperpage == 100) ? ' selected="selected"' : ''; ?>>100 Records</option>
    <option value="250" <?php echo ($recordsperpage == 250) ? ' selected="selected"' : ''; ?>>250 Records</option>
    <option value="500" <?php echo ($recordsperpage == 500) ? ' selected="selected"' : ''; ?>>500 Records</option>
    <option value="1000" <?php echo ($recordsperpage == 1000) ? ' selected="selected"' : ''; ?>>1000 Records</option>
  </select></div>
  </form>

  <table class="table table-hover table-striped table_hd1">
    <thead class="table_heading">
      <tr>
        <th>S No</th>
        <th>User Name</th>
        <th>Partner Name</th>
        <th>Status</th>
        <th width="10%">Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php $sno = 0; foreach($employeeDetails as $employeeID => $employee){ ?>
      <tr>
        <td class="alignright1"><?php echo ++$sno; ?></td>
        <td><?php echo $employee['userName']; ?></td>
        <td><?php echo $employee['firstName'].' '.$employee['lastName']; ?></td>
        <th id="categoryActive-<?php echo $employee['ID']; ?>"><?php echo $employee['isActive'] ? '<a data-toggle="tooltip" data-placement="top" title="Click here to inactivate" class="btn btn-sm btn-success" onclick="activate(\''.$employee['ID'].'\', \'0\')">Active</a>' : '<a data-toggle="tooltip" data-placement="top" title="Click here to activate" class="btn btn-sm btn-danger" onclick="activate(\''.$employee['ID'].'\', \'1\')">Inactive</a>'; ?></th>
        
        <td class="textbutton"><a href="<?php echo base_url('index.php/admin/addpartner/'.$employee['ID']); ?>" data-toggle="tooltip" data-placement="top" title="Edit" class="glyphicon glyphicon-pencil view_button"></a> <a onclick="return confirm('Are you sure. Do you want to delete?');" href="<?php echo base_url('index.php/admin/deletepartner/'.$employee['ID']); ?>" class="glyphicon glyphicon-trash view_button" data-toggle="tooltip" data-placement="top" title="Delete"></a></td>
      </tr>
    </tbody>
    <?php } ?>
  </table>
  
  <div class="pagination">&nbsp;&nbsp;<?php echo $paginationLinks; ?></div>
  
  <?php } ?>
</div>
<script type="text/javascript">
function activate(employeeID, status)
{
	var dataString = "employeeID="+employeeID+"&status="+status;
	$.ajax({
		type: "POST",
		url: '<?php echo base_url('index.php/admin/updatePartnerStatus'); ?>',
		data: dataString,
		success: function (data) {
			if(data)
			{
				var html = '';
				if(status == 0)
					html = '<a data-toggle="tooltip" data-placement="top" title="Click here to activate" class="btn btn-sm btn-danger" onclick="activate(\''+employeeID+'\', \'1\')">Inactive</a>';	
				else 
					html = '<a class="btn btn-sm btn-success" data-toggle="tooltip" data-placement="top" title="Click here to inactivate" onclick="activate(\''+employeeID+'\', \'0\')">Active</a>';	
				$("#categoryActive-"+employeeID).html(html);
				$("[data-toggle='tooltip']").tooltip();
			}
		}
	});
}
function getQr(employeeID)
{
	var dataString = "employeeID="+employeeID;
	$.ajax({
		type: "POST",
		url: '<?php echo base_url('index.php/admin/getQrCode'); ?>',
		data: dataString,
		success: function (data) {
			alert(data)
		}
	});
}

</script>