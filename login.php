<?php require 'widgets/header.php';
require 'init.php';
$db = new Database;
$conn = $db->connect();
$user = new User($conn);
if($user->is_logged())
{
  header("Location: index.php");
}
?>
<?php extract($_GET);?>
<link rel="stylesheet" href="assets/login.css?<?php echo time(); ?>">
<h1 id='signin'>Sign in</h1>
<div class="login-container">
  <form action="app/endPoints/handleAuth.php" method="post" class="login-form">
    <div class="form-group">
      <label for="email">Email</label><br>
      <input type="text" name="email" value="<?php echo $email ?? '' ;?>">
      <span><?php echo $email_err ?? ''; ?></span>
    </div>

    <div class="form-group">
      <label for="password">Password</label><br>
      <input type="password" name="password" value="<?php echo $password ?? '' ;?>">
      <span><?php echo $password_err ?? ''; ?></span>
    </div>

    <div class='form-group'>
      <input type="checkbox" name="remember"><span id='remember'>remember me</span>
    </div>

    <div class="form-group">
      <input type="submit" name="login" value="login">
    </div>

    <div class="form-group">
      <p id='signup'>You don`t have an account?<a href="register.php"> Sign Up</a></p>
      <p id='forgot-password'><a href='#'>Forgot password?</a></p>
    </div>
  </form>
</div>
