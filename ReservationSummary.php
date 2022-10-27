
<script type="text/javascript">
    function PrintDiv() {
        var divToPrint = document.getElementById('divToPrint');
        var popupWin = window.open('', '_blank', 'width=800,height=800');
        popupWin.document.open();
        popupWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</html>');
        popupWin.document.close();
    }
</script>

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
        <title>Reservation Summary</title> 
        <?php require 'utils/styles.php'; ?><!--css links. file found in utils folder-->
        <?php require 'utils/scripts.php'; ?><!--js links. file found in utils folder-->
        <?php require './debugging.php'; ?>
        <link rel="stylesheet" media="print" href="print.css" />
    </head>
    <body>
        <?php require 'utils/header.php'; ?><!--header content. file found in utils folder-->
        <div class = "content" style="margin-bottom: 300px; margin-top: 100px;" >
            <div class = "container">
                <h1 style="margin-bottom: 40px; font-weight: bolder; color: #005f81">Event Reservation Summary</h1><!--form title-->
                <div id="divToPrint">
                    <?php
                    if (isset($errorMessage)) {
                        echo '<p>Error: ' . $errorMessage . '</p>';
                    }
                    ?>
                    <hr>

                    <div class="alert alert-info text-center">
                        <h3><?php if (!empty($_SESSION['ReservationMessage'])) echo $_SESSION['ReservationMessage']; ?></h3>
                    </div>

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
                    <hr>
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
                    <div class="row" style="margin-left: 40px;">
                        <h3 style="color: darkblue; font-weight: bold;">Detail Of Reservation No: <span style="color: darkcyan; font-weight: bolder;"> <?php echo $_SESSION['bookedEventID'];?></span></h3>
                        <div class="form-group col-md-3" >
                            <label style="font-weight: bold; font-size: medium;" class="control-label"><strong>Payment Status: </strong></label>
                            <h4>Paid</h4>
                        </div>
                        <div class="form-group col-md-3" >
                            <label style="font-weight: bold; font-size: medium;" class="control-label"><strong>Reservation Status: </strong></label>
                            <h4>Confirmed</h4>
                        </div>
                        <div class="form-group col-md-3" >
                            <label style="font-weight: bold; font-size: medium;" class="control-label"><strong>Reservation Total Cost: </strong></label>
                            <h4><?php echo $_SESSION['totalReservationCost'] ?></h4>
                        </div>
                        <div class="form-group col-md-3">
                            <label style="font-weight: bold; font-size: medium;" class="control-label"><strong>Reservation Date: </strong></label>
                            <h4><?php echo $_SESSION['bookDate'] ?></h4>
                        </div>
                    </div>
                </div>
                <div style="margin-top: 50px;" class="align align-content-between">
                    <div>
                        <a class="btn btn-default pull-left" href="index.php"><span class="glyphicon glyphicon-circle-arrow-left"></span> Finish</a>
                        <input class="btn btn-primary pull-right" type="button" value="Print Reservation Summary" onclick="PrintDiv();" />
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>