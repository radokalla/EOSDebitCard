<div class="memberlogin-wps col-md-12 products_page">
  <h2>Change Password</h2>
  <div class="col-md-12"> 
    <?php if(isset($_SESSION['FORGOT_SUCESS'])){?>
 <div class="success"> <?php echo isset($_SESSION['FORGOT_SUCESS'])?$_SESSION['FORGOT_SUCESS']:'';?></div>
 <?php }?>
    <form method="post" role="form" id="add-category-form" class="validate-form">
    <input type="hidden" value="<?php echo (isset($id)?$id:'')?>" id="id" name="id">
      <div class="form-group">
        <label for="exampleInputEmail1" class="col-md-4 catogery_name">New Password<strong class="star">*</strong> : </label>
        <div class="col-md-6">
          <input type="password" class="form-control text_input1 required " id="password" name="password"  placeholder="Enter New Password" >
        </div>
      </div>
      <div class="form-group">
        <label for="exampleInputEmail1" class="col-md-4 catogery_name">Confirm Password<strong class="star">*</strong> : </label>
        <div class="col-md-6">
          <input type="password" class="form-control text_input1 required " equalto="password" id="cpassword" name="cpassword" placeholder="Enter Confirm Password" >
        </div>
      </div>
        <div class="col-md-2"></div>
      </div>
      <div class="form-group">
        <div class="col-md-4"></div>
        <div class="col-md-6">
          <button type="submit" class="btn btn-primary category_button">Change</button>
        </div>
        
      </div>
    </form>
  </div>
</div>
