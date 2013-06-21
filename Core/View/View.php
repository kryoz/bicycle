<?php
/**
 * Description of View
 *
 * @author kubintsev
 */
namespace Core\View;

use Core\Debug;
use Core\View\TemplateNotFoundException;

class View
{
    protected $component_path;
	protected $globalTemplate;
	protected $template;
	protected $vars;

    /**
     *
     * @param string $tmpl the name of global template
     */
    public function __construct($tmpl = null)
    {
        if ($tmpl === null) {
            $this->globalTemplate = 'global_tmpl.php';
        } else {
            $this->globalTemplate = $tmpl;
        }
        $this->setPath(COMPONENTS);
    }

    /**
     * Sets new global template
     * @param string $path
     * @return \Core\View\View
     */
    public function setGlobalTemplate($tmpl)
    {
        $this->globalTemplate = strtolower($tmpl);
        return $this;
    }

    /**
     * Sets component path
     * @param string $path
     * @return \Core\View\View
     */
    public function setPath($path)
    {
        $this->component_path = $path;
        return $this;
    }

    /**
     * Sets template vars
     * @param array $vars
     * @return \Core\View\View
     */
    public function loadVars(array $vars)
    {
        $this->vars = $vars;
        return $this;
    }

    /**
     * Sets template name
     * @param string $tmpl
     * @param string $component
     * @return \Core\View\View
     */
    public function loadTemplate($tmpl, $component = null)
    {
        $this->template = strtolower($tmpl);

        if ($component)
            $this->setPath(COMPONENTS . $component . DS);

        return $this;
    }

    /**
     * Render without displaying (for emailing for ex.)
     * @return string
     * @throws TemplateNotFoundException|\Exception
     */
    public function prepare()
    {
        try {
            $template = $this->component_path . $this->template . '.php';

            if (!file_exists($template)) {
                throw new TemplateNotFoundException('"' . $template . '" not found!');
            }

            if (is_array($this->vars))
                extract($this->vars);

            ob_start();
            include $template;

            $content = ob_get_contents();
            ob_end_clean();

            if (DEBUG) {
                Debug::log(Debug::getmem());
                $content .= Debug::getlog();
            }

            return $content;
        } catch (\Exception $e) {
            ob_end_clean();
            throw $e;
        }
    }

    /**
     * Render the view. If $raw = true then render goes without global template
     * @param bool $raw
     * @throws TemplateNotFoundException
     */
    public function render($raw = false)
    {
        $content = $this->prepare();

        header('Content-Type: text/html; charset=' . CODEPAGE);
        header('P3P: CP="CAO PSA OUR"');

        if (is_array($this->vars))
            extract($this->vars);

        if (!$raw) {
	       	if (!file_exists(GLOBALVIEWS . $this->globalTemplate)) {
		        throw new TemplateNotFoundException;
	        }
            include_once GLOBALVIEWS . $this->globalTemplate;
        }
        else {
            echo $content;
	    }
    }
}
