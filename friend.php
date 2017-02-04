<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "blogster";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
$thisid = 1;
$sql = "SELECT * FROM `friendship` WHERE `id_user` =".$thisid;
$result = $conn->query($sql);
$list = [];
//get all friends
$array = $result->fetch_all();
?>

<!DOCTYPE html>
<html>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script>

$(document).ready(function(){
	//var list = <?php $array?>;
    $("input").keydown(function(){
        $("input").css("background-color", "yellow");
    });
    $("input").keyup(function(){
        $("input").css("background-color", "pink");
        document.getElementById("demo").innerHTML = document.getElementById("searchTxt").value;
       
    });

	$("button").click(function(){
		
		document.getElementById("demo2").innerHTML = 1;//document.getElementById("searchTxt").value;
		
		//$(this).hide();
	});
	
});
</script>
</head>
<style>
div {
    
    
}
.left{
	float: left;
	margin: auto;
    border: 1px solid black;
}
.recm{
	float: right;
	margin: auto;
    border: 1px solid black;
}

</style>
<body>
<?php 
//I only use form at this moment
//sent the get name to current page 
//if isset the variable 
//run these code
?>
<div class = 'left'>
<h1>Friends</h1>
<form action="#" method="get">
<input name='name'></input>
<button type="submit">refresh</button>
</form>

<input id ="searchTxt"></input>
<button>search</button>
<p id="demo"></p>
<p id="demo2"></p>


<?php
//echo "num of result".count($array);
//echo "1st".$array[1]['id_user'];
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
		?>
		<div class = 'friend'>

		<p><p>		
		<?php
		array_push($list,$row);
		
        echo "id:" . $row["id_user"]. "</br> friend: " . $row["id_friend"]. "<br>";
		echo "time";
		echo "</br></br>";		
		?>
		
		
		<?php

    }
} else {
    echo "0 results";
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
			
			$sql = "SELECT * FROM `user` WHERE `id_user` =".$value[1];
			$result = $conn->query($sql);
			
			if ($result->num_rows > 0) {
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
    echo 'id: '.$value[1].' ';	
	$sql = "SELECT * FROM `user` WHERE `id_user` =".$value[1];
	$result = $conn->query($sql);	
	if ($result->num_rows > 0) {
    // output data of each row	
		while($row = $result->fetch_assoc()) {
			echo $row['first_name'].' '.$row['last_name'];
		}
	}
	echo '</br>';
	array_push($friends,$value[1]);		
	}
	
}



$list = [];
$array = $result->fetch_all();


echo count($friends).'friends</br>';
$ff = [];
//each my friend ,member,member f 's friends 
foreach ($friends as $member) {   	
	$sql = "SELECT * FROM `friendship` WHERE `id_user` =".$member;
	$result = $conn->query($sql);
	//echo 'user '.$member.' got '.$result->num_rows.'  friends</br>';
	if ($result->num_rows > 0) {
	$fri2 = $result->fetch_all();
	//add their all friends
		foreach ($fri2 as $row) {
			//echo 'adding '.$row[1].'</br>';
			array_push($ff,$row[1]);
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
<h1>recommendation</h1>
<?php 
//print_r($remm);
$start = 0;
$end = count($remm)-1;
$ranlist=[];
sort($remm);
//print_r($remm);
for($i = 0;$i <20;$i++){
	if($i==$end+1)break;
	$num  = rand($start, $end);	
	while(in_array($remm[$num], $ranlist) ){
		$tmp  = rand($start, $end);
		$num = $tmp;
	}
	array_push($ranlist,$remm[$num]);
}
//print_r($ranlist);
foreach ($ranlist as $user){
	echo $user; 
	echo '  ';
	$sql = "SELECT * FROM `user` WHERE `id_user` =".$user;
	$result = $conn->query($sql);
	
	if ($result->num_rows > 0) {
    // output data of each row
	
		while($row = $result->fetch_assoc()) {
			echo $row['first_name'].' '.$row['last_name'];
		}
	}
	echo '</br>';
}
$conn->close();
?>

</div>
</body>
</html>

