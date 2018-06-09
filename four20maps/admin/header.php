<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $lang['STORE_FINDER']; ?> | <?php echo $lang['ADMIN_STORE_LIST']; ?></title>
     <link rel="stylesheet" href="http://www.four20maps.com/css/style-four20.css">
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="dist/css/font-awesome.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="plugins/iCheck/flat/blue.css">
    <!-- Morris chart -->
    <link rel="stylesheet" href="plugins/morris/morris.css">
    <!-- jvectormap -->
    <link rel="stylesheet" href="plugins/jvectormap/jquery-jvectormap-1.2.2.css">
    <!-- Date Picker -->
    <link rel="stylesheet" href="plugins/datepicker/datepicker3.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker-bs3.css">
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
    <link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
	<link rel="stylesheet" href="js/bootstrap-colorpicker.js">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]-->
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <!--[endif]-->
   
    <script src="plugins/jQuery/jquery-ui.min.js"></script>
  
  </head>
  <body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
<header class="main-header">
        <!-- Logo -->
        <a href="dashboard.php" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"><b>F20M</b></span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><img alt="Logo" src="dist/img/logo.png"></span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
             
              <!-- User Account: style can be found in dropdown.less -->
              <li class="dropdown user user-menu" style="background-color: #3C8DBC">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <?php
				  include_once './includes/config.inc.php';
				  include_once './includes/class.database.php';
				  $db = db_connect();
					auth();
					?>
                  <span><?php echo $_SESSION['User']['username']; ?> <i class="fa fa-angle-down"></i></span>
                </a>
                <ul class="dropdown-menu">
                  <!-- User image -->
                  <li class="user-header">
                    
                    <p>
                      <?php echo $_SESSION['User']['username']; ?>
                      <small>Admin For Four20Maps</small>
                    </p>
                  </li>
                  <!-- Menu Body -->
				  <!--
                  <li class="user-body">
                    <div class="col-xs-4 text-center">
                      <a href="#">Followers</a>
                    </div>
                    <div class="col-xs-4 text-center">
                      <a href="#">Sales</a>
                    </div>
                    <div class="col-xs-4 text-center">
                      <a href="#">Friends</a>
                    </div>
                  </li>
				  -->
                  <!-- Menu Footer-->
                  <li class="user-footer">
                    <!--<div class="pull-left">
                      <a href="#" class="btn btn-default btn-flat">Profile</a>
                    </div>-->
                    <div class="text-center">
                      <a href="logout.php" class="btn btn-primary">Sign out</a>
                    </div>
                  </li>
                </ul>
              </li>
                          
            </ul>
          </div>
        </nav>
      </header>
	  