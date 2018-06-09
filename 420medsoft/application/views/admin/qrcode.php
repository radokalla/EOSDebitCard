<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo (isset($title))?$title:'Qr Code'; ?></title>
</head>
<body>

<?php /*?><img id="labelAddressImage" src="" /><?php */?>

<table border="1" width="160">
<tr>
<td width="150"><img width="150" src="<?php echo base_url($image); ?>" /></td>
</tr>
<tr>
<td align="center"><a href="<?php echo base_url($image); ?>" download="<?php echo ((isset($filename))?$filename:'qrcode'); ?>">Download</a></td>
</tr>
<tr>
<td align="center"><a href="javascript:" onclick="PrintShippingLabel()">Print label #3</a></td>
</table>

<?php //echo "<pre>"; print_r($employee); exit; ?>

<input type="hidden" id="employeeName" value="<?php echo $employee['firstName'].' '.$employee['lastName']; ?>" />
<input type="hidden" id="price" value="<?php echo $filename; ?>" />
<input type="hidden" id="image" value="<?php echo $qr_url; ?>" />

</body>
</html>

<script src="<?php echo base_url("js/jquery.min.js");?>"></script> 
<script src="http://labelwriter.com/software/dls/sdk/js/DYMO.Label.Framework.latest.js"
        type="text/javascript" charset="UTF-8"> </script>
<script type="text/javascript">

function PrintShippingLabel()
{
	 	var productName = $("#employeeName").val();
		var price = $("#price").val();
		var image = $("#image").val();
	    var	sub_cat = ' ';
	
		$.get('http://bayfrontorganics.com/labels/product-sample-label.label', function(labelXml)
		{
			var label = dymo.label.framework.openLabelXml(labelXml);  
			 
			label.setObjectText("BARCODE", image );
			label.setObjectText("category_name", productName);
			label.setObjectText("subcategory", ' ');
			label.setObjectText("item_price", price);
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
			var params = dymo.label.framework.createLabelWriterPrintParamsXml({twinTurboRoll: dymo.label.framework.TwinTurboRoll.Left});
			console.log(params);
			label.print(printerName,params);
			//finally print the label
			//label.print(printerName);
		});
	 
}

</script>