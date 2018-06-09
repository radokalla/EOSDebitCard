</div></div>
      </div>
      	</div>
<footer class="footer" id="footer">
  <div class="footer-wps">
    <div class="copyright">
      <div class="container">
      <span class="copyrighttext pull-left">Copyright © 2017 Four20maps, All Rights Reserved</span>
      <span class="copyrighttext pull-left" style="margin-left: 30px;">Support: 619 972-5280</span>       
     <ul class="social-wrps pull-right">
      <li><a target="_blank" href="<?php echo SOCIAL_TW_LINK;?>"><i class="fa fa-twitter"></i></a></li>
      <li><a target="_blank" href="<?php echo SOCIAL_FB_LINK;?>"><i class="fa fa-facebook"></i></a></li>
      <li><a target="_blank" href="<?php echo SOCIAL_INST_LINK;?>"><i class="fa fa-instagram"></i></a></li>
      <li><a target="_blank" href="<?php echo SOCIAL_LIN_LINK;?>"><i class="fa fa-linkedin"></i></a></li>
      <li><a target="_blank" href="http://www.420MedSoft.com"> Powered by <img src="http://420medsoft.com/images/logo.png" style="height:20px;"></a>  </li>
      
      
     </ul>
    </div> </div>
  </div>
		<link rel="stylesheet" href="<?php echo ROOT_URL; ?>css/jquery-ui.css">
		<script src="<?php echo ROOT_URL; ?>js/jquery-1.10.2.js"></script>
		<script src="<?php echo ROOT_URL; ?>js/jquery-ui.js"></script>
		<script src="<?php echo ROOT_URL; ?>js/jquery.validate.js"></script>
		<script src="<?php echo ROOT_URL; ?>js/jquery.maskedinput.js"></script>
		<script src="<?php echo ROOT_URL; ?>js/jquery.bxslider.js"></script>
		<script src="<?php echo ROOT_URL; ?>js/jquery.mCustomScrollbar.concat.min.js"></script>
		
		<script type="text/javascript">
$('.bxslider').bxSlider({
  auto:true,
  minSlides: 4,
  maxSlides: 6,
  slideWidth: 170,
  slideMargin: 20,
  adaptiveHeight: true, 
  onSliderLoad: function(){
 $(".bxslider-wrap").css("visibility", "visible");
 }
});</script>
	    <?php  if(!empty($_SESSION["regSuccess"])){?>
		<script type="text/javascript"  src="<?php echo ROOT_URL; ?>js/json_register_data.js" ></script>
		<?php }else{?>
		<script type="text/javascript"  src="<?php echo ROOT_URL; ?>js/json_non_register_user_store.js" ></script>
		
		<?php }?>
		<script src="<?php echo ROOT_URL; ?>pagescript.js"  charset="utf-8"></script>
</footer>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-101540967-1', 'auto');
  ga('send', 'pageview');

</script>
</body>
</html>
