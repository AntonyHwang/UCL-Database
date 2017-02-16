<?php 
    require 'includes/config.php'; 
?>
<html>
    <head>
        <script>
            function showResult(str) {
            if (str.length==0) { 
                document.getElementById("livesearch").innerHTML="";
                document.getElementById("livesearch").style.border="0px";
                return;
            }
            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp=new XMLHttpRequest();
            } else {  // code for IE6, IE5
                xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange=function() {
                if (this.readyState==4 && this.status==200) {
                document.getElementById("livesearch").innerHTML=this.responseText;
                document.getElementById("livesearch").style.border="1px solid #A5ACB2";
                }
            }
            xmlhttp.open("GET","livesearch.php?q="+str,true);
            xmlhttp.send();
            }
        </script>
    </head>
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
                <div>
                    <form align="right">
                        <input type="text" size="30" onkeyup="showResult(this.value)" placeholder=" Search.." style="margin-top:15px;">
                        <div id="livesearch"></div>
                    </form>
                </div>
        </nav>
    </body>
</html>
