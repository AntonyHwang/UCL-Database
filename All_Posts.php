<?php

    require'includes/config.php';
    include_once('adminheader.php');

?>



<style>
.panel-body {
background-color:white;
}
.posts {
    
    margin: auto;
    
}
</style>

<?php 



$allposts = [];
$me = $_SESSION["id"];
$allposts_sql =  "SELECT id_post, id_user, body ,timestamp FROM post ORDER BY timestamp DESC";
$MyPostlist = $conn->query($allposts_sql);



?>


<html>
    <body>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-1">
                </div>
                <div class="col-md-10">
                    <h1>All Posts </h1>    
                    <hr>
                </div>
                <div class="col-md-1">
                </div>
            </div>
        </div>


        <div class="container-fluid">
             <div class="row">
                <div class="col-md-1">
                </div>
                <div class="col-md-10">       


<!-- the post users-->

<?php 




    while($row = $MyPostlist->fetch()) {
        $postid = $row["id_post"];
        $postOwner = $row["id_user"];
        $getpostowner = "SELECT first_name,surname FROM user WHERE id_user = ".$postOwner.' ';
        $getpostowernresult = $conn->query($getpostowner);
        $namerow = $getpostowernresult->fetch();
        $username= ucfirst($namerow["first_name"])." ".ucfirst($namerow["surname"]);

?>

<div class="panel-body">

                    <h2>
                        <div class="row">
                            <div class="col-md-3">
                            <?php 
                            echo "<img src= \"./uploads/".$postOwner."/profile.jpg\" alt=\"Profile Pic\" class=\"img-rounded\" style=\"width:60px; height 60px;\">";
                            echo "&nbsp <a href=\"./profile.php?profile=".$postOwner."\" >$username</a>\n";  
                                               
                            ?>
                            </div>
                            <div class="col-md-1">                       
                                                    
                            </div>
                            <div class="col-md-8" align="right">
                                <form  action = 'server.php' method="get" >     
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        Delete Post
                                    </button>               
                                    <input type="hidden" name="id_del" value="<?php echo $postid; ?>" />                
                                    <input type="hidden" name="last_page" value="All_Posts.php" />   
                                </form>  
                            </div>
                        </div>                   

  
                    </h2>
    <paragraph>
                        <?php 
                        echo "<h3>&nbsp&nbsp&nbsp&nbsp".$row["body"]."</h3>";
                        echo "<br>";
                        echo '<small style=\" float = right\">'.$row['timestamp'] .'</small>';
                        
                        ?>
    </paragraph>
    </br>
    <hr>
    <?php
    $com = "SELECT id_post, id_user,id_comment, body,timestamp FROM post_comment WHERE id_post = ". $row["id_post"].' ORDER BY timestamp';
    $res_com = $conn->query($com);
    ?>
    <?php 
 
    while($sqlcomment = $res_com->fetch()){
        $commentUsername = "SELECT first_name,surname FROM user WHERE id_user = ".$sqlcomment["id_user"].' ';
        $res_commentUsername = $conn->query($commentUsername);
        while($sqlcommentUsername = $res_commentUsername->fetch()){
        $commentusername= ucfirst($sqlcommentUsername["first_name"])." ".ucfirst($sqlcommentUsername["surname"]);
        }
//picture and two rows goes here
echo "   <div class=\"row\">\n"; 
echo "		<div class=\"col-md-1\">\n"; 
echo "          <img src= \"./uploads/".$sqlcomment["id_user"]."/profile.jpg\" class=\"img-rounded\" alt=\"Profile Pic\" style=\"width:40px; height 40px;\">";
echo "		</div>\n"; 
echo "		<div class=\"col-md-10\">\n"; 
echo "			<div class=\"row\">\n"; 
echo "				<div class=\"col-md-12\">\n"; 
//echo "<a href=\"./profile.php?profile=".$postOwner."\" >$username</a>\n";  
    
echo "<strong><a href=\"./profile.php?profile=".$sqlcomment["id_user"]."\" >".$commentusername." </a>: </strong><h4>&nbsp&nbsp&nbsp&nbsp".$sqlcomment["body"]."</h4>";

?>



<?php
//echo "                example user: this is a  test comment\n"; 
echo "				</div>\n"; 
echo "			</div>\n"; 
echo "			<div class=\"row\">\n"; 
echo "          <br>";
echo "				<div class=\"col-md-12\">\n"; 
//echo "                at 2010 5 7\n"; 
echo "                <small style=\" float = right\">".$sqlcomment['timestamp'] ."</small>";;
echo "				</div>\n"; 
echo "			</div>\n"; 
echo "		</div>\n"; 
//button might go here
echo "		<div class=\"col-md-1\">\n"; 
if($sqlcomment["id_user"]==$_SESSION['id']){
?>
        <form  action = 'server.php' method="get">
            <div class="input-group">
                <div class="input-group-btn">               
                    <input type="hidden" name="last_page" value="homepage.php" /> 
                    <button type="submit" class="btn btn-default btn-sm">
                    <span class="glyphicon glyphicon-remove"></span>  
                    </button>
                </div>
                <input type="hidden" name="id_del_comment" value="<?php echo $sqlcomment["id_comment"]; ?>" />                
            </div>
        </form>
<?php
}
echo "		</div>\n"; 

echo "	</div>\n";
echo "</br>";
//button end
        
    }
    echo "</br>";
    ?>
    <!--add a comment-->

    </div>
    <!--end of one post-->
<hr>
<?php

}

?>
                </div>
                <div class="col-md-1">
                </div>
            </div>
        </div>
    </body>
</html>






