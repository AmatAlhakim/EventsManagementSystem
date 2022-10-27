<?php
require_once 'Event.php';

class EventTableGateway {

    private $connect;
    
    public function __construct($c) {
        $this->connect = $c;
    }
    
    public function getEvents() {
        // execute a query to get all events
        $sqlQuery = "SELECT e.*, l.name " .
                    "FROM Proj_Event e " .
                    "LEFT JOIN Proj_Location l ON e.id = l.locationId";
        
        $statement = $this->connect->prepare($sqlQuery);
        $status = $statement->execute();
        
        if (!$status) {
            die("Could not retrieve event details");
        }
        
        return $statement;
    }
    
    public function getEventsByLocationId($id) {
        // execute a query to get all events
        $sqlQuery = "SELECT e.*, l.name " .
                    "FROM Proj_Event e " .
                    "LEFT JOIN Proj_Location l ON e.locationId = l.locationId " .
                    "WHERE e.locationId =:locationId";
        
        $params = array(
            "locationId" => $id
        );
        $statement = $this->connect->prepare($sqlQuery);
        $status = $statement->execute($params);
        
        if (!$status) {
            die("Could not retrieve event details");
        }
        
        return $statement;
    }
    
    public function getEventsById($id) {
        // execute a query to get an event with the specified id
        $sqlQuery = "SELECT * FROM Proj_Event WHERE id = :id";
        
        $statement = $this->connect->prepare($sqlQuery);
        $params = array(
            "id" => $id
        );
        
        $status = $statement->execute($params);
        
        if (!$status) {
            die("Could not retrieve Event ID");
        }
        
        return $statement;
    }
    
    public function insert($p) {
        $sql = "INSERT INTO Proj_Event (Title, Description, StartDate, EndDate, Cost, locationId, NumOfAudience, Hall, Service) " .
                "VALUES (:Title, :Description, :StartDate, :EndDate, :Cost, :locationId, :NumOfAudience, :Hall, :Service)";
        
        $statement = $this->connect->prepare($sql);
        $params = array(
            "Title"           => $p->getTitle(),
            "Description"     => $p->getDescription(),            
            "StartDate"       => $p->getStartDate(),
            "EndDate"         => $p->getEndDate(),
            "Cost"            => $p->getCost(),
            "locationId"      => $p->getLocationID(),
            "NumOfAudience"   => $p->getNumOfAudience(),
            "Hall"            => $p->getHall(),
            "Service"         => $p->getService()
        );
        
        echo "<pre>";
        print_r($p);
        print_r($params);
        echo "</pre>";
        
        $status = $statement->execute($params);
        
        
        if (!$status) {
            die("Could not insert event");
        }
        
        $id = $this->connect->lastInsertId();
        
        return $id;
    }

    public function update($p) {
        $sql = "UPDATE Proj_Event SET " .
                "Title = :Title, " . 
                "Description = :Description, " .                
                "StartDate = :StartDate, " .
                "EndDate = :EndDate, " .
                "Cost = :Cost, " .
                "locationId = :locationId, " .
                "NumOfAudience = :NumOfAudience".
                "Hall = :Hall".
                "Service = :Service".
                " WHERE id = :id";
        
        $statement = $this->connect->prepare($sql);
        $params = array(
            "Title"          => $p->getTitle(),
            "Description"    => $p->getDescription(),            
            "StartDate"      => $p->getStartDate(),
            "EndDate"        => $p->getEndDate(),
            "Cost"           => $p->getCost(),
            "locationId"     => $p->getLocationID(),
            "NumOfAudience"     => $p->getNumOfAudience(),
            "Hall"     => $p->getHall(),
            "Service"     => $p->getService(),
            "id"             => $p->getId()
        );
        
        echo "<pre>";
        print_r($p);
        print_r($params);
        print_r($statement);
        echo "</pre>";
        
        $status = $statement->execute($params);
        
        if (!$status) {
            die("Could not update event details");
        }
    }
    
    public function delete($id) {
        $sql = "DELETE FROM Proj_Event WHERE id = :id";
        
        $statement = $this->connect->prepare($sql);
        $params = array(
            "id" => $id
        );
        $status = $statement->execute($params);
        
        if (!$status) {
            die("Could not delete Proj_Event");
        }
    }    

    
}