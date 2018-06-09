
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?=TITLE;?></title>
<link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url("images/favicon.ico");?>">

<link rel="stylesheet" href="<?php echo base_url("css/bootstrap.css");?>">
<link rel="stylesheet" href="<?php echo base_url("css/f20maps.css");?>">
<link rel="stylesheet" href="<?php echo base_url("css/animate.css");?>">

<link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>

</head><body>


<div class="container">
  <div class="title wow bounceInDown animated"> <?=$patientName;?> </div>
</div>
<div class="haeder"> <div class="container"><span class="order wow flipInX center animated" data-wow-delay="1s" style="visibility: visible; animation-delay: 1s; animation-name: flipInX;">you are In the Queue. </span></div> </div>
<div class="container">
  <div class="collect wow flipInX center animated" data-wow-delay="1.5s" style="visibility: visible; animation-delay: 1.5s; animation-name: flipInX;"> &nbsp; Please have a seat in the waiting room and watch the monitor for your station assignment.</div>
  <?php /*?><div class="wht-box">
    <div class="box  wow flipInX animated" data-wow-delay="2s" style="visibility: visible; animation-delay: 2s; animation-name: flipInX;"><?php echo $announcement['counterNumber']; ?></div>
  </div><?php */?>
  <div class="clearfix"></div>
</div>
<div class="ftlogo"><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <?php /*?><td width="388" ><img src="<?php echo base_url("images/footer-logo.png");?>" alt=""/></td><?php */?>
      <td align="center"><img src="<?php echo base_url("images/logo-announcement.png");?>" alt=""/></td>
    </tr>
  </tbody>
</table></div>


</body>
</html>
<script type="text/javascript">
setTimeout(function(){
  window.close();
}, 5000);
</script>