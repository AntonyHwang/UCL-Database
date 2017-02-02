<!DOCTYPE html>
<html>
<style>
div {
    
    
}
.left{
	float: left;
	margin: auto;
    border: 1px solid black;
}
.recm{
	float: right;
	margin: auto;
    border: 1px solid black;
}

</style>
<body>
<div class = 'left'>
<h1>Friends</h1>
<input></input>
<button>search</button>
<?php
//echo "Hello World!";

?>
<?php 
for ($x = 0; $x <= 10; $x++) {?>
	<div class = 'friend'>
	 
	 <p>friend<p>
	 <p></p>
	</div>

<?php } 
?>
</div>
<div class = 'recm'>
<h1>recommendation</h1>
<?php 
for ($x = 0; $x <= 10; $x++) {?>
	<div class = 'friend'>
	 
	 <p>friend<p>
	 <p></p>
	</div>

<?php } 
?>
</div>
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
