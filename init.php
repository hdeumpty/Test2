<?php

// connexion à la base de données
$db_username = 'root';
$db_password = '';
$db_name     = 'coc_dev';
$db_host     = 'localhost';

/*
$db_username = 'ztneozl831';
$db_password = '3mOK9Q421rMluv5';
$db_name     = 'ztneozl831';
$db_host     = 'ztneozl831.mysql.db';
*/

$db = mysqli_connect($db_host, $db_username, $db_password,$db_name)
       or die("Échec de la connexion à la base de donnés");
?>
