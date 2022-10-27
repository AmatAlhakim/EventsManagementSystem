<?php

class Users {

    private $uid;
    private $username;
    private $password;
    private $role;
    private $employeeId;
    public $ok;
    public $salt;
    public $domain;

    public function __construct() {
        $this->uid = $uid;
        $this->username = $username;
        $this->password = $password;
        $this->role = $role;
        $this->employeeId = $employeeId;
        $this->ok = false;
        $this->salt = 'ENCRYPT';
        $this->domain = '';
        if (!$this->check_session())
            $this->check_cookie();
        return $this->ok;
    }

    public function getUid() {
        return $this->uid;
    }

    public function getUidByUserName($name) {
        $db = Database::getInstance();
        $data = $db->multiFetch('Select id from Proj_User where username = ' . "'" . $name . "'");
        return $data;
    }

    public function getUserRoleByUserName($name) {
        $db = Database::getInstance();
        $data = $db->multiFetch('Select role from Proj_User where username = ' . "'" . $name . "'");
        return $data;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getRole() {
        return $this->role;
    }

    public function getEmployeeId() {
        return $this->employeeId;
    }

    public function setUid($uid) {
        $this->uid = $uid;
        return $this;
    }

    public function setUsername($username) {
        $this->username = $username;
        return $this;
    }

    public function setPassword($password) {
        $this->password = $password;
        return $this;
    }

    public function setRole($role) {
        $this->role = $role;
        return $this;
    }

    public function setEmployeeId($employeeId) {
        $this->employeeId = $employeeId;
        return $this;
    }

    public function deleteuser() {
        try {
            $db = Database::getInstance();
            $data = $db->querySql('Delete from Proj_User where id=' . $this->uid);
            return true;
        } catch (Exception $e) {
            echo 'Exception: ' . $e;
            return false;
        }
    }

    function initWithUid($uid) {
        $db = Database::getInstance();
        $data = $db->singleFetch('SELECT * FROM Proj_User WHERE id = ' . $uid);
        $this->initWith($data->uid, $data->username, $data->password, $data->role, $data->employeeId);
    }

    public function checkUser($username, $password) {
        $db = Database::getInstance();
        $command = "SELECT * FROM Proj_User WHERE username = '" . $username . "' ";
        $command .= "AND password = '" . $password . "' ";
        $data = $db->singleFetch($command);
        $this->initWith($data->uid, $data->username, $data->password, $data->role, $data->employeeId);
    }

    public function initWithUsername() {
        $db = Database::getInstance();
        $data = $db->singleFetch('SELECT * FROM Proj_User WHERE username = \'' . $this->username . '\'');
        if ($data != null) {
            return false;
        }
        return true;
    }

    public function registerUser() {
        if ($this->isValid()) {
            try {
                $db = Database::getInstance();
                $command = "INSERT INTO Proj_User (id, username, password, role, employeeId) ";
                $command .= "VALUES (NULL, '" . $this->username . "' , '";
                $command .= $this->password . "' , '";
                $command .= $this->role . "' , ";
                $command .= $this->employeeId;
                $command .= ")";
                $data = $db->querySql($command);
                return true;
            } catch (Exception $e) {
                echo 'Exception: ' . $e;
                return false;
            }
        } else
            return false;
    }

    private function initWith($uid, $username, $password, $role, $employeeId) {
        $this->uid = $uid;
        $this->username = $username;
        $this->password = $password;
        $this->role = $role;
        $this->employeeId = $employeeId;
    }

    public function updateDB() {
        if ($this->isValid()) {
            $db = Database::getInstance();
            $data = 'UPDATE Proj_User set
			username = \'' . $this->username . '\' ,
			password = \'' . $this->password . '\'  
				WHERE id = ' . $this->uid;
            $db->querySql($data);
        }
    }

    public function getAllusers() {
        $db = Database::getInstance();
        $data = $db->multiFetch('Select * from Proj_User');
        return $data;
    }

    public function isValid() {
        $errors = true;
        if (empty($this->username))
            $errors = false;
        if (empty($this->role))
            $errors = false;
        if (empty($this->password))
            $errors = false;
        if (empty($this->employeeId))
            $errors = false;
        return $errors;
    }

    public function check_session() {
        if (!empty($_SESSION['uid'])) {
            $this->ok = true;
            return true;
        } else
            return false;
    }

    public function check_cookie() {
        if (!empty($_COOKIE['uid'])) {
            $this->ok = true;
            return $this->check($_COOKIE['uid']);
        } else
            return false;
    }

    public function check($uid) {
        $this->initWithUid($uid);
        if ($this->getUid() != null && $this->getUid() == $uid) {
            $this->ok = true;
            $_SESSION['uid'] = $this->getUid();
            $_SESSION['username'] = $this->getUsername();
            setcookie('uid', $_SESSION['uid'], time() + 60 * 60 * 24 * 7, '/', $this->domain);
            setcookie('username', $_SESSION['username'], time() + 60 * 60 * 24 * 7, '/', $this->domain);
            return true;
        } else
            $error[] = 'Wrong Username';
        return false;
    }

    public function login($username, $password) {
        try {
            $this->checkUser($username, $password);
            if (!empty($this->getUsername())) {
                $this->ok = true;
                $_SESSION['uid'] = $this->getUid();
                $_SESSION['username'] = $this->getUsername();
                setcookie('uid', $_SESSION['uid'], time() + 60 * 60 * 24 * 7, '/', $this->domain);
                setcookie('username', $_SESSION['username'], time() + 60 * 60 * 24 * 7, '/', $this->domain);
                return true;
            } else {
                $error[] = 'Wrong Username OR password';
                return false;
            }
            return false;
        } catch (Exception $e) {
            $error[] = $e->getMessage();
            return false;
        }
    }

    public function logout() {
        $this->ok = false;
        $_SESSION['uid'] = '';
        $_SESSION['username'] = '';
        setcookie('uid', '', time() - 3600, '/', $this->domain);
        setcookie('username', '', time() - 3600, '/', $this->domain);
        session_destroy();
    }

}
?>


