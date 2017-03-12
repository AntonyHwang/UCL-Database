<?php
//To test on your local machine, modify your MAMP/MySQL user/pwd
$host = "eu-cdbr-azure-west-a.cloudapp.net";
$user = "bd38b99b177044";
$pwd = "5e59f1c8";
$db = "blogster";


try {
    $conn = new PDO( "mysql:host=$host", $user, $pwd);
    echo "Connected Successfully";
    $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    $sql = '
CREATE TABLE `admin` (
  `first_name` varchar(30) NOT NULL,
  `last_name` varchar(30) NOT NULL,
  `email` varchar(30) NOT NULL,
  `password` varchar(40) NOT NULL DEFAULT '',
  `id_admin` int(5) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id_admin`)
)

CREATE TABLE `circle` (
  `id_circle` int(10) NOT NULL AUTO_INCREMENT,
  `id_user` int(10) NOT NULL,
  `name` varchar(30) NOT NULL,
  PRIMARY KEY (`id_circle`),
  KEY `id_user_idx` (`id_user`),
  CONSTRAINT `circle_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE
)

CREATE TABLE `friend_request` (
  `id_request` int(10) NOT NULL AUTO_INCREMENT,
  `id_from_user` int(10) NOT NULL,
  `id_to_user` int(10) NOT NULL,
  `date_sent` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_request`),
  KEY `id_user_idx` (`id_to_user`,`id_from_user`),
  KEY `id_from_user` (`id_from_user`),
  CONSTRAINT `friend_request_ibfk_1` FOREIGN KEY (`id_from_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `friend_request_ibfk_2` FOREIGN KEY (`id_to_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE
)

CREATE TABLE `friendship` (
  `id_friend1` int(1) NOT NULL,
  `id_friend2` int(1) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `id_friend1` (`id_friend1`),
  KEY `id_friend2` (`id_friend2`),
  CONSTRAINT `friendship_ibfk_1` FOREIGN KEY (`id_friend1`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `friendship_ibfk_2` FOREIGN KEY (`id_friend2`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE
) 

CREATE TABLE `member` (
  `id_member` int(10) NOT NULL AUTO_INCREMENT,
  `id_circle` int(10) NOT NULL,
  `id_user` int(10) NOT NULL,
  `date_joined` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_member`),
  KEY `id_user` (`id_user`),
  KEY `id_circle` (`id_circle`),
  CONSTRAINT `member_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `member_ibfk_2` FOREIGN KEY (`id_circle`) REFERENCES `circle` (`id_circle`) ON DELETE CASCADE ON UPDATE CASCADE
) 

CREATE TABLE `message` (
  `id_message` int(11) NOT NULL AUTO_INCREMENT,
  `id_circle` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `body` varchar(150) NOT NULL DEFAULT '',
  PRIMARY KEY (`id_message`),
  KEY `id_circle` (`id_circle`),
  KEY `id_user` (`id_user`),
  CONSTRAINT `message_ibfk_1` FOREIGN KEY (`id_circle`) REFERENCES `circle` (`id_circle`) ON DELETE CASCADE ON UPDATE CASCADE
) 

CREATE TABLE `photo` (
  `id_photo` int(10) NOT NULL AUTO_INCREMENT,
  `id_user` int(10) NOT NULL,
  `file_path` varchar(150) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `body` varchar(150) DEFAULT NULL,
  `privacy_setting` int(3) NOT NULL,
  PRIMARY KEY (`id_photo`),
  KEY `id_user_idx` (`id_user`),
  CONSTRAINT `photo_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE
) 

CREATE TABLE `photo_comment` (
  `id_comment` int(10) NOT NULL AUTO_INCREMENT,
  `id_photo` int(10) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `body` varchar(150) NOT NULL,
  `id_user` int(10) NOT NULL,
  PRIMARY KEY (`id_comment`),
  KEY `id_photo_idx` (`id_photo`),
  KEY `id_user` (`id_user`),
  CONSTRAINT `photo_comment_ibfk_1` FOREIGN KEY (`id_photo`) REFERENCES `photo` (`id_photo`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `photo_comment_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE
) 

CREATE TABLE `post` (
  `id_post` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `body` varchar(150) NOT NULL,
  `privacy_setting` int(3) NOT NULL,
  PRIMARY KEY (`id_post`),
  KEY `id_user` (`id_user`),
  CONSTRAINT `post_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE
) 

CREATE TABLE `post_comment` (
  `id_comment` int(10) NOT NULL AUTO_INCREMENT,
  `id_post` int(10) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `body` varchar(150) NOT NULL,
  `id_user` int(10) NOT NULL,
  PRIMARY KEY (`id_comment`),
  KEY `id_photo_idx` (`id_post`),
  KEY `id_user` (`id_user`),
  CONSTRAINT `post_comment_ibfk_1` FOREIGN KEY (`id_post`) REFERENCES `post` (`id_post`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `post_comment_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE
) 

CREATE TABLE `user` (
  `id_user` int(1) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(30) NOT NULL,
  `surname` varchar(30) NOT NULL,
  `email` varchar(30) NOT NULL,
  `password` varchar(50) NOT NULL DEFAULT '',
  `gender` varchar(10) NOT NULL,
  `dob` date NOT NULL,
  `privacy_setting` int(1) NOT NULL,
  PRIMARY KEY (`id_user`)
) 
'
;
    $conn->query($sql);
}
catch(Exception $e){
    die(print_r($e));
}
echo "<h3>Tables created.</h3>";
?>
