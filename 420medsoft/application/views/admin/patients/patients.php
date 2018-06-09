<div class="memberlogin-wps col-md-12 products_page">
  <h2>Patient List<a class="category_add" href="<?php echo base_url('index.php/adminpatients/addpatient'); ?>">Add Patient</a>
   <a class="category_add" href="<?php echo base_url('index.php/adminpatients/uploadpatients'); ?>">Import patients</a></h2>
  <div class="col-md-12"> </div>
  <?php if(!$patientDetails){ ?>
  <div>No Patients found. Please add.</div>
  <?php }else{ ?>
  <form method="post" action="<?php echo base_url('index.php/adminpatients/patients'); ?>">
  	<div class="table-responsive-wps">
    <table class="table table-hover table-striped table_hd1">
      <thead class="table_heading">
        <tr>
          <th class="cell1">Patient Name:</th>
          <th class="cell1">Username:</th>
          <th class="cell1">E-mail:</th>
          <th class="cell1">Phone:</th>
          <th class="cell1">Status:</th>
          <th class="cell1"></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><input class="text_input2" type="text" name="patientName" value="" size="10" ></td>
          <td><input class="text_input2" type="text" name="userName" value="" size="8" ></td>
          <td><input class="text_input2" type="text" name="emailID" value="" size="8" ></td>
          <td><input class="text_input2" type="text" name="phone" value="" size="8" ></td>
          <td><select class="text_input2" name="Status" >
              <option value=""> Select </option>
              <option value="1">Active</option>
              <option value="0">Inavtive</option>
            </select></td>
          <td><input type="hidden" name="recordPerPage" value="<?php echo isset($recordsperpage) ? $recordsperpage : 10; ?>">
            <input type="submit" name="search" value="Search" class="btn btn-success"></td>
        </tr>
      </tbody>
    </table>
    </div>
  </form>
  <form name="view_order" method="post" action="<?php echo base_url('index.php/adminpatients/patients'); ?>">
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
        <option value="5000" <?php echo ($recordsperpage == 5000) ? ' selected="selected"' : ''; ?>>5000 Records</option>
        <option value="10000" <?php echo ($recordsperpage == 10000) ? ' selected="selected"' : ''; ?>>10000 Records</option>
      </select>
    </div>
  </form>
  <div class="table-responsive-wps">
  <table class="table table-hover table-striped table_hd1">
    <thead class="table_heading">
      <tr>
        <th>S No</th>
        <th>Patient Name</th>
        <th>Username</th>
        <th>E-mail</th>
        <th>Phone</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php $sno = 0; foreach($patientDetails as $patient){ ?>
      <tr>
        <td class="alignright1"><?php echo ++$sno; ?></td>
        <td><?php echo $patient['firstName'].' '.$patient['lastName']; ?></td>
        <td><?php echo $patient['userName']; ?></td>
        <td><?php echo $patient['email']; ?></td>
        <td><?php echo $this->session->TelephoneNumberFormat($patient['phone']); ?></td>
        <th id="patientActive-<?php echo $patient['patientID']; ?>"><?php echo $patient['isActive'] ? '<a data-toggle="tooltip" data-placement="top" title="Click here to inactivate" class="btn btn-sm btn-success" onclick="activate(\''.$patient['patientID'].'\', \'0\')">Active</a>' : '<a data-toggle="tooltip" data-placement="top" title="Click here to activate" class="btn btn-sm btn-danger" onclick="activate(\''.$patient['patientID'].'\', \'1\')">Inactive</a>'; ?>
          <?php if($session['LOGIN_TYPE']!='EMPLOYEE'){?>
          <a onclick="getQr(<?php echo $patient['patientID']; ?>)" class="btn btn-sm btn-success">QrCode</a>
          <a  onclick="getLogin('<?php echo urlencode(base64_encode($patient['patientID'])); ?>')" class="btn btn-sm btn-success">Login</a>
          <?php }?>
        </th>
        <td nowrap="nowrap"><a href="<?php echo base_url('index.php/adminpatients/addpatient/'.$patient['patientID']); ?>" data-toggle="tooltip" data-placement="top" title="Edit" class="glyphicon glyphicon-pencil view_button"></a>
          <?php if($session['LOGIN_TYPE']=='ADMIN'){?>
          <a onclick="return confirm('Are you sure. Do you want to delete?');" href="<?php echo base_url('index.php/adminpatients/deletepatient/'.$patient['patientID']); ?>" class="glyphicon glyphicon-trash view_button" data-toggle="tooltip" data-placement="top" title="Delete"></a> <a href="<?php echo base_url('index.php/adminpatients/changepwd/'.$patient['patientID']); ?>" class="glyphicon glyphicon-lock view_button" data-toggle="tooltip" data-placement="top" title="Change Password"></a>
          <?php }?></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
  </div>
  <div class="pagination">&nbsp;&nbsp;<?php echo $paginationLinks; ?></div>
  <?php } ?>
</div>
<script type="text/javascript">
function activate(patientID, status)
{
	var dataString = "patientID="+patientID+"&status="+status;
	$.ajax({
		type: "POST",
		url: '<?php echo base_url('index.php/adminpatients/updatePaitientStaus'); ?>',
		data: dataString,
		success: function (data) {
			if(data)
			{
				var html = '';
				if(status == 0)
					html = '<a data-toggle="tooltip" data-placement="top" title="Click here to activate" class="btn btn-sm btn-danger" onclick="activate(\''+patientID+'\', \'1\')">Inactive</a>';
				else 
					html = '<a data-toggle="tooltip" data-placement="top" title="Click here to inactivate" class="btn btn-sm btn-success" onclick="activate(\''+patientID+'\', \'0\')">Active</a>';
					
				$("#patientActive-"+patientID).html(html);
				$("[data-toggle='tooltip']").tooltip();
			}
		}
	});
}

function getLogin(patientID)
{
	window.open('<?php echo base_url('index.php/adminpatients/getLogin') ?>/'+patientID);
}
function getQr(patientID)
{
	window.open('<?php echo base_url('index.php/admin/getPatientQrCode') ?>/'+patientID);
}
</script>