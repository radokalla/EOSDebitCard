<?php 
session_start();
include_once './includes/config.inc.php';
  $ticker_sql="SELECT DISTINCT categoryAliasName,s.name,`categoryName`, spm.product_id,spm.`user_id` as owner_id,spm.`store_id`,pc.`categoryID`,pc.`UserId`,pc.`image`,`price`,`qty`,productDescription,spm.store_product_map_id
FROM `ProductCategory` as pc inner join 
Products as p on p.categoryID=pc.`categoryID`
inner join store_product_map as spm on spm.product_id=p.productID
inner join StoreUsers as su on su.UserId=spm.user_id
inner join stores as s on s.id=spm.store_id WHERE `SubscriptionCategoryId`='4' and p.`qty`>0 and p.`isActive` = 1 and p.`isDeleted`=0  and pc.parentID !=0  and  p.is_ticker=0 order by spm.store_product_map_id desc";
 $ticker_result=mysql_query($ticker_sql);
if(mysql_num_rows($ticker_result) >0){
while($ticker_farm_array = mysql_fetch_array($ticker_result)){  
	  if((int)($ticker_farm_array["price"]) != 0) {?>    
   <tr>
      <td width="50"><div class="tableblk_prfimg"><a style="border: none;" href="javascript:"  data-toggle="modal" data-target="#ticker_popup" class="rl-imwps"><img src="http://www.four20maps.com/<?=$ticker_farm_array["image"];?>" onerror="this.src='http://www.four20maps.com/img/no_image.png'" class="imb-responsive mCS_img_loaded"  data-img="<?php echo $ticker_farm_array["image"];?>"  data-productname="<?php echo $ticker_farm_array['categoryName']; ?>" data-productDescription="<?php echo $ticker_farm_array['productDescription']; ?>"></a></div></td>
      <td><?=$ticker_farm_array["categoryAliasName"];?></td> 
      <td><?=$ticker_farm_array["name"];?></td>       
      <td><?=$ticker_farm_array["price"];?></td>
      <td><?=$ticker_farm_array["qty"];?></td>
    
      <td><a href="javascript:" data-toggle="modal" class="btn btn-sm btn-primary modaldonatenow" data-target="#responsive" data-total_price="<?=$ticker_farm_array["price"];?>" data-owner_id="<?=$ticker_farm_array["owner_id"];?>" data-category_id="<?=$ticker_farm_array["categoryID"];?>" data-product_id="<?=$ticker_farm_array["product_id"];?>" data-store_id="<?=$ticker_farm_array["store_id"];?>">Donate</a></td>
    </tr>
    <?php  } } }
mysql_query("UPDATE `Products` SET  `is_ticker`=1");


?>