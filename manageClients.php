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
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Manage Clients</title>
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
                $client = new Client();
                $row = $client->getAllClients();
                if (empty($row))
                    echo '<h2 style="color: darkblue;"> Clients Records Are Empty</h2>';
                ?>
                <br>
                <a class="btn btn-lg btn-primary pull-right" href = "addClient.php">Add Client</a><!--register button-->
                <a class="btn btn-info navbar-btn pull-left" href="dashboard.php"><span class="glyphicon glyphicon-circle-arrow-left"></span> Back To Dashboard</a>
                <br><br><br><br><br><br>

                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Client ID</th>
                            <th>Name</th>
                            <th>Email</th>                    
                            <th>Password</th>
                            <th>Address</th>
                            <th>Phone No</th>
                            <th>Royalty Point</th>
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
                            echo '<td>' . $row[$i]->address . '</td>';
                            echo '<td>' . $row[$i]->phone . '</td>';
                            echo '<td>' . $row[$i]->royaltyPoint . '</td>';
                            echo '<td>'
                            . '<a href="editClient.php?id=' . $row[$i]->id . '">Edit | </a> '
                            . '<a href="deleteClient.php?id=' . $row[$i]->id . '">Delete</a> '
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

