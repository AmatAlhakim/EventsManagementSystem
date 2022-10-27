<?php

class Location {

    private $id;
    private $name;
    private $address;
    private $managerFName;
    private $managerLName;
    private $managerEmail;
    private $managerNumber;
    private $maxCapacity;
    private $type;
    private $seatingAvailable;
    private $facilities;
    private $locationImage;
    private $cost;

    public function __construct() {
        $this->id = $id;
        $this->name = $name;
        $this->address = $address;
        $this->managerFName = $managerFName;
        $this->managerLName = $managerLName;
        $this->managerEmail = $managerEmail;
        $this->managerNumber = $managerNumber;
        $this->maxCapacity = $maxCapacity;
        $this->type = $type;
        $this->seatingAvailable = $seatingAvailable;
        $this->facilities = $facilities;
        $this->locationImage = $locationImage;
        $this->cost = $cost;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getAddress() {
        return $this->address;
    }

    public function getManagerFName() {
        return $this->managerFName;
    }

    public function getManagerLName() {
        return $this->managerLName;
    }

    public function getManagerEmail() {
        return $this->managerEmail;
    }

    public function getManagerNumber() {
        return $this->managerNumber;
    }

    public function getMaxCapacity() {
        return $this->maxCapacity;
    }

    public function getType() {
        return $this->type;
    }

    public function getCost() {
        return $this->cost;
    }

    public function setCost($cost) {
        $this->cost = $cost;
        return $this;
    }

    public function getSeatingAvailable() {
        return $this->seatingAvailable;
    }

    public function getFacilities() {
        return $this->facilities;
    }

    public function getLocationImage() {
        return $this->locationImage;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    public function setAddress($address) {
        $this->address = $address;
        return $this;
    }

    public function setManagerFName($managerFName) {
        $this->managerFName = $managerFName;
        return $this;
    }

    public function setManagerLName($managerLName) {
        $this->managerLName = $managerLName;
        return $this;
    }

    public function setManagerEmail($managerEmail) {
        $this->managerEmail = $managerEmail;
        return $this;
    }

    public function setManagerNumber($managerNumber) {
        $this->managerNumber = $managerNumber;
        return $this;
    }

    public function setMaxCapacity($maxCapacity) {
        $this->maxCapacity = $maxCapacity;
        return $this;
    }

    public function setType($type) {
        $this->type = $type;
        return $this;
    }

    public function setSeatingAvailable($seatingAvailable) {
        $this->seatingAvailable = $seatingAvailable;
        return $this;
    }

    public function setFacilities($facilities) {
        $this->facilities = $facilities;
        return $this;
    }

    public function setLocationImage($locationImage) {
        $this->locationImage = $locationImage;
        return $this;
    }

    function deleteLocation() {
        try {
            $db = Database::getInstance();
            $data = $db->querySql('Delete from Proj_Location WHERE id=' . $this->id);
            return true;
        } catch (Exception $e) {
            echo 'Exception: ' . $e;
            return false;
        }
    }

    function initWithId($id) {
        $db = Database::getInstance();
        $data = $db->singleFetch('SELECT * FROM Proj_Location WHERE id = ' . $id);
        $this->initWith($data->id, $data->name, $data->address, $data->managerFName, $data->managerLName, $data->managerEmail, $data->managerNumber, $data->maxCapacity, $data->type, $data->seatingAvailable, $data->facilities, $data->locationImage, $data->cost);
    }

    function initWithLocationName() {
        $db = Database::getInstance();
        $data = $db->singleFetch('SELECT * FROM Proj_Location WHERE name = \'' . $this->name . '\'');
        if ($data != null) {
            return false;
        }
        return true;
    }

    function registerLocation() {
        if ($this->isValid()) {
            try {
                $db = Database::getInstance();
                $command = "INSERT INTO Proj_Location (id, name, address, "
                        . "managerFName, managerLName, managerEmail, "
                        . "managerNumber, maxCapacity, type, seatingAvailable, "
                        . "facilities, locationImage, cost) ";
                
                $command .= " VALUES (NULL, '" . $this->name . "', ";
                $command .= "'" . $this->address . "', ";
                $command .= "'" . $this->managerFName . "', ";
                $command .= "'" . $this->managerLName . "', ";
                $command .= "'" . $this->managerEmail . "', ";
                $command .= "'" . $this->managerNumber . "', ";
                $command .= "'" . $this->maxCapacity . "', ";
                $command .= "'" . $this->type . "', ";
                $command .= "'" . $this->seatingAvailable . "', ";
                $command .= "'" . $this->facilities . "', ";
                $command .= "'" . $this->locationImage . "', ";
                $command .= "'" . $this->cost. "')";

                $data = $db->querySQL($command);
                return true;
            } catch (Exception $e) {
                echo 'Exception: ' . $e;
                return false;
            }
        } else
            return false;
    }

    private function initWith($id, $name, $address, $managerFName, $managerLName, $managerEmail, $managerNumber, $maxCapacity, $type, $seatingAvailable, $facilities, $locationImage, $cost) {
        $this->id = $id;
        $this->name = $name;
        $this->address = $address;
        $this->managerFName = $managerFName;
        $this->managerLName = $managerLName;
        $this->managerEmail = $managerEmail;
        $this->managerNumber = $managerNumber;
        $this->maxCapacity = $maxCapacity;
        $this->type = $type;
        $this->seatingAvailable = $seatingAvailable;
        $this->facilities = $facilities;
        $this->locationImage = $locationImage;
        $this->cost = $cost;
    }

    function updateDB() {
        if ($this->isValid()) {
            $db = Database::getInstance();
            $data = 'UPDATE Proj_Location SET
			name = \'' . $this->name . '\' ,
			address = \'' . $this->address . '\' ,
			managerFName = \'' . $this->managerFName . '\' ,
                        managerLName = \'' . $this->managerLName . '\' ,
                        managerEmail = \'' . $this->managerEmail . '\' ,
                        managerNumber = \'' . $this->managerNumber . '\' ,
                        maxCapacity = \'' . $this->maxCapacity . '\',
                        type = \'' . $this->type . '\',
                        seatingAvailable = \'' . $this->seatingAvailable . '\',
                        facilities = \'' . $this->facilities . '\',
                        cost = \'' . $this->cost . '\'
				WHERE id = ' . $this->id;
            $db->querySQL($data);
        }
    }

    function getAllLocations() {
        $db = Database::getInstance();
        $data = $db->multiFetch('Select * from Proj_Location');
        return $data;
    }

    private function isValid() {
        $errors = true;
        if (empty($this->name))
            $errors = false;
        if (empty($this->address))
            $errors = false;
        if (empty($this->managerFName))
            $errors = false;
        if (empty($this->managerLName))
            $errors = false;
        if (empty($this->managerEmail))
            $errors = false;
        if (empty($this->managerNumber))
            $errors = false;
        if (empty($this->maxCapacity))
            $errors = false;
        if (empty($this->type))
            $errors = false;
        if (empty($this->seatingAvailable))
            $errors = false;
        if (empty($this->facilities))
            $errors = false;
        if (empty($this->cost))
            $errors = false;
        return $errors;
    }

}

?>