<?php
	// DB
    try {
        //Type of DB
        $DB_TYPE = 'mysql';
        //Host Name
        $DB_HOST = 'eu-cdbr-west-02.cleardb.net';
        //Host Username
        $DB_USER = 'b0ca8bf99caa9b';
        //Host Password
        $DB_PASS = '58c6a701';
        //DB Name
        $DB_NAME = 'heroku_4d5a25fca0bc072';

        $connStr = "mysql:host=".$DB_HOST.";port=3306;dbname=".$DB_NAME;
        $DBH = new PDO($connStr,$DB_USER,$DB_PASS);
        } catch(PDOException $e) {
        	$error .= $e;
        	echo $e;
        }
?>
