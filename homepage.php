<?php
    require'includes/config.php';
    include_once('header.php');
    if (isset($_GET['comment']) and $_GET['comment']!=null and isset($_GET['postid'])){
        $userid = $_SESSION['id'];
        //echo $_GET['body'];
        $table = 'post_comment';
        $body = $_GET['comment'];
        $postid = $_GET['postid'];
        $sql = "INSERT INTO ".$table."(id_comment,id_post, id_user,body) VALUES (null, '$postid','$userid','$body')";
        // if ($conn->query($sql) === TRUE) {
        //  echo"New post created successfully<br>";
        //  unset($_GET['body']);
        // } else {
        //  echo"Error:". $sql ."<br>". $conn->error;
        // }
        $stmt = $conn->query($sql);  
        if (!$stmt){
        die('post failed');
        }
        else {
            echo"New post created successfully<br>";
            $_GET['body']=null;
            unset($_GET['body']);
        }
    }
?>
<html>
    <style>

    .panel-body {
    background-color:white;
    }

    </style>
</html>

<h1>Your Own Posts</h1>

<?php 
    $sql = "SELECT id_post, id_user, body FROM post WHERE id_user = ".$_SESSION["id"].' ORDER BY timestamp DESC';
    $sql2= "SELECT first_name,surname FROM user WHERE id_user = ".$_SESSION["id"].' ';
    $result = $conn->query($sql);
    $result2= $conn->query($sql2);
    while($row2 = $result2->fetch()) {
        $username= $row2["first_name"]." ".$row2["surname"];
    }
    while($row = $result->fetch()) {
        $postid = $row["id_post"];
    ?>

<html> 
    <div class="panel-body">
    <h2><?php echo $username; ?>
    </h2>
    <paragraph>
    <?php echo $row["body"];?>
    </paragraph>
    <br>
    <div class="clearfix"></div>
    <hr>
</html>
    <?php
        //echo "id_post:" . $row["id_post"]. "</br> userid: " . $row["id_user"]. "</br>body " . $row["body"]. "<br>";
        $com = "SELECT id_post, id_user,id_comment, body,timestamp FROM post_comment WHERE id_post = ". $row["id_post"].' ORDER BY timestamp DESC';
        $res_com = $conn->query($com);
        while($row = $res_com->fetch()){
        echo "userid/name: " . $row["id_user"]. " " . $row["body"]. " at ".$row["timestamp"]."</br>";
        }
        echo "</br>";
    ?>
    <form  action = '#' method="get">
    <div class="input-group">
    <div class="input-group-btn">
    <button class="btn btn-default"><i class="glyphicon glyphicon-share"></i></button>
    </div>
    <input type="hidden" name="postid" value="<?php echo $postid; ?>" />
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

<h1>Friend's Post </h1>
<?php 
    $friends=array();
    $thisid = $_SESSION["id"];
    $sql = "SELECT * FROM `friendship` WHERE `id_friend1` =".$thisid. " OR `id_friend2` =".$thisid;
    $result = $conn->query($sql);
    $list = [];
    //get all friends
    $array = $result->fetchAll();

    foreach ($array as $value) {
    if($value[1]==$value[0]){
    countinue;
    }
    if($value[1]==$thisid){
        $friend = $value[0];
    }
    else{
        $friend = $value[1];
        }
     
    $sql = "SELECT * FROM `user` WHERE `id_user` =".$value[1];
    $result = $conn->query($sql);   
        if ($result->rowCount() > 0) {
        // output data of each row  
    while($row = $result->fetch(PDO::FETCH_ASSOC)) {

        // echo $row['first_name'].' '.$row['surname'];
        }
    }
   
    array_push($friends,$friend);       
    }
?>
<?php 
foreach ($friends as $current_id)
{
$sql = "SELECT id_post, id_user, body FROM post WHERE id_user = ".$current_id.' ORDER BY timestamp DESC';
$result = $conn->query($sql);
$sql2= "SELECT first_name,surname FROM user WHERE id_user = ".$current_id.' ';
$result2= $conn->query($sql2);
while($row2 = $result2->fetch()) {
    $username= $row2["first_name"]." ".$row2["surname"];
}
    while($row = $result->fetch()) {
        $postid = $row["id_post"];

        ?>
        <div class="panel-body">
        <h2>    
        <?php
        echo $username;
        ?>
        </h2>
        <paragraph>
        <?php
        echo $row["body"];
        ?>
        </paragraph>
        <div class="clearfix"></div>
        <hr>
    <?php
        //echo "id_post:" . $row["id_post"]. "</br> userid: " . $row["id_user"]. "</br>body " . $row["body"]. "<br>";
        $com = "SELECT id_post, id_user,id_comment, body,timestamp FROM post_comment WHERE id_post = ". $row["id_post"].' ORDER BY timestamp DESC';
        $res_com = $conn->query($com);
        while($row = $res_com->fetch()){
        echo "userid/name: " . $row["id_user"]. " " . $row["body"]. " at ".$row["timestamp"]."</br>";
        }
        echo "</br>";
    ?>

        <form  action = '#' method="get">
 <div class="input-group">
   <div class="input-group-btn">
   <button class="btn btn-default"><i class="glyphicon glyphicon-share"></i></button>
   </div>
   <input type="hidden" name="postid" value="<?php echo $postid; ?>" />
   <input type="text" name = 'comment' class="form-control" placeholder="Add a comment..">
 </div>
 </form>

        </div>
        <hr>
        <?php
    }

}

?>

 


<br>
<h1>Friend of Friend's Post </h1>
<br>
<h1>Circle's Post </h1>
<br>