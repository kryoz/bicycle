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
        return $this;
    }
    
    /**
     * Sets component path
     * @param string $path
     */
    function setPath($path)
    {
        $this->component_path = $path;
        return $this;
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
    function loadTemplate($tmpl, $component = null)
    {
        $this->template = strtolower($tmpl);
        
        if ($component)
            $this->setPath(COMPONENTS.$component.DS);
        
        return $this;
    }
    
    /**
     * Render without displaying (for emailing for ex.)
     * @return string
     */
    function prepare()
    {
        try {
            
            $template = $this->component_path.$this->template.'.php';

            if ( !file_exists($template) )
            {
                throw new Exception('"'.$template.'" not found!');
            }
            
            if (is_array($this->vars))
                extract($this->vars);
            
            ob_start();
            require $template;
            
            $content = ob_get_contents();
            ob_end_clean();
            
            if (DEBUG) {
                DEBUG::log(DEBUG::getmem());
                $content .= DEBUG::getlog();
            }
            
            return $content;
        }
        catch (Exception $e)
        {
            Debug::log(__CLASS__.'::'.__FUNCTION__.':<br>Line:'.$e->getLine().'<br>Message: '.$e->getMessage().'<br>');
        }
    }
    /**
     * Render the view. If $raw = true then render goes without global template
     * @param bool $raw
     * @throws Exception
     */
    function render($raw = false)
    {
        $content = $this->prepare();
        
        header('Content-Type: text/html; charset='.CODEPAGE);
        header('P3P: CP="CAO PSA OUR"');
        
        if (is_array($this->vars))
                extract($this->vars);
        
        if (!$raw)
            require_once GLOBALVIEWS.$this->global_template;
        else
            echo $content;
    }
}
