<?php
require 'authentication.php'; // admin authentication check 

// auth check
if(isset($_SESSION['admin_id'])){
  $user_id = $_SESSION['admin_id'];
  $user_name = $_SESSION['admin_name'];
  $security_key = $_SESSION['security_key'];
  if ($user_id != NULL && $security_key != NULL) {
    header('Location: task-info.php');
  }
}

if(isset($_POST['login_btn'])){
 $info = $obj_admin->admin_login_check($_POST);
}

$page_name="Login";
include("include/login_header.php");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Task Management System</title>
   <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            font-family: 'Optima', sans-serif;
        }
		.title {
			text-align:center;
		}
		
        .well {
			
            background-color: #fff;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
        }

        .form-heading {
            margin-bottom: 20px;
        }

		.form-custom-login {
			border-radius: 10px;
		}
        .form-custom-login .form-group {
            margin-bottom: 15px;
			border:none;
        }

        .form-custom-login .form-control {
            border-radius: 10px;
            border: 1px solid rgb(107, 238, 168);
            padding: 10px;
            font-size: 16px;
			background:white;
        }

        .form-custom-login .btn {
            border-radius: 30px;
            padding: 10px 20px;
            font-size: 16px;
            background-color:rgb(107, 238, 168);
            color: #fff;
            border: none;
            cursor: pointer;
        }
		.form-custom-login .btn:hover {
            border-radius: 30px;
            padding: 10px 20px;
            font-size: 16px;
            background-color:white;
            color: rgb(107, 238, 168);
            border-style: solid;
            border-color: rgb(107, 238, 168);
            cursor: pointer;
        }
    </style>
</head>
<body>
<h1 class="title"style="text-align:center;margin-left:-200px;margin-bottom:70px;margin-top:-50px;color: rgb(107, 238, 168);font-size: 35px;">STUDENT TASK MANAGEMENT SYSTEM</h1>
    <div class="row">
        <div class="col-md-4 col-md-offset-3">
            <div class="well">
                <h2 style="text-align: center;position:relative; margin-bottom: 20px;font-size: 25px;color:rgb(107, 238, 168);">LOGIN PANEL</h2>
                	<form class="form-horizontal form-custom-login" action="" method="POST">
                    <?php if(isset($info)){ ?>
                        <h5 class="alert alert-danger"><?php echo $info; ?></h5>
                    <?php } ?>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Username" name="username" required/>
                    </div>
                    <div class="form-group" ng-class="{'has-error': loginForm.password.$invalid && loginForm.password.$dirty, 'has-success': loginForm.password.$valid}">
                        <input type="password" class="form-control" placeholder="Password" name="admin_password" required/>
                    </div>
                    <button type="submit" name="login_btn" class="btn btn-info pull-right">Login</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>


<?php

include("include/footer.php");

?>
