<?php

function show($data) {
    foreach ( $data as $i=>$item )
        echo "$i -> $item<br>";
}

if ( isset($title) )
    echo '<h1>'.$title.'</h1><br /><br />';
?>
This is new randomly generated array:<br>
<?
show($data);
?>
<br><br>
This is previous (cached) data array:<br>
<?
show($prev);
?>