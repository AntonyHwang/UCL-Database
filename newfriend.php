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
	$thisid = $_SESSION['id'];

	function getFriends($user,$conn){
		$friend_list =[];
		$getlist = "SELECT * FROM `friendship` WHERE `id_friend1` =".$user. " OR `id_friend2` =".$user;
		$result = $conn->query($getlist);
		$data = $result->fetchAll();		
		foreach ($data as $value){
			if($value[1]==$user){
				$friend = $value[0];
			}else{
				$friend = $value[1];
			}
			array_push($friend_list,$friend);
		}
		//echo $user," 's friend";
		//print_r($friend_list);
		return $friend_list;
	}
	function getFriendsFriends($friend_list,$conn){
		$all = [];
		foreach($friend_list as $one_friend){
			$onelist = getFriends($one_friend,$conn);
			$all = array_merge($onelist, $all);
		}
		//print_r($all);
		return $all;
	}
    $thisid = $_SESSION['id'];
    $f1 = getFriends($thisid,$conn);
    $f2 = getFriendsFriends($f1,$conn);


    echo "<div class=\"container-fluid\">\n"; 

    echo "	<div class=\"row\">\n"; 
    echo "		<div class=\"col-md-6\">\n"; 
    echo "			<h3 class=\"text-left\">\n"; 
    echo "				my firends\n"; 
    echo "			</h3>\n"; 
    echo "		</div>\n"; 
    echo "		<div class=\"col-md-6\">\n"; 
    echo "			<h3 class=\"text-right\">\n"; 
    echo "				people you may know\n"; 
    echo "			</h3>\n"; 
    echo "		</div>\n"; 
    echo "	</div>\n";


    echo "	<div class=\"row\">\n"; 
    //left part
    echo "		<div class=\"col-md-6\">\n"; 
    foreach($f1 as $friend){
        $sql = "SELECT * FROM `user` WHERE `id_user` =".$friend;
        $result = $conn->query($sql);
        $row = $result->fetch(PDO::FETCH_ASSOC);
        $name = $row['first_name'].' '.$row['surname'];
        
                //one friend
    echo "			<div class=\"row\">\n"; 
    echo "				<div class=\"col-md-6\">\n"; 
    //echo " the pic and name";
    echo "<img src= \"./uploads/".$friend."/profile.jpg\" alt=\"Profile Pic\" style=\"width:75px; height 75px;\">";
    echo "<a href=\"./profile.php?profile=".$friend."\"> <b>".$name."</b></a>";
    echo "				</div>\n"; 
    echo "				<div class=\"col-md-6\">\n"; 
    //echo " the delete button";
    echo "<a href=\"./friend.php?id_del_friend=".$friend." \"><button class=\"btn btn-warning\" >unfriend</button></a>";

    
    echo "				</div>\n"; 
    echo "			</div>\n"; 
    //end of one friend
    echo "<hr>";
    }

    echo "		</div>\n"; 

    //right part
    echo "		<div class=\"col-md-6\">\n"; 
    foreach($f2 as $friend){
        $sql = "SELECT * FROM `user` WHERE `id_user` =".$friend;
        $result = $conn->query($sql);
        $row = $result->fetch(PDO::FETCH_ASSOC);
        $name = $row['first_name'].' '.$row['surname'];
        
                //one friend
    echo "			<div class=\"row\">\n"; 
    echo "				<div class=\"col-md-6\">\n"; 
    //echo " the pic and name";
    echo "<img src= \"./uploads/".$friend."/profile.jpg\" alt=\"Profile Pic\" style=\"width:75px; height 75px;\">";
    echo "<a href=\"./profile.php?profile=".$friend."\"> <b>".$name."</b></a>";
    echo "				</div>\n"; 
    echo "				<div class=\"col-md-6\">\n"; 
    //echo " the delete button";
    echo "<a href=\"./friend.php?id_del_friend=".$friend." \"><button class=\"btn btn-success\" >addfriend</button></a>";

    
    echo "				</div>\n"; 
    echo "			</div>\n"; 
    //end of one friend
    echo "<hr>";
    }

    echo "		</div>\n"; 
    echo "	</div>\n"; 
    echo "</div>\n";
?>

