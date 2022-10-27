<?php
include 'debugging.php';
require_once 'utils/functions.php';
require_once 'classes/Users.php';
require_once 'classes/Employee.php';
require_once 'classes/Database.php';

start_session();

if (isset($_POST['submitted'])) {
    try {
        $errors = array();
        if (empty($_POST['username'])) {
            $errors['username'] = "Username required";
        }
        if (empty($_POST['password'])) {
            $errors['password'] = "Password required";
        }
        if (empty($_POST['cpassword'])) {
            $errors['cpassword'] = "Confirm password required";
        }
        if (empty($_POST['role'])) {
            $errors['role'] = "Role required";
        }
        if (!empty($_POST['password']) && !empty($_POST['cpassword']) && $_POST['password'] != $_POST['cpassword']) {
            $errors['password'] = "Passwords must match";
        }

        if (empty($errors)) {
            $username = trim($_POST['username']);
            $password = trim($_POST['password']);
            $cpassword = trim($_POST['cpassword']);
            $role = trim($_POST['role']);

            //Set New User Properties
            $user = new Users();
            $user->setUsername($username);
            $user->setPassword($password);
            $user->setRole($role);

            //If the user selected Admin role they should enter the employee Id to profe that they are an employee
            $usersList = new Employee();
            $employeeIds = $usersList->getAllEmployees();
            $found = false;

            if (strcmp($role, 'Customer (Normal User)') == 0) {
                $employeeId = 'NULL';
                $user->setEmployeeId($employeeId);
                $found = true;
            } else {
                $employeeId = trim($_POST['employeeId']);
                for ($i; $i < count($employeeIds); $i++) {
                    if ($employeeIds[$i]->id == $employeeId) {
                        $user->setEmployeeId($employeeId);
                        $found = true;
                        break;
                    }
                }
            }

            if ($user->initWithUsername()) {
                if ($user->registerUser())
                    echo 'Registerd Successfully';
                else
                    echo '<p class="error"> Not Successfull </p>';
            } else {
                echo '<p class="error"> Username Already Exists </p>';
            }

            if ($user != null) {
                $errors['username'] = "Username already registered";
            }
            if ($found == false) {
                echo "<h3>You have entered incorrect EmployeeId</h3>";
                $errors ['employeeId'] = "You should enter a valid EmployeeId";
            }
        }
        if (!empty($errors)) {
            throw new Exception("There were errors. Please fix them.");
        }
//        echo '<h2>User Registered</h2>';
        //header('Location: index.php');
    } catch (Exception $ex) {
        $errorMessage = $ex->getMessage();
    }
}
?>
<!--End PHP Part-->

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
        <?php require 'utils/styles.php'; ?><!--css links. file found in utils folder-->
        <?php require 'utils/scripts.php'; ?><!--js links. file found in utils folder-->
    </head>
    <body>
        <?php require 'utils/header.php'; ?><!--header content. file found in utils folder-->
        <div class ="content"><!--body content holder-->
            <div class = "container">
                <div class="registrationHeader" style="margin-bottom: 30px;">
                    <h2>Registration Form</h2>
                    <h4 style="padding-left: 30px;">Please Fill in The Following Form To Create An Account</h4>
                </div>
                <div class ="col-md-6 col-md-offset-3">
                    <form action="register_form.php" class ="form-group" method="post">
                        <div class="form-group">
                            <label for="username">Username<span style="color:red"> * </span>: </label>
                            <input type="text"
                                   id="username"
                                   name="username"
                                   class="form-control"
                                   value="<?php if (isset($username)) echo $username; ?>"
                                   >
                            <span class="error">
                                <?php if (isset($errors['username'])) echo $errors['username']; ?>
                            </span>
                        </div>
                        <div class="form-group">
                            <label for="password">Password<span style="color:red"> * </span>: </label>
                            <input type="password"
                                   id="password"
                                   name="password"
                                   class="form-control"
                                   value=""
                                   >
                            <span class="error">
                                <?php if (isset($errors['password'])) echo $errors['password']; ?>
                            </span>
                        </div>
                        <div class="form-group">
                            <label for="cpassword">Confirm Password<span style="color:red"> * </span>: </label>
                            <input type="password"
                                   id="cpassword"
                                   name="cpassword"
                                   class="form-control"
                                   value=""
                                   >
                            <span class="error">
                                <?php if (isset($errors['cpassword'])) echo $errors['cpassword']; ?>
                            </span>
                        </div>
                        <div class="form-group">
                            <label for="role">Role <span style="color:red"> * </span>: </label>
                            <select 
                                id="role"
                                name="role"
                                class="form-control"
                                value="">
                                <option>Admin</option>
                                <option>Customer (Normal User)</option>
                            </select>
                            <span class="error">
                                <?php if (isset($errors['role'])) echo $errors['role']; ?>
                            </span>
                        </div>
                        <div class="form-group">
                            <label for="role">Employee ID (For Employees Only): </label>
                            <input type="number"
                                   id="employeeId"
                                   name="employeeId"
                                   class="form-control"
                                   value=""
                                   >
                            <span class="error">
                                <?php if (isset($errors['employeeId'])) echo $errors['employeeId']; ?>
                            </span>
                        </div>
                        <button type="submit" name ="btnSubmit" class = "btn btn-default">Register</button>
                        <input type="hidden" name="submitted" value="1" />
                    </form>
                </div>
            </div>
        </div>
        <?php require 'utils/footer.php'; ?>
    </body>
</html>
