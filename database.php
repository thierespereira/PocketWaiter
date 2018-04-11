<?php
	// DB
    try {
        //Type of DB
        $DB_TYPE = 'mysql';
        //Host Name
        $DB_HOST = 'localhost';
        //Host Username
        $DB_USER = 'root';
        //Host Password
        $DB_PASS = '';
        //DB Name
        $DB_NAME = 'pocketwaiter'; 

        $connStr = "mysql:host=".$DB_HOST.";port=3306;dbname=".$DB_NAME;
        $DBH = new PDO($connStr,$DB_USER,$DB_PASS);
        } catch(PDOException $e) {
        	$error .= $e;
        	echo $e;
        }
?>
