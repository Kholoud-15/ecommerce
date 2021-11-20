<?php
session_start();
session_destroy();
setcookie('token', '', time()-86400, '/');
header('Location: login.php');
?>
