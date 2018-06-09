
<div class="memberlogin-wps col-md-12 products_pagess">
  <h2>Create New Order</h2>
  <div class="col-md-12">
    <form class="validate-form" method="post">
      <div class="row">
        <div class="col-md-12">
          <div class="form-group" id="patientMessage"> </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="form-group">
            <label class="col-md-4 patient_id" for="exampleInputEmail1">Patient Name</label>
            <div class="col-md-8 catogory_name">
              <input type="text" id="patientName" required="required" class="text_input3">
              <input type="hidden" name="patientID" id="patientID" class="text_input3">
            </div>
          </div>
        </div>
      </div>
      <div class="row" style="display:none;">
        <div class="col-md-12">
          <div class="form-group" style="display:none;">
            <label class="col-md-4 patient_id" for="exampleInputEmail1">Patient Notes</label>
            <div class="col-md-8 catogory_name" id="patientNotes"></div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="form-group">
            <div class="col-md-10"></div>
            <div class="col-md-2 catogory_name">
              <button type="submit" class="btn btn-primary category_button" id="search-cat">Add</button>
            </div>
          </div>
        </div>
      </div>
      <div id="show_subcategories"> </div>
    </form>
  </div>
</div>

<link rel="stylesheet" href="//code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script> 
<script src="//code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
<link rel="stylesheet" href="/resources/demos/style.css">
<script>
$(function() {
	
	$('#patientName').autocomplete({
		source: function( request, response ) {
			$.ajax({
				url : '<?php echo base_url('index.php/adminorders/getPatientDetails'); ?>',
				dataType: "json",
				data: {
				   name_startsWith: request.term
				},
				 success: function( data ) {
					 response( $.map( data, function( item ) {
						return {
							label: item.patientName,
							notes: item.patientNotes,
							value: item.patientID,
							message: item.message
						}
					}));
				}
			});
		},
		autoFocus: true,
		minLength: 0 ,
		select: function( event, ui ) {
			$( "#patientName" ).val( ui.item.label );
			$( "#patientNotes" ).html( ui.item.notes );
			$( "#patientMessage" ).html( ui.item.message );
			$( "#patientID" ).val( ui.item.value );	
			$( "#patientNotes" ).closest(".form-group").show();		
			return false;
		}    	
	});
});
</script>