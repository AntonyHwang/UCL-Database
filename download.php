<?php
    header('Content-Disposition: attachment; filename="'.$_GET['profile'].'.xml"');
    header('Content-type: text/xml');
    readfile('xml_export/'.$_GET['profile'].'.xml');
    unlink('xml_export/'.$_GET['profile'].'.xml');
    header("./profile.php?profile=".$_GET['profile']);
?>