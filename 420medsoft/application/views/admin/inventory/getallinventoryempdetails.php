
<div class="memberlogin-wps col-md-12 products_page">
  <h2>Employee Inventory Details
   <a class="category_add" href="<?php echo base_url('index.php/adminempinventory/inventory'); ?>">Add Employee Inventory</a></h2>
   <div class="col-md-12"> </div>
  <?php if(!$inventoryStock){ ?>
  <div>Not found Inventory Details. Please add.</div>
  <?php }else{ ?>

  <form method="post">
    <table class="table table-hover table-striped table_hd1">
<thead class="table_heading">
        <tr>
          <th class="cell1">Category Name:</th>
          <th class="cell1">Product Name:</th>
          <th class="cell1">Employee Name:</th>
          <th class="cell1">Inner Procudt Name:</th>
           <th class="cell1"> </th>
          
        </tr>
      </thead>
       <tbody>
       <tr>
       <td> <input class="text_input2" type="text" name="mainCategoryName" value="" size="10" ></td>
       <td> <input class="text_input2" type="text" name="categoryName" value="" size="10" ></td>
       <td> <input class="text_input2" type="text" name="employeeName" value="" size="10" ></td>
       <td> <input class="text_input2" type="text" name="productName" value="" size="10" ></td>
         
                <td width="14%">   <input type="hidden" name="recordPerPage" value="<?php echo isset($recordsperpage) ? $recordsperpage : 10; ?>">
            <input type="submit" name="search" value="Search" class="btn btn-success"></td>
       </tr>
       </tbody>
    </table>
  </form>
  
  <form name="view_order" method="post" action=""><div class="col-md-10"></div><div class="col-md-2 show_class"> 
  <select name="recordPerPage" onchange="view_order.submit()" class="text_input4 cellform">
    <option value="10" <?php echo ($recordsperpage == 10) ? ' selected="selected"' : ''; ?>>10 Records</option>
    <option value="25" <?php echo ($recordsperpage == 25) ? ' selected="selected"' : ''; ?>>25 Records</option>
    <option value="50" <?php echo ($recordsperpage == 50) ? ' selected="selected"' : ''; ?>>50 Records</option>
    <option value="100" <?php echo ($recordsperpage == 100) ? ' selected="selected"' : ''; ?>>100 Records</option>
    <option value="250" <?php echo ($recordsperpage == 250) ? ' selected="selected"' : ''; ?>>250 Records</option>
    <option value="500" <?php echo ($recordsperpage == 500) ? ' selected="selected"' : ''; ?>>500 Records</option>
    <option value="1000" <?php echo ($recordsperpage == 1000) ? ' selected="selected"' : ''; ?>>1000 Records</option>
  </select></div>
  </form>

  <table class="table table-hover table-striped table_hd1">
    <thead class="table_heading">
      <tr>
        <th>S No</th>
        <th>Category Name</th>
        <th>Product Name</th>
        <th>Employee Name</th>
        <th>Inner Product Name</th>
        <th>Total Stock</th>
        <?php /*?><th>Total Sales</th>
        <th>Remaining Stock</th><?php */?>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php $sno = 0; foreach($inventoryStock as  $inventoryStockDetails){ ?>
      <tr>
        <td class="alignright1"><?php echo ++$sno; ?></td>
        <td><?php echo $inventoryStockDetails['mainCategoryName']; ?></td>
        <td><?php echo $inventoryStockDetails['categoryName']; ?></td>
        <td><?php echo $inventoryStockDetails['firstName']." ".$inventoryStockDetails['lastName']; ?></td>
        <td><?php echo $inventoryStockDetails['productName']; ?></td>
        <td><?php echo $inventoryStockDetails['totalStock']; ?></td>
        <?php /*?><td><?php echo $inventoryStockDetails['totalSales']; ?></td>
        <td><?php echo $inventoryStockDetails['totalRemaining']; ?></td><?php */?>
        <td class="textbutton"><a class="glyphicon glyphicon-pencil view_button" title="Edit" data-placement="top" data-toggle="tooltip" href="<?php echo base_url('index.php/adminempinventory/editempinventory/'.$inventoryStockDetails['inventoryID']); ?>"></a>
                </td>
      </tr>
    </tbody>
    <?php } ?>
  </table>
  
  <div class="pagination">&nbsp;&nbsp;<?php echo $paginationLinks; ?></div>
  
  <?php } ?>
</div>
