<?php
    require'includes/config.php';
    include_once('header.php');
// Create connection
try {
	$conn = new PDO( "mysql:host=$host;dbname=$db", $user, $pwd);
	$conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	//echo 'ok';
}
catch(Exception $e){
	die(var_dump($e));
}
$_SESSION['id']=71;
$thisid = $_SESSION['id'];
echo $thisid."whattttttt~~";
$friendlist = "SELECT * FROM `friendship` WHERE `id_friend1` =".$thisid. " OR `id_friend2` =".$thisid;
$result = $conn->query($friendlist);
$list = [];
//get all friends
$array = $result->fetchAll();
?>

<!DOCTYPE html>
<html>

<script>

$(document).ready(function(){
/*    $(".user").click(function(){
        
		id = $(this);
		//alert(id.html());
		window.location.href = "profile.php"+'?profile='+id.html();//$("#test").val();
    });	*/
	



	
});
</script>
</head>
<style>
div {
    
    
}
.left{
	float: left;
	width:500px;
	margin: auto;
    border: 1px solid blue;
}
.recm{
	float: right;
	margin: auto;
    border: 1px solid blue;
}

</style>
<body>

<div class = 'left'>
<h1>Friends</h1>


<input id ="searchTxt"></input>
<button>search</button>
<p id="demo"></p>
<p id="demo2"></p>

<?php 
//I only use form at this moment
//sent the get name to current page 
//if isset the variable 
//run these code

//check the friend request
//INSERT INTO friend_request (id_from_user, id_to_user)
$friendRequest = "SELECT id_from_user, id_to_user FROM `friend_request` WHERE `id_to_user` =$thisid" ;

$result = $conn->query($friendRequest);

$waitinglist = $result->fetchAll();
foreach($waitinglist as $row){
	echo '<form>';
	echo $row[0].'want to be your friend';
	
	echo '<input type="hidden" name="p_friend" value="'.  $row[0].'" />';
	echo '<button class="btn btn-primary pull-right" type="submit">accept</button>';
	echo '<button class="btn btn-primary pull-right" type="submit">delete</button>';
	
	echo '</form>';
	
}

?>
<?php
//echo "num of result".count($array);
//echo "1st".$array[1]['id_friend1'];
if ($row_count = $result->rowCount()> 0) {
    // output data of each row
    while($row = $result->fetch()) {
		?>
		<div class = 'friend'>

		<p><p>		
		<?php
		array_push($list,$row);
		
        echo "id:" . $row["id_friend1"]. "</br> friend: " . $row["id_friend2"]. "<br>";
		echo "time";
		echo "</br></br>";		
		?>
		
		
		<?php

    }
} else {
    echo "0 results";
}
if (isset($_GET['p_friend']) and $_GET['p_friend']!=null){
	echo 'next friend is '.$_GET['p_friend'];
	$f = $_GET['p_friend'];
	$me = $_SESSION['id'];
	$addfriend = "INSERT INTO friendship (id_friend1, id_friend2)VALUES ($me, $f)";
	$stmt = $conn->query($addfriend); 
	$_GET['p_friend']=null; 
	//header("location:friend.php");
}
//print_r($array) 
//print all friends or search result
$friends = [];
//print all friends
//show only searchId matches
//should first get id by full name ,not yet implemented
$searchID = 100;
if (isset($_GET['name']) and $_GET['name']!=null){
	echo 'searching----';
	echo '</br>';
	foreach ($array as $value) {
		if ($value[1] ==$searchID ){
			echo 'id: '.$value[1].' ';
			
			$sql = "SELECT * FROM `user` WHERE `id_friend1` =".$value[1];
			$result = $conn->query($sql);
			
			if ($result->rowCount() > 0) {
			// output data of each row
			
				while($row = $result->fetch_assoc()) {
					echo $row['first_name'].' '.$row['last_name'];
				}
			}
			echo '</br>';
		}

	array_push($friends,$value[1]);
	
	
	}	
}else{
	foreach ($array as $value) {
	if($value[1]==$thisid){
		$friend = $value[0];
	}else{
		$friend = $value[1];
	}


	$sql = "SELECT * FROM `user` WHERE `id_user` =".$friend;
	$result = $conn->query($sql);	
	
	echo 'id: '.$friend.' ';
	echo '<div class="container-fluid">';
	echo '<div class="row">';

	echo '<div class="col-md-6">';
	echo "<img src= \"./uploads/".$friend."/profile.jpg\" alt=\"Profile Pic\" style=\"width:75px; height 75px;\">";
    
		if ($result->rowCount() > 0) {
    // output data of each row	

		while($row = $result->fetch(PDO::FETCH_ASSOC)) {
			$name = $row['first_name'].' '.$row['surname'];
			if ($row['first_name']==null or $row['surname']==null)
			echo "<a href=\"./profile.php?profile=".$user."\"> <b>undefined</b></a>";
			else 
			
			echo "<a href=\"./profile.php?profile=".$user."\"> <b>".$name."</b></a>";
			//echo $row['first_name'].' '.$row['surname'];
		}
	}
	echo '</div>';
	echo '<div class="col-md-6">';
	echo 'column on right</div>';
	echo '</div>';
	echo '</div>';
	?>

	
		
		
		
		

<?php
	array_push($friends,$friend);		
	}
	
}



