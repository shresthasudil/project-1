<?php
/**
 * Created by PhpStorm.
 * User: shresthasudil
 * Date: 9/26/17
 * Time: 7:49 PM
 */
/*
 * Destory the current active session if any and redirect to index page.
 */
session_start();
$_SESSION = array();
session_destroy();
header("location: index.php");
exit;
?>