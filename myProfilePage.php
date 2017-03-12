<?php 
    require('includes/config.php'); 
    if ($_SESSION["user_type"] == "ADMIN") {
        include_once('adminheader.php');
    }
    else {
        include_once('header.php');
    }
    if ($_SESSION["user_type"] == "ADMIN") {
        $sql_select = "SELECT * FROM user WHERE id_user = '".$_GET["profile"]."'";
    }
    else {
        $sql_select = "SELECT * FROM user WHERE id_user = '".$_SESSION["id"]."'";
    }
    $stmt = $conn->query($sql_select);
    $row = $stmt->fetch();
    echo "<title>".ucfirst($row["first_name"])." ".ucfirst($row["surname"])."</title>";
    $email = $row["email"];
    $gender = $row["gender"];
    $dob = $row["dob"];
?>



<style>
.panel-body {
background-color:white;
}
</style>


<html>
    <body>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-1">
                </div>
                <div class="col-md-3">
                    <nav>
                        <ul>
                        <?php if ($_SESSION["user_type"] == "ADMIN") { ?>
                            <img src="./uploads/<?php echo $_GET["profile"]?>/profile.jpg" class="img-rounded" alt="Profile Pic" style="width:120px;height 120px;">
                <?php } else { ?>
                            <img src="./uploads/<?php echo $_SESSION["id"]?>/profile.jpg" class="img-rounded" alt="Profile Pic" style="width:120px;height 120px;">
                <?php } ?>
                        </ul>
                    </nav>
                </div>
                <div class="col-md-6">
                    <article>
                        <h1><?php echo ucfirst($row["first_name"])." ".ucfirst($row["surname"]);?></h1>
                        <h4>Gender: <?php echo $gender;?></h4>
                        <h4>Email: <?php echo $email;?></h4>
                        <h4>Birthday:  <?php echo $dob;?></h4>
                    </article>
                </div>
                <div class="col-md-1">  
                <?php 
                if ($_SESSION["user_type"] == "ADMIN") {?>
                    <form method="POST" action=''>
                        <div>
                            <input type="submit" class="btn btn-warning" name="delete_account" value="Delete Account" action="All_Users.php"/>
                        </div>
                        <br>
                        <div>
                            <input type="submit" class="btn btn-primary" name="export_account" value="Export to XML" action="#"/>
                        </div>
                    </form>

                <?php
                }?>     
                </div>
                <div class="col-md-1">
                </div>
            </div>
            <div class="row">
                <div class="col-md-1">
                </div>
                <div class="col-md-10">
                      <h1>Your Own Posts</h1>
                      <hr>
                </div>
                <div class="col-md-1">
                </div>
            </div>



            <!-- all posts-->
            <div class="row">
                <div class="col-md-1">
                </div>
                <div class="col-md-10">
                <?php
                    if ($_SESSION["user_type"] == "ADMIN") {
                        $userid = $_GET['profile'];
                    }
                    else {
                        $userid = $_SESSION['id'];
                    }

                ?>

                <?php 
                    $sql = "SELECT id_post, id_user, body,timestamp FROM post WHERE id_user = ".$userid.' ORDER BY timestamp DESC';
                    $sql2= "SELECT first_name,surname FROM user WHERE id_user = ".$userid.' ';
                    $result = $conn->query($sql);
                    $result2= $conn->query($sql2);
                    while($row2 = $result2->fetch()) {
                        $username= ucfirst($row2["first_name"])." ".ucfirst($row2["surname"]);
                    }
                    while($row = $result->fetch()) {
                        $postid = $row["id_post"];
                    ?>

                <html> 

            
                    <div class="panel-body">
                    <h2>
                        <div class="row">
                            <div class="col-md-3">
                            <?php 
                            echo "<img src= \"./uploads/".$userid."/profile.jpg\" alt=\"Profile Pic\" class=\"img-rounded\" style=\"width:60px; height 60px;\">";
                            echo "&nbsp <a href=\"./profile.php?profile=".$userid."\" >$username</a>\n";  
                                               
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
                                    <input type="hidden" name="last_page" value="myProfilePage.php" />   
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
                    <br>
                    <div class="clearfix"></div>
                    <hr>
                </html>
    <?php
    //echo "id_post:" . $row["id_post"]. "</br> userid: " . $row["id_user"]. "</br>body " . $row["body"]. "<br>";
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
echo "          <img src= \"./uploads/".$sqlcomment["id_user"]."/profile.jpg\" class=\"img-rounded\" alt=\"Profile Pic\" style=\"width:55px; height 55px;\">";
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
echo "          <br>";
echo "			<div class=\"row\">\n"; 
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
                    <button type="submit" class="btn btn-default btn-sm">
                    <span class="glyphicon glyphicon-remove"></span>
                    <input type="hidden" name="last_page" value="myProfilePage.php" />     
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
    //echo "</br>";
    ?>
                    <form  action = 'server.php' method="get">
                        <div class="input-group">
                            <div class="input-group-btn">
                                <button class="btn btn-default"><i class="glyphicon glyphicon-share"></i></button>
                            </div>
                            <input type="hidden" name="postid" value="<?php echo $postid; ?>" />
                            <input type="hidden" name="last_page" value="myProfilePage.php" />   
                            <input type="text" name = 'comment' class="form-control" placeholder="Add a comment..">
                        </div>
                    </form>
                    </div>
                    <hr>
                    <?php
                    echo "</br></br>";
                    }
                    ?>
                
                


                <br>
                <br>
                </div>
                <div class="col-md-1">
                
                </div>
            </div>
        </div>
    </body>
</html>

<?php 


        if (isset($_POST['send'])) {
        $sql_insert = "INSERT INTO friend_request (id_from_user, id_to_user)VALUES ('".$_SESSION["id"]."','".$_GET['profile']."')";
        echo $sql_insert;
        $stmt = $conn->prepare($sql_insert);
        $stmt->execute();
        header('Location: '.$_SERVER['REQUEST_URI']);
    }
    else if (isset($_POST['delete_account'])) {
        $sql_delete = "DELETE FROM user WHERE id_user = ".$_GET['profile'];
        echo $sql_delete;
        $stmt = $conn->prepare($sql_delete);
        $stmt->execute();
        unlink(getcwd().'/uploads/'.$_GET['profile']);
        header('location: All_Users.php');
    }
    else if (isset($_POST['export_account'])) {
        $sql_export = "SELECT * FROM user WHERE id_user = '".$_GET['profile']."'";
        $stmt = $conn->prepare($sql_export);
        $stmt->execute();
        $row = $stmt->fetch();
        $input = new stdClass;
        $input->id = @trim($row["id_user"]);
        $input->first_name = @trim($row["first_name"]);
        $input->surname = @trim($row["surname"]);
        $input->email = @trim($row["email"]);
        $input->password = @trim($row["password"]);
        $input->gender = @trim($row["gender"]);
        $input->dob = @trim($row["dob"]);
        $input->privacy_setting = @trim($row["privacy_setting"]);
        $doc = new DOMDocument('1.0');
        $doc->formatOutput = true;
        $root = $doc->createElement('user');
        $root = $doc->appendChild($root);
        foreach ($input as $key => $value) {
            $element = $doc->createElement($key, $value);
            $root->appendChild($element);
        }
        $doc->save(getcwd()."/xml_export/".$_GET['profile'].".xml");
        header('location: download.php?profile='.$_GET['profile']);
    }
?>
