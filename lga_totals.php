<?php
// lga_totals.php

require 'db.php';

// Get LGAs in Delta State (state_id = 25)
$lgaResult = $conn->query("SELECT lga_id, lga_name FROM lga WHERE state_id = 25");
?>

<!DOCTYPE html>
<html>
<head>
  <title>LGA Total Results</title>
</head>
<body>
  <h2>View Total Election Results by LGA (Delta State)</h2>

  <form method="GET" action="">
    <label for="lga">Select Local Government:</label>
    <select name="lga_id" required>
      <option value="">-- Select LGA --</option>
      <?php while ($row = $lgaResult->fetch_assoc()): ?>
        <option value="<?php echo $row['lga_id']; ?>"
          <?php if (isset($_GET['lga_id']) && $_GET['lga_id'] == $row['lga_id']) echo 'selected'; ?>>
          <?php echo $row['lga_name']; ?>
        </option>
      <?php endwhile; ?>
    </select>
    <button type="submit">View Total</button>
  </form>

  <?php
  if (isset($_GET['lga_id'])) {
    $lga_id = intval($_GET['lga_id']);

    // Get all polling unit unique IDs under selected LGA
    $puQuery = $conn->prepare("SELECT uniqueid FROM polling_unit WHERE lga_id = ?");
    $puQuery->bind_param("i", $lga_id);
    $puQuery->execute();
    $puResult = $puQuery->get_result();

    $pollingUnitIds = [];
    while ($row = $puResult->fetch_assoc()) {
      $pollingUnitIds[] = $row['uniqueid'];
    }

    if (!empty($pollingUnitIds)) {
      // Convert to comma-separated string
      $inClause = implode(",", array_map('intval', $pollingUnitIds));

      // Get summed party scores for all polling units under this LGA
      $result = $conn->query("
        SELECT party_abbreviation, SUM(party_score) as total_score
        FROM announced_pu_results
        WHERE polling_unit_uniqueid IN ($inClause)
        GROUP BY party_abbreviation
        ORDER BY party_abbreviation ASC
      ");
      ?>

      <h3>Total Party Scores for Selected LGA</h3>
      <table border="1" cellpadding="8">
        <thead>
          <tr>
            <th>Party</th>
            <th>Total Score</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
              <tr>
                <td><?php echo $row['party_abbreviation']; ?></td>
                <td><?php echo $row['total_score']; ?></td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr><td colspan="2">No results found for this LGA.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    <?php } else {
      echo "<p>No polling units found for this LGA.</p>";
    }
  }
  ?>
  <br><a href="index.php">Back to Polling Unit Results</a>
</body>
</html>
