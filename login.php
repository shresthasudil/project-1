<?php
/**
 * Created by PhpStorm.
 * User: shresthasudil
 * Date: 9/28/17
 * Time: 12:49 AM
 */

require_once 'includes/config.php';

$email = $password = "";
$email_err = $password_err = "";

//
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //
    if(empty(trim($_POST["email"]))) {
        $email_err = "Please enter your email.";
    } else {
        $email = trim($_POST["email"]);
    }

    //
    if (empty(trim($_POST['password']))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate entered credentials.
    if (empty($email_err) && empty($password_err)) {
        //
        $sql = "SELECT first_name, email, password FROM users WHERE email = :email";

        if ($stmt = $pdo->prepare($sql)) {
            //
            //$stmt->bindParam(':first_name', $param_first_name, PDO::PARAM_STR);
            $stmt->bindParam(':email', $param_email, PDO::PARAM_STR);
            //
            $param_email = trim($_POST["email"]);

            //
            if ($stmt->execute()) {
                //
                if ($stmt->rowCount() == 1) {
                    if ($row = $stmt->fetch()) {
                        //var_dump($row);
                        //die();
                        $hashed_password = $row['password'];
                        if (password_verify($password, $hashed_password)) {
                            /* Password is correct, start new session, save email */
                            session_start();
                            $_SESSION['first_name'] = $row['first_name'];
                            $_SESSION['email'] = $email;
                            header("location: index.php");
                        } else {
                            //
                            $password_err = "Password is not valid.";
                        }
                    }
                } else {
                    $email_err = "User not found.";
                }
            } else {
                echo "Error in login.php: Catch here 1";
            }
        }
        //
        unset($stmt);
    }
    //
    unset($pdo);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
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
                    <h3>Number Guesser Game Login</h3>
                    <p>Login using email and password.</p>
                </div>
                <form class="form-horizontal"
                      role="form"
                      method="POST"
                      action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

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
                            <input type="password" class="form-control" name="password" required>
                            <span class="help-block"><?php echo $password_err; ?></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            <button type="submit" class="btn btn-primary" style="margin-right: 15px;">Login</button>
                        </div>
                    </div>
                    <div class="col-md-6 col-md-offset-4">
                        <p>Don't have an account? <a href="register.php">Register</a>.</p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>