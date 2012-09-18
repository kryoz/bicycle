<?php
/**
 * Description of View
 *
 * @author kubintsev
 */
class View {
    private $component_path;
    private $global_template;
    private $template;
    private $vars;
    
    /**
     * 
     * @param string $tmpl the name of global template
     */
    function __construct($tmpl = null) 
    {
        if ( $tmpl === null)
            $this->global_template = 'global_tmpl.php';
        else
            $this->global_template = $tmpl;
            
    }

    /**
     * Sets new global template
     * @param string $path
     */
    function setGlobalTemplate($tmpl)
    {
        $this->global_template = strtolower($tmpl);
    }
    
    /**
     * Sets component path
     * @param string $path
     */
    function setPath($path)
    {
        $this->component_path = $path;
    }
    
    /**
     * Sets template vars
     * @param array $vars
     * @return \View
     */
    function loadVars(array $vars)
    {
        $this->vars = $vars;
        return $this;
    }
    
    /**
     * Sets template name
     * @param string $tmpl
     * @return \View
     */
    function loadTemplate($tmpl)
    {
        $this->template = strtolower($tmpl);
        
        return $this;
    }
    
    /**
     * Render without displaying (for emailing for ex.)
     * @return string
     */
    function prepare()
    {
        try {
            
            $template = $this->component_path.'view_'.$this->template.'.php';

            if ( !file_exists($template) )
            {
                throw new Exception('"'.$template.'" not found!');
            }
            
            extract($this->vars);
            
            ob_start();
            require $template;
            
            $content = ob_get_contents();
            ob_end_clean();
            
            return $content;
        }
        catch (Exception $e)
        {
            Debug::log('VIEW CLASS error!<br>Line:'.$e->getLine().'<br>Message: '.$e->getMessage().'<br>');
        }
    }
    /**
     * Render the view. If $is_ajax = true then render goes without global template
     * @param bool $is_ajax 
     * @throws Exception
     */
    function render($is_ajax = false)
    {
        $content = $this->prepare();
        if (DEBUG) {
                DEBUG::log(DEBUG::getmem());
            }
            
        $debug = DEBUG::getlog();
        
        header('Content-Type: text/html; charset='.CODEPAGE);
        header('P3P: CP="CAO PSA OUR"');
        
        if (!$is_ajax)
            require_once GLOBALVIEWS.$this->global_template;
        else
            echo $content;
    }
}
