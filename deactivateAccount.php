<?php 
    require 'includes/config.php'; 
    include_once('header.php');
?>

<html>
    <body>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-4">
                        </div>
                        <div class="col-md-4">
                            <form method="POST" action=''>
                                <div class="form-group">
                                    <h1>
                                        Deactivate Account
                                    </h1>
                                    <p class="help-block">
                                        Once your account is deactivated. All your data and photos will be removed from our database.
                                    </p>
                                </div>
                                <button type="submit" class="btn btn-warning" name="delete_account" action="login.php">
                                    Deactivate Account
                                </button>
                            </form>
                        </div>
                        <div class="col-md-4">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>

<?php
    if (isset($_POST['delete_account'])) {
        $sql_delete = "DELETE FROM user WHERE id_user = ".$_SESSION['id'];
        echo $sql_delete;
        $stmt = $conn->prepare($sql_delete);
        $stmt->execute();
        unlink('uploads/'.$_SESSION['id']);
        header('location: login.php');
    }
?>