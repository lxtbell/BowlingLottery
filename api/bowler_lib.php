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
 * @return array An row of bowler data
 */
function findBowlerRecord($conn, $bowlerId) {
	global $db_table_bowlers;
	
	$sql = "SELECT id, firstname, lastname, email, reg_date, payouts FROM $db_table_bowlers WHERE id = :id";
	$stmt = $conn->prepare($sql);
	$stmt->bindParam(':id', $bowlerId, PDO::PARAM_INT);
	$stmt->execute();

	$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

	if ($stmt->rowCount() == 0)
		return null;

	return $stmt->fetch();
}
?>
