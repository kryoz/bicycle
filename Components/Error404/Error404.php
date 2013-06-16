<?php
/**
 * 404 error handler
 *
 * @author kubintsev
 */
namespace Components\Error404;

use Site\BaseController;

class Error404 extends BaseController
{
    public function index($args, $params)
    {
        $this->view
            ->setGlobalTemplate('404.php')
            ->loadTemplate("view_error404")
            ->render();
    }
}
