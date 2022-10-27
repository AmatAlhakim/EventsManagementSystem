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
        <meta charset="UTF-8">
        <title>Book A Service</title>
        <?php require 'utils/styles.php'; ?><!--css links. file found in utils folder-->
        <?php require 'utils/scripts.php'; ?><!--js links. file found in utils folder-->
        <?php
        require './debugging.php';
        ?>
    </head>
    <body>

        <?php
        require_once './functions.php';
        require_once './classes/Users.php';
        require_once './classes/Services.php';
        require_once './classes/ServiceItem.php';
        ?>

        <!--Main PHP Section-->
        <?php

        $services = new Services();
        $servicesList = $services->getAllServices();
        $items = new ServiceItem();
        $serviceId = 0;

        if (isset($_POST['search'])) {
            $serviceName = $_POST['Service'];
            for ($i = 0; $i < count($servicesList); $i++) {
                if ($servicesList[$i]->name == $serviceName) {
                    $serviceId = $servicesList[$i]->id;
                }
            }
            $row = $items->getServiceItemsByServiceId($serviceId);
            $_SESSION['MotherServiceID'] = $serviceId;
        }
        if (!isset($errors)) {
            $errors = array();
        }
        ?>
        <!--End Main PHP Section-->

        <?php require 'utils/header.php'; ?><!--header content. file found in utils folder-->

        <div class="content"><!--body content holder-->
            <div class="container">
                <h1><b style="color:darkblue;">Book A Service</b></h1>
                <br><br>
                <?php
                if (isset($errorMessage)) {
                    echo '<p>Error: ' . $errorMessage . '</p>';
                }
                ?>
                <form action="bookAService.php" class="form-horizontal" method="Post">
                    <div class="form-group">
                        <label class="col-md-2 control-label">Services Available</label>
                        <div class="col-md-5">
                            <select class="form-control"
                                    id="Service"
                                    name="Service">
                                <option value='' disabled selected>Select A Category</option>
                                <?php
                                for ($j = 0; $j < count($servicesList); $j++) {
                                    echo '<option>';
                                    echo $servicesList[$j]->name;
                                    echo '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <span id="ServiceError" class="error"><!--error message for invalid input-->
                                <?php
                                if (!isset($errors)) {
                                    echoValue($errors, 'Service');
                                }
                                ?>
                            </span>
                        </div>
                    </div>  
                    <div>
                        <button type="submit" name="search" class = "btn btn-default pull-right">Search <span class="glyphicon glyphicon-send"></span></button>
                    </div>
                </form>

                <table class="table table-hover table_format">
                    <thead>
                        <tr>
                            <th>Service Item ID</th>
                            <th>Name</th>
                            <th>Cost</th>                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        echo '<h4>' . $serviceName . '</h4>';
                        for ($i = 0; $i < count($row); $i++) {
                            echo '<tr>';
                            echo '<td>' . $row[$i]->id . '</td>';
                            echo '<td>' . $row[$i]->name . '</td>';
                            echo '<td>' . $row[$i]->cost . '</td>';
                            echo '<td>'
                            . '<a href="clientDetails.php?id=' . $row[$i]->id . '">Book Service</a> '
                            . '</td>';
                            echo '</td>';
                            echo '</tr>';
                            $_SESSION['ServiceItemID'] = $row[$i]->id;
                            $_SESSION['ServiceItemCost'] = $row[$i]->cost;
                            $_SESSION['ServiceItemName'] = $row[$i]->name;
                        }
                        ?>
                    </tbody>
                </table> 
                <div>
                    <a class="btn btn-warning navbar-btn" href="index.php">
                        <span class="glyphicon glyphicon-circle-arrow-left"></span>Cancel</a>

                    <a class="btn btn-primary navbar-btn pull-right" href="clientDetails.php">Skip
                        <span class="glyphicon glyphicon-circle-arrow-right"></span></a>
                </div>
            </div>
        </div>                
    </body>
</html>


