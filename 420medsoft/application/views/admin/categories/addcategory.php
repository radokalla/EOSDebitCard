

<div class="memberlogin-wps col-md-12 products_page">

  <h2><?php echo isset($category['categoryID']) ? 'Update' : 'Add'; ?> Category</h2>

  <div class="col-md-12">

    <?php echo validation_errors(); ?>

    <form method="post" role="form" id="add-category-form" class="validate-form">

    	<input type="hidden" name="category_id" value="<?php echo isset($category['categoryID']) ? $category['categoryID'] : ''; ?>" />

      <div class="form-group">

        <label for="exampleInputEmail1" class="col-md-4 catogery_name">Category Name</label>
<div class="col-md-6">
        <input type="text" class="form-control text_input1 required alpha" id="category_name" name="categoryName" value="<?php echo isset($category['categoryName']) ? $category['categoryName'] : ''; ?>" placeholder="Enter Category">

      </div></div>

      

      <div class="form-group">

        <label for="exampleInputEmail1" class="col-md-4 catogery_name">Category Description Heading</label>
<div class="col-md-6">
        <input type="text" class="form-control text_input1 required " id="category_description_heading" name="categoryDescriptionHeading" value="<?php echo isset($category['categoryDescriptionHeading']) ? $category['categoryDescriptionHeading'] : ''; ?>" placeholder="Enter Category Description Heading">

      </div></div>

      

      <div class="form-group">

        <label for="exampleInputEmail1" class="col-md-4 catogery_name">Category Description</label>
<div class="col-md-6">
        <input type="text" class="form-control text_input1 required " id="category_description" name="categoryDescription" value="<?php echo isset($category['categoryDescription']) ? $category['categoryDescription'] : ''; ?>" placeholder="Enter Category Description">

      </div>
      <div class="col-md-2"></div>
</div>
      

      <div class="form-group"><div class="col-md-4"></div>
<div class="col-md-6">
        <button type="submit" class="btn btn-primary category_button"><?php echo isset($category['categoryID']) ? 'Update' : 'Add'; ?></button>
</div>
      </div>

    </form>

  </div>

</div>

