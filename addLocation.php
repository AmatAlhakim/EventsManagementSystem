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
        <title>Add Location Form</title>
        <style>
            span.error{
                color: red;
            }
        </style>  
        <?php require 'utils/styles.php'; ?><!--css links. file found in utils folder-->
        <?php require 'utils/scripts.php'; ?><!--js links. file found in utils folder-->
        <?php require './debugging.php'; ?>
    </head>
    <body>
        <!--Beginning of main php section-->
        <?php
        require './functions.php';
        require_once './classes/Location.php';
        require_once './classes/Files.php';
        require_once './classes/Upload.php';

        if (isset($_POST['submitted'])) {
            saveData();
        }

        function saveData() {
            try {
                if (empty($_POST['Name'])) {
                    $errors['Name'] = "name required";
                }
                if (empty($_POST['Address'])) {
                    $errors['Address'] = "address required";
                }
                if (empty($_POST['managerFName'])) {
                    $errors['managerFName'] = "first name required";
                }
                if (empty($_POST['managerLName'])) {
                    $errors['managerLName'] = "last name required";
                }
                if (empty($_POST['managerEmail'])) {
                    $errors['managerEmail'] = "manager email required";
                }
                if (empty($_POST['managerNumber'])) {
                    $errors['managerNumber'] = "manager number required";
                }
                if (empty($_POST['maxCap'])) {
                    $capacity = intval($_POST['maxCap']);
                    if ($capacity < 0 || $capacity > 999999) {
                        $errors['maxCap'] = "capacity required. Cannot be a negative value";
                    }
                    $errors['maxCap'] = "capacity required. Cannot be a negative value";
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
                    $name = trim($_POST['Name']);
                    $address = trim($_POST['Address']);
                    $managerFName = trim($_POST['managerFName']);
                    $managerLName = trim($_POST['managerLName']);
                    $managerEmail = trim($_POST['managerEmail']);
                    $managerNumber = trim($_POST['managerNumber']);
                    $maxCapacity = trim($_POST['maxCap']);
                    $type = trim($_POST['lType']);
                    $seatingAvailable = trim($_POST['seat']);
                    $cost = trim($_POST['Cost']);
                    //$locationImage = uploadImg();

                    $location = new Location();
                    //set the values in the db
                    $location->setName($name);
                    $location->setAddress($address);
                    $location->setManagerFName($managerFName);
                    $location->setManagerLName($managerLName);
                    $location->setManagerEmail($managerEmail);
                    $location->setManagerNumber($managerNumber);
                    $location->setMaxCapacity($maxCapacity);
                    $location->setType($type);
                    $location->setCost($cost);
                    $location->setSeatingAvailable($seatingAvailable);
                    // $location->setLocationImage($locationImage);
                    $facilityList = "(";
                    if (!empty($_POST['facilities'])) {
                        for ($i = 0;
                                $i < count($_POST['facilities']);
                                $i++) {
                            $facilityList .= $_POST['facilities'][$i];
                            if ($i === count($_POST['facilities']) - 1)
                                break;
                            else
                                $facilityList .= ", ";
                        }
                        $facilityList .= ")";
                    }
                    $location->setFacilities($facilityList);
                    //add the location
                    if ($location->initWithLocationName()) {
                        if ($location->registerLocation()) {
                            $fileLocation = new Location();
                            $location->setName($name);
                            $location->initWithLocationName();
                            $_SESSION['locationId'] = $fileLocation->getId();
                            echo '<p>' . $fileLocation->getId() . '</p>';
                            echo '<p>registered</p>';
                            header('Location: manageLocations.php?uploadSuccess');
                        } else {
                            echo $command;
                            echo '<br><p>failed to register</p>';
                        }
                    } else {
                        echo $command;
                        echo '<br><h4>Location Already Exists</h4>';
                    }
                    //set img
                    if (!empty($_FILES)) {
                        $upload = new Upload();
                        $upload->setUploadDir('images/');
                        $msg = $upload->upload('pic');

                        if (empty($msg)) {
                            $file = new Files();
                            $file->setLocationId($name);
                            $file->setFname($upload->getFilepath());
                            $file->setFlocation($upload->getUploadDir() . $upload->getFilepath());
                            $file->setFtype($upload->getFileType());
                            $file->addFile();
                        } else
                            print_r($msg);
                    } else
                        echo '<p> try again';

                    //end image file
                } else {
                    echo '<h4>There are some errors</h4>';
                }
            } catch (Exception $ex) {
                $errorMessage = $ex->getMessage();
            }
            if (!isset($errors)) {
                $errors = array();
            }
        }
        ?>
        <!--End of main php section-->

        <?php require 'utils/header.php'; ?><!--header content. file found in utils folder-->
        <div class="content">
            <div class="container">
                <h1>Create Location Form</h1><!--form title-->
                <?php
                if (isset($errorMessage)) {
                    echo '<p>Error: ' . $errorMessage . '</p>';
                }
                ?>
                <form action="addLocation.php" method="Post" enctype="multipart/form-data" class="form-horizontal">
                    <div class="form-group">
                        <label for="Name" class="col-md-2 control-label">Location Name</label>
                        <div class="col-md-5">
                            <input type="text" 
                                   class="form-control" 
                                   id="Name" 
                                   name="Name" 
                                   value="<?php echo $_POST['Name']; ?>" /><!--input-->
                        </div>
                        <div class="col-md-4">
                            <span id="NameError" class="error"><!--error message for invalid input-->
                                <?php echoValue($errors, 'Name'); ?>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="Address" class="col-md-2 control-label">Address</label>
                        <div class="col-md-5">
                            <input type="text" 
                                   class="form-control" 
                                   id="Address" 
                                   name="Address" 
                                   value="<?php echo $_POST['Address']; ?>" /><!--input-->
                        </div>
                        <div class="col-md-4">
                            <span id="LAddressError" class="error"><!--error message for invalid input-->
                                <?php echoValue($errors, 'Address'); ?>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="managerFName" class="col-md-2 control-label">Manager First Name</label>
                        <div class="col-md-5">
                            <input type="text" 
                                   class="form-control" 
                                   id="managerFName" 
                                   name="managerFName" 
                                   value="<?php echo $_POST['managerFName'] ?>" /><!--input-->
                        </div>
                        <div class="col-md-4">
                            <span id="mNameError" class="error"><!--error message for invalid input-->
                                <?php echoValue($errors, 'managerFName'); ?>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="managerLName" class="col-md-2 control-label">Manager Last Name</label>
                        <div class="col-md-5">
                            <input type="text" 
                                   class="form-control" 
                                   id="managerName" 
                                   name="managerLName" 
                                   value="<?php echo $_POST['managerLName']; ?>" /><!--input-->
                        </div>
                        <div class="col-md-4">
                            <span id="mNameError" class="error"><!--error message for invalid input-->
                                <?php echoValue($errors, 'managerLName'); ?>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="managerEmail" class="col-md-2 control-label">Manager Email</label>
                        <div class="col-md-5">
                            <input type="email" 
                                   class="form-control" 
                                   id="managerEmail" 
                                   name="managerEmail" 
                                   value="<?php echo $_POST['managerEmail'] ?>" /><!--input-->
                        </div>
                        <div class="col-md-4">
                            <span id="mEmailError" class="error"><!--error message for invalid input-->
                                <?php echoValue($errors, 'managerEmail'); ?>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="managerNumber" class="col-md-2 control-label">Manager Number</label>
                        <div class="col-md-5">
                            <input type="text" 
                                   class="form-control" 
                                   id="managerNumber" 
                                   name="managerNumber" 
                                   value="<?php echo $_POST['managerNumber']; ?>" /><!--input-->
                        </div>
                        <div class="col-md-4">
                            <span id="mNumError" class="error"><!--error message for invalid input-->
                                <?php echoValue($errors, 'managerNumber'); ?>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="locationMaxCap" class="col-md-2 control-label">Max Capacity</label>
                        <div class="col-md-5">
                            <input type="text" 
                                   class="form-control" 
                                   id="maxCap" 
                                   name="maxCap" 
                                   value="<?php echo $_POST['maxCap']; ?>" /><!--input-->
                        </div>
                        <div class="col-md-4">
                            <span id="maxCapError" class="error"><!--error message for invalid input-->
                                <?php echoValue($errors, 'maxCap'); ?>
                            </span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="Cost" class="col-md-2 control-label">Cost Per Hour</label>
                        <div class="col-md-5">
                            <input type="text" 
                                   class="form-control" 
                                   id="Cost" 
                                   name="Cost" 
                                   value="<?php echo $_POST['Cost']; ?>" /><!--input-->
                        </div>
                        <div class="col-md-4">
                            <span id="CostError" class="error"><!--error message for invalid input-->
                                <?php echoValue($errors, 'Cost'); ?>
                            </span>
                        </div>
                    </div>

                    <!--codes below has no connection with the database.-->
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
                                   }
                                   ?>>Both
                        </div>
                        <div class="col-md-4">
                            <span id="lTypeError" class="error">
                                <?php echoValue($errors, 'lType'); ?>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Seating Available</label>
                        <div class="col-md-5">
                            <select class="form-control" name="seat" id="seat">
                                <option value="yes" <?php
                                if (isset($_POST['seat'])) {
                                    if ($_POST['seat'] == "yes") {
                                        echo "selected";
                                    }
                                }
                                ?>>Yes</option>
                                <option value="no" <?php
                                if (isset($_POST['seat'])) {
                                    if ($_POST['seat'] == "no") {
                                        echo "selected";
                                    }
                                }
                                ?>>No</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <span id="seatError" class="error">
                                <?php echoValue($errors, 'seat'); ?>
                            </span>
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
                                   }
                                   ?>
                                   />Disabled Access Toilets <br>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Attach File:</label>
                        <div class="col-md-5">
                            <input type="file" 
                                   class="control-label" 
                                   name="pic" 
                                   id="pic"
                                   value="">
                        </div>
                    </div>
                    <br><br><br>
                    <button type="submit" name="submit" class="btn btn-primary btn-lg pull-right">Create Location <span class="glyphicon glyphicon-send"></span></button>
                    <input type="hidden" name="submitted" value="1"/>
                    <a class="btn btn-info btn-lg pull-left navbar-btn" href="manageLocations.php"><span class="glyphicon glyphicon-circle-arrow-left"></span> Back</a>
                </form>
            </div>
        </div>
        <?php require 'utils/footer.php'; ?><!--footer content. file found in utils folder-->
    </body>
</html>

