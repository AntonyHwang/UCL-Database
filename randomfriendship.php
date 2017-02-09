<?php
$servername ="localhost";
$username ="root";
$password ="";
$dbname ="blogster";
?>
<?php
$start = 0;
$end = 100-1;
$ranlist=[];

//print_r($remm);
for($i = 0;$i <1000;$i++){
	if($i==$end+1)break;
	$num1  = rand($start, $end);
	$num2  = rand($start, $end);
    $fship = [$num1,$num2];	
	while(in_array($fship, $ranlist) ){
	$num1  = rand($start, $end);
	$num2  = rand($start, $end);
    $fship = [$num1,$num2];	
	}
	array_push($ranlist,$fship);
	array_push($ranlist,[$fship[1],$fship[0]]);
}
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed:". $conn->connect_error);
} 
foreach ($ranlist as $line){
	
	
$sql = "INSERT INTO friendship (id_user, id_friend)
VALUES ('$line[0]', '$line[1]')";
if ($conn->query($sql) === TRUE) {
    echo"New record created successfully";
} else {
    echo"Error:". $sql ."<br>". $conn->error;
}


}

$conn->close();
?>