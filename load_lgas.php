<?php
require 'db.php';
$state_id = intval($_GET['state_id']);
$result = $conn->query("SELECT lga_id, lga_name FROM lga WHERE state_id = $state_id");

while ($row = $result->fetch_assoc()) {
    echo "<option value=\"{$row['lga_id']}\">{$row['lga_name']}</option>";
}
?>
