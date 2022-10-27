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
        <title>Manage Services</title>
        <?php require 'utils/styles.php'; ?>
        <?php require 'utils/scripts.php'; ?>
        <?php require './debugging.php'; ?>
    </head>
    <body>
        <?php
        require 'utils/header.php';
        ?>
        <div class = "content" style="margin-bottom: 400px;">
            <div class = "container">
                <?php
                require_once 'classes/ServiceItem.php';
                require_once 'classes/Services.php';
                if (isset($message)) {
                    echo '<p>' . $message . '</p>';
                }
                $service = new ServiceItem();
                $row = $service->getAllServiceItems();
                if (empty($row))
                    echo '<h2 style="color: darkblue;"> Service Records Are Empty</h2>';
                ?>
                <br>
                <a class="btn btn-lg btn-primary pull-right" href = "addServiceItem.php">Add Service Item</a><!--register button-->
                <a class="btn btn-info navbar-btn pull-left" href="dashboard.php"><span class="glyphicon glyphicon-circle-arrow-left"></span> Back To Dashboard</a>
                <br><br><br><br><br><br>

                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Service Item ID</th>
                            <th>Name</th>
                            <th>Cost</th>
                            <th>Service Category</th> 
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        for ($i = 0; $i < count($row); $i++) {
                            $motherService = new Services();
                            $motherService->initWithId($row[$i]->serviceId);
                            echo '<tr>';
                            echo '<td>' . $row[$i]->id . '</td>';
                            echo '<td>' . $row[$i]->name . '</td>';
                            echo '<td>' . $row[$i]->cost . '</td>';
                            echo '<td>' . $motherService->getName(). '</td>';
                            echo '<td>'
                            . '<a href="editServiceItem.php?id=' . $row[$i]->id . '">Edit | </a> '
                            . '<a href="deleteServiceItem.php?id=' . $row[$i]->id . '">Delete</a> '
                            . '</td>';
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
                <a class="btn btn-info navbar-btn" href="dashboard.php"><span class="glyphicon glyphicon-circle-arrow-left"></span> Back To Dashboard</a>
            </div>
        </div>
        <?php require 'utils/footer.php'; ?>
    </body>
</html>
