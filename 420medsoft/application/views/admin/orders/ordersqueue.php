<div class="memberlogin-wps col-md-12 products_page">
  <h2>Orders Queue List <a class="category_add" href="<?php echo base_url('index.php/adminorders/addorderqueue'); ?>">Add Queue</a> </h2>
  <form method="post" action="<?php echo base_url('index.php/adminorders/orderqueue'); ?>">
    <table class="table table-hover table-striped table_hd">
      <thead class="table_heading">
        <tr>
          <th>Order Id:</th>
          <th>Patient Name:</th>
          <th>Order Date:</th>
          <th> </th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><input class="text_input2" type="text" name="orderID" value="" size="10" placeholder="Order Id" ></td>
          <td><input class="text_input2" type="text" name="patientName" value="" size="10" placeholder="Patient Name" ></td>
          <td><input class="text_input2" type="text" value="" name="orderDate" size="8" placeholder="YYYY-MM-DD" ></td>
          <td width="14%"><input type="submit" name="search" value="Search" class="btn btn-success"></td>
        </tr>
      </tbody>
    </table>
  </form>
  <?php if($ordersDetails){ ?>
  <table class="table table-hover table-striped table_hd">
    <thead class="table_heading">
      <tr> 
        <th>Patient Name</th>
        <th>Order Date</th>
        <th>Order By</th>
        <th width="8%">Action</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($ordersDetails as $orderID => $orderDetails){ ?>
      
      <tr> 
       
        <td><?php echo $orderDetails['patientName'];?></td>
        <td class="aligncenter"><?php echo date("m/d/Y H:i:s", strtotime($orderDetails['created'])); ?></td>
        <td class="aligncenter"><?php echo ($orderDetails['createdBy']); ?></td>
        
        <td class="aligncenter"><a class="glyphicon glyphicon-file view_button" onClick="getOrderDetails('<?php echo $orderID;?>')" data-toggle="modal" data-target="#myModal" title="View"></a></td>
      </tr>
      <?php } ?>
      
    </tbody>
  </table>
  
  <?php }else{ ?>
  <div> No Orders found. </div>
  <?php } ?>
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
      <form method="post" action="<?php echo base_url('index.php/adminorders/assignCounter'); ?>">
      <input type="hidden" name="queueID" id="queueID" value="">
      Counter : 
      <select name="counterID">
      <?php foreach($queueCounters as $queueCounter){ ?>
      <option value="<?php echo $queueCounter['counterID']; ?>"><?php echo $queueCounter['counterName']; ?></option>
      <?php } ?>
      </select>
      <input type="submit" name="assign" value="Assign">
      </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
function getOrderDetails(queueID)
{
	$("#myModal #queueID").val(queueID);
}
</script>
