<?php
/**
 * Base Model
 *
 * @author kubintsev
 */
abstract class Model_Base {
    public $name;
            
    public function __construct() {
        $name = explode('_', get_class($this));
        $this->name = $name[1];
    }
}

?>
