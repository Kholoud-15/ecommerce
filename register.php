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
<link rel="stylesheet" href="assets/signup.css?<?php echo time(); ?>">
<h1>Signup</h1>
<div class="container">
  <form action="app/endPoints/handleAuth.php" method="post" class="form-row">
      <div class='row'><label for="firstname">First Name</label></div>
      <div class='row'><input type="text" name="firstname" value='<?php echo $_GET['fn'] ?? ''; ?>'></div>
      <span><?php echo $_GET['fn_err'] ?? '';  ?></span>
      <div class='row'><label for="lastname">Last Name</label></div>
      <div class='row'><input type="text" name="lastname" value='<?php echo $_GET['ln'] ?? ''; ?>'></div>
      <span><?php echo $_GET['ln_err'] ?? '';  ?></span>
      <div class='row'><label for="email">Email</label></div>
      <div class='row'><input type="text" name="email" value='<?php echo $_GET['em'] ?? ''; ?>'></div>
      <span><?php echo $_GET['em_err'] ?? '';  ?></span>
      <div class='row'><label for="Select">Account Type</label></div>
      <div class='row'><select name="type">
        <option value="user">User</option>
        <option value="seller">Affiliate</option>
      </select> </div>
      <div class='row'><label for="password">Password</label></div>
      <div class='row'><input type="password" name="password" value='<?php echo $_GET['pass'] ?? ''; ?>'></div>
      <span><?php echo $_GET['pass_err'] ?? '';  ?></span>
      <div class='row'><label for="re_password">Repeat Password</label></div>
      <div class='row'><input type="password" name="re_password" value='<?php echo $_GET['re_pass'] ?? ''; ?>'></div>
      <span><?php echo $_GET['re_pass_err'] ?? '';  ?></span>
      <div class='row'><input type="submit" name="register" value="Signup" id='signup'></div>
      <div class='row'><span>You already have an account? <a href="login.php">Login</a></span></div>
  </form>
</div>
