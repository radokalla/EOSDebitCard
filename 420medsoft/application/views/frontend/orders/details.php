
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
          <th>Categoty Name</th>
          <th>Product Name</th>
          <th class="aligncenter">Quantity</th>
          <th class="alignright">Unit Donations</th>
          <th class="alignright">Donations</th>
        </tr>
      </thead>
      <tbody>
        <?php $sno = 0; foreach($ordersDetails['productDetails'] as $orderID => $productDetails){ ?>
        <tr>
          <td class="alignright"><?php echo ++$sno; ?></td>
          <td><?php echo $productDetails['subCategotyName']; ?></td>
          <td><?php echo $productDetails['productName']; ?></td>
          <td class="aligncenter"><?php echo $productDetails['quantity']; ?></td>
          <td class="alignright"><?php echo $currency['symbol']; ?><?php echo ($productDetails['categotyID'] == 96) ? $productDetails['productPrice']*2 : $productDetails['productPrice']; ?></td>
          <td class="alignright"><?php echo $currency['symbol']; ?><?php echo $productDetails['quantity']*$productDetails['productPrice']; ?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
    <table class="pull-right table table-condensed col-md-4 checkout-total">
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
        <td style="font-size:15px;"><strong><?php echo $currency['symbol']; ?><?php echo $ordersDetails['totalPrice']; ?></strong></td>
      </tr>
    </table>
    <?php }else{ ?>
    <div> No Orders found. </div>
    <?php } ?>
  </div>
</div>
