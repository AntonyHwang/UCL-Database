<?php
    require'includes/config.php';
    try {
        $conn = new PDO( "mysql:host=$host;dbname=$db", $user, $pwd);
        $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        echo 'ok';
    }
    catch(Exception $e){
        die(var_dump($e));
    }
    
//delete comment
    if (isset($_GET['id_del_comment']) and $_GET['id_del_comment']!=null ){

        $post_del = $_GET['id_del_comment'];
        $del = "DELETE FROM post_comment  WHERE id_comment= ".$post_del;
        echo $del;
        $stmt = $conn->query($del);  
        if (!$stmt){

            die('deleting failed');
        }
        else {
            echo " deleted comment successfully<br>";
            $_GET['id_del_comment']=null;
            unset($_GET['id_del_comment']);
            if($_GET['last_page']=="myProfilePage.php")
            header("location:myProfilePage.php");
            else if($_GET['last_page']=="profile.php"){
                
                 header("location:profile.php?profile=".$_GET['profile']);
            }else if($_GET['last_page']=="homepage.php"){
                 header("location:homepage.php");
            }
        }

    }    
//delete post
    if (isset($_GET['id_del']) and $_GET['id_del']!=null ){
        $table = 'post';
        $post_del = $_GET['id_del'];
        $del = "DELETE FROM post WHERE id_post= ".$post_del;
        $stmt = $conn->query($del);  
        if (!$stmt){
            die('deleting failed');
        }
        else {
            echo " deleted successfully<br>";
            $_GET['id_del']=null;
            unset($_GET['id_del']);
            if($_GET['last_page']=="myProfilePage.php")
            header("location:myProfilePage.php");
            else{
                if($_GET['last_page']=="profile.php")
                 header("location:profile.php?profile=".$_GET['profile']);
            }
        }
    }  
//insert post
//insert comment
        if (isset($_GET['comment']) and $_GET['comment']!=null and isset($_GET['postid'])){
            //echo $_GET['body'];
            $table = 'post_comment';
            $body = $_GET['comment'];
            $postid = $_GET['postid'];
            $me = $_SESSION['id'];
            $sql = "INSERT INTO ".$table." (id_comment,id_post, id_user,body) VALUES (null, '$postid','$me','$body')";

            $stmt = $conn->query($sql);  
            if (!$stmt){
            die('post failed');
            }
            else {
                echo"New comment created successfully<br>";
                $_GET['body']=null;
                unset($_GET['body']);
                echo 'server';
                if($_GET['last_page']=="myProfilePage.php")
                header("location:myProfilePage.php");
                else if($_GET['last_page']=="profile.php"){
                    
                    header("location:profile.php?profile=".$_GET['profile']);
                }else if($_GET['last_page']=="homepage.php"){
                    header("location:homepage.php");
                }
            }
        }

?>