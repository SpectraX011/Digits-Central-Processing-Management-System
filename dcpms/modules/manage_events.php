<h2>Manage Events</h2>
<form method="POST">
  <input type="text" name="title" placeholder="Event Title" required>
  <input type="date" name="event_date" required>
  <textarea name="description" placeholder="Event Description" required></textarea>
  <button type="submit">Add Event</button>
</form>

<?php
//parent class and it is the base of operations
class DatabaseHandler {
    protected $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    protected function executeQuery($query) {
        return $this->conn->query($query);
    }
}

//child class and encapsulation
class Event extends DatabaseHandler {
    private $title;
    private $description;
    private $startDate;
    private $endDate;
    private $isRequired;

    public function __construct($conn, $title, $description, $startDate, $endDate = null, $isRequired = 0) {
        parent::__construct($conn);
        $this->title = $title;
        $this->description = $description;
        $this->startDate = $startDate;
        $this->endDate = $endDate ?: $startDate;
        $this->isRequired = $isRequired;
    }

    public function addEvent() {
        $query = "INSERT INTO events (title, description, startDate, endDate, isRequired) 
                  VALUES ('{$this->title}', '{$this->description}', '{$this->startDate}', '{$this->endDate}', {$this->isRequired})";
        return $this->executeQuery($query);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = $_POST['title'];
  $date = $_POST['event_date'];
  $desc = $_POST['description'];

  $event = new Event($conn, $title, $desc, $date);
  if ($event->addEvent()) {
    echo "<p style='color:green;'>Event added!</p>";
  } else {
    echo "<p style='color:red;'>Error adding event!</p>";
  }
}
?>





















