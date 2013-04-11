<?php
if ( isset($title) )
    echo '<h1>'.$title.'</h1><br /><br />';
?>
Success!<br>
This page shows the ability to use controller methods as part of URL parameters<br>
There is an option to put controller logic into index() method of your controller class for flexibility.<br>
$args = <?=$args?><br>
$params = <?=$params?>