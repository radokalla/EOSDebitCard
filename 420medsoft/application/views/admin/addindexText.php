<div class="memberlogin-wps col-md-12 products_page">
  <h2>Home Content</h2>
  <div class="col-md-12"> <?php echo validation_errors(); ?>
    <form method="post" role="form" id="add-category-form" class="validate-form">
      <div class="form-group">
        <label for="exampleInputEmail1" class="col-md-4 catogery_name">First Title<strong class="star">*</strong> : </label>
        <div class="col-md-6">
          <input type="text" class="form-control text_input1 required " id="firstTitle" name="firstTitle" value="<?php echo isset($text[0]['firstTitle']) ? $text[0]['firstTitle'] : ''; ?>" placeholder="Enter First Title"   >
        </div>
      </div>
      <div class="form-group">
        <label for="exampleInputEmail1" class="col-md-4 catogery_name">First Title Description<strong class="star">*</strong> : </label>
        <div class="col-md-6">
        <textarea class="form-control text_input1 required " rows="10" cols="20" id="firstDesc" name="firstDesc" placeholder="Enter First Title Description"><?php echo isset($text[0]['firstDesc']) ? $text[0]['firstDesc'] : ''; ?></textarea>
        </div>
      </div>
      <div class="form-group">
        <label for="exampleInputEmail1" class="col-md-4 catogery_name">Second Title<strong class="star">*</strong> : </label>
        <div class="col-md-6">
          <input type="text" class="form-control text_input1 required " id="secondTitle" name="secondTitle" value="<?php echo isset($text[0]['secondTitle']) ? $text[0]['secondTitle'] : ''; ?>" placeholder="Enter Second Title"   >
        </div>
      </div>
      <div class="col-md-2"></div>
      </div>
      <div class="form-group">
        <div class="col-md-4"></div>
        <div class="col-md-6">
          <button type="submit" class="btn btn-primary category_button">Update</button>
        </div>
      </div>
    </form>
  </div>
</div>
