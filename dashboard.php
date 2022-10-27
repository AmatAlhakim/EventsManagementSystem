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
<html lang="en">
    <head>
        <!-- basic -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!-- mobile metas -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="viewport" content="initial-scale=1, maximum-scale=1">
        <!-- site metas -->
        <title>Dashboard</title>
        <meta name="keywords" content="">
        <meta name="description" content="">
        <meta name="author" content="">
        <!-- site icon -->
        <link rel="icon" href="images/fevicon.png" type="image/png" />
        <!-- bootstrap css -->
        <link rel="stylesheet" href="css/bootstrap.min.css" />
        <!-- site css -->
        <link rel="stylesheet" href="style.css" />
        <!-- responsive css -->
        <link rel="stylesheet" href="css/responsive.css" />
        <!-- color css -->
        <link rel="stylesheet" href="css/colors.css" />
        <!-- select bootstrap -->
        <link rel="stylesheet" href="css/bootstrap-select.css" />
        <!-- scrollbar css -->
        <link rel="stylesheet" href="css/perfect-scrollbar.css" />
        <!-- custom css -->
        <link rel="stylesheet" href="css/custom.css" />

        <?php require 'utils/styles.php'; ?><!--css links. file found in utils folder-->
        <?php require 'utils/scripts.php'; ?><!--js links. file found in utils folder-->
        <?php require './debugging.php'; ?>
    </head>

    <body class="dashboard dashboard_1">
    <body class="dashboard dashboard_1" data-new-gr-c-s-check-loaded="14.1062.0" data-gr-ext-installed="">

        <!--header content. file found in utils folder-->

        <div class="full_container">
            <div class="inner_container">
                <!-- Sidebar  -->
                <nav id="sidebar" class="ps">
                    <div class="sidebar_blog_1">
                        <div class="sidebar_user_info">
                            <div class="icon_setting"></div>
                            <div class="user_profle_side">
                                <div class="user_img"><img class="img-responsive" src="images/user icon.png" alt="#"></div>
                                <div class="user_info">
                                    <h6><?php
                                        echo 'hi ' . $_SESSION['username'];
                                        ?></h6>
                                    <p><span class="online_animation"></span> Online</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="sidebar_blog_2">
                        <h4>General</h4>
                        <ul class="list-unstyled components">
                            <li>
                                <a name="btnHome" href="index.php"><strong style="font-size: medium"><i class="fa fa-home blue1_color"></i> <span>Home Page</strong></span></a>
                            </li>
                            <li class="active">
                                <a href="dashboard.php">
                                    <i class="fa fa-dashboard yellow_color"></i> <span>Dashboard</span>
                                </a>
                            </li>
                            <li>
                                <a name="btnEmp" href="manageEmployees.php"><i class="fa fa-clock-o orange_color"></i> <span>Manage Employees</span></a>
                            </li>
                            <li>
                                <a name="btnClient" href="manageClients.php"><i class="fa fa-diamond purple_color"></i> <span>Mange Clients</span></a>
                            </li>
                            <li>
                                <a name="btnLocation" href="manageLocations.php"><i class="fa fa-table purple_color2"></i> <span>Manage Locations</span></a></li>
                            <li>
                                <a  name="btnService" href="manageServices.php"><i class="fa fa-object-group blue2_color"></i> <span>Manage Services</span></a>
                            </li>
                            <li>
                                <a  name="btnService" href="manageServiceItems.php"><i class="fa fa-object-ungroup red_color"></i> <span>Manage Service Items</span></a>
                            </li>
                        </ul>
                    </div>
                    <div class="ps__rail-x" style="left: 0px; bottom: 0px;"><div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps__rail-y" style="top: 0px; right: 0px;"><div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;"></div></div><div class="ps__rail-x" style="left: 0px; bottom: 0px;"><div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps__rail-y" style="top: 0px; right: 0px;"><div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;"></div></div></nav>
                <!-- end sidebar -->
                <!-- right content -->
                <div id="content">
                    <!-- topbar -->
                    <div class="topbar">
                        <nav class="navbar navbar-expand-lg navbar-light">
                            <div class="full">
                                <button type="button" id="sidebarCollapse" class="sidebar_toggle"><i class="fa fa-bars"></i></button>
                            </div>
                        </nav>
                    </div>
                    <!-- end topbar -->

                    <!-- dashboard inner -->
                    <div class="midde_cont">
                        <div class="container-fluid">
                            <div class="row column_title">
                                <div class="col-md-12">
                                    <div class="page_title" style="margin-top: 40px;">
                                        <h2>Dashboard</h2>
                                    </div>
                                </div>
                            </div>
                            <div class="row column1">
                                <div class="col-md-6 col-lg-3">
                                    <div class="full counter_section margin_bottom_30">
                                        <div class="couter_icon">
                                            <div> 
                                                <i class="fa fa-user yellow_color"></i>
                                            </div>
                                        </div>
                                        <div class="counter_no">
                                            <div>
                                                <p class="total_no">2500</p>
                                                <p class="head_couter">Welcome</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-3">
                                    <div class="full counter_section margin_bottom_30">
                                        <div class="couter_icon">
                                            <div> 
                                                <i class="fa fa-clock-o blue1_color"></i>
                                            </div>
                                        </div>
                                        <div class="counter_no">
                                            <div>
                                                <p class="total_no">123.50</p>
                                                <p class="head_couter">Average Time</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-3">
                                    <div class="full counter_section margin_bottom_30">
                                        <div class="couter_icon">
                                            <div> 
                                                <i class="fa fa-cloud-download green_color"></i>
                                            </div>
                                        </div>
                                        <div class="counter_no">
                                            <div>
                                                <p class="total_no">1,805</p>
                                                <p class="head_couter">Collections</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-3">
                                    <div class="full counter_section margin_bottom_30">
                                        <div class="couter_icon">
                                            <div> 
                                                <i class="fa fa-comments-o red_color"></i>
                                            </div>
                                        </div>
                                        <div class="counter_no">
                                            <div>
                                                <p class="total_no">54</p>
                                                <p class="head_couter">Comments</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row column1 social_media_section">
                                <div class="col-md-6 col-lg-3">
                                    <div class="full socile_icons fb margin_bottom_30">
                                        <div class="social_icon">
                                            <i class="fa fa-facebook"></i>
                                        </div>
                                        <div class="social_cont">
                                            <ul>
                                                <li>
                                                    <span><strong>350k</strong></span>
                                                    <span>Friends</span>
                                                </li>
                                                <li>
                                                    <span><strong>2128</strong></span>
                                                    <span>Feeds</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-3">
                                    <div class="full socile_icons tw margin_bottom_30">
                                        <div class="social_icon">
                                            <i class="fa fa-twitter"></i>
                                        </div>
                                        <div class="social_cont">
                                            <ul>
                                                <li>
                                                    <span><strong>984k</strong></span>
                                                    <span>Followers</span>
                                                </li>
                                                <li>
                                                    <span><strong>11.9k</strong></span>
                                                    <span>Tweets</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-3">
                                    <div class="full socile_icons linked margin_bottom_30">
                                        <div class="social_icon">
                                            <i class="fa fa-snapchat"></i>
                                        </div>
                                        <div class="social_cont">
                                            <ul>
                                                <li>
                                                    <span><strong>758+k</strong></span>
                                                    <span>Snaps</span>
                                                </li>
                                                <li>
                                                    <span><strong>3.5B</strong></span>
                                                    <span>Followers</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-3">
                                    <div class="full socile_icons google_p margin_bottom_30">
                                        <div class="social_icon">
                                            <i class="fa fa-instagram"></i>
                                        </div>
                                        <div class="social_cont">
                                            <ul>
                                                <li>
                                                    <span><strong>4.5k</strong></span>
                                                    <span>Followers</span>
                                                </li>
                                                <li>
                                                    <span><strong>5754</strong></span>
                                                    <span>Post</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <iframe style="width: 500px; height: 450px;" src="adminDashboardForm.php" allowfullscreen>
                            </iframe>
                            <br>
                            <iframe 
                                style="width: 900px; height: 850px;" 
                                src="clientReport.php" >
                            </iframe>
                        </div>
                    </div>
                    <!-- end dashboard inner -->
                    <?php include './utils/footer.php'; ?>
                </div>
            </div>
        </div> 

        <!-- jQuery -->
        <script src="utils/scripts.php"></script>
        <script src="js/jquery.min.js"></script>
        <script src="js/popper.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <!-- wow animation -->
        <script src="js/animate.js"></script>
        <!-- select country -->
        <script src="js/bootstrap-select.js"></script>
        <!-- owl carousel -->
        <script src="js/owl.carousel.js"></script> 
        <!-- chart js -->
        <script src="js/Chart.min.js"></script>
        <script src="js/Chart.bundle.min.js"></script>
        <script src="js/utils.js"></script>
        <script src="js/analyser.js"></script>
        <!-- nice scrollbar -->
        <script src="js/perfect-scrollbar.min.js"></script>
        <script>
            var ps = new PerfectScrollbar('#sidebar');
        </script>
        <!-- custom js -->
        <script src="js/chart_custom_style1.js"></script>
        <script src="js/custom.js"></script>
    </body>
</body>
</html>