<?php

class Payment {

    private $id;
    private $cardNumber;
    private $cardHolder;
    private $expireDate;
    private $pin;
    private $bookedEventID;

    public function __construct() {
        $this->id = $id;
        $this->cardNumber = $cardNumber;
        $this->cardHolder = $cardHolder;
        $this->expireDate = $expireDate;
        $this->pin = $pin;
        $this->bookedEventID = $bookedEventID;
    }

    public function getId() {
        return $this->id;
    }

    public function getCardNumber() {
        return $this->cardNumber;
    }

    public function getCardHolder() {
        return $this->cardHolder;
    }

    public function getExpireDate() {
        return $this->expireDate;
    }

    public function getPin() {
        return $this->pin;
    }

    public function getBookedEventID() {
        return $this->bookedEventID;
    }

    public function setPin($pin) {
        $this->pin = $pin;
    }

    public function setBookedEventID($bookedEventID) {
        $this->bookedEventID = $bookedEventID;
    }

    public function setId($id): void {
        $this->id = $id;
    }

    public function setCardNumber($cardNumber): void {
        $this->cardNumber = $cardNumber;
    }

    public function setCardHolder($cardHolder): void {
        $this->cardHolder = $cardHolder;
    }

    public function setExpireDate($expireDate): void {
        $this->expireDate = $expireDate;
    }

    function deleteService() {
        try {
            $db = Database::getInstance();
            $data = $db->querySql('Delete from Proj_Payment where id=' . $this->id);
            return true;
        } catch (Exception $e) {
            echo 'Exception: ' . $e;
            return false;
        }
    }

    function initWithId($id) {
        $db = Database::getInstance();
        $data = $db->singleFetch('SELECT * FROM Proj_Payment WHERE id = ' . $id);
        $this->initWith($data->id, $data->cardNumber, $data->cardHolder, $data->expireDate, $data->pin, $data->bookedEventID);
    }

    function initWithCardNumber() {
        $db = Database::getInstance();
        $data = $db->singleFetch('SELECT * FROM Proj_Payment WHERE cardNumber = \'' . $this->cardNumber . '\'');
        if ($data != null) {
            return false;
        }
        return true;
    }

    function registerPayment() {
        if ($this->isValid()) {
            try {
                $db = Database::getInstance();
                $data = $db->querySql('INSERT INTO Proj_Payment (id, cardNumber, cardHolder, expireDate, pin, bookedEventId) '
                        . 'VALUES (NULL, \'' . $this->cardNumber . '\',\'' . $this->cardHolder
                        . '\',\'' . $this->expireDate . '\' ,\'' . $this->pin . '\' ,\'' . $this->bookedEventID . '\')');
                return true;
            } catch (Exception $e) {
                echo 'Exception: ' . $e;
                return false;
            }
        } else
            return false;
    }

    private function initWith($id, $cardNumber, $cardHolder, $expireDate, $pin, $bookedEventId) {
        $this->id = $id;
        $this->cardNumber = $cardNumber;
        $this->cardHolder = $cardHolder;
        $this->expireDate = $expireDate;
        $this->pin = $pin;
        $this->bookedEventID = $bookedEventId;
    }

    function updateDB() {
        if ($this->isValid()) {
            $db = Database::getInstance();
            $data = 'UPDATE Proj_Payment set
			cardNumber = \'' . $this->cardNumber . '\' ,
			cardHolder = \'' . $this->cardNumber . '\' ,
			expireDate = \'' . $this->expireDate . '\',  
                        pin = \'' . $this->pin . '\',
                        bookedEventId = \'' . $this->bookedEventID . '\'
				WHERE id = ' . $this->id;
            $db->querySql($data);
        }
    }

    function getAllServices() {
        $db = Database::getInstance();
        $data = $db->multiFetch('Select * from Proj_Payment');
        return $data;
    }

    public function isValid() {
        $errors = true;
        if (empty($this->cardNumber))
            $errors = false;
        if (empty($this->cardHolder))
            $errors = false;
        if (empty($this->expireDate))
            $errors = false;
        if (empty($this->pin))
            $errors = false;
        if (empty($this->bookedEventID))
            $errors = false;
        return $errors;
    }

}

?>
