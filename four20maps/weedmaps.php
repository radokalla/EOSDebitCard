<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Shopping Cart</title>
<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
<style>
.media-heading{font-weight:700;}
.menu-item-prices{font-weight:700;}
.media-body p{font-size:0.8em}
</style>
</head>
<body>
<div class="container">
    <h1>Products</h1>
    <div class="row">
		<div class="col-md-12" id="menu-items">
		
		<?php 
		  //current URL of the Page. cart_update.php redirects back to this URL
	$current_url = base64_encode($url="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
    
	$headers = array(
    'Content-Type: application/json',
	);
	

	$url = 'https://weedmaps.com/api/web/v1/listings/secretgarden-2-3/menu?show_unpublished=false&type=delivery';

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, false);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

	$result = curl_exec($ch); 

	curl_close($ch);

	$menu_data = json_decode($result, true);
	
		foreach($menu_data['categories'] as $menu_item){
				foreach ($menu_item['items'] as $item) {
					echo '<div class="media results-item">';
						echo '<div class="media-left item">';
							echo '<div class="menu-item-photos">';
							echo '<a href="#">';
								echo isset($item['image_url']) ? '<img class="media-object menu-item-photo" height="120" src="'.$item['image_url'].'">' : '<img class="media-object menu-item-photo" height="120" src="square_missing.jpg">';
							echo '</a>';
							echo '</div>';
						echo '</div>';
						echo '<div class="media-body">';
							echo '<div class="col-md-8 menu-item-names">';
								echo '<h4 class="media-heading">'.$item['name'].'&emsp;<small>'.$menu_item['title'].'</small></h4>';
								echo '<p>'.$item['body'].'</p>';
							echo '</div>';
						
							echo '<div class="col-md-4 menu-item-prices">';
									foreach($item['prices'] as $key => $cost){
										echo '<div class="menu-item-price">'.$key." : ".$cost.'&emsp;<i class="glyphicon glyphicon-shopping-cart" data-toggle="tooltip" data-placement="right" title="Add To Cart"></i></div>';
									}
							echo '</div>';
						echo '</div>';
					echo '</div>';
	
					
			}
		
		}
		?>
		</div>
	</div>