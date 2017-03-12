<?php
	require ("includes/config.php");
	include_once "header.php";
	if (!empty($_GET["uploadButton"])) {
?>
<style>
	.posts {

		margin: auto;
		
	}
	.wrapper{
		background-color:
	}

	.panel-body {
		background-color:white;
	}
</style>


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
			<ul class="list-inline"><li><a href="postPage.php?id=<?php echo $_SESSION['id']?>"><i class="glyphicon glyphicon-pencil"></i></a>  Make a new Post</li></ul>

			<button class="btn btn-primary pull-right" type="submit"  name="submit">Upload Image</button>
		</form>
	</div>
</div>	
    <!--<input type="submit" value="Upload Image" name="submit" style="align-right">--><?php

}
if (!empty($_POST)) {
	$target_dir = getcwd()."/uploads/";
	print_r($_FILES["fileToUpload"]);
	$target_file = $target_dir . $_SESSION["id"]."/".basename($_FILES["fileToUpload"]["name"]);
	//$target_file = $target_dir . $_SESSION["id"]."/".$result;
	echo $target_file;
	$uploadOk = 1;
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	// Check if image file is a actual image or fake image
	if(isset($_POST["submit"])) {
	    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
	    if($check !== false) {
	        echo "File is an image - " . $check["mime"] . ".";
	        $uploadOk = 1;
	    } else {
	        echo "File is not an image.";
	        $uploadOk = 0;
	    }
	}
	// Check if file already exists
	if (file_exists($target_file)) {
	    echo "Sorry, file already exists.";
	    $uploadOk = 0;
	}
	// Check file size
	if ($_FILES["fileToUpload"]["size"] > 10000000) {
	    echo "Sorry, your file is too large.";
	    $uploadOk = 0;
	}
	// Allow certain file formats
	if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
	    echo "Sorry, only JPG, JPEG & PNG files are allowed.";
	    $uploadOk = 0;
	}
	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
	    echo "Sorry, your file was not uploaded.";
	// if everything is ok, try to upload file
	} else {
			$date=new DateTime();
	$result = $date->format('Y-m-d-H-i-s');
	//$target_file = $target_dir . $_SESSION["id"]."/".basename($_FILES["fileToUpload"]["name"]);
	$target_file = $target_dir . $_SESSION["id"]."/".$result.".".$imageFileType;
	echo $target_file;
	
	    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
	        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
	        $host = "eu-cdbr-azure-west-a.cloudapp.net";
		    $user = "bd38b99b177044";
		    $pwd = "5e59f1c8";
		    $db = "blogster";
		    // Connect to database.
		    try {
		        $conn = new PDO( "mysql:host=$host;dbname=$db", $user, $pwd);
		        $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		    }
		    catch(Exception $e){
		        die(var_dump($e));
		    }
	        try {
	            // insert data
	            $user_id = $_SESSION["id"];
	            $caption = empty($_POST["caption"])? NULL: $_POST["caption"];
	            $privacy = empty($_POST["privacy"])? 0: $_POST["privacy"];
	            $sql_insert = "INSERT INTO photo (id_photo, id_user, file_path, timestamp, body, privacy_setting) VALUES (?,?,?,?,?,?)";
	            $stmt = $conn->prepare($sql_insert);
                $stmt->bindValue(1, NULL);
                $stmt->bindValue(2, $user_id);
                $stmt->bindValue(3, $target_file);
                $stmt->bindValue(4, NULL);
                $stmt->bindValue(5, $caption);
                $stmt->bindValue(6, $privacy);
	            $stmt->execute();
	            header('Location:photoPage.php?id='.$_SESSION['id'].'');
	        }
	        catch(Exception $e) {
	            die(var_dump($e));
	        }
	    } 
	    else {
	        echo "Sorry, there was an error uploading your file.";
	    }
	}
}
?>
