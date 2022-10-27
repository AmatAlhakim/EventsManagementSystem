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
        <title>Manage Employees</title>
        <?php require 'utils/styles.php'; ?>
        <?php require 'utils/scripts.php'; ?>
        <?php require './debugging.php'; ?>
    </head>
    <body>
        <?php
        require 'utils/header.php';
        ?>
        <div class = "content">
            <div class = "container">
                <?php
                if (isset($message)) {
                    echo '<p>' . $message . '</p>';
                }
                $employees = new Employee();
                $row = $employees->getAllEmployees();
                if (empty($row))
                    echo '<h2 style="color: darkblue;"> Employees Records Are Empty</h2>';
                ?>
                <br><br>
                <a class="btn btn-lg btn-primary pull-right" href = "addEmployee.php">Add Employee</a><!--register button-->
                <a class="btn btn-info navbar-btn pull-left" href="dashboard.php"><span class="glyphicon glyphicon-circle-arrow-left"></span> Back To Dashboard</a>
                <br><br><br><br><br><br>

                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Employee ID</th>
                            <th>Name</th>
                            <th>Email</th>                    
                            <th>Password</th>
                            <th>Phone No</th>
                            <th>Address</th>
                            <th>Department ID</th>
                            <th>Enrollment Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        for ($i = 0; $i < count($row); $i++) {
                            echo '<tr>';
                            echo '<td>' . $row[$i]->id . '</td>';
                            echo '<td>' . $row[$i]->name . '</td>';
                            echo '<td>' . $row[$i]->email . '</td>';
                            echo '<td>' . $row[$i]->password . '</td>';
                            echo '<td>' . $row[$i]->phone . '</td>';
                            echo '<td>' . $row[$i]->address . '</td>';
                            $dept = new Department();
                            $dept->initWithDeptId($row[$i]->departmentId);
                            $deptName = $dept->getDepartmentName();
                            echo '<td>' . $deptName . '</td>';
                            echo '<td>' . $row[$i]->enrollmentDate . '</td>';
                            echo '<td>'
                            . '<a href="editEmployee.php?id=' . $row[$i]->id . '">Edit | </a> '
                            . '<a href="deleteEmployee.php?id=' . $row[$i]->id . '">Delete</a> '
                            . '</td>';
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
                <br><br>
                <a class="btn btn-info navbar-btn pull-left" href="dashboard.php"><span class="glyphicon glyphicon-circle-arrow-left"></span> Back To Dashboard</a>
            </div>
        </div>
        <?php require 'utils/footer.php'; ?>
    </body>
</html>