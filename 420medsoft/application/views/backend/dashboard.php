<div class="memberlogin-wps col-md-12 products_page">
  <h2>Dashboard</h2>
  <p class="dashbaord_heading">Admin&nbsp;Master</p>
  <p class="date_dashboard">Today Date: <strong>
    <?php $date=date('Y-m-d H:i:s'); echo date('l, F d, Y h:i A', strtotime($date)); ?>
    </strong></p>
  <p class="date_dashboard">New Subscribers: <strong> <a href="<?php echo base_url('index.php/backend/delsubscriberupdate') ?>"><?php echo $subscribers['newuser']; ?> Add New</a></strong></p>
  <p class="date_dashboard">Active Subscribers: <strong> <a href="<?php echo base_url('index.php/backend/subscriberdetails1/active') ?>"><?php echo $subscribers['activeuser']; ?> Edit</a></strong></p>
  <p class="date_dashboard">Inactive Subscribers: <strong> <a href="<?php echo base_url('index.php/backend/subscriberdetails1/inactive') ?>"><?php echo $subscribers['inactiveuser']; ?> Edit</a></strong></p>
  <p class="date_dashboard">Sales Reports: <strong> <a href="<?php echo base_url('index.php/backend/salesreport') ?>"> View</a></strong></p>
</div>
<div style="height:20px; width:100%;"></div>
