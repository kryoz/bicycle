<?php
if ( isset($title) )
    echo '<h1>'.$title.'</h1><br /><br />';


foreach ( $data as $i=>$item )
{
    echo "$i -> $item<br>";
}
?>
            