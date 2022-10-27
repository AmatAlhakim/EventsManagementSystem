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
        <title>Client Report</title>
        <?php require 'utils/styles.php'; ?>
        <?php require 'utils/scripts.php'; ?>
        <?php require './debugging.php'; ?>
    </head>
    <body>
        <?php
        require 'classes/Client.php';
        require_once 'utils/functions.php';
        if (isset($_POST['submitted'])) {
            $status = 0;
            $range = 0;
            $type = "";
            if (isset($_POST['category']) && ($_POST['category'] == 'Golden')) {
                $status = 16;
                $range = 1000;
                $type = "Golden";
            }
            if (isset($_POST['category']) && ($_POST['category'] == 'Silver')) {
                $status = 11;
                $range = 15;
                $type = "Silver";
            }
            if (isset($_POST['category']) && ($_POST['category'] == 'Bronze')) {
                $status = 6;
                $range = 10;
                $type = "Bronze";
            }
            if (isset($_POST['category']) && ($_POST['category'] == 'Uncategorized')) {
                $status = 1;
                $range = 5;
                $type = "Uncategorized";
            }
            $client = new Client();
            $row = $client->getAllClientByStatus($status, $range);
        }
        ?>
        <div class = "content">
            <div class = "container">
                <div class ="col-md-6 col-md-offset-3">
                    <div class="list_cont">
                        <h3>Client Report</h3>
                    </div>
                    <br>
                    <form action="clientReport.php" method="Post"><!--form-->
                        <div class="dash_blog_inner">
                            <div class="list_cont">
                                <h3>Select Royalty Category</h3>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-2">
                                        <input type="radio" 
                                               class="form-check-input" 
                                               name="category" 
                                               value="Golden" 
                                               <?php
                                               if (isset($_POST['category']) && ($_POST['category'] == 'Golden'))
                                                   echo "checked";
                                               ?>/><label> Golden</label>                                        
                                    </div>
                                    <div class="col-md-2">
                                        <input type="radio" 
                                               class="form-check-input" 
                                               name="category" 
                                               value="Silver"
                                               <?php
                                               if (isset($_POST['category']) && ($_POST['category'] == 'Silver'))
                                                   echo "checked";
                                               ?>/> <label> Silver</label>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="radio" 
                                               class="form-check-input" 
                                               name="category" 
                                               value="Bronze"  
                                               <?php
                                               if (isset($_POST['category']) && ($_POST['category'] == 'Bronze'))
                                                   echo "checked";
                                               ?>/> <label> Bronze</label>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="radio" 
                                               class="form-check-input" 
                                               name="category" 
                                               value="Uncategorized" 
                                               <?php
                                               if (isset($_POST['category']) && ($_POST['category'] == 'Uncategorized'))
                                                   echo "checked";
                                               ?> /> <label> Uncategorized</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type = "submit" class = "btn btn-primary pull-left">Search</button>
                        <input type="hidden" name="submitted" value="1" />
                    </form>
                    <br><br><br>
                    <h3> <?php if (isset($_POST['submitted'])) echo $type . ' '; ?> Clients Are:</h3> 
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
                            if (isset($_POST['submitted'])) {
                                for ($i = 0; $i < count($row); $i++) {
                                    echo '<tr>';
                                    echo '<td>' . $row[$i]->id . '</td>';
                                    echo '<td>' . $row[$i]->name . '</td>';
                                    echo '<td>' . $row[$i]->email . '</td>';
                                    echo '<td>' . $row[$i]->password . '</td>';
                                    echo '<td>' . $row[$i]->address . '</td>';
                                    echo '<td>' . $row[$i]->phone . '</td>';
                                    echo '<td>' . $row[$i]->royaltyPoint . '</td>';
                                    echo '</tr>';
                                }
                            }
                            ?>
                        </tbody>
                    </table>   
                </div>
            </div>
        </div>
    </body>
</html>



