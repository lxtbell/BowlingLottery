<?php
/**
 * @author      Xiaotian Le
 * @version     1.0
 * @since       2015-11-10
 */
require_once "bowler_lib.php";

/**
 * Add a user
 * @param firstname Non-empty firstname
 * @param lastname Non-empty lastname
 * @param email A valid email address
 * @param password Password
 * @return id The id of the bowler added
 */
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$firstname = purifyInput($_POST["firstname"]);
	$lastname = purifyInput($_POST["lastname"]);
	$email = purifyInput($_POST["email"]);
	$password = hashPassword($_POST["password"]);
	
	if (!validateInput($firstname, "NAME") || !validateInput($lastname, "NAME"))
		return echoError(ERROR_INVALID_NAME);
	
	if (!validateInput($email, "EMAIL"))
		return echoError(ERROR_INVALID_EMAIL);
	
	try {
		$conn = connectDb();
		
		$sql = "SELECT * FROM $db_table_bowlers WHERE email = :email";
		$stmt = $conn->prepare($sql);
		$stmt->bindParam(':email', $email);
		$stmt->execute();
		if ($stmt->rowCount() > 0)
			return echoError(ERROR_EMAIL_ALREADY_EXIST);
		
		$sql = "INSERT INTO $db_table_bowlers (firstname, lastname, email, password, reg_date) VALUES ('$firstname', '$lastname', '$email', '$password', NOW())";
		$conn->exec($sql);
		echo encodeOuput(array('id' => $conn->lastInsertId("id")));
	}
	catch(PDOException $e) {
		processError($e);
	}
}

// Request data about a user
if ($_SERVER["REQUEST_METHOD"] == "GET") {
	$bowlerId = (int) purifyInput($_GET["bowler_id"]);
	
	try {
		$conn = connectDb();
		$record = findBowlerRecord($conn, $bowlerId);
		if (is_null($record))
			return echoError(ERROR_BOWLER_NOT_FOUND);
		echo encodeOuput($record);
	}
	catch(PDOException $e) {
		processError($e);
	}
}
?>
