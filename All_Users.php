<?php 
    require 'includes/config.php'; 
    include_once('adminheader.php');
?>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-1">
            </div>
            <div class="col-md-11">
                
                <button type="button" class="btn btn-primary">
                    Import Profile XML
                </button>
            </div>
        </div>
        <br>
        <br>
        <div class="row">
            <div class="col-md-1">
            </div>
            <div class="col-md-10">
                <?php 
                    $sql= "SELECT id_user, first_name,surname FROM user ";
                    $result = $conn->query($sql);
                    $count = 1;

                    while($row = $result->fetch()) {
                        $count++;
                        $username= ucfirst($row["first_name"])." ".ucfirst($row["surname"]);
                        $user_id = $row["id_user"];
                        $profile_img = "./uploads/".$user_id."/profile.jpg";
                        $profile_link = "./profile.php?profile=".$row[id_user];
                                            
                        if ($count % 2 != 0) {
                ?>
                        <div class="row">
                        <?php
                        }
                        ?>
                            <div class="col-md-6">
                                <div class="col-md-12">
                                    <div class="col-md-6">
                                        <img src="<?php echo $profile_img ?>" class="img-rounded" style="width:50px; height 50px;" />
                                        <?php echo "   ".$username ?>
                                    </div>
                                    <div class="col-md-6">  
                                        <a href="./profile.php?profile=<?php echo $row[id_user]?>" class="btn btn-success">Profile Page</button></a>
                                    </div>
                                </div>
                                <div class="col-md-12">
        
                                </div>  
                            </div>
                        <?php
                        if ($count % 2 != 0) {
                        ?>
                        </div>
                        <br>
                        <br>
                        <?php
                        }
                        ?>
                    <?php
                    }
                    ?>
            </div>
            <div class="col-md-1">
            </div>
        </div>
    </div>
</body>

