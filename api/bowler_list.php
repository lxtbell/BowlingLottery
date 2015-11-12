<?php
/**
 * @author      Xiaotian Le
 * @version     1.0
 * @since       2015-11-10
 */
require_once "bowler_lib.php";

/**
 * Get all bowlers
 * High potential bandwidth!
 * @return array An array of bowler data including id, Email, payouts, firstname, lastname, and registration date
 */
if ($_SERVER["REQUEST_METHOD"] == "GET") {
	try {
		$conn = connectDb();
		$sql = "SELECT id, firstname, lastname, email, reg_date, payouts FROM $db_table_bowlers";
		$stmt = $conn->prepare($sql);
		$stmt->execute();

		$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

		if ($stmt->rowCount() == 0)
			return echoError(ERROR_NO_BOWLER);

		echo encodeOuput($stmt->fetchAll());
	}
	catch(PDOException $e) {
		processError($e);
	}
}
?>