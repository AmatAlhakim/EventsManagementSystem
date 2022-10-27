<?php

class Client {

    private $id;
    private $name;
    private $email;
    private $password;
    private $address;
    private $phone;
    private $royaltyPoint;

    public function __construct() {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->address = $address;
        $this->phone = $phone;
        $this->royaltyPoint = $royaltyPoint;
    }

    public function getId() {
        return $this->id;
    }
    
    public function GetIdForClientDetails() {
        $db = Database::getInstance();
        $data = $db->multiFetch('Select id from Proj_Client where name = ' . "'" . $this->name . "'");
        return $data;
    }

    public function getName() {
        return $this->name;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getAddress() {
        return $this->address;
    }

    public function getPhone() {
        return $this->phone;
    }

    public function getRoyaltyPoint() {
        return $this->royaltyPoint;
    }
    
    public function getRoyaltyPointForClientDetails() {
        //return $this->royaltyPoint;
        $db = Database::getInstance();
        $data = $db->multiFetch('Select royaltyPoint from Proj_Client where name = ' . "'" . $this->name . "'");
        return $data;
    }

    public function setId($id): void {
        $this->id = $id;
    }

    public function setName($name): void {
        $this->name = $name;
    }

    public function setEmail($email): void {
        $this->email = $email;
    }

    public function setPassword($password): void {
        $this->password = $password;
    }

    public function setAddress($address): void {
        $this->address = $address;
    }

    public function setPhone($phone): void {
        $this->phone = $phone;
    }

    public function setRoyaltyPoint($royaltyPoint): void {
        $this->royaltyPoint = $royaltyPoint;
    }
    
    public function updateRoyaltyPoint ($royaltyPoint): void {
        $db = Database::getInstance();
        $data = 'UPDATE Proj_Client set
                royaltyPoint = ' . $royaltyPoint . ' WHERE name = ' . "'".$this->name."'";
        $db->querySql($data);
    }

    function deleteClient() {
        try {
            $db = Database::getInstance();
            $data = $db->querySql('Delete from Proj_Client where id = ' . $this->id);
            return true;
        } catch (Exception $e) {
            echo 'Exception: ' . $e;
            return false;
        }
    }

    function initWithId($id) {
        $db = Database::getInstance();
        $data = $db->singleFetch('SELECT * FROM Proj_Client WHERE id = ' . $id);
        $this->initWith($data->id, $data->name, $data->email, $data->password, $data->address, $data->phone, $data->royaltyPoint);
    }

    function initWithClientName() {
        $db = Database::getInstance();
        $data = $db->singleFetch('SELECT * FROM Proj_Client WHERE name = \'' . $this->name . '\'');
        if ($data != null) {
            return false;
        }
        return true;
    }

    function registerClient() {
        if ($this->isValid()) {
            try {
                $db = Database::getInstance();
                $data = $db->querySql('INSERT INTO Proj_Client (id, name, email, password, address, phone, royaltyPoint) '
                        . 'VALUES (NULL, \'' . $this->name . '\',\'' . $this->email . '\',\'' . $this->password . '\''
                        . ' , \'' . $this->address . '\', \'' . $this->phone . '\', \'' . $this->royaltyPoint . '\')');
                return true;
            } catch (Exception $e) {
                echo 'Exception: ' . $e;
                return false;
            }
        } else
            return false;
    }

    private function initWith($id, $name, $email, $password, $address, $phone, $royaltyPoint) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->address = $address;
        $this->phone = $phone;
        $this->royaltyPoint = $royaltyPoint;
    }

    function updateDB() {
        if ($this->isValid()) {
            $db = Database::getInstance();
            $data = 'UPDATE Proj_Client set
			name = \'' . $this->name . '\' ,
			email = \'' . $this->email . '\' ,
			password = \'' . $this->password . '\' ,
                        address = \'' . $this->address . '\',
                        phone = \'' . $this->phone . '\',
                        royaltyPoint = \'' . $this->royaltyPoint . '\'
				WHERE id = ' . $this->id;
            $db->querySql($data);
        }
    }

    function getAllClients() {
        $db = Database::getInstance();
        $data = $db->multiFetch('Select * from Proj_Client');
        return $data;
    }
    
    function getTotalNumOfClients() {
        $db = Database::getInstance();
        $data = $db->multiFetch('Select count(id) from Proj_Client');
        return $data;
    }
    
    function getAllClientByStatus($status, $range) {
        $db = Database::getInstance();
        $data = $db->multiFetch('Select * from Proj_Client where royaltyPoint between '
                . ''. $status.' and '.$range);
        return $data;
    }

    public function isValid() {
        $errors = true;
        if (empty($this->name))
            $errors = false;
        if (empty($this->email))
            $errors = false;
        if (empty($this->password))
            $errors = false;
        if (empty($this->address))
            $errors = false;
        if (empty($this->phone))
            $errors = false;
        if (empty($this->royaltyPoint))
            $errors = false;
        return $errors;
    }

}

?>
