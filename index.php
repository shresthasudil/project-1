<?php
/**
 * Created by PhpStorm.
 * User: shresthasudil
 * Date: 9/26/17
 * Time: 4:47 PM
 */

/*
 * This is index.php file. It includes the main 'Number Guesser Game'.
 * Access to this page requires authenticated login, thus it will redirect to login.php if user is not logged in.
 * When a user is logged in, 'Number Guesser Game' is accessible.
 * In 'Number Guesser Game', a random integer between the number of 0 to 100 is generated in the server side (PHP)
 * and it is passed to javascript (included in this same page). Only generating random number and checking the user
 * session is done in server side (PHP) here, all the calculation of game is done in javascript.
 * User is asked to guess the random number generated and has 10 tries to get it right.
 */

// Starts a new session or resumes existing session if available.
session_start();

// Check if session variable is set or not, redirect to login if not.
if (!isset($_SESSION['email']) || empty($_SESSION['email'])){
    header("location: login.php");
    exit;
}

// Generate a random integer between 0 and 100;
$random_int = rand(0, 100);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>
    <!-- Navbar with 'Welcome' message and 'Logout' link-->
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <p class="navbar-brand">Welcome! <?php echo $_SESSION['first_name']; ?>! to the Number Guesser Game</p>
            </div>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="logout.php">Logout</a></li>    <!-- link to logout, destroy the session -->
            </ul>
        </div>
    </nav>

    <!-- Ask user to input their guess number -->
    <div class="form">
        <label for="guessNum">Guess a number between 1 to 100: </label>
        <input type="text" id="guessNum" class="guessNum"/>
        <button type="button" value="Submit" class="guessSubmit btn btn-primary">Submit</button>
    </div>
    <!-- div(s) for javascript to load respone later -->
    <div class="result">
        <p class="eachResult"></p>
        <p class="bigSmall"></p>
        <p class="remainingGuess"></p>
    </div>

    <script>
        var random_num = <?php echo $random_int; ?>;                // get the random number generated in server side to javascript

        // List of required variable to catch and calulate the response.
        var eachResult = document.querySelector('.eachResult');
        var result = document.querySelector('.result');
        var guessNum = document.querySelector('.guessNum');
        var bigSmall = document.querySelector('.bigSmall');
        var guessSubmit = document.querySelector('.guessSubmit');
        var remainingGuess = document.querySelector('.remainingGuess');
        var guessLeft = 10;                                         // allowed number of guesses per game.

        /*
         * Javascript function to check the user guessed number on click to 'submit' button
         */
        function checkGuess() {
            var userGuess = Number(guessNum.value);             // get the user guessed number from the form.

            /*
             * Check if the entered value is valid(between 0 to 100)
             * if valid, count the submission and calculate if guessed number is bigger or smaller than random number
             * if not valid, submission is not effected and user is asked to resubmit again.
             */
            if ((userGuess > 0) && (userGuess <= 100)) {
                // if user guesses the number, user wins the game and game is over.
                if (userGuess === random_num) {
                    eachResult.textContent = 'Congratulations!!! You have guessed the correct number.Please refresh the page to play again.';
                    eachResult.style.color = 'green';
                    bigSmall.textContent = '';
                    gameOver();     // call to gameOver() function to disable the input.
                } else if (guessLeft === 1) {       // if user runs out of number of submissions, game is over
                    eachResult.textContent = 'Sorry! Game Over! You are out of allowed number of guesses. Please refresh the page to play again.';
                    eachResult.style.color = 'red';
                    bigSmall.textContent = '';
                    gameOver();     // call to gameOver() function to disable the input.
                } else {                            // user has wrong guess.
                    eachResult.textContent = 'Your guess is wrong!';
                    eachResult.style.color = 'red';
                    // calculate if user guessed number is bigger or smaller than random number.
                    if(userGuess < random_num) {
                        bigSmall.textContent='Your guess is smaller than generated number.' ;
                    } else if(userGuess > random_num) {
                        bigSmall.textContent = 'Your guess is BIGGER than generated number. ';
                    }
                }
                guessLeft--;                            // subtract the allowed number of guesses by 1.
                remainingGuess.textContent = "Remaining guess left: " + guessLeft;
            } else {                    // if user inputs anything other than number between 0 to 100, its an invalid input.
                eachResult.textContent = 'Your guess is invalid, please enter number between 1 to 100. Remaining submission: ' + guessLeft;
                bigSmall.textContent = '';
            }   // end of if to validate user input.
        }   // end of function checkGuess()

        // event listener to trigger the checkGuess() function on click 'submit' button
        guessSubmit.addEventListener('click', checkGuess);

        /*
         * Function to disable the user input when the game is over.
         */
        function gameOver() {
            guessNum.disabled = true;           // disable the input form field
            guessSubmit.disabled = true;        // disable the 'submit' button
        }
    </script>
</body>
</html>