<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Product QR Codes</title>
</head>
<body>
<?php //echo "<pre>"; print_r($images); exit; ?>

<?php /*?><img id="labelAddressImage" src="" /><?php */?>

<table>
<?php $srno=1; $ma=(int)count($images)/2; $ma++; foreach($images as $imageKey => $image) { ?>
<?php if($srno==1) { ?>
<tr>
<?php } ?>
<td>
<table width="160" border="1">
<tr>
<td><img width="150" src="<?php echo base_url($image['image']); ?>" /></td>
</tr>
<tr>
<td align="center"><?php echo $image['productName']; ?></td>
</tr>
<tr>
<td align="center"><?php echo $image['QBcode']; ?></td>
</tr>
<tr>
<td align="center"><?php echo $image['price']; ?></td>
</tr>
<tr>
<td align="center"><a href="<?php echo base_url($image['image']); ?>" download="<?php echo $image['productName']; ?>">Download</a></td>
<tr>
<td align="center"><a href="javascript:" onclick="PrintShippingLabel('<?php echo $imageKey ?>')">Print label #2</a></td>

<input type="hidden" id="productName-<?php echo $imageKey ?>" value="<?php echo $image['productName']; ?>" />
<input type="hidden" id="price-<?php echo $imageKey ?>" value="<?php echo $currency['symbol']; ?><?php echo $image['price']; ?>" />
<input type="hidden" id="image-<?php echo $imageKey ?>" value="<?php echo $image['qr_url']; ?>" />
<input type="hidden" id="sub_cat_text-<?php echo $imageKey ?>" value="<?php echo $sub_cat_text;?>" />


</tr>
<!--<tr>
<td align="center"><a href="<?php //echo $image['url']; ?>" >Go to</a></td>
</tr>-->
</table>
</td>
<?php $srno++; if($srno!=1 &&  $srno%$ma==0) { ?>
	</tr> <tr>
<?php } ?>
<?php  } ?>
</table>
</body>
</html>
<script src="<?php echo base_url("js/jquery.min.js");?>"></script> 
<script src="http://labelwriter.com/software/dls/sdk/js/DYMO.Label.Framework.latest.js"
        type="text/javascript" charset="UTF-8"> </script>
<script type="text/javascript">

function updateOrderStatus(orderID, varthis)
{
	var status = $(varthis).val();
	var dataString = "orderID="+orderID+"&status="+status;
	$.ajax({
		type: "POST",
		url: '<?php echo base_url('index.php/adminorders/updateOrderStaus'); ?>',
		data: dataString,
		success: function (data) {
		}
	});
}


function PrintShippingLabel(orderID)
{
	//if(confirm("Are you sure you want to print small shipping label # 2 ?"))
	//{
		var productName = $("#productName-"+orderID).val();
		var price = $("#price-"+orderID).val();
		var image = $("#image-"+orderID).val();
	    var	sub_cat = $("#sub_cat_text-"+orderID).val();
	
		$.get('http://bayfrontorganics.com/labels/product-sample-label.label', function(labelXml)
		{
			var label = dymo.label.framework.openLabelXml(labelXml);  
			 
			label.setObjectText("BARCODE", image );
			label.setObjectText("category_name", '<?php echo $categoryName; ?>');
			label.setObjectText("subcategory", productName);
			label.setObjectText("item_price", 'Price '+price);
			label.setObjectText("subcategory",sub_cat);
			var printers = dymo.label.framework.getPrinters();
			if (printers.length == 0)
				throw "No DYMO printers are installed. Install DYMO printers.";
	
			var printerName = "";
			for (var i = 0; i < printers.length; ++i)
			{
				var printer = printers[i];
				if (printer.printerType == "LabelWriterPrinter")
				{
					printerName = printer.name;
					break;
				}
			}
			//alert(printerName);
			if (printerName == "")
				throw "No LabelWriter printers found. Install LabelWriter printer";
			
			if (!label)
				return;
		
			<?php /*?>var pngData = label.render();
		
			var labelImage = document.getElementById('labelAddressImage');
			labelImage.src = "data:image/png;base64," + pngData;<?php */?>
			
			//finally print the label
			label.print(printerName);
		});
	//}
}

</script>