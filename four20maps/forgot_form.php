<?php include_once 'header.php';?>
        	<div class="row">
            	
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"><h3>Forgot Password</h3>
                    <form OnSubmit="return false;" method="post" id="resetForm">
                    		<div class="form-group">
                                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email">
              				</div>
                    		<div class="form-group">
                                <input type="text" id="temp_pass" name="temp_pass" class="form-control"  placeholder="Enter your temporary password">
              				</div>
							<div class="form-group">
                            	<input type="password" id="new_pass" name="new_pass" class="form-control" placeholder="Enter your New password" />
                            </div>
							<div class="form-group">
                            	<input type="password" id="c_pass" name="c_pass" class="form-control" placeholder="Confirm Your New Password"/>
                            </div>
							<div class="form-group"><button OnClick="ResetPass();" class="btn btn-primary">Confirm</button><a href="".ROOT_URL."index.php" class="btn btn-success pull-right">Home</a></div>
						</form>
						<div class="alert alert-success" id="fgtpgdiv" style="text-align:center; display:none"></div>
                </div>
            </div>
        </div>
	</div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script>
		function ResetPass()
		{
			var email = $.trim($('#email').val());
			var temp_pass = $.trim($('#temp_pass').val());
			var new_pass = $.trim($('#new_pass').val());
			var c_pass = $.trim($('#c_pass').val());
			if((email!='') && (temp_pass!='') && (new_pass!='') && (c_pass!=''))
			{
				var formdata = $('#resetForm').serializeArray();
				$.ajax({
						type: "POST",
						url: "reset.php",
						data : formdata,
						success: function(data)
						{
							if((data==1))
							{
								$('#fgtpgdiv').removeClass('alert-warning');
								$('#fgtpgdiv').addClass('alert-success');
								document.getElementById('fgtpgdiv').innerHTML = "Passwod reset successful..";
								$('#fgtpgdiv').show();
								setTimeout(function (){window.location.href='addstore.php'; }, 3000);								
							}
							else if((data==2))
							{
								$('#fgtpgdiv').removeClass('alert-warning');
								$('#fgtpgdiv').addClass('alert-success');
								document.getElementById('fgtpgdiv').innerHTML = "Passwod reset successful..";
								$('#fgtpgdiv').show();
								setTimeout(function (){window.location.href='index.php'; }, 3000);
							}
							else
							{
								$('#fgtpgdiv').removeClass('alert-success');
								$('#fgtpgdiv').addClass('alert-warning');
								document.getElementById('fgtpgdiv').innerHTML = data;
								$('#fgtpgdiv').show();
								setTimeout(function (){$('#fgtpgdiv').hide(); }, 4000);
							}
						}
				});
			}
			else
			{
				$('#fgtpgdiv').removeClass('alert-success');
				$('#fgtpgdiv').addClass('alert-warning');
				document.getElementById('fgtpgdiv').innerHTML = 'Please Fill all the details';
				$('#fgtpgdiv').show();
				setTimeout(function (){$('#fgtpgdiv').hide(); }, 4000);
			}
		}
	</script>
<?php include ROOT."themes/footer.inc.php";    /* if(($data['Payment'])==0) { echo $data['payment'] ;} */  ?> 
</body>
</html>