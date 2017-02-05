<?php ob_start(); ?>
<form action="uploadPhoto.php" method="get" >
	<input type="hidden" name="uploadButton" value="upload">
  	<input type="submit" value="Upload Photo">
</form>

<?php 
	require ("includes/config.php");
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
            // Retrieve data
            $user_id = $_SESSION['id'];
            $sql_select = ("SELECT * FROM photo WHERE id_user = '".$user_id."' ORDER BY id_photo DESC");
            $stmt = $conn->prepare($sql_select);
            $stmt->execute();
            $results = $stmt->fetchAll();
            echo $_SESSION['id']. " " . $_SESSION['email']."\n";
            //Otherwise, render index/homepage. Set seesion to be logged in
            print_r($results);
        }
        catch(Exception $e) {
            die(var_dump($e));
        }
    
?>

