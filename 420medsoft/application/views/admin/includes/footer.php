</div>

</div>

		</div>

	</div>

    <div id="footer" class="footer-wps1">

    	<div class="sec-footer2">

    		<div class="container">

            	<div class="row">

                	<?php /*?><div class="col-md-3 sec-borright">

                    	<img src="<?php echo base_url("images/footer-logo.png");?>" class="img-responsive">

                        <div class="socia-media col-md-12">

                        	<a href="#" class="scl-icn facebook"></a>

                        	<a href="#" class="scl-icn twitter"></a>

                        	<a href="#" class="scl-icn instram"></a>

                        	<a href="#" class="scl-icn linkdin"></a>

                        </div>

                    	

                    </div>

                	<div class="col-md-9 info-wps">

                    	<div class="col-md-5">

                        	<h3>7 days a week 7am-9pm</h3>

                            <p>7625 Carroll Road,<br />

                            San Diego CA 92145</p> 

                        </div>

                        <div class="col-md-7">

                        	<div class="info-map pull-right">

                            	<iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d3350.529536001857!2d-117.15822000000003!3d32.884164999999996!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x80dbf8b31a2500d9%3A0x52fb140261cc5777!2s7625+Carroll+Rd%2C+San+Diego%2C+CA+92121%2C+USA!5e0!3m2!1sen!2sin!4v1409576904750" width="390" height="200" frameborder="0" style="border:0"></iframe>

                            </div>

                        </div>

                    </div><?php */?>
<div class="container copyright_text"> <?=COPYRIGHT;?> </div>
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

});
    
    </script>
   

  </body>

</html>
<script type="text/javascript">
    $(document).ready(function () {
    $('input[type=text]').each(function(){
     $(this).attr("autocapitalize","off");
    $(this).attr("autocorrect","off");
   })});
</script>