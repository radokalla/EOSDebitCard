
<div class="memberlogin-wps col-md-12 products_page">  
    <?php if($ordersDetails){ ?>
   <table class="table table-hover table-striped table_hd">

     <thead class="table_heading">
        <tr>
          <th>S No</th>
          <th>Categoty Name</th>
          <th>Product Name</th>
          <th>Quantity</th>
          <th>Unit Donations</th>
          <th>Donations</th>
        </tr>
      </thead>
      <tbody>
        <?php $sno = 0; foreach($ordersDetails['productDetails'] as $orderID => $productDetails){ ?>
        <tr>
          <td class="aligncenter"><?php echo ++$sno; ?></td>
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
        <td style="font-size:15px;"><strong>Invoice Number</strong></td>
        <td style="font-size:15px;"><strong><?php echo $ordersDetails['invoiceNumber']; ?></strong></td>
      </tr>
      
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
        <td style="font-size:15px;" class="alignright"><strong><?php echo $currency['symbol']; ?><?php echo $ordersDetails['totalPrice']; ?></strong></td>
      </tr>
    </table>
    
   
    <?php }else{ ?>
    <div> No Orders found. </div>
    <?php } ?>
  </div>
