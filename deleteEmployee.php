<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Delete Employee</title> 
        <?php require 'utils/styles.php'; ?><!--css links. file found in utils folder-->
        <?php require 'utils/scripts.php'; ?><!--js links. file found in utils folder-->
        <?php require './debugging.php'; ?>
    </head>
    <body>
        <!--Begining of main php section-->
        <?php
        require_once 'utils/functions.php';
        require_once 'classes/Employee.php';

        $id = 0;
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
        } elseif (isset($_POST['id'])) {
            $id = $_POST['id'];
        } else {
            //die("Illegal request");
            echo '<p class="error">No User id Parameter</p>';
            exit();
        }

        $employee = new Employee();
        $employee->initWithId($id);

        if (isset($_POST['submitted'])) {
            if (isset($_POST['sure']) && ($_POST['sure'] == 'Yes')) { //delete the record   
                if ($employee->deleteEmployee())
                    header('Location: manageEmployees.php');
            }//no confirmation
            else
                echo '<h3> User ' . $employee->getName() . '  deletion was not confirmed</h3>';
        }
        ?>
        <!--End of main php section-->
        <?php include './utils/header.php'; ?>
        <div class = "content">
            <div class = "container" style="margin-bottom:200px; margin-top: 50px;">
                <h1>Confirm Employee <b style="color:red">" <?php echo $employee->getName(); ?> " </b> Deletion Process </h1><!--form title-->
                <form action="deleteEmployee.php" method="Post" class="form-horizontal">
                    <h3>Delete This Employee <b style="color:red">" <?php echo $employee->getName(); ?> " </b>Record Permanently????</h3>
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
                    <input type="submit" class ="btn btn-danger pull-left btn-lg" name="submit" value="Delete" />
                    <input type ="hidden" name="submitted" value="1">
                    <input type ="hidden" name="id" value="'<?php echo $id ?>'"/>
                    <br><br><br>
                    <a class="btn btn-lg btn-info" href="manageEmployees.php"><span class="glyphicon glyphicon-circle-arrow-left"></span> Cancel</a><!--return/back button-->
                </form>
            </div>
        </div>
        <?php require 'utils/footer.php'; ?><!--footer content. file found in utils folder-->
    </body>
</html>

