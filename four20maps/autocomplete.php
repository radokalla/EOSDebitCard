<?php
if(isset($_REQUEST['keyword'])){ $keyword = $_REQUEST['keyword'];
	include_once './includes/config.inc.php';
	include_once './includes/class.database.php';
	//echo json_encode(array('label'=>$keyword,'category'=>$keyword));exit;
	$totres = array();//'[';
	$res = mysql_query("CALL map_proc_getStoredetails('{$keyword}')")or die(mysql_error());
	while($reg =mysql_fetch_assoc($res)){
		//echo '<pre>';print_r($reg);exit;
		$totres[] = array('longlat'=>$reg['latitude'].'|'.$reg['longitude'],'label'=>$reg['value'],'category'=>$reg['Ke'],'id'=>$reg['id']);
		//$totres .= '{ label: "'.$reg['value'].'", category: "'.$reg['Ke'].'"},';
	}
	//$totres = substr($totres,0,-1).']';
	//echo $totres;exit;
	echo json_encode($totres);exit;
}

/*$term=$_GET['term'];

$resul=mysql_query("CALL map_proc_getStoredetails('{$term}')") or die(mysql_error());
while($reg=mysql_fetch_assoc($resul))
{
	$totres[]=$reg;
}

echo json_encode($totres);*/

?>