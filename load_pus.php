<?php
// load_pus.php

require 'db.php';

$ward_id = intval($_GET['ward_id']);
$result = $conn->query("SELECT uniqueid, polling_unit_name FROM polling_unit WHERE ward_id = $ward_id");

while ($row = $result->fetch_assoc()) {
    echo "<option value=\"{$row['uniqueid']}\">{$row['polling_unit_name']}</option>";
}
?>
