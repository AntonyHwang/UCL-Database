<!DOCTYPE html>
<html>
<style>
div {
    width: 300px;
    margin: auto;
    border: 1px solid red;
}
</style>
<body>
<div>
<h1>posts</h1>
<input></input>
<button>post</button>
<?php
//echo "Hello World!";

?>
<?php 
for ($x = 0; $x <= 10; $x++) {?>
	<div class = 'post'>
	 
	 <p>body<p>
	 <p>comment</p>
	</div>

<?php } 
?>
<div></div>
</body>
</html>
<?php
/* CREATE TABLE `post` (
  `id_post` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `body` varchar(150) NOT NULL,
  `privacy_setting` int(3) NOT NULL
) */
?>
