<?php
//DB connection info
$host = "localhost";
$user = "user name";
$pwd = "password";
$db = "blogster";
try {
    $conn = new PDO( "mysql:host=$host;dbname=$db", $user, $pwd);
    $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    $sql = "CREATE TABLE users(
	    id_user INT NOT NULL AUTO_INCREMENT,
	    PRIMARY KEY(id_user),
	    title VARCHAR(10),
	    first_Name VARCHAR(30),
	    last_Name VARCHAR(30),
	    email VARCHAR(30),
	    password VARCHAR(30))";
    $conn->query($sql);
        
    $sql = "CREATE TABLE friendships( 
	    id_friendship INT NOT NULL AUTO_INCREMENT),
        ADD FOREIGN KEY (id_user1) REFERENCES users(id_user),
        ADD FOREIGN KEY (id_user2) REFERENCES users(id_user))";
    $conn->query($sql);
        
    $sql = "CREATE TABLE friend_request(
   	    id _request INT NOT NULL AUTO_INCREMENT,
	    PRIMARY KEY(id_request),
        ADD FOREIGN KEY (id_from_user) REFERENCES users(id_user),
        ADD FOREIGN KEY (id_to_user) REFERENCES users(id_user))";
    $conn->query($sql);
    
    $sql = "CREATE TABLE posts(
	    id_post INT NOT NULL AUTO_INCREMENT,
	    timestamp TIMESTAMP,
        body VARCHAR(150),
	    privacy_setting INT,
        ADD FOREIGN KEY (id_user) REFERENCES users(id_user))";
    $conn->query($sql);
        
    $sql = "CREATE TABLE circles(
	    id_circle INT NOT NULL AUTO_INCREMENT,
	    name  VARCHAR(30),
        ADD FOREIGN KEY (id_owner) REFERENCES users(id_user))";
    $conn->query($sql);
    
    $sql = "CREATE TABLE members(
	    id_member INT NOT NULL AUTO_INCREMENT),
        ADD FOREIGN KEY (id_circle) REFERENCES circles(id_circle),
        ADD FOREIGN KEY (id_user) REFERENCES users(id_user))";
    $conn->query($sql);
        
    $sql = "CREATE TABLE messages(
	    id_message INT NOT NULL AUTO_INCREMENT,
	    timestamp TIMESTAMP,
	    body VARCHAR(150),
        ADD FOREIGN KEY (id_circle) REFERENCES circles(id_circle))";
    $conn->query($sql);

    $sql = "CREATE TABLE photos(   
        id_photo INT NOT NULL AUTO_INCREMENT,
        timestamp TIMESTAMP,
        body VARCHAR(150),
        privacy_setting INT,
        ADD FOREIGN KEY (id_user) REFERENCES users(id_user))";
    $conn->query($sql);

    $sql = "CREATE TABLE comments(
	Id_comment INT NOT NULL AUTO_INCREMENT,
	timestamp TIMESTAMP,
	body VARCHAR(150),
    ADD FOREIGN KEY (id_photo) REFERENCES photos(id_photo))";
    $conn->query($sql);

    $sql = "CREATE TABLE Admins(
        id_admin INT NOT NULL AUTO_INCREMENT,
        title VARCHAR(10),
	    first_name VARCHAR(30),
        last_name VARCHAR(30),
        email VARCHAR(30),
        password VARCHAR(30))";
    $conn->query($sql);
}
catch(Exception $e){
    die(print_r($e));
}
echo "<h3>Table created.</h3>";
?>