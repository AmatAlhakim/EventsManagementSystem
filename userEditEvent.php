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
        <meta charset="UTF-8">
        <title>Edit Event Reservation</title>
        <?php require 'utils/styles.php'; ?><!--css links. file found in utils folder-->
        <?php require 'utils/scripts.php'; ?><!--js links. file found in utils folder-->
        <?php require './debugging.php'; ?>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>  
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />  
        <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>  
        <link rel="stylesheet" href="http://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css"> 
    </head>

    <body>
        <?php
        require './functions.php';
        require_once 'classes/Event.php';
        require_once 'classes/BookedEvents.php';
        require_once 'classes/Client.php';
        require_once 'classes/Location.php';

        $id = 0;
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $_SESSION['locationId'] = $id;
        } elseif (isset($_POST['id'])) {
            $id = $_POST['id'];
        } else {
            echo '<p class="error">No Event id Parameter</p>';
            exit();
        }

        $event = new Event();
        $event->initWithId($id);

        if (isset($_POST['submitted'])) {
            try {
                if (empty($_POST['Name'])) {
                    $errors['Name'] = "Name Is required";
                }
                if (empty($_POST['Description'])) {
                    $errors['Description'] = "Description Is required";
                }
                if (empty($_POST['StartDate'])) {
                    $errors['StartDate'] = "StartDate Is required";
                }
                if (empty($_POST['EndDate'])) {
                    $errors['EndDate'] = "EndDate Is required";
                }
                if (empty($_POST['Duration'])) {
                    $errors['Duration'] = "Event Duration Is required";
                }
                if (empty($_POST['NumOfAttendees'])) {
                    $errors['NumOfAttendees'] = "Num of Attendees Is required";
                }
                if (empty($_POST['Services'])) {
                    $errors['Services'] = "Services Is required";
                }
                if (empty($_POST['LocationID'])) {
                    $errors['LocationID'] = "LocationID Is required";
                }
                if (empty($errors)) {
                    //Get the values
                    $startDate = date(trim($_POST['StartDate']));
                    $today = date("Y/m/d");
                    $endDate = date(trim($_POST['EndDate']));
                    $event->setTitle(trim($_POST['Name']));
                    $event->setDescription(trim($_POST['Description']));
                    $event->setStartDate($startDate);
                    $event->setEndDate($endDate);
                    $event->setDuration(trim($_POST['Duration']));
                    $event->setNumOfAudience(trim($_POST['NumOfAttendees']));
                    $event->setService(trim($_POST['Services']));
                    $event->setLocationID(trim($_POST['LocationID']));
                    //update location in the db
                    
                    $client = new Client();
                    $client->initWithId($_SESSION['ClientID']);
                    $points = $client->getRoyaltyPoint();
                                        
                    $location = new Location();
                    $location->initWithId($event->getLocationID());
                    $locationPrice = $location->getCost();
                    
                    $reservationCost = $locationPrice * $event->getDuration();
                    $event->setCost($reservationCost);        
                    //calculate the discount
                    $discount = 0.0;
                    if ($points > 0 && $points <= 10)
                        $discount = $reservationCost * 0.05;
                    else if ($points > 10 && $points <= 15)
                        $discount = $reservationCost * 0.1;
                    else if ($points > 15)
                        $discount = $reservationCost * 0.2;                    
                    
                    $totalReservationCost = $reservationCost - $discount;
                    
                    $bookedEvent = new BookedEvents();
                    $bookedEvent->initWithUid($_SESSION['ReservationID']);
                    $bookedEvent->setTotalCost($totalReservationCost);
                    $bookedEvent->updateDB();
                    $event->updateDB();
                    header('Location: userConfirmChanges.php');
                } else {
                    echo '<h4>There are some errors</h4>';
                }
            } catch (Exception $ex) {
                $errorMessage = $ex->getMessage();
            }
        }
        if (!isset($errors)) {
            $errors = array();
        }
        ?>

        <?php require 'utils/header.php'; ?><!--header content. file found in utils folder-->
        <div class="content">
            <div class="container">
                <h1>View Event Details</h1><!--form title-->
                <?php
                if (isset($errorMessage)) {
                    echo '<p>Error: ' . $errorMessage . '</p>';
                }
                ?>
                <form action="userEditEvent.php" method="Post" class="form-horizontal">
                    <div class="form-group">
                        <label for="Name" class="col-md-2 control-label">Name</label><!--label-->
                        <div class="col-md-5">
                            <input type="text" 
                                   class="form-control" 
                                   id="Name" 
                                   name="Name" 
                                   value="<?php echo $event->getTitle(); ?>" 
                                   /><!--input with current data from database-->
                        </div>
                        <div class="col-md-4">
                            <span id="LNameError" class="error">
                                <?php echoValue($errors, 'Name') ?>
                            </span><!--error message for invalid input-->
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="Description" class="col-md-2 control-label">Description</label><!--label-->
                        <div class="col-md-5">
                            <input type="text" 
                                   class="form-control" 
                                   id="Description" 
                                   name="Description" 
                                   value="<?php echo $event->getDescription(); ?>" 
                                   /><!--input with current data from database-->
                        </div>
                        <div class="col-md-4">
                            <span id="DescriptionError" class="error">
                                <?php echoValue($errors, 'Description') ?>
                            </span><!--error message for invalid input-->
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="StartDate" class="col-md-2 control-label">Start Date</label><!--label-->
                        <div class="col-md-5">
                            <input type="text" 
                                   class="form-control" 
                                   id="StartDate" 
                                   name="StartDate" 
                                   value="<?php echo $event->getStartDate(); ?>" 
                                   /><!--input with current data from database-->
                        </div>
                        <div class="col-md-4">
                            <span id="StartDateError" class="error">
                                <?php echoValue($errors, 'StartDate') ?>
                            </span><!--error message for invalid input-->
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="EndDate" class="col-md-2 control-label">End Date</label><!--label-->
                        <div class="col-md-5">
                            <input type="text" 
                                   class="form-control" 
                                   id="EndDate" 
                                   name="EndDate" 
                                   value="<?php echo date($event->getEndDate()); ?>" 
                                   /><!--input with current data from database-->
                        </div>
                        <div class="col-md-4">
                            <span id="EndDateError" class="error">
                                <?php echoValue($errors, 'End') ?>
                            </span><!--error message for invalid input-->
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="Cost" class="col-md-2 control-label">Duration</label><!--label-->
                        <div class="col-md-5">
                            <input type="text" 
                                   class="form-control" 
                                   id="Duration" 
                                   name="Duration" 
                                   value="<?php echo $event->getDuration(); ?>" 
                                   /><!--input with current data from database-->
                        </div>
                        <div class="col-md-4">
                            <span id="DurationError" class="error">
                                <?php echoValue($errors, 'Duration') ?>
                            </span><!--error message for invalid input-->
                        </div>
                    </div> 

                    <div class="form-group">
                        <label for="NumOfAttendees" class="col-md-2 control-label">Num Of Attendees</label><!--label-->
                        <div class="col-md-5">
                            <input type="text" 
                                   class="form-control" 
                                   id="NumOfAttendees" 
                                   name="NumOfAttendees" 
                                   value="<?php echo $event->getNumOfAudience(); ?>" 
                                   /><!--input with current data from database-->
                        </div>
                        <div class="col-md-4">
                            <span id="NumOfAttendeesError" class="error">
                                <?php echoValue($errors, 'NumOfAttendees') ?>
                            </span><!--error message for invalid input-->
                        </div>
                    </div> 

                    <div class="form-group">
                        <label for="Services" class="col-md-2 control-label">Service</label><!--label-->
                        <div class="col-md-5">
                            <input type="text" 
                                   class="form-control" 
                                   id="Services" 
                                   name="Services" 
                                   value="<?php echo $event->getService(); ?>" 
                                   /><!--input with current data from database-->
                        </div>
                        <div class="col-md-4">
                            <span id="ServicesError" class="error">
                                <?php echoValue($errors, 'Services') ?>
                            </span><!--error message for invalid input-->
                        </div>
                    </div> 

                    <div class="form-group">
                        <label for="LocationID" class="col-md-2 control-label">Location ID</label><!--label-->
                        <div class="col-md-5">
                            <input type="text" 
                                   class="form-control" 
                                   id="LocationID" 
                                   name="LocationID" 
                                   value="<?php echo $event->getLocationID(); ?>" 
                                   /><!--input with current data from database-->
                        </div>
                        <div class="col-md-4">
                            <span id="ServicesError" class="error">
                                <?php echoValue($errors, 'LocationID') ?>
                            </span><!--error message for invalid input-->
                        </div>
                    </div> 

                    <div class="form-group row" style="margin-top: 40px;">
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary" name="submit">Confirm Changes & Pay <span class="glyphicon glyphicon-floppy-disk"></span></button><!--submit button-->
                            <input type ="hidden" name="submitted" value="TRUE">
                            <input type ="hidden" name="id" value="<?php echo $id ?>"/>
                            <br><br>
                            <a class="btn btn-info pull-left" href="userManageReservation.php"><span class="glyphicon glyphicon-circle-arrow-left"></span> Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <?php require 'utils/footer.php'; ?><!--footer content. file found in utils folder-->
    </body>
</html>

<!--AJAX SEARCHING & LISTING SECTION-->
<script>
    $(document).ready(function () {
        $.datepicker.setDefaults({
            dateFormat: 'yy-mm-dd'
        })
        $(function () {
            $("#StartDate").datepicker();
            $("#EndDate").datepicker();
        });
        $('#submitted').click(function () {
            var from_date = $('#StartDate').val();
            var to_date = $('#EndDate').val();
            if (from_date !== '' && to_date !== '') {
            } else {
                alert("Please Select A Date")
            }
        })
    });
</script>
