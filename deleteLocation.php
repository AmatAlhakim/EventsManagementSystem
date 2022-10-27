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
        <title>Delete Location</title> 
        <?php require 'utils/styles.php'; ?><!--css links. file found in utils folder-->
        <?php require 'utils/scripts.php'; ?><!--js links. file found in utils folder-->
        <?php require './debugging.php'; ?>
    </head>
    <body>
        <!--Begining of main php section-->
        <?php
        require_once 'utils/functions.php';
        require_once 'classes/Location.php';

        $id = 0;
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
        } elseif (isset($_POST['id'])) {
            $id = $_POST['id'];
        } else {
            echo '<p class="error">No Location id Parameter</p>';
            exit();
        }

        $location = new Location();
        $location->initWithId($id);

        if (isset($_POST['submitted'])) {
            if (isset($_POST['sure']) && ($_POST['sure'] == 'Yes')) { //delete the record   
                if ($location->deleteLocation())
                    header('Location: manageLocations.php');
            }//no confirmation
            else
                echo '<h3> Location ' . $location->getName() . '  deletion was not confirmed</h3>';
        }
        ?>
        <!--End of main php section-->
        <?php include './utils/header.php'; ?>
        <div class = "content" style="margin-top: 100px; margin-bottom: 300;">
            <div class = "container" >
                <h1>Confirm Location <b style="color:red">" <?php echo $location->getName(); ?> " </b> Deletion Process </h1><!--form title-->
                <form action="deleteLocation.php" method="Post" class="form-horizontal">
                    <h3>Delete This Location <b style="color:red">" <?php echo $location->getName(); ?> " </b>Record Permanently????</h3>
                    <br><br><br><br><br>
                    <div class="row" style="margin-left: 40px; font-size: x-large">
                        <div class="col-md-2">
                            <input type="radio" 
                                   class="form-check-input" 
                                   name="sure" 
                                   value="Yes" 
                                   checked="checked"/><label>Yes</label>
                        </div>
                        <div class="col-md-2">
                            <input type="radio" 
                                   class="form-check-input" 
                                   name="sure" 
                                   value="No" /> <label>No</label>
                        </div>
                    </div>
                    <br><br><br><br><br>
                    <input type="submit" class ="btn btn-lg btn-danger pull-left" name="submit" value="Delete Location" />
                    <input type ="hidden" name="submitted" value="1">
                    <input type ="hidden" name="id" value="'<?php echo $id ?>'"/>
                    <br><br><br>
                    <a class="btn btn-lg btn-info" href="manageLocations.php"><span class="glyphicon glyphicon-circle-arrow-left"></span> Back</a>
                </form>
            </div>
        </div>
        <?php require 'utils/footer.php'; ?><!--footer content. file found in utils folder-->
    </body>
</html>