$(function() {

	$("#selectAll").click(function()
	{
		var checked_status = this.checked;
		$('input[name="deleteCB[]"]').each(function()
		{
			this.checked = checked_status;
		});
	});
	   
	
	$('input[name="deleteCB[]"], #selectAll').click( function(){
		var n = 1;
		$('input[name="deleteCB[]"]').each( function(){
			var checked_status = this.checked;
			if(checked_status){
				n++;
			}
		});
		
		if(n > 1){
			$("#del_tr").show();
			//$("#deleteAll").removeClass("disabled");
			//$("#deleteAll").attr("disabled", false);
		}
		else{
			$("#del_tr").hide();
			//$("#deleteAll").addClass("disabled");
			//$("#deleteAll").attr("disabled", true);
		}
	});
	
	
	

	var $dialog = $('<div></div>')
		.html('Item(s) have been successfully deleted')
		.dialog({
			autoOpen: false
		});
	var $dialog2 = $('<div></div>')
		.html('No checkboxes are checked!')
		.dialog({
			autoOpen: false,
			title: 'Error'
		});


     /*$("#deleteAll").click(function() {
     
	 $("#search_form").submit(function(e) {
		return false;       
	 });
	 	
	 if ($('input[type=checkbox]').is(':checked')){
        $( "#dialog-confirm" ).dialog({
                modal: false,
                      buttons: {
                            "Delete all items": function() {
                              $(this ).dialog( "close" );
                                        var data = $(":checkbox:checked").map(function(i,n)
                                              {
                                                return $(n).val();
												//alert($(n).val());
                                            }).get();
											alert(data);
                                                  $.post("?p=process", { 'deleteCB[]': data },
                                                   function(){
                                                                alert(deleteCB[data]);
																$('body').load('?p=members', function() {
                                                                  $dialog.dialog({title: 'Item(s) Deleted'});
                                                                  });
                                                            });
        },
        Cancel: function() {
          $( this ).dialog( "close" );
          return false;
        }
      } //end buttons
    });
    }
    else
    {
         $dialog2.dialog("open");
    }
   });*/
});

