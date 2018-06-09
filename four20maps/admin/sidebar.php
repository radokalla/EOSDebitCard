<aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          
          <!-- search form -->
         <!-- <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
              <input type="text" name="q" class="form-control" placeholder="Search...">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
              </span>
            </div>
          </form>-->
          <!-- /.search form -->
          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu">
            <li class="header">MAIN NAVIGATION</li>
            <li <?php if($heading=='dashboard'){echo "class='active'";} ?>><a href="dashboard.php"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
            <li <?php if($heading=='stores'){echo "class='active'";} ?>><a href="stores.php"><i class="fa fa-list"></i> <span>Store List</span></a></li>
             <li <?php if($heading=='import'){echo "class='active'";} ?>><a href="import.php"><i class="fa fa-download"></i> <span>Import/Export Stores</span></a></li>
            <li <?php if($heading=='import'){echo "class='active'";} ?>><a href="weedmaps.php"><i class="fa fa-download"></i> <span>Import Weed Maps Menu</span></a></li>
               <li class="treeview <?php if($heading=='sms'){echo "active";} ?>">
              <a href="#">
                <i class="fa fa-home"></i><span>SMS</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
             <ul class="treeview-menu">
                <li><a href="groups.php"><i class="fa fa-circle-o"></i> Groups</a></li>
                <li><a href="sms.php"><i class="fa fa-circle-o"></i> Sms List</a></li>
			 </ul>
         <!--   <li <?php if($heading=='categories'){echo "class='active'";} ?>><a href="categories.php"><i class="fa fa-list"></i> <span>Category List</span></a></li>-->
            <li <?php if($heading=='users'){echo "class='active'";} ?>><a href="users.php"><i class="fa fa-list"></i> <span>User's List</span></a></li>
            <li <?php if($heading=='admins'){echo "class='active'";} ?>><a href="adminusers.php"><i class="fa fa-users"></i> <span>Admin Users</span></a></li>
            <li <?php if($heading=='adds'){echo "class='active'";} ?>><a href="addslist.php"><i class="fa fa-list"></i> <span>Admin Adds</span></a></li>
            <li <?php if($heading=='reviews'){echo "class='active'";} ?>><a href="reviews.php"><i class="fa fa-file-text"></i> <span>Reviews</span></a></li>
            <li <?php if($heading=='reports'){echo "class='active'";} ?>><a href="reports.php"><i class="fa fa-file"></i> <span>Reports</span></a></li>
              <li <?php if($heading=='settings'){echo "class='active'";} ?>><a href="settings.php"><i class="fa fa-file"></i> <span>Settings</span></a></li>
             
              
                <li <?php if($heading=='newsletter'){echo "class='active'";} ?>><a href="newsletter/" target="_blank"><i class="fa fa-file"></i> <span>Newsletter</span></a></li>
           <!-- <li <?php if($heading=='menu'){echo "class='active'";} ?>><a href="menu.php"><i class="fa fa-dashboard"></i> <span>Menu</span></a></li>-->
            <li class="treeview <?php if($heading=='store'){echo "active";} ?>">
              <a href="#">
                <i class="fa fa-home"></i> <span>Store</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="subscription.php"><i class="fa fa-circle-o"></i> Subscriptions</a></li>
                <li><a href="store_users.php"><i class="fa fa-circle-o"></i> Store Users</a></li>
				<li><a href="user_cats.php"><i class="fa fa-circle-o"></i> User Categories</a></li>
				<li><a href="user_pro.php"><i class="fa fa-circle-o"></i> User Products</a></li> 
                <li><a href="subuser_trans.php"><i class="fa fa-circle-o"></i> Store Users Transactions</a></li>
              </ul>
            </li>

          </ul>
        </section>
        <!-- /.sidebar -->
      </aside>
<script>
/*	$(document).ready(function() {
		$('input:text').keyup(function() { 
		var regex = /^([a-zA-Z0-9]+\s?)*$/; ;
		
        if (!regex.test($('input').val())) 
		{
			$(this).val($(this).val().replace(/ +?/g, ''));
		}
		});
	});*/
</script>