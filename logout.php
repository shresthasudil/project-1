<?php
/**
 * Created by PhpStorm.
 * User: shresthasudil
 * Date: 9/28/17
 * Time: 12:49 AM
 */

//
session_start();
$_SESSION = array();

session_destroy();

header("location: index.php");
exit;
?>