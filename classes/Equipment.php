<?php

class Equipment {
    private $id;
    private $equipmentName;
    private $description;
    private $capacity;
    private $location;
    
    public function __construct() {
        $this->id = $id;
        $this->equipmentName = $equipmentName;
        $this->description = $description;
        $this->capacity = $capacity;
        $this->location = $location;
    }

    public function getId() {
        return $this->id;
    }

    public function getEquipmentName() {
        return $this->equipmentName;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getCapacity() {
        return $this->capacity;
    }

    public function getLocation() {
        return $this->location;
    }

    public function setId($id): void {
        $this->id = $id;
    }

    public function setEquipmentName($equipmentName): void {
        $this->equipmentName = $equipmentName;
    }

    public function setDescription($description): void {
        $this->description = $description;
    }

    public function setCapacity($capacity): void {
        $this->capacity = $capacity;
    }

    public function setLocation($location): void {
        $this->location = $location;
    }

    function deleteEquipment() {
        try {
            $db = Database::getInstance();
            $data = $db->querySql('Delete from Proj_Equipment where id=' . $this->id);
            return true;
        } catch (Exception $e) {
            echo 'Exception: ' . $e;
            return false;
        }
    }

    function initWithUid($id) {

        $db = Database::getInstance();
        $data = $db->singleFetch('SELECT * FROM Proj_Equipment WHERE id = ' . $id);
        $this->initWith($data->id, $data->equipmentName, $data->desciption, $data->capacity, $data->location);
    }

    function initWithEquipmentName() {

        $db = Database::getInstance();
        $data = $db->singleFetch('SELECT * FROM Proj_Equipment WHERE equipmentName = \'' . $this->equipmentName . '\'');
        if ($data != null) {
            return false;
        }
        return true;
    }

    function registerEquipment() {
        if ($this->isValid()) {
            try {
                $db = Database::getInstance();
                $data = $db->querySql('INSERT INTO Proj_Equipment (id, equipmentName, description, capacity, location) '
                        . 'VALUES (NULL, \'' . $this->equipmentName . '\',\'' . $this->description . '\',\'' 
                        . $this->capacity . '\', \'' . $this->location . '\')');
                return true;
            } catch (Exception $e) {
                echo 'Exception: ' . $e;
                return false;
            }
        } else
            return false;
    }

    private function initWith($id, $equipmentName, $description, $capacity, $location) {
        $this->id = $id;
        $this->equipmentName = $equipmentName;
        $this->description = $description;
        $this->capacity = $capacity;
        $this->location = $location;
    }

    function updateDB() {
        if ($this->isValid()) {
            $db = Database::getInstance();
            $data = 'UPDATE Proj_Equipment set
			equipmentName = \'' . $this->equipmentName . '\' ,
			description = \'' . $this->description . '\' ,
			capacity = \'' . $this->capacity . '\'  ,
                        location = \'' . $this->location . '\'
				WHERE id = ' . $this->id;
            $db->querySql($data);
        }
    }

    function getAllEquipments() {
        $db = Database::getInstance();
        $data = $db->multiFetch('Select * from Proj_Equipment');
        return $data;
    }

    public function isValid() {
        $errors = true;
        if (empty($this->equipmentName))
            $errors = false;
        if (empty($this->description))
            $errors = false;
        if (empty($this->capacity))
            $errors = false;
        if (empty($this->location))
            $errors = false;
        return $errors;
    }
    
}

?>
