
var map='';
var infowindow = new google.maps.InfoWindow({ content: ''});
								 
								  
 
 $(document).ready(function(e) {
	 initialize();
	  $("#adduser_phone").mask("(999) 999-9999");
	  $(".icn-closebtn").hide()
	  
	  $(document).on('click','#addEnquiry_button', addEnquiry);
	  $(document).on('click','#storecomments', loadReviews);
	  $(document).on('click','#sicon', showSativa);
	 /* $(document).on('click','.h-icon', showHydrid);
	  $(document).on('click','.i-icon', showIndica);*/
	  $( "#edit-submit" ).click(function(){
		   $( "#search_box" ).catcomplete("search")
		  // console.log('clicked')
	  });
$('#search_box').on('input', function() {
    if($(this).val()=='')
		$(".icn-closebtn").hide()
	else
		$(".icn-closebtn").show()
});	 
$(".icn-closebtn").click(function(){
	$('#search_box').val('');
})	 
	 $( "#search_box" ).catcomplete({
			source: function(request, response) {
				$.ajax({ url: "harsha_ajax.php",
				data: { q: $("#search_box").val()},
				dataType: "json",
				type: "POST",
				beforeSend:function(){
					
					//$('#search-loading').html("<img src='img/ajax-loader.gif'>");
				},
				complete:function(){
					//$('#search-loading').empty();
				},
				success: function(data){
					if(data[0].text == "No Result found")
					{
						alert("We currently don't have any locations for this area")
						return ;
					}	
					
					//console.log(data[0].children)
                    //alert(JSON.stringify(data));
					 var suggestions = [];
					  $.each(data[1].children, function(i, val){
                       var pros = {name:val, cat:"Products",id:'',lat:'',long:''};
						suggestions.push(pros);
                    });
					$.map(data[0].children, function(val){
                       var pros = {name:val.name, cat:"Stores",id:val.id,lat:val.lat,long:val.long};
						suggestions.push(pros);
                    });
					
					 response($.map(suggestions, function(item){
							return{
								label: item.name,
								category: item.cat,
								id: item.id,
								lat: item.lat,
								long: item.long,
							};
						})
					
                    );
					$(".ui-autocomplete").addClass("scbarheight mCustomScrollbar mcs-white")
					
                    
				}
			});
		},
		minLength: 2,
		select: function( event, ui ){
				
				
				 if(ui.item.category=='Stores') 
				{
					
					$.ajax({ url: location.href,
						data: { 
							lat:ui.item.lat,
							longi:ui.item.long,
							action:'get_nearby_stores',
							ajax:1,
							id:ui.item.id
						  },
						dataType: "json",
						type: "POST",
						success: function(data){
							var stores = data.stores;
							var keyword='';
							//var infowindow=[];
							var myLatlng = new google.maps.LatLng(ui.item.lat,ui.item.long);
							  var mapOptions = {
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
							var l=0; var ln=0;
							for(x in stores)
							{
								var store = stores[x];
								store.id = store.storeid;
								l = store.lat;
								ln= store.lng;
								attachMarker( store,map,keyword );
							}
							if(l!=0)
							{
								map.setCenter(new google.maps.LatLng(l,ln));
								map.setZoom(10);
								
							}
						}
					});
					
				}
				else
				{
					//console.log(ui.item)
					$("#store_search").attr("value",ui.item.value) 
					$("#store_search").val(ui.item.value)
					getStoresByProduct(ui.item.label);
				}
 			   $('.icn-closebtn').focus();	  
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
					//console.log(cat_name)
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
			//console.log(visible)
	});
	$(document).on('click', "[name=buy_product]", function(){
		var pname = $(this).attr('pname');
		var site = $(this).attr('href');
		$.ajax({
			url: location.href,
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
 
	$.ajax({ url: "getStoresByProduct.php",
		data: { 
				keyword:keyword
			  },
		dataType: "json",
		type: "POST",
		beforeSend:function(){
			$("#store_products").html("<img style='margin-left:40%' src='img/299.GIF'>");
			$("#nav").empty();
		},
		complete:function(){
			$("#store_products").empty();
		},

		success: function(data){
					var myLatlng = new google.maps.LatLng(54.81,40.35);
					 var mapOptions = {
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
					for(x in data)
					{
						var store = data[x];
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
	 $.ajax({ url: "getProductsByStore.php",
			data: { 
					q:store_id,
					keyword:keyword
				  },
			dataType: "json",
			type: "POST",
			beforeSend:function(){
				$("#store_products").html("<img style='margin-left:40%' src='img/299.GIF'>");
				$("#nav").empty();
			},
			complete:function(){
				
			},
			
			success: function(returndata){
				mainCategories = returndata.mainCategories
				var mainHtml = '';
				for(item in mainCategories)
				{
					mainHtml += '<li><a href="javascript:" rel="'+item+'" onclick="mainfilter(\''+item+'\')">'+mainCategories[item]+'</a></li>';
				}
				$('#category-menu').html(mainHtml);
				if(mainHtml != "")
					$('#category-menu-div').show()
				else
					$('#category-menu-div').hide()
				
				
				var parentCatID = '';
				var data = returndata.products
				for(item in data)
				{
					var catid = data[item].catid;
					var catname = data[item].catname;
					var products = data[item].products;
					//var catname = data[item].ParentcategoryName;
					var types='';
					var optionsstr='<div class="prd-optsbx col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="prd-optsbx-in">';
					var image='';
					var categoryType ='';
					//types+="<div style='width:100%'>";
					for(product in products)
					{
						var pname = products[product].pname;
						var ProductID = products[product].ProductID;
						parentCatID = products[product].ParentcategoryID;
						var price = products[product].price;
						var stock = products[product].AvailableStock;
						if(website.indexOf("http://") < 0)
						website = "http://" + website;

						if(parseInt(stock)>0)
							var but = "<a name='buy_product' pname='"+catid+"' class='btn btn-primary btn-sm btn-buy-item' target='_blank' href='"+website+"/index.php/main/addtocart/"+ProductID+"'>Buy</a>";
						else
							var but="<a class='btn btn-primary btn-sm soldout'>Sold out</a>";
						image = products[product].image;
						 categoryType = products[product].categoryType;
						types+="<div class='product-itemwps-bx col-lg-2 col-md-2 col-sm-4 col-xs-6' name='product'><div class='product-itemwps'>"+
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
						optionsstr+="<p class='prd-optsbx-txt'>"+value+"</p>";
						else
						optionsstr+="<p class='prd-optsbx-txt'>"+optionType+":"+value+"</p>";
					}
					 optionsstr+='</div></div>';
					 html+='<div class="product-rowwps" name="product_row" '+
					 ' cat_type="'+categoryType+'"'
					 +' parentCatID="'+parentCatID+'" '
					 +'cat_name="'+catname+'" style="/*height:170px;background:#b1b1b1;border-radius:6px;width:930px;margin-left:2px*/">'+
					 '<div class="harsh-imageleft">'+
					 '<img style="border-radius:5px" src="'+website+'/'+image+'" ></div>'+
					 '<div class="harsh-textright"><p style="position: relative;bottom: 0px; width: 100%;text-align: center;color: #ffffff;font-size: 17px;text-transform: uppercase;'+
					 'font-weight: bold;background-color: rgba(0,0,0,0.8);border-radius: 0px 0px 6px 6px;margin: 0px; padding: 10px 0px;">'+
					 catname+'</p>'+
					 '<div class="harsh-optionsstrwps">'+optionsstr+'</div>'+
					 '<div class="harsh-typeswps">'+types+'</div>'+
					 '</div>'+'</div>';
				}
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
				pano: data.location.pano,
				addressControl: false,
				navigationControl: true,
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
function initialize() {
	
	//console.log(default_stores)
	var mapCanvas = document.getElementById('map-canvas');
	var mapOptions = {
	  center: new google.maps.LatLng(lat, lng),
	  zoom: inizoom,
	    zoomControl: false,
		mapTypeControl: false,
		streetViewControl: false,
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
	//console.log(default_stores)
	$.map(default_stores, function(item){
		attachMarker(item,map,"")
	});
	
	
}
function attachMarker( store,map,keyword ) {
	if(store.DatabaseName=='')
		var email ='#';
	else
		var email =store.email;
	var website=store.website;
	var result = website.search(new RegExp(/^http:\/\//i));
	if( result )
	website = 'http://'+website;
		var telephone = store.telephone;
		var phone = telephone.replace(/[&\/\\#,()$~%. '":*?<>{}-]/g, '');
		 var contentString = '<div class="maps_popup"><img style="max-width:70px; max-height:70px;" alt="'+store.name+'" src="'+store.img+'" class="img">'+
		'<h1>'+store.name+'</h1><p>'+store.address+' </p><p class="tel"><label>Telephone:</label> <a href="tel:['+phone+']">'+phone+'</a></p><p class="email"><label>Email:</label> '+
		'<a href="mailto:'+email+'">'+email+'</a></p>'+
		'<p class="web"><label>Website:</label> <a onClick="window.open(\''+website+'\')">'+store.website+'</a></p>'+
		'<p class="description">'+store.description+'</p><div class="products" style="font-weight:bold">'; 
		if(store.cat_name!='' && store.cat_img!='')
		contentString+='<img style="max-width:24px; max-height:24px;margin-right:5px;" src="'+store.cat_img+'">'+store.cat_name;				
		
		contentString+='</div>'+
		'<span class="email"><center><a style="display:inline-block;font-size:13px;padding:5px 10px;margin-top:10px;margin-bottom:10px;margin-left:3px;'+
		'border:1px solid #8b8b8b;text-align: center;font-weight:bold;width:auto;" class="contact-clinic button blue-button" '+
		'href="mailto:'+email+'"> Contact this store</a></center></span>';
		var str = '<a class="ft-acolr" href="javascript:createStreetMap('+store.lat+','+store.lng+');">'+
								'Street view</a> | <a class="ft-acolr" href="javascript:zoomMap(map,'+store.lat+','+store.lng+');">Zoom here</a> | '+
								'<a class="ft-acolr" href="javascript:direction(map,&quot;hyderabad&quot;,'+store.lat+','+store.lng+');">Directions</a></div>';
								
		if(store.cat_icon)
			var icon = store.cat_icon;
		else
			var icon = "img/map-icon.png";
	
		 var icon1 = new google.maps.MarkerImage(
           icon, //url
            new google.maps.Size(32, 32) //size
          
		);
		 var marker = new google.maps.Marker({
			map: map,
			position: new google.maps.LatLng(store.lat, store.lng),
			store_id: store.id,
			keyword: keyword,
			website: store.website,
			 icon:icon1
			});
			
			//console.log(store.id+'=>'+store.lat+','+ store.lng)
			 google.maps.event.addListener(marker, 'click', function() {
				infowindow.setContent(contentString+str);
				infowindow.open(map, this);
				 getProductsByStore(marker.store_id,marker.keyword,marker.website);
				 getStoreRating(marker.store_id)
				
				$('#store_information').find('[name=store_name]').text(store.name);
				$('#store_information').find('[name=store_addr]').text(store.address);
				$('#store_information').find('[name=store_deli]').text('');
				$('#store_information').find('[name=store_telephone]').text(store.telephone);
				$('#store_information').find('[name=store_email]').text(store.email);
				$('#store_information').find('[name=store_website]').text(store.website);
				$('#store_information').find('[name=store_desc]').text(store.description);
				$('#store_information').find('.store_image').css('display',"block");
				$('.searchindi').css('display',"block");
				$('#rateus').css('display',"block");
				$('#storecomments').css('display',"block");
				$('#filter_search_res').css('display',"block");
				$('#store_information').find('.store_image').attr('src',store.img);
				$('#store_information').find('.store_image').attr('alt',store.name);
				$('#store_information').find('[name=store_id]').html(store.id);
		        $('.maps_popup').parent().css('overflow',"inherit");
				  $('.maps_popup').parent().parent().css('overflow',"inherit");
				
				//$('#store_information').html(contentString)
			  });
	}
	function jslogin()
   {
	var username = $('#usernamer').val();
	var password = $('#passwordr').val();
	if(username=='')
	{
		$('#usernamer').css("border",'1px solid red');
		$('#usernamer').focus();
		return false;
	}
	else
		$('#usernamer').css("border",'');
	
	if(password=='')
	{
		$('#passwordr').css("border",'1px solid red');
		$('#passwordr').focus();
		return false;
	}
	else
		$('#passwordr').css("border",'');
	
	   if(username)
	   {
			$.ajax({
				type: 'POST',
				url: 'ajlogin.php',
				data: {'username':username,'password':password},
				//dataType: "json",
				success: function(data)
				{
					data=data.trim();
					if(data == 0)
					alert('Invalid Username password');
					else
					location.reload();
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
		 $.ajax({
			type: 'POST',
			url: 'forgotemail.php',
			data:{'email':frgtuser},
			success: function(data)
			{
				if(data == 'success') 
				{
					alert('A mail Has been sent to Your Email ID');
					location.reload();
				}
				else
				{
					alert('Invalid Email ID');
					location.reload();
				}
			}
		 });
	}
		
   }
   
   function userlogin()
   {
	var regfname = $('#regfname').val();
	var reglname = $('#reglname').val();
	var regemail = $('#regemail').val();
	var reguser = $('#reguser').val();
	var regpassword = $('#regpassword').val();
	var regaddress = $('#regaddress').val();
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
	if(regemail=='')
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
			url: 'reg.php',
			data:{'regfname':regfname,'reglname':reglname,'regemail':regemail,'reguser':reguser,'regpassword':regpassword,'regaddress':regaddress,},
			success: function(data)
			{
				if(data == 'success') 
				{
					alert('Registration Successfull');
					location.reload();
				}
				else
				{
					alert('User name OR Email ID Already Exsists');
					location.reload();
				}
			}
		 });
	}
		
   }
   
   function getStoreRating(store_id)
   {
	   $.ajax({
			type: 'POST',
			url: location.href,
			data: {'storeid':store_id,'getStoreRating':1},
			//dataType: "json",
			success: function(data)
			{
				data = data.trim();
				data = $.parseJSON(data);
				//console.log(data)
				$('[name=store_rating]').show();
				$('[name=rate_us_store]').show();
				if(data.avg != '')
				{
					$('#star'+data.avg).attr('checked', true);
					
					$('[name=store_rating]').html("<img src='img/"+data.avg+".png' style='height:16px'>");
					$("#storecomments").html("Reviews ("+data.total+")")
				}
				//console.log("<img src='img/"+data+".png'")
			}
		});
   }
   function saveRating(uid){
	   var rating = $('[name=rate]:checked').val();
	   var store_id = $('[name=store_id]').text();
	   var rating_comments = $('[name=rating_comments]').val();
	   $.ajax({
			type: 'POST',
			url: location.href,
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
			}
		});
		$('#rating_form').trigger('reset');
		$("#myModal5").find('.close').trigger('click');
		return false;
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
			url: 'jsupdate.php',
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
			$("#adduser_url").css('border','1px solid red');
			return false;
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
				url: 'addss.php',
				type: 'POST',
		   data: data,
				cache: false,
				dataType: 'json',
				processData: false, // Don't process the files
				contentType: false,
				enctype: 'multipart/form-data',
				beforeSend: function(){
					$('#addEnquiry_button').parent().append("<img src='img/ajax-loader.gif'>")
				},
				complete: function(){
					$('#addEnquiry_button').parent().find('img').remove()
					$("#myModal4").find('.close').trigger('click');
					$("#add_form").trigger('reset');
				},
				success: function(data, textStatus, jqXHR)
				{
					if(data=='1')
						alert("Success")
					else
						alert("Error in sending enquiry!!")
					
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
	function forgotpass(){
		
		var email = $("#Email_for").val();
		if(email=='')
		{
			$("#Email_for").css("border","1px solid red");
			$("#Email_for").focus();
			return false;
		}
		$.ajax({
			url: 'forgot.php',
			type: "POST",
			data: {email:email},
			beforeSend: function(){
				$("[name=doFPsubmit]").parent().append("<img src='img/ajax-loader.gif'>")
			},
			success: function(data){
				alert("Success, an email has been sent to your mail address");
				$("#forgot_pass").find('.close').trigger('click');
				$("#login_id").trigger("click")
			}
		})
		
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
			url: location.href,
			data: {	
					'storeid':store_id,
					'getStoreReviews':1,
				},
			//dataType: "json",
			beforeSend:function(){
				$("#review_form").html("<img src='img/ajax-loader.gif'>");
			},
			complete: function(){
				$("#review_form").html('');
			},
			success: function(data)
			{
				if(data.trim()!='null' && data.trim()!='')
				{
					data = $.parseJSON(data)
					var str='';
					$.map(data,function(item){
						var name = item.username;
						if(name=='')
							name='Visitor';
						str+="<tr><td><b>"+name+"</b></td><td>:</td><td style='width:400px; text-align:justify'>"+item.store_review+"</td>"+
						"<td><img style='height:16px' src='img/"+item.store_rating+".png'></td></tr>";
						//console.log(item)
					});
					//console.log(str)
					$("#myModal6").find('.modal-body').find('div').html("<table>"+str+"</table>");
				}
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
	{
		//console.log(lat)
		//console.log(lng)
		var markerArray = [];
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
	
	 