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
    ob_start();
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
        $host = "eu-cdbr-azure-west-a.cloudapp.net";
        $user = "bd38b99b177044";
        $pwd = "5e59f1c8";
        $db = "blogster";
        // Connect to database.
        $conn;
        try {
            $conn = new PDO( "mysql:host=$host;dbname=$db", $user, $pwd);
            $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        }
        catch(Exception $e){
            die(var_dump($e));
        }
        echo $_GET["profile"];
        print_r($_POST);
        $post_del = $_GET["id_del"];
        $sql_del = "DELETE FROM photo  WHERE id_photo = ? ";
        echo $sql_del;
        $stmt = $conn->prepare($sql_del);  
        $stmt->bindValue(1, $post_del);
        if (!$stmt->execute()){

            die('deleting failed');
        }
        else {
            echo " deleted photo successfully<br>";
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
        echo $_POST["profile"];
        print_r($_POST);
        $post_del = $_POST["id_del"];
        $sql_del = "DELETE FROM photo  WHERE id_photo = ? ";
        echo $sql_del;
        $stmt = $conn->prepare($sql_del);  
        $stmt->bindValue(1, $post_del);
        if (!$stmt->execute()){

            die('deleting failed');
        }
        else {
            echo " deleted photo successfully<br>";
            unlink($_GET["del_path"]);
            echo $_GET["del_path"];
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
		<div class="col-md-9">
        <h1>My Photos</h1>
		</div>
		<div class="col-md-1">
        <?php
            $photoUploadLink = "uploadPhoto.php?uploadButton=upload";
            echo "<div style=\"float:right;\""."><a href=\"".$photoUploadLink." \"><button class=\"btn btn-primary\" >Upload Photo</button></a></div><br><br>";
        ?>        
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
                                <?php
                                $photoViewLink = "photoViewer.php?id=".$row["id_user"]."&photoPath=".$row["file_path"]."&caption=".$row["body"]."&photo_id=".$row['id_photo']."&user=".$_SESSION["id"];
                                ?>
                            
								<a class="btn btn-primary" href="<?php echo $photoViewLink;?>">comment</a> 
                                <?php 
                                $photoDeleteLink = "photoPage.php?profile=".$row["id_user"]."&id_del=".$row["id_photo"]."&del_path=".$row["file_path"];
                                echo "<a href=\"".$photoDeleteLink." \"><button class=\"btn btn-warning\" >Delete Photo</button></a><br><br>";
                                                                  
                                ?>
  
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