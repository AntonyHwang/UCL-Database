<?php 
    require 'includes/config.php'; 
    include_once('adminheader.php');
     


?>


<?php

?>

<?php 
//pho

$photo_user_allow_seen=[];

    $sql = "SELECT id_photo, id_user FROM photo ORDER BY timestamp DESC";
    $result = $conn->query($sql);
    while($row = $result->fetch()) {
        $postid = $row["id_photo"];
        array_push($photo_user_allow_seen,$postid);
    }



?>





<?php 




$index = 0;
$row=0;


               

?>
<div class="container-fluid">
	<div class="row">
        <div class="col-md-1">
        </div>
		<div class="col-md-10">
            <h1>All  Photos</h1>    
             <hr>
		</div>
        <div class="col-md-1">
        </div>
	</div>
</div>

<div class="container-fluid">
	<div class="row">
        <div class="col-md-1">
        </div>
		<div class="col-md-10">
        
            <?php
            foreach($photo_user_allow_seen as $photo_id){
            if($index % 3 ==0) echo "<div class=\"row\">";
                $current_photo = $photo_user_allow_seen[$index];
                $sql_select = ("SELECT * FROM photo WHERE id_photo = '".$current_photo."' ORDER BY id_photo DESC");
                $stmt = $conn->query($sql_select);
                $row = $stmt->fetch();
                $index++;

                $postOwner = $row["id_user"];
                $getpostowner = "SELECT first_name,surname FROM user WHERE id_user = ".$postOwner.' ';
                $getpostowernresult = $conn->query($getpostowner);
                $namerow = $getpostowernresult->fetch();
                $username= ucfirst($namerow["first_name"])." ".ucfirst($namerow["surname"]);                    
            ?>

			
				<div class="col-md-4">
					<div class="thumbnail">
						<img class="center-block" style="max-width:100%;max-height:300px;"src="<?php echo $row["file_path"]?>">
                         <div class="row">
                        <div class="col-md-12" align="centre">
                        <a href="<?php   echo "./profile.php?profile=$postOwner";?>  ">  <?php echo $username;?></a>
                        <?php// echo "   ".$username ;
                        
                        ?>
                        </div>
                        </div>  
                        <div class="caption">
							<h3>
								<?php echo $row["body"]?>
							</h3>

                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-2">
                                            <form  action = 'photoViewer.php?path=<?php echo $row["file_path"] ?>' method="post">
                                                <div class="input-group">
                                                    <input type="hidden" name="user_id" value="<?php echo $row["id_user"] ?>" /> 
                                                    <input type="hidden" name="photo_id" value="<?php echo $row["id_photo"] ?>" /> 
                                                    <input type="hidden" name="photo_path" value="<?php echo $row["file_path"] ?>" /> 
                                                    <input type="hidden" name="photo_caption" value="<?php echo $row["body"] ?>" /> 
                                                </div>
                                                <button type="submit" name ="comment" class="btn btn-primary">Comment</button> 
                                                    
                                            </form>   
                                    </div>
                                    <div class="col-md-5">
                                    </div>
                                    <div class="col-md-5">
                                    <?php 
                                    $photoDeleteLink = "photoPage.php?profile=".$row["id_user"]."&id_del=".$row["id_photo"]."&del_path=".dirname(__FILE__)."/".$row["file_path"];
                                    echo "<a href=\"".$photoDeleteLink." \"><button class=\"btn btn-danger\" >Delete Photo</button></a><br><br>";
                                                                    
                                    ?>
                                    
                                    </div>
                                </div>
                            </div>   
						</div>
                        </br></br>
					</div>
				</div>				
            <?php 
            if($index % 3 ==0)echo "</div>";
            }
            ?>
		</div>
        <div class="col-md-1">
        </div>
	</div>
</div>
