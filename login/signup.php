<?php

require_once('../includes/config.php');

if($user -> is_logged_in()){ header('Location: ../admin/index.php'); }
?>
<?php
if(isset($_POST['submit'])){
	$username = trim($_POST['username']);
	$password = trim($_POST['password']);
	$cnf_password = trim($_POST['cnf_password']);
	$email = trim($_POST['email']);

	if($username == ''){
		$error[] = 'Please enter proper username';
	}

	if($email == ''){
		$error[] = 'Please enter email address';
	}

	if($password == ''){
		$error[] = 'Please enter password';
	}

	if($cnf_password == ''){
		$error[] = 'Please enter password for confirmation';
	}

	if($password != $cnf_password){
		$error[] = 'Entered passwords do not match';
	}

	if(!isset($error)){
		$hashed_password = $user -> password_hash($password, PASSWORD_BCRYPT);

		try{

			$stmt = $db -> prepare('INSERT into blog_members (username, email, password) VALUES (:username,:email,:password)');

			$stmt -> execute(array(
				':username' => $username,
				':password' => $hashed_password,
				':email' => $email
				));
            $_SESSION['username'] = $username;
			header('Location : ../user/index.php');
			exit;

		} catch(PDOException $e){
			echo $e -> getMessage();
		}

	}
}

	if(isset($error)){
		foreach($error as $error){
			echo '<p class="error">'.$error.'</p>';
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Simple Login Widget</title>
  
  
  <link rel='stylesheet prefetch' href='https://www.google.com/fonts#UsePlace:use/Collection:Roboto:400,300,100,500'>
<link rel='stylesheet prefetch' href='http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css'>
<link rel='stylesheet prefetch' href='https://www.google.com/fonts#UsePlace:use/Collection:Roboto+Slab:400,700,300,100'>

      <link rel="stylesheet" href="css/style.css">

  
</head>

<body>

<div id="dialog" class="dialog dialog-effect-in">
  <div class="dialog-front">
    <div class="dialog-content">
      <form id="register_form" class="dialog-form" action="" method="POST">
        <fieldset>
          <legend>Register</legend>
          <div class="form-group">
            <label for="user_username" class="control-label">Username:</label>
            <input type="text" id="user_username" value='<?php if(isset($error)){ echo $_POST['username'];} ?>'
            class="form-control" name="username"/> 
          </div>
          <div class="form-group">
            <label class="form-control">Email:</label>
            <input type="text" value='<?php if(isset($error)){ echo $_POST['email'];} ?>' class="form-control" name="email"/>
          </div>
          <div class="form-group">
            <label for="user_password" class="control-label">Password:</label>
            <input type="password" id="user_password" value='<?php if(isset($error)){ echo $_POST['password'];} ?>' class="form-control" name="password"/>
          </div>
          <div class="form-group">
            <label for="user_cnf_password" class="control-label">Confirm password:</label>
            <input type="password" id="user_cnf_password" value='<?php if(isset($error)){ echo $_POST['username'];} ?>' class="form-control" name="cnf_password"/>
          </div>
          <div class="pad-btm-20">
            <input type="submit" class="btn btn-default btn-block btn-lg" name="submit" value="Continue"/>
          </div>
          <div class="text-center">
            <p>Return to <a href="login.php" class="link user-actions"><strong>log in page</strong></a>?</p>
          </div>
        </fieldset>
      </form>
    </div>
  </div>
</div>
</body>
</html>

