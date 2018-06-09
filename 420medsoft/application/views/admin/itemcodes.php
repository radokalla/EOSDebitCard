<div class="memberlogin-wps col-md-12 products_page">
  <h2>Item Codes</h2>
  <div class="col-md-12"> <?php echo validation_errors(); ?>
    <form method="post" role="form" id="add-category-form" class="validate-form">
      <div class="form-group">
        <label for="exampleInputEmail1" class="col-md-4 catogery_name">Item Name<strong class="star">*</strong> : </label>
        <div class="col-md-6">
          <input type="text" class="form-control text_input1 required " id="name" name="name" value="" placeholder="Enter Item Name"   >
        </div>
      </div>
      
    
      <div class="form-group">
        <div class="col-md-4"></div>
        <div class="col-md-6">
          <button type="submit" class="btn btn-primary category_button">Check</button>
        </div>
      </div>
    </form>
  </div>
  <?php if(isset($itemCodes)){ ?>
  <table class="table table-hover table-striped table_hd1">
    <thead class="table_heading">
    <tr>
      <th>QBCODE</th>
      <th>Item Name</th>
    </tr>
     </thead>
      <tbody>
    <?php foreach($itemCodes as $id => $name){ ?>
     <tr>
      <td class="alignright1"><?php echo $id; ?></td>
      <td><?php echo $name; ?></td> 
    </tr>
    <?php } ?>
    </tbody>
  </table>
  <?php } ?>
</div>
