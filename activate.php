<?php
require 'init.php';
$db = new Database;
$conn = $db->connect();
$user = new User($conn);
if(isset($_GET['email']) && isset($_GET['email_code']))
{
  extract($_GET);
  if(!empty($email) && !empty($email_code))
  {
    $user->email = $email;
    if(!$user->email_exists())
    {
      $message = 'This email doesn`t exist';
    }
    else {
      $user->email = $email;
      $user->email_code = $email_code;
      if(!$user->activate_email())
      {
        $message = 'Your Email is already activated';
      }
      else {
        $message = 'Your email has been activated successfully';
      }
    }
    header('Location: index.php?msg='. $message);
  }
}
else {
  header('Location: index.php?msg=You don`t have permission to access this page');
}


?>
