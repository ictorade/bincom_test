<?php
// index.php

require 'db.php';

// Fetch LGAs in Delta State (state_id = 25)
$lgaResult = $conn->query("SELECT lga_id, lga_name FROM lga WHERE state_id = 25");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Polling Unit Results</title>
</head>
<body>
  <h2>Select a Polling Unit in Delta State</h2>

  <form action="view_results.php" method="GET">
    <label for="lga">Local Government Area:</label><br>
    <select name="lga_id" id="lga" required onchange="loadWards(this.value)">
      <option value="">-- Select LGA --</option>
      <?php while ($row = $lgaResult->fetch_assoc()) { ?>
        <option value="<?php echo $row['lga_id']; ?>"><?php echo $row['lga_name']; ?></option>
      <?php } ?>
    </select><br><br>

    <label for="ward">Ward:</label><br>
    <select name="ward_id" id="ward" required onchange="loadPollingUnits(this.value)">
      <option value="">-- Select Ward --</option>
    </select><br><br>

    <label for="pu">Polling Unit:</label><br>
    <select name="polling_unit_id" id="pu" required>
      <option value="">-- Select Polling Unit --</option>
    </select><br><br>

    <button type="submit">View Results</button>
  </form>

  <script>
    // Load wards by selected LGA (AJAX)
    function loadWards(lgaId) {
      fetch('load_wards.php?lga_id=' + lgaId)
        .then(response => response.text())
        .then(data => {
          document.getElementById('ward').innerHTML = data;
          document.getElementById('pu').innerHTML = '<option value=\"\">-- Select Polling Unit --</option>';
        });
    }

    // Load polling units by selected ward
    function loadPollingUnits(wardId) {
      fetch('load_pus.php?ward_id=' + wardId)
        .then(response => response.text())
        .then(data => {
          document.getElementById('pu').innerHTML = data;
        });
    }
  </script>
</body>
</html>
