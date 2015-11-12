<?php
/**
 * @author      Xiaotian Le
 * @version     1.0
 * @since       2015-11-10
 */

require_once "db.php";

/**
 * Find the record for a specific bowler
 * @param PDO $conn PDO object to the database
 * @param int $bowlerId Bowler's id
 * @return array An array of bowler data including id, Email, payouts, firstname, lastname, and registration date
 */
function findBowlerRecord($conn, $bowlerId) {
	global $db_table_bowlers;
	
	$sql = "SELECT * FROM $db_table_bowlers WHERE id = :id";
	$stmt = $conn->prepare($sql);
	$stmt->bindParam(':id', $bowlerId, PDO::PARAM_INT);
	$stmt->execute();

	$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

	if ($stmt->rowCount() == 0)
		return null;

	return $stmt->fetch();
}
?>
