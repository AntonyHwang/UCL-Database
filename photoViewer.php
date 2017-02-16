<style>
    div.individualPhoto {
    	text-align: center;
    	margin: auto;
        width: 500px;
        border: 5px solid blue;
    }
    img.individualPhoto {
        width: 420px;
    }
    figcaption {
        height: 100px;
        border: 5px solid yellow;
    }
</style>
<?php 
    ob_start();
    require ("includes/config.php");
    include_once "header.php";
    if (!empty($_GET)) {
    	$user_id = $_GET['id'];
    	$photo = $_GET['photoPath'];
    	$caption = $_GET['caption'];
    	?>
    	<div class="individualPhoto">
            <img class="individualPhoto" src="<?php echo $photo?>">
            <figcaption><?php echo $caption?></figcaption>
        </div>
        <?php
        //Add an add-comment feature, as well as render comments
        //in photopage, maybe show number of comments or few comments
        //if they want ot add from photopage, link them to photoViewer
    }

?>