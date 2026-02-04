<?php
//parent class
class DatabaseHandler {
    protected $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }
}

//child class (inheritance and encapsulation)
class Event extends DatabaseHandler {
    public function getAllEvents() {
        return $this->conn->query("SELECT eventID, title FROM events ORDER BY startDate ASC");
    }
}
//child class (inheritance and encapsulation)
class Registration extends DatabaseHandler {
    public function isRegistered($studentID, $eventID) {
        $check = $this->conn->query("SELECT * FROM registrations WHERE studentID='$studentID' AND eventID='$eventID'");
        return $check->num_rows > 0;
    }

    public function register($studentID, $eventID) {
        $this->conn->query("INSERT INTO registrations (studentID, eventID, registeredOn) VALUES ('$studentID', '$eventID', NOW())");
    }
}
//instantiate classes
$event = new Event($conn);
$registration = new Registration($conn);
?>

<h2>Register for an Event</h2>

<form method="POST">
  <select name="eventID" required>
    <option value="">Select Event</option>
    <?php
    $events = $event->getAllEvents();
    while ($e = $events->fetch_assoc()) {
      echo "<option value='{$e['eventID']}'>{$e['title']}</option>";
    }
    ?>
  </select>
  <button type="submit">Register</button>
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eventID'])) {
  $eventID = $_POST['eventID'];
  $studentID = $_SESSION['studentID'];

  //checks if already registered
  if ($registration->isRegistered($studentID, $eventID)) {
    echo "<p style='color:red;'>You are already registered for this event.</p>";
  } else {
    $registration->register($studentID, $eventID);
    echo "<p style='color:green;'>Registration successful!</p>";
  }
}
?>