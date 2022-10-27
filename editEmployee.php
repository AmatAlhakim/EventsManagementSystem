<?php
require_once 'utils/functions.php';
require_once 'classes/Users.php';
require_once 'classes/Employee.php';
require_once 'classes/Department.php';
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
        <title>Edit Employee</title>
        <?php require 'utils/styles.php'; ?><!--css links. file found in utils folder-->
        <?php require 'utils/scripts.php'; ?><!--js links. file found in utils folder-->
        <?php require './debugging.php'; ?>
    </head>
    <body>
        <!--Begin main php section-->
        <?php
        require_once 'utils/functions.php';
        require_once 'classes/Employee.php';
        require_once 'classes/Department.php';

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
            $oldName = $employee->getName();
            //get new employee values
            $name = trim($_POST['Name']);
            $email = trim($_POST['Email']);
            $password = trim($_POST['Password']);
            $phone = trim($_POST['Phone']);
            $address = trim($_POST['Address']);
            $deparmentId = trim($_POST['DepartmentId']);
            $enrollmentDate = trim($_POST['EnrollmentDate']);

            $employee->setName($name);
            $employee->setEmail($email);
            $employee->setPassword($password);
            $employee->setPhone($phone);
            $employee->setAddress($address);
            $employee->setDepartmentId($deparmentId);
            $employee->setEnrollmentDate($enrollmentDate);

            //update the user 
            $employee->updateDB();
            
            header('Location: manageEmployees.php');
        } 
        ?>
        <!--End of main php section-->

        <?php require 'utils/header.php'; ?><!--header content. file found in utils folder-->
        <div class="content">
            <div class="container">
                <?php
                if (empty($employee))
                    echo '<h2 style="color: darkblue;"> Employees Records Are Empty</h2>';
                ?>

                <h1>Edit Employee Form </h1><!--form title-->
                <h3 style="padding-left: 30px;"><?php echo 'Employee Name: <b>' . $employee->getName() . '</b>'; ?></h3>
                <br><br>

                <form action="editEmployee.php" method="Post" class="form-horizontal">
                    <div class="form-group">
                        <label class="col-md-2 control-label">Name</label><!--label-->
                        <div class="col-md-5">
                            <input type="text" 
                                   class="form-control" 
                                   id="Name" 
                                   name="Name" 
                                   value="<?php echo $employee->getName(); ?>" /><!--input with current data from database-->
                        </div>
                        <div class="col-md-4">
                            <span id="NameError" class="error"></span><!--error message for invalid input-->
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Email</label><!--label-->
                        <div class="col-md-5">
                            <input type="text" 
                                   class="form-control" 
                                   id="Email" 
                                   name="Email" 
                                   value="<?php echo $employee->getEmail(); ?>" /><!--input with current data from database-->
                        </div> 
                        <div class="col-md-4">
                            <span id="EmailError" class="error"></span><!--error message for invalid input-->
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Password</label><!--label-->
                        <div class="col-md-5">
                            <input type="password" 
                                   class="form-control" 
                                   id="Password" 
                                   name="Password" 
                                   value="<?php echo $employee->getPassword(); ?>" /><!--input with current data from database-->
                        </div>
                        <div class="col-md-4">
                            <span id="PasswordError" class="error"></span><!--error message for invalid input-->
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Phone</label><!--label-->
                        <div class="col-md-5">
                            <input type="text" 
                                   class="form-control" 
                                   id="Phone" 
                                   name="Phone" 
                                   value="<?php echo $employee->getPhone(); ?>" /><!--input with current data from database-->
                        </div>
                        <div class="col-md-4">
                            <span id="PhoneError" class="error"></span><!--error message for invalid input-->
                        </div>
                    </div>

                    <div class="form-group">
                        <label  class="col-md-2 control-label">Address</label><!--label-->
                        <div class="col-md-5">
                            <input type="text" 
                                   class="form-control" 
                                   id="Address" 
                                   name="Address" 
                                   value="<?php echo $employee->getAddress(); ?>" /><!--input with current data from database-->
                        </div>
                        <div class="col-md-4">
                            <span id="LAddressError" class="error"></span><!--error message for invalid input-->
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="DepartmentId" class="col-md-2 control-label">Department Id</label><!--label-->
                        <div class="col-md-5">
                            <input type="text" 
                                   class="form-control" 
                                   id="DepartmentId" 
                                   name="DepartmentId" 
                                   value="<?php echo $employee->getDepartmentId(); ?>" /><!--input with current data from database-->
                        </div>
                        <div class="col-md-4">
                            <span id="DepartmentIdError" class="error"></span><!--error message for invalid input-->
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Enrollment Date</label><!--label-->
                        <div class="col-md-5">
                            <input type="date" 
                                   class="form-control" 
                                   id="EnrollmentDate" 
                                   name="EnrollmentDate" 
                                   value="<?php echo $employee->getEnrollmentDate(); ?>" /><!--input with current data from database-->
                        </div>
                        <div class="col-md-4">
                            <span id="EnrollmentDateError" class="error"></span><!--error message for invalid input-->
                        </div>
                    </div>
                    <br><br><br>
                    <button type="submit" class="btn btn-primary btn-lg pull-right" name="submit">Update <span class="glyphicon glyphicon-floppy-disk"></span></button>
                    <input type ="hidden" name="submitted" value="TRUE">
                    <input type ="hidden" name="id" value="<?php echo $id ?>"/>
                    <a class="btn btn-info btn-lg" href="manageEmployees.php"><span class="glyphicon glyphicon-circle-arrow-left"></span> Back</a><!--return/back button-->
                </form>
            </div>
        </div>
        <?php require 'utils/footer.php'; ?><!--footer content. file found in utils folder-->
    </body>
</html>
