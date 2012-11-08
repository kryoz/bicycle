<?php
/**
 * Base Model
 *
 * @author kubintsev
 */
abstract class Model_Base {
    protected static $name; // name of the component
    
    function setComponentName($name)
    {
        self::$name = $name;
    }
}

?>
