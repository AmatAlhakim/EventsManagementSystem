<?php
require_once 'utils/functions.php';
require_once 'classes/Users.php';
require_once 'classes/Client.php';

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
        <title>Add Client</title> 
        <?php require 'utils/styles.php'; ?><!--css links. file found in utils folder-->
        <?php require 'utils/scripts.php'; ?><!--js links. file found in utils folder-->
        <?php require './debugging.php'; ?>
    </head>
    <body>

        <?php
        require_once 'functions.php';
        require_once 'classes/Client.php';

        if (isset($_POST['submitted'])) {
            try {
                //Check Errors
                if (empty($_POST['Name'])) {
                    $errors['Name'] = "Client Name required";
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
                if (empty($_POST['RoyaltyPoint'])) {
                    $errors['RoyaltyPoint'] = "RoyaltyPoint required";
                }

                if (empty($errors)) { //if there are no errrors
                    //get the user input
                    $name = trim($_POST['Name']);
                    $email = trim($_POST['Email']);
                    $password = trim($_POST['Password']);
                    $address = trim($_POST['Address']);
                    $phone = trim($_POST['Phone']);
                    $royaltyPoint = trim($_POST['RoyaltyPoint']);

                    //set the new employee properties
                    $client = new Client();
                    $client->setName($name);
                    $client->setEmail($email);
                    $client->setPassword($password);
                    $client->setAddress($address);
                    $client->setPhone($phone);
                    $client->setRoyaltyPoint($royaltyPoint);

                    if ($client->initWithClientName($name)) {
                        //check if the employee exists
                        if ($client->registerClient()) {
                            echo '<p>registered</p>';
                            header('Location: manageClients.php');
                        } else {
                            echo '<p>failed to register</p>';
                        }
                    }else{
                        echo '<h3> Client Name Already Exists!!!</h3>';
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
        <div class = "content">
            <div class = "container">
                <h1>Add Client Form</h1><!--form title-->
                <?php
                if (isset($errorMessage)) {
                    echo '<p>Error: ' . $errorMessage . '</p>';
                }
                ?>
                <form action="addClient.php" method="Post" class="form-horizontal">
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

                    <div class="form-group">
                        <label for="DepartmentId" class="col-md-2 control-label">Royalty Point</label><!--location id-->
                        <div class="col-md-5">
                            <input type="text" 
                                   class="form-control" 
                                   id="Address" 
                                   name="RoyaltyPoint" 
                                   value="<?php if (isset($_POST['RoyaltyPoint'])) echo $_POST['RoyaltyPoint']; ?>" /><!--input-->
                        </div>
                        <div class="col-md-4">
                            <span id="RoyaltyPointError" class="error"><!--error message for invalid input-->
                                <?php echoValue($errors, 'RoyaltyPoint'); ?>
                            </span>
                        </div>
                    </div>
                    <br><br><br>
                    <button type="submit" name="submit" class = "btn btn-primary btn-lg pull-right">Add Client <span class="glyphicon glyphicon-send"></span></button>
                    <input type="hidden" name="submitted" value="1"/>
                    <a class="btn btn-info btn-lg pull-left navbar-btn" href="manageClients.php"><span class="glyphicon glyphicon-circle-arrow-left"></span> Back</a>
                </form>
            </div>
        </div>
        <?php require 'utils/footer.php'; ?><!--footer content. file found in utils folder-->
    </body>
</html>

