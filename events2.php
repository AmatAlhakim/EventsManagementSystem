<?php
require_once 'utils/functions.php';
require_once 'classes/Users.php';
require_once 'classes/Search.php';

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
        <title>View Upcoming Events</title>
        <?php require 'utils/styles.php'; ?><!--css links. file found in utils folder-->
        <?php require 'utils/scripts.php'; ?><!--js links. file found in utils folder-->
        <?php require './debugging.php'; ?>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>  
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />  
        <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>  
        <link rel="stylesheet" href="http://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">  
    </head>
    <body>
        <!--Beginning Main PHP Section-->
        <?php
        require_once './functions.php';
        require_once './classes/Files.php';
        require_once './classes/Upload.php';
        require_once 'classes/Event.php';

        $row = array();
        $halls = array();
        $event = new Event();

        if (empty($_POST['eventDate'])) {
            $errors['eventDate'] = "Event Start Date is required";
        }
        if (empty($_POST['eventTime'])) {
            $errors['eventTime'] = "Event End Date is required";
        }
        if (empty($_POST['audienceNum'])) {
            $errors['audienceNum'] = "Audience Num required";
        }

        if (isset($_POST['submitted'])) {
            if (empty($errors)) {
                $date = trim($_POST['eventDate']);
                $_SESSION['from_date'] = $date;
                $time = trim($_POST['eventTime']);
                $_SESSION['to_date'] = $time;
                $attendees = trim($_POST['audienceNum']);
                $_SESSION['attendees'] = $attendees;

                $row = $event->searchForEvents($date, $time, $attendees);

                $ids = "(";
                if (empty($row))
                    echo "no events available";

                for ($i = 0;
                        $i < count($row);
                        $i++) {
                    $halls[$i] .= $row[$i]->locationId;
                    $ids .= $row[$i]->locationId;
                    if ($i == (count($row) - 1))
                        continue;
                    else
                        $ids .= ', ';
                }
                $ids .= ")";
                $locations = $event->getAvailableLocations($ids);
                if (empty($locations))
                    echo 'there is no location <br>';
                
                //show all 
                if (isset($_POST['showAll'])) {
                    $row = $event->showAvailableEvents();
                    $ids = "(";
                    for ($i = 0;
                            $i < count($row);
                            $i++) {
                        $halls[$i] .= $row[$i]->locationId;
                        $ids .= $row[$i]->locationId;
                        if ($i === (count($row) - 1))
                            continue;
                        else
                            $ids .= ', ';
                    }
                    $ids .= ")";
                    $locations = $event->getAvailableLocations($ids);
                    if (empty($locations))
                        echo 'there is no location <br>';
                }
            }
        }
        if (!isset($errors)) {
            $errors = array();
        }
        ?>
        <!--End Main PHP Section-->
        <?php require 'utils/header.php'; ?><!--header content. file found in utils folder-->

        <div class="content"><!--body content holder-->
            <div class="container">
                <h3><b style="color:darkblue;">Search For Events</b></h3>
                <br><br>
                <form action="events2.php" class="form-inline" method="Post">
                    <div class="form-group"><!--body content title holder with 12 grid columns-->
                        <label><strong>From</strong></label>
                        <input type="text" 
                               class="form-control" 
                               id="eventDate" 
                               name="eventDate" 
                               value="<?php
                               if (isset($_POST['eventDate']))
                                   echo $_POST['eventDate'];
                               ?>"/>
                        <div class="col-md-4">
                            <span id="eventDateError" class="error"><!--error message for invalid input-->
                                <?php echoValue($errors, 'eventDate'); ?>
                            </span>
                        </div>
                    </div>
                    <div class="form-group"><!--body content title holder with 12 grid columns-->
                        <label>To</label>
                        <input type="text" 
                               class="form-control" 
                               id="eventTime" 
                               name="eventTime"
                               value="<?php
                               if (isset($_POST['eventTime']))
                                   echo $_POST['eventTime'];
                               ?>"/>
                        <div class="col-md-4">
                            <span id="eventTimeError" class="error"><!--error message for invalid input-->
                                <?php echoValue($errors, 'eventTime'); ?>
                            </span>
                        </div>
                    </div>
                    <div class="form-group"><!--body content title holder with 12 grid columns-->
                        <label>Attendees No</label>
                        <input type="number" 
                               class="form-control"  
                               name="audienceNum"
                               value="<?php
                               if (isset($_POST['audienceNum']))
                                   echo $_POST['audienceNum'];
                               ?>"/>
                        <div class="col-md-4">
                            <span id="audienceNumError" class="error"><!--error message for invalid input-->
                                <?php echoValue($errors, 'audienceNum'); ?>
                            </span>
                        </div>
                    </div>
                    <div class="form-group"><!--body content title holder with 12 grid columns-->
                        <input type="submit" class = "btn btn-primary pull-right" name="submit" value="Search"/>
                        <input type="hidden" name="submitted" value="1"/>
                    </div>  
                    <br><br><br>
                    <input type="submit" 
                           class = "btn btn-primary pull-left" 
                           name="showAll" 
                           value="Show Recommendation"/>
                </form>
                <br><br>
            </div>

        </div>
        <hr/>

        <div class="container">
            <div class="col-md-12"><!--body content title holder with 12 grid columns-->
                <h1>What's On</h1><!--body content title-->
                <hr/>
            </div>
            <table class="table table-hover table_format">
                <thead>
                    <tr>
                        <th>Event ID</th>
                        <th>Hall Name</th>
                        <th>Max Capacity</th>                    
                        <th>Facilities</th>
                        <th>Cost</th>
                        <th>Location Image</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $file = new Files;
                    for ($i = 0; $i < count($locations); $i++) {

                        $file->initWithLocationName($locations[$i]->name);

                        echo '<tr>';
                        echo '<td>' . $locations[$i]->id . '</td>';
                        echo '<td>' . $locations[$i]->name . '</td>';
                        echo '<td>' . $locations[$i]->maxCapacity . '</td>';
                        echo '<td>' . $locations[$i]->facilities . '</td>';
                        echo '<td>' . $locations[$i]->cost . '</td>';
                        echo '<td>' . $locations[$i]->locationImage . '</td>';

                        echo '<td>';
                        echo '<img name="pic" id="pic" src="'
                        . $file->getFlocation()
                        . '" width="120px" height="110px"/>';
                        echo '<td>';

                        echo '<td>'
                        . '<a href="bookEvent.php?id=' . $locations[$i]->id . '">View Details</a> '
                        . '</td>';
                        echo '</td>';
                        echo '</tr>';
                        $_SESSION['locationId'] = $locations[$i]->id;
                    }
                    ?>
                </tbody>
            </table> 
        </div>

        <div class="container">
            <div class="col-md-12">
                <hr>
                <h2>Trend Events</h2>
                <br><br><br>
            </div>
        </div>

        <div class="row"><!--event content-->
            <section>
                <div class="container">
                    <div class="date col-md-1"><!--date holder with 1 grid column-->
                        <span class="month">JAN</span><br><!--month-->
                        <hr class="line"><!--css modified horizontal line-->
                        <span class="day">20</span><!--day-->
                    </div>
                    <div class="col-md-5"><!--image holder with 5 grid column-->
                        <img src="images/bdayevent.jpg" class="img-responsive">
                    </div>
                    <div class="subcontent col-md-6"><!--event content holder with 6 grid column-->
                        <h1 class="title">Joe's 21st Seminar</h1><!--event content title-->
                        <p class="location"><!--event content location-->
                            UrbanXchange Private Dining Room, The Rocks 12 Argyle Street
                        </p>
                        <p class="definition"><!--event content definition-->
                            Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
                            When an unknown printer took a galley of type and scrambled it to make a type specimen book.
                        </p>
                        <hr class="customline2"><!--css modified horizontal line-->
                    </div><!--subcontent div-->
                </div><!--container div-->
            </section>
        </div><!--row div-->

        <div class="container">
            <div class="col-md-12">
                <hr>
            </div>
        </div>

        <div class="row">
            <section>
                <div class="container">
                    <div class="date col-md-1"><!--date holder with 1 grid column-->
                        <span class="month">APR</span><br><!--month-->
                        <hr class="line"><!--css modified horizontal line-->
                        <span class="day">20</span><!--day-->
                    </div>
                    <div class="col-md-5"><!--image holder with 5 grid column-->
                        <img src="images/fashevent.jpg" class="img-responsive">
                    </div>
                    <div class="subcontent col-md-6"><!--event content holder with 6 grid column-->
                        <h1 class="title">Dress to Impress Workshop</h1><!--event content title-->
                        <p class="location"><!--event content location-->
                            Ananas Bar & Brasserie, The Rocks 18 Argyle Street
                        </p>
                        <p class="definition"><!--event content definition-->
                            Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
                            When an unknown printer took a galley of type and scrambled it to make a type specimen book.
                        </p>
                        <hr class="customline2"><!--css modified horizontal line-->
                    </div><!--subcontent div-->
                </div><!--container div-->
            </section>
        </div><!--row div-->

        <div class="container">
            <div class="col-md-12">
                <hr>
            </div>
        </div>

        <div class="row">
            <section>
                <div class="container">
                    <div class="date col-md-1"><!--date holder with 1 grid column-->
                        <span class="month">JUN</span><br><!--month-->
                        <hr class="line"><!--css modified horizontal line-->
                        <span class="day">20</span><!--day-->
                    </div>
                    <div class="col-md-5"><!--image holder with 5 grid column-->
                        <img src="images/fashion2.jpg" class="img-responsive">
                    </div>
                    <div class="subcontent col-md-6"><!--event content holder with 6 grid column-->
                        <h1 class="title">Modern Fashion Design Principles Workshop</h1><!--event content title-->
                        <p class="location"><!--event content location-->
                            Munich Brauhaus South Wharf, 45 South Wharf Promenade
                        </p>
                        <p class="definition"><!--event content definition-->
                            Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
                            When an unknown printer took a galley of type and scrambled it to make a type specimen book.
                        </p>
                        <hr class="customline2"><!--css modified horizontal line-->
                    </div><!--subcontent div-->
                </div><!--container div-->
            </section>
        </div><!--row div-->

        <div class="container">
            <div class="col-md-12">
                <hr>
            </div>
        </div>

        <div class="row">
            <section>
                <div class="container">
                    <div class="date col-md-1"><!--date holder with 1 grid column-->
                        <span class="month">AUG</span><br><!--month-->
                        <hr class="line"><!--css modified horizontal line-->
                        <span class="day">20</span><!--day-->
                    </div>
                    <div class="col-md-5"><!--image holder with 5 grid column-->
                        <img src="images/meetevent.jpg" class = "img-responsive">
                    </div>
                    <div class="subcontent col-md-6"><!--event content holder with 6 grid column-->
                        <h1 class="title">Career Talk</h1><!--event content title-->
                        <p class="location"><!--event content location-->
                            UrbanXchange Private Dining Room, The Rocks 12 Argyle Street
                        </p>
                        <p class="definition"><!--event content definition-->
                            Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
                            When an unknown printer took a galley of type and scrambled it to make a type specimen book.
                        </p>
                        <hr class="customline2"><!--css modified horizontal line-->
                    </div><!--subcontent div-->
                </div><!--container div-->
            </section>
        </div><!--row div-->
    </div><!--body content div-->
    <?php require 'utils/footer.php'; ?><!--footer content. file found in utils folder-->
</body>
</html>

<!--AJAX SEARCHING & LISTING SECTION-->
<script>
    $(document).ready(function () {
        $.datepicker.setDefaults({
            dateFormat: 'yy-mm-dd'
        })
        $(function () {
            $("#eventDate").datepicker();
            $("#eventTime").datepicker();
        });
        $('#search').click(function () {
            var from_date = $('#eventDate').val();
            var to_date = $('#eventTime').val();
            if (from_date !== '' && to_date !== '') {
            } else {
                alert("Please Select A Date")
            }
        })
    });
</script>
