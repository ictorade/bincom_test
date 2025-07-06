<?php
// load_wards.php

require 'db.php';

$lga_id = intval($_GET['lga_id']);
$result = $conn->query("SELECT ward_id, ward_name FROM ward WHERE lga_id = $lga_id");

while ($row = $result->fetch_assoc()) {
    echo "<option value=\"{$row['ward_id']}\">{$row['ward_name']}</option>";
}
?>
