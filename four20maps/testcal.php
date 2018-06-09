<?php

ob_start();
session_start();

 $header='SMS';
$username = $_SESSION["regSuccess"];
if(empty($username)){ ?>
	<script>
		window.location.href = 'addstore.php';
	</script>
<?php }
include_once 'header.php';
error_reporting(0);
$db = db_connect();

?>
        	<div class="row">
            	<?php include ROOT."admin-left.php"; ?>
                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 myact-userdet">
                        <h2 class="head-text">SMS Calculator</h2>
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                        <form class="form-horizontal" id="" method="post" >
                            <div class="col-xs-4 control-group form-group">
    <div class="controls">
        <label>Number Of SMS:</label>
        <input type="text" class="form-control calculate" id="uweight" required value="00">
        <p class="help-block"></p>
    </div>
</div>
<div class="col-xs-4 control-group form-group">
    <div class="controls">
        <label>Per SMS:</label>
        <input type="text" class="form-control calculate" id="units" required value="0.2">
        <p class="help-block"></p>
    </div>
</div>
<div class="col-xs-4 control-group form-group">
    <div class="controls">
        <label>Total:</label>
        <input type="text" class="form-control" id="total" readonly>
        <p class="help-block"></p>
    </div>
</div>
<div class="col-xs-12 control-group form-group" id="errmsg"></div>
                        </form></div>
                     </div>
                </div>
            </div>
        </div>
	</div>
</div>



<script>
$(document).ready(function () {

    //Calculate both inputs value on the fly
    $('.calculate').keyup(function () {
        var Tot = parseFloat($('#units').val()) * parseFloat($('#uweight').val());
        $('#total').val(Tot);
    });

    //Clear both inputs first time when user focus on each inputs and clear value 00
    $('.calculate').focus(function (event) {
        $(this).val("").unbind(event);
    });

    //Remove this it's just for example purpose
    $('.calculate').keypress(function (e) {
        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
            $("#errmsg").html("Digits Only").show().fadeOut("slow");
            return false;
        }
    });
});
</script>
</body>
<?php include ROOT."themes/footer.inc.php"; ?>
</html>