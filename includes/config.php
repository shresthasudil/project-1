<?php
/**
 * Created by PhpStorm.
 * User: shresthasudil
 * Date: 9/26/17
 * Time: 4:45 PM
 */
/*
 * Using a PHP Data Object (PDO) driver to connect to MySQL database.
 * PDO provides options to connect to multiple database types.
 */

/* Define the database credential. */
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'project_1');

/* Attempt to connect database with defined DB_* values or catch errors if any. */
try {
    $pdo = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);

    // Set PDO attribute to report errors
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection Failed. Error: " . $e->getMessage());
}
?>