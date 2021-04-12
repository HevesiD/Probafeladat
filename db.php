<?php
$dbhost = 'localhost';
$dbname = 'root';
$dbpassword = '';
$db = 'proba';
$connection = new PDO('mysql:host='.$dbhost.';dbname='.$db, $dbname, $dbpassword);
?>