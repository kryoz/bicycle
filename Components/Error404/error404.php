<?php
/**
 * 404 error handler
 *
 * @author kubintsev
 */
namespace Components\Error404;

class CError404 extends \Site\BaseController {

    function index($args, $params)
    {
        $view = $this->view;

        $view->setGlobalTemplate('404.php')->loadTemplate("view_error404")->render();
    }
}
