<?php
// add_results.php

require 'db.php';

// Fetch states from `states` table
$states = $conn->query("SELECT state_id, state_name FROM states");

// Fetch unique party list
$parties = $conn->query("SELECT DISTINCT party_abbreviation FROM announced_pu_results");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Enter New Polling Unit Results</title>
</head>
<body>
  <h2>Enter New Polling Unit Results</h2>

  <form method="POST" action="save_results.php">
    <!-- State Dropdown -->
    <label>State:</label><br>
    <select name="state_id" required onchange="loadLGAs(this.value)">
      <option value="">-- Select State --</option>
      <?php while ($row = $states->fetch_assoc()): ?>
        <option value="<?= $row['state_id']; ?>"><?= $row['state_name']; ?></option>
      <?php endwhile; ?>
    </select><br><br>

    <!-- LGA Dropdown -->
    <label>LGA:</label><br>
    <select name="lga_id" id="lga" required onchange="loadWards(this.value)">
      <option value="">-- Select LGA --</option>
    </select><br><br>

    <!-- Ward Dropdown -->
    <label>Ward:</label><br>
    <select name="ward_id" id="ward" required onchange="loadPUs(this.value)">
      <option value="">-- Select Ward --</option>
    </select><br><br>

    <!-- Polling Unit Dropdown -->
    <label>Polling Unit:</label><br>
    <select name="polling_unit_id" id="pu" required>
      <option value="">-- Select Polling Unit --</option>
    </select><br><br>

    <h3>Enter Scores for Each Party</h3>

    <?php
    // Reset party pointer
    $parties->data_seek(0);
    while ($p = $parties->fetch_assoc()):
    ?>
      <label><?= $p['party_abbreviation']; ?>:</label>
      <input type="number" name="scores[<?= $p['party_abbreviation']; ?>]" min="0" required><br>
    <?php endwhile; ?>

    <br>
    <label>Entered By:</label><br>
    <input type="text" name="entered_by" required><br><br>

    <button type="submit">Submit Results</button>
  </form>

  <script>
    // Fetch LGAs based on selected state
    function loadLGAs(state_id) {
      fetch('load_lgas.php?state_id=' + state_id)
        .then(res => res.text())
        .then(data => {
          document.getElementById('lga').innerHTML = data;
          document.getElementById('ward').innerHTML = '<option value="">-- Select Ward --</option>';
          document.getElementById('pu').innerHTML = '<option value="">-- Select PU --</option>';
        });
    }

    // Fetch wards based on LGA
    function loadWards(lga_id) {
      fetch('load_wards.php?lga_id=' + lga_id)
        .then(res => res.text())
        .then(data => {
          document.getElementById('ward').innerHTML = data;
          document.getElementById('pu').innerHTML = '<option value="">-- Select PU --</option>';
        });
    }

    // Fetch polling units based on ward
    function loadPUs(ward_id) {
      fetch('load_pus.php?ward_id=' + ward_id)
        .then(res => res.text())
        .then(data => {
          document.getElementById('pu').innerHTML = data;
        });
    }
  </script>
</body>
</html>