$list = [];
$array = $result->fetchAll();


echo count($friends).'friends</br>';
$ff = [];
//each my friend ,member,member f 's friends 
foreach ($friends as $member) {   	
	$sql = "SELECT * FROM `friendship` WHERE `id_friend1` =".$member.' or id_friend2='.$member ;

	$result = $conn->query($sql);
	//echo 'user '.$member.' got '.$result->num_rows.'  friends</br>';
	if ($result->rowCount() > 0) {
	$fri2 = $result->fetchAll();
	//add their all friends
		foreach ($fri2 as $row) {
			//echo 'adding '.$row[1].'</br>';
			if($row[0]==$member)
			array_push($ff,$row[1]);
			else array_push($ff,$row[0]);
			//ff is friends s friends
		}
	}
	
}
//print_r($ff);
echo '</br>';
$ff=array_unique($ff);
//remove depulicate
$me =1;
//print_r($ff);
//remove this user from the list
echo '</br>';
if (in_array($me, $ff)) 
{
	echo 'delete';
    unset($ff[array_search($me,$array)]);
}
else echo 'no';

//print_r($ff);
echo '</br>';
//print_r($list);
//remove my friend from  all friends of friends
$remm = array_diff($ff, $friends);



?>
<p id="res">here is the matching</p>

</div>
<div class = 'recm'>
<h1>people you may know</h1>
<?php 
//print_r($remm);
$start = 0;
$end = count($remm)-1;
$ranlist=[];
sort($remm);
//print_r($remm);
for($i = 0;$i <20;$i++){
	if($i==$end+1) break;
	$num  = rand($start, $end);	
	while(in_array($remm[$num], $ranlist) ){
		$tmp  = rand($start, $end);
		$num = $tmp;
	}
	array_push($ranlist,$remm[$num]);
}
//print_r($ranlist);
foreach ($ranlist as $user){
	//echo $_SESSION['id'];
	echo '<span class=\'user\'>'.$user.'</span>'; 
	echo '  ';
	$sql = "SELECT * FROM `user` WHERE `id_user` =".$user ;
	$result = $conn->query($sql);
	
	echo "<img src= \"./uploads/".$user."/profile.jpg\" alt=\"Profile Pic\" style=\"width:75px; height 75px;\">";
    //echo "<a href=\"./profile.php?profile=".$user."\"> click</a>";            
	if ($result->rowCount() > 0) {
    // output data of each row	    
		while($row = $result->fetch(PDO::FETCH_ASSOC)) {
			$name = $row['first_name'].' '.$row['surname'];
			if ($row['first_name']==null or $row['surname']==null)
			echo "<a href=\"./profile.php?profile=".$user."\"> <b>undefined</b></a>";
			else 
			
			echo "<a href=\"./profile.php?profile=".$user."\"> <b>".$name."</b></a>";
		}
	}else{
		echo "<a href=\"./profile.php?profile=".$user."\"> <b>undefined</b></a>";		
	}
	echo '</br>';
}
//$conn->close();
?>

</div>
</body>
</html>

