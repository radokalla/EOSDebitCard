

<div class="col-md-8 login_form">

  <div class="memberlogin-wps col-md-12">

    <h2>Partner/Employee Login</h2>

    <div class="col-md-12">

      <p>Please login with your details</p>

      <?php echo validation_errors(); ?>

      <form method="post" role="form" id="admin-login-form" class="validate-form">
      
      <div class="form-group">

          <label for="exampleInputEmail1">User type</label>

          <select class="form-control" id="userType" name="userType" required="required">
          <option value="employee">Employee</option>
          <option value="partner">Partner</option>
          </select>

        </div>

        <div class="form-group">

          <label for="exampleInputEmail1">Username</label>

          <input type="text" class="form-control" id="username" name="username" placeholder="Username" required="required">

        </div>

        <div class="form-group">

          <label for="exampleInputPassword1">Password</label>

          <input type="password" class="form-control" id="password" name="password" placeholder="Password" required="required">

        </div>

        <div class="form-group">

          <button type="submit" class="btn btn-primary">LOGIN</button>

        </div>

      </form>

    </div>

  </div>

</div>

