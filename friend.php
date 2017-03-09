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
 	function countFriends($user,$conn){
		
		$getlist = "SELECT * FROM `friendship` WHERE `id_friend1` =".$user. " OR `id_friend2` =".$user;
		$result = $conn->query($getlist);
		$row_count = $result->rowCount();
        return $row_count;

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
 	function getRecommedation($friend_list,$number){
         if(count($friend_list<$number))return $friend_list;
         else{
             $start = 0;
             $end = count($friend_list)-1;
             $ranlist=[];
             sort($friend_list);
             for($i = 0;$i <$number;$i++){
                if($i==$end+1) break;
                $num  = rand($start, $end);	
                while(in_array($remm[$num], $ranlist) ){
                    $tmp  = rand($start, $end);
                    $num = $tmp;
                }
                array_push($ranlist,$remm[$num]);
             }
             return $ranlist;
                     
         }

	}
   function mightknow($friend_list,$me,$conn){
       $list = [];
       foreach($friend_list as $user){
           if(count(commonfriends($user,$me,$conn))>2){
               array_push($list,$user);

           }
       }
       return $list;

   }
   function commonfriends($a,$b,$conn){
       $list1 = getFriends($a,$conn);
       $list2 = getFriends($b,$conn);
       //print_r(array_intersect($list1, $list2)) ;
       return array_intersect($list1, $list2);

   }
    //handle delete friend
    if (isset($_GET['id_del_friend']) and $_GET['id_del_friend']!=null){
        $deletefriend =  "DELETE FROM friendship WHERE id_friend1 = ".$_GET['id_del_friend'].' and id_friend2 = '.$thisid;
        $stmt = $conn->query($deletefriend);
        $deletefriend =  "DELETE FROM friendship WHERE id_friend2 = ".$_GET['id_del_friend'].' and id_friend1 = '.$thisid;
        $stmt = $conn->query($deletefriend);
        header("location:friend.php");
    } 
    //accept request
    if (isset($_GET['p_friend']) and $_GET['p_friend']!=null and $_GET['mod']=='accept'){
        echo 'next friend is '.$_GET['p_friend'];
        $f = $_GET['p_friend'];
        $me = $_SESSION['id'];
        $addfriend = "INSERT INTO friendship (id_friend1, id_friend2)VALUES ($me, $f)";
        $stmt = $conn->query($addfriend);
        //delete the request
        //$deleteREQ =  "DELETE FROM friend_request WHERE id_from_user = ".$f.' and id_to_user = '.$me;
        $request_id = $_GET['id_request'];
        $deleteREQ =  "DELETE FROM friend_request WHERE id_request = "."$request_id";
        
        $stmt = $conn->query($deleteREQ);
        $_GET['p_friend']=null; 
        header("location:friend.php");
    }
    //delete request
    if (isset($_GET['mod']) and $_GET['mod']=='delete' and $_GET['id_request']!=null){
        $deleteREQ =  "DELETE FROM friend_request WHERE id_request = ".$_GET['id_request'];
        $stmt = $conn->query($deleteREQ);
    }         
    $thisid = $_SESSION['id'];
    $f1 = getFriends($thisid,$conn);
    $f2 = getFriendsFriends($f1,$conn);
    $f2=array_unique($f2);
    if (in_array($thisid, $f2)) unset($f2[array_search($thisid,$f2)]);


    $wait2remm = array_diff($f2, $f1);
    //get my  friend list and friendlist of one user_error
    //count the common friends  
    $remm = getRecommedation($wait2remm,20);
    //$remm = mightknow($wait2remm,$thisid,$conn);


    echo "<div class=\"container-fluid\">\n"; 
    //NEW 
    echo "	<div class=\"row\">\n";
    echo "		<div class=\"col-md-1\">\n"; 
    echo "		</div>\n"; 
    echo "		<div class=\"col-md-10\">\n"; 
    //title 
    echo "	<div class=\"row\">\n"; 
    echo "		<div class=\"col-md-6\">\n"; 
    echo "			<h1 class=\"text-left\">\n"; 
    echo "				Friends\n"; 
    echo "			</h1>\n"; 
    echo "		</div>\n"; 
    echo "		<div class=\"col-md-6\">\n"; 
    echo "			<h1 class=\"text-left\" align=\"left\">\n"; 
    echo "				People You May Know\n"; 
    echo "			</h1>\n"; 
    echo "		</div>\n"; 
    echo "	</div>\n";


    echo "	<div class=\"row\">\n"; 
    //left part
    echo "		<div class=\"col-md-6\">\n"; 
    foreach($f1 as $friend){
        $sql = "SELECT * FROM `user` WHERE `id_user` =".$friend;
        $result = $conn->query($sql);
        $row = $result->fetch(PDO::FETCH_ASSOC);
        $name = ucfirst($row['first_name']).' '.ucfirst($row['surname']);
        
                //one friend
    echo "			<div class=\"row\">\n"; 
    echo "				<div class=\"col-md-6\">\n"; 
    //echo " the pic and name";
    echo "<img src= \"./uploads/".$friend."/profile.jpg\" alt=\"Profile Pic\" style=\"width:75px; height 75px;\">";
    echo "<a href=\"./profile.php?profile=".$friend."\"> <b>".$name."</b></a>";
    $n= countFriends($friend,$conn);
    echo ""." $n"." friends";
    echo "				</div>\n"; 
    echo "				<div class=\"col-md-6\">\n"; 
    //echo " the delete button";
    echo "<a href=\"./friend.php?id_del_friend=".$friend." \"><button class=\"btn btn-warning\" >Unfriend</button></a>";

    
    echo "				</div>\n"; 
    echo "			</div>\n"; 
    //end of one friend
    echo "<hr>";
    }

    echo "<h1>Friend Request</h1>";

    $friendRequest = "SELECT id_from_user, id_to_user,id_request FROM `friend_request` WHERE `id_to_user` =$thisid" ;

	$result = $conn->query($friendRequest);

	$waitinglist = $result->fetchAll();
    $followers =[];
    foreach($waitinglist as $row){
        array_push($followers,$row[0]);
    }//print_r($followers);

//start of friendrequests
    foreach($waitinglist as $row){
        $friend = $row[0];
        $follower = $row[1];
        //echo $friend;
        //echo $row[1];
        
        $sql = "SELECT * FROM `user` WHERE `id_user` =".$friend;
        $result = $conn->query($sql);
        $row1 = $result->fetch(PDO::FETCH_ASSOC);
        $name = ucfirst($row1['first_name']).' '.ucfirst($row1['surname']);
        
                //one friend
    echo "			<div class=\"row\">\n"; 
    echo "				<div class=\"col-md-6\">\n"; 
    //echo " the pic and name";
    echo "<img src= \"./uploads/".$friend."/profile.jpg\" alt=\"Profile Pic\" style=\"width:75px; height 75px;\">";
    echo "<a href=\"./profile.php?profile=".$friend."\"> <b>".$name."</b></a>";
    echo "				</div>\n"; 
    echo "				<div class=\"col-md-6\">\n"; 
    //echo " the delete button";
    echo '<form  style="display: inline-block;">';	
    echo '  <input type="hidden" name="p_friend" value="'."$friend".'" />';
    echo '  <input type="hidden" name="id_request" value="'."$row[2]".'" />';
    echo '  <input type="hidden" name="mod" value="accept" />';
    echo '  <button class="btn btn-success" type="submit">Accept</button>';
    echo '</form>';
    echo '&nbsp &nbsp &nbsp';
    echo '<form  style="display: inline-block;">';
    echo '  <input type="hidden" name="id_request" value="'.$row[2].'" />';	
    echo '  <input type="hidden" name="mod" value="delete" />';	
    echo '  <button class="btn btn-danger" type="submit">Delete</button>';    
    echo '</form>';
    
    echo "				</div>\n"; 
    echo "			</div>\n"; 
    //end of one friend
    echo "<hr>";
    }
//end of fq
    echo "		</div>\n"; 

    //right part
    echo "		<div class=\"col-md-6\">\n"; 
    foreach($remm as $friend){
        
        $sql = "SELECT * FROM `user` WHERE `id_user` =".$friend;
        $result = $conn->query($sql);
        $row = $result->fetch(PDO::FETCH_ASSOC);
        $name = ucfirst($row['first_name']).' '.ucfirst($row['surname']);
        
                //one friend
    echo "			<div class=\"row\">\n"; 
    echo "				<div class=\"col-md-6\">\n"; 
    //echo " the pic and name";
    echo "                 <img src= \"./uploads/".$friend."/profile.jpg\" alt=\"Profile Pic\" style=\"width:75px; height 75px;\">";
    echo "                 <a href=\"./profile.php?profile=".$friend."\"> <b>".$name."</b></a>";
    echo "				</div>\n"; 
    echo "				<div class=\"col-md-6\">\n"; 
    //echo " the delete button";
    echo "<a href=\"./profile.php?profile=".$row[id_user]."\" class=\"btn btn-Primary\">View Profile</button></a>\n";  
    echo "				</div>\n"; 
    echo "			</div>\n"; 
    //end of one friend
    echo "<hr>";
    }

    echo "		</div>\n"; 
    //end of right part

    echo "	</div>\n"; 


    echo "      </div>\n";
    echo "		<div class=\"col-md-1\">\n";    
    echo "      </div>\n";
    echo "</div>\n";


    echo "</div>\n";
?>

