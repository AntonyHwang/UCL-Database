<?php 
    require 'includes/config.php'; 
?>
<html>
    <body>
        <nav class="navbar navbar-inverse">
            <div class="container-fluid">
                <!-- logo -->
                <div class="navbar-header">
                    <a href="homepage.php" class="navbar-brand">BLOGSTER</a>
                </div>
                <!-- menu items -->
                <div>
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="homepage.php">Home</a></li>
                        <li><a href="myProfilePage.php">Profile</a></li>
                        <!-- drop down menu -->
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Circle<span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="circlePage.php">View Circles</a></li>
                                <li><a href="circleMessengerPage.php">Circle Messenger</a></li>
                            </ul>
                        </li>
                        <li><a href="postPage.php">Post</a></li>
                        <li><a href="photoPage.php">Photo</a></li>
                        <li><a href="friendPage.php">Friend</a></li>
                    </ul>
                </div>
        </nav>
    </body>
</html>