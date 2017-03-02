<?php
	require ("includes/config.php");
	if (!empty($_GET["uploadButton"])) {
?>
	<form action="uploadPhoto.php" method="post" enctype="multipart/form-data">
    Select image to upload: <input type="file" name="fileToUpload" id="fileToUpload">
    Caption: <textarea name ="caption" rows="3" cols="30"></textarea>
    Privacy: 
	    <input type="radio" name="privacy" value="0">Public
		<input type="radio" name="privacy" value="1">Friends
		<input type="radio" name="privacy" value="2">Just you
    <input type="submit" value="Upload Image" name="submit">
</form>
<?php

}
if (!empty($_POST)) {
	$target_dir = "uploads/";
	$target_file = $target_dir . $_SESSION["id"]."/".basename($_FILES["fileToUpload"]["name"]);
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
	if ($_FILES["fileToUpload"]["size"] > 1000000) {
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
	            header('Location:photoPage.php');
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
