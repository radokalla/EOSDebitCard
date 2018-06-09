<div class="memberlogin-wps col-md-12 products_page">
  <h2>Counters</h2>
  <?php if($counterDetails){ ?>
  <table class="table table-hover table-striped table_hd">
    <thead class="table_heading">
      <tr>
        <th>Counter</th>
        <th>Patient Name</th>
        <th>Order Date</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($counterDetails as $orderID => $counterDetail){ ?>
      <tr>
        <td><?php echo $counterDetail['counterName'];?></td>
        <td><?php echo $counterDetail['patientName'];?></td>
        <td class="aligncenter"><?php echo date("m/d/Y H:i:s", strtotime($counterDetail['created'])); ?></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
  <?php }else{ ?>
  <div> All Counters are free. </div>
  <?php } ?>
</div>
