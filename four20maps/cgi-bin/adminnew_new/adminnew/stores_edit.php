<?php

// include Config File

include_once './includes/config.inc.php';

include_once './includes/validate.php';

// Authenticate user login

//auth();

$upload_dir = '';

validate_edit_store();



$images = array();

	if(is_dir($upload_dir)) {

		$images = get_files($upload_dir);

		foreach($images as $k=>$v) {

			$images[$k] = ROOT_URL.'imgs/stores/'.$store['id'].'/'.$v;

		}

}



?>
	  <?php include("header.php"); ?>
      <!-- Left side column. contains the logo and sidebar -->
      <?php include("sidebar.php"); ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1><?php echo $lang['ADMIN_EDIT_STORE']; ?></h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Store List</a></li>
            <li class="active">Add a Store</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <!-- Small boxes (Stat box) -->
          <div class="row">
			<div class="col-lg-12 col-sm-12 col-xs-12">
            	<div class="box">
                <div class="box-header">
                  <h3 class="box-title"><?php echo $lang['ADMIN_ADD_STORE']; ?></h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <?php echo notification(); ?>

			<?php if(isset($errors) && !empty($errors)): ?>

			<div class="alert alert-block alert-error fade in">

			<ul>

				<?php foreach($errors as $k=>$v): ?>

				<li><?php echo $v; ?></li>

				<?php endforeach; ?>

			</ul>

			</div>

			<?php endif; ?>
            
            <div id="map_canvas" class="newstore_map"></div>

			<div id="ajax_msg"></div>
            
            <div style="display:block; clear:both">

			<form method='post' action='./stores_edit.php?id=<?php echo $store['id']; ?>' id='form_new_store' enctype="multipart/form-data">

			<div class="row">
            	<div class="col-lg-6 col-sm-12">

						<div class="form-group">
                            <label><?php echo $lang['ADMIN_NAME']; ?>: <span class='required'>*</span></label>
    
                            <input type='text' class="form-control" name='name' id='name' value="<?php echo $fields['name']['value']; ?>" />
                        </div>

						

						<div class="form-group">
                            <label><?php echo $lang['ADMIN_ADDRESS']; ?>: <span class='required'>*</span></label>
    
                           <input type='text' class="form-control" name='address' id='address' value="<?php echo $fields['address']['value']; ?>" />

						<span><?php echo $lang['ADMIN_LAT_LANG_AUTO']; ?></span>
                        </div>


						<div class="form-group">
                            <label><?php echo $lang['ADMINISTRATOR_EMAIL']; ?>:</label>
    
                            <input type='text' class="form-control" name='email' id='email' value='<?php echo $fields['email']['value']; ?>' />
                        </div>


					<div class='form-group'>

						<label><?php echo $lang['ADMIN_DESCRIPTION']; ?>:</label>

						<textarea name='description' class="form-control" id='description' rows="5" cols="40"><?php echo htmlentities($fields['description']['value']); ?></textarea>

					</div>
                    <div class='form-group'>
                    	<label><?php echo $lang['ADMIN_STORE_IMAGE']; ?>:</label>

						<input type="file" name="file" id="file" <?php if(!empty($images)) {echo 'disabled="disabled"';} ?>/>

						<span><?php echo $lang['ADMIN_THUMB_AUTO']; ?></span>
                        
                        
                        	<?php if(!empty($images)): ?>

					<div class="input">

						<?php foreach($images as $k=>$v): ?>

						<div class="image">

							<img src="<?php echo $v; ?>" alt="Image" />

							<button type="submit" class="btn btn-danger" name="delete_image[<?php echo basename($v); ?>]" id="delete_image" value="<?php echo basename($v); ?>">Delete</button>

						</div>

						<?php endforeach; ?>

					</div>

					<?php endif; ?>
                    </div>
                    
                    <div class='form-group input second'>

						<label><?php echo $lang['ADMIN_LONGITUDE']; ?>:</label>

						<input type='text' name='longitude' id='longitude' value='<?php echo $fields['longitude']['value']; ?>' />

					</div>

					</div>
                    <div class="col-lg-6 col-sm-12">
                    	<?php 

						$db = db_connect();

						mysql_query("SET NAMES utf8");

						$cats = $db->get_rows("SELECT categories.* FROM categories WHERE categories.id!='' GROUP BY categories.cat_name ORDER BY categories.cat_name ASC");



						?>
                        
                        <?php 

						$db = db_connect();

						mysql_query("SET NAMES utf8");

						$stores = $db->get_rows("select 0 as id, 'New Store' as name, '' as DatabaseName union all SELECT id,name,DatabaseName FROM stores  where ifnull(DatabaseName,'') <> '' and id = ParentId ");



						?>

						
						<div class="form-group">
                            <label><?php echo $lang['SSF_CATEGORY']; ?>: <span class='required'>*</span></label>
    
                            <select name="cat_id" class="form-control" id="cat_id" ><option value="0"><?php echo $lang['SSF_CATEGORY_NO_CAT_LISTBOX']; ?></option>

						 <?php if(!empty($cats)): ?>

							<?php foreach($cats as $k=>$v): ?>

							<option value="<?php echo $v['id']; ?>" <?php if($store['cat_id']==$v['id']){ ?>selected<?php } ?>><?php echo $v['cat_name']; ?></option>

							<?php endforeach; ?>

							<?php endif; ?>

						 </select>

                        </div>
                        <div class="form-group">
                            <label><?php echo $lang['ADMIN_TELEPHONE']; ?>:</label>
    
                            <input type='text' class="form-control" name='telephone' id='telephone' value='<?php echo $fields['telephone']['value']; ?>' />
                        </div>
                        <div class='form-group'>

						<label><?php echo $lang['ADMIN_WEBSITE']; ?>:</label>

						<input type='text' class="form-control" name='website' id='website' value='<?php echo $fields['website']['value']; ?>' />

					</div>
                        <div class="form-group">
                            <label><?php echo $lang['DatabaseName']; ?>: <span class='required'>*</span></label>
    
                            <select name="ParentId" class="form-select" id="store_id" >
                        

						 <?php if(!empty($stores)): ?>

							<?php foreach($stores as $k=>$v):  ?>

							<option value="<?php echo $v['id']; ?>" <?php if( $store[ParentId]==$v['id']){ ?>selected<?php } ?>><?php echo $v['name']; ?></option>

							<?php endforeach; ?>

							<?php endif; ?>

						 </select>
                         </div>
                         <div class="form-group input first">

						<label><?php echo $lang['ADMIN_LATITUDE']; ?>:</label>

						<input type='text' name='latitude' id='latitude' value='<?php echo $fields['latitude']['value']; ?>' />

					</div>
                    </div>
                    <div class="col-lg-12 col-sm-12">
                        <div class='input buttons'>
                            <button type='submit' class="btn btn-primary" name='save' id='save'><?php echo $lang['ADMIN_SAVE']; ?></button>

						<button type='button' class="btn btn-danger" onclick="document.location.href='stores.php'"><?php echo $lang['ADMIN_CANCEL']; ?></button>
                        </div>
                    </div>
				</div>
			</form>

			</div>
                </div><!-- /.box-body -->
              </div>
            </div>

          </div><!-- /.row -->

        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
            <?php include("footer.php"); ?>
