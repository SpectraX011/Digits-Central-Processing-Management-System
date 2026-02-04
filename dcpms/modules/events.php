<?php
// inheritance parents class
class DatabaseEntity {
    protected $conn;

    public function __construct($conn) {
        if (!$conn) {
            throw new Exception("Database connection is required.");
        }
        $this->conn = $conn;
    }

    // gets query and returns result
    protected function query($sql) {
        $result = $this->conn->query($sql);
        if (!$result) {
            throw new Exception("Query failed: " . $this->conn->error);
        }
        return $result;
    }
}

// encapsulation for event class
class Event {
    private $title;
    private $startDate;
    private $endDate;
    private $description;
    private $isRequired;

    public function __construct($data) {
        $this->title = $data['title'] ?? '';
        $this->startDate = $data['startDate'] ?? '';
        $this->endDate = $data['endDate'] ?? '';
        $this->description = $data['description'] ?? '';
        $this->isRequired = $data['isRequired'] ?? false;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getStartDate() {
        return $this->startDate;
    }

    public function getEndDate() {
        return $this->endDate;
    }

    public function getDescription() {
        return $this->description;
    }

    public function isRequired() {
        return $this->isRequired;
    }

    // the method to display event
    public function display() {
        $requiredText = $this->isRequired ? "Required" : "Optional";
        $color = $this->isRequired ? "red" : "green";
        echo "<div style='margin-bottom:20px;'>
          <strong>{$this->title}</strong><br>
          <em>{$this->startDate} to {$this->endDate}</em><br>
          <p>{$this->description}</p>
          <span style='color:{$color}; font-weight:bold;'>
            {$requiredText}
          </span>
        </div><hr>";
    }
}

// child class
class EventManager extends DatabaseEntity {
    public function getUpcomingEvents() {
        $sql = "SELECT * FROM events ORDER BY startDate ASC";
        $result = $this->query($sql);
        $events = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $events[] = new Event($row);
            }
        }
        return $events;
    }
}

// main script
try {
    $eventManager = new EventManager($conn);
    $events = $eventManager->getUpcomingEvents();

    if (!empty($events)) {
        foreach ($events as $event) {
            $event->display();
        }
    } else {
        echo "<p>No events available.</p>";
    }
} catch (Exception $e) {
    echo "<p>Error: " . $e->getMessage() . "</p>";
}
?>
