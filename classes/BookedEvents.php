<?php

class BookedEvents {

    private $id;
    private $eventId;
    private $clientId;
    private $bookDate;
    private $totalCost;
    private $userId;

    public function __construct() {
        $this->id = $id;
        $this->eventId = $eventId;
        $this->clientId = $clientId;
        $this->bookDate = $bookDate;
        $this->totalCost = $totalCost;
        $this->userId = $userId;
    }
    
    public function getId() {
        return $this->id;
    }

    public function getIdByEventId($eventId) {
        $db = Database::getInstance();
        $data = $db->multiFetch('Select id from Proj_BookedEvent where eventId = ' . $eventId);
        return $data;
    }

    public function getEventId() {
        return $this->eventId;
    }

    public function getClientId() {
        return $this->clientId;
    }

    public function getBookDate() {
        return $this->bookDate;
    }

    public function getTotalCost() {
        return $this->totalCost;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function setUserId($userId) {
        $this->userId = $userId;
        return $this;
    }

    public function setId($id): void {
        $this->id = $id;
    }

    public function setEventId($eventId): void {
        $this->eventId = $eventId;
    }

    public function setClientId($clientId): void {
        $this->clientId = $clientId;
    }

    public function setBookDate($bookDate): void {
        $this->bookDate = $bookDate;
    }

    public function setTotalCost($totalCost): void {
        $this->totalCost = $totalCost;
    }

    function deleteReservation() {
        try {
            $db = Database::getInstance();
            $data = $db->querySql('Delete from Proj_BookedEvent where id=' . $this->id);
            return true;
        } catch (Exception $e) {
            echo 'Exception: ' . $e;
            return false;
        }
    }

    function initWithUid($id) {
        $db = Database::getInstance();
        $data = $db->singleFetch('SELECT * FROM Proj_BookedEvent WHERE id = ' . $id);
        $this->initWith($data->id, $data->eventId, $data->clientId, $data->bookDate, $data->totalCost, $data->userId);
    }

    function initWithUserId() {

        $db = Database::getInstance();
        $data = $db->singleFetch('SELECT * FROM Proj_BookedEvent WHERE userId = \'' . $this->userId . '\'');
        if ($data != null) {
            return false;
        }
        return true;
    }

    function registerBookedEvent() {
        if ($this->isValid()) {
            try {
                $db = Database::getInstance();
                $data = $db->querySql('INSERT INTO Proj_BookedEvent (id, eventId, clientId, bookDate, totalCost, userId)'
                        . ' VALUES (NULL, \'' . $this->eventId . '\',\'' . $this->clientId . '\',\''
                        . $this->bookDate . '\',\'' . $this->totalCost . '\', \'' . $this->userId . '\' )');
                return true;
            } catch (Exception $e) {
                echo 'Exception: ' . $e;
                return false;
            }
        } else
            return false;
    }

    private function initWith($id, $eventId, $clientId, $bookDate, $totalCost, $userId) {
        $this->id = $id;
        $this->eventId = $eventId;
        $this->clientId = $clientId;
        $this->bookDate = $bookDate;
        $this->totalCost = $totalCost;
        $this->userId = $userId;
    }

    function updateDB() {
        if ($this->isValid()) {
            $db = Database::getInstance();
            $data = 'UPDATE Proj_BookedEvent set
			eventId = \'' . $this->eventId . '\' ,
			clientId = \'' . $this->clientId . '\' ,
			bookDate = \'' . $this->bookDate . '\' ,
                        totalCost = \'' . $this->totalCost . '\',
                        userId = \'' . $this->userId . '\'
				WHERE id = ' . $this->id;
            $db->querySql($data);
        }
    }

    function getAllReservationsByUserId($userId) {
        $db = Database::getInstance();
        $data = $db->multiFetch('Select * from Proj_BookedEvent where userId = ' . $userId);
        return $data;
    }
    
    function getAllReservations() {
        $db = Database::getInstance();
        $data = $db->multiFetch('Select * from Proj_BookedEvent');
        return $data;
    }

    function getAllReservationsByDate($date) {
        $db = Database::getInstance();
        $data = $db->multiFetch('Select * from Proj_BookedEvent where bookDate = ' . $date);
        return $data;
    }
    
    function getMonthlyIncome($month, $year){
        $db = Database::getInstance();
        $data = $db->multiFetch('select totalCost from Proj_BookedEvent where '
                . 'month(bookDate) = ' . $month . ' and year(bookDate) = '. $year);
        return $data;
    }
    
    function getIncomeByDateRange($date, $range){
        $db = Database::getInstance();
        $data = $db->multiFetch('select totalCost from Proj_BookedEvent where bookDate '
                . 'between '."'". $date . "'".' and '. "'".$range."'");
        return $data;
    }

    public function isValid() {
        $errors = true;
        if (empty($this->clientId))
            $errors = false;
        if (empty($this->eventId))
            $errors = false;
        if (empty($this->bookDate))
            $errors = false;
        if (empty($this->totalCost))
            $errors = false;
        if (empty($this->userId))
            $errors = false;
        return $errors;
    }

}
?>

