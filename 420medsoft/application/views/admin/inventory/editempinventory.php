
<form class="validate-form" method="post">
<input type="hidden"  value="<?php echo $inventoryStock[0]['inventoryID']; ?>" id="inventoryID" name="inventoryID" />
  <div class="memberlogin-wps col-md-12 products_page">
    <h2>Products Inventory</h2>
    <div class="col-md-12">
      <div class="form-group">
        <label class="col-md-4 patient_id" for="exampleInputEmail1">Employee Name</label>
        <div class="col-md-8 catogory_name">
        <input type="text" value="<?php echo $inventoryStock[0]['firstName'].' '.$inventoryStock[0]['lastName']; ?>"  class="form-control num text_input1" readonly />
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-4" for="exampleInputEmail1">Category Name</label>
        <div class="col-md-8">
          <select class="text_input3">
            <option value=""><?php echo $inventoryStock[0]['mainCategoryName']; ?></option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-4" for="exampleInputEmail1">Product Name</label>
        <div class="col-md-8">
          <select class="text_input3">
            <option value=""><?php echo $inventoryStock[0]['categoryName']; ?></option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-4" for="exampleInputEmail1">Inner Product Name</label>
        <div class="col-md-8">
          <input type="text" required="required" placeholder="Inventory" value="<?php echo $inventoryStock[0]['totalStock']; ?>" id="inventory" name="inventory" class="form-control num text_input1">
        </div>
      </div>
      
      <div class="form-group">
    <div class="col-md-8">
      <button class="btn btn-primary category_button" type="submit">Update</button>
    </div>
  </div>
  
    </div>
  </div>
</form>
