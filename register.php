<?php
/**
 * Created by PhpStorm.
 * User: shresthasudil
 * Date: 9/28/17
 * Time: 12:48 AM
 */

require_once 'includes/config.php';

//
$first_name = $last_name = $email = $password = "";
$first_name_err = $last_name_err = $email_err = $password_err = $confirm_password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate first_name input
    if (empty(trim($_POST["first_name"]))) {
        $first_name_err = "Please enter your First Name.";
    } else {
        $first_name = trim($_POST["first_name"]);
    }

    // Validate last_name input
    if (empty(trim($_POST["last_name"]))) {
        $last_name_err = "Please enter your Last Name.";
    } else {
        $last_name = trim($_POST["last_name"]);
    }

    // Validate email input and if it has been already used for previous registration.
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter your email.";
    } else {
        $sql = "SELECT id FROM users WHERE email = :email";

        if ($stmt = $pdo->prepare($sql)) {
            //
            $stmt->bindParam(':email', $param_email, PDO::PARAM_STR);

            //
            $param_email = trim($_POST["email"]);

            //
            if ($stmt->execute()) {

                if ($stmt->rowCount() == 1) {
                    $email_err = "This email has already been registerd.";
                } else {
                    $email = trim($_POST["email"]);
                }
            } else {
                echo "Error in register.php 1: Catch here.";
            }
        }

        unset($stmt);
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if ($password != $confirm_password) {
            $confirm_password_err = "Passwords does not match.";
        }
    }

    // Check if any of the errors occours, if there are NO any *_err
    if (empty($first_name_err) && empty($last_name_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err)) {
        // insert user info into DB
        $sql = "INSERT INTO users (first_name, last_name, email, password) VALUES (:first_name, :last_name, :email, :password)";

        if ($stmt = $pdo->prepare($sql)) {
            //
            $stmt->bindParam(':first_name', $param_first_name, PDO::PARAM_STR);
            $stmt->bindParam(':last_name', $param_last_name, PDO::PARAM_STR);
            $stmt->bindParam(':email', $param_email, PDO::PARAM_STR);

            $param_first_name = $first_name;
            $param_last_name = $last_name;
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT);

            //
            if ($stmt->execute(array(":first_name" => $param_first_name, ":last_name" => $param_last_name, ":email" => $param_email, ":password" => $param_password))) {
                //
                session_start();
                $_SESSION['first_name'] = $first_name;
                $_SESSION['email'] = $email;
                header("location: index.php");                    //need to login later
            } else {
                echo "Error in register.php 2: Catch here 2.";
            }
        }

        unset($stmt);
    }
    unset($pdo);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
    </style>
</head>
<body>
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="col-md-6 col-md-offset-4">
                    <h3>Register to play Number Guesser Game</h3>
                    <p>Please fill this form to create an account.</p>
                </div>
                <form class="form-horizontal"
                      role="form"
                      method="post"
                      action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

                    <div class="form-group <?php echo (!empty($first_name_err)) ? 'has-error' : ''; ?>" >
                        <label class="col-md-4 control-label">First Name:<sup>*</sup></label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="first_name" value="<?php echo $first_name; ?>" required>
                            <span class="help-block"><?php echo $first_name_err; ?></span>
                        </div>
                    </div>

                    <div class="form-group <?php echo (!empty($last_name_err)) ? 'has-error' : ''; ?>" >
                        <label class="col-md-4 control-label">Last Name:<sup>*</sup></label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="last_name" value="<?php echo $last_name; ?>" required>
                            <span class="help-block"><?php echo $last_name_err; ?></span>
                        </div>
                    </div>

                    <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>" >
                        <label class="col-md-4 control-label">Email:<sup>*</sup></label>
                        <div class="col-md-6">
                            <input type="email" class="form-control" name="email" value="<?php echo $email; ?>" required>
                            <span class="help-block"><?php echo $email_err; ?></span>
                        </div>
                    </div>

                    <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                        <label class="col-md-4 control-label">Password:<sup>*</sup></label>
                        <div class="col-md-6">
                            <input type="password" class="form-control" name="password" value="<?php echo $password; ?>" required>
                            <span class="help-block"><?php echo $password_err; ?></span>
                        </div>
                    </div>

                    <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                        <label class="col-md-4 control-label">Confirm Password:<sup>*</sup></label>
                        <div class="col-md-6">
                            <input type="password" class="form-control" name="confirm_password" value="<?php echo $confirm_password; ?>">
                            <span class="help-block"><?php echo $confirm_password_err; ?></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            <button type="submit" class="btn btn-primary" style="margin-right: 15px;">Register</button>
                            <input type="reset" class="btn btn-default" value="Reset">
                        </div>
                    </div>
                    <div class="col-md-6 col-md-offset-4">
                        <p>Already have an account? <a href="login.php">Login</a>.</p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>