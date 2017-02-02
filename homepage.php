<html>
    <body>
        <form action="#" method="post">
            FirstName: <input type="text" name="first_name"><br>
            LastName: <input type="text" name="surname"><br>
            <button class="btn btn-default" type="submit">
                <span aria-hidden="true" class="glyphicon glyphicon-log-in"></span>
                Find
            </button>
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
                    $first_name = $_POST['first_name'];
                    $surname = $_POST['surname'];
                    $sql_select = "SELECT * FROM user WHERE first_name = '".$first_name."'";
                    $stmt = $conn->query($sql_select);
                    if (!$stmt){
                        die('No data');
                    }
                    while($row = fetch_assoc($stmt)){
                        echo $row[first_name];
                    }
                }
                catch(Exception $e) {
                    die(var_dump($e));
                }
            }
        ?>

    </body>
</html>