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
        <meta charset="UTF-8">
        <title>Add Employees</title> 
        <?php require 'utils/styles.php'; ?><!--css links. file found in utils folder-->
        <?php require 'utils/scripts.php'; ?><!--js links. file found in utils folder-->
    </head>
    <body>

        <?php
        require_once 'functions.php';
        require_once 'classes/Employee.php';
        include './debugging.php';

        if (isset($_POST['submitted'])) {
            try {
                //Check Errors
                // $errors = array();
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
                if (empty($_POST['DepartmentId'])) {
                    $errors['DepartmentId'] = "DepartmentId required";
                }
                if (empty($_POST['EnrollmentDate'])) {
                    $errors['EnrollmentDate'] = "EnrollmentDate required";
                }

                if (empty($errors)) { //if there are no errrors
                    //get the user input
                    $name = trim($_POST['Name']);
                    $email = trim($_POST['Email']);
                    $password = trim($_POST['Password']);
                    $phone = trim($_POST['Phone']);
                    $address = trim($_POST['Address']);
                    $departmentId = trim($_POST['DepartmentId']);
                    $enrollmentDate = trim($_POST['EnrollmentDate']);

                    //set the new employee properties
                    $employee = new Employee();
                    $employee->setName($name);
                    $employee->setEmail($email);
                    $employee->setPassword($password);
                    $employee->setPhone($phone);
                    $employee->setAddress($address);
                    $employee->setDepartmentId($departmentId);
                    $employee->setEnrollmentDate($enrollmentDate);

                    //check if the employee exists
                    if ($employee->registerEmployee()) {
                        echo '<p>registered</p>';
                        header('Location: manageEmployees.php');
                    } else {
                        echo '<p>failed to register</p>';
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
                <h1>Add Employee Form</h1><!--form title-->
                <?php
                if (isset($errorMessage)) {
                    echo '<p>Error: ' . $errorMessage . '</p>';
                }
                ?>
                <form action="addEmployee.php" method="Post" class="form-horizontal">
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
                        <label for="DepartmentId" class="col-md-2 control-label">Department Id</label><!--location id-->
                        <div class="col-md-5">
                            <input type="text" 
                                   class="form-control" 
                                   id="Address" 
                                   name="DepartmentId" 
                                   value="<?php if (isset($_POST['DepartmentId'])) echo $_POST['DepartmentId']; ?>" /><!--input-->
                        </div>
                        <div class="col-md-4">
                            <span id="DepartmentIdError" class="error"><!--error message for invalid input-->
                                <?php echoValue($errors, 'DepartmentId'); ?>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="EnrollmentDate" class="col-md-2 control-label">enrollment Date</label><!--location id-->
                        <div class="col-md-5">
                            <input type="text" 
                                   class="form-control" 
                                   id="enrollmentDate" 
                                   name="EnrollmentDate" 
                                   value="<?php if (isset($_POST['EnrollmentDate'])) echo $_POST['EnrollmentDate']; ?>" /><!--input-->
                        </div>
                        <div class="col-md-4">
                            <span id="EnrollmentDateError" class="error"><!--error message for invalid input-->
                                <?php echoValue($errors, 'EnrollmentDate'); ?>
                            </span>
                        </div>
                    </div>
                    <button type="submit" name="submit" class = "btn btn-default pull-right">Add Employee <span class="glyphicon glyphicon-send"></span></button>
                    <input type="hidden" name="submitted" value="1"/>
                    <a class="btn btn-default navbar-btn" href="manageEmployees.php"><span class="glyphicon glyphicon-circle-arrow-left"></span> Back</a>
                </form>
            </div>
        </div>
        <?php require 'utils/footer.php'; ?><!--footer content. file found in utils folder-->
    </body>
</html>


