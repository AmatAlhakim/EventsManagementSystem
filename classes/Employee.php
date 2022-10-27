<?php

class Employee {

    private $id;
    private $name;
    private $email;
    private $password;
    private $phone;
    private $address;
    private $departmentId;
    private $enrollmentDate;

    public function __construct() {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->phone = $phone;
        $this->address = $address;
        $this->department = $departmentId;
        $this->enrollmentDate = $enrollmentDate;
    }

    public function getId() {
        return $this->id;
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

    public function getPhone() {
        return $this->phone;
    }

    public function getAddress() {
        return $this->address;
    }

    public function getDepartmentId() {
        return $this->departmentId;
    }

    public function getEnrollmentDate() {
        return $this->enrollmentDate;
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

    public function setPhone($phone): void {
        $this->phone = $phone;
    }

    public function setAddress($address): void {
        $this->address = $address;
    }

    public function setDepartmentId($departmentId): void {
        $this->departmentId = $departmentId;
    }

    public function setEnrollmentDate($enrollmentDate): void {
        $this->enrollmentDate = $enrollmentDate;
    }

    function deleteEmployee() {
        try {
            $db = Database::getInstance();
            $data = $db->querySql('Delete from Proj_Employee where id=' . $this->id);
            return true;
        } catch (Exception $e) {
            echo 'Exception: ' . $e;
            return false;
        }
    }

    function initWithId($id) {
        $db = Database::getInstance();
        $data = $db->singleFetch('SELECT * FROM Proj_Employee WHERE id = ' . $id);
        $this->initWith($data->id, $data->name, $data->email, $data->password, $data->phone, $data->address, $data->departmentId, $data->enrollmentDate);
    }

    function initWithEmployeeName() {
        $db = Database::getInstance();
        $data = $db->singleFetch('SELECT * FROM Proj_Employee WHERE name = \'' . $this->name . '\'');
        if ($data != null) {
            return false;
        }
        return true;
    }

    function registerEmployee() {
        if ($this->isValid()) {
            try {
                $db = Database::getInstance();
                $data = $db->querySql('INSERT INTO Proj_Employee (id, name, email, password, phone, address, departmentId, enrollmentDate) '
                        . 'VALUES (NULL, \'' . $this->name . '\',\'' . $this->email . '\',\'' . $this->password . '\''
                        . ', \'' . $this->phone . '\', \'' . $this->address . '\', \'' . $this->departmentId . '\', '
                        . '\'' . $this->enrollmentDate . '\')');
                return true;
            } catch (Exception $e) {
                echo 'Exception: ' . $e;
                return false;
            }
        } else
            return false;
    }

    private function initWith($id, $name, $email, $password, $phone, $address, $departmentId, $enrollmentDate) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->phone = $phone;
        $this->address = $address;
        $this->departmentId = $departmentId;
        $this->enrollmentDate = $enrollmentDate;
    }

    function updateDB() {
        if ($this->isValid()) {
            $db = Database::getInstance();
            $data = 'UPDATE Proj_Employee SET
			email = \'' . $this->email . '\' ,
			name = \'' . $this->name . '\' ,
			password = \'' . $this->password . '\' ,
                        phone = \'' . $this->phone . '\' ,
                        address = \'' . $this->address . '\' ,
                        departmentId = \'' . $this->departmentId . '\' ,
                        enrollmentDate = \'' . $this->enrollmentDate . '\'
				WHERE id = ' . $this->id;
            $db->querySQL($data);
        }
    }

    function getAllEmployees() {
        $db = Database::getInstance();
        $data = $db->multiFetch('Select * from Proj_Employee');
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
        if (empty($this->phone))
            $errors = false;
        if (empty($this->address))
            $errors = false;
        if (empty($this->departmentId))
            $errors = false;
        if (empty($this->enrollmentDate))
            $errors = false;
        return $errors;
    }
}
?>

