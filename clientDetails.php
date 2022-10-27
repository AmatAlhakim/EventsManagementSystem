<!--Allow Registered Users Only-->
<?php
require_once 'utils/functions.php';
require_once 'classes/Users.php';
require_once 'classes/Search.php';

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
        <title>Add Client Form</title> 
        <?php require 'utils/styles.php'; ?><!--css links. file found in utils folder-->
        <?php require 'utils/scripts.php'; ?><!--js links. file found in utils folder-->
    </head>
    <body>

        <?php
        require_once './functions.php';
        require_once './classes/Event.php';
        require_once './classes/Client.php';
        include './debugging.php';

        //To get the service item id
        $id = 0;
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            if (!empty($id)) {
                $_SESSION['ServiceItemID'] = $id;
            }
        }

        $counter = 0;

        if (isset($_POST['submitted'])) {
            try {
                //Check Errors
                if (empty($_POST['Name'])) {
                    $errors['Name'] = "Employee Name required";
                }
                if (empty($_POST['Email'])) {
                    $errors['Email'] = "Email required";
                }
                if (empty($_POST['Password'])) {
                    $errors['Password'] = "Password required";
                }
                if (empty($_POST['Phone'])) {
                    $errors['Phone'] = "Phone required";
                }
                if (empty($_POST['Address'])) {
                    $errors['Address'] = "Address required";
                }
                if (empty($_POST['Title'])) {
                    $errors['Title'] = "Title required";
                }
                if (empty($_POST['Description'])) {
                    $errors['Description'] = "Description required";
                }

                if (empty($errors)) { //if there are no errrors
                    // get the event details
                    $title = trim($_POST['Title']);
                    $description = trim($_POST['Description']);
                    $event = new Event();
                    $event->setTitle($title);
                    $event->setDescription($description);
                    $_SESSION['eventDescription'] = $description;
                    $event->setEndDate($_SESSION['to_date']);
                    $event->setStartDate($_SESSION['from_date']);
                    $event->setCost($_SESSION['locationCost']);
                    $event->setLocationID($_SESSION['locationId']);
                    $event->setNumOfAudience($_SESSION['attendees']);
                    $event->setService($_SESSION['MotherServiceID']);
                    $event->setDuration($_SESSION['duration']);

//                    if ($event->initWithEventTitle($title)) {
//                        if ($event->registerEvent()) {
//                            echo "event was booked";
//                            $_SESSION['eventTitle'] = $title;
//                        }
//                    }

                    $_SESSION['event'] = $event;
                    $_SESSION['eventTitle'] = $title;

                    //get the user input
                    $name = trim($_POST['Name']);
                    $email = trim($_POST['Email']);
                    $password = trim($_POST['Password']);
                    $address = trim($_POST['Address']);
                    $phone = trim($_POST['Phone']);

                    //set the new employee properties
                    $client = new Client();

                    $client->setName($name);
                    $client->setEmail($email);
                    $client->setPassword($password);
                    $client->setAddress($address);
                    $client->setPhone($phone);

                    $royaltyPoint = 0;
                    $totalPoint = 0;

                    if (!$client->initWithClientName()) {
                        if ($counter === 0) {
//                            $royaltyPoint = $client->getRoyaltyPointForClientDetails();
//                            for ($i = 0; $i < count($royaltyPoint); $i++) {
//                                $totalPoint += $royaltyPoint[$i]->royaltyPoint;
//                            }
//                            $client->updateRoyaltyPoint($totalPoint);

                            //get the client id
                            $clientIdList = $client->GetIdForClientDetails();
                            $clientId = 0;
                            for ($i = 0; $i < count($clientIdList); $i++) {
                                $clientId = $clientIdList[$i]->id;
                            }

                           // $_SESSION['TotalRoyaltyPoint'] = $totalPoint;
                            $_SESSION['ClientID'] = $clientId;

                            $client->updateDB();

                            echo "<br>client already exists, update is done";
                            header('Location: PaymentForm.php');
                        }
                        $counter++;
                    } else {
                        $royaltyPoint++;
                        $client->setRoyaltyPoint($royaltyPoint);
                        if ($client->registerClient()) {

                            $clientIdList = $client->GetIdForClientDetails();
                            $clientId = 0;
                            for ($i = 0; $i < count($clientIdList); $i++) {
                                $clientId = $clientIdList[$i]->id;
                            }

                            $_SESSION['ClientID'] = $clientId;
                            $_SESSION['TotalRoyaltyPoint'] = $royaltyPoint;
                            echo '<p>registered</p>';

                            header('Location: PaymentForm.php');
                        } else {
                            echo '<p>failed to register</p>';
                        }
                    }
                    $_SESSION['ClientName'] = $name;
                    $_SESSION['ClientEmail'] = $email;
                    $_SESSION['ClientPhone'] = $phone;
                    $_SESSION['ClientAddress'] = $address;
                    //Create An Event Obj
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
        <div class = "content">
            <div class = "container">
                <h1>Register Client/Company Form</h1><!--form title-->
                <h3>Please Fill in The Form</h3>
                <hr/>
                <?php
                if (isset($errorMessage)) {
                    echo '<p>Error: ' . $errorMessage . '</p>';
                }
                ?>
                <form action="clientDetails.php" method="Post" class="form-horizontal">


                    <!--Event Details-->
                    
                    <div class="form-group">
                        <h2>Event Detail Form</h2>
                        <label for="Title" class="col-md-2 control-label">Event Title</label><!--event title-->
                        <div class="col-md-5">
                            <input type="text" 
                                   class="form-control" 
                                   id="Title" 
                                   name="Title" 
                                   value="<?php if (isset($_POST['Title'])) echo $_POST['Title']; ?>" /><!--input-->
                        </div>
                        <div class="col-md-4">
                            <span id="TitleError" class="error"><!--error message for invalid input-->
                                <?php echoValue($errors, 'Title'); ?>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="Description" class="col-md-2 control-label">Event Description</label><!--event description-->
                        <div class="col-md-5">
                            <input type="text" 
                                   class="form-control" 
                                   id="Description" 
                                   name="Description" 
                                   value="<?php if (isset($_POST['Description'])) echo $_POST['Description']; ?>" /><!--input-->
                        </div>
                        <div class="col-md-4">
                            <span id="DescriptionError" class="error"><!--error message for invalid input-->
                                <?php echoValue($errors, 'Description'); ?>
                            </span>
                        </div>
                    </div>
                    <!--End Event Details-->

                    <!--Client Details-->
                    <h2>Client Detail Form</h2>
                    <div class="form-group">
                        <label for="Name" class="col-md-2 control-label">Name</label><!--event title-->
                        <div class="col-md-5">
                            <input type="text" 
                                   class="form-control" 
                                   id="Name" 
                                   name="Name" 
                                   value="<?php if (isset($_POST['Name'])) echo $_POST['Name']; ?>" /><!--input-->
                        </div>
                        <div class="col-md-4">
                            <span id="NameError" class="error"><!--error message for invalid input-->
                                <?php echoValue($errors, 'Name'); ?>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="Email" class="col-md-2 control-label">Email</label><!--event description-->
                        <div class="col-md-5">
                            <input type="text" 
                                   class="form-control" 
                                   id="Email" 
                                   name="Email" 
                                   value="<?php if (isset($_POST['Email'])) echo $_POST['Email']; ?>" /><!--input-->
                        </div>
                        <div class="col-md-4">
                            <span id="EmailError" class="error"><!--error message for invalid input-->
                                <?php echoValue($errors, 'Email'); ?>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="Password" class="col-md-2 control-label">Password</label><!--start date-->
                        <div class="col-md-5">
                            <input type="password" 
                                   class="form-control" 
                                   id="Password" 
                                   name="Password" 
                                   value="<?php if (isset($_POST['Password'])) echo $_POST['Password']; ?>" /><!--input-->
                        </div>
                        <div class="col-md-4">
                            <span id="PasswordError" class="error"><!--error message for invalid input-->
                                <?php echoValue($errors, 'Password'); ?>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="Address" class="col-md-2 control-label">Address</label><!--cost-->
                        <div class="col-md-5">
                            <input type="text" 
                                   class="form-control" 
                                   id="Address" 
                                   name="Address" 
                                   value="<?php if (isset($_POST['Address'])) echo $_POST['Address']; ?>" /><!--input-->
                        </div>
                        <div class="col-md-4">
                            <span id="AddressError" class="error"><!--error message for invalid input-->
                                <?php echoValue($errors, 'Address'); ?>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="Phone" class="col-md-2 control-label">Phone</label><!--end date-->
                        <div class="col-md-5">
                            <input type="text" 
                                   class="form-control" 
                                   id="Phone" 
                                   name="Phone" 
                                   value="<?php if (isset($_POST['Phone'])) echo $_POST['Phone']; ?>" /><!--input-->
                        </div>
                        <div class="col-md-4">
                            <span id="PhoneError" class="error"><!--error message for invalid input-->
                                <?php echoValue($errors, 'Phone'); ?>
                            </span>
                        </div>
                    </div>

                    <!--End Client Details-->
                    <button type="submit" name="submit" class = "btn btn-default pull-right">Check Out <span class="glyphicon glyphicon-send"></span></button>
                    <input type="hidden" name="submitted" value="1"/>
                    <a class="btn btn-default navbar-btn" href="index.php"><span class="glyphicon glyphicon-circle-arrow-left"></span> Cancel</a>
                </form>
            </div>
        </div>
        <?php require 'utils/footer.php'; ?><!--footer content. file found in utils folder-->
    </body>
</html>

