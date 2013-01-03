<?php
/**
 *
 * @author kryoz
 */
interface icache {
    function get($scope);
    function set($scope, $data = null, $ttl = null);
    function flush($scope, $regular = false);
}

?>
