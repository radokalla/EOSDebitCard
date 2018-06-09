var base_url="http://www.four20maps.com/";
var isStoreSearch = $("#isStoreSearch").val();
var map='';
var infowindow = new google.maps.InfoWindow({ content: ''});
var global_marker = [];	
function storedelivery(store_id,store_lat,store_lng)
{
 var is_licensed='5';
 if($('#is_licensed').val() == 1){
	 is_licensed =1;
 }
			map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
							
							var l=0; var ln=0;
							clearMarkers();		
		$.ajax({ url: base_url,
					data: { 
						lat:store_lat,
						longi:store_lng,
						action:'get_nearby_stores',
						ajax:1,
						id:store_id,
						is_licensed:is_licensed
					  },
						dataType: "json",
						type: "POST",
						success: function(data){
							 
							var det = JSON.stringify(data);
						    dat = JSON.parse(det);
							var keyword='';							
							var stores = dat.stores;	
							//attachMarker( stores[0],map,keyword );
							var latlng = new google.maps.LatLng(stores[0].latitude,stores[0].longitude); 
							//console.log(latlng);
  							map.setPosition(latlng);
							return false;
					 }
					});
					
				 
		
	
}


function search_by_shortlink(store_id,store_lat,store_lng){
	var is_licensed='5';
 if($('#is_licensed').val() == 1){
	 is_licensed =1;
 }
				$.ajax({ url: base_url,
					data: { 
						lat:store_lat,
						longi:store_lng,
						action:'get_nearby_stores_shorturl',
						ajax:1,
						id:store_id,
						is_licensed:is_licensed
					  },
						dataType: "json",
						type: "POST",
						success: function(data){
						 	var det = JSON.stringify(data);
						    dat = JSON.parse(det);
							var keyword='';							
							var stores = dat.stores;							
							//var infowindow=[];
							var myLatlng = new google.maps.LatLng(stores[0].latitude,stores[0].longitude);
							var mapOptions = {
								 minZoom: 6, maxZoom: 15 ,
								center: myLatlng,
								zoomControl: false,
								mapTypeControl: false,
								streetViewControl: true,
								panControl: false,
								 styles: [{"featureType":"all","elementType":"labels.text.fill", "stylers":[{"saturation":36},{"color":"#a5a6a8"},{"lightness":2}]},
										{"featureType":"all","elementType":"labels.text.stroke","stylers":[{"visibility":"on"},{"color":"#45484D"},{"lightness":0}]},
										{"featureType":"all","elementType":"labels.icon","stylers":[{"visibility":"off"}]},
										{"featureType":"administrative","elementType":"geometry.fill","stylers":[{"color":"#45484D"},{"lightness":2}]},
										{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#45484D"},{"lightness":1},{"weight":1.2}]},
										{"featureType":"landscape","elementType":"geometry","stylers":[{"color":"#4F5660"},{"lightness":1}]},
										{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#4F5660"},{"lightness":2}]},
										{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#45484D"},{"lightness":6}]},
										{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#45484D"},{"lightness":6},{"weight":0.2}]},
										{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#45484D"},{"lightness":6}]},
										{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#45484D"},{"lightness":6}]},
										{"featureType":"transit","elementType":"geometry","stylers":[{"color":"#45484D"},{"lightness":19}]},
										{"featureType":"water","elementType":"geometry","stylers":[{"color":"#282A2E"},{"lightness":14}]}]
							  }
							map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
							
							var l=0; var ln=0;
							clearMarkers();
							for(x in stores)
							{
								var store = stores[x];
								store.id = store.id;
								l = store.latitude;
								ln= store.longitude;
								attachMarker( store,map,keyword,1);
								 
                             // google.maps.event.trigger(global_marker[0], 'click');
							}
							if(l!=0)
							{
								//map.setCenter(new google.maps.LatLng(l,ln));
								map.setZoom(10);
								
							    //infowindow.open(map, global_marker[0]); 
							} 
							// showStoreDetails(store_id,store);
							 
						}
					});
					
}

$(document).ready(function() {
	if(isStoreSearch == 0) {initialize();}
	    }
);	
 
 $(document).ready(function(e) {
	// initialize();
	  $("#adduser_phone").mask("(999) 999-9999");
	  $(".icn-closebtn").hide()
	  
	  $(document).on('click','#addEnquiry_button', addEnquiry);
	  $(document).on('click','#storecomments', loadReviews);
	  $(document).on('click','#sicon', showSativa);
	 /* $(document).on('click','.h-icon', showHydrid);
	  $(document).on('click','.i-icon', showIndica);*/

$('#search_box').on('input', function() {
    if($(this).val()=='')
		$(".icn-closebtn").hide()
	else
		$(".icn-closebtn").show()
});	 
$(".icn-closebtn").click(function(){
	$('#search_box').val('');
})	 

$( "#search_box" ).autocomplete({
		source: function( request, response ) {
			 var is_licensed='0';
 if($('#is_licensed').val() == 1){
	 is_licensed =1;
 }
        $.ajax({
			url: base_url+"harsha_ajax.php",
			data: 
			{
				type: $('#search_type').val(),
				q: $("#search_box").val(),
				is_licensed:'5'
			},
			dataType: "json",
			type: "POST",
			beforeSend:function(){
				$('#load1').show();
			},
			complete:function(){
				$('#load1').hide();
			},
			success: function( data ) {
				response(data);
			}
        })
      },
		minLength:2,
		select: function( event, ui ){
			var id = ui.item.id;
			  
			if($("#search_type").val()=='Stores') 
			{
				$.ajax({ url: location.href,
					data: { 
						lat:ui.item.lat,
						longi:ui.item.lng,
						action:'get_nearby_stores',
						ajax:1,
						id:ui.item.id,
						is_licensed:ui.item.is_licensed,
					  },
				//$.ajax({ 
//					url: "http://www.four20maps.com/",
//					data: { 
//						lat:ui.item.lat,
//						longi:ui.item.lng,
//						action:'get_nearby_stores',
//						ajax:1,
//						id:ui.item.name,
//						is_licensed:is_licensed
//						//id:ui.item.id
//					  },
						dataType: "json",
						type: "POST",
						success: function(data){
							var det = JSON.stringify(data);
							dat = JSON.parse(det);
							var keyword='';
							var stores = dat.stores;
							//var infowindow=[];
							var myLatlng = new google.maps.LatLng(ui.item.lat,ui.item.lng);
							var mapOptions = {
								 minZoom: 6, maxZoom: 15 ,
								center: myLatlng,
								zoomControl: false,
								mapTypeControl: false,
								streetViewControl: true,
								panControl: false,
								 styles: [{"featureType":"all","elementType":"labels.text.fill", "stylers":[{"saturation":36},{"color":"#a5a6a8"},{"lightness":2}]},
										{"featureType":"all","elementType":"labels.text.stroke","stylers":[{"visibility":"on"},{"color":"#45484D"},{"lightness":0}]},
										{"featureType":"all","elementType":"labels.icon","stylers":[{"visibility":"off"}]},
										{"featureType":"administrative","elementType":"geometry.fill","stylers":[{"color":"#45484D"},{"lightness":2}]},
										{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#45484D"},{"lightness":1},{"weight":1.2}]},
										{"featureType":"landscape","elementType":"geometry","stylers":[{"color":"#4F5660"},{"lightness":1}]},
										{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#4F5660"},{"lightness":2}]},
										{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#45484D"},{"lightness":6}]},
										{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#45484D"},{"lightness":6},{"weight":0.2}]},
										{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#45484D"},{"lightness":6}]},
										{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#45484D"},{"lightness":6}]},
										{"featureType":"transit","elementType":"geometry","stylers":[{"color":"#45484D"},{"lightness":19}]},
										{"featureType":"water","elementType":"geometry","stylers":[{"color":"#282A2E"},{"lightness":14}]}]
							  }
							map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
							
							var l=0; var ln=0;
							clearMarkers();
							for(x in stores)
							{
								var store = stores[x];
								store.id = store.id;
								l = store.latitude;
								ln= store.longitude;
								attachMarker( store,map,keyword ); 
							}
							if(l!==0)
							{
								//map.setCenter(new google.maps.LatLng(l,ln));
								map.setZoom(10);
								
							}
						}
					});
					
				}
				else
				{
					 $("#store_search").attr("value",ui.item.value);
					$("#store_search").val(ui.item.value);
					getStoresByProduct(ui.item.label);
				}
		}
	});
 
	$("#store_search").on('keyup',function(){
			var value = $(this).val().toLowerCase();
			if($('#store_products').children().length > 0)
			{
				//$('#pro_data').find('tr').css('display','none')
				//$('#nav').css('display','none')
			}
			var visible = [];
			$('#store_products').find('[name=product_row]').each(function(){
				if(value.trim()!='')
				{
					var cat_name= $(this).attr('cat_name').toLowerCase(); 
					if(cat_name.indexOf(value)> -1)
						$(this).css('display','block');
					else
						$(this).css('display','none');
				}
				else
				{
					$(this).css('display','block');
				}
				
			}) 
	});
	$(document).on('click', "[name=buy_product]", function(){
		var pname = $(this).attr('pname');
		var site = $(this).attr('site');
		$.ajax({
			url: base_url,
			data: {
				pname : pname,
				site : site,
				'recordBuy':1
			}
			
		});
		
	});
 });
 
 function getStoresByProduct(keyword)
 {
	if(keyword=='')
	{
		keyword = $("#search_box").val();
	}
 
	$.ajax({ url: base_url+"getStoresByProduct.php",
		data: { 
				keyword:keyword
			  },
		dataType: "json",
		type: "POST",
		beforeSend:function(){
			$('#ToggleMe').trigger('click');
			$("#nav").empty();
		},
		complete:function(){
			$('#ToggleMe').trigger('click');
		},

		success: function(data){
					var myLatlng = new google.maps.LatLng(54.81,40.35);
					 var mapOptions = {
						minZoom: 2, maxZoom: 15 ,
						zoom: 10,
						center: myLatlng,
						zoomControl: false,
						mapTypeControl: false,
						streetViewControl: false,
						panControl: false,
						 styles: [{"featureType":"all","elementType":"labels.text.fill", "stylers":[{"saturation":36},{"color":"#a5a6a8"},{"lightness":2}]},
								{"featureType":"all","elementType":"labels.text.stroke","stylers":[{"visibility":"on"},{"color":"#45484D"},{"lightness":0}]},
								{"featureType":"all","elementType":"labels.icon","stylers":[{"visibility":"off"}]},
								{"featureType":"administrative","elementType":"geometry.fill","stylers":[{"color":"#45484D"},{"lightness":2}]},
								{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#45484D"},{"lightness":1},{"weight":1.2}]},
								{"featureType":"landscape","elementType":"geometry","stylers":[{"color":"#4F5660"},{"lightness":1}]},
								{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#4F5660"},{"lightness":2}]},
								{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#45484D"},{"lightness":6}]},
								{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#45484D"},{"lightness":6},{"weight":0.2}]},
								{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#45484D"},{"lightness":6}]},
								{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#45484D"},{"lightness":6}]},
								{"featureType":"transit","elementType":"geometry","stylers":[{"color":"#45484D"},{"lightness":19}]},
								{"featureType":"water","elementType":"geometry","stylers":[{"color":"#282A2E"},{"lightness":14}]}]
					  }
					  map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
					var l=0;
					var ln=0;
					var det = JSON.stringify(data);
					dat = JSON.parse(det);
					clearMarkers()
					for(x in dat)
					{
						var store = dat[x];
						l = store.lat;
						ln = store.lng;
						//store.img = "http://four20maps.com/admin/imgs/stores/"+store.id+"/ghghg.jpg"+store.image;
						attachMarker( store,map,keyword );
					}
					if(l!=0)
						map.setCenter(new google.maps.LatLng(l,ln));
				
		}
	});
 }
 function getProductsByStore(store_id,keyword,website)
 {
	 var html='';
	 $.ajax({ url: base_url+"getProductsByStore.php",
			data: { 
					q:store_id,
					keyword:keyword
				  },
			dataType: "json",
			type: "POST",
			beforeSend:function(){
				$("#store_products").html("<img id='loadingImg' style='margin-left:40%; height:80px' src='img/ajaxloader.gif'>");
				$("#nav").empty();
			},
			complete:function(){
				$('#loadingImg').hide();
			},
			
			success: function(returndata)
			{ 	mainCategories = returndata.mainCategories
				var mainHtml = '';
				for(item in mainCategories)
				{
				var cattids = mainCategories[item].Catids;
				var cattname = mainCategories[item].name;
				var orderids = mainCategories[item].orderid;
				mainHtml += '<li><a href="javascript:" rel="'+orderids+'" onclick="mainfilter(\''+cattids+'\')">'+cattname+'</a></li>';
				}
				$('#category-menu').html(mainHtml);
				if(mainHtml != "")
				$('#category-menu-div').show()
				else
				$('#category-menu-div').hide()


				var parentCatID = '';
				var data = returndata.products
				if(returndata.website1)
					var web = returndata.website1.web
				for(item in data)
				{
				var catid = data[item].catid;
				var catname = data[item].catname;
				var products = data[item].products;
				//var catname = data[item].ParentcategoryName;
				var types='';
				var optionsstr='<div class="product-itemwps-bx prditmsbxwdful"><div class="product-itemwps prdctitem_optiontype">';
				var image='';
				var categoryType ='';
				
				//types+="<div style='width:100%'>";
				for(product in products)
				{
				var pname = products[product].pname;
				var productDescription = products[product].productDescription;
				var ProductID = products[product].ProductID;
			 	parentCatID = products[product].ParentcategoryID;
				var price = products[product].price;
				var stock = products[product].AvailableStock;
                if(website.indexOf("http://")>=0)
					website = website;
				else
					website = 'http://'+website;

				if(parseInt(stock)>0)
				var but = "<a name='buy_product' site='"+website+"' pname='"+catid+"' class='btn btn-primary btn-sm btn-buy-item' target='_blank' href='"+website+"/index.php/main/addtocart/"+ProductID+"'>Donate</a>";
				else
				var but="Donation Not Available";
				var other_img = products[product].image;
				image = web+'/'+products[product].image;
			 	if(other_img.indexOf("https")>=0)
						var image1 = other_img; 
				else if(image.indexOf("http://")>=0)
					var image1 = image; 
				else
					var image1 = "http://"+image;
				
				categoryType = products[product].categoryType;
				types+="<div class='product-itemwps-bx prdctitemtypeby col-lg-2 col-md-2 col-sm-2 col-xs-4' name='product'><div class='product-itemwps'>"+
				"<p class='pritem-name' name='"+pname+"'>"+pname+"</p>"+
				"<span class='pritem-price'>$"+price+"</span>"+ but+"</div></div>";


				}
				//types+="</div>";
				var options = data[item].options;
				for(option in options)
				{
				var optionType = options[option].optionType;
				var value = options[option].value;
				if("Indoor/Outdoor"==optionType)
				optionsstr+="<div class='prd-optsbx-txt mbwdfl'>"+value+"</div>";
				else
				optionsstr+="<div class='prd-optsbx-txt'><span class='prd-optsbx-txt-optype'>"+optionType+" :</span> <span class='prd-optsbx-txt-sb'>"+value+"</span></div>";
				}
				var errimg = '$(this.src="noimg1.gif")';
				optionsstr+='</div></div>';
				html+='<div class="product-rowwps" name="product_row"'+
				' cat_type="'+categoryType+'"'
				+' parentCatID="'+parentCatID+'" '
				+'cat_name="'+catname+'" style="/*height:170px;background:#b1b1b1;border-radius:6px;width:930px;margin-left:2px*/">'+
				'<div class="row">'+
				'<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">'+
				'<div class="row">'+
				'<div class="col-lg-4 col-md-4 col-sm-4 col-xs-6 productDescriptionModalPreview" data-id="'+ProductID+'" data-toggle="modal" data-target="#productDescriptionModal"><img class="vimage" id="js_product_image_'+ProductID+'" src="'+image1+'"/></div>'+
				'<div class="col-lg-4 col-md-4 col-sm-4 col-xs-6"><div id="js_product_name_'+ProductID+'" class="res_catheaditle">'+catname+'</div></div>'+
				'<div id="js_product_descrition_'+ProductID+'" style="display:none;">' + productDescription + '</div>'+
				'<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" id="js_product_options_'+ProductID+'">'+optionsstr+'</div>'+
				'</div>'+
				'</div>'+
				'<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">'+
				'<div class="row">'+types+'</div>'+
				'</div>'+
				'</div>'+'</div>'; 
				}
				$('#vimage').attr('onerror',errimg);

				$('#store_products').html(html);				
	
				$rows   = $('#store_products > .product-rowwps');
				$rows .sort(function (a, b) {
				var contentA =parseInt( $(a).attr('parentcatid'));
				var contentB =parseInt( $(b).attr('parentcatid'));
				return (contentA < contentB) ? -1 : (contentA > contentB) ? 1 : 0;
				})
				$("#store_products").empty()
				$("#store_products").append($rows)
				//$("#nav").remove();
				//  $('#store_products').after('<div id="nav" class="pagenations-wps"></div>');
				//var rowsShown = 10;
				//var rowsTotal = $('#pro_data tbody tr').length;
				//var numPages = rowsTotal/rowsShown;
				//for(i = 0;i < numPages;i++) {
				//	var pageNum = i + 1;
				//	$('#nav').append('<a href="#" rel="'+i+'">'+pageNum+'</a> ');
				//}
				//$('#store_products tbody tr').hide();
				//$('#store_products tbody tr').slice(0, rowsShown).show();
				//$('#nav a:first').addClass('active');
				/*$('#nav a').bind('click', function(){

				$('#nav a').removeClass('active');
				$(this).addClass('active');
				var currPage = $(this).attr('rel');
				var startItem = currPage * rowsShown;
				var endItem = startItem + rowsShown;
				$('#store_products tbody tr').css('opacity','0.0').hide().slice(startItem, endItem).
				css('display','table-row').animate({opacity:1}, 300);
				});*/
				$("#store_search").trigger('keyup')
				if(!returndata.website1)
					$('#store_products').append("<p style='font-size:20px; text-align:center'>No Products Available..</p>");
			}
		});
 }
 function zoomMap(lat,longi)
 {
	/* var myLatlng = new google.maps.LatLng(lat,longi);
		  var mapOptions = {
			zoom: 17,
			center: myLatlng
		  }*/
		 
 }
 function createStreetMap(lat,longi )
{
	var panorama;
	var strMapCanvasID = 'map-canvas';
	var yourLatLng = new google.maps.LatLng(lat,longi);
	//once the document is loaded, see if google has a streetview image within 50 meters of the given location, and load that panorama
	var sv = new google.maps.StreetViewService();

	sv.getPanoramaByLocation(yourLatLng, 50, function(data, status) {
		if (status == 'OK') {
			//google has a streetview image for this location, so attach it to the streetview div
			var panoramaOptions = {
				enableCloseButton: true,
				pano: data.location.pano,
				addressControl: false,
				navigationControl: true,
				linksControl: true,
				navigationControlOptions: {
					style: google.maps.NavigationControlStyle.SMALL
				}
			}; 
			var panorama = new google.maps.StreetViewPanorama(document.getElementById(strMapCanvasID), panoramaOptions);

 
			// lets try and hide the pegman control from the normal map, if we're displaying a seperate streetview map
		/*	map.setOptions({
				streetViewControl: false
			});*/
		}
		else{
			//no google streetview image for this location, so hide the streetview div
			$('#' + strMapCanvasID).parent().hide();
		}
	});
}
$(".lirate").hover(function(){
	$("#rchange").attr("data-rate",$(this).attr("value")) 
	var rate = $(this).attr("value")
		if(rate == 1)
			$("#rchange").find('#SpanText').html('Eek! Methinks not.');
		if(rate == 2)
			$("#rchange").find('#SpanText').html("Meh. I've experienced better.");
		if(rate == 3)
			$("#rchange").find('#SpanText').html('A-OK.');
		if(rate == 4)
			$("#rchange").find('#SpanText').html("Yay! I'm a fan.");
		if(rate == 5)
			$("#rchange").find('#SpanText').html('Woohoo! As good as it gets!');
});

function initialize() { 
 
	var mapCanvas = document.getElementById('map-canvas');
	var mapOptions = {
	  center: new google.maps.LatLng(lat, lng),
	   minZoom: 2, maxZoom: 35 ,zoom: inizoom,
	    zoomControl: false,
		mapTypeControl: false,
		streetViewControl: true,
		 panControl: false,
	  mapTypeId: google.maps.MapTypeId.ROADMAP,
	  styles: [{"featureType":"all","elementType":"labels.text.fill", "stylers":[{"saturation":36},{"color":"#a5a6a8"},{"lightness":2}]},
				{"featureType":"all","elementType":"labels.text.stroke","stylers":[{"visibility":"on"},{"color":"#45484D"},{"lightness":0}]},
				{"featureType":"all","elementType":"labels.icon","stylers":[{"visibility":"off"}]},
				{"featureType":"administrative","elementType":"geometry.fill","stylers":[{"color":"#45484D"},{"lightness":2}]},
				{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#45484D"},{"lightness":1},{"weight":1.2}]},
				{"featureType":"landscape","elementType":"geometry","stylers":[{"color":"#4F5660"},{"lightness":1}]},
				{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#4F5660"},{"lightness":2}]},
				{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#45484D"},{"lightness":6}]},
				{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#45484D"},{"lightness":6},{"weight":0.2}]},
				{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#45484D"},{"lightness":6}]},
				{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#45484D"},{"lightness":6}]},
				{"featureType":"transit","elementType":"geometry","stylers":[{"color":"#45484D"},{"lightness":19}]},
				{"featureType":"water","elementType":"geometry","stylers":[{"color":"#282A2E"},{"lightness":14}]}]
 	}
	map = new google.maps.Map(mapCanvas, mapOptions);
	map.addListener('zoom_changed', function() { 
	});
	clearMarkers();
	  
	$.map(default_stores, function(item){
		 
		 attachMarker(item,map,"");
		 
	});
	 
}


function attachMarker( store,map,keyword,short_url=0 ) {
	$('#rating_form').find('input:radio').each(function(){
		   $(this).removeAttr("checked")
	}); 
	var email = store.email;
	var website = '';
	if(store.website.indexOf("http://")>=0 || store.website.indexOf("https")>=0)
		website = store.website;
	else  
		website ="http://"+store.website;
	var telphone = '';
		if(store.telephone.trim().length >0)
		{
			telphone = store.telephone;
		}
		else if(store.mobile.trim().length >0)
		{
			 telphone = store.mobile;
		}
		else
		{
			telphone = '';
		} 
		if(typeof store.NAME != "undefined")
	    store.name=store.NAME;
		if(typeof store.store_cat_name != "undefined")
	    store.cat_name=store.store_cat_name;
		if(store.image.indexOf("http://")>=0)
			var simage = store.image; 
		else
			var simage = base_url+store.image;
		var errimg = "$(this.src='store.png')";
		//var phone = telphone.replace(/[&\/\\#,()$~%. '":*?<>{}-]/g, '');
	var phone = telphone;
		 var contentString = '<div class="maps_popups" id="store_map_popup_'+store.id+'" style="/*max-width:250px*/"><img id="Store_imG'+store.id+'" style="margin:0 0px 10px 10px; float:right !important; max-width:70px; max-height:70px;" alt="'+store.name+'" src="'+simage+'" Onerror = '+errimg+' class="img">'+
		'<h1>'+store.name+'</h1><p class="mps-addrss">'+store.address+' </p><p class="tel"><label>Telephone:</label> <a href="tel:['+phone+']">'+phone+'</a></p><p class="email"><label>Email:</label> '+
		'<a href="mailto:'+email+'">'+email+'</a></p>'+
		'<p class="web"><label>Website:</label> <a href="'+website+'" target="_blank" style="color:blue">'+website+'</a></p>'+
		'<p class="description">'+store.description+'</p><div class="products" style="font-weight:bold">';
		if(store.ctype_icon!=null)
		{
			 
			if(store.ctype_icon.indexOf("http://")>=0)
					var ctype_iconNew = store.ctype_icon; 
				else
					var ctype_iconNew = base_url+"admin/"+store.ctype_icon;
		}	
	 
		if(store.cat_name!='' && store.ctype_icon!=null)
		{
			contentString+='<img id="ContentCatImg" style="max-width:32px; max-height:32px;margin-right:5px;" src="'+ctype_iconNew+'" >'+store.cat_name;	
		}			
		
		contentString+='<div class="circle map_popup_hits"></div></div>'+
		'<span class="email"><center><a style="display:inline-block;font-size:13px;padding:5px 10px;margin-top:10px;margin-bottom:10px;margin-left:3px;'+
		'border:1px solid #8b8b8b;text-align: center;font-weight:bold;width:auto;" class="contact-clinic button blue-button" '+
		'href="mailto:'+email+'"> <span class="mobile-hidden-xs">Contact this store</span><span class="mobile-visible-xs">Contact store</span></a><a style="display:inline-block;font-size:13px;padding:5px 10px;'+
		'margin-top:10px;margin-bottom:10px;margin-left:3px;border:1px solid #8b8b8b;text-align: center;'+
		'font-weight:bold;width:auto;" class="contact-clinic button blue-button scrolldown" '+
		'>Menu</a></center></span>';
		
		var str = '<a class="ft-acolr" href="javascript:createStreetMap('+store.latitude+','+store.longitude+');">'+
								'Street view</a> | <a class="ft-acolr" href="javascript:zoomMap(map,'+store.latitude+','+store.longitude+');">Zoom</a> | '+
								'<a class="ft-acolr" href="javascript:direction(map,&quot;hyderabad&quot;,'+store.latitude+','+store.longitude+');">Directions</a></div>';
								
		if(store.cat_icon)
		{
			if(store.cat_icon.indexOf("http://")>=0)
					var icon = store.cat_icon; 
				else
					var icon = base_url+"admin/"+store.cat_icon;
		}
		else
			var icon = base_url+"admin/imgs/caticons/1440221344Delivery%20Truck%20-%20160%20-%20PREMIER%20-%20SMALL.png";
	   
	var icon1 = new google.maps.MarkerImage(
           icon, //url
            new google.maps.Size(80, 100) //size
		 );	
			var indexZ = '';
			var maxZindex = google.maps.Marker.MAX_ZINDEX;
				if(store.OrderId > 0)
					indexZ = parseInt(maxZindex)*parseInt(store.OrderId);
				else
					indexZ = maxZindex
			
			
			
		 var marker = new google.maps.Marker({
			map: map,
			position: new google.maps.LatLng(store.latitude, store.longitude),
			store_id: store.id,
			//keyword: keyword,
			website: store.website,
			icon:icon1,
			zIndex : indexZ
			});
			
			 global_marker.push(marker);
	
	          liTag=$("body ul.utilities").find("[rel='" + store.id + "']"); 
			// show info window when marker is clicked
			$(liTag).click(function() {
				//searchbystorename(store.latitude,store.longitude,store.name)
				 infowindow.setContent(contentString+str);
				infowindow.open(map, marker);
				 map.panTo(marker.getPosition());
			  showStoreDetails(marker.store_id,store);
			}); 
			 google.maps.event.addListener(marker, 'click', function() {
				infowindow.setContent(contentString+str);
				infowindow.open(map, this);
				// getProductsByStore(marker.store_id,marker.keyword,marker.website);
				 getStoreRating(marker.store_id);
				 //addViewForStore(marker.store_id);
				 
				// getStoreViews(marker.store_id);
				 
				 showStoreDetails(marker.store_id,store); 
				 if(isStoreSearch == 1) {
					 infowindow.setContent(contentString+str);
			         infowindow.open(map, marker)
				 }
				var options = {
            imagePath: 'images/m'
        };
        
        //var markerCluster = new MarkerClusterer(map, global_marker, options); 
				  google.maps.event.addDomListener(window, 'load', initialize);
			 	/*else
					$('#store_information').find('.store_image').attr('src',store.img);*/
				$('#store_information').find('[name=store_desc]').text(store.description);
				$('#store_information').find('[name=store_name]').text(store.name);
				$('#store_information').find('[name=store_addr]').text(store.address); 
			  });
 	if(short_url){
	  		   setTimeout(function () {
			  infowindow.setContent(contentString+str);
		      infowindow.open(map, marker);
			  //map.panTo(marker.getPosition());
			  showStoreDetails(marker.store_id,store);
			  }, 2000);
		}
	}

	function clearMarkers()
	{
	 	for(i=0; i<global_marker.length; i++)
		{
        	global_marker[i].setMap(null);
    	}
 	}
function showSearchStoreDetails(store_id,store)
	{
	    getProductsByStore(store_id, '', store.website);
		getStoreRating(store_id); 
		addViewForStore(store_id);
		getStoreViews(store_id); 
	 	showStoreDetails(store_id,store); 
		 if(isStoreSearch == 0) {
			// scrollBottom();	
		 }
	}
function getById(arr, id) { 
     for (var d = 0, len = arr.length; d < len; d += 1) {
        if (arr[d].id === id) {
            return arr[d][0];
        }
    } 
	 
}
 
	function showStoreDetails(store_id,store)
	{  
		var website = '';
	if(store.website.indexOf("http://")>=0 || store.website.indexOf("https")>=0)
		website = store.website;
	else  
		website ="http://"+store.website;
		 //store =search(store_id, default_stores); 
		getProductsByStore(store.id, '', store.website);
		getStoreRating(store);		
		addViewForStore(store.id);
		getStoreViews(store.id); 
		$('.searchindi').css('display',"block");
		$('#rateus').css('display',"block");
		$('#storecomments').css('display',"block");
		$('#filter_search_res').css('display',"block");
		$('.srchfilter_wrps').css('display',"block");
		$('.resultsfbx_wrps').css('display',"block");
		$('#store_information').find('[name=store_id]').html(store.id);
		if(store.createdby!='')
		{
			if(store.image!='')
			{
				 if(store.image.indexOf("http://")>=0)
					var storeimg = store.image;
				else
					var storeimg =base_url+store.image;
				$('#store_image').attr('src',storeimg);
				var errimg = '$(this.src="store.png")';
				$('#store_information').find('.store_image').attr('onerror',errimg);
			}
			else
				$('#store_information').find('.store_image').attr('src',base_url+'store.png');

		}
        $('.h3_headtitle').text(store.name);
		$('#store_information').find('[name=store_desc]').text(store.description);
		$('#store_information').find('[name=store_name]').text(store.name);
		$('#store_information').find('[name=store_addr]').text(store.address);
		 	if(store.first_time_patients !== null){
			$('#js_first_time_patients div.panel-body').html('<h3>First-Time Patients</h3><p>' + store.first_time_patients + '</p>');
		} else {
			$('#js_first_time_patients div.panel-body').html('<h3>First-Time Patients</h3><p> No data found.</p>');
		}
		if(store.announcement !== null){
			$('#js_announcement div.panel-body').html('<h3>Announcement</h3><p>' + store.announcement + '</p>');
		}else{
			$('#js_announcement div.panel-body').html('<h3>Announcement</h3><p>No data found.</p>');
		}
		if(store.about_us !== null){
			$('#js_about_us div.panel-body').html('<h3>About Us</h3><p>' + store.about_us + '</p>');
		}else{
			$('#js_about_us div.panel-body').html('<h3>About Us</h3><p>No data found.</p>');
		}
		if(store.telephone !="")
	 	$("#js-review-phone").html(store.telephone);
		else
		$("#js-review-phone").html(store.mobile);
		$("#js-review-email").html(store.email);
		$("#js-member-since").html(store.created);
		$("#js-review-email").attr('href', 'mailto:'+store.email);
		$("#js-review-website").attr('href', website);
		$("#js-review-website").html(website);
	 var js_timings = ''; 
		
		if(store.timings !== null) {
		 	if(!(typeof store.timings == "string"))
			store.timings=JSON.stringify(store.timings);
			console.log(store.timings);
			$.each(JSON.parse(store.timings), function(i, item) { 
				js_timings += '<li>'; 
				js_timings += i ; 
				if(item.is_closed){
					js_timings +=  ': Closed';
				}
				else
					{
						
						//js_timings += '</span>'; 
						js_timings +=    ' : '+item.starttime+ ' - ' 
						js_timings +=  item.endtime;
						
				}
			});
		}
		
		if(js_timings !== ''){
			$('ul#js_timings').html(js_timings);
		}else{
			$('ul#js_timings').html('No data found.');
		} 
	
	
		//$("#js-review-website").attr('href', store.website);
		
		loadStoreReviews(store_id);
		 		
	}
 
	function jslogin()
	{
		var username = $('#loginuser').val();
		var password = $('#loginpass').val();
		if(username=='')
		{
			$('#loginuser').css("border",'red');
			$('#loginuser').focus();
			return false;
		}
		else
			$('#loginuser').css("border",'');
		
		if(password=='')
		{
			$('#loginpass').css("border",'red');
			$('#loginpass').focus();
			return false;
		}
		else
			$('#loginpass').css("border",'');
		
		   if((username!='') && (password!=''))
		   {
				$.ajax({
					type: 'POST',
					url: base_url+'ajlogin.php',
					data: {'username':username,'password':password},
					//dataType: "json",
					success: function(data)
					{
						data=data.trim();
						if(data == 1)
						{
							$('#loginmsg').removeClass('alert-danger');
							$('#loginmsg').addClass('alert-success');
							document.getElementById('loginmsg').innerHTML = "Login Successful. Please wait..";
							$('#loginmsg').show();
							window.location.reload();
						}
						else if(data == 2)
						{
							$('#loginmsg').removeClass('alert-danger');
							$('#loginmsg').addClass('alert-success');
							document.getElementById('loginmsg').innerHTML = "Login Successful. Please wait..";
							$('#loginmsg').show();
							window.location.href =base_url+'stores.php';
						}
						else
						{
							$('#loginmsg').removeClass('alert-success');
							$('#loginmsg').addClass('alert-danger');
							document.getElementById('loginmsg').innerHTML = "Invalid Login Details";
							$('#loginmsg').show();
							setTimeout(function (){$('#loginmsg').hide(); }, 4000);
						}
					}
				});
			   
		   }
		   return false;
	}
  
   function frgtuser()
   {
	var frgtuser = $('#frgtemailid').val();
	if(frgtuser=='')
	{
		$('#frgtemailid').css("border",'1px solid red');
		$('#frgtemailid').focus(); 
		return false;
	}
	else
	{
		var type = $('#type').val();
		 $.ajax({
			type: 'POST',
			url: base_url+'forgotemail.php',
			data:{'email':frgtuser, 'type':type},
			success: function(data)
			{
				if(data == '1') 
				{
					$('#frgtUalert').removeClass('alert-danger');
					$('#frgtUalert').addClass('alert-success');
					$('#frgtemailid').val('');
					document.getElementById('frgtUalert').innerHTML = "A mail has been sent to your Email..";
					$('#frgtUalert').show();
					setTimeout(function (){$('#frgtUalert').hide(); }, 4000);
					
				}
				else
				{
					$('#frgtUalert').removeClass('alert-success');
					$('#frgtUalert').addClass('alert-danger');
					document.getElementById('frgtUalert').innerHTML = "Invalid Email ID";
					$('#frgtUalert').show();
					setTimeout(function (){$('#frgtUalert').hide(); }, 4000);
				}
			}
		 });
	}
		
   }
   
   function userlogin()
   {
	 
	var regfname = $.trim($('#regfname').val());
	var reglname = $.trim($('#reglname').val());
	var regemail = $.trim($('#regemail').val());
	var reguser = $.trim($('#reguser').val());
	var regpassword = $.trim($('#regpassword').val());
	var regaddress = $.trim($('#regaddress').val());

   if((regfname=='')|| (reglname=='') || (regemail=='') || (reguser=='') || (regpassword=='') || (regaddress==''))
   {
	   
	if(regfname=='')
	{
		$('#regfname').css("border",'1px solid red');
		$('#regfname').focus(); 
		return false;
	}
	if(reglname=='')
	{
		$('#reglname').css("border",'1px solid red');
		$('#reglname').focus(); 
		return false;
	}
	eMailPattern = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
	if(!eMailPattern.test(regemail))
	{
		$('#regemail').css("border",'1px solid red');
		$('#regemail').focus(); 
		return false;
	}
	if(reguser=='')
	{
		$('#reguser').css("border",'1px solid red');
		$('#reguser').focus(); 
		return false;
	}
	if(regpassword=='')
	{
		$('#regpassword').css("border",'1px solid red');
		$('#regpassword').focus(); 
		return false;
	}
	if(regaddress=='')
	{
		$('#regaddress').css("border",'1px solid red');
		$('#regaddress').focus(); 
		return false;
	}
	return false;
   }
	else
	{
		 
		var regfname = $('#regfname').val();
		var reglname = $('#reglname').val();
		var regemail = $('#regemail').val();
		var reguser = $('#reguser').val();
		var regpassword = $('#regpassword').val();
		var regaddress = $('#regaddress').val();
		 $.ajax({
			type: 'POST',
			url: base_url+'reg.php',
			data:{'regfname':regfname,'reglname':reglname,'regemail':regemail,'reguser':reguser,'regpassword':regpassword,'regaddress':regaddress,},
			success: function(data)
			{
				if(data == 1) 
				{
					alert('Registration Successfull');
					location.reload(); 
				}
				else
				{
					alert('User name OR Email ID Already Exsists');
				}
			}
		 });
	}
		
   }

	function addViewForStore(store_id)
	{
		$.ajax({
			type: 'POST',
			url: base_url,
			data: {'storeid':store_id,'addViewForStore':1},
			//dataType: "json",
			success: function(data)
			{
				
			}
		});
	}
	
	function getStoreViews(store_id)
	{
		$.ajax({
			type: 'POST',
			url: base_url,
			data: {'storeid':store_id,'getStoreViews':1},
			//dataType: "json",
			success: function(data)
			{
				$("#store_map_popup_"+store_id).find(".map_popup_hits").html( $.trim(data) + ' Hits');
				$("#store_map_block_"+store_id).find(".map_block_hits").html( $.trim(data) + ' Hits');
			}
		});
	}
   
   function getStoreRating(store_id)
   {
	   $.ajax({
			type: 'POST',
			url: base_url,
			data: {'storeid':store_id,'getStoreRating':1},
			//dataType: "json",
			success: function(data)
			{
				$('[name=store_rating]').html('');
				data = data.trim();
				data = $.parseJSON(data);
				$('[name=store_rating]').show();
				$('[name=rate_us_store]').show();
				$('#store_information').show();
				$('#review_customermh').show();
				$('.adsfeature_wrps').show();
				if(data.avg != '')
				{
					$('#star'+data.avg).attr('checked', true);
					$('[name=store_rating]').html("<div class='store-rating-starwrps rating' data-rate='"+data.avg+"'><i class='star-1 fa fa-star'></i><i class='star-2 fa fa-star'></i><i class='star-3 fa fa-star'></i><i class='star-4 fa fa-star'></i><i class='star-5 fa fa-star'></i></div>");
					$("#storecomments").html("Reviews ("+data.total+")")
				}
				else
				{
					$('#star'+data.avg).attr('checked', false);
					$("#storecomments").html("Reviews")
				}
				$('#rating_form').find('#StoreRatingImg').attr('src','');
				$('#rating_form').find('#RatingStoreName').html('');
				$('#rating_form').find('#RatingStoreName').html($('#store_information').find("[name='store_name']").html());
				$('#rchange').attr('data-rate','');
				$("#rchange").find('#SpanText').html('Select your rating.');	
				$('#rating_form').find('#StoreRatingImg').attr('src','');
				var errimg = '$(this.src="noimg1.gif")';
				$('#rating_form').find('#StoreRatingImg').attr('src',errimg);
				$('#rating_form').find('#StoreRatingImg').attr('src',$('#store_image').attr('src'));
			}
		});
   }
   
   function saveRating(uid){
	   var rating = $('#rchange').attr("data-rate");
	   var store_id = $('[name=store_id]').text();
	   var rating_comments = $('[name=rating_comments]').val();
	   if(typeof(rating)!="undefined")
	   {
		   $.ajax({
				type: 'POST',
				url: base_url,
				data: {	
						'storeid':store_id,
						'setStoreRating':1,
						rating: rating,
						comments: rating_comments,
						uid: uid},
				//dataType: "json",
				success: function(data)
				{
					alert("Rating Posted Successfully..!!");
					getStoreRating(store_id);
					loadStoreReviews(store_id);
				}
			});
			$('#rating_form').trigger('reset');
			$("#myModal5").find('.close').trigger('click');
			return false;
	   }
	   else
		   alert("Please select stars as rating.");
   }
   
   function updatereg()
   {
	   
	var idd = $('#idd').val();
	var firstname = $('#firstname').val();
	var lastname = $('#lastname').val();
	var address = $('#addressm').val();
	//var password = $('#passwordr').val();
	
	   if(idd)
	   {
		   	$.ajax({
			type: 'POST',
			url: base_url+'jsupdate.php',
			data: {'idd':idd,'firstname':firstname,'lastname':lastname,'address':address},
			//dataType: "json",
			success: function(data)
			{
				if(data)
				{
			 $('#myModal3').hide();
			 alert("Data Successfully saved");
			 location.reload();
			 }
				
			}
			});
		   
	   }
   }
   
  
   function addEnquiry(event)
   {
		if($("#adduser_name").val()=='')
		{
			$("#adduser_name").css('border','1px solid red');
			return false;
		}
		else
			$("#adduser_name").css('border','');
		
		if($("#adduser_phone").val()=='')
		{
			 $("#adduser_phone").css('border','1px solid red');
			 return false;
		}
		else
		{
			var pattern = /^\d{10}$/;
			var val = $("#adduser_phone").val();
			val =val.replace('-', '');
			val =val.replace('(', '');
			val =val.replace(')', '');
			val =val.replace(' ', '');
			if (pattern.test(val))
				$("#adduser_phone").css('border',''); 
			else
			{
				$("#adduser_phone").css('border','1px solid red');
				return false;
			}
		}	
		
		if($("#adduser_url").val()=='')
		{
				var url = /^((https|http|ftp)\:\/\/)?([a-z0-9A-Z]+\.[a-z0-9A-Z]+\.[a-z0-9A-Z]+\.[a-zA-Z]{2,4}|[a-z0-9A-Z]+\.[a-z0-9A-Z]+\.[a-zA-Z]{2,4}|[a-z0-9A-Z]+\.[a-zA-Z]{2,4})$/i;
				var web = $('#adduser_url').val();
				if(!url.test(web))
				{
					$('#adduser_url').css("border","1px solid red");
					$('#adduser_url').val('');
					alert('Enter a valid URL');
				}
				else
					$('#website').css("border"," ");
		}
		else
		{
			if(isValidURL($("#adduser_url").val()))
			$("#adduser_url").css('border','');
			else
			{
				$("#adduser_url").css('border','1px solid red');
				return false;
			}
		}
			
		
		if($("#adduser_email").val()=='')
		{
			$("#adduser_email").css('border','1px solid red');
			return false;
		}
		else
		{
			if( !isValidEmailAddress( $("#adduser_email").val() ) )
			{
				$("#adduser_email").css('border','1px solid red');
				return false;
			}
			else
			$("#adduser_email").css('border','');
		}
		
		if($("#adduser_image").val()=='')
		{
			$("#adduser_image").css('border','1px solid red');
			return false;
		}
		else
		$('#adduser_image').css('border','');
		
		
		
		var data = new FormData();
		  data.append('name', $("#adduser_name").val());
		  data.append('email', $("#adduser_email").val());
		  data.append('phone', $("#adduser_phone").val());
		  data.append('url', $("#adduser_url").val());
		  data.append('file', $("#adduser_image")[0].files[0]);
		   $.ajax({
				url: base_url+'addss.php',
				type: 'POST',
		   data: data,
				cache: false,
				dataType: 'json',
				processData: false, // Don't process the files
				contentType: false,
				enctype: 'multipart/form-data',
				beforeSend: function(){
					$('#addEnquiry_button').attr('disabled','disabled');
					$('#addEnquiry_button').parent().append("<img src='img/ajax-loader.gif'>")
				},
				complete: function(){
					$('#addEnquiry_button').parent().find('img').remove()
				},
				success: function(data, textStatus, jqXHR)
				{
					if(data=='1')
					{
						$('#addEnquiry_button').removeAttr("disabled");
						$("#myModal4").find('.close').trigger('click');
						$("#add_form").trigger('reset');
						alert("Success");
						return;
					}
					else if(data=='3')
					{
						$('#addEnquiry_button').removeAttr("disabled");
						$('#adduser_image').val('');
						alert("File type should be PNG, JPEG or GIF");
						return;
					}
					else
					{
						$('#addEnquiry_button').removeAttr("disabled"); 
						alert("Error in sending enquiry!!");
						return;
					}
					
				}
		   });
		
   }
   
   function isValidEmailAddress(emailAddress) {
		var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
		return pattern.test(emailAddress);
	}
	
	function isValidURL(url){
		var RegExp = /(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
		if(RegExp.test(url))
			return true;
		else
			return false;
    } 
	
	function fp(){
		
		$("#myModal1").find('.close').trigger('click');
	}
	
	function forgotpass()
	{
		$('#submit_login').hide();
		$('#passimg').show();
		var type = $('#typeforgot').val();
		var email = $.trim($("#Email_for").val());
		if(email!='')
		{
			$("#Email_for").css("border-color","");
			$.ajax({
				url: base_url+'forgot.php',
				type: "POST",
				data: {email:email, type:type},
				/*beforeSend: function(){
					$("[name=doFPsubmit]").parent().append("<img src='img/ajax-loader.gif'>")
				},*/
				success: function(data)
				{
					if(data == 1)
					{
						$('#frgtmsg').removeClass('alert-danger');
						$('#frgtmsg').addClass('alert-success');
						document.getElementById('frgtmsg').innerHTML = "A mail has been sent to your Email..";
						$('#frgtmsg').show();
						setTimeout(function (){$('#frgtmsg').hide(); }, 4000);
						$('#passimg').hide();
						$('#submit_login').show();
						$('#Email_for').val('');
						//$("[name=doFPsubmit]").parent().remove("<img src='img/ajax-loader.gif'>");
					}
					else
					{
						$('#frgtmsg').removeClass('alert-success');
						$('#frgtmsg').addClass('alert-danger');
						document.getElementById('frgtmsg').innerHTML = "Invalid Email ID..";
						$('#frgtmsg').show();
						setTimeout(function (){$('#frgtmsg').hide(); }, 4000);
						$('#passimg').hide();
						$('#submit_login').show();
						$('#Email_for').val('');
						//$("[name=doFPsubmit]").parent().remove("<img src='img/ajax-loader.gif'>");		
					}
				}
			});
		}
		else
		{
			$("#Email_for").css("border-color","red");
			$('#passimg').hide();
			$('#submit_login').show();
		}
		
	}
	
	function showSativa()
	{
		filter($(this).attr("name"))
	}
	
	/*function showHydrid()
	{
		filter("Hybrid")
	}
	
	function showIndica()
	{
		filter("Indica")
	}*/
	
	function mainfilter(maincatid)
	{
		if($('#store_products').children().length > 0)
		{
			$('#store_products').find('[name=product_row]').css('display','none');
		}
		$('#store_products').find('[name=product_row]').each(function(){
			var parentcatid = $(this).attr('parentcatid');				
			if(parentcatid==maincatid)
				$(this).css('display','block');
			else
				$(this).css('display','none');
		});
	}
	
	function filter(value)
	{
		//alert(value)
		value = value.toLowerCase();
		if($('#store_products').children().length > 0)
		{
			$('#store_products').find('[name=product_row]').css('display','none')
			
		}
		$('#store_products').find('[name=product_row]').each(function(){
			var cat_type= $(this).attr('cat_type').toLowerCase();				
			if(cat_type.toLowerCase()==value)
				$(this).css('display','block');
			else
				$(this).css('display','none');
		});
	}
	
	function loadReviews()
	{
		 var store_id = $('[name=store_id]').text();
		 $.ajax({
			type: 'POST',
			url: base_url,
			data: {	
					'storeid':store_id,
					'getStoreReviews':1,
				},
			//dataType: "json",
			beforeSend:function(){
				$("#review_form").html("<div align='center'><img style='width:45px;' src='img/loading.png'></div>");
			},
			complete: function(){
				$("#review_form").html('');
			},
			success: function(data)
			{
				$("#myModal6").find('.modal-body').find('div').html('');
				if(data.trim()!='null' && data.trim()!='')
				{
					data = $.parseJSON(data)
					var str='';
					if(data == '')
					{
						$("#myModal6").find('.modal-body').find('div').html("No Reviews Found..");
					}
					else
					{
						$.map(data,function(item){
						var name = item.username;
						if(name=='')
							name='Visitor';
						str+="<div class='frmvw-block'><label class='col-lg-3 col-md-3 col-sm-4 col-xs-12 frmvw-lbl'>"+name+":</label><div class='frmvw-txbtsmp col-lg-9 col-md-9	col-sm-8 col-xs-12'>"+item.store_review+"</div></div>"+
						"<div class='rvw-srng'><div class='rating' data-rate='"+item.store_rating+"'><i class='star-1 fa fa-star'></i><i class='star-2 fa fa-star'></i><i class='star-3 fa fa-star'></i><i class='star-4 fa fa-star'></i><i class='star-5 fa fa-star'></i></div></div>"; 
						}); 
						$("#myModal6").find('.modal-body').find('div').html("<div class='frm-sreview'>"+str+"</div>");
					}
					
				}
				else
					$("#myModal6").find('.modal-body').find('div').html("No Reviews Found..");
			}
		});
	}
	
	function zoomMap(map,lat,lng)
	{
		map.setZoom(15);
		var latLng = new google.maps.LatLng(lat, lng); 
		map.panTo(latLng);
	}
	
	function direction(map,city,lat1,lng1)
	{ 	var markerArray = [];
		var directionsService;
		var stepDisplay = new google.maps.InfoWindow();
		var directionsDisplay;
		directionsService = new google.maps.DirectionsService();
		
		 // Create a renderer for directions and bind it to the map.
		var rendererOptions = {
			map: map
		}
		directionsDisplay = new google.maps.DirectionsRenderer(rendererOptions)
		var geocoder = new google.maps.Geocoder();
		var endpoint = new google.maps.LatLng(lat1, lng1);
		var startpoint = new google.maps.LatLng(lat, lng);
		var address='';
		geocoder.geocode({latLng: startpoint}, function(results1, status1) {
				if (status1 == google.maps.GeocoderStatus.OK)
				{
					var saddress = results1[0].address_components[1].long_name+","+results1[0].address_components[2].long_name;
					if(saddress)
					{
						geocoder.geocode({latLng: endpoint}, function(results, status) {
							if (status == google.maps.GeocoderStatus.OK)
							{
								address = results[0].address_components[1].long_name+","+results[0].address_components[2].long_name;
								var request = {
									origin: saddress,
									destination:  address,
									travelMode: google.maps.TravelMode.TRANSIT
								};
								directionsService.route(request, function(response, status) {
									if(status=='ZERO_RESULTS')
										alert("We can not find directions from your location")
									 directionsDisplay.setDirections(response);
								});
							}
							
						
						});	
					}
				}
			});
			
		
		
		
		/*
		map.setZoom(15);
		var latLng = new google.maps.LatLng(lat, lng); 
		map.panTo(latLng);*/

	}
	
	function jsaddstore()
	{
		var name = $('#regname').val();
		var category = $('#regcategory').val();
		var regaddress = $('#regaddress1').val();
		var telephone = $('#regtelephone').val();
		var email = $('#regemail1').val();
		var website = $('#regwebsite').val();
		var description = $('#regdescription').val();
		var image = $('#regimage').val();
		var latitude = $('#reglatitude').val();
		var longitude = $('#reglongitude').val();
			
		if(name=='')
		{
			$('#regname').css("border",'1px solid red');
			$('#regname').focus();
			return false;
		}
		else
			$('#regname').css("border",'');
			
			if(category=='')
		{
			$('#regcategory').css("border",'1px solid red');
			$('#regcategory').focus();
			return false;
		}
		else
			$('#regcategory').css("border",'');
			
			if(regaddress=='')
		{
			$('#regaddress1').css("border",'1px solid red');
			$('#regaddress1').focus();
			return false;
		}
		else
			$('#regaddress1').css("border",'');
			//var regExp = /^(\([0-9]{3}\) |[0-9]{3}-)[0-9]{3}-[0-9]{4}/;
			//var phone = telephone.match(regExp);
			if((telephone=='') )//&& (phone) )
		{
			$('#regtelephone').css("border",'1px solid red');
			$('#regtelephone').focus();
			return false;
		}
		else
			$('#regtelephone').css("border",'');
			
			if(email=='')
		{
			$('#regemail1').css("border",'1px solid red');
			$('#regemail1').focus();
			return false;
		}
		else
			$('#regemail1').css("border",'');
			
			if(website=='')
		{
			$('#regwebsite').css("border",'1px solid red');
			$('#regwebsite').focus();
			return false;
		}
		else
			$('#regwebsite').css("border",'');
		
		if(description=='')
		{
			$('#regdescription').css("border",'1px solid red');
			$('#regdescription').focus();
			return false;
		}
		else
			$('#regdescription').css("border",'');
			if(image=='')
		{
			$('#regimage').css("border",'1px solid red');
			$('#regimage').focus();
			return false;
		}
		else
			$('#regimage').css("border",'');
			if(latitude=='')
		{
			$('#reglatitude').css("border",'1px solid red');
			$('#reglatitude').focus();
			return false;
		}
		else
			$('#reglatitude').css("border",'');
			if(longitude=='')
		{
			$('#reglongitude').css("border",'1px solid red');
			$('#reglongitude').focus();
			return false;
		}
		else
			$('#reglongitude').css("border",'');
		
			if($("#addstore_image").val()=='')
		{
			$('#addstore_image').css("border",'1px solid red');
			$('#addstore_image').focus();
			return false;
		}
		else
			$('#addstore_image').css("border",'');
	}
	
	function scrollBottom()
	{
		// $("html, body").animate({ scrollTop: 935 }, 1000);
		 $("html, body").animate({scrollTop: $('#storeinformation').offset().top }, 'slow');
	}
	$(document).on('click','.scrolldown',function(){
		// $("html, body").animate({ scrollTop: 935 }, 1000);
		 $("html, body").animate({scrollTop: $('#storeinformation').offset().top }, 'slow');
	});
	

$('.reviewcust_bxslider').bxSlider({
  auto: true,
  autoControls: true,
		speed: 1000,
		pause: 6000
});
$(document).ready(function(){
    $(".mapsidefx_toggle").click(function(){
        $(".mapsidefx_cntwrps").toggle();
        $(".mapsidefx_wrps").toggleClass('mapside_open');
        $(".mapsidefx_toggle i").toggleClass('fa-rotate-180');
    });
	/*$(".mapsdfx_block").click(function(){
		//alert($(this).attr('rel'));
		showStoreDetails($(this).attr('rel'));
		});*/

$(".mCustomScrollbar").mCustomScrollbar({
    theme:"dark"
});

	});

function searchbystorename(search_lat,search_long,search_itemname)
{
	
				$.ajax({ url: base_url,
					data: { 
						lat:search_lat,
						longi:search_long,
						action:'get_nearby_stores',
						ajax:1,
						id:search_itemname
						//id:ui.item.id
					  },
						dataType: "json",
						type: "POST",
						success: function(data){
							var det = JSON.stringify(data);
							dat = JSON.parse(det);
							var keyword='';
							var stores = dat.stores;
							//var infowindow=[];
							var myLatlng = new google.maps.LatLng(search_lat,search_long);
							var mapOptions = {
								 minZoom: 6, maxZoom: 15 ,
								center: myLatlng,
								zoomControl: false,
								mapTypeControl: false,
								streetViewControl: true,
								panControl: false,
								 styles: [{"featureType":"all","elementType":"labels.text.fill", "stylers":[{"saturation":36},{"color":"#a5a6a8"},{"lightness":2}]},
										{"featureType":"all","elementType":"labels.text.stroke","stylers":[{"visibility":"on"},{"color":"#45484D"},{"lightness":0}]},
										{"featureType":"all","elementType":"labels.icon","stylers":[{"visibility":"off"}]},
										{"featureType":"administrative","elementType":"geometry.fill","stylers":[{"color":"#45484D"},{"lightness":2}]},
										{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#45484D"},{"lightness":1},{"weight":1.2}]},
										{"featureType":"landscape","elementType":"geometry","stylers":[{"color":"#4F5660"},{"lightness":1}]},
										{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#4F5660"},{"lightness":2}]},
										{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#45484D"},{"lightness":6}]},
										{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#45484D"},{"lightness":6},{"weight":0.2}]},
										{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#45484D"},{"lightness":6}]},
										{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#45484D"},{"lightness":6}]},
										{"featureType":"transit","elementType":"geometry","stylers":[{"color":"#45484D"},{"lightness":19}]},
										{"featureType":"water","elementType":"geometry","stylers":[{"color":"#282A2E"},{"lightness":14}]}]
							  }
							map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
							
							var l=0; var ln=0;
							clearMarkers();
							for(x in stores)
							{
								var store = stores[x];
								store.id = store.id;
								l = store.latitude;
								ln= store.longitude;
								attachMarker( store,map,keyword );
							}
							if(l!==0)
							{
								//map.setCenter(new google.maps.LatLng(l,ln));
								map.setZoom(10);
								
							}
						}
					});
					
				
	
}


/////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////


function loadStoreReviews(store_id)
{
	 $.ajax({
		type: 'POST',
		url: base_url,
		data: {	
				'storeid':store_id,
				'getStoreReviews':1,
			},
		success: function(data)
		{
			$("#js-load-store-reviews").html('');
			if(data.trim()!='null' && data.trim()!='')
			{
				data = $.parseJSON(data)
				var str='';
				if(data == '')
				{
					$("#js-load-store-reviews").html("No Reviews Found..");
				}
				else
				{
					$.map(data,function(item){
					var name = item.username;
					if(name=='')
						name='Visitor';
					
						str+="<div class='reviewcust_block'> <div class='reviewcustblk_quote'>";
						str+=item.store_review;
						str+="</div>";
						str+="<h3 class='reviewcustblk_name'>- ";
						str+=name;
						str+="</h3>";
						str+="<div class='store-rating-starwrps rating' data-rate='"+item.store_rating+"'>";
						str+="<i class='star-1 fa fa-star'></i>";
						str+="<i class='star-2 fa fa-star'></i>";
						str+="<i class='star-3 fa fa-star'></i>";
						str+="<i class='star-4 fa fa-star'></i>";
						str+="<i class='star-5 fa fa-star'></i>";
						str+="</div>";
						str+="</div>";					
					});
					$("#js-load-store-reviews").html(str);
				}
			}
			else
				$("#js-load-store-reviews").html("No Reviews Found..");
		}
	});
}

 
	$(document).on('click', '.productDescriptionModalPreview', function(e) {
		$('#productDescriptionModal').addClass('in');
		var ProductID = $(this).data('id');
		
		if($("#js_product_image_"+ProductID).attr('src').indexOf("four">=0)){
			  image_path = $("#js_product_image_"+ProductID).attr('src').replace('uploaded/product_images/', 'uploaded/product_images/');
		}
		else
			{
				var image_path = $("#js_product_image_"+ProductID).attr('src').replace('uploaded/product_images/', 'uploaded/product_images/original/')
			}
		 
		
		
		$("#productDescriptionModal #thumbnil").attr('src', image_path);
		$("#productDescriptionModal #product_title").html($("#js_product_name_"+ProductID).html());
		$("#productDescriptionModal #product_options").html($("#js_product_options_"+ProductID).html());
		$("#productDescriptionModal #product_description").html($("#js_product_descrition_"+ProductID).html());
	});
	function search(nameKey, myarray){
   for (x in default_stores) {
    if (default_stores[x].id == nameKey) {
        return default_stores[x];
    }
}
}
 
function changemap(){
		var is_licensed='5';
 if($("#is_licensed").val() == "1"){
	 is_licensed =1;
 $("#search_box").val('');
		var default_lat_store = $("#default_lat_store").val();
		var default_long_store = $("#default_long_store").val();
		$.ajax({ url: base_url+"/harsha_ajax.php",
					data: { 
						type: 'Stores',
						q: ' ',
						is_licensed:is_licensed
						//id:ui.item.id
					  },
						dataType: "json",
						type: "POST",
						success: function(data){
							  
							var det = JSON.stringify(data);
						    dat = JSON.parse(det);
							var keyword='';							
							var stores = dat;							
							 
							
							
							var mapCanvas = document.getElementById('map-canvas');
							var mapOptions = {
							  center:  new google.maps.LatLng(stores[0].lat,stores[0].lng),
							   minZoom: 2, maxZoom: 35 ,zoom: inizoom,
								zoomControl: false,
								mapTypeControl: false,
								streetViewControl: true,
								 panControl: false,
							  mapTypeId: google.maps.MapTypeId.ROADMAP,
							  styles: [{"featureType":"all","elementType":"labels.text.fill", "stylers":[{"saturation":36},{"color":"#a5a6a8"},{"lightness":2}]},
										{"featureType":"all","elementType":"labels.text.stroke","stylers":[{"visibility":"on"},{"color":"#45484D"},{"lightness":0}]},
										{"featureType":"all","elementType":"labels.icon","stylers":[{"visibility":"off"}]},
										{"featureType":"administrative","elementType":"geometry.fill","stylers":[{"color":"#45484D"},{"lightness":2}]},
										{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#45484D"},{"lightness":1},{"weight":1.2}]},
										{"featureType":"landscape","elementType":"geometry","stylers":[{"color":"#4F5660"},{"lightness":1}]},
										{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#4F5660"},{"lightness":2}]},
										{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#45484D"},{"lightness":6}]},
										{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#45484D"},{"lightness":6},{"weight":0.2}]},
										{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#45484D"},{"lightness":6}]},
										{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#45484D"},{"lightness":6}]},
										{"featureType":"transit","elementType":"geometry","stylers":[{"color":"#45484D"},{"lightness":19}]},
										{"featureType":"water","elementType":"geometry","stylers":[{"color":"#282A2E"},{"lightness":14}]}]
							}
							map = new google.maps.Map(mapCanvas, mapOptions);
							map.addListener('zoom_changed', function() { 
							});
							clearMarkers();
							for(x in stores)
							{
							 	var store =search(stores[x].id, default_stores); 
							 	store.id = store.id;
								l = store.latitude;
								ln= store.longitude;
								attachMarker( store,map,keyword );
								
     
							}
							if(l!=0)
							{
								//map.setCenter(new google.maps.LatLng(l,ln));
								map.setZoom(10);
								 
							} 
							// showStoreDetails(store_id,store);
							 
						
						}
					});
 }
		else{
			 
			initialize();
		}
	}
$("#is_licensed").val(5); 
 $("#btn_is_licensed").click(function(){
		if($(this).hasClass('btn-success')){
			$(this).removeClass('btn-success');
			$("#is_licensed").val(5); 
		}
		else{
			$(this).addClass('btn-success');
			$("#is_licensed").val(1); 
			
			} 
		changemap();
 } ); 
  