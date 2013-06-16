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
Please note that <em><?=CACHEDIR?></em> must have write enabled rights (777) in case of absence <i>APC</i> cache.<br>
<br>
FormToken code is
<pre>
    <?=htmlspecialchars($token)?>
</pre>
Type anything and submit to check form verification token
<form action="<?=URLROOT?>index.php?c=index&p=tokencheck" method="POST">
    <input type="text" name="sometext">
    <input type="submit">
    <?=$token?>
</form>
<br>
You can also try <a href="<?=URLROOT?>index.php?c=index&p=second">alternate action</a> for <b>index</b> component<br><br>
or <a href="<?=URLROOT?>index.php?c=test">Test component</a><br><br>
This is new randomly generated array:<br>
<?php show($data); ?>
<br><br>
This is previous (cached) data array:<br>
<?php show($prev); ?>