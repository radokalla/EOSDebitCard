

<div class="col-md-8 login_form">

  <div class="memberlogin-wps col-md-12">

    <h2>Admin Login</h2>

    <div class="col-md-12">

      <p>Please login with your details</p>

      <?php echo validation_errors(); ?>

      <form method="post" role="form" id="admin-login-form" class="validate-form">

        <div class="form-group">

          <label for="exampleInputEmail1">Username</label>

          <input type="text" class="form-control required" id="username" onkeyup="gonext(this.event,1)" name="username" placeholder="Username">

        </div>

        <div class="form-group">

          <label for="exampleInputPassword1">Password</label>

          <input type="password" class="form-control required" id="password" onkeyup="gonext(this.event,2)"  name="password" placeholder="Password">

        </div>

        <div class="form-group">

          <button type="submit" class="btn btn-primary">LOGIN</button>

        </div>

      </form>

    </div>

  </div>

</div>
<script>
 function gonext(e,id)
  {
   if(keyCode==13) {
    if(id==1) {
   // e.preventDefault();
    document.getElementById('password').focus();
   }
    if(id==2) {
    //e.preventDefault();
    document.getElementById('admin-login-form').submit();
   }
   }
  }</script>