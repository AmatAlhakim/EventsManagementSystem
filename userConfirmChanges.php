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
        require_once 'classes/Payment.php';

        $totalPayment = 0.0;
        $rate = 0.05;
        $reservationCost = 0.0;
        //calculate the discount
        $reservationCost = $_SESSION['ReservationCost'];
        $totalPayment = $rate * $reservationCost;
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

                if (empty($errors)) {

                    $_SESSION['userId'] = $userId;

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
                    $payment->setBookedEventID($_SESSION['ReservationID']);

                    //check if the employee exists
                    if ($payment->registerPayment()) {
                        echo '<p>paid sucessfully</p>';
                        header('Location: userManageReservation.php');
                        $_SESSION['UpdatingMessage'] = 'Your Reservation Was Updated Successfully.';
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
                echo '<h3>Total Charge: ' . $totalPayment . '</h3>';
                ?>
                <hr>
                <h1>Payment Form</h1>
                <form action="userConfirmChanges.php" method="Post" class="form-horizontal">

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
                        <button type="submit" name="submit" class = "btn btn-default pull-right">Confirm Payment <span class="glyphicon glyphicon-send"></span></button>
                        <input type="hidden" name="submitted" value="1"/>
                        <a class="btn btn-default navbar-btn" href="userManageReservation.php"><span class="glyphicon glyphicon-circle-arrow-left"></span> Cancel</a>
                    </div>
                </form>
            </div>
        </div>
        <?php require 'utils/footer.php'; ?><!--footer content. file found in utils folder-->
    </body>
</html>

