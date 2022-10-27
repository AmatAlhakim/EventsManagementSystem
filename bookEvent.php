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
        <title>View Location Details</title>
        <?php require 'utils/styles.php'; ?><!--css links. file found in utils folder-->
        <?php require 'utils/scripts.php'; ?><!--js links. file found in utils folder-->
        <?php require './debugging.php'; ?>
    </head>

    <body>
         <?php
        require_once 'utils/functions.php';
        require_once 'classes/Users.php';

        start_session();
        if (!is_logged_in()) {
            header("Location: login_form.php");
        }
        $user = $_SESSION['user'];
        ?>
        
        <?php
        require './functions.php';
        require_once './classes/Location.php';
        require_once './classes/Files.php';
        require_once './classes/Upload.php';
        
        $id = 0;
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $_SESSION['locationId'] = $id;
        } elseif (isset($_POST['id'])) {
            $id = $_POST['id'];
        } else {
            echo '<p class="error">No Event id Parameter</p>';
            exit();
        }

        $location = new Location();
        $location->initWithId($id);
        
        $file = new Files;
        $file->initWithLocationName($location->getName());

        if (isset($_POST['submitted'])) {
            try {
                //get the duration in hours to calculate the cost 

                if (empty($_POST['Duration'])) {
                    $errors['Duration'] = "Event Duration Is required";
                }
                if (empty($errors)) {

                    $name = trim($_POST['Name']);
                    $address = trim($_POST['Address']);
                    $managerFName = trim($_POST['ManagerFName']);
                    $managerLName = trim($_POST['ManagerLName']);
                    $managerEmail = trim($_POST['ManagerEmail']);
                    $managerNumber = trim($_POST['ManagerNumber']);
                    $maxCapacity = trim($_POST['MaxCapacity']);
                    $type = trim($_POST['lType']);
                    $seatingAvailable = trim($_POST['seat']);
                    $locationImage = $location->getLocationImage();
                    $cost = $_POST['Cost'];
                    $hours = trim($_POST['Duration']);

                    $totalPrice = (double) $hours * (double) $cost;
                    $_SESSION['locationCost'] = $totalPrice;
                    $_SESSION['duration'] = $hours;

                    //set the values in the db
                    $location->setName($name);
                    $location->setAddress($address);
                    $location->setManagerFName($managerFName);
                    $location->setManagerLName($managerLName);
                    $location->setManagerEmail($managerEmail);
                    $location->setManagerNumber($managerNumber);
                    $location->setMaxCapacity($maxCapacity);
                    $location->setType($type);
                    $location->setSeatingAvailable($seatingAvailable);
                    if (isset($_POST['facilities'])) {
                        if (!empty($_POST['facilities'])) {
                            $facilityList = "";
                            foreach ($_POST['facilities'] as $facility) {
                                $facilityList .= $facility . ", ";
                            }
                            $location->setFacilities($facilityList);
                        }
                    }
                    $location->setCost($cost);

                    //update location in the db
                    $location->updateDB();

                    header('Location: bookAService.php');
                } else {
                    echo '<h4>There are some errors</h4>';
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
        <div class="content">
            <div class="container">
                <h1>View Hall Details</h1><!--form title-->
                <h3><?php echo 'Start Date: ' . $_SESSION['from_date'] . ', End Date: ' . $_SESSION['to_date'] . ', Totla Number Of Audience: ' . $_SESSION['attendees']; ?></h3>
                <?php
                if (isset($errorMessage)) {
                    echo '<p>Error: ' . $errorMessage . '</p>';
                }
                ?>
                <form action="bookEvent.php" method="Post" enctype="multipart/form-data" class="form-horizontal">
                    <div class="form-group">
                        <label for="Name" class="col-md-2 control-label">Name</label><!--label-->
                        <div class="col-md-5">
                            <input type="text" 
                                   class="form-control" 
                                   id="Name" 
                                   name="Name" 
                                   value="<?php echo $location->getName(); ?>" 
                                   readonly/><!--input with current data from database-->
                        </div>
                        <div class="col-md-4">
                            <span id="LNameError" class="error"></span><!--error message for invalid input-->
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="Address" class="col-md-2 control-label">Address</label><!--label-->
                        <div class="col-md-5">
                            <input type="text" 
                                   class="form-control" 
                                   id="Address" 
                                   name="Address" 
                                   value="<?php echo $location->getAddress(); ?>" 
                                   readonly/><!--input with current data from database-->
                        </div>
                        <div class="col-md-4">
                            <span id="LAddressError" class="error"></span><!--error message for invalid input-->
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="ManagerFName" class="col-md-2 control-label">Manager First Name</label><!--label-->
                        <div class="col-md-5">
                            <input type="text" 
                                   class="form-control" 
                                   id="ManagerFName" 
                                   name="ManagerFName" 
                                   value="<?php echo $location->getManagerFName(); ?>" 
                                   readonly/><!--input with current data from database-->
                        </div> 
                        <div class="col-md-4">
                            <span id="mNameError" class="error"></span><!--error message for invalid input-->
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="ManagerLName" class="col-md-2 control-label">Manager Last Name</label><!--label-->
                        <div class="col-md-5">
                            <input type="text" 
                                   class="form-control" 
                                   id="ManagerLName" 
                                   name="ManagerLName" 
                                   value="<?php echo $location->getManagerLName(); ?>" 
                                   readonly/><!--input with current data from database-->
                        </div>
                        <div class="col-md-4">
                            <span id="mNameError" class="error"></span><!--error message for invalid input-->
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="ManagerEmail" class="col-md-2 control-label">Manager Email</label><!--label-->
                        <div class="col-md-5">
                            <input type="text" 
                                   class="form-control" 
                                   id="ManagerEmail" 
                                   name="ManagerEmail" 
                                   value="<?php echo $location->getManagerEmail(); ?>" 
                                   readonly/><!--input with current data from database-->
                        </div>
                        <div class="col-md-4">
                            <span id="mEmailError" class="error"></span><!--error message for invalid input-->
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="ManagerNumber" class="col-md-2 control-label">Manager Number</label><!--label-->
                        <div class="col-md-5">
                            <input type="text" 
                                   class="form-control" 
                                   id="ManagerNumber" 
                                   name="ManagerNumber" 
                                   value="<?php echo $location->getManagerNumber(); ?>" 
                                   readonly/><!--input with current data from database-->
                        </div>
                        <div class="col-md-4">
                            <span id="mNumError" class="error"></span><!--error message for invalid input-->
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="MaxCapacity" class="col-md-2 control-label">Max Capacity</label><!--label-->
                        <div class="col-md-5">
                            <input type="text" 
                                   class="form-control" 
                                   id="MaxCapacity" 
                                   name="MaxCapacity" 
                                   value="<?php echo $location->getMaxCapacity(); ?>" 
                                   readonly/><!--input with current data from database-->
                        </div>
                        <div class="col-md-4">
                            <span id="capError" class="error"></span><!--error message for invalid input-->
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Location Type</label><!--radio buttons with multiple options-->
                        <div class="col-md-5">
                            <input type="radio"  
                                   name="lType" 
                                   value="indoor" 
                                   <?php
                                   if (isset($_POST['lType'])) {
                                       if ($_POST["lType"] == "indoor") {
                                           echo "checked";
                                       }
                                   } else {
                                       if ($location->getType() == "indoor") {
                                           echo "checked";
                                       }
                                   }
                                   ?> 
                                   onclick="return false;"/>Indoor <br>
                            <input type="radio" 
                                   name="lType" 
                                   value="outdoor" 
                                   <?php
                                   if (isset($_POST['lType'])) {
                                       if ($_POST["lType"] == "outdoor") {
                                           echo "checked";
                                       }
                                   } else {
                                       if ($location->getType() == "outdoor") {
                                           echo "checked";
                                       }
                                   }
                                   ?>
                                   onclick="return false;"/>Outdoor <br>
                            <input type="radio" 
                                   name="lType" 
                                   value="both" 
                                   <?php
                                   if (isset($_POST['lType'])) {
                                       if ($_POST["lType"] == "both") {
                                           echo "checked";
                                       }
                                   } else {
                                       if ($location->getType() == "both") {
                                           echo "checked";
                                       }
                                   }
                                   ?>
                                   onclick="return false;"/>Both
                        </div>
                        <div class="col-md-4">
                            <span id="typeError" class="error">
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Seating Available</label>
                        <div class="col-md-5">
                            <input class="form-control"
                                   type="text"
                                   value="<?php
                                   echo $location->getSeatingAvailable();
                                   ?>" readonly/>
                        </div>
                    </div>    
                    <div class="form-group">
                        <label class="col-md-2 control-label">Facilities</label>
                        <div class="col-md-5">
                            <input type="checkbox" 
                                   name="facilities[]" 
                                   value="sound" 
                                   <?php
                                   if (isset($_POST['facilities'])) {
                                       $facilities = $_POST["facilities"];
                                       if (!empty($facilities)) {
                                           foreach ($facilities as $value) {
                                               if ($value == "sound") {
                                                   echo "checked";
                                               }
                                           }
                                       }
                                   } else {
                                       if (strpos($location->getFacilities(), "sound") !== false) {
                                           echo "checked";
                                       }
                                   }
                                   ?>    
                                   >Sound Room <br>
                            <input type="checkbox" 
                                   name="facilities[]" 
                                   value="screen" 
                                   <?php
                                   if (isset($_POST['facilities'])) {
                                       $facilities = $_POST["facilities"];
                                       if (!empty($facilities)) {
                                           foreach ($facilities as $value) {
                                               if ($value == "screen") {
                                                   echo "checked";
                                               }
                                           }
                                       }
                                   } else {
                                       if (strpos($location->getFacilities(), "screen") !== false) {
                                           echo "checked";
                                       }
                                   }
                                   ?>   
                                   />Big Screen Room <br>
                            <input type="checkbox" 
                                   name="facilities[]" 
                                   value="restaurant" 
                                   <?php
                                   if (isset($_POST['facilities'])) {
                                       $facilities = $_POST["facilities"];
                                       if (!empty($facilities)) {
                                           foreach ($facilities as $value) {
                                               if ($value == "restaurant") {
                                                   echo "checked";
                                               }
                                           }
                                       }
                                   } else {
                                       if (strpos($location->getFacilities(), "restaurant") !== false) {
                                           echo "checked";
                                       }
                                   }
                                   ?>
                                   />Restaurants <br>
                            <input type="checkbox" 
                                   name="facilities[]" 
                                   value="lab" 
                                   <?php
                                   if (isset($_POST['facilities'])) {
                                       $facilities = $_POST["facilities"];
                                       if (!empty($facilities)) {
                                           foreach ($facilities as $value) {
                                               if ($value == "lab") {
                                                   echo "checked";
                                               }
                                           }
                                       }
                                   } else {
                                       if (strpos($location->getFacilities(), "lab") !== false) {
                                           echo "checked";
                                       }
                                   }
                                   ?>
                                   />Lab <br>
                            <input type="checkbox" 
                                   name="facilities[]" 
                                   value="disabled" 
                                   <?php
                                   if (isset($_POST['facilities'])) {
                                       $facilities = $_POST["facilities"];
                                       if (!empty($facilities)) {
                                           foreach ($facilities as $value) {
                                               if ($value == "disabled") {
                                                   echo "checked";
                                               }
                                           }
                                       }
                                   } else {
                                       if (strpos($location->getFacilities(), "disabled") !== false) {
                                           echo "checked";
                                       }
                                   }
                                   ?>
                                   />Disabled Access Toilets <br>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="Duration" class="col-md-2 control-label">Duration in Hours</label><!--label-->
                        <div class="col-md-5">
                            <input type="text" 
                                   class="form-control" 
                                   id="Duration" 
                                   name="Duration" 
                                   value="<?php
                                   echo $_POST['Duration'];
                                   ?>" /><!--input with current data from database-->
                        </div>
                        <div class="col-md-4">
                            <span id="DurationError" class="error">
                                <?php echoValue($errors, 'Duration'); ?>
                            </span><!--error message for invalid input-->
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="Duration" class="col-md-2 control-label">Cost</label><!--label-->
                        <div class="col-md-5">
                            <input type="text" 
                                   class="form-control" 
                                   id="Cost" 
                                   name="Cost" 
                                   value="<?php echo $location->getCost(); ?>" 
                                   readonly/><!--input with current data from database-->
                        </div>
                        <div class="col-md-4">
                            <span id="CostError" class="error"></span><!--error message for invalid input-->
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Location Image</label>
                        <div class="col-md-5">
                            <img name="pic" id="pic" src="<?php
                            
                            if (!empty($file->getFlocation())) {
                                echo $file->getFlocation();
                            }
                            ?>" width="480px" height="450px">
                        </div>
                    </div>                    

                    <div class="form-group row" style="margin-top: 40px;">
                        <div class="col-md-4">
                            <a class="btn btn-warning pull-left" href="events2.php">
                                <span class="glyphicon glyphicon-circle-arrow-left"></span>Update Options | Cancel</a><!--return/back button-->
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary pull-right" name="submit">Proceed Booking <span class="glyphicon glyphicon-floppy-disk"></span></button><!--submit button-->
                            <input type ="hidden" name="submitted" value="TRUE">
                            <input type ="hidden" name="id" value="<?php echo $id ?>"/>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <?php require 'utils/footer.php'; ?><!--footer content. file found in utils folder-->
    </body>
</html>
