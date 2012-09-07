<?php
if ( isset($title) )
    echo '<h1>'.$title.'</h1><br /><br />';
?>
<table width="80%" align="center">
    <tbody>
        <tr>
            <td valign="top" width="50%">
<?php
$column = 1;
$datacount = count($data);

foreach ( $data as $letter=>$group )
{
    if ( ord($letter) > ord('Н') && $column == 1 && $datacount > 1)
    {
        $column++;
        echo '</td><td valign="top" width="50%">';
    }
    
    echo "<h4>$letter</h4>";
    
    foreach ($group as $item)
        echo '<a href="'.Controller_index::makeURL($item['CountryCode']).'">Авиабилеты в '.$item['CountryName']."</a><br>";
}
?>
            </td>
        </tr>
    </tbody>
</table>