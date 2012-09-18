<?php
function show($data) {
    foreach ( $data as $i=>$item )
        echo "$i -> $item<br>";
}

if ( isset($title) )
    echo '<h1>'.$title.'</h1><br /><br />';
?>
This example shows if framework works correctly.<br>
This is <b>index</b> component which you can find in <em><?=COMPONENTS.'index'?></em><br>
In first run random array is generated then it put into cache. Refresh page to to see cached result in second array.<br>
Please note that <em><?=CACHEDIR?></em> must have write enabled rights (777) in case of absence <i>APC</i> cache.
<br><br>
You can also see <a href="<?=URLROOT?>index/second">alternate action</a> for <b>index</b> component<br><br>
This is new randomly generated array:<br>
<?php show($data); ?>
<br><br>
This is previous (cached) data array:<br>
<?php show($prev); ?>