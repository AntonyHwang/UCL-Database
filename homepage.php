
<?php
    require'includes/config.php';
    include_once('header.php');
?>
<html>
    <body>
        <form action="homepage.php" method="post" align="center">
            <fieldset>
                <div class="form-group">
                    <input autocomplete="off" autofocus class="form-control" name="Name" placeholder="Name" type="text"/>
                </div>
                <br>
                <div class="form-group">
                    <button class="btn btn-default" type="submit">
                        <span aria-hidden="true" class="glyphicon glyphicon-log-in"></span>

                        Search
                    </button>
                </div>
            </fieldset>
        </form>

        <?php
            //Insert registration info
            $matchedFirstName=array();  
            $matchedSurname=array();
            $matchedId=array();
            $matchedPassword=array();
            $counter=0;
        
            if(!empty($_POST)) {
                try {
                    // Retrieve data
                    $fullname = $_POST['Name'];
                    $sql_select = "SELECT first_name, surname, id_user, password FROM (SELECT first_name,surname,id_user, password,CONCAT(first_name,' ',surname) AS 'Con_Name' FROM user) AS x WHERE Con_Name LIKE '%".$fullname."%'";

                    $stmt = $conn->query($sql_select);  
                    if (!$stmt){
                        die('No data');
                    }
                    else {
                        while($row = $stmt->fetch()){
                            $matchedFirstName[$counter] = $row[first_name];
                            $matchedSurname[$counter] = $row[surname];
                            $matchedId[$counter]=$row[id_user];
                            $matchedPassword[$counter]=$row[password];
                            $counter++;
                        }
                    }
                }
                catch(Exception $e) {
                    die(var_dump($e));
                }
            }
            
        ?>
        <h1>
            <?php
            for ($pos=0; $pos < $counter; $pos++) {  

                echo "<img src=\"./uploads/".$matchedId[$pos].$matchedPassword[$pos]."/profile.jpg\" alt=\"Profile Pic\" style=\"width:120px;height 120px;\">";
                echo $matchedFirstName[$pos]." ".$matchedSurname[$pos];
                echo "<button type=\"submit\" class=\"btn btn-default\">Add Friend</button>";
                echo "<br>";
            }
        ?>
    </h1>



    </body>
</html>