<?php
 
/**
* Database config variables
*/
define("DB_HOST", "localhost");
define("DB_USER", "campusconnect");
define("DB_PASSWORD", "campusconnect");
define("DB_DATABASE", "db_cc");

$con=mysql_connect(DB_HOST,DB_USER,DB_PASSWORD);
if(!$con)
{
	die("server connection failed");
}

$db_con=mysql_select_db(DB_DATABASE,$con);
if(!$db_con)
{
	die("DB connection failed");
}

?>