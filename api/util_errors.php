<?php
/**
 * @author      Xiaotian Le
 * @version     1.0
 * @since       2015-11-10
 */
define("ERROR_LEAGUE_NOT_FOUND", "League not found.");
define("ERROR_BOWLERS_NOT_IN_LEAGUE", "Bowler not in the league.");
define("ERROR_NO_BOWLER_IN_LOTTERY", "No bowlers entered the lottery in the league.");
define("ERROR_NON_WINNER_ATTEMPTING", "The user attempting is not the winner.");
define("ERROR_BOWLER_NOT_IN_LOTTERY", "Bowler not in current lottery.");
define("ERROR_INVALID_NAME", "Invalid name format.");
define("ERROR_INVALID_DESCRIPTION", "Invalid description format.");
define("ERROR_INVALID_CAPACITY", "Invalid capacity.");
define("ERROR_INVALID_EMAIL", "Invalid email format.");
define("ERROR_EMAIL_ALREADY_EXIST", "Email already exists.");
define("ERROR_BOWLER_NOT_FOUND", "Bowler not found.");
define("ERROR_BOWLER_ALREADY_JOIN_LEAGUE", "Bowler has already joined the league.");
define("ERROR_LEAGUE_REACH_CAPACITY", "League has reached its capacity.");
define("ERROR_NO_BOWLER_IN_LEAGUE", "No bowlers in the league.");
define("ERROR_NO_LEAGUE", "No leagues found.");
define("ERROR_NO_BOWLER", "No bowlers found.");

function processError($e) {
	echo $e->getMessage() . "<br>";
}

function encodeError($desc) {
	return json_encode(array("error" => $desc));
}

function echoError($desc) {
	echo encodeError($desc);
	return true;
}

?>