<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>index page</title>
        <?php require 'utils/styles.php'; ?><!--css links. file found in utils folder-->
        <?php require 'utils/scripts.php'; ?><!--js links. file found in utils folder-->
        <?php require './debugging.php'; ?>
    </head>
    <body>
        <?php
        require 'utils/header.php';
        require_once 'utils/functions.php';
        ?><!--header content. file found in utils folder-->

        <div class="bgImage">
            <div class = "col-md-12">
                <div class = "container">
                    <div class = "jumbotron"><!--jumbotron-->
                        <h1>Orient Event Organizer Ltd (OEO), Venues & Catering </h1><!--jumbotron heading-->
                        <p><!--jumbotron content-->
                            Whether you're looking to book a Training project, post-work gathering, celebratory function, conference, business
                            meeting, wedding or private dining event, our dedicated Urban Events Team can create a package that will meet
                            your every need.
                        </p>
                        <p id="dateAndTime"></p>
                    </div>
                </div>
            </div>
        </div>

        <div class = "content"><!--body content holder-->
            <div class = "container">

                <div class = "col-md-12"><!--body content title holder with 12 grid columns-->
                    <h1>What we organize</h1><!--body content title-->
                </div>
            </div>

            <div class="container">
                <div class="col-md-12">
                    <hr>
                </div>
            </div>

            <div class="row"><!--wedding content-->
                <section>
                    <div class="container">
                        <div class="col-md-6"><!--image holder with 6 grid columns-->
                            <img src="images/workshop.jpg" class="img-responsive">
                        </div>
                        <div class="subcontent col-md-6"><!--Text holder with 6 column grid-->
                            <h1>Workshop</h1><!--title-->
                            <p><!--content-->
                                We offer a wide range of workshop room options that allow you to choose the perfect 
                                space and location for your next meeting. We will help you find the perfect 
                                meeting room to host any of your events, a private room for interviewing candidates, 
                                or an inspiring space for a training session or lecture.
                            </p>
                            <hr class="customline"><!--css modified horizontal line-->
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
                        <div class="col-md-6"><!--image holder with 6 grid columns-->
                            <img src="images/training.jpg" class="img-responsive">
                        </div>
                        <div class="subcontent col-md-6"><!--Text holder with 6 column grid-->
                            <h1>Training</h1><!--title-->
                            <p><!--content-->
                                Whether an all-day or the ultimate extravaganza that
                                lasts well into the wee hours, our Urban Events team is here to make sure all your Training
                                goes as you planed and much more better.
                            </p>
                            <hr class="customline"><!--css modified horizontal line-->
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
                        <div class="col-md-6"><!--image holder with 6 grid columns-->
                            <img src="images/seminar.jpg" class="img-responsive">
                        </div>
                        <div class="subcontent col-md-6"><!--Text holder with 6 column grid-->
                            <h1>Seminar</h1><!--title-->
                            <p><!--content-->
                                From formal, to not-so-formal, our flexible event
                                spaces can cater to your every need for meetings and conferences large or small, and our
                                dedicated event team can assist with all aspects of your event planning.
                            </p>
                            <hr class="customline"><!--css modified horizontal line-->
                        </div><!--subcontent div-->
                    </div><!--container div-->
                </section>
            </div><!--row div-->

            <div class="container">
                <div class="col-md-12">
                    <hr>
                </div>
            </div>

        </div><!--body content div-->
        <?php require 'utils/footer.php'; ?><!--footer content. file found in utils folder-->
    </body>
</html>
