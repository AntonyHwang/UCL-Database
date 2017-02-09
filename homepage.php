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
            // DB connection info
            //TODO: Update the values for $host, $user, $pwd, and $db
            //using the values you retrieved earlier from the Azure Portal.
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
            //Insert registration info
            if(!empty($_POST)) {
                try {
                    // Retrieve data
                    $fullname = $_POST['Name'];
                    $sql_select = "SELECT first_name, surname FROM (SELECT first_name,surname,CONCAT(first_name,' ',surname) AS 'Con_Name' FROM user) AS x WHERE Con_Name LIKE '%".$fullname."%'";

                    $stmt = $conn->query($sql_select);  
                    if (!$stmt){
                        die('No data');
                    }
                    else {
                        while($row = $stmt->fetch()){
                            echo "First Name: ".$row["first_name"]." Surname: ".$row["surname"];
                        }
                    }
                }
                catch(Exception $e) {
                    die(var_dump($e));
                }
            }
        ?>

    </body>
</html>