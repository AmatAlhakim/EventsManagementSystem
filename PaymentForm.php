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
        <title>Payment</title> 
        <?php require 'utils/styles.php'; ?><!--css links. file found in utils folder-->
        <?php require 'utils/scripts.php'; ?><!--js links. file found in utils folder-->
        <?php require './debugging.php'; ?>
    </head>
    <body>
        <?php
        require_once './functions.php';
        require_once './classes/Payment.php';
        require_once './classes/Event.php';
        require_once './classes/Client.php';
        require_once './classes/EventService.php';
        require './classes/BookedEvents.php';

        if (isset($_POST['submitted'])) {
            try {
                //Check Errors
                if (empty($_POST['cardNumber'])) {
                    $errors['cardNumber'] = "Card Number required";
                }
                if (empty($_POST['cardHolder'])) {
                    $errors['cardHolder'] = "Card Holder required";
                }
                if (empty($_POST['expireDate'])) {
                    $errors['expireDate'] = "Expire Date required";
                }
                if (empty($_POST['pin'])) {
                    $errors['pin'] = "PIN required";
                }

                if (empty($errors)) { //if there are no errrors
                    //calculate the reservation cost
                    $locationCost = $_SESSION['locationCost'];
                    $serviceCost = 0.0;//$_SESSION['ServiceItemCost'];
                    $royaltyPoints = 0;
                    //$royaltyPoints = $_SESSION['TotalRoyaltyPoint'];

                    $reservationCost = $locationCost;
                    
//                    if (!empty($serviceCost)) {
//                        $reservationCost += $serviceCost;
//                    }
//                    
                    //get the royality point of the client
                    $client = new Client();
                    $royaltyPoint = $client->getRoyaltyPointForClientDetails();
                    for ($i = 0; $i < count($royaltyPoint); $i++) {
                        $totalPoint += $royaltyPoint[$i]->royaltyPoint;
                    }
                    $client->updateRoyaltyPoint($totalPoint);
                    $client->updateDB();

                    //calculate the discount
                    $discount = 0.0;
                    if ($royaltyPoints > 0 && $royaltyPoints <= 10)
                        $discount = $reservationCost * 0.05;
                    else if ($royaltyPoints > 10 && $royaltyPoints <= 15)
                        $discount = $reservationCost * 0.1;
                    else if ($royaltyPoints > 15)
                        $discount = $reservationCost * 0.2;

                    $totalReservationCost = $reservationCost - $discount;
                    if (!empty($_SESSION['ServiceItemCost'])) {
                        $totalReservationCost += $_SESSION['ServiceItemCost'];
                    }
                    $_SESSION['totalReservationCost'] = $totalReservationCost;
                    $bookDate = date("Y-m-d");

                    //get the event obj
                    $event = new Event();
                    //add event obj to the db
                    $event->setTitle($_SESSION['eventTitle']);
                    $event->setDescription($_SESSION['eventDescription']);
                    $event->setEndDate($_SESSION['to_date']);
                    $event->setStartDate($_SESSION['from_date']);
                    $event->setCost($_SESSION['locationCost']);
                    $event->setLocationID($_SESSION['locationId']);
                    $event->setNumOfAudience($_SESSION['attendees']);
                    $event->setService($_SESSION['MotherServiceID']);
                    $event->setDuration($_SESSION['duration']);
                    if ($event->initWithEventTitle($_SESSION['eventTitle'])) {
                        if ($event->registerEvent()) {
                            echo "event was booked";
                           // $_SESSION['eventTitle'] = $title;
                        } else{
                            echo "failed to register the event";
                        }
                    }else{
                        $event->updateDB();
                    }
                    
                    $getEventId = $event->getIdByName($_SESSION['eventTitle']);
                    $eventId = 0;
                    for ($i = 0; $i < count($getEventId); $i++) {
                        $eventId = $getEventId[$i]->id;
                    }
                    $_SESSION['EventId'] = $eventId;
                    
                    //end adding event to the db
                    //
                    //create event services obj if the user chose to book a service
                    if (!empty($_SESSION['ServiceItemID'])) {
                        $evnetService = new EventService();
                        $evnetService->setEventId($eventId);
                        $evnetService->setServiceItemId($_SESSION['ServiceItemID']);
                        $evnetService->registerServiceItem();
                    }

                    //get user id 
                    $user = new Users();
                    $id = $user->getUidByUserName($_SESSION['username']);
                    $userId = 0;
                    for ($i = 0; $i < count($id); $i++) {
                        $userId = $id[$i]->id;
                    }
                    $_SESSION['userId'] = $userId;

                    //create event obj and set the propreties
                    $bookedEvent = new BookedEvents();
                    $bookedEvent->setEventId($eventId);
                    $bookedEvent->setClientId($_SESSION['ClientID']);
                    $bookedEvent->setBookDate($bookDate);
                    $bookedEvent->setTotalCost($totalReservationCost);
                    $bookedEvent->setUserId($_SESSION['uid']);
                    $bookedEvent->setUserId($userId);
                    $bookedEventId = 0;
                    
                    if ($bookedEvent->registerBookedEvent()) {
                        echo '<br>Reservation Is Done<br>';
                        $bookedIds = $bookedEvent->getIdByEventId($eventId);
                        for ($j = 0; $j < count($bookedIds); $j++) {
                            $bookedEventId = $bookedIds[$j]->id;
                        }
                    } else {
                        echo '<br>Reservation Failed<br>';
                    }
                    $_SESSION['bookDate'] = $bookDate;

                    //create payment event obj
                    //get the user input
                    $cardNumber = trim($_POST['cardNumber']);
                    $cardHolder = trim($_POST['cardHolder']);
                    $expireDate = trim($_POST['expireDate']);
                    $pin = trim($_POST['pin']);

                    //set the new employee properties
                    $payment = new Payment();
                    $payment->setCardNumber($cardNumber);
                    $payment->setCardHolder($cardHolder);
                    $payment->setExpireDate($expireDate);
                    $payment->setPin($pin);

                    $payment->setBookedEventID($bookedEventId);
                    $_SESSION['bookedEventID'] = $bookedEventId;

                    //check if the employee exists
                    if ($payment->registerPayment()) {
                        echo '<p>paid sucessfully</p>';
                        header('Location: ReservationSummary.php');
                        $_SESSION['ReservationMessage'] = 'Your Event Reservation Is Done Successfully.';
                    } else {
                        echo '<p>failed to pay</p>';
                    }
                } else {
                    echo '<p> there are some errors </p>';
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
        <div class = "content" style="margin-bottom: 300px; margin-top: 100px;"">
            <div class = "container">
                <h1 style="margin-bottom: 40px;">Check Out</h1><!--form title-->
                <?php
                if (isset($errorMessage)) {
                    echo '<p>Error: ' . $errorMessage . '</p>';
                }
                ?>
                <hr>
                <div class="row" style="margin-left: 40px;">
                    <h3 style="color: darkblue; font-weight: bold;">Event Details</h3>
                    <div class="form-group col-md-2" style="margin-left: 40px;">
                        <label style="font-weight: bold; font-size: medium;" class="control-label"><strong>From Date: </strong></label>
                        <h4><?php echo $_SESSION['from_date']; ?></h4>
                    </div>
                    <div class="form-group col-md-2" style="margin-left: 20px;">
                        <label style="font-weight: bold; font-size: medium;" class="control-label"><strong>To Date: </strong></label>
                        <h4><?php echo $_SESSION['to_date']; ?></h4>
                    </div>

                    <div class="form-group col-md-2" style="margin-left: 20px;">
                        <label style="font-weight: bold; font-size: medium;" class="control-label"><strong>Attendees: </strong></label>
                        <h4><?php echo $_SESSION['attendees']; ?></h4>
                    </div>

                    <div class="form-group col-md-2" style="margin-left: 20px;">
                        <label style="font-weight: bold; font-size: medium;" class="control-label"><strong>Hall ID: </strong></label>
                        <h4><?php echo $_SESSION['locationId']; ?></h4>
                    </div>

                    <div class="form-group col-md-2" style="margin-left: 20px;">
                        <label style="font-weight: bold; font-size: medium;" class="control-label"><strong>Service: </strong></label>
                        <h4><?php echo $_SESSION['ServiceItemName']; ?></h4>
                    </div>
                </div>
                <!--Client Details-->
                <div class="row" style="margin-left: 40px;">
                    <h3 style="color: darkblue; font-weight: bold;">Client Details</h3>
                    <div class="form-group col-md-2" style="margin-left: 40px;">
                        <label style="font-weight: bold; font-size: medium;" class="control-label"><strong>Name: </strong></label>
                        <h4><?php echo $_SESSION['ClientName']; ?></h4>
                    </div>
                    <div class="form-group col-md-4" style="margin-left: 20px;">
                        <label style="font-weight: bold; font-size: medium;" class="control-label"><strong>Email: </strong></label>
                        <h4><?php echo $_SESSION['ClientEmail']; ?></h4>
                    </div>
                    <div class="form-group col-md-2" style="margin-left: 40px;">
                        <label style="font-weight: bold; font-size: medium;" class="control-label"><strong>Address: </strong></label>
                        <h4><?php echo $_SESSION['ClientAddress']; ?></h4>
                    </div>
                    <div class="form-group col-md-2" style="margin-left: 40px;">
                        <label style="font-weight: bold; font-size: medium;" class="control-label"><strong>Phone: </strong></label>
                        <h4><?php echo $_SESSION['ClientPhone']; ?></h4>
                    </div>
                </div>
                <hr>

                <h1>Payment Form</h1>
                <form action="PaymentForm.php" method="Post" class="form-horizontal">

                    <div class="form-group">
                        <label for="cardNumber" class="col-md-2 control-label">Card Number</label><!--event title-->
                        <div class="col-md-5">
                            <input type="text" 
                                   class="form-control" 
                                   id="cardNumber" 
                                   name="cardNumber" 
                                   value="<?php if (isset($_POST['cardNumber'])) echo $_POST['cardNumber']; ?>" /><!--input-->
                        </div>
                        <div class="col-md-4">
                            <span id="cardNumberError" class="error"><!--error message for invalid input-->
                                <?php echoValue($errors, 'cardNumber'); ?>
                            </span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="cardHolder" class="col-md-2 control-label">Card Holder</label><!--event description-->
                        <div class="col-md-5">
                            <input type="text" 
                                   class="form-control" 
                                   id="cardHolder" 
                                   name="cardHolder" 
                                   value="<?php if (isset($_POST['cardHolder'])) echo $_POST['cardHolder']; ?>" /><!--input-->
                        </div>
                        <div class="col-md-4">
                            <span id="cardHolderError" class="error"><!--error message for invalid input-->
                                <?php echoValue($errors, 'cardHolder'); ?>
                            </span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="expireDate" class="col-md-2 control-label">Expire Date</label><!--start date-->
                        <div class="col-md-5">
                            <input type="text" 
                                   class="form-control" 
                                   id="expireDate" 
                                   name="expireDate" 
                                   value="<?php if (isset($_POST['expireDate'])) echo $_POST['expireDate']; ?>" /><!--input-->
                        </div>
                        <div class="col-md-4">
                            <span id="expireDateError" class="error"><!--error message for invalid input-->
                                <?php echoValue($errors, 'expireDate'); ?>
                            </span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="pin" class="col-md-2 control-label"> PIN </label><!--start date-->
                        <div class="col-md-5">
                            <input type="password" 
                                   class="form-control" 
                                   id="pin" 
                                   name="pin" 
                                   value="<?php if (isset($_POST['pin'])) echo $_POST['pin']; ?>" /><!--input-->
                        </div>
                        <div class="col-md-4">
                            <span id="pinError" class="error"><!--error message for invalid input-->
                                <?php echoValue($errors, 'pin'); ?>
                            </span>
                        </div>
                    </div>
                    <div>
                        <button type="submit" name="submit" class = "btn btn-default pull-right">Confirm Booking <span class="glyphicon glyphicon-send"></span></button>
                        <input type="hidden" name="submitted" value="1"/>
                        <a class="btn btn-default navbar-btn" href="index.php"><span class="glyphicon glyphicon-circle-arrow-left"></span> Cancel</a>
                    </div>
                </form>
            </div>
        </div>
        <?php require 'utils/footer.php'; ?><!--footer content. file found in utils folder-->
    </body>
</html>
