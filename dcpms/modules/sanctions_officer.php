<h2>Issue a Sanction</h2>
<form method="POST">
  <input type="text" name="studentID" placeholder="Student ID" required>
  <select name="eventID">
    <option value="">Related Event (optional)</option>
    <?php
    $events = $conn->query("SELECT eventID, title FROM events");
    while ($e = $events->fetch_assoc()) {
      echo "<option value='{$e['eventID']}'>{$e['title']}</option>";
    }
    ?>
  </select>
  <textarea name="reason" placeholder="Violation Reason" required></textarea>
  <input type="number" name="penaltyAmount" placeholder="Penalty Amount (₱)" step="0.01">
  <select name="status" required>
    <option value="Pending">Pending</option>
    <option value="Resolved">Resolved</option>
  </select>
  <input type="date" name="resolvedOn">
  <button type="submit">Submit Sanction</button>
</form>
<?php
// encapsulation of connection and basic queries
class DatabaseHandler {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    protected function getConnection() {
        return $this->conn;
    }

    protected function executeQuery($query) {
        return $this->conn->query($query);
    }
}

// Sanction class inheriting from DatabaseHandler inheritance and encapsulation
class Sanction extends DatabaseHandler {
    private $studentID;
    private $eventID;
    private $reason;
    private $penaltyAmount;
    private $status;
    private $resolvedOn;

    public function __construct($conn, $studentID, $eventID, $reason, $penaltyAmount, $status, $resolvedOn) {
        parent::__construct($conn);
        $this->studentID = $studentID;
        $this->eventID = $eventID ?: null;
        $this->reason = $reason;
        $this->penaltyAmount = $penaltyAmount ?: 0;
        $this->status = $status;
        $this->resolvedOn = $resolvedOn ?: null;
    }

    public function save() {
        $eid = $this->eventID ? "'{$this->eventID}'" : 'NULL';
        $resolved = $this->resolvedOn ? "'{$this->resolvedOn}'" : 'NULL';
        $query = "INSERT INTO sanctions (studentID, eventID, reason, status, penaltyAmount, resolvedOn)
                  VALUES ('{$this->studentID}', $eid, '{$this->reason}', '{$this->status}', '{$this->penaltyAmount}', $resolved)";
        $this->executeQuery($query);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sanction = new Sanction(
        $conn,
        $_POST['studentID'],
        $_POST['eventID'],
        $_POST['reason'],
        $_POST['penaltyAmount'],
        $_POST['status'],
        $_POST['resolvedOn']
    );
    $sanction->save();
    echo "<p style='color:green;'>Sanction submitted successfully.</p>";
}
?>




