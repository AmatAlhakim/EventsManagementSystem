<?php
require_once 'utils/functions.php';
require_once 'classes/Users.php';
start_session();
if (!is_logged_in()) {
    header("Location: login_form.php");
}
$user = $_SESSION['user'];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Incomes</title>
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
        <?php // require 'utils/header.php'; ?><!--header content. file found in utils folder-->
        <?php
        require './classes/BookedEvents.php';
        if (isset($_POST['submitted'])) {
            try {
                //$errors = array();
                if (empty($_POST['incomeMonth'])) {
                    $errors['incomeMonth'] = "date required";
                    echo '<h3 style="color: red;"> Please Select a date ' . $date . '</h3>';
                }
                if (empty($_POST['incomeRange'])){
                    $errors['incomeRange'] = "range  required";
                    echo '<h3 style="color: red;"> Please Select a range ' . $date . '</h3>';
                }
                if (empty($errors)) {
                    $date = $_POST['incomeMonth'];
                    $year = date('Y', strtotime($date));
                    $month = date('m', strtotime($date));
                    $bookedEvent = new BookedEvents();
                    $range = $_POST['incomeRange'];
                    $bookedList = $bookedEvent->getIncomeByDateRange($date, $range);
                    $totalIncomes = 0.0;
                    for ($i = 0; $i < count($bookedList); $i++){
                        $totalIncomes += $bookedList[$i]->totalCost;
                    }
                }
                if (!empty($errors)) {
                    throw new Exception("");
                }
            } catch (Exception $ex) {
                $errorMessage = $ex->getMessage();
            }
        }
        if (!isset($errors)) {
            $errors = array();
        }
        ?>
        <div class = "content"><!--body content holder-->
            <div class = "container">
                <div class ="col-md-6 col-md-offset-3">
                    <?php
                    if (isset($errorMessage))
                        echo "<p>$errorMessage</p>";
                    ?>
                    <form action="adminDashboardForm.php" method="post"><!--form-->
                        <div class="dash_blog_inner">
                            <div class="list_cont">
                                <h3>Select Dates Range</h3>
                            </div>
                            <div class="form-group">
                                <lable>From:</lable>
                                <input type="datetime-local" 
                                       class="form-control" 
                                       id="incomeMonth" 
                                       name="incomeMonth"
                                       value="<?php
                                       if (isset($_POST['incomeMonth']))
                                           echo $_POST['incomeMonth'];
                                       ?>"/>
                            </div>
                            
                            <div class="form-group">
                                <lable>To:</lable>
                                <input type="datetime-local" 
                                       class="form-control" 
                                       id="incomeMonth" 
                                       name="incomeRange"
                                       value="<?php
                                       if (isset($_POST['incomeRange']))
                                           echo $_POST['incomeRange'];
                                       ?>"/>
                            </div>
                            
                        </div>
                        <button type = "submit" class = "btn btn-default">Search</button>
                        <input type="hidden" name="submitted" value="1" />
                    </form>
                    
                    <div class="aler alert-secondary">
                        <h3>
                            Total Revenue For This Month Is
                            <span style="color: royalblue; font-weight: bolder">
                            <?php
                            echo $totalIncomes;
                            ?>
                            </span>
                        </h3>
                    </div>
                    
                </div><!--col md 6 div-->
            </div><!--container div-->
        </div><!--content div-->
        <?php // require 'utils/footer.php'; ?><!--footer content. file found in utils folder-->
    </body>
</html>
