<html>
<head>
<title>User Registration Using PHP Ajax</title>
<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript">
$(document).ready(function(){
$("#submit").click(function(){
var username=$('#username').val();
var password=$('#password').val();
var password1=$('#password1').val();
var email=$('#email').val();
$.ajax({
type: "POST",
url: "ajax.php",
data: "username="+username+"&password="+password+"&email="+email ,
success: function(html){
$("#load").css('display','block');
$("#form2").css('display','none');
$("#box").css('display','none');
$("#load").fadeOut('500', function(){
$("#load").css('display','none');
$("#box").html(html).show('slow');
});
}
});
return false;

});
});
</script>
</head>
<style type="text/css">
#load
{
display:none;
width:500px;
height:500px;
background:url(loading3.gif) no-repeat;
}
#line
{
margin:20px 0;
}
</style>
<body>
<div id="load" style="">
</div>
<div id="box">
</div>
<form method="post" action="" id="form2">
<div id="line">USERNAME: <input type="text" name="username" id="username" /></div>
<div id="line">PASSWORD:<input type="password" name="password" id="password" /></div>
<div id="line">EMAIL:<input type="text" name="email" id="email" /></div>
<input type="submit" id="submit" name="submit" />
</form>

</body>
</html>