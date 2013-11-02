<?php
/**
 * Description of View
 *
 * @author kubintsev
 */
namespace Site;

class View
{
    protected $componentPath;
	protected $globalTemplate;
	protected $template;
	protected $vars;

    /**
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
        $this->componentPath = $path;
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
        $template = $this->componentPath . $this->template . '.php';

        if (file_exists($template)) {
            if (is_array($this->vars))
	            extract($this->vars);

            ob_start();
            try {
                include $template;
            } catch (\Exception $e) {

            }

            $content = ob_get_contents();
            ob_end_clean();
            return $content;
        }

        throw new \Exception($template.' not found!');
    }

	/**
	 * @param bool $renderWithGlobal
	 * @throws \Exception
	 */
	public function render($renderWithGlobal = false)
    {
        $content = $this->prepare();

        header('Content-Type: text/html; charset='.SETTINGS_CODEPAGE);
        header('P3P: CP="CAO PSA OUR"');

        if (is_array($this->vars))
            extract($this->vars);

        if (!$renderWithGlobal) {
	       	if (!file_exists(SETTINGS_GLOBALVIEWS_DIR . $this->globalTemplate)) {
		        throw new \Exception('No global view found '.$this->globalTemplate);
	        }
	        include_once SETTINGS_GLOBALVIEWS_DIR . $this->globalTemplate;
	        return;
        }

        echo $content;
    }
}
