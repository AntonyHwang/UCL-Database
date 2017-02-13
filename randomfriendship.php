<?php
            $host = "eu-cdbr-azure-west-a.cloudapp.net";
            $user = "bd38b99b177044";
            $pwd = "5e59f1c8";
            $db = "blogster";
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
	$fship2 = [$num1,$num2];	
	while(in_array($fship, $ranlist) or in_array($fship2, $ranlist)){
	$num1  = rand($start, $end);
	$num2  = rand($start, $end);
    $fship = [$num1,$num2];	
	}
	array_push($ranlist,$fship);
	//array_push($ranlist,[$fship[1],$fship[0]]);
}
// Create connection
            try {
                $conn = new PDO( "mysql:host=$host;dbname=$db", $user, $pwd);
                $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
				echo 'ok';
            }
            catch(Exception $e){
                die(var_dump($e));
            }
			
foreach ($ranlist as $line){
	
	
$sql = "INSERT INTO friendship (id_user, id_friend)
VALUES ('$line[0]', '$line[1]')";
$stmt = $conn->query($sql);  
	if (!$stmt){
		die('new friendship failed');
		}
	else {
	echo"New friendship added successfully<br>";
	}

}


?>