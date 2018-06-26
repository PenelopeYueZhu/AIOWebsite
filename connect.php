<?php
//connect.php
session_start();
$server = 'localhost';
$username   = 'Panda';
$password   = '0000';
$database   = 'test_forum_db';
$_SESSION['link'] = mysqli_connect($server, $username,  $password, $database);
//$link = mysqli_connect($server, $username,  $password, $database);

if(!$_SESSION['link'])
{
    exit('Error: could not establish database connection');
}
if(!mysqli_select_db($_SESSION['link'], $database))
{
    exit('Error: could not select the database');
}
?>
