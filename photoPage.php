<style>
    div.panel-body {
        text-align: center;
        margin: auto;
        width: 500px;
        border: 5px solid black;
    }
    img.individualPhoto {
        width: 420px;
    }
    figcaption {
        height: auto;
    }
</style>

<?php 
    require ("includes/config.php");
    include_once "header.php";

    // Connect to database.
    try {
        $conn = new PDO( "mysql:host=$host;dbname=$db", $user, $pwd);
        $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    }
    catch(Exception $e){
        die(var_dump($e));
    }

    //delete photos
    if (isset($_GET["profile"] ) && $_GET["profile"] == $_SESSION["id"]) {
        //echo $_GET["profile"];
        //print_r($_POST);
        $post_del = $_GET["id_del"];
        $sql_del = "DELETE FROM photo  WHERE id_photo = ? ";
        //echo $sql_del;
        $stmt = $conn->prepare($sql_del);  
        $stmt->bindValue(1, $post_del);
        if (!$stmt->execute()){

            die('deleting failed');
        }
        else {
            //echo " deleted photo successfully<br>";
            unlink($_GET["del_path"]);
            //echo $_GET["del_path"];
            $refresh = $_GET['profile'];
            //echo "<a href=\"photoPage.php?id=".$refresh." \"><button class=\"btn btn-primary\" >Return to Photos</button></a><br><br>";
        }
    }
    if (isset($_POST["profile"] ) && $_POST["profile"] == $_SESSION["id"]) {
        try {
            $conn = new PDO( "mysql:host=$host;dbname=$db", $user, $pwd);
            $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        }
        catch(Exception $e){
            die(var_dump($e));
        }
        $post_del = $_POST["id_del"];
        $sql_del = "DELETE FROM photo  WHERE id_photo = ? ";
        $stmt = $conn->prepare($sql_del);  
        $stmt->bindValue(1, $post_del);
        if (!$stmt->execute()){

            die('deleting failed');
        }
        else {
            //echo " deleted photo successfully<br>";
            unlink($_GET["del_path"]);
            //echo $_GET["del_path"];
            $refresh = $_GET['profile'];
            echo "<a href=\"photoPage.php?id=".$refresh." \"><button class=\"btn btn-primary\" >Return to Photos</button></a><br><br>";
        }
    }
    
?>
<?php 
//get photo of this user and do intersection with all allow_to_seen_photo
$profile_id = $_SESSION['id'];
$photolist_oneuser=[];
$photos_user = "SELECT id_photo, id_user FROM photo WHERE  id_user = ".$profile_id.'  ORDER BY timestamp DESC';
$result = $conn->query($photos_user);
foreach($result as $user){
    array_push($photolist_oneuser,$user[0]);
}
?>
<?php 
$index = 0;


               

?>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-1">
		</div>
		<div class="col-md-10">
        <h1>My Photos</h1>
        <hr>
		</div>
		<div class="col-md-1">
        <?php
            $photoUploadLink = "uploadPhoto.php?uploadButton=upload";
            //echo "<div style=\"float:right;\""."><a href=\"".$photoUploadLink." \"><button class=\"btn btn-primary\" >Upload Photo</button></a></div><br><br>";
        ?>        
		</div>
	</div>
</div>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-1">
		</div>
		<div class="col-md-10">
            <div class = 'posts'>
                <div class="well"> 
                    <form class="form-horizontal" action="uploadPhoto.php" method="post" enctype="multipart/form-data">
                        <h4>Share a Photo</h4>
                        <div class="form-group" style="padding:14px;">
                            Select Photo <input type="file" name="fileToUpload" id="fileToUpload">
                            <textarea class="form-control" placeholder="Caption" name="caption"></textarea>
                            </br>Privacy: </br>
                            <input class = "checkbox-inline" type="radio" name='privacy' value="0">Friends
                            <input class = "checkbox-inline" type="radio" name='privacy' value="1">Circles
                            <input class = "checkbox-inline" type="radio" name='privacy' value="2">Friends of Friends
                                
                        </div>
                        <ul class="list-inline"><li><a href="homepage.php?id=<?php echo $_SESSION['id']?>"><i class="glyphicon glyphicon-pencil"></i></a>  Make a new Post</li></ul>

                        <button class="btn btn-primary pull-right" type="submit"  name="submit">Upload Image</button>
                    </form>
                    </br>
                </div>
            </div>	

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
        
            <?php
            foreach($photolist_oneuser as $photo_id){
            if($index % 3 ==0) echo "<div class=\"row\">";
                $current_photo = $photolist_oneuser[$index];
                $sql_select = ("SELECT * FROM photo WHERE id_photo = '".$current_photo."' ORDER BY id_photo DESC");
                $stmt = $conn->query($sql_select);
                $row = $stmt->fetch();
                $index++;
            ?>

			
				<div class="col-md-4">
					<div class="thumbnail">
						<img class="center-block" style="max-width:100%;max-height:300px;"src="<?php echo $row["file_path"]?>">
                        <div class="caption">
							<h3>
								<?php echo $row["body"]?>
							</h3>

							<p>
                                
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-2">
                                            <form  action = 'photoViewer.php' method="post">
                                                <div class="input-group">
                                                    <input type="hidden" name="user_id" value="<?php echo $row["id_user"] ?>" /> 
                                                    <input type="hidden" name="photo_id" value="<?php echo $row["id_photo"] ?>" /> 
                                                    <input type="hidden" name="photoPath" value="<?php echo $row["file_path"] ?>" /> 
                                                    <input type="hidden" name="caption" value="<?php echo $row["body"] ?>" /> 
                                                </div>
                                                <button type="submit" name ="comment" class="btn btn-primary">Comment</button> 
                                                    
                                            </form>   
                                    </div>
                                    <div class="col-md-5">
                                    </div>
                                    <div class="col-md-5">
                                    <?php 
                                    $photoDeleteLink = "photoPage.php?profile=".$row["id_user"]."&id_del=".$row["id_photo"]."&del_path=".getcwd().$row["file_path"];
                                    echo "<a href=\"".$photoDeleteLink." \"><button class=\"btn btn-danger\" >Delete Photo</button></a><br><br>";
                                                                    
                                    ?>
                                    </div>
                                </div>
                            </div>   
                        <?php                            
                                //$photoViewLink = "photoViewer.php?id=".$row["id_user"]."&photoPath=".$row["file_path"]."&caption=".$row["body"]."&photo_id=".$row['id_photo']."&user=".$_SESSION["id"];
                                ?>
                            
</br>
  
							</p>
						</div>
					</div>
				</div>				
            <?php 
            if($index % 3 ==0)echo "</div>";
            }
            ?>
		</div>
        <div class="col-md-1">
        </div>
	</div>
</div>