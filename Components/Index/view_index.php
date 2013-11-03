<?php
use Core\ServiceLocator\Locator;

function show($data) {
    foreach ( $data as $i=>$item )
        echo "$i -> $item<br>";
}

if ($userData = Locator::get('sessionManager')->getUser()) {
    $user = 'user "'.$userData['user'].'"';
}
?>
<h1>Hi, <?=$user ?: 'anonymous'?></h1>
<? if ($userData) { ?>
    <a href="/?c=index&p=logout">Logout?</a>
<? } ?>

<br /><br />
This example shows if framework works correctly.<br>
This is <b>index</b> component which you can find in <em><?=SETTINGS_COMPONENTS_DIR.'index'?></em><br>
In first run random array is generated then it put into cache. Refresh page to to see cached result in second array.<br>
Please note that <em><?=SETTINGS_CACHE_DIR?></em> must have write enabled rights (777) in case of absence <i>APC</i> cache.<br>
<br>
FormToken code is
<pre>
    <?=htmlspecialchars($token)?>
</pre>
Type demo/demo and submit to check form verification token and authorize
<form action="<?=SETTINGS_URLROOT?>index.php?c=index&p=tokencheck" method="POST">
    <input type="text" name="user">
    <input type="text" name="password">
    <input type="submit">
    <?=$token?>
</form>
<br>
You can also try <a href="<?=SETTINGS_URLROOT?>index.php?c=index&p=second">alternate action</a> for <b>index</b> component<br><br>
or <a href="<?=SETTINGS_URLROOT?>index.php?c=test">Test component</a><br><br>
This is new randomly generated array:<br>
<?php show($data); ?>
<br><br>
This is previous (cached) data array:<br>
<?php show($prev); ?>