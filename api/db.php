<?php
/**
 * @author      Xiaotian Le
 * @version     1.0
 * @since       2015-11-10
 */
require_once "util.php";

$db_servername = "localhost";	// Database server
$db_username = "dbadmin";    	// Database user name 
$db_password = "123456";     	// Database user password

$db_dbname = "bowling";      	// Scheme name
$db_table_bowlers = "bowlers";
$db_table_leagues = "leagues";
$db_table_league_bowlers = "league_members";
$db_table_lotteries = "lotteries";

function connectHost() {
	global $db_servername, $db_username, $db_password;
	try {
		return new PDO("mysql:host=$db_servername", $db_username, $db_password);
	}
	catch(PDOException $e) {
		processError($e);
	}
}

function connectDb() {
	global $db_dbname;
	try {
		$conn = connectHost();
		
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "USE $db_dbname";
		$conn->exec($sql);
		return $conn;
	}
	catch(PDOException $e) {
		processError($e);
	}
}
?>
