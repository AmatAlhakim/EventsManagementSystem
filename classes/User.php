<?php
class User {
    private $id;
    private $username;
    private $password;
    private $role;
    private $employeeId;

    public function __construct($i, $uname, $pwd, $r, $employeeId) {
        $this->id = $i;
        $this->username = $uname;
        $this->password = $pwd;
        $this->role = $r;
        $this->employeeId = $employeeId;
    }
    public function getId() { return $this->id; }
    public function getUsername() { return $this->username; }
    public function getPassword() { return $this->password; }
    public function getRole() { return $this->role; }
    public function getEmployeeId() {  return $this->employeeId;  }
     
    public function setId($i) { $this->id = $i; }
    public function setUsername($n) { $this->username = $n; }
    public function setPassword($p) { $this->password = $p; }
    public function setRole($r) { $this->role = $r; }
    public function setEmployeeId($employeeId) { $this->employeeId = $employeeId; }

}
?>
