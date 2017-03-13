<?php
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
function getCircleMember($circle_id,$conn){
        $memberList = [];
    
        $allposts_sql =  "SELECT id_circle, id_user FROM member WHERE id_circle='$circle_id'";
        $Postlist = $conn->query($allposts_sql);
        while($row = $Postlist->fetch()) {
            array_push($memberList , $row[1]);
            //print($row[1]);
        }
   
    return $memberList;
}
function getCircleList($user_id,$conn){
    $CircleList=[];
    $allposts_sql =  "SELECT id_circle, id_user FROM member WHERE id_user='$user_id'";
    $Postlist = $conn->query($allposts_sql);
    while($row = $Postlist->fetch()) {
        array_push($CircleList , $row[0]);
    }
    return $CircleList;
}
function allCircleMember($user_id,$conn){
    $users =[];
    $circlelist = getCircleList($user_id,$conn);
    
    foreach($circlelist as $circle ){
        $users=array_merge($users,getCircleMember($circle,$conn));
    }
    $users=array_unique($users);

    return $users;
}
function posts($allMembers,$conn){
    $list = [];
    foreach($allMembers as $member){
        $allposts_sql =  "SELECT id_post, id_user FROM post WHERE id_user='$member' and privacy_setting ='1'";
        $Postlist = $conn->query($allposts_sql);
        while($row = $Postlist->fetch()) {
            array_push($list , $row[0]);
        }
    }
    return $list;
}
function photos($allMembers,$conn){
    $list = [];
    foreach($allMembers as $member){
        $allposts_sql =  "SELECT id_photo, id_user FROM photo WHERE id_user='$member' and privacy_setting ='1'";
        $Postlist = $conn->query($allposts_sql);
        while($row = $Postlist->fetch()) {
            array_push($list , $row[0]);
        }
    }
    return $list;
}
function oneuser_posts($one,$conn){
        $list = [];
        $allposts_sql =  "SELECT id_post, id_user FROM post WHERE id_user='$one' and privacy_setting ='1'";
        $Postlist = $conn->query($allposts_sql);
        while($row = $Postlist->fetch()) {
            array_push($list , $row[0]);
        }
        return $list;
}
function oneuser_photos($one , $conn){
        $list = [];

        $allposts_sql =  "SELECT id_photo, id_user FROM photo WHERE id_user='$one' and privacy_setting ='1' ";
        $Postlist = $conn->query($allposts_sql);
        while($row = $Postlist->fetch()) {
            array_push($list , $row[0]);
        }

        return $list;
}
//$allMembers=allCircleMember($_SESSION["id"],$conn);
///$CirclePosts = posts($allMembers,$conn);
//$CirclePhotos = photos($allMembers , $conn);
?>
