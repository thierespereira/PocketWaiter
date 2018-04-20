<?php
	// DB
    try {
        //Type of DB
        $DB_TYPE = 'mysql';
        //Host Name
        $DB_HOST = 'zwgaqwfn759tj79r.chr7pe7iynqr.eu-west-1.rds.amazonaws.com';
        //Host Username
        $DB_USER = 'sx99dk46xxn7t3bf';
        //Host Password
        $DB_PASS = 'at0coe8avypl2e1n';
        //DB Name
        $DB_NAME = 'dclfxlmdymnwb73w';

        $connStr = "mysql:host=".$DB_HOST.";port=3306;dbname=".$DB_NAME;
        $DBH = new PDO($connStr,$DB_USER,$DB_PASS);
        } catch(PDOException $e) {
        	$error .= $e;
        	echo $e;
        }
?>
