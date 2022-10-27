<?php

class Department {
    //Declare variables
    private $id;
    private $departmentName;
    
    public function __construct() {
        $this->id = $id;
        $this->departmentName = $departmentName;
    }
    
    public function getId() {
        return $this->id;
    }

    public function getDepartmentName() {
        return $this->departmentName;
    }

    public function setId($id): void {
        $this->id = $id;
    }

    public function setDepartmentName($departmentName): void {
        $this->departmentName = $departmentName;
    }

    function deleteDepartment() {
        try {
            $db = Database::getInstance();
            $data = $db->querySql('Delete from Proj_Department where id=' . $this->id);
            return true;
        } catch (Exception $e) {
            echo 'Exception: ' . $e;
            return false;
        }
    }

    function initWithDeptId($id) {
        $db = Database::getInstance();
        $data = $db->singleFetch('SELECT * FROM Proj_Department WHERE id = ' . $id);
        $this->initWith($data->id, $data->departmentName);
    }

    function initWithDepartmentName() {
        $db = Database::getInstance();
        $data = $db->singleFetch('SELECT * FROM Proj_Department WHERE DepartmentName = \'' . $this->departmentName . '\'');
        if ($data != null) {
            return false;
        }
        return true;
    }

    private function initWith($id, $departmentName) {
        $this->id = $uid;
        $this->departmentName = $departmentName;
    }

    function updateDB() {
        if ($this->isValid()) {
            $db = Database::getInstance();
            $data = 'UPDATE Proj_Department set
			departmentName = \'' . $this->departmentName . '\' 
				WHERE id = ' . $this->id;
            $db->querySql($data);
        }
    }

    function getAllDepartments() {
        $db = Database::getInstance();
        $data = $db->multiFetch('Select * from Proj_Department');
        return $data;
    }

    public function isValid() {
        $errors = true;
        if (empty($this->departmentName))
            $errors = false;
        return $errors;
    }
}

?>

