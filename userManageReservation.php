<?php
require_once 'utils/functions.php';
require_once 'classes/Users.php';
start_session();
if (!is_logged_in()) {
    header("Location: login_form.php");
}
$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Manage Reservations</title>
        <?php require 'utils/styles.php'; ?>
        <?php require 'utils/scripts.php'; ?>
        <?php require './debugging.php'; ?>
    </head>
    <body>
        <?php
        require 'utils/header.php';
        ?>
        <div class = "content">
            <div class = "container">
                <?php
                require_once './functions.php';
                require './classes/BookedEvents.php';
                require './classes/Client.php';
                require './classes/Event.php';
                
                if (isset($_POST['ReservationID'])) {
                    $_SESSION['UpdatingMessage'] = null;
                    $show = false;
                    try {
                        if (empty($_POST['ReservationID'])) {
                            $errors['ReservationID'] = "You should enter a "
                                    . "valid reservation id.";
                        }
                        if (empty($errors)) {
                            $id = trim($_POST['ReservationID']);
                            $_SESSION['ReservationID'] = $id;
                            $bookedEvent = new BookedEvents();
                            $bookedEvent->initWithUid($id);
                            
                            $row = "";
                            if (!empty($bookedEvent->getId())) { //reservation id exists
                                $clientId = $bookedEvent->getClientId();
                                $eventId = $bookedEvent->getEventId();
                                $client = new Client();
                                $client->initWithId($clientId);
                                $event = new Event();
                                $event->initWithId($eventId);
                                $show = true;
                                $_SESSION['ReservationCost'] = $bookedEvent->getTotalCost();
                                $_SESSION['ClientID'] = $client->getId();
                            } else
                                echo '<h2 style="color: darkblue;"> '
                                . 'Reservation Record Is Empty</h2>';
                        }
                    } catch (Exception $ex) {
                        $errorMessage = $ex->getMessage();
                    }
                }
                if (!isset($errors)) {
                    $errors = array();
                }
                ?>
                <br>
                <a class="btn btn-info navbar-btn pull-left" href="userDashboard.php">
                    <span class="glyphicon glyphicon-circle-arrow-left"></span> Back To Dashboard</a>
                <br><br><br><br>
                <h2 class="aler alert-info"><?php echo $_SESSION['UpdatingMessage'];?></h2>
                <br><br>
                <div>
                    <form action="userManageReservation.php" method="Post">
                        <div class="form-group">
                            <label class="col-md-2 control-label">Reservation ID:</label><!--label-->
                            <div class="col-md-5">
                                <input type="text" 
                                       class="form-control" 
                                       id="ReservationID" 
                                       name="ReservationID" 
                                       value="<?php if (isset($_POST['ReservationID'])) 
                                           echo $_POST['ReservationID']; ?>" /><!--input with current data from database-->
                            </div>
                            <div class="col-md-4">
                                <span id="ReservationIDError" class="error">
                                    <?php echoValue($errors, 'ReservationID') ?>
                                </span><!--error message for invalid input-->
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary pull-right" 
                                name="submit">Show Reservation <span class="glyphicon glyphicon-floppy-disk"></span></button>
                        <input type ="hidden" name="submitted" value="TRUE">
                    </form>
                </div>
                <br><br><br>
                <!--Event Details-->
                <h3>Event Details</h3>
                <h4>Any updates for event details cost 5% of the overall cost</h4>
                <table class = "table table-hover">
                    <thead><!--table labels-->
                        <tr>
                            <th>Event ID</th>
                            <th>Title</th>
                            <th>Description</th>                    
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Cost</th>
                            <th>Location ID</th>
                        </tr>
                    </thead>
                    <tbody><!--table contents, pulled from database-->
                        <?php
                        if ($show) {
                            echo '<tr>';
                            echo '<td>' . $event->getId() . '</td>';
                            echo '<td>' . $event->getTitle() . '</td>';
                            echo '<td>' . $event->getDescription() . '</td>';
                            echo '<td>' . $event->getStartDate() . '</td>';
                            echo '<td>' . $event->getEndDate() . '</td>';
                            echo '<td>' . $event->getCost() . '</td>';
                            echo '<td>' . $event->getLocationID() . '</td>';
                            echo '<td>'
                            . '<a class="delete" href="userEditEvent.php?id=' . $event->getId() . '">Edit |</a> '
                            . '<a href="userDeletEvent.php?id=' . $event->getId() . '">Cancel</a> '
                            . '</td>';
                            echo '</tr>';
                            
                        }
                        ?>
                    </tbody>
                </table>
                <br><br>
                <!--Client Details-->
                <h3>Client Details</h3>
                <h4>No extra fees applied for updating client details</h4>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Client ID</th>
                            <th>Name</th>
                            <th>Email</th>                    
                            <th>Password</th>
                            <th>Address</th>
                            <th>Phone No</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($show) {
                            echo '<tr>';
                            echo '<td>' . $client->getId() . '</td>';
                            echo '<td>' . $client->getName() . '</td>';
                            echo '<td>' . $client->getEmail() . '</td>';
                            echo '<td>' . $client->getPassword() . '</td>';
                            echo '<td>' . $client->getAddress() . '</td>';
                            echo '<td>' . $client->getPhone() . '</td>';
                            echo '<td>'
                            . '<a href="userEditClient.php?id=' . $client->getId() . '">Edit</a> '
                            . '</td>';
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>              
            </div>
        </div>
        <?php require 'utils/footer.php'; ?>
    </body>
</html>

