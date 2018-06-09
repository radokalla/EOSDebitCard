</div>
</div>
<script type="text/javascript">
function launchdemo()
{
	$("#admin-login-form").submit();
}
</script>

<form class="validate-form" id="admin-login-form" role="form" method="post" target="_blank" action="http://420medsoft.com/index.php/admin/">
  <input type="hidden" placeholder="Username" name="username"  id="username"   autocapitalize="off" value="admin">
  <input type="hidden" placeholder="Password" name="password"  id="password"  autocapitalize="off" value="123456">
</form>
<div id="footer" class="footer-wps">
  <?php /*?><div class="sec-footer">

    		<div class="container">

            	<div class="row">

                	<div class="col-md-3 sec-borright">

                    	<img src="<?php echo base_url("images/footer-logo.png");?>" class="img-responsive">

                        <div class="socia-media col-md-12">

                        	<a href="https://www.facebook.com/bayfrontorganics" class="scl-icn facebook" target="_blank"></a>

                        	<a href="https://twitter.com/BayFrontOrganic" class="scl-icn twitter" target="_blank"></a>

                        	<a href="http://instagram.com/BayFrontOrganics/" class="scl-icn instram" target="_blank"></a>

                        	<a href="https://www.linkedin.com/" class="scl-icn linkdin" target="_blank"></a>

                        </div>

                    	

                    </div>

                	<div class="col-md-9 info-wps">

                    	<div class="col-md-5">

                        	<h3>7 days a week 7am-9pm</h3>

                            <p>7625 Carroll Road,<br />

                            San Diego CA 92121</p> 
                            
                            <a target="_blank" href="https://www.google.co.in/maps/search/marijuana+doctors+near+San+Diego/@32.8245525,-117.0951632,10z/data=!3m1!4b1?hl=en"><h3>Doctor Search</h3></a>

                        </div>

                        <div class="col-md-7">

                        	<div class="info-map pull-right">

                            	<iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d3350.529536001857!2d-117.15822000000003!3d32.884164999999996!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x80dbf8b31a2500d9%3A0x52fb140261cc5777!2s7625+Carroll+Rd%2C+San+Diego%2C+CA+92121%2C+USA!5e0!3m2!1sen!2sin!4v1409576904750" width="390" height="200" frameborder="0" style="border:0"></iframe>

                            </div>

                        </div>

                    </div>

                </div>

        	</div>

        </div>
<?php */?>
  <div class="sec-footer">
    <div class="container">
      <div class="row">
        <div class="col-md-3 sec-borright"> <a href="<?php echo base_url("index.php/main/index"); ?>"><img src="<?php echo base_url('images'); echo "/"; echo $logos[0]['footerlogo']; ?>" class="img-responsive"></a>
          <div class="socia-media col-md-12 clearfix"> 
          <a href="<?php echo isset($admin_settings['facebook'])&&!empty($admin_settings['facebook']) ? 'https://www.facebook.com/'.$admin_settings['facebook'] : "javascript:";?>" class="scl-icn facebook" <?php echo isset($admin_settings['facebook'])&&!empty($admin_settings['facebook']) ? '' : 'style="opacity:0.1"';?>  target="_blank"></a> 
          <a href="<?php echo isset($admin_settings['twitter'])&&!empty($admin_settings['twitter']) ? 'https://twitter.com/'.$admin_settings['twitter'] : "javascript:";?>" class="scl-icn twitter" <?php echo isset($admin_settings['twitter'])&&!empty($admin_settings['twitter']) ? '' : 'style="opacity:0.1"';?>  target="_blank"></a> 
          <a href="<?php echo isset($admin_settings['instagram'])&&!empty($admin_settings['instagram']) ? 'http://instagram.com/'.$admin_settings['instagram'] : "javascript:";?>" class="scl-icn instram" <?php echo isset($admin_settings['instagram'])&&!empty($admin_settings['instagram']) ? '' : 'style="opacity:0.1"';?>  target="_blank"></a> 
          <a href="<?php echo isset($admin_settings['linkedin'])&&!empty($admin_settings['linkedin']) ? 'https://www.linkedin.com/'.$admin_settings['linkedin'] : "javascript:";?>" class="scl-icn linkdin" <?php echo isset($admin_settings['linkedin'])&&!empty($admin_settings['linkedin']) ? '' : 'style="opacity:0.1"';?>  target="_blank"></a>
            <?php /*?><a href="<?php echo base_url("index.php/main/index"); ?>" target="_blank" class="scl-icn four-soc-chat"></a><?php */?>
          <?php /*?><a href="<?php echo isset($admin_settings['chat'])&&!empty($admin_settings['chat']) ? $admin_settings['chat'] : "javascript:";?>" target="_blank" class="scl-icn four-soc-map" <?php echo isset($admin_settings['chat'])&&!empty($admin_settings['chat']) ? '' : 'style="opacity:0.1"';?> ></a><?php */?>
          <a href="http://www.four20maps.com" target="_blank" class="scl-icn four-soc-map"></a> </div>
           
        </div>
        <div class="col-md-5 foot-bor-right clearfix">
          <div class="info-wps"><a onclick="launchdemo()" style="cursor:pointer"><!--<img src="<?php //echo base_url("images/demo.png");?>" class="img-responsive">--></a></div>
        </div>
        <div class="col-md-4"> <a href="<?php echo base_url('index.php/main/packages');?>">
          <div class="subscribe-form"><!--<img src="<?php //echo base_url("images/subscribe.png");?>" class="img-responsive">--></div>
          </a> </div>
      </div>
    </div>
  </div>
  <div class="copyright">
    <div class="container"> <?=COPYRIGHT;?> </div>
  </div>
