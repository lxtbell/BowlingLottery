<?php
/**
 * @author      Xiaotian Le
 * @version     1.0
 * @since       2015-11-10
 */
require_once "league_lib.php";

/**
 * Get all leagues
 * High potential bandwidth!
 */
if ($_SERVER["REQUEST_METHOD"] == "GET") {
	try {
		$conn = connectDb();
		$sql = "SELECT * FROM $db_table_leagues";
		$stmt = $conn->prepare($sql);
		$stmt->execute();

		$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

		if ($stmt->rowCount() == 0)
			return echoError(ERROR_NO_LEAGUE);

		echo encodeOuput($stmt->fetchAll());
	}
	catch(PDOException $e) {
		processError($e);
	}
}
?>
