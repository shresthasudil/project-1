<?php
/**
 * Created by PhpStorm.
 * User: shresthasudil
 * Date: 9/28/17
 * Time: 12:47 AM
 */

//
session_start();
//var_dump($_SESSION);

$random_int = rand(0, 100);


// check if session variable is set or not, redirect to login if not.
if (!isset($_SESSION['email']) || empty($_SESSION['email'])){
    header("location: login.php");
    exit;
}
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
<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <p class="navbar-brand">Welcome! <?php echo $_SESSION['first_name']; ?>! to the Number Guesser Game</p>
        </div>
        <ul class="nav navbar-nav navbar-right">
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
</nav>
<div class="form">
    <label for="guessNum">Guess a number between 1 to 100: </label>
    <input type="text" id="guessNum" class="guessNum"/>
    <input type="submit" value="Submit" class="guessSubmit"/>
</div>
<div class="result">
    <p class="eachResult"></p>
    <p class="bigSmall"></p>
    <p class="remainingGuess"></p>
</div>

<script>
    var random_num = <?php echo $random_int; ?>;
    //var random_num = 45;
    var eachResult = document.querySelector('.eachResult');
    var result = document.querySelector('.result');
    var guessNum = document.querySelector('.guessNum');
    var bigSmall = document.querySelector('.bigSmall');
    var guessSubmit = document.querySelector('.guessSubmit');
    var remainingGuess = document.querySelector('.remainingGuess');
    var guessCount = 1;
    var guessLeft = 10;


    function checkGuess() {
        var userGuess = Number(guessNum.value);

        if ((userGuess > 0) && (userGuess <= 100)) {
            if (userGuess === random_num) {
                eachResult.textContent = 'Congratulations!!! You have guessed the correct number.Please refresh the page to play again.';
                bigSmall.textContent = '';
                gameOver();
            } else if (guessLeft === 1) {
                eachResult.textContent = 'Sorry! Game Over! You are out of allowed number of guesses. Please refresh the page to play again.';
                bigSmall.textContent = '';
                gameOver();
            } else {
                eachResult.textContent = 'Your guess is wrong!';
                if(userGuess < random_num) {
                    bigSmall.textContent='Your guess is smaller than generated number.' ;
                } else if(userGuess > random_num) {
                    bigSmall.textContent = 'Your guess is BIGGER than generated number. ';
                }
            }

            guessLeft--;
            remainingGuess.textContent = "Remaining guess left: " + guessLeft;


        } else {
            remainingGuess
            eachResult.textContent = 'Your guess is invalid, please enter number between 1 to 100. Remaining submission: ' + guessLeft;
            bigSmall.textContent = '';
        }


    }

    guessSubmit.addEventListener('click', checkGuess);

    function gameOver() {
        guessNum.disabled = true;
        guessSubmit.disabled = true;
    }

</script>
</body>
</html>