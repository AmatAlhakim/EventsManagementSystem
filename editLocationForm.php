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
        <title>Edit Location</title>
        <?php require 'utils/styles.php'; ?><!--css links. file found in utils folder-->
        <?php require 'utils/scripts.php'; ?><!--js links. file found in utils folder-->
        <?php require './debugging.php'; ?>
    </head>

    <body>
        <?php
        require './functions.php';
        require_once './classes/Location.php';
        require_once './classes/Files.php';
        require_once './classes/Upload.php';

        $id = 0;
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
        } elseif (isset($_POST['id'])) {
            $id = $_POST['id'];
        } else {
            echo '<p class="error">No Client id Parameter</p>';
            exit();
        }

        $location = new Location();
        $location->initWithId($id);

        $file = new Files;
        $file->initWithLocationName($location->getName());

        if (isset($_POST['submitted'])) {
            try {
                if (empty($_POST['Name'])) {
                    $errors['Name'] = "name required";
                }
                if (empty($_POST['Address'])) {
                    $errors['Address'] = "address required";
                }
                if (empty($_POST['ManagerFName'])) {
                    $errors['ManagerFName'] = "first name required";
                }
                if (empty($_POST['ManagerLName'])) {
                    $errors['ManagerLName'] = "last name required";
                }
                if (empty($_POST['ManagerEmail'])) {
                    $errors['ManagerEmail'] = "manager email required";
                }
                if (empty($_POST['ManagerNumber'])) {
                    $errors['ManagerNumber'] = "manager number required";
                }
                if (empty($_POST['MaxCapacity'])) {
                    $capacity = intval($_POST['MaxCapacity']);
                    if ($capacity < 0 || $capacity > 999999) {
                        $errors['MaxCapacity'] = "MaxCapacity required. Cannot be a negative value";
                    }
                    $errors['MaxCapacity'] = "MaxCapacity required. Cannot be a negative value";
                }
                if (empty($_POST['lType'])) {
                    $type = array("indoor", "outdoor", "both");
                    if (!in_array($_POST['lType'], $type)) {
                        $errors['lType'] = "invalid type";
                    }
                }
                if (empty($_POST['facilities'])) {
                    $fcl = array("sound", "screen", "restaurant", "lab", "disabled");
                    if (in_array($_POST['facilities'], $fcl)) {
                        $errors['facilities'] = "invalid restriction";
                    }
                }
                //end validation
                if (empty($errors)) {
                    $oldName = $location->getName();
                    $name = trim($_POST['Name']);
                    $address = trim($_POST['Address']);
                    $managerFName = trim($_POST['ManagerFName']);
                    $managerLName = trim($_POST['ManagerLName']);
                    $managerEmail = trim($_POST['ManagerEmail']);
                    $managerNumber = trim($_POST['ManagerNumber']);
                    $maxCapacity = trim($_POST['MaxCapacity']);
                    $type = trim($_POST['lType']);
                    $seatingAvailable = trim($_POST['seat']);

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
                    //update location in the db
                    $location->updateDB();
                    //update image
                    if (!empty($_FILES)) {
                        $upload = new Upload();
                        $upload->setUploadDir('images/');
                        $msg = $upload->upload('pic');
                        if (empty($msg)) {
                            unlink($file->getFlocation());
                            $file->setFname($upload->getFilepath());
                            $file->setFlocation($upload->getUploadDir() . $upload->getFilepath());
                            $file->setFtype($upload->getFileType());
                            $file->setLocationId($name);
                            $file->updateDB($oldName);
                        }
                    } else {
                        print_r($msg);
                    }
                    //end update image
                    header('Location: manageLocations.php?uploadSuccess');
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
                <h1>Edit Location Form</h1><!--form title-->
                <?php
                if (isset($errorMessage)) {
                    echo '<p>Error: ' . $errorMessage . '</p>';
                }
                ?>
                <br><br>
                <form action="editLocationForm.php" method="Post" enctype="multipart/form-data" class="form-horizontal">
                    <div class="form-group">
                        <label for="Name" class="col-md-2 control-label">Name</label><!--label-->
                        <div class="col-md-5">
                            <input type="text" 
                                   class="form-control" 
                                   id="Name" 
                                   name="Name" 
                                   value="<?php echo $location->getName(); ?>" /><!--input with current data from database-->
                        </div>
                        <div class="col-md-4">
                            <span id="LNameError" class="error">
                                <?php echoValue($errors, 'Name') ?>
                            </span><!--error message for invalid input-->
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="Address" class="col-md-2 control-label">Address</label><!--label-->
                        <div class="col-md-5">
                            <input type="text" 
                                   class="form-control" 
                                   id="Address" 
                                   name="Address" 
                                   value="<?php echo $location->getAddress(); ?>" /><!--input with current data from database-->
                        </div>
                        <div class="col-md-4">
                            <span id="LAddressError" class="error">
                                <?php echoValue($errors, 'Address') ?>
                            </span><!--error message for invalid input-->
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="ManagerFName" class="col-md-2 control-label">Manager First Name</label><!--label-->
                        <div class="col-md-5">
                            <input type="text" 
                                   class="form-control" 
                                   id="ManagerFName" 
                                   name="ManagerFName" 
                                   value="<?php echo $location->getManagerFName(); ?>" /><!--input with current data from database-->
                        </div> 
                        <div class="col-md-4">
                            <span id="mNameError" class="error">
                                <?php echoValue($errors, 'ManagerFName') ?>
                            </span><!--error message for invalid input-->
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="ManagerLName" class="col-md-2 control-label">Manager Last Name</label><!--label-->
                        <div class="col-md-5">
                            <input type="text" 
                                   class="form-control" 
                                   id="ManagerLName" 
                                   name="ManagerLName" 
                                   value="<?php echo $location->getManagerLName(); ?>" /><!--input with current data from database-->
                        </div>
                        <div class="col-md-4">
                            <span id="mNameError" class="error">
                                <?php echoValue($errors, 'ManagerLName') ?>
                            </span><!--error message for invalid input-->
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="ManagerEmail" class="col-md-2 control-label">Manager Email</label><!--label-->
                        <div class="col-md-5">
                            <input type="text" 
                                   class="form-control" 
                                   id="ManagerEmail" 
                                   name="ManagerEmail" 
                                   value="<?php echo $location->getManagerEmail(); ?>" /><!--input with current data from database-->
                        </div>
                        <div class="col-md-4">
                            <span id="mEmailError" class="error">
                                <?php echoValue($errors, 'ManagerEmail') ?>
                            </span><!--error message for invalid input-->
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="ManagerNumber" class="col-md-2 control-label">Manager Number</label><!--label-->
                        <div class="col-md-5">
                            <input type="text" 
                                   class="form-control" 
                                   id="ManagerNumber" 
                                   name="ManagerNumber" 
                                   value="<?php echo $location->getManagerNumber(); ?>" /><!--input with current data from database-->
                        </div>
                        <div class="col-md-4">
                            <span id="mNumError" class="error">
                                <?php echoValue($errors, 'ManagerNumber') ?>
                            </span><!--error message for invalid input-->
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="MaxCapacity" class="col-md-2 control-label">Max Capacity</label><!--label-->
                        <div class="col-md-5">
                            <input type="text" 
                                   class="form-control" 
                                   id="MaxCapacity" 
                                   name="MaxCapacity" 
                                   value="<?php echo $location->getMaxCapacity(); ?>" /><!--input with current data from database-->
                        </div>
                        <div class="col-md-4">
                            <span id="capError" class="error">
                                <?php echoValue($errors, 'MaxCapacity') ?>
                            </span><!--error message for invalid input-->
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
                                   ?> >Indoor <br>
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
                                   ?>>Outdoor <br>
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
                                   ?>>Both
                        </div>
                        <div class="col-md-4">
                            <span id="typeError" class="error">
                                <?php echoValue($errors, 'lType') ?>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Seating Available</label>
                        <div class="col-md-5">
                            <select class="form-control" name="seat">
                                <option value="yes" <?php
                                if (isset($_POST['seat'])) {
                                    if ($_POST['seat'] == "yes") {
                                        echo "selected";
                                    }
                                } else {
                                    if ($location->getSeatingAvailable() == "yes") {
                                        echo "selected";
                                    }
                                }
                                ?>>Yes</option>
                                <option value="no" <?php
                                if (isset($_POST['seat'])) {
                                    if ($_POST['seat'] == "no") {
                                        echo "selected";
                                    }
                                } else {
                                    if ($location->getSeatingAvailable() == "no") {
                                        echo "selected";
                                    }
                                }
                                ?>>No</option>
                            </select>
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
                        <label class="col-md-2 control-label">Location Image</label>
                        <div class="col-md-5">
                            <img name="pic" id="pic" src="<?php
                            if (!empty($file->getFlocation())) {
                                echo $file->getFlocation();
                            }
                            ?>" width="480px" height="450px">
                            <input type="file" 
                                   class="control-label" 
                                   name="pic" 
                                   id="pic"
                                   value="<?php
//                                   echo $file->getFlocation();
                                   ?>">
                        </div>
                    </div>       
                    <br><br>

                    <button type="submit" class="btn btn-primary btn-lg pull-right" name="submit">Update <span class="glyphicon glyphicon-floppy-disk"></span></button><!--submit button-->
                    <input type ="hidden" name="submitted" value="TRUE">
                    <input type ="hidden" name="id" value="<?php echo $id ?>"/>
                    <a class="btn btn-info btn-lg pull-left" href="manageLocations.php"><span class="glyphicon glyphicon-circle-arrow-left"></span> Back</a><!--return/back button-->
                </form>
            </div>
        </div>
        <?php require 'utils/footer.php'; ?><!--footer content. file found in utils folder-->
    </body>
</html>
