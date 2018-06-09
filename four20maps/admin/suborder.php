<?php
$heading='store';
// include Config File
include_once './includes/config.inc.php';
include_once './includes/validate.php';
// Authenticate user login
auth();

	$db = db_connect();
	$sql = mysql_query("select OrderId,SubscriptionTypeId,Subscription from SubscriptionTypes where Status = '1' order by OrderId ASC"); 
	
	if($_POST)
	{
		$queries = $_POST['method'];
		$data = json_decode($queries);
		foreach($data as $row)
		{
			mysql_query($row);
		}
		die;
	}
?>
		<script type="text/javascript" src="ordering/jquery-1.3.2.min.js"></script>
		<script type="text/javascript" src="ordering/jquery-ui-1.7.1.custom.min.js"></script>
		<!--<link rel='stylesheet' href='ordering/styles.css' type='text/css' media='all' />-->
	  <?php include("header.php"); ?>
      <!-- Left side column. contains the logo and sidebar -->
      <?php include("sidebar.php"); ?>

      <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>Sort Subscriptions</h1>
          <ol class="breadcrumb">
            <li><a href="subscription.php"><i class="fa fa-dashboard"></i>Store Subscriptions</a></li>
            <li class="active">Sort Subscriptions</li>
          </ol>
        </section>
	<section class="content">
        <div class="row">
			<div class="col-lg-12 col-sm-12 col-xs-12">
            	<div class="box">
					<div class="box-header">
					</div>
					<div class="box-body">
						<div class="alert alert-success" id="msg" style="display:none; text-align:center"></div>
						<div class="col-lg-10 col-sm-10 col-xs-12">
							</pre>
							<ul id="test-list">
								<?php while($subs = mysql_fetch_assoc($sql)){ ?>
									<li id="listItem_<?php echo $subs['SubscriptionTypeId'] ?>" class="sortsub"><img src="ordering/arrow.png" alt="move" width="16" height="16" class="handle" /><strong>
									Present Order: <?php echo $subs['OrderId']; ?> &nbsp &nbsp
									<?php echo $subs['Subscription'] ?></strong></li>
								<?php } ?>
							</ul>
							<div class="form-group" align="center">
								<button class="btn btn-primary" OnClick="Func();">Submit</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<div class="modal fade" id="loading" tabindex="-1" role="dialog" aria-labelledby="eDIT" aria-hidden="true">
	<div align="center">
		<h2 style="color:#fff">Please Wait..</h2>
		<img src="../img/loading.gif" style="height:45px;" class="img-responsive"/>
	</div>	
</div>
<script>
var method = '';
 $(document).ready(function() {
    $("#test-list").sortable({
      handle : '.handle',
      update : function () 
	  {
		  var order = $('#test-list').sortable('serialize');
		  $("#info").val('');
		  $.ajax({
			url: "ordering/process-sortable.php?"+order,
			type:"POST",
			success:function(data)
			{
				method = data;
			}
		  });
      }
    });
});
function Func()
{
	$.ajax({
		url: "",
		type:"POST",
		data: {'method':method},
		beforeSend:function(){
			$("#loading").modal('show');
		},
		complete:function(){
			$("#loading").modal('hide');
		},
		success:function(data)
		{
			document.getElementById('msg').innerHTML = 'Sorted Sucessfully';
			$('#msg').show();
			setTimeout(function (){window.location.href = "subscription.php"; }, 3000);
		}
	});
}
(function( $ ) {

    $.support.touch = typeof Touch === 'object';

    if (!$.support.touch) {
        return;
    }

    var proto =  $.ui.mouse.prototype,
    _mouseInit = proto._mouseInit;

    $.extend( proto, {
        _mouseInit: function() {
            this.element
            .bind( "touchstart." + this.widgetName, $.proxy( this, "_touchStart" ) );
            _mouseInit.apply( this, arguments );
        },

        _touchStart: function( event ) {
            if ( event.originalEvent.targetTouches.length != 1 ) {
                return false;
            }

            this.element
            .bind( "touchmove." + this.widgetName, $.proxy( this, "_touchMove" ) )
            .bind( "touchend." + this.widgetName, $.proxy( this, "_touchEnd" ) );

            this._modifyEvent( event );

            $( document ).trigger($.Event("mouseup")); //reset mouseHandled flag in ui.mouse
            this._mouseDown( event );

            return false;           
        },

        _touchMove: function( event ) {
            this._modifyEvent( event );
            this._mouseMove( event );   
        },

        _touchEnd: function( event ) {
            this.element
            .unbind( "touchmove." + this.widgetName )
            .unbind( "touchend." + this.widgetName );
            this._mouseUp( event ); 
        },

        _modifyEvent: function( event ) {
            event.which = 1;
            var target = event.originalEvent.targetTouches[0];
            event.pageX = target.clientX;
            event.pageY = target.clientY;
        }

    });

})( jQuery );
</script>
            <?php include("footer.php"); ?>