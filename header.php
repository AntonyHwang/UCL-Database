<?php 
    require 'includes/config.php'; 
    //require 'livesearch.php';
?>
<style type="text/css">
    /* Formatting search box */
    .search-box{
        margin-top:15px;
        margin-bottom:15px;
    }
    .result{
        position: absolute;        
        z-index: 999;
        top: 100%;
        left: 0;
        background: #D3D3D3;
    }
    .search-box input[type="text"], .result{
        width: 100%;
        box-sizing: border-box;
    }
    /* Formatting result items */
    .result p{
        margin: 0;
        padding: 7px 10px;
        border: 1px solid #D3D3D3;
        border-top: none;
        background: #D3D3D3;
        cursor: pointer;
    }
    .result p:hover{
        background: #D3D3D3;
    }
</style>
<html>
    <head>
    <script type="text/javascript">
        $(document).ready(function(){
            $('.search-box input[type="text"]').on("keyup input", function(){
                /* Get input value on change */
                var inputVal = $(this).val();
                var resultDropdown = $(this).siblings(".result");
                if(inputVal.length){
                    $.get("livesearch.php", {term: inputVal}).done(function(data){
                        // Display the returned data in browser
                        resultDropdown.html(data);
                    });
                } else{
                    resultDropdown.empty();
                }
            });
            
            // Set search input value on click of result item
            $(document).on("click", ".result p", function(){
                $(this).parents(".search-box").find('input[type="text"]').val($(this).text());
                $(this).parent(".result").empty();
            });
        });
    </script>
    </head>
    <body>
        <nav class="navbar navbar-inverse">
            <div class="container-fluid">
                <!-- logo -->
                <div class="navbar-header">
                    <a href="#" class="navbar-brand">BLOGSTER</a>
                </div>
                <!-- menu items -->
                <div>
                    <ul class="nav navbar-nav">
                        <?php if ($_SESSION["logged_in"] == "YES"): ?>
                            <li class="active"><a href="homepage.php">Home</a></li>
                            <li><a href="myProfilePage.php">Profile</a></li>
                            <!-- drop down menu -->
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Circle<span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="createCircle.php">Create Circle</a></li>
                                    <li><a href="circlePage.php">View Circles</a></li>
                                    <li><a href="circleMessengerPage.php">Circle Messenger</a></li>
                                </ul>
                            </li>
                            <li><a href="postPage.php?id=<?php echo $_SESSION['id']?>">Post</a></li>
                            <li><a href="photoPage.php?id=<?php echo $_SESSION['id']?>">Photo</a></li>
                            <li><a href="friend.php">Friend</a></li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Account<span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="editPassword.php">Change Password</a></li>
                                    <li><a href="editPassword.php">Deactivate Account</a></li>
                                    <li><a href="login.php">Log out</a></li>
                                </ul>
                            </li>
                            <?php else:
                                header('Location:login.php');
                        endif; ?>
                        <div class="search-box">
                            <input type="text" autocomplete="off" placeholder="Search for People.." />
                            <div class="result"></div>
                        </div>
                    </ul>
                </div>
        </nav>
    </body>
</html>
