<?php

class EventService {

    private $id;
    private $eventId ;
    private $serviceItemId ;

    public function __construct() {
        $this->id = $id;
        $this->serviceItemId = $serviceItemId;
        $this->eventId = $eventId;
    }

    public function getId() {
        return $this->id;
    }

    public function getServiceItemId() {
        return $this->serviceItemId;
    }

    public function getEventId() {
        return $this->eventId;
    }

    public function setId($id): void {
        $this->id = $id;
    }

    public function setServiceItemId($serviceItemId): void {
        $this->serviceItemId = $serviceItemId;
    }

    public function setEventId($eventId): void {
        $this->eventId = $eventId;
    }

    function deleteServiceItem() {
        try {
            $db = Database::getInstance();
            $data = $db->querySql('Delete from Proj_EventServices where id=' . $this->id);
            return true;
        } catch (Exception $e) {
            echo 'Exception: ' . $e;
            return false;
        }
    }

    function initWithId($id) {
        $db = Database::getInstance();
        $data = $db->singleFetch('SELECT * FROM Proj_EventServices WHERE id = ' . $id);
        $this->initWith($data->id, $data->eventId, $data->serviceItemId);
    }

    function initWithEventId() {
        $db = Database::getInstance();
        $data = $db->singleFetch('SELECT * FROM Proj_EventServices WHERE eventId = \'' . $this->eventId . '\'');
        if ($data != null) {
            return false;
        }
        return true;
    }

    function registerServiceItem() {
        if ($this->isValid()) {
            try {
                $db = Database::getInstance();
                $data = $db->querySql('INSERT INTO Proj_EventServices (id, eventId, serviceItemId) '
                        . 'VALUES (NULL, \'' . $this->eventId . '\',\'' . $this->serviceItemId . '\')');
                return true;
            } catch (Exception $e) {
                echo 'Exception: ' . $e;
                return false;
            }
        } else
            return false;
    }

    private function initWith($id, $eventId, $serviceItemId) {
        $this->id = $id;
        $this->serviceItemId = $serviceItemId;
        $this->eventId = $eventId;
    }

    function updateDB() {
        if ($this->isValid()) {
            $db = Database::getInstance();
            $data = 'UPDATE Proj_EventServices set
			serviceItemId = \'' . $this->serviceItemId . '\' ,
			eventId = \'' . $this->eventId . '\'  
				WHERE id = ' . $this->id;
            $db->querySql($data);
        }
    }

    function getAllServiceItems() {
        $db = Database::getInstance();
        $data = $db->multiFetch('Select * from Proj_EventServices');
        return $data;
    }
    
    function getServiceItemsByServiceId($serviceID){
        $db = Database::getInstance();
        $data = $db->multiFetch('Select * from Proj_EventServices WHERE serviceItemId = ' . $serviceID);
        return $data;
    }

    public function isValid() {
        $errors = true;
        if (empty($this->serviceItemId))
            $errors = false;
        if (empty($this->eventId))
            $errors = false;
        return $errors;
    }

}
?>
