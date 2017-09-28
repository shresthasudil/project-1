<?php
/**
 * Created by PhpStorm.
 * User: shresthasudil
 * Date: 9/26/17
 * Time: 6:28 PM
 */
// import the required config.php file for database connections and query.
require_once 'includes/config.php';

// initialize the form field and its error field variable.
$first_name = $last_name = $email = $password = "";
$first_name_err = $last_name_err = $email_err = $password_err = $confirm_password_err = "";

/*
 * Process the POST request for the register form submission.
 * Also, each field includes a server side validation here.
 */
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Server side validation to check if the 'first_name' field is empty.
    if (empty(trim($_POST["first_name"]))) {
        $first_name_err = "Please enter your First Name.";
    } else {    // if not empty, get first_name value.
        $first_name = trim($_POST["first_name"]);
    }

    // Server side validation to check if the 'last_name' field is empty.
    if (empty(trim($_POST["last_name"]))) {
        $last_name_err = "Please enter your Last Name.";
    } else {    // if not empty, get last_name value.
        $last_name = trim($_POST["last_name"]);
    }

    // Server side validation to check if the 'email' field is empty.
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter your email.";
    } else {    // if not empty, query the database to check if email has been registered previously.
        $sql = "SELECT id FROM users WHERE email = :email";     // query to check if email exists.

        // prepare to execute the above query with required fields.
        if ($stmt = $pdo->prepare($sql)) {
            $stmt->bindParam(':email', $param_email, PDO::PARAM_STR);       // bind parameter for query
            $param_email = trim($_POST["email"]);
            // execute the query
            if ($stmt->execute()) {
                // check if query has results, if query has results, means the email has been used previously
                if ($stmt->rowCount() == 1) {
                    $email_err = "This email has already been registered.";
                } else {    // if no results then get email value.
                    $email = trim($_POST["email"]);
                }
            } else {
                echo "Error in register.php 1: Catch here.";        // if anything fails... catch here.
            }
        }
        unset($stmt);       // unset $stmt for this form submission
    }

    // Server side validation to check if the 'password' field is empty.
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } else {    // if not empty, get password value.
        $password = trim($_POST["password"]);
    }

    // Server side validation to check if the 'confirm_password' field is empty.
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm password.";
    } else {    // if not empty, verify 'password' and 'confirmed_password' field has same value
        $confirm_password = trim($_POST["confirm_password"]);
        if ($password != $confirm_password) {
            $confirm_password_err = "Passwords does not match.";
        }
    }

    /*
     * Check if there are any errors before registering user by inserting values into the database.
     */
    if (empty($first_name_err) && empty($last_name_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err)) {
        // database query with insert statement to register new user.
        $sql = "INSERT INTO users (first_name, last_name, email, password) VALUES (:first_name, :last_name, :email, :password)";

        // prepare to execute the above query with required fields.
        if ($stmt = $pdo->prepare($sql)) {
            // bind the required fields for the query
            $stmt->bindParam(':first_name', $param_first_name, PDO::PARAM_STR);
            $stmt->bindParam(':last_name', $param_last_name, PDO::PARAM_STR);
            $stmt->bindParam(':email', $param_email, PDO::PARAM_STR);
            $param_first_name = $first_name;
            $param_last_name = $last_name;
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT);
            // execute the query
            if ($stmt->execute(array(":first_name" => $param_first_name, ":last_name" => $param_last_name, ":email" => $param_email, ":password" => $param_password))) {
                // when successful, start a new session and pass first_name and email to index.php
                session_start();
                $_SESSION['first_name'] = $first_name;
                $_SESSION['email'] = $email;
                header("location: index.php");                    //need to login later
            } else {
                echo "Error in register.php 2: Catch here 2.";
            }
        }
        unset($stmt);   // unset $stmt for this form submission
    }
    unset($pdo);    // unset $pdo for this form submission
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
    <!-- Login Form -->
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
                          action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">   <!-- POST request process in the same file. -->

                        <!-- First Name Field, also has HTML5 client side validation for empty input -->
                        <div class="form-group <?php echo (!empty($first_name_err)) ? 'has-error' : ''; ?>" >
                            <label class="col-md-4 control-label">First Name:<sup>*</sup></label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="first_name" value="<?php echo $first_name; ?>" required>
                                <span class="help-block"><?php echo $first_name_err; ?></span>
                            </div>
                        </div>

                        <!-- Last Name Field, also has HTML5 client side validation for empty input -->
                        <div class="form-group <?php echo (!empty($last_name_err)) ? 'has-error' : ''; ?>" >
                            <label class="col-md-4 control-label">Last Name:<sup>*</sup></label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="last_name" value="<?php echo $last_name; ?>" required>
                                <span class="help-block"><?php echo $last_name_err; ?></span>
                            </div>
                        </div>

                        <!-- Email Field, also has HTML5 client side validation for email type -->
                        <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>" >
                            <label class="col-md-4 control-label">Email:<sup>*</sup></label>
                            <div class="col-md-6">
                                <input type="email" class="form-control" name="email" value="<?php echo $email; ?>" required>
                                <span class="help-block"><?php echo $email_err; ?></span>
                            </div>
                        </div>

                        <!-- Password Field, also has HTML5 client side validation for empty input -->
                        <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                            <label class="col-md-4 control-label">Password:<sup>*</sup></label>
                            <div class="col-md-6">
                                <input type="password" class="form-control" name="password" value="<?php echo $password; ?>" required>
                                <span class="help-block"><?php echo $password_err; ?></span>
                            </div>
                        </div>

                        <!-- Confirm Password Field, also has HTML5 client side validation for empty input -->
                        <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                            <label class="col-md-4 control-label">Confirm Password:<sup>*</sup></label>
                            <div class="col-md-6">
                                <input type="password" class="form-control" name="confirm_password" value="<?php echo $confirm_password; ?>">
                                <span class="help-block"><?php echo $confirm_password_err; ?></span>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary" style="margin-right: 15px;">Register</button>
                                <input type="reset" class="btn btn-default" value="Reset">
                            </div>
                        </div>

                        <!-- Link to Login for the site, if user already have an account-->
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