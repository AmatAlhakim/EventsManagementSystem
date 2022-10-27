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
        <title>Edit Service Item</title> 
        <?php require 'utils/styles.php'; ?><!--css links. file found in utils folder-->
        <?php require 'utils/scripts.php'; ?><!--js links. file found in utils folder-->
        <?php require './debugging.php'; ?>
    </head>
    <body>
        <?php
        require_once 'functions.php';
        require_once 'classes/ServiceItem.php';
        require 'classes/Services.php';

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

        $service = new ServiceItem();
        $service->initWithId($id);

        if (isset($_POST['submitted'])) {
            try {
           // Check Errors
                if (empty($_POST['Name'])) {
                    $errors['Name'] = "Service Name required";
                }
                if (empty($_POST['ServiceId'])) {
                    $errors['ServiceId'] = "Service Id required";
                }
                if (empty($_POST['Cost'])) {
                    $errors['Cost'] = "Cost required";
                }
                if (empty($errors)) { //if there are no errrors
            $oldName = $service->getName();
            //get the user input
            $name = trim($_POST['Name']);
            $serviceId= trim($_POST['ServiceId']);//trim($_POST['ServiceId']);
            $cost = trim($_POST['Cost']);

            //set the new employee properties
            $service->setName($name);
            $service->setServiceId($serviceId);
            $service->setCost($cost);

            $service->updateDB();
            echo $_SESSION['getMotherServiceId'];
            header('Location: manageServiceItems.php');
        } else {
            echo '<p> there are some errors </p>';
        }
    }
            catch (Exception $ex) {
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
                <h1 style="margin-bottom: 40px;">Edit Service Form</h1><!--form title-->
                <?php
                if (empty($service)) {
                    echo '<p>Error: Service Item Not Found </p>';
                }
                ?>
                <h3 style="padding-left: 30px;"><?php echo 'Service Name: <b>' . $service->getName() . '</b>'; ?></h3>
                <br><br>
                <form action="editServiceItem.php" method="Post" class="form-horizontal">
                    <div class="form-group">
                        <label for="Name" class="col-md-2 control-label">Name</label><!--event title-->
                        <div class="col-md-5">
                            <input type="text" 
                                   class="form-control" 
                                   id="Name" 
                                   name="Name" 
                                   value="<?php echo $service->getName(); ?>" /><!--input-->
                        </div>
                        <div class="col-md-4">
                            <span id="NameError" class="error"><!--error message for invalid input-->
                                <?php // echoValue($errors, 'Name');  ?>
                            </span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="ServiceId" class="col-md-2 control-label">Service Id</label><!--event serviceId-->
                        <div class="col-md-5">                            
                            <select 
                                class="form-control"
                                id="ServiceId" 
                                name="ServiceId"
                                value="">
                                <option disabled selected>Select Option</option>
                                <?php
                                $motherService = new Services();
                                $servicesList = $motherService->getAllServices();
                                for ($i = 0; $i < count($servicesList); $i++) {
                                    echo '<option>';
                                    echo $servicesList[$i]->id;
                                    $_SESSION['getMotherServiceId'] = $servicesList[$i]->id;
                                    echo '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <span id="ServiceIdError" class="error"><!--error message for invalid input-->
                                <?php // echoValue($errors, 'ServiceId');  ?>
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
                                   value="<?php echo $service->getCost(); ?>" /><!--input-->
                        </div>
                        <div class="col-md-4">
                            <span id="CostError" class="error"><!--error message for invalid input-->
                                <?php // echoValue($errors, 'Cost');  ?>
                            </span>
                        </div>
                    </div>
                    <br><br>

                    <button type="submit" name="submit" class="btn btn-lg btn-primary pull-right">Update Service <span class="glyphicon glyphicon-send"></span></button>
                    <input type="hidden" name="submitted" value="TRUE"/>
                    <input type ="hidden" name="id" value="<?php echo $id ?>"/>
                    <a class="btn btn-lg btn-info" href="manageServiceItems.php"><span class="glyphicon glyphicon-circle-arrow-left"></span> Back</a>
                </form>
            </div>
        </div>
        <?php require 'utils/footer.php'; ?><!--footer content. file found in utils folder-->
    </body>
</html>
