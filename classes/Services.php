<?php

class Services {

    private $id;
    private $name;
    private $description;
    private $cost;

    public function __construct() {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->cost = $cost;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getDescription() {
        return $this->description;
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

    public function setDescription($description): void {
        $this->description = $description;
    }

    public function setCost($cost): void {
        $this->cost = $cost;
    }

    function deleteService() {
        try {
            $db = Database::getInstance();
            $data = $db->querySql('Delete from Proj_Service where id=' . $this->id);
            return true;
        } catch (Exception $e) {
            echo 'Exception: ' . $e;
            return false;
        }
    }

    function initWithId($id) {
        $db = Database::getInstance();
        $data = $db->singleFetch('SELECT * FROM Proj_Service WHERE id = ' . $id);
        $this->initWith($data->id, $data->name, $data->description, $data->cost);
    }

    function initWithServiceName() {
        $db = Database::getInstance();
        $data = $db->singleFetch('SELECT * FROM Proj_Service WHERE name = \'' . $this->name . '\'');
        if ($data != null) {
            return false;
        }
        return true;
    }

    function registerService() {
        if ($this->isValid()) {
            try {
                $db = Database::getInstance();
                $data = $db->querySql('INSERT INTO Proj_Service (id, name, description, cost) '
                        . 'VALUES (NULL, \'' . $this->name . '\',\'' . $this->description . '\',\'' . $this->cost . '\')');
                return true;
            } catch (Exception $e) {
                echo 'Exception: ' . $e;
                return false;
            }
        } else
            return false;
    }

    private function initWith($id, $name, $description, $cost) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->cost = $cost;
    }

    function updateDB() {
        if ($this->isValid()) {
            $db = Database::getInstance();
            $data = 'UPDATE Proj_Service set
			name = \'' . $this->name . '\' ,
			description = \'' . $this->name . '\' ,
			cost = \'' . $this->cost . '\'  
				WHERE id = ' . $this->id;
            $db->querySql($data);
        }
    }

    function getAllServices() {
        $db = Database::getInstance();
        $data = $db->multiFetch('Select * from Proj_Service');
        return $data;
    }

    public function isValid() {
        $errors = true;
        if (empty($this->name))
            $errors = false;
        if (empty($this->description))
            $errors = false;
        if (empty($this->cost))
            $errors = false;
        return $errors;
    }
}
?>