</div>
   <div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="product_title">Modal Header</h4>
      </div>
      <div class="modal-body">
        <p><img id="thumbnil" src="" alt="image" class="img-responsive"/></p>
        <div class="row"> 
        
			<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12" id="product_description"></div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><div class="product-itemwps-bx prditmsbxwdful"><div class="product-itemwps prdctitem_optiontype"><div class="prd-optsbx-txt mbwdfl"></div><div class="prd-optsbx-txt"><span class="prd-optsbx-txt-optype">CBD % :</span> <span class="prd-optsbx-txt-sb" id="cbd_per">0.00</span></div><div class="prd-optsbx-txt"><span class="prd-optsbx-txt-optype">THC % :</span> <span class="prd-optsbx-txt-sb" id="thc_per">0.00</span></div><div class="prd-optsbx-txt"><span class="prd-optsbx-txt-optype">THCA % :</span> <span class="prd-optsbx-txt-sb" id="thca_per">0.00</span></div></div></div></div></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<a href="#0" class="cd-top">Top</a>
    <script type="text/javascript">
    
    jQuery(document).ready(function($){
	// browser window scroll (in pixels) after which the "back to top" link is shown
	var offset = 300,
		//browser window scroll (in pixels) after which the "back to top" link opacity is reduced
		offset_opacity = 1200,
		//duration of the top scrolling animation (in ms)
		scroll_top_duration = 700,
		//grab the "back to top" link
		$back_to_top = $('.cd-top');
	//hide or show the "back to top" link
	$(window).scroll(function(){
		( $(this).scrollTop() > offset ) ? $back_to_top.addClass('cd-is-visible') : $back_to_top.removeClass('cd-is-visible cd-fade-out');
		if( $(this).scrollTop() > offset_opacity ) { 
			$back_to_top.addClass('cd-fade-out');
		}
	});
	//smooth scroll to top
	$back_to_top.on('click', function(event){
		event.preventDefault();
		$('body,html').animate({
			scrollTop: 0 ,
		 	}, scroll_top_duration
		);
	});

    $(".rl-imwps").click(function(){ 
		 
		$("#thumbnil").attr("src",$(this).find("img").attr("data-img"));
		$("#product_title").html($(this).find("img").attr("data-productname"));
       $("#cbd_per").html($(this).find("img").attr("data-option-2"));
		$("#thc_per").html($(this).find("img").attr("data-option-3"));
		$("#thca_per").html($(this).find("img").attr("data-option-4"));
		$("#product_description").html($(this).find("img").attr("data-productDescription"));
		//$('#myModalq').modal('show');
    });
	
	
	});$(document).on({
    'show.bs.modal': function () {
        var zIndex = 1040 + (10 * $('.modal:visible').length);
        $(this).css('z-index', zIndex);
        setTimeout(function() {
            $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
        }, 0);
    },
    'hidden.bs.modal': function() {
        if (jQuery('.modal:visible').length > 0) {
            // restore the modal-open class to the body element, so that scrolling works
            // properly after de-stacking a modal.
            setTimeout(function() {
                $(document.body).addClass('modal-open');
            }, 0);
        }
    }
}, '.modal');
    </script>

</body></html>
<script type="text/javascript">
    $(document).ready(function () {
    $('input[type=text]').each(function(){
     $(this).attr("autocapitalize","off");
    $(this).attr("autocorrect","off");
   })});
</script>