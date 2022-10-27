<?php

class Event {

    private $id;
    private $title;
    private $description;
    private $startDate;
    private $endDate;
    private $cost;
    private $locationID;
    private $numOfAudience;
    private $service;
    private $duration;

    public function __construct() {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->startDate = $sDate;
        $this->endDate = $eDate;
        $this->cost = $cost;
        $this->locationID = $locID;
        $this->numOfAudience = $numOfAudience;
        $this->service = $service;
        $this->duration = $duration;
    }

    public function getId() {
        return $this->id;
    }
    
    public function getIdByName($name) {
        $db = Database::getInstance();
        $data = $db->multiFetch('Select id from Proj_Event where title = '. "'" . $name. "'");
        return $data;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getStartDate() {
        return $this->startDate;
    }

    public function getEndDate() {
        return $this->endDate;
    }

    public function getCost() {
        return $this->cost;
    }

    public function getLocationID() {
        return $this->locationID;
    }

    public function getNumOfAudience() {
        return $this->numOfAudience;
    }

    public function getService() {
        return $this->service;
    }

    public function getDuration() {
        return $this->duration;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function setTitle($title) {
        $this->title = $title;
        return $this;
    }

    public function setDescription($description) {
        $this->description = $description;
        return $this;
    }

    public function setStartDate($startDate) {
        $this->startDate = $startDate;
        return $this;
    }

    public function setEndDate($endDate) {
        $this->endDate = $endDate;
        return $this;
    }

    public function setCost($cost) {
        $this->cost = $cost;
        return $this;
    }

    public function setLocationID($locationID) {
        $this->locationID = $locationID;
        return $this;
    }

    public function setNumOfAudience($numOfAudience) {
        $this->numOfAudience = $numOfAudience;
        return $this;
    }

    public function setService($service) {
        $this->service = $service;
        return $this;
    }
    
    public function setDuration($duration) {
        $this->duration = $duration;
        return $this;
    }

    function initWithId($id) {
        $db = Database::getInstance();
        $data = $db->singleFetch('SELECT * FROM Proj_Event WHERE id = ' . $id);
        $this->initWith($data->id, $data->title, $data->description, $data->startDate, $data->endDate, $data->cost, $data->locationId, $data->numOfAudience, $data->service, $data->duration);
    }

    function initWithEventTitle() {
        $db = Database::getInstance();
        $data = $db->singleFetch('SELECT * FROM Proj_Event WHERE title = \'' . $this->title . '\'');
        if ($data != null) {
            return false;
        }
        return true;
    }

    private function initWith($id, $title, $description, $startDate, $endDate, $cost, $locationId, $numOfAudience, $service, $duration) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->cost = $cost;
        $this->locationID = $locationId;
        $this->numOfAudience = $numOfAudience;
        $this->service = $service;
        $this->duration = $duration;
    }

    public function isValid() {
        $errors = true;
        if (empty($this->title))
            $errors = false;
        if (empty($this->description))
            $errors = false;
        if (empty($this->startDate))
            $errors = false;
        if (empty($this->endDate))
            $errors = false;
        if (empty($this->cost))
            $errors = false;
        if (empty($this->locationID))
            $errors = false;
        if (empty($this->numOfAudience))
            $errors = false;
        if (empty($this->service))
            $errors = false;
        if (empty($this->duration))
            $errors = false;
        return $errors;
    }

    function getAllEvents() {
        $db = Database::getInstance();
        $data = $db->multiFetch('Select * from Proj_Event');
        return $data;
    }

    function registerEvent() {
        if ($this->isValid()) {
            try {
                $db = Database::getInstance();
                $data = $db->querySql('INSERT INTO Proj_Event (id, title, description, startDate, endDate, cost, locationId, numOfAudience, service, duration) '
                        . 'VALUES (NULL, \'' . $this->title . '\',\'' . $this->description . '\',\'' . $this->startDate . '\''
                        . ', \'' . $this->endDate . '\', \'' . $this->cost . '\', \'' . $this->locationID . '\', '
                        . '\'' . $this->numOfAudience . '\' , \'' . $this->service . '\' , \'' . $this->duration . '\')');
                return true;
            } catch (Exception $e) {
                echo 'Exception: ' . $e;
                return false;
            }
        } else
            return false;
    }

    function updateDB() {
        if ($this->isValid()) {
            $db = Database::getInstance();
            $data = 'UPDATE Proj_Event SET
			title = \'' . $this->title . '\' ,
			description = \'' . $this->description . '\' ,
			startDate = \'' . $this->startDate . '\' ,
                        endDate = \'' . $this->endDate . '\' ,
                        cost = \'' . $this->cost . '\' ,
                        locationId = \'' . $this->locationID . '\' ,
                        numOfAudience = \'' . $this->numOfAudience . '\',
                        service = \'' . $this->service . '\',
                        duration = \'' . $this->duration . '\'
				WHERE id = ' . $this->id;
            $db->querySQL($data);
        }
    }

    function deleteEmployee() {
        try {
            $db = Database::getInstance();
            $data = $db->querySql('Delete from Proj_Event where id=' . $this->id);
            return true;
        } catch (Exception $e) {
            echo 'Exception: ' . $e;
            return false;
        }
    }

    function searchForEvents($date, $duration, $attendees) {
        $db = Database::getInstance();
        $data = $db->multiFetch('Select * from Proj_Event where startDate '
                . 'NOT BETWEEN \''
                . $date. '\'' . ' and \'' . $duration  .'\''. 
                ' AND endDate <> \''.   $duration .'\''                
                . ' and numOfAudience >= ' . $attendees);
        return $data;
    }
    
    function getAvailableLocations($array){
        $db = Database::getInstance();
        $data = $db->multiFetch('Select * from Proj_Location where id IN '. $array);
        return $data;
    }

    function showAvailableEvents() {
        $db = Database::getInstance();
        $data = $db->multiFetch('Select * from Proj_Event where startDate <= NOW() or endDate >= NOW()');
        return $data;
    }

}

?>