<?php
/**
 *
 * @author kryoz
 */
interface icache {
    function get($scope);
    function set($scope, $data = null);
    function flush($scope);
}

?>
