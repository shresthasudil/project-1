<?php
/**
 * Created by PhpStorm.
 * User: shresthasudil
 * Date: 9/28/17
 * Time: 12:45 AM
 */

/* Define the database credential. */
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'project_1');

/* Try connecting to database or catch the error. */
try {
    $pdo = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME, DB_USERNAME, DB_PASSWORD);
    // set PDO attribute to report error
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection Failed. Error: " . $e->getMessage());
}
?>