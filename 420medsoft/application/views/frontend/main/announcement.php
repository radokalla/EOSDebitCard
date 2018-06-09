
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?=TITLE;?></title>
<link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url("images/favicon.ico");?>">

<link rel="stylesheet" href="<?php echo base_url("css/f20maps.css");?>">
<link rel="stylesheet" href="<?php echo base_url("css/animate.css");?>">
<script src="<?php echo base_url("js/responsivevoice.js");?>"></script>
<script src="http://code.jquery.com/jquery-git2.js"></script>
<script type='text/javascript' src='<?php echo base_url("js/wow.min.js");?>'></script>
<?php if(isset($announcement['patientName']) && !empty($announcement['patientName'])){ ?>
<script type="text/javascript">
//responsiveVoice.speak("Dear Rado Kalla Yadav your order is completed Please collect your order at station number 02",'US English Female')
var text = "Dear <?php echo $announcement['patientName']; ?> Please proceed to station <?php echo $announcement['counterNumber']; ?>";
            var url = 'http://translate.google.com/translate_tts?tl=en&q='+text;
            var a = new Audio(url);
                a.play();
         
</script>
<?php } ?>
<link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>

</head><body>

<?php if(isset($announcement['patientName']) && !empty($announcement['patientName'])){ ?>
<div class="mainheader">
  <div class="title wow bounceInDown animated"> Dear <?php echo $announcement['patientName']; ?> </div>
</div>
<div class="haeder"> <span class="order wow flipInX center animated" data-wow-delay="1s" style="visibility: visible; animation-delay: 1s; animation-name: flipInX;">Please proceed to station </span> </div>
<div class="mainwrap ">
  <div class="collect wow flipInX center animated" data-wow-delay="1.5s" style="visibility: visible; animation-delay: 1.5s; animation-name: flipInX;"> &nbsp; <?php /*?> Please collect your order at station number <?php */?></div>
  <div class="wht-box">
    <div class="box  wow flipInX animated" data-wow-delay="2s" style="visibility: visible; animation-delay: 2s; animation-name: flipInX;"><?php echo $announcement['counterNumber']; ?></div>
  </div>
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

<?php }else{ ?>


<div class="mainheader">
  <div class="title wow bounceInDown animated">  </div>
</div>
<div class="haeder"> <span class="order wow flipInX center animated" data-wow-delay="1s" style="visibility: visible; animation-delay: 1s; animation-name: flipInX;">Welcome to <?=SITENAME;?></span> </div>

<div class="ftlogo"><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <?php /*?><td width="388" ><img src="<?php echo base_url("images/footer-logo.png");?>"  alt=""/></td><?php */?>
      <td align="center"><img src="<?php echo base_url("images/logo-announcement.png");?>"  alt=""/></td>
    </tr>
  </tbody>
</table></div>

<?php } ?>

</body>
</html>
<script type="text/javascript">
window.setTimeout(function(){ document.location.reload(true); }, 10000);
</script>