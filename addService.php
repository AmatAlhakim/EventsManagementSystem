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
        <title>Add Service</title> 
        <?php require 'utils/styles.php'; ?><!--css links. file found in utils folder-->
        <?php require 'utils/scripts.php'; ?><!--js links. file found in utils folder-->
        <?php require './debugging.php'; ?>
    </head>
    <body>

        <?php
        require_once 'functions.php';
        require_once 'classes/Services.php';

        if (isset($_POST['submitted'])) {
            try {
                //Check Errors
                if (empty($_POST['Name'])) {
                    $errors['Name'] = "Employee Name required";
                }
                if (empty($_POST['Description'])) {
                    $errors['Description'] = "Description required";
                }
                if (empty($_POST['Cost'])) {
                    $errors['Cost'] = "Cost required";
                }
                if (empty($errors)) { //if there are no errrors
                    //get the user input
                    $name = trim($_POST['Name']);
                    $description= trim($_POST['Description']);
                    $cost = trim($_POST['Cost']);

                    //set the new employee properties
                    $service= new Services();
                    $service->setName($name);
                    $service->setDescription($description);
                    $service->setCost($cost);

                    if ($service->initWithServiceName($name)) {
                        //check if the employee exists
                        if ($service->registerService()) {
                            echo '<p>registered</p>';
                            header('Location: manageServices.php');
                        } else {
                            echo '<p>failed to register</p>';
                        }
                    }else{
                        echo '<h3> Service Name Already Exists!!!</h3>';
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
                <h1 style="margin-bottom: 40px;">Add Service Form</h1><!--form title-->
                <?php
                if (isset($errorMessage)) {
                    echo '<p>Error: ' . $errorMessage . '</p>';
                }
                ?>
                <br>
                <form action="addService.php" method="Post" class="form-horizontal">
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
                        <label for="Description" class="col-md-2 control-label">Description</label><!--event description-->
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
                    
                    <div class="form-group" style="margin-bottom: 50px;">
                        <label for="Cost" class="col-md-2 control-label">Cost</label><!--start date-->
                        <div class="col-md-5">
                            <input type="text" 
                                   class="form-control" 
                                   id="Cost" 
                                   name="Cost" 
                                   value="<?php if (isset($_POST['Cost'])) echo $_POST['Cost']; ?>" /><!--input-->
                        </div>
                        <div class="col-md-4">
                            <span id="CostError" class="error"><!--error message for invalid input-->
                                <?php echoValue($errors, 'Cost'); ?>
                            </span>
                        </div>
                    </div>
                    <br><br><br>
                    <button type="submit" name="submit" class = "btn btn-primary btn-lg pull-right">Add Service <span class="glyphicon glyphicon-send"></span></button>
                    <input type="hidden" name="submitted" value="1"/>
                    <a class="btn btn-info btn-lg pull-left navbar-btn" href="manageServices.php"><span class="glyphicon glyphicon-circle-arrow-left"></span> Back</a>
                </form>
            </div>
        </div>
        <?php require 'utils/footer.php'; ?><!--footer content. file found in utils folder-->
    </body>
</html>