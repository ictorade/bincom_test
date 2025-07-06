<?php
// view_results.php

require 'db.php';

$polling_unit_id = intval($_GET['polling_unit_id']);

$query = $conn->prepare("SELECT party_abbreviation, party_score 
                         FROM announced_pu_results 
                         WHERE polling_unit_uniqueid = ?");
$query->bind_param("i", $polling_unit_id);
$query->execute();
$result = $query->get_result();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Polling Unit Results</title>
</head>
<body>
  <h2>Results for Selected Polling Unit</h2>
  <table border="1" cellpadding="8">
    <thead>
      <tr>
        <th>Party</th>
        <th>Score</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?php echo $row['party_abbreviation']; ?></td>
            <td><?php echo $row['party_score']; ?></td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr><td colspan="2">No results found for this polling unit.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>

  <br><a href="index.php">Back</a>
</body>
</html>
        