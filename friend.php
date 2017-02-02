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
<div class = 'left'>
<h1>Friends</h1>
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

//print_r($array);
$friends = [];
foreach ($array as $value) {
    echo 'id is '.$value[1].'</br>';
	array_push($friends,$value[1]);
	
	
}
echo count($friends).'friends</br>';
$ff = [];
//each my friend
foreach ($friends as $member) {
    
	
	$sql = "SELECT * FROM `friendship` WHERE `id_user` =".$member;
	$result = $conn->query($sql);
	echo 'user '.$member.' got '.$result->num_rows.'  friends</br>';
	if ($result->num_rows > 0) {
	$fri2 = $result->fetch_all();
	//add their all friends
	foreach ($fri2 as $row) {
    echo 'adding '.$row[1].'</br>';
	array_push($ff,$row[1]);
	//ff is friends s friends
	}
	}
	
}
//print_r($ff);
echo '</br>';
$ff=array_unique($ff);
$me =1;
//print_r($ff);
echo '</br>';
if (in_array($me, $ff)) 
{
	echo 'delete';
    unset($ff[array_search('124',$array)]);
}
else echo 'no';

//print_r($ff);
echo '</br>';
//print_r($list);
$remm = array_diff($ff, $friends);

$conn->close();

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
print_r($ranlist);
for ($x = 0; $x <= 10; $x++) {?>
	<div class = 'friend'>

	<p>friend<p>
	<p></p>
	</div>

<?php } 
?>
</div>
</body>
</html>

