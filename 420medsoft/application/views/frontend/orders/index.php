
<div class="row">
  <div class="col-md-12">
    <h1 class="hd-title-light">My orders</h1>
  </div>
  <?php $this->load->view('frontend/includes/sidebar');?>
  <div class="col-md-9 item-wps-container">
  	<?php if($ordersDetails){ ?>
    <table class="table table-hover table-striped">
      <thead>
        <tr>
          <th class="alignright">S No</th>
          <th>Order Id</th>
          <th class="alignright">Total</th>
          <th>Delivery Type</th>
          <th>Status</th>
          <th class="aligncenter">Order Date</th>
        </tr>
      </thead>
      <tbody>
        <?php $sno = 0; foreach($ordersDetails as $orderID => $orderDetails){ ?>
        <tr>
        <td class="alignright"><?php echo ++$sno; ?></td>
          <td><a href="<?php echo base_url('index.php/orders/details/'.$orderID); ?>"><?php echo $orderID;?></a></td>
          <td class="alignright"><?php echo $currency['symbol']; ?><?php echo $orderDetails['totalPrice']; ?></td>
          <td><?php echo $orderDetails['deliveryType']; ?></td>
          <td><?php echo $ordersStatus[$orderDetails['status']]; ?></td>
          <td class="aligncenter"><?php echo $orderDetails['created']; ?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
    <?php }else{ ?>
    <div> No Orders found. </div>
    <?php } ?>
  </div>
</div>
