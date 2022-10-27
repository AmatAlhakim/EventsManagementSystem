<?php

class Files {

    private $fid;
    private $fname;
    private $ftype;
    private $flocation;
    private $locationId;

    public function getFid() {
        return $this->fid;
    }

    public function setFid($fid) {
        $this->fid = $fid;
    }

    public function getLocationId() {
        return $this->locationId;
    }

    public function setLocationId($locationId) {
        $this->locationId = $locationId;
    }

    public function getFname() {
        return $this->fname;
    }

    public function setFname($fname) {
        $this->fname = $fname;
    }

    public function getFtype() {
        return $this->ftype;
    }

    public function setFtype($ftype) {
        $this->ftype = $ftype;
    }

    public function getFlocation() {
        return $this->flocation;
    }

    public function setFlocation($flocation) {
        $this->flocation = $flocation;
    }

    function __construct() {
        $this->fid = $fid;
        $this->fname = $fname;
        $this->ftype = $ftype;
        $this->flocation = $flocation;
        $this->locationId = $locationId;
    }

    function deleteFile() {
        try {
            $db = Database::getInstance();
            $data = $db->querySql('Delete from Proj_File where id=' . $this->fid);
            unlink($this->flocation);
            return true;
        } catch (Exception $e) {
            echo 'Exception: ' . $e;
            return false;
        }
    }

    function initWithFid($fid) {

        $db = Database::getInstance();
        $data = $db->singleFetch('SELECT * FROM Proj_File WHERE id = ' . $fid);
        $this->initWith($data->fid, $data->fname, $data->flocation, $data->ftype, $data->locationId);
    }
    
    function initWithLocationName($locationName) {
        $db = Database::getInstance();
        $data = $db->singleFetch('SELECT * FROM Proj_File WHERE locationId = ' . "'".$locationName."'");
        $this->initWith($data->fid, $data->fname, $data->flocation, $data->ftype, $data->locationId);
    }

    function addFile() {
        try {
            $db = Database::getInstance();
            $data = $db->querySql('INSERT INTO Proj_File (id, fname, ftype, '
                    . 'flocation,locationId) VALUES '
                    . '(NULL, '. '\'' . $this->fname . '\''
                    . ', \'' . $this->ftype 
                    . '\',\''. $this->flocation 
                    . '\',\'' . $this->locationId .'\')');
            return true;
        } catch (Exception $e) {
            echo 'Exception: ' . $e;
            return false;
        }
    }

    private function initWith($fid, $fname, $flocation, $ftype, $locationId) {
        $this->fid = $fid;
        $this->fname = $fname;
        $this->flocation = $flocation;
        $this->ftype = $ftype;
        $this->locationId = $locationId;
    }

    function updateDB($oldName) {
        $db = Database::getInstance();
        $data = 'UPDATE Proj_File set
			fname = \'' . $this->fname . '\' ,
			ftype = \'' . $this->ftype . '\' ,
			flocation = \'' . $this->flocation . '\' ,
                        locationId = ' ."'". $this->locationId ."'". '
                            WHERE locationId = ' . "'". $oldName . "'";
        $db->querySql($data);
    }

    function getAllFiles() {
        $db = Database::getInstance();
        $data = $db->multiFetch('Select * from Proj_File');
        return $data;
    }

    function getMyFiles() {
        $db = Database::getInstance();
        $data = $db->multiFetch('Select * from Proj_File where locationId=' . $this->locationId);
        return $data;
    }

}