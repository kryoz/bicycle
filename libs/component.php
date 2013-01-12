<?php
/**
 * Base Model
 *
 * @author kryoz
 */
abstract class Component {
    public $name;
            
    public function __construct() {
        $this->name = get_class($this);
    }
}