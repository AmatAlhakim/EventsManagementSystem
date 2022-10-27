<?php

class ServiceItem {

    private $id;
    private $name;
    private $cost;
    private $serviceId;

    public function __construct() {
        $this->id = $id;
        $this->name = $name;
        $this->serviceId = $serviceId;
        $this->cost = $cost;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getServiceId() {
        return $this->serviceId;
    }

    public function getCost() {
        return $this->cost;
    }

    public function setId($id): void {
        $this->id = $id;
    }

    public function setName($name): void {
        $this->name = $name;
    }

    public function setServiceId($serviceId): void {
        $this->serviceId = $serviceId;
    }

    public function setCost($cost): void {
        $this->cost = $cost;
    }

    function deleteServiceItem() {
        try {
            $db = Database::getInstance();
            $data = $db->querySql('Delete from Proj_ServiceItem where id=' . $this->id);
            return true;
        } catch (Exception $e) {
            echo 'Exception: ' . $e;
            return false;
        }
    }

    function initWithId($id) {
        $db = Database::getInstance();
        $data = $db->singleFetch('SELECT * FROM Proj_ServiceItem WHERE id = ' . $id);
        $this->initWith($data->id, $data->name, $data->serviceId, $data->cost);
    }

    function initWithServiceItemName() {
        $db = Database::getInstance();
        $data = $db->singleFetch('SELECT * FROM Proj_ServiceItem WHERE name = \'' . $this->name . '\'');
        if ($data != null) {
            return false;
        }
        return true;
    }

    function registerServiceItem() {
        if ($this->isValid()) {
            try {
                $db = Database::getInstance();
                $data = $db->querySql('INSERT INTO Proj_ServiceItem (id, name, cost, serviceId) '
                        . 'VALUES (NULL, \'' . $this->name . '\',\'' . $this->cost . '\',\'' . $this->serviceId . '\')');
                return true;
            } catch (Exception $e) {
                echo 'Exception: ' . $e;
                return false;
            }
        } else
            return false;
    }

    private function initWith($id, $name, $serviceId, $cost) {
        $this->id = $id;
        $this->name = $name;
        $this->serviceId = $serviceId;
        $this->cost = $cost;
    }

    function updateDB() {
        if ($this->isValid()) {
            $db = Database::getInstance();
            $data = 'UPDATE Proj_ServiceItem set
			name = \'' . $this->name . '\' ,
			serviceId = \'' . $this->serviceId . '\' ,
			cost = \'' . $this->cost . '\'  
				WHERE id = ' . $this->id;
            $db->querySql($data);
        }
    }

    function getAllServiceItems() {
        $db = Database::getInstance();
        $data = $db->multiFetch('Select * from Proj_ServiceItem');
        return $data;
    }
    
    function getServiceItemsByServiceId($serviceID){
        $db = Database::getInstance();
        $data = $db->multiFetch('Select * from Proj_ServiceItem WHERE serviceId = ' . $serviceID);
        return $data;
    }

    public function isValid() {
        $errors = true;
        if (empty($this->name))
            $errors = false;
        if (empty($this->serviceId))
            $errors = false;
        if (empty($this->cost))
            $errors = false;
        return $errors;
    }

}
?>



