<?php
session_start();
if(empty($_SESSION['logged_in']))
{
    header('Location: http://' . $_SERVER['HTTP_HOST'] . '/login.php');
    exit;
}
?>