<?php
require 'widgets/header.php';
require 'init.php';
$db = new Database;
$conn = $db->connect();
$user = new User($conn);
?>

<?php if($user->is_logged()){?>
<a href="logout.php">logout</a>
<p class="alert-success"><?php echo $_GET['msg'] ?? ''; ?></p>
<?php }else{ ?>
<a href="register.php">register</a>
<a href="login.php">login</a>
<p class="alert-success"><?php echo $_GET['msg'] ?? '';} ?></p>
