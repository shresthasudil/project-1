<?php
/**
 * Created by PhpStorm.
 * User: shresthasudil
 * Date: 9/26/17
 * Time: 5:49 PM
 */
// import the required config.php file for database connections and query.
require_once 'includes/config.php';

// initialize the form field and its error field variable.
$email = $password = "";
$email_err = $password_err = "";

/*
 * Process the POST request for the login form submission.
 * Also, each field includes a server side validation here.
 */
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Server side validation to check if the 'email' field is empty.
    if(empty(trim($_POST["email"]))) {
        $email_err = "Please enter your email.";
    } else {    // if not empty, get email value.
        $email = trim($_POST["email"]);
    }

    // Server side validation to check if the 'password' field is empty.
    if (empty(trim($_POST['password']))) {
        $password_err = "Please enter your password.";
    } else {    // if not empty, get password value.
        $password = trim($_POST["password"]);
    }

    /*
     * Check if the provided email and password is registered in the database.
     */
    if (empty($email_err) && empty($password_err)) {
        // database query to get user information.
        $sql = "SELECT first_name, email, password FROM users WHERE email = :email";

        // prepare to execute the above query with required fields.
        if ($stmt = $pdo->prepare($sql)) {
            $stmt->bindParam(':email', $param_email, PDO::PARAM_STR);   // bind the parameter
            $param_email = trim($_POST["email"]);
            // execute the query
            if ($stmt->execute()) {
                // check if there is query results, means the email is valid in database.
                if ($stmt->rowCount() == 1) {
                    // when user is found, get its hashed_password from database
                    if ($row = $stmt->fetch()) {
                        $hashed_password = $row['password'];
                        // check if the password provided in login form matches the password in database,
                        // if matches, start new session and pass first_name and email to the session for index.php page
                        if (password_verify($password, $hashed_password)) {
                            session_start();
                            $_SESSION['first_name'] = $row['first_name'];
                            $_SESSION['email'] = $email;
                            header("location: index.php");
                        } else {        // error if password does not matches.
                            $password_err = "Password is not valid.";
                        }
                    }
                } else {        // email is not found in database, thus user is not found.
                    $email_err = "User not found.";
                }
            } else {
                echo "Error in login.php: Catch here 1";        // if everything fails... catch here.
            }
        }
        unset($stmt);       // unset $stmt for this session
    }
    unset($pdo);        // unset $pdo for this session
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
    <!-- Login Form -->
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
                          action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">   <!-- POST request process in the same file. -->

                        <!-- Email Field, also has HTML5 client side validation for email type -->
                        <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>" >
                            <label class="col-md-4 control-label">Email:<sup>*</sup></label>
                            <div class="col-md-6">
                                <input type="email" class="form-control" name="email" value="<?php echo $email; ?>" required>
                                <span class="help-block"><?php echo $email_err; ?></span>
                            </div>
                        </div>

                        <!-- Password Field, also has HTML5 client side validation -->
                        <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                            <label class="col-md-4 control-label">Password:<sup>*</sup></label>
                            <div class="col-md-6">
                                <input type="password" class="form-control" name="password" required>
                                <span class="help-block"><?php echo $password_err; ?></span>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary" style="margin-right: 15px;">Login</button>
                            </div>
                        </div>

                        <!-- Link to Register for the site, if user does not have any account-->
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