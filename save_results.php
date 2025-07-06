<?php
// save_results.php

require 'db.php';

$polling_unit_id = intval($_POST['polling_unit_id']);
$entered_by = $conn->real_escape_string($_POST['entered_by']);
$scores = $_POST['scores'];
$date = date("Y-m-d H:i:s");
$ip = $_SERVER['REMOTE_ADDR'];

$success = true;

foreach ($scores as $party => $score) {
    $party = $conn->real_escape_string($party);
    $score = intval($score);

    $stmt = $conn->prepare("INSERT INTO announced_pu_results 
        (polling_unit_uniqueid, party_abbreviation, party_score, entered_by_user, date_entered, user_ip_address) 
        VALUES (?, ?, ?, ?, ?, ?)");

    $stmt->bind_param("isisss", $polling_unit_id, $party, $score, $entered_by, $date, $ip);

    if (!$stmt->execute()) {
        $success = false;
        break;
    }
}

if ($success) {
    echo "<h3>Results submitted successfully!</h3>";
} else {
    echo "<h3>Error submitting results.</h3>";
}
echo "<a href='add_results.php'>Back</a>";
?>
