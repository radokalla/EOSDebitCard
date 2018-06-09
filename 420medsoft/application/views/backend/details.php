
<div class="memberlogin-wps col-md-12 products_page">
<?php /*?><h2>Order Details</h2><?php */?>
  
    <?php if($subscriberDetails){ ?>
   
   
   <table class="table table-hover table-striped table_hd">
      <tr>
        <td>Name</td>
        <td><?php echo $subscriberDetails['username']; ?></td>
      </tr>
      <tr>
        <td>Email</td>
        <td><?php echo $subscriberDetails['email']; ?></td>
      </tr>
      <tr>
        <td>Address</td>
        <td><?php echo $subscriberDetails['address']; ?></td>
      </tr>
      <tr>
        <td>Phone Number</td>
        <td><?php echo $subscriberDetails['PhoneNumber']; ?></td>
      </tr>
      <tr>
        <td>Domain Name</td>
        <td><?php echo $subscriberDetails['DomainName']; ?></td>
      </tr>
      <tr>
        <td>Color</td>
        <td><?php echo $subscriberDetails['ColorID']; ?></td>
      </tr>
      
      
      <tr>
        <td>Package Name</td>
        <td><?php echo $subscriberDetails['PackageName']; ?></td>
      </tr>
      <tr>
        <td><?php echo $subscriberDetails['Description']; ?></td>
        <td><?php echo $subscriberDetails['CurrencySymbol'].number_format($subscriberDetails['Cost'],2); ?></td>
      </tr>
      <tr>
        <td><?php echo $subscriberDetails['RecurringDuration']; ?></td>
        <td><?php echo $subscriberDetails['CurrencySymbol'].number_format($subscriberDetails['RecurringCost'],2); ?></td>
      </tr>
      
    </table>
    
    
   
    <?php }else{ ?>
    <div> No Orders found. </div>
    <?php } ?>
  </div>
