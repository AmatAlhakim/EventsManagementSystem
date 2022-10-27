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
        <title>Manage Locations</title>
        <?php require 'utils/styles.php'; ?>
        <?php require 'utils/scripts.php'; ?>
        <?php require './debugging.php'; ?>
    </head>
    <body>
        <?php
        require 'utils/header.php';
        require './classes/Upload.php';
        require './classes/Files.php';
        ?>
        <div class = "content">
            <div class = "container">
                <?php
                require_once 'classes/Location.php';
                if (isset($message)) {
                    echo '<p>' . $message . '</p>';
                }
                $location = new Location();
                $row = $location->getAllLocations();
                if (empty($row))
                    echo '<h2 style="color: darkblue;"> Location Records Are Empty</h2>';
                ?>
                <br>
                <a class="btn btn-primary btn-lg pull-right" href = "addLocation.php">Add Location</a><!--register button-->
                <a class="btn btn-info navbar-btn pull-left" href="dashboard.php"><span class="glyphicon glyphicon-circle-arrow-left"></span> Back To Dashboard</a>
                <br><br><br><br><br><br>

                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Location ID</th>
                            <th>Name</th>
                            <th>Manager First Name</th>                    
                            <th>Manager Last Name</th>
                            <th>Manager Email Name</th>
                            <th>Manager Phone No</th>
                            <th>Address</th>
                            <th>Max Capacity</th>
                            <th>Type</th>
                            <th>Seating Available</th>
                            <th>Facilities</th>
                            <th>Location URL</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $file = new Files;
                        for ($i = 0; $i < count($row); $i++) {

                            $file->initWithLocationName($row[$i]->name);
                            
                            echo '<tr>';
                            echo '<td>' . $row[$i]->id . '</td>';
                            echo '<td>' . $row[$i]->name . '</td>';
                            echo '<td>' . $row[$i]->managerFName . '</td>';
                            echo '<td>' . $row[$i]->managerLName . '</td>';
                            echo '<td>' . $row[$i]->managerEmail . '</td>';
                            echo '<td>' . $row[$i]->managerNumber . '</td>';
                            echo '<td>' . $row[$i]->address . '</td>';
                            echo '<td>' . $row[$i]->maxCapacity . '</td>';
                            echo '<td>' . $row[$i]->type . '</td>';
                            echo '<td>' . $row[$i]->seatingAvailable . '</td>';
                            echo '<td>' . $row[$i]->facilities . '</td>';
                            
                            echo '<td>' ;
                            echo '<img name="pic" id="pic" src="'
                            .$file->getFlocation()
                            . '" width="120px" height="110px"/>';
                            echo '<td>' ;
                            
                            echo '<td>'
                            . '<a href="editLocationForm.php?id=' . $row[$i]->id . '">Edit | </a> '
                            . '<a href="deleteLocation.php?id=' . $row[$i]->id . '">Delete</a> '
                            . '</td>';
                            echo '</tr>';
                        }
                        ?>

                    </tbody>
                </table>
                <br><br>
                <a class="btn btn-info navbar-btn" href="dashboard.php"><span class="glyphicon glyphicon-circle-arrow-left"></span> Back To Dashboard</a>
            </div>
        </div>
        <?php require 'utils/footer.php'; ?>
    </body>
</html>