<?php 
$db_host = 'localhost';
$db_user = 'root';
$db_password = '';
$db_name = 'upload_file_image';


$db = new PDO("mysql:host={$db_host};dbname={$db_name};",$db_user, $db_password);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>