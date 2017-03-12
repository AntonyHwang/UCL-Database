<?php 
    require 'includes/config.php'; 
    
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
        left: 120px;
        background: #FFFFFF;
    }
    .search-box input[type="text"], .result{
        width: 100%;
        box-sizing: border-box;
    }
    /* Formatting result items */
    .result p{
        margin: 0;
        padding: 7px 10px;
        border: 1px solid #FFFFFF;
        border-top: none;
        background: #FFFFFF;
        cursor: pointer;
    }
    .result p:hover{
        background: #FFFFFF;
    }
</style>
<html>
    <head>
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

                            <li><a href="All_Users.php">All Users</a></li>
                             <li><a href="All_Posts.php">All Posts</a></li>
                             <li><a href="All_Photos.php">All Photos</a></li>
                           
                                    <li><a href="login.php">Log out</a></li>
                                     <li><a href="admin_register.php">Register Admin</a></li>
                              
                        <?php else:
                            header('Location:login.php');
                        endif; ?>
                        <div class="search-box">
                            <input type="text" autocomplete="off" placeholder="Search for People.." />
                            <div class="result"></div>
                        </div>
                        <div>
                            <input type="radio" name="search_type" value="all" checked> <font color="white">All</font>
                           
                        </div>
                        <div><br></div>
                    </ul>
                </div>
        </nav>
    </body>
    <script type="text/javascript">
        $(document).ready(function(){
            $('.search-box input[type="text"]').on("keyup input", function(){
                /* Get input value on change */
                var inputVal = $(this).val();
                var search_type = $('input[name="search_type"]:checked').val();
                var resultDropdown = $(this).siblings(".result");
                if(inputVal.length){
                    $.get("livesearch.php", {term: inputVal, type: search_type}).done(function(data){
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
</html>

