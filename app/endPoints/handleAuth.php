<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require '../../vendor/autoload.php';

//  don`t forget to prevent this page access without login  or register
require '../boot.php';

// instantiate Databese and User classes
$db = new Database;
$conn = $db->connect();
$user = new User($conn);

// handle register data

if(isset($_POST['register']))
{
  $errors = [];
  extract($_POST);

  // validate the firstname field

  if(empty($firstname))
  {
    $errors['firstname'] = 'First name is required';
  }
  else {
    if(!preg_match('/^\w{4,}$/', $firstname))
    {
      $errors['firstname'] = 'letters and numbers and underscore only allowed, At least 6 characters';
    }
  }

  // validate the lastname field
  if(empty($lastname))
  {
    $errors['lastname'] = 'Last name is required';
  }
  else {
    if(!preg_match('/^\w{4,}$/', $lastname))
    {
      $errors['lastname'] = 'letters and numbers and underscore only allowed, At least 6 characters';
    }
  }

  // validate user email
  if(empty($email))
  {
    $errors['email'] = 'Email Field is required';
  }
  else {
    if(!filter_var($email, FILTER_VALIDATE_EMAIL))
    {
      $errors['email'] = 'Please, Enter a valid email';
    }
    else {
      $user->email = $email;
      if($user->email_exists())
      {
        $errors['email']  = 'This email is alraedy exists';
      }
    }
  }

  //  validate the password
  if(empty($password))
  {
    $errors['password'] = 'Password is required';
  }
  else {
    if(!preg_match('/^(\w|\$|&|-|%){8,24}$/', $password))
    {
      $errors['password'] = 'Password has to be at least 8 characters and at most 24 characters';
    }
  }

  //  confirm the password
  if(empty($re_password))
  {
    $errors['re_password'] = 'Please, re-enter the Password';
  }
  else {
    if($re_password != $password)
    {
      $errors['re_password'] = 'This field has to match the password';
    }
  }


  if(!empty($errors))
  {
    header("Location: ../../register.php?fn_err={$errors['firstname']}&ln_err={$errors['lastname']}&em_err={$errors['email']}&pass_err={$errors['password']}&re_pass_err={$errors['re_password']}&fn={$firstname}&ln={$lastname}&em={$email}&pass={$password}&re_pass={$re_password}");
  }
  else {
    $email_code = password_hash($firstname + microtime(), PASSWORD_BCRYPT);
    $user->firstname = $firstname;
    $user->lastname = $lastname;
    $user->email = $email;
    $user->password = password_hash($password, PASSWORD_BCRYPT);
    $user->type = $type;
    $user->email_code = $email_code;
    $user->save();

    //  mail the user to activate his account
    $mail = new PHPMailer(true);
    try {
      $mail->isSMTP();
      $mail->Host       = 'smtp.gmail.com';
      $mail->SMTPAuth   = true;
      $mail->Username   = 'badabedobasher@gmail.com';
      $mail->Password   = '01283452073';
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
      $mail->Port       = 465;
      $mail->setFrom('badabedobasher@gmail.com', 'Sender Abdallah');
      $mail->addAddress('badabedobasher@gmail.com', 'recipient Abdallah');
      $mail->isHTML(true);
      $mail->Subject = 'Email activation';
      $mail->Body    = 'hello,' . $firstname . '
      <br><br>you need to activate your account to be able to use our website. <br><br>Please, click the link below.<br><br>http://localhost/islam/activate.php?email=' . $email . '&email_code=' . $email_code;
      $mail->send();
    } catch (Exception $e) {
          echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
      }
     header("Location: ../../index.php?msg=you have registered successfully.Plesae, check your inbox to verify your email");
  }
}


// handle login data
elseif (isset($_POST['login'])) {
  extract($_POST);
  $errors = [];

  //  validate email
  if(empty($email))
  {
    $errors['email'] = 'Email field is required';
  }
  else {
    $user->email = $email;
    if(!$user->email_exists())
    {
      $errors['email'] = 'This email doesn`t exist';
    }
    else {
      $user->email = $email;
      if(!$user->is_active())
      {
        $errors['email'] = 'Your email is not active. Please, check your inbox';
      }
      else {
        $user->email = $email;
        if(!password_verify($password, $user->get_password_by_email()))
        {
          $errors['password'] = 'Password is incorrect';
        }
      }
    }
  }

  //  validate password
  if(empty($password))
  {
    $errors['password'] = 'Password Field is required';
  }

  // if no errors login the user else redirect to the login page
  if(!empty($errors))
  {
    header("Location: ../../login.php?email_err={$errors['email']}&password_err={$errors['password']}&email={$email}&password={$password}");
  }
  else {
    $user->email = $email;
    $data = $user->get_data_by_email();
    if(isset($remember))
    {
      setcookie('token', password_hash($data['user_id'], PASSWORD_BCRYPT) , time() + (86400 * 30), '/');
    }
    else {
      $_SESSION['id'] = $data['user_id'];
    }

    header('Location: ../../index.php');

  }
}
else {
  header('Location: ../../index.php?msg=you don`t have permission to access this resource');
}
 ?>
