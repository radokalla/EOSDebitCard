
<div class="row">
      <div class="col-md-12">
        <h1 class="hd-title-light"><?php if(!isset($message) || empty($message)){ ?>Thank you for your order<?php }else{ ?>Error occured during payment<?php } ?></h1>
        <p class="col-md-11 p-tx22"><?php if(isset($message)){ echo urldecode($message); } ?></p>
        <div class="newuser-wps col-md-8"></div>
    <div class="newuser-wps col-md-4"> <a href="<?php echo base_url("index.php/orders"); ?>" class="btn btn-block btn-primary btn-grey">Back to Orders</a> </div>
  </div>
</div>
