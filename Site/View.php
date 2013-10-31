<?php
/**
 * Description of View
 *
 * @author kubintsev
 */
namespace Site\View;

use Core\Debug;

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
        $this->setPath(SETTINGS_COMPONENTS_DIR);
    }

	/**
	 * @param $tmpl
	 * @return $this
	 */
	public function setGlobalTemplate($tmpl)
    {
        $this->globalTemplate = strtolower($tmpl);
        return $this;
    }

	/**
	 * @param $path
	 * @return $this
	 */
	public function setPath($path)
    {
        $this->component_path = $path;
        return $this;
    }

	/**
	 * @param array $vars
	 * @return $this
	 */
	public function loadVars(array $vars)
    {
        $this->vars = $vars;
        return $this;
    }

	/**
	 * @param $tmpl
	 * @param null $component
	 * @return $this
	 */
	public function loadTemplate($tmpl, $component = null)
    {
        $this->template = strtolower($tmpl);

        if ($component)
            $this->setPath(SETTINGS_COMPONENTS_DIR . $component . DS);

        return $this;
    }

	/**
	 * @return string
	 * @throws \Exception
	 * @throws TemplateNotFoundException
	 */
	public function prepare()
    {
        $template = $this->component_path . $this->template . '.php';
        $content = '';

        if (file_exists($template)) {
            if (is_array($this->vars))
	            extract($this->vars);

            ob_start();
            try {
                include $template;
            } catch (\Exception $e) {
	            Debug::log($e);
            }

            $content = ob_get_contents();
            ob_end_clean();
        } else {
            Debug::log('"' . $template . '" not found!');
        }

        if (SETTINGS_IS_DEBUG) {
            $content .= Debug::getlog();
        }

        return $content;
    }

	/**
	 * @param bool $renderWithGlobal
	 * @throws \Exception
	 */
	public function render($renderWithGlobal = false)
    {
        $content = $this->prepare();

        header('Content-Type: text/html; charset=' . CODEPAGE);
        header('P3P: CP="CAO PSA OUR"');

        if (is_array($this->vars))
            extract($this->vars);

        if (!$renderWithGlobal) {
	       	if (!file_exists(SETTINGS_GLOBALVIEWS_DIR . $this->globalTemplate)) {
		        throw new \Exception(__CLASS__ . '::' . __FUNCTION__ . ': No global view found '.$this->globalTemplate);
	        }
	        include_once SETTINGS_GLOBALVIEWS_DIR . $this->globalTemplate;
	        return;
        }

        echo $content;
    }
}
