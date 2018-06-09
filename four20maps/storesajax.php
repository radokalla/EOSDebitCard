<script type="atext/javascript">
function register(id)
	{
		$('#register-form').toggle();
		$('#subs_type').val(id);
	}
	
	function SubmitForm()
	{
		var formdata = $('#FormReg').SerializeArray();
		var username = $('#uname').val();
		var email = $('#email').val();
		var password = $('#pswrd').val();
		var cpassword = $('#cpswrd').val();
		var phoneno = $('#phno').val();
		if(username=='')
		{
			$('#uname').focus();
			$('#uname').css('border-radius','2px');
			$('#uname').css('border-color','red');
			$("#uname").attr('title', 'This is the hover-over text');
		}
			
	}
</script>