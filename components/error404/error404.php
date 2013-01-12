<?php
/**
 * 404 error handler
 *
 * @author kubintsev
 */
class CError404 extends BaseController {
    
    function index($args, $params) 
    {
        $view = $this->view;

        $view->setGlobalTemplate('404.php')->loadTemplate("view_error404")->render();
    }
}
