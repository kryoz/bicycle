<?php
/**
 * phpLESS wrapper
 *
 * @author kubintsev
 */
class Less {
    
    public function Compiler($file)
    {
        if ( !file_exists($file) )
            return false;
        
        require_once "lessc.inc.php";
        $less = new LessC();
        
        $css = $less->compileFile($file);
        
        $info = pathinfo($file);
        
        $fh = fopen(ROOT.'css'.DS.$info['basename'].'css', 'w');
        fwrite($fh, $css);
        fclose($fh);
    }
}

?>
