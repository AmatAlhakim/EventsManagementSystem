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
        <title>User Edit Client</title>
        <?php require 'utils/styles.php'; ?><!--css links. file found in utils folder-->
        <?php require 'utils/scripts.php'; ?><!--js links. file found in utils folder-->
        <?php require './debugging.php'; ?>
    </head>
    <body>
        <!--Begin main php section-->
        <?php
        require_once './functions.php';
        require_once './classes/Client.php';

        $id = 0;
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
        } elseif (isset($_POST['id'])) {
            $id = $_POST['id'];
        } else {
            //die("Illegal request");
            echo '<p class="error">No Client id Parameter</p>';
            exit();
        }

        $client = new Client();
        $client->initWithId($id);

        if (isset($_POST['submitted'])) {
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
            if (empty($errors)) {
                $oldName = $client->getName();
                //get new employee values
                $name = trim($_POST['Name']);
                $email = trim($_POST['Email']);
                $password = trim($_POST['Password']);
                $phone = trim($_POST['Phone']);
                $address = trim($_POST['Address']);

                $client->setName($name);
                $client->setEmail($email);
                $client->setPassword($password);
                $client->setPhone($phone);
                $client->setAddress($address);

                //update the user 
                $client->updateDB();
                $_SESSION['UpdatingMessage'] = "Client Information Was Updated Successfully";
                header('Location: userManageReservation.php');
            }
        }

        if (!isset($errors)) {
            $errors = array();
        }
        ?>
        <!--End of main php section-->

        <?php require 'utils/header.php'; ?><!--header content. file found in utils folder-->
        <div class="content">
            <div class="container">
                <?php
                if (empty($client))
                    echo '<h2 style="color: darkblue;"> Client Record Is Empty</h2>';
                ?>

                <h1>Edit Client Form </h1><!--form title-->
                <h3 style="padding-left: 30px;"><?php echo 'Client Name: <b>' . $client->getName() . '</b>'; ?></h3>
                <br><br>

                <form action="userEditClient.php" method="Post" class="form-horizontal">
                    <div class="form-group">
                        <label class="col-md-2 control-label">Name</label><!--label-->
                        <div class="col-md-5">
                            <input type="text" 
                                   class="form-control" 
                                   id="Name" 
                                   name="Name" 
                                   value="<?php echo $client->getName(); ?>" /><!--input with current data from database-->
                        </div>
                        <div class="col-md-4">
                            <span id="NameError" class="error">
                                <?php echoValue($errors, 'Name') ?>
                            </span><!--error message for invalid input-->
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Email</label><!--label-->
                        <div class="col-md-5">
                            <input type="text" 
                                   class="form-control" 
                                   id="Email" 
                                   name="Email" 
                                   value="<?php echo $client->getEmail(); ?>" /><!--input with current data from database-->
                        </div> 
                        <div class="col-md-4">
                            <span id="EmailError" class="error">
                                <?php echoValue($errors, 'Email') ?>
                            </span><!--error message for invalid input-->
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Password</label><!--label-->
                        <div class="col-md-5">
                            <input type="password" 
                                   class="form-control" 
                                   id="Password" 
                                   name="Password" 
                                   value="<?php echo $client->getPassword(); ?>" /><!--input with current data from database-->
                        </div>
                        <div class="col-md-4">
                            <span id="PasswordError" class="error">
                                <?php echoValue($errors, 'Password') ?>
                            </span><!--error message for invalid input-->
                        </div>
                    </div>

                    <div class="form-group">
                        <label  class="col-md-2 control-label">Address</label><!--label-->
                        <div class="col-md-5">
                            <input type="text" 
                                   class="form-control" 
                                   id="Address" 
                                   name="Address" 
                                   value="<?php echo $client->getAddress(); ?>" /><!--input with current data from database-->
                        </div>
                        <div class="col-md-4">
                            <span id="LAddressError" class="error">
                                <?php echoValue($errors, 'LAddressError') ?>
                            </span><!--error message for invalid input-->
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Phone</label><!--label-->
                        <div class="col-md-5">
                            <input type="text" 
                                   class="form-control" 
                                   id="Phone" 
                                   name="Phone" 
                                   value="<?php echo $client->getPhone(); ?>" /><!--input with current data from database-->
                        </div>
                        <div class="col-md-4">
                            <span id="PhoneError" class="error">
                                <?php echoValue($errors, 'Phone') ?>
                            </span><!--error message for invalid input-->
                        </div>
                    </div>
                    <br><br>
                    <button type="submit" class="btn btn-lg btn-primary pull-right" name="submit">Update Client <span class="glyphicon glyphicon-floppy-disk"></span></button>
                    <input type ="hidden" name="submitted" value="TRUE">
                    <input type ="hidden" name="id" value="<?php echo $id ?>"/>
                    <a class="btn btn-lg btn-info" href="userManageReservation.php"><span class="glyphicon glyphicon-circle-arrow-left"></span> Back</a><!--return/back button-->
                </form>
            </div>
        </div>
        <?php require 'utils/footer.php'; ?><!--footer content. file found in utils folder-->
    </body>
</html>
